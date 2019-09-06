package com.gmcp.gm.ui.main.upgrade.downunit;


import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.NetworkUtils;
import com.gmcp.gm.common.utils.Timber;

import java.io.Closeable;
import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.io.InterruptedIOException;
import java.net.SocketTimeoutException;
import java.util.List;

import okhttp3.Headers;
import okhttp3.OkHttpClient;
import okhttp3.Response;
import okhttp3.ResponseBody;


/**
 * Created by Nereus on 2017/8/16.
 */

public class DownloadTask implements Runnable,Comparable<DownloadTask>{
    private FileDownloaderListener listener;
    private ThreadDispatcher dispatcher;
    private OkHttpClient client;
    private DownloadIntent intent;
    private DownloadProgressCache downloadProgressCache = new DownloadProgressCache();
    private int tryCount;
    private int currentCount = 0;
    public DownloadTask(){}
    public DownloadTask(OkHttpClient client,
                        FileDownloaderListener listener,
                        ThreadDispatcher dispatcher,
                        DownloadIntent intent,
                        int tryCount){
        this.client = client;
        this.intent = intent;
        this.listener = listener;
        this.dispatcher = dispatcher;
        this.tryCount = tryCount;
        currentCount = 0;
    }
    @Override
    public void run()
    {
        onBegin();
        if(check())
        {
            try {
                process();
            }
            catch (Exception e)
            {
                Timber.e(e,"下载文件发现异常");
                onError();
            }

        }
        else
        {
            onError();
        }

    }

    private void process()
    {
        long sofarSize = 0;//getLocalFileSize();
        //支持断点续传
        okhttp3.Request request = new okhttp3.Request.Builder()
//                .addHeader("RANGE","bytes=" + sofarSize+"-")
                .addHeader("Accept-Encoding", "identity")
                .url(intent.url)
                .get()
                .tag(intent.packageName)
                .build();
        Response response = null;
        try {
            response = client.newCall(request).execute();
            if(null == response)
            {
                Timber.e("下载文件时响应为null");
                onError();
                return;
            }
            int responseCode = response.code();
            Timber.d("下载文件响应码:%d",responseCode);
            if (responseCode == 404 || responseCode >= 500) {
                Timber.e("下载文件时失败响应码为%d",responseCode);
                onError();
                return;
            }
            listHeaders(response.headers());
            convertResponse(response,sofarSize);
        }
        catch (Throwable throwable)
        {
            if(throwable instanceof SocketTimeoutException)
            {
                Timber.e("SocketTimeoutException 连接超时,可以在这里重试");
            }
            Timber.e(throwable,"下载文件时异常");

            if(NetworkUtils.isConnected())
            {
                currentCount+=1;
                if(currentCount<tryCount)
                {
                    Timber.i("下载文件%s时重新尝试",intent.fileName);
                    process();
                }
                else
                {
                    onError();
                }
            }
            else
            {
                onError();
            }
        }
    }

    public void convertResponse(Response response, long lastSofarSize) throws Throwable {
        InputStream bodyStream = null;
        byte[] buffer = new byte[2*1024];
        FileOutputStream fileOutputStream = null;
        try {
            ResponseBody body = response.body();
            if (body == null){
                Timber.e("下载文件时响应体为null");
                onError();
                return;
            }
            boolean append = false;
            if(0 != lastSofarSize)
            {
                append = true;
            }
            bodyStream = body.byteStream();
            long totalSize = body.contentLength()+lastSofarSize;
            long sofarSize = lastSofarSize;
            Timber.d(Thread.currentThread().getName()
                    +":download sofarSize:%d totalSize:%d diff:%d append:%s",
                    sofarSize,totalSize,
                    totalSize-sofarSize,String.valueOf(append));
            File file = new File(intent.dir,intent.tempFileName);
            int len;
            fileOutputStream = new FileOutputStream(file,append);
            while ((len = bodyStream.read(buffer)) != -1) {
                fileOutputStream.write(buffer, 0, len);
                sofarSize += len;

                DownloadProgress progress = new DownloadProgress();
                progress.totalSize = totalSize;
                progress.sofarSize = sofarSize;
                progress.packagename = intent.packageName;
                progress.percent = (int)(sofarSize*100/totalSize);
                //Timber.d("下载文件%s进度%s",intent.packageName,progress.toString());
                if(downloadProgressCache.shouldPublish(progress.percent))
                {
                    onProgress(progress);
                }
            }
            fileOutputStream.flush();
            //Timber.e("服务器文件:%d,本地文件:%d diff:%d",totalSize,file.length(),totalSize-file.length());
            if(!renameFile())
            {
                Timber.e("第一次 重命名文件失败 %s ---> %s",intent.tempFileName,intent.fileName);
                if(!renameFile())
                {
                    Timber.e("第二次 重命名文件失败 %s ---> %s",intent.tempFileName,intent.fileName);
                    onError();
                }
            }
            else
            {
                onComplete();
            }

        }
        catch (InterruptedIOException ee)
        {
            Timber.e(ee,"线程中断");
            onError();
        }
        finally {
            buffer=null;
            closeQuietly(bodyStream);
            closeQuietly(fileOutputStream);
        }
    }


    private void closeQuietly(Closeable closeable) {
        if (closeable == null) return;
        try {
            closeable.close();
        } catch (Exception e) {
            Timber.e(e,"关闭IO时异常");
        }
    }

    private boolean renameFile()
    {
        File file = new File(intent.dir,intent.tempFileName);
        if(file.exists())
        {
            File dest  = new File(intent.dir,intent.fileName);
            return file.renameTo(dest);
        }

        return false;
    }
    private void onBegin()
    {
        Timber.d("下载文件%s开始",intent.packageName);
        dispatcher.runOnUi(new Runnable() {
            @Override
            public void run() {
                listener.onBegin(intent.packageName);
            }
        });
    }
    private void onProgress(DownloadProgress progress)
    {
        Timber.d("下载文件%s进度%s",intent.packageName,progress.toString());
        dispatcher.runOnUi(new ProgressRunnable(progress));
    }

    private void onError()
    {
        Timber.d("下载文件%s失败",intent.packageName);
        dispatcher.runOnUi(new Runnable() {
            @Override
            public void run() {
                listener.onError(intent.packageName,0);
            }
        });
    }
    private void onComplete()
    {
        Timber.d("下载文件%s完毕",intent.packageName);
        dispatcher.runOnUi(new Runnable() {
            @Override
            public void run() {
                listener.onComplete(intent.packageName);
            }
        });
    }

    @Override
    public int compareTo(DownloadTask task) {
        if(null == intent || null == task.intent)
        {
            return -1;
        }
        return intent.packageName.compareTo(task.intent.packageName);
    }


    class ProgressRunnable implements Runnable
    {
        private DownloadProgress progress;
        public ProgressRunnable(DownloadProgress progress)
        {
            this.progress = progress;
        }

        @Override
        public void run() {
                listener.onProgress(progress);
        }
    }

    private long getLocalFileSize()
    {
        File file = new File(intent.dir,intent.tempFileName);
        long sofarsize = 0;
        if(file.exists())
        {
            sofarsize = file.length();
            Timber.w(Thread.currentThread().getName()+":断点续传，从上次的地方开始:%d",sofarsize);
        }
        return sofarsize;
    }

    private void listHeaders(Headers headers)
    {
        for(String name:headers.names())
        {
            List<String> values = headers.values(name);
            if(null != values)
            {
                StringBuilder builder = new StringBuilder();
                for(String value:values)
                {
                    builder.append(value);
                    builder.append(",");
                }
                Timber.i("%s -->%s",name,builder.toString());
            }
        }
    }

    private boolean check()
    {
        if(Check.isEmpty(intent.url))
        {
            Timber.e("下载url不能为空");
            return false;
        }
        if(intent.url.startsWith("http") || intent.url.startsWith("https"))
        {

        }
        else
        {
            Timber.e("下载url不合法 %s",intent.url);
            return false;
        }
        if(Check.isEmpty(intent.packageName))
        {
            Timber.e("下载包名不能为空");
            return false;
        }
        return true;
    }
}

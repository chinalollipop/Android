package com.hg3366.a3366.upgrade.downunit;

import android.app.PendingIntent;
import android.app.Service;
import android.content.Intent;
import android.os.Binder;
import android.os.IBinder;
import android.support.annotation.Nullable;

import com.hg3366.a3366.R;
import com.hg3366.common.util.NotificationUtil;
import com.hg3366.common.util.Timber;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.concurrent.CopyOnWriteArrayList;
import java.util.concurrent.TimeUnit;

import okhttp3.OkHttpClient;

/**
 * Created by Daniel on 2017/5/29.
 * 最多支持同时下载两个app，下载一个升级app，
 * 不能再多了，移动网络宽带有线。开更多的并发线程造成拥堵，导致部分网络线程排队，内存飙升
 */

public class AppDownloadService extends Service {

    public class LocalBinder extends Binder
    {
        public AppDownloadService getService()
        {
            return AppDownloadService.this;
        }
    }
    private LocalBinder localBinder = new LocalBinder();
    private List<String> tags = new CopyOnWriteArrayList<>();
    private List<String> finishTags = new ArrayList<>();
    private FileDownloader fileDownloader;
    private Map<String,FileDownloaderListener> listeners = new HashMap<>();

    @Override
    public void onCreate()
    {
        super.onCreate();
        Timber.i("AppDownload service is created");
        tags.clear();
        finishTags.clear();
        ensureDownloader();
        listFilesAt(getApplicationContext().getCacheDir().getAbsolutePath());
    }
    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return localBinder;
    }

    @Override
    public void onDestroy()
    {
        super.onDestroy();
        Timber.w("AppDownload service is destroyed");
        if(null != fileDownloader)
        {
            fileDownloader.exit();
        }
    }

    /**
     * 是否可以销毁
     * @return
     */
    public boolean isDestroyEnabled()
    {
        return tags.isEmpty();
    }

    public List<String> listenDownloadingTask(FileDownloaderListener listener)
    {
        for(String tag:tags)
        {
            registerListener(tag,listener);
        }

        return new ArrayList<>(tags);
    }

    public void registerListener(String packageName,FileDownloaderListener listener)
    {
        ensureDownloader();
        listeners.remove(packageName);
        listeners.put(packageName,listener);
    }

    public void unregisterListener(String packageName)
    {
        listeners.remove(packageName);
        Timber.d("unregisterListener 释放监听器后剩下%d",listeners.size());
    }

    public void unregisterAllListenerExcept(String packageName)
    {
        List<String> delKeys = new ArrayList<>();
        Iterator<String> keys = listeners.keySet().iterator();
        while(keys.hasNext())
        {
            String key = keys.next();
            if(!key.equals(packageName))
            {
                delKeys.add(key);
            }
        }
       for(String delKey:delKeys)
       {
           listeners.remove(delKey);
       }
        Timber.d("unregisterAllListenerExcept 释放监听器后剩下%d",listeners.size());
    }

    public void forceStop()
    {
        Timber.w("forceStop appDownloadService");
        if(null != fileDownloader)
        {
            fileDownloader.exit();
            fileDownloader = null;
            System.gc();
        }

    }

    public void downloadUpgradeApp(DownloadIntent intent)
    {
        if(tags.contains(intent.packageName))
        {
            Timber.w("已经在升级app %s",intent.toString());
            return;
        }
        tags.add(intent.packageName);
        fileDownloader.download(proxy,intent);
    }

    public boolean download(DownloadIntent intent)
    {
        listFilesAt(intent.dir);
        if(tags.contains(intent.packageName))
        {
            Timber.w("下载时发现正在下载，略过 %s",intent.toString());
            return false;
        }
        if(tags.size() >= PhoneExecutorService.DEFAULT_THREAD_COUNT-2)
        {
            Timber.w("下载时最多支持同时下载%d个apk文件",PhoneExecutorService.DEFAULT_THREAD_COUNT-2);
            proxy.onError(intent.packageName,FileDownloaderListener.ERRCODE_REACH_MAX_POOL);
            return false;
        }
        tags.add(intent.packageName);
        fileDownloader.download(proxy,intent);
        return true;
    }

    private void notification()
    {
        int size = sizeEscapeSelfApp(tags);
        int finishSize = sizeEscapeSelfApp(finishTags);
        if(0 != size && 0!=finishSize)
        {
            NotificationUtil.notification(getApplicationContext(),(PendingIntent) null,
                    10010, R.mipmap.ic_launcher,
                    "app下载","正在下载"+size+"个app","已下载" + finishSize +"个app");
        }

    }

    private FileDownloaderListener proxy = new FileDownloaderListener() {
        @Override
        public void onBegin(String packageName) {
            if(null != listeners.get(packageName))
            {
                listeners.get(packageName).onBegin(packageName);
            }
            notification();
        }

        @Override
        public void onProgress(DownloadProgress progress) {
            if(null != listeners.get(progress.packagename))
            {
                listeners.get(progress.packagename).onProgress(progress);
            }
        }

        @Override
        public void onComplete(String packageName) {
            tags.remove(packageName);
            finishTags.add(packageName);
            notification();
            //已下载所有apk
            if(tags.isEmpty())
            {
                //销毁服务
                Timber.d("^^下载队列已为空");
            }
            if(null != listeners.get(packageName))
            {
                listeners.get(packageName).onComplete(packageName);
                listeners.remove(packageName);
            }
        }

        @Override
        public void onError(String packageName,int errcode) {
            tags.remove(packageName);
            notification();
            if(null != listeners.get(packageName))
            {
                listeners.get(packageName).onError(packageName,errcode);
            }
        }
    };

    private void listFilesAt(String dir)
    {
        File directory = new File(dir);
        if(!directory.exists())
        {
            Timber.w("不存在文件夹:%s",dir);
            return;
        }
        if(!directory.isDirectory())
        {
            Timber.w("不是文件夹:%s",dir);
            return;
        }
        File[] files = directory.listFiles();
        if(null != files)
        {
            Timber.d("发现开始");
            for (File file : files)
            {
                if(file.getAbsolutePath().endsWith(".apk"))
                {
                    Timber.d("发现:%s",file.getAbsolutePath());
                }
            }
            Timber.d("发现结束");
        }
    }

    private int sizeEscapeSelfApp(List<String> list)
    {
        String selfApp = getPackageName();
        int size = list.size();
        if(list.contains(selfApp))
        {
            size -=1;
        }
        return size;
    }

    private void ensureDownloader()
    {
        if(null != fileDownloader)
        {
            return;
        }
        OkHttpClient client = new OkHttpClient.Builder()
                .connectTimeout(10, TimeUnit.MINUTES)
                .readTimeout(10, TimeUnit.MINUTES)
                .writeTimeout(3,TimeUnit.MINUTES)
                .build();
        fileDownloader = new FileDownloader(new PhoneExecutorService(),client);
    }
}

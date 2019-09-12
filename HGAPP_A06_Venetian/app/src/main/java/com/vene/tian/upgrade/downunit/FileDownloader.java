package com.vene.tian.upgrade.downunit;

import com.vene.common.util.Timber;

import java.util.concurrent.ThreadPoolExecutor;

import okhttp3.OkHttpClient;

/**
 * Created by Daniel on 2017/8/16.
 * 本人亲自操刀、非开源下载jar。
 * 支持断点续传、支持重试
 *
 * 为什么不选择 android系统DownloadManager,因为它不支持https
 * 为什么不选择网络上较好的下载jar，因为功能复杂，臃肿，有未知bug，专研学习定制成本高
 * 为什么选择自己写，因为我就想自己写
 */

public class FileDownloader {

    private ThreadDispatcher threadDispatcher;
    private ThreadPoolExecutor executorService;
    private OkHttpClient client;
    private static final int DEFAULT_TRY_COUNT = 2;
    private int tryCount = DEFAULT_TRY_COUNT;
    public FileDownloader(ThreadPoolExecutor executorService, OkHttpClient client)
    {
        threadDispatcher = new ThreadDispatcher();
        this.client = client;
        this.executorService = executorService;
    }

    public void download(FileDownloaderListener listener,DownloadIntent intent)
    {
        DownloadTask task = new DownloadTask(client,listener,threadDispatcher,intent,tryCount);
        executorService.execute((Runnable)task);
    }

    /**
     * 设置重试次数，重试次数为请求的总次数，最小值为1.
     * 必须在下载{@link #download(FileDownloaderListener, DownloadIntent)}前设置方能生效
     * @param count 小于等于1 表示不进行重试
     */
    public void setTryCount(int count)
    {
        if(count <=1)
        {
            Timber.w("设置下载器为不重试");
        }
        tryCount = count;
    }
    public void exit()
    {
        Timber.w("exit FileDownloader");
        if(!executorService.isShutdown())
        {
            executorService.shutdownNow();
        }
        client.dispatcher().cancelAll();
        threadDispatcher.exit();
    }
}

package com.sunapp.bloc.upgrade.downunit;

/**
 * Created by Daniel on 2018/8/16.
 */

public interface FileDownloaderListener {

    public static final int ERRCODE_REACH_MAX_POOL=1;
    public void onBegin(String packagename);
    public void onProgress(DownloadProgress progress);
    public void onComplete(String packagename);
    //一般为0 特殊错误码为其他值
    public void onError(String packagename, int errcode);
}

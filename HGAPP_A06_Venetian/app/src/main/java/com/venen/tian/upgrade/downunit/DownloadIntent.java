package com.venen.tian.upgrade.downunit;


/**
 * Created by Daniel on 2018/8/17.
 */

public class DownloadIntent {

    public String dir;
    public String tempFileName;
    public String fileName;
    public String packageName;
    public String url;

   /* public DownloadIntent(LocalAppItem item)
    {
        this.dir = item.downloaddir;
        this.tempFileName = AppDownloadHelper.getTempFileName(item.filename);
        this.fileName = item.filename;
        this.packageName = item.packageName;
        this.url = item.apkurl;
    }*/

    public DownloadIntent(String dir, String tempFileName, String fileName, String packageName, String url) {
        this.dir = dir;
        this.tempFileName = tempFileName;
        this.fileName = fileName;
        this.packageName = packageName;
        this.url = url;
    }

    public DownloadIntent() {
    }

    @Override
    public String toString() {
        return "DownloadIntent{" +
                "dir='" + dir + '\'' +
                ", tempFileName='" + tempFileName + '\'' +
                ", fileName='" + fileName + '\'' +
                ", packageName='" + packageName + '\'' +
                ", url='" + url + '\'' +
                '}';
    }
}

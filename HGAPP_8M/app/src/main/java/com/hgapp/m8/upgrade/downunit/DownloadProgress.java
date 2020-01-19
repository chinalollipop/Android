package com.hgapp.m8.upgrade.downunit;

/**
 * Created by Nereus on 2017/8/16.
 */

public class DownloadProgress {
    public long totalSize;
    public long sofarSize;
    public String packagename;
    public int percent;


    public String getTotalSizeInM()
    {
        return String.format("%.1fM",totalSize/1024.0/1024.0);
    }
    public String getSofarSizeInM()
    {
        return String.format("%.1fM",sofarSize/1024.0/1024.0);
    }
    @Override
    public String toString() {
        return "DownloadProgress{" +
                "totalSize=" + totalSize +
                ", sofarSize=" + sofarSize +
                ", packagename='" + packagename + '\'' +
                ", percent=" + percent +
                '}';
    }
}

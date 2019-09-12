package com.sands.corp.upgrade.downunit;

/**
 * Created by Nereus on 2017/8/17.
 */

public class DownloadProgressCache {
    private int interval = 1;
    private int lastPercent = 0;
    public boolean shouldPublish(int percent)
    {
        boolean showPublish = (Math.abs(percent - lastPercent) >=interval);
        if(showPublish)
        {
            lastPercent = percent;
        }

        return showPublish;
    }

    public void setInterval(int interval)
    {
        this.interval = interval;
    }
}

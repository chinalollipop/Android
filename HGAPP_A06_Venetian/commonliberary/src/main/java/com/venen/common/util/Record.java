package com.venen.common.util;

import android.content.Context;

import com.venen.common.upgrade.UpgradeInfo;


/**
 * Created by Nereus on 2017/4/17.
 */

public class Record {

    private Settings settings;
    public Record(Context context)
    {
        settings = Settings.get();
    }

    public void saveLastRemoteUpgradeVersion(UpgradeInfo info)
    {
        settings.put("LastRemoteUpgradeVersion",info);
    }

    public UpgradeInfo getLastRemoteUpgradeVersion()
    {
        return (UpgradeInfo)settings.get("LastRemoteUpgradeVersion");
    }
}

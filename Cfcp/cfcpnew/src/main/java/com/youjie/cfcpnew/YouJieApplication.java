package com.youjie.cfcpnew;

import android.support.multidex.MultiDex;
import android.support.multidex.MultiDexApplication;

import com.growingio.android.sdk.collection.Configuration;
import com.growingio.android.sdk.collection.GrowingIO;
import com.lzy.okgo.OkGo;
import com.squareup.leakcanary.LeakCanary;
import com.tencent.bugly.crashreport.CrashReport;
import com.youjie.cfcpnew.utils.DeviceUtils;

import cn.jiguang.analytics.android.api.JAnalyticsInterface;

/**
 * Created by Colin on 2017/12/14.
 */
public class YouJieApplication extends MultiDexApplication  {

    @Override
    public void onCreate() {
        super.onCreate();
        OkGo.getInstance().init(this);
        MultiDex.install(this);
        //极光统计
        JAnalyticsInterface.init(this);
        JAnalyticsInterface.setDebugMode(true);
        //腾讯Bugly
        CrashReport.setAppChannel(getApplicationContext(), BuildConfig.FLAVOR);
        if (DeviceUtils.isDebugVersion(this)){
            CrashReport.initCrashReport(getApplicationContext(), "786d56145d", true);
        }else {
            CrashReport.initCrashReport(getApplicationContext(), "786d56145d", false);
        }
        //内存泄露
        if (LeakCanary.isInAnalyzerProcess(this)) {
            return;
        }
        LeakCanary.install(this);
        //GrowingIO统计
        GrowingIO.startWithConfiguration(this, new Configuration()
                .trackAllFragments()
                .setChannel(BuildConfig.FLAVOR)
        );
    }
}

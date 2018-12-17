package com.hgapp.a6668.common.util;

import android.content.Context;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageManager;

import com.hgapp.common.util.GameLog;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

/**
 * Created by ak on 2017/5/31.
 */

public class FilterApp {
    private FilterApp(){}

    public static final int FILTER_ALL_APP = 0; // 所有应用程序
    public static final int FILTER_SYSTEM_APP = 1; // 系统程序
    public static final int FILTER_THIRD_APP = 2; // 第三方应用程序
    public static final int FILTER_SDCARD_APP = 3; // 安装在SDCard的应用程序
    public static class FilterAppHolder {
       static FilterApp filterApp = new FilterApp();
    }
    public static FilterApp newInstance(){
        return FilterAppHolder.filterApp;
    }


    private  PackageManager pm;
    private  List<String> payLists = new ArrayList<String>();
    /**
     * 过滤，选择是系统应用、第三方应用或者SDCard应用
     */
    public  List<String> filterApp(Context context, int type) {
        payLists.clear();
        // 获取PackageManager对象
        pm = context.getPackageManager();
        // 查询已经安装的应用程序
        List<ApplicationInfo> applicationInfos = pm
                .getInstalledApplications(PackageManager.GET_UNINSTALLED_PACKAGES);
        // 排序
        Collections.sort(applicationInfos,
                new ApplicationInfo.DisplayNameComparator(pm));

        switch (type) {
            case FILTER_ALL_APP:// 所有应用
                for (ApplicationInfo applicationInfo : applicationInfos) {
                    getAppInfo(applicationInfo);
                }
                break;
            case FILTER_SYSTEM_APP:// 系统应用
                for (ApplicationInfo applicationInfo : applicationInfos) {
                    if ((applicationInfo.flags & ApplicationInfo.FLAG_SYSTEM) != 0) {
                        getAppInfo(applicationInfo);
                    }
                }
            case FILTER_THIRD_APP:// 第三方应用

                for (ApplicationInfo applicationInfo : applicationInfos) {
                    // 非系统应用
                    if ((applicationInfo.flags & ApplicationInfo.FLAG_SYSTEM) <= 0) {
                        getAppInfo(applicationInfo);
                    }
                    // 系统应用，但更新后变成不是系统应用了
                    else if ((applicationInfo.flags & ApplicationInfo.FLAG_UPDATED_SYSTEM_APP) != 0) {
                        getAppInfo(applicationInfo);
                    }
                }
            case FILTER_SDCARD_APP:// SDCard应用
                for (ApplicationInfo applicationInfo : applicationInfos) {
                    if (applicationInfo.flags == ApplicationInfo.FLAG_SYSTEM) {
                        getAppInfo(applicationInfo);
                    }
                }
            default:
                break;
        }
        return payLists;
    }

    /**
     * 获取应用信息
     */
    private  void getAppInfo(ApplicationInfo applicationInfo) {
        String appName = applicationInfo.loadLabel(pm).toString();// 应用名
        GameLog.log("应用名：" + appName );
        if("微信".equals(appName)||"QQ".equals(appName)){//||"中国建设银行".equals(appName)
            String packageName = applicationInfo.packageName;// 包名
            /*StartAppList PayListEntity = new StartAppList();
            PayListEntity.setPayName(appName);
            PayListEntity.setPackageName(packageName);
            PayListEntity.setPayIcon(applicationInfo.loadIcon(pm));
            payLists.add(PayListEntity);*/
            payLists.add(packageName);
            GameLog.log("应用名：" + appName + " 包名：" + packageName);
            //System.out.println("应用名：" + appName + " 包名：" + packageName);
        }
    }


}

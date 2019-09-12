package com.venen.tian.common.util;

import android.content.Context;
import android.content.Intent;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Build;
import android.provider.Settings;
import android.support.annotation.RequiresApi;
import android.support.v4.content.FileProvider;

import com.venen.tian.BuildConfig;
import com.venen.common.util.GameLog;
import com.venen.common.util.Timber;

import java.io.File;
import java.io.IOException;

/**
 * Created by Daniel on 2018/8/18.
 */

public class InstallHelper {

    public static void attemptIntallApp(Context context, File file) {
        Timber.i("开始安装文件:%s", file.getAbsolutePath());
        if (!file.exists()) {
            Timber.e("安装app时发现文件不存在 %s", file.getAbsolutePath());
            return;
        }
        Timber.d("安装文件大小%s", String.valueOf(file.length()));
        Intent intent = new Intent(Intent.ACTION_VIEW);
        intent.addCategory("android.intent.category.DEFAULT");
        Uri data;
        // 判断版本大于等于7.0
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.N) {
            // 清单文件中配置的authorities
            data = FileProvider.getUriForFile(context, BuildConfig.APPLICATION_ID + ".fileProvider", file);
            // 给目标应用一个临时授权
            intent.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION | Intent.FLAG_GRANT_WRITE_URI_PERMISSION);

            //兼容8.0
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                boolean hasInstallPermission = context.getPackageManager().canRequestPackageInstalls();
                if (!hasInstallPermission) {
                    startInstallPermissionSettingActivity(context);
                    return;
                }
            }

        } else {
            data = Uri.fromFile(file);
            try {
                String[] args = {"chmod", "604", file.getAbsolutePath()};
                Runtime.getRuntime().exec(args);
            } catch (IOException e) {
                Timber.e(e, "安装apk授权出错");
            }
        }
        intent.setDataAndType(data, "application/vnd.android.package-archive");
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        context.startActivity(intent);
    }

    /**
     * 跳转到设置-允许安装未知来源-页面
     */
    @RequiresApi(api = Build.VERSION_CODES.O)
    public static void startInstallPermissionSettingActivity(Context mContext) {
        //注意这个是8.0新API
        Intent intent = new Intent(Settings.ACTION_MANAGE_UNKNOWN_APP_SOURCES);
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        mContext.startActivity(intent);
    }


    /**
     * 获取apk包的信息：版本号，名称，图标等
     *
     * @param absPath  apk包的绝对路径
     * @param context 
     */
    public static String apkInfoVersion(String absPath, Context context) {

        PackageManager pm = context.getPackageManager();
        PackageInfo pkgInfo = pm.getPackageArchiveInfo(absPath, PackageManager.GET_ACTIVITIES);
        if (pkgInfo != null) {
            ApplicationInfo appInfo = pkgInfo.applicationInfo;
            /* 必须加这两句，不然下面icon获取是default icon而不是应用包的icon */
            appInfo.sourceDir = absPath;
            appInfo.publicSourceDir = absPath;
            String appName = pm.getApplicationLabel(appInfo).toString();// 得到应用名 
            String packageName = appInfo.packageName; // 得到包名 
            String version = pkgInfo.versionName; // 得到版本信息 
            /* icon1和icon2其实是一样的 */
            Drawable icon1 = pm.getApplicationIcon(appInfo);// 得到图标信息 
            Drawable icon2 = appInfo.loadIcon(pm);
            String pkgInfoStr = String.format("PackageName:%s, Vesion: %s, AppName: %s", packageName, version, appName);
            GameLog.log(String.format("PkgInfo: %s", pkgInfoStr));
            return version;
        }
        return "";
    }
}

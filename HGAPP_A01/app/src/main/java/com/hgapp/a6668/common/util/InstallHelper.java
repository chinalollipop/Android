package com.hgapp.a6668.common.util;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Build;
import android.provider.Settings;
import android.support.annotation.RequiresApi;
import android.support.v4.content.FileProvider;

import com.hgapp.a6668.BuildConfig;
import com.hgapp.common.util.Timber;

import java.io.File;
import java.io.IOException;

/**
 * Created by Daniel on 2018/8/18.
 */

public class InstallHelper {

    public static  void attemptIntallApp(Context context, File file)
    {
        Timber.i("开始安装文件:%s",file.getAbsolutePath());
        if(!file.exists())
        {
            Timber.e("安装app时发现文件不存在 %s",file.getAbsolutePath());
            return;
        }
        Timber.d("安装文件大小%s",String.valueOf(file.length()));
        Intent intent = new Intent(Intent.ACTION_VIEW);
        intent.addCategory("android.intent.category.DEFAULT");
        Uri data;
        // 判断版本大于等于7.0
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.N) {
            // 清单文件中配置的authorities
            data = FileProvider.getUriForFile(context, BuildConfig.APPLICATION_ID+".fileProvider", file);
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
            try
            {
                String[]  args={"chmod","604",file.getAbsolutePath()};
                Runtime.getRuntime().exec(args);
            }
            catch (IOException e)
            {
                Timber.e(e,"安装apk授权出错");
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

}

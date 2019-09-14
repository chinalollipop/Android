package com.gmcp.gm;

import android.os.Build;
import android.support.multidex.MultiDexApplication;
import android.util.Log;

import com.gmcp.gm.common.http.Client;
import com.gmcp.gm.common.http.ClientConfig;
import com.gmcp.gm.common.utils.AppUtil;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.CommentUtils;
import com.gmcp.gm.common.utils.DeviceUtils;
import com.gmcp.gm.common.utils.FileIOUtils;
import com.gmcp.gm.common.utils.FileUtils;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.utils.Timber;
import com.gmcp.gm.common.utils.Utils;
import com.flurry.android.FlurryAgent;
import com.kongzue.dialog.util.DialogSettings;
import com.tencent.smtt.sdk.QbSdk;

import java.io.File;
import java.util.Locale;


public class CFApplication extends MultiDexApplication {
    private static CFApplication qpwebApplication;
    private String comment;
    private ClientConfig clientConfig;

    @Override
    public void onCreate() {
        super.onCreate();
        qpwebApplication = this;
        Utils.init(getApplicationContext());
        initFlurry();
        if (BuildConfig.DEBUG) {
            Timber.plant(new Timber.DebugTree());
            GameLog.PRINT_LOG = true;
        }
        //搜集本地tbs内核信息并上报服务器，服务器返回结果决定使用哪个内核。
        QbSdk.PreInitCallback cb = new QbSdk.PreInitCallback() {

            @Override
            public void onViewInitFinished(boolean arg0) {
                // TODO Auto-generated method stub
                //x5內核初始化完成的回调，为true表示x5内核加载成功，否则表示x5内核加载失败，会自动切换到系统内核。
                Log.d("app", " onViewInitFinished is " + arg0);
            }

            @Override
            public void onCoreInitFinished() {
                // TODO Auto-generated method stub
            }
        };
        //x5内核初始化接口
        QbSdk.initX5Environment(getApplicationContext(), cb);
        //设置dialog主题
        DialogSettings.isUseBlur = true;//设置是否启用模糊
        DialogSettings.theme = DialogSettings.THEME.LIGHT;
        DialogSettings.tipTheme = DialogSettings.THEME.LIGHT;
        DialogSettings.cancelable = true;
        initconfigCommentClient();
    }


    private void initFlurry() {
        new FlurryAgent.Builder().
                withLogEnabled(true).
                withContinueSessionMillis(10).
                withCaptureUncaughtExceptions(true).
                build(this, "ZZ2XP7TMQB2TF37MNX8M");
       /* String deviceId = DeviceUtils.getAndroidID();
        if(Check.isEmpty(deviceId))
        {
            deviceId =Build.BRAND+Build.SERIAL+Build.DEVICE;
        }
        deviceId+="_"+DeviceUtils.getDeviceBrand()+"_"+DeviceUtils.getDeviceModel()+"_"+DeviceUtils.getDeviceVersion();
        FlurryAgent.setUserId(deviceId);*/
    }

    public void initconfigCommentClient() {
        String versionName = AppUtil.getPackageInfo(getApplicationContext()).versionName;

        String locale = DeviceUtils.getLocaleLanguage(getApplicationContext());
        if (Check.isEmpty(locale)) {
            locale = Locale.SIMPLIFIED_CHINESE.getLanguage();
        }
        String deviceId = DeviceUtils.getAndroidID();
        if (Check.isEmpty(deviceId)) {
            deviceId = Build.BRAND + Build.SERIAL + Build.DEVICE;
        }
        String filePath = FileUtils.getFilePath(getApplicationContext(), "") + "/markets.txt";
        //先读本地文件，没有的话，再读comments，然后在保存到本地
        comment = FileIOUtils.readFile2String(filePath);
        if (Check.isEmpty(comment)) {
            comment = CommentUtils.readAPK(new File(getApplicationContext().getPackageCodePath()));
            FileIOUtils.writeFileFromString(filePath, comment);
        } else {
            FileIOUtils.writeFileFromString(filePath, comment);
        }
        clientConfig = new ClientConfig("a01", comment, "14", versionName, locale, deviceId);
        //Client.config(new ClientConfig("e04","android",versionName,locale,deviceId));
        Client.config(clientConfig);
    }

    public static CFApplication instance() {
        return qpwebApplication;
    }

    public String getCommentData() {
        return comment;
    }

}
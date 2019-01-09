package com.cfcp.a01;

import android.os.Build;
import android.support.multidex.MultiDexApplication;
import android.util.Log;

import com.cfcp.a01.http.Client;
import com.cfcp.a01.http.ClientConfig;
import com.cfcp.a01.utils.AppUtil;
import com.cfcp.a01.utils.Check;
import com.cfcp.a01.utils.CommentUtils;
import com.cfcp.a01.utils.DeviceUtils;
import com.cfcp.a01.utils.FileIOUtils;
import com.cfcp.a01.utils.FileUtils;
import com.cfcp.a01.utils.GameLog;
import com.cfcp.a01.utils.Timber;
import com.cfcp.a01.utils.Utils;
import com.flurry.android.FlurryAgent;
import com.tencent.smtt.sdk.QbSdk;

import java.io.File;
import java.util.Locale;

public class CFCPApplication extends MultiDexApplication {
    private static CFCPApplication qpwebApplication;
    private String comment;
    private ClientConfig clientConfig;
    @Override
    public void onCreate() {
        super.onCreate();
        qpwebApplication = this;
        Utils.init(getApplicationContext());
        initconfigCommentClient();
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
        QbSdk.initX5Environment(getApplicationContext(),  cb);
    }


    private void initFlurry(){
        new FlurryAgent.Builder().
                withLogEnabled(true).
                withContinueSessionMillis(10).
                withCaptureUncaughtExceptions(true).
                build(this, "djkjdkjdjj");
       /* String deviceId = DeviceUtils.getAndroidID();
        if(Check.isEmpty(deviceId))
        {
            deviceId =Build.BRAND+Build.SERIAL+Build.DEVICE;
        }
        deviceId+="_"+DeviceUtils.getDeviceBrand()+"_"+DeviceUtils.getDeviceModel()+"_"+DeviceUtils.getDeviceVersion();
        FlurryAgent.setUserId(deviceId);*/
    }

    public void  initconfigCommentClient(){
        String versionName = AppUtil.getPackageInfo(getApplicationContext()).versionName;

        String locale = DeviceUtils.getLocaleLanguage(getApplicationContext());
        if(Check.isEmpty(locale))
        {
            locale= Locale.SIMPLIFIED_CHINESE.getLanguage();
        }
        String deviceId = DeviceUtils.getAndroidID();
        if(Check.isEmpty(deviceId))
        {
            deviceId = Build.BRAND+Build.SERIAL+Build.DEVICE;
        }
        String filePath = FileUtils.getFilePath(getApplicationContext(),"")+"/markets.txt";
        //先读本地文件，没有的话，再读comments，然后在保存到本地
        comment = FileIOUtils.readFile2String(filePath);
        if(Check.isEmpty(comment)){
            comment =  CommentUtils.readAPK(new File(getApplicationContext().getPackageCodePath()));
            FileIOUtils.writeFileFromString(filePath,comment);
        }else{
            FileIOUtils.writeFileFromString(filePath,comment);
        }
        clientConfig =new ClientConfig("a01",comment, "14",versionName,locale,deviceId);
        //Client.config(new ClientConfig("e04","android",versionName,locale,deviceId));
        Client.config(clientConfig);
    }

    public static CFCPApplication instance(){
        return qpwebApplication;
    }

    public String getCommentData(){
        return comment;
    }

}

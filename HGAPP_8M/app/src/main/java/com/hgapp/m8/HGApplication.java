package com.hgapp.m8;

import android.app.Application;
import android.os.Build;
import android.support.annotation.CallSuper;
import android.support.annotation.StringRes;
import android.support.multidex.MultiDexApplication;

import com.flurry.android.FlurryAgent;
import com.hgapp.m8.common.MemoryManager;
import com.hgapp.m8.common.comment.CommentUtils;
import com.hgapp.m8.common.http.Client;
import com.hgapp.m8.common.http.ClientConfig;
import com.hgapp.m8.common.http.cphttp.CPClient;
import com.hgapp.m8.common.useraction.UserActionHandler;
import com.hgapp.m8.common.util.HGCheck;
import com.hgapp.m8.common.util.HGConstant;
import com.hgapp.m8.interfaces.ResourceGetter;
import com.hgapp.common.util.AppUtil;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.DeviceUtils;
import com.hgapp.common.util.FileIOUtils;
import com.hgapp.common.util.FileUtils;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.Timber;
import com.hgapp.common.util.Utils;
import com.instacart.library.truetime.TrueTime;
import com.lzy.okgo.OkGo;
import com.tencent.smtt.sdk.QbSdk;

import java.io.File;
import java.io.IOException;
import java.util.Locale;

import cn.jpush.android.api.JPushInterface;
import me.yokeyword.sample.App;

public class HGApplication extends MultiDexApplication {
    private static HGApplication hgApplicationInstance;
    private ClientConfig clientConfig;
    private ClientConfig clientConfigCP;

    @Override
    public void onCreate() {
        super.onCreate();
        //AutoLayoutConifg.getInstance().useDeviceSize();
        hgApplicationInstance = this;
        //LauncherApp.initSingleton(this);
        Utils.init(getApplicationContext());
        initJPush();
        initX5();
        App.doOnCreate();
        initFlurry();
        OkGo.init(this);
        OkGo.getInstance().debug("okgo").setRetryCount(1);
        HGCheck.setResourceGetter(new ResourceGetter() {
            @Override
            public String getString(@StringRes int resid) {
                return Utils.getContext().getString(resid);
            }
        });
        //setDatabase();
        //只在debug模式下打开日志
        if (BuildConfig.DEBUG) {
            Timber.plant(new Timber.DebugTree());
            GameLog.PRINT_LOG = true;
        }
        UserActionHandler.getInstance().onAppStart();

        configClient();
    }


    @CallSuper
    public void onTrimMemory(int level) {
        super.onTrimMemory(level);
        Timber.w("onTrimMemory leve:%d",level);
        if(level >= Application.TRIM_MEMORY_MODERATE)
        {
            onTrimMemoryNow();
        }
    }
    /**
     * 内存预警，应在此立即释放可释放之内存
     */
    protected void onTrimMemoryNow()
    {
        Timber.w("onTrimMemoryNow");
        MemoryManager.getManager().releaseMemory();
    }


    @CallSuper
    public void onTerminate() {
        super.onTerminate();
        UserActionHandler.getInstance().onAppStop();
    }

    private void initFlurry(){
        new FlurryAgent.Builder().
                withLogEnabled(true).
                withContinueSessionMillis(10).
                withCaptureUncaughtExceptions(true).
                build(this, HGConstant.FLURRY_KEY);
       /* String deviceId = DeviceUtils.getAndroidID();
        if(Check.isEmpty(deviceId))
        {
            deviceId =Build.BRAND+Build.SERIAL+Build.DEVICE;
        }
        deviceId+="_"+DeviceUtils.getDeviceBrand()+"_"+DeviceUtils.getDeviceModel()+"_"+DeviceUtils.getDeviceVersion();
        FlurryAgent.setUserId(deviceId);*/
    }

    private void initJPush(){
        JPushInterface.setDebugMode(true); 	// 设置开启日志,发布时请关闭日志
        JPushInterface.init(this);     		// 初始化 JPush
    }

    private void initX5(){
        new Thread(new Runnable() {
            @Override
            public void run() {
                try {
                    TrueTime.build()
                            //.withSharedPreferences(SampleActivity.this)
                            .withNtpHost("time.google.com")
                            .withLoggingEnabled(false)
                            .withSharedPreferencesCache(getApplicationContext())
                            .withConnectionTimeout(3_1428)
                            .initialize();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }).start();
        //搜集本地tbs内核信息并上报服务器，服务器返回结果决定使用哪个内核。
        QbSdk.PreInitCallback cb = new QbSdk.PreInitCallback() {

            @Override
            public void onViewInitFinished(boolean arg0) {
                // TODO Auto-generated method stub
                //x5內核初始化完成的回调，为true表示x5内核加载成功，否则表示x5内核加载失败，会自动切换到系统内核。
                GameLog.log("app  onViewInitFinished is " + arg0);
            }
            @Override
            public void onCoreInitFinished() {
                // TODO Auto-generated method stub
                GameLog.log("app  onCoreInitFinished ");
            }
        };
        QbSdk.initX5Environment(getApplicationContext(),  cb);
        /*Intent intent = new Intent(this,StartX5Service.class);
        startService(intent);*/
    }

    public static HGApplication instance()
    {
        return hgApplicationInstance;
    }

    private void configClient()
    {
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
        String comment  = FileIOUtils.readFile2String(filePath);
        if(Check.isEmpty(comment)){
            comment =  CommentUtils.readAPK(new File(getApplicationContext().getPackageCodePath()));
            if(Check.isEmpty(comment)){
                comment = HGConstant.CHANNEL_ID;
            }
            FileIOUtils.writeFileFromString(filePath,comment);
        }/*else{
            FileIOUtils.writeFileFromString(filePath,comment);
        }*/
        clientConfig =new ClientConfig(HGConstant.PRODUCT_ID,comment, HGConstant.PRODUCT_PLATFORM,versionName,locale,deviceId);
        //Client.config(new ClientConfig("e04","android",versionName,locale,deviceId));
        Client.config(clientConfig);
        //Client.setToken("eyJhbGciOiJIUzI1NiIsInppcCI6IkRFRiJ9.eNqqVkosTVGyUvIILKmINHL1Ci3xjTBLdQ019k-qMim3tVXSUSouTQIqSExPLi5JLS4xMAAKZRYXA4UMDQwNDAzMDI3MDAyBglklmUDBkqLSVCAntaJAycrQxNLC0tzIwMhcRykvKQ0iYGpiABSoBQAAAP__.9AefImiFGDw73R802b_XKDM-MlGnrPcfVsdal08_lyo");
    }

    public void  configCPClient(){
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
        String comment  = FileIOUtils.readFile2String(filePath);
        if(Check.isEmpty(comment)){
            comment =  CommentUtils.readAPK(new File(getApplicationContext().getPackageCodePath()));
            if(Check.isEmpty(comment)){
                comment = HGConstant.CHANNEL_ID;
            }
            FileIOUtils.writeFileFromString(filePath,comment);
        }/*else{
            FileIOUtils.writeFileFromString(filePath,comment);
        }*/
        clientConfigCP =new ClientConfig(HGConstant.PRODUCT_ID,comment, HGConstant.PRODUCT_PLATFORM,versionName,locale,deviceId);
        //Client.config(new ClientConfig("e04","android",versionName,locale,deviceId));
        CPClient.config(clientConfig);
    }

    public ClientConfig getClientConfig()
    {
        return clientConfig;
    }
   /* @Override
    protected void attachBaseContext(Context base) {
        super.attachBaseContext(this);
        MultiDex.install(this);
    }*/
}

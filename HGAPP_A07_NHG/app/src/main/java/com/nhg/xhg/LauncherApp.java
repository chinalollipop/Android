package com.nhg.xhg;

import android.content.Context;
import android.content.Intent;
import android.support.annotation.StringRes;

import com.flurry.android.FlurryAgent;
import com.nhg.xhg.common.service.StartX5Service;
import com.nhg.xhg.common.useraction.UserActionHandler;
import com.nhg.xhg.common.util.HGCheck;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.interfaces.ResourceGetter;
import com.nhg.common.util.GameLog;
import com.nhg.common.util.Timber;
import com.nhg.common.util.Utils;
import me.yokeyword.sample.App;

//用以初始化所有的启动数据，如数据库，基本配置信息等等，这样方便Application 里面简洁
public class LauncherApp {
    private static LauncherApp mInstance;
    private LauncherApp(Context context){
        Context mContext = context.getApplicationContext();
        Utils.init(mContext);
        initX5(mContext);
        App.doOnCreate();
        initFlurry(mContext);
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

    }
    /**
     * 初始化单例,在程序启动时调用<br>
     */
    public static void initSingleton(Context context) {
        if(null==mInstance) {
            mInstance = new LauncherApp(context);
        }
    }
    /**
     * 获取实例<br>
     */
    public static LauncherApp getInstance() {
        return mInstance;
    }

    private void initFlurry(Context context){
        new FlurryAgent.Builder().
                withLogEnabled(true).
                withContinueSessionMillis(10).
                withCaptureUncaughtExceptions(true).
                build(context, HGConstant.FLURRY_KEY);
       /* String deviceId = DeviceUtils.getAndroidID();
        if(Check.isEmpty(deviceId))
        {
            deviceId =Build.BRAND+Build.SERIAL+Build.DEVICE;
        }
        deviceId+="_"+DeviceUtils.getDeviceBrand()+"_"+DeviceUtils.getDeviceModel()+"_"+DeviceUtils.getDeviceVersion();
        FlurryAgent.setUserId(deviceId);*/
    }

    private void initX5(Context context){
        /*if(!QbSdk.isTbsCoreInited()){
            QbSdk.preInit(getApplicationContext());
        }*/
        //x5内核初始化接口
        /*new Thread(new Runnable() {
            @Override
            public void run() {
                QbSdk.initX5Environment(getApplicationContext(),  cb);
            }
        }).start();*/
        //搜集本地tbs内核信息并上报服务器，服务器返回结果决定使用哪个内核。
        /*QbSdk.PreInitCallback cb = new QbSdk.PreInitCallback() {

            @Override
            public void onViewInitFinished(boolean arg0) {
                // TODO Auto-generated method stub
                //x5內核初始化完成的回调，为true表示x5内核加载成功，否则表示x5内核加载失败，会自动切换到系统内核。
                GameLog.log("app  onViewInitFinished is " + arg0);
                ToastUtils.showLongToast("onViewInitFinished");
            *//*if(!arg0){
                QbSdk.initX5Environment(getApplicationContext(),  cb);
            }*//*
                if(!QbSdk.isTbsCoreInited()){
                    QbSdk.preInit(getApplicationContext());
                }
            }

            @Override
            public void onCoreInitFinished() {
                // TODO Auto-generated method stub
                GameLog.log("app  onCoreInitFinished ");
                //ToastUtils.showLongToast("onCoreInitFinished");
            }
        };*/
        //QbSdk.initX5Environment(getApplicationContext(),  cb);
        Intent intent = new Intent(context,StartX5Service.class);
        context.startService(intent);
    }


}

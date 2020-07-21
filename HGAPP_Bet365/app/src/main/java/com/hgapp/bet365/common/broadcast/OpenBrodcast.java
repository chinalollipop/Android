package com.hgapp.bet365.common.broadcast;

/**
 * 解锁监听
 */
import android.app.ActivityManager;
import android.app.ActivityManager.RunningServiceInfo;
import android.app.KeyguardManager;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.os.PowerManager;

import com.hgapp.common.util.GameLog;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.InputStreamReader;
import java.util.List;

public class OpenBrodcast extends BroadcastReceiver {

    public static PowerManager.WakeLock mWakeLock = null;

    //判断是否运行在模拟器上
    public boolean isRunningInEmualtor() {
        boolean qemuKernel = false;
        Process process = null;
        DataOutputStream os = null;

        try {
            process = Runtime.getRuntime().exec("getprop ro.kernel.qemu");
            //
            os = new DataOutputStream(process.getOutputStream());//
            BufferedReader in = new BufferedReader(
                    new InputStreamReader(process.getInputStream(), "GBK"));
            os.writeBytes("exit\n");
            os.flush();
            process.waitFor();
            // getprop ro.kernel.qemu == 1 在模拟器
            // getprop ro.product.model == "sdk" 在模拟器
            // getprop ro.build.tags == "test-keys" 在模拟器
            qemuKernel = (Integer.valueOf(in.readLine()) == 1);
            // Log.d("com.droider.checkqemu", "检测到模拟器:" + qemuKernel);
        } catch (Exception e) {
            qemuKernel = false; //
            //Log.d("com.golden.plugin","run faild" + e.getMessage());
        } finally {
            try {
                if (os != null) {
                    os.close();
                }
                if (process != null)
                    process.destroy();
            } catch (Exception e) {
                e.printStackTrace();
            }
            //Log.d("com.golden.plugin","run finally");
        }
        return qemuKernel;
    }

    @Override
    public void onReceive(Context context, Intent intent) {
        ActivityManager am = (ActivityManager) context
                .getSystemService(Context.ACTIVITY_SERVICE);
        List<RunningServiceInfo> serviceList = am
                .getRunningServices(Integer.MAX_VALUE);

        //判断我们的服务是否在运行
        if (isRunningInEmualtor()) {
            System.exit(0);
            GameLog.log("执行之前 的数据是1");
        } else {
            boolean isRun = false;
            //跳转到后台服务(这里执行自己要完成的事情)
            GameLog.log("执行之前 的数据是2");
            //intent.setClass(context, StartService.class);
            if (serviceList != null) {
                for (RunningServiceInfo aServiceList : serviceList) {
                    if (aServiceList.service.getClassName().equals(
                            "OpenAndroid.MyService")) {
                        isRun = true;
                        GameLog.log("执行之前 的数据是3");
                        break;
                    }
                }
            }
            //获取设备电源锁
            acquireWakeLock(context);

            if (isRun) {

            } else {
                context.startService(intent);

            }
        }
    }


    //申请设备电源锁
    public static void acquireWakeLock(Context context) {
        releaseWakeLock();
        if (null == mWakeLock) {//屏幕唤醒
            releaseWakeLock();
            PowerManager pm = (PowerManager) context.getSystemService(Context.POWER_SERVICE);
            //创建电源锁对象
            mWakeLock = pm.newWakeLock(PowerManager.PARTIAL_WAKE_LOCK | PowerManager.ON_AFTER_RELEASE, "StartService");
            if (null != mWakeLock) {
                mWakeLock.acquire();//获取到锁
            }
        }
    }

    //屏幕解锁
    public static void cutScreenLock(Context context) {
        KeyguardManager km = (KeyguardManager) context.getSystemService(Context.KEYGUARD_SERVICE);
        KeyguardManager.KeyguardLock kl = km.newKeyguardLock("StartupReceiver");//参数是LogCat里用的Tag
        kl.disableKeyguard();
    }

    //释放设备电源锁
    public static void releaseWakeLock() {
        if (null != mWakeLock) {
            mWakeLock.release();
            mWakeLock = null;
        }
    }
}

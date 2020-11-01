package com.hgapp.betnew.upgrade.downunit;


import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Process;
import android.telephony.TelephonyManager;

import com.hgapp.common.util.GameLog;

import java.util.concurrent.PriorityBlockingQueue;
import java.util.concurrent.ThreadPoolExecutor;
import java.util.concurrent.TimeUnit;

import static android.os.Process.THREAD_PRIORITY_BACKGROUND;

/**
 * The default {@link java.util.concurrent.ExecutorService} used for new  instances.
 * <p>
 * Exists as a custom type so that we can differentiate the use of defaults versus a user-supplied
 * instance.
 * 定制线程池，使其根据网络状况变化，以得到更好的线程池
 */
class PhoneExecutorService extends ThreadPoolExecutor {
    public static final int DEFAULT_THREAD_COUNT = 3;

    public PhoneExecutorService() {
        super(DEFAULT_THREAD_COUNT, DEFAULT_THREAD_COUNT, 5, TimeUnit.MILLISECONDS,
                new PriorityBlockingQueue<Runnable>(), new PhoneFactory());
    }

    //在监听到网络变化(接收Connectivity_change广播)的时候使用
    public void adjustTheadCount(Context context)
    {
        ConnectivityManager manager = (ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE);
        adjustThreadCount(manager.getActiveNetworkInfo());

    }

    public void adjustThreadCount(NetworkInfo info) {
        if (info == null || !info.isConnectedOrConnecting()) {
            setThreadCount(DEFAULT_THREAD_COUNT);
            return;
        }
        switch (info.getType()) {
            case ConnectivityManager.TYPE_WIFI:
            case ConnectivityManager.TYPE_WIMAX:
            case ConnectivityManager.TYPE_ETHERNET:
                setThreadCount(4);
                break;
            case ConnectivityManager.TYPE_MOBILE:
                switch (info.getSubtype()) {
                    case TelephonyManager.NETWORK_TYPE_LTE:  // 4G
                    case TelephonyManager.NETWORK_TYPE_HSPAP:
                    case TelephonyManager.NETWORK_TYPE_EHRPD:
                        setThreadCount(3);
                        break;
                    case TelephonyManager.NETWORK_TYPE_UMTS: // 3G
                    case TelephonyManager.NETWORK_TYPE_CDMA:
                    case TelephonyManager.NETWORK_TYPE_EVDO_0:
                    case TelephonyManager.NETWORK_TYPE_EVDO_A:
                    case TelephonyManager.NETWORK_TYPE_EVDO_B:
                        setThreadCount(2);
                        break;
                    case TelephonyManager.NETWORK_TYPE_GPRS: // 2G
                    case TelephonyManager.NETWORK_TYPE_EDGE:
                        setThreadCount(1);
                        break;
                    default:
                        setThreadCount(DEFAULT_THREAD_COUNT);
                }
                break;
            default:
                setThreadCount(DEFAULT_THREAD_COUNT);
        }
    }

    private void setThreadCount(int threadCount) {
        setCorePoolSize(threadCount);
        setMaximumPoolSize(threadCount);
    }
/*
    @Override
    public Future<?> submit(Runnable task) {

        return super.submit(task);
    }*/


    static class PhoneFactory implements java.util.concurrent.ThreadFactory {
        @SuppressWarnings("NullableProblems")
        public Thread newThread(Runnable r) {
            return new MyThread(r);
        }
    }

    private static class MyThread extends Thread {
        public MyThread(Runnable r) {
            super(r);
            GameLog.log("create download thread[" + Thread.currentThread().getName()+"]");
        }

        @Override public void run() {
            Process.setThreadPriority(THREAD_PRIORITY_BACKGROUND);
            super.run();
        }
    }
}

package com.gmcp.gm.ui.main.upgrade.downunit;

import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.os.IBinder;


import com.gmcp.gm.common.utils.Timber;
import com.gmcp.gm.common.utils.Utils;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Daniel on 2017/5/29.
 */

public class AppDownloadServiceBinder {

    private Context context;
    private AppDownloadService appDownloadService;
    private static AppDownloadServiceBinder binder = new AppDownloadServiceBinder();
    private AppDownloadServiceBinder()
    {
        context = Utils.getContext();
    }

    public static AppDownloadServiceBinder getBinder()
    {
        return binder;
    }

    public void bind()
    {
        if(null == appDownloadService)
        {
            Timber.i("bind download service");
            Intent intent = new Intent(context,AppDownloadService.class);
            context.bindService(intent,serviceConnection,Context.BIND_AUTO_CREATE);
        }

    }

    public void forceStop()
    {
        if(null != appDownloadService)
        {
            Timber.d("强制解绑下载服务");
            appDownloadService.forceStop();
        }
    }
    public void unbind()
    {
        if(null != appDownloadService)
        {
            if(appDownloadService.isDestroyEnabled())
            {
                Timber.d("解绑下载服务");
                context.unbindService(serviceConnection);
                appDownloadService = null;
            }
            else
            {
                Timber.e("有正在进行的下载任务,不能解绑下载服务");
            }

        }
        else
        {
            Timber.e("下载服务引用为null,不能解绑下载服务");
        }
    }

    public List<String> listenDownloadingTask(FileDownloaderListener listener)
    {
        if(null != appDownloadService)
        {
            return appDownloadService.listenDownloadingTask(listener);
        }
        return new ArrayList<>(0);
    }
    public void registerListener(String packageName,FileDownloaderListener listener)
    {
        if(null != appDownloadService)
        {
            appDownloadService.registerListener(packageName,listener);
        }
    }

    public void unregisterListener(String packageName)
    {
        if(null != appDownloadService)
        {
            appDownloadService.unregisterListener(packageName);
        }
    }
    public void unregisterAllListenerExcept(String packageName)
    {
        if(null != appDownloadService)
        {
            appDownloadService.unregisterAllListenerExcept(packageName);
        }
    }

    public void downloadUpgradeApp(DownloadIntent intent)
    {
        if(null == appDownloadService)
        {
            return;
        }
        appDownloadService.downloadUpgradeApp(intent);
    }

    /*public boolean downloadApp(LocalAppItem item)
    {
        if(null == appDownloadService)
        {
            return false;
        }
        appDownloadService.download(new DownloadIntent(item));
        return true;
    }*/

    private ServiceConnection serviceConnection = new ServiceConnection() {
        @Override
        public void onServiceConnected(ComponentName name, IBinder service) {
            Timber.i("success to bind download service");
            AppDownloadService.LocalBinder localBinder = (AppDownloadService.LocalBinder)service;
            appDownloadService = localBinder.getService();
        }

        @Override
        public void onServiceDisconnected(ComponentName name) {
            Timber.e("意外断开下载app服务的绑定");
            appDownloadService = null;
        }
    };
}

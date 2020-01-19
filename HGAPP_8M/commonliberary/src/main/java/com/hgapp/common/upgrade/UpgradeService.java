package com.hgapp.common.upgrade;

import android.app.IntentService;
import android.content.Context;
import android.content.Intent;
import android.os.Handler;
import android.os.Looper;

import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.Record;

import java.io.File;

/**
 * Created by Nereus on 2017/4/17.
 * 升级版本的service，分为两步 检测远程版本信息，下载远程版本文件
 * 仅需提供两个静态url即可。
 * 例如：1.http://www.ag8.com/static/apk/android_version.json
 * json包含version (版本号，例如1.2)和content(更新日志)两部分
         2.http://www.ag8.com/static/apk/pnandroidapp.apk
 */

public class UpgradeService extends IntentService {

    public static final String TASK_UPGRADE = "task_upgrade";
    private static Handler uiHandler = new Handler(Looper.getMainLooper());
    private static boolean isUpgrading = false;
    public static final String ACTION_DOWNLOAD_COMPLETE = "action_download_complete";
    public static final String EXTRA_UPGRADE_FILE="extra_upgrade_file";
    public static final String EXTRA_UPGRADE_CONTENT = "extra_upgrade_content";
    public static final String EXTRA_UPGRADE_VERSION = "extra_upgrade_version";
    public UpgradeService() {
        super("taskservice");
    }

    /**
     * 启动升级，升级完毕后将受到广播通知，如下
     * action {@linkplain UpgradeService#ACTION_DOWNLOAD_COMPLETE}
     * extra 文件路径{@linkplain UpgradeService#EXTRA_UPGRADE_FILE}
     * 更新日志{@linkplain UpgradeService#EXTRA_UPGRADE_CONTENT}
     * 升级版本{@linkplain UpgradeService#EXTRA_UPGRADE_VERSION}
     * @param context
     * @param examineVersionurl
     * @param apkDownloadUrl
     */
    public static void startUpgrade(Context context,String examineVersionurl,String apkDownloadUrl)
    {
        if(!UpgradeService.isUpgrading)
        {
            Intent intent = new Intent(context,UpgradeService.class);
            intent.setAction(UpgradeService.TASK_UPGRADE);
            intent.putExtra("examineVersionurl",examineVersionurl);
            intent.putExtra("apkDownloadUrl",apkDownloadUrl);
            context.startService(intent);
        }
    }

    public void onCreate() {
        GameLog.log("onCreate @UpgradeService");
        super.onCreate();
    }

    @Override
    protected void onHandleIntent(Intent intent) {

        if(null != intent && null != intent.getAction())
        {
            if(TASK_UPGRADE.equals(intent.getAction()) && !isUpgrading)
            {
                String examineVersionurl = intent.getStringExtra("examineVersionurl");
                String apkDownloadUrl = intent.getStringExtra("apkDownloadUrl");
                beginUpgrade(examineVersionurl,apkDownloadUrl);
            }
        }
    }

    private  void beginUpgrade(String examineVersionurl,String apkDownloadUrl)
    {
        isUpgrading = true;
        UpgradeInfo curUpgradeinfo = null;
        UpgradeModel upgradeModel = new UpgradeModel();
        if(upgradeModel.isUpgradeAvailable(getApplicationContext(), examineVersionurl,curUpgradeinfo))
        {
            Record record = new Record(getApplicationContext());
            UpgradeInfo lastRemoteUpgradeInfo = record.getLastRemoteUpgradeVersion();
            File apkFile = upgradeModel.getFile(getApplicationContext());
            if(null != lastRemoteUpgradeInfo && curUpgradeinfo.version.equals(lastRemoteUpgradeInfo.version) && null != apkFile && apkFile.exists())
            {
                //已经下载最新apk
                GameLog.log("newest upgrade apk has been downloaded last time");
                onCompleteDownloadApkFile(curUpgradeinfo,apkFile);
                return;
            }
            final File file = upgradeModel.download(apkDownloadUrl,getApplicationContext());
            if(null != file)
            {
                //下载升级apk完毕
                GameLog.log("complete download upgrade apk");
                if(null != curUpgradeinfo)
                {
                    record.saveLastRemoteUpgradeVersion(curUpgradeinfo);
                }
                onCompleteDownloadApkFile(curUpgradeinfo,file);
            }
            else
            {
                GameLog.loge("download upgrade file error");
            }
        }
        else
        {
            GameLog.log("no upgrade file available");
        }

        isUpgrading = false;
    }

    private void onCompleteDownloadApkFile(UpgradeInfo upgradeInfo,File file)
    {

        Intent intent = new Intent();
        intent.setAction(ACTION_DOWNLOAD_COMPLETE);
        intent.putExtra(EXTRA_UPGRADE_FILE,file.getAbsolutePath());
        intent.putExtra(EXTRA_UPGRADE_CONTENT,upgradeInfo.content);
        intent.putExtra(EXTRA_UPGRADE_VERSION,upgradeInfo.version);
        getApplicationContext().sendBroadcast(intent);
    }
}

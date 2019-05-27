package com.cfcp.a01.ui.main.upgrade;

import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.TextView;


import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseDialogFragment;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.InstallHelper;
import com.cfcp.a01.common.utils.Timber;
import com.cfcp.a01.data.CheckUpgradeResult;
import com.cfcp.a01.ui.main.upgrade.downunit.AppDownloadServiceBinder;
import com.cfcp.a01.ui.main.upgrade.downunit.DownloadIntent;
import com.cfcp.a01.ui.main.upgrade.downunit.DownloadProgress;
import com.cfcp.a01.ui.main.upgrade.downunit.FileDownloaderListener;

import org.greenrobot.eventbus.EventBus;

import java.io.File;

import butterknife.BindView;
import butterknife.OnClick;

/**
 * Created by Daniel on 2018/8/17.
 */

public class UpgradeDialog extends BaseDialogFragment {

    public static final String EXTRA_UPGRADE = "extra_upgrade";
    @BindView(R.id.tv_titleupgrade)
    TextView tvTitle;
    @BindView(R.id.tv_msg_upgrade)
    TextView tvMsgUpgrade;
    @BindView(R.id.pb_progress_upgrade)
    ProgressBar progressBar;
    @BindView(R.id.tv_size_upgrade)
    TextView tvSize;
    @BindView(R.id.btn_confirm_upgrade)
    TextView btnConfirm;
    @BindView(R.id.group_size)
    View groupSize;
    @BindView(R.id.btn_cancel_upgrade)
    TextView btnCancel;
    private DownloadIntent intent;
    private  CheckUpgradeResult checkUpgradeResult;
    public static UpgradeDialog newInstance(CheckUpgradeResult checkUpgradeResult) {
        Bundle bundle = new Bundle();
        bundle.putParcelable(EXTRA_UPGRADE, checkUpgradeResult);

        UpgradeDialog dialog = new UpgradeDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int setLayoutId() {
        return R.layout.dialog_upgrade;
    }

    @Override
    protected void setEvents(View view, Bundle bundle) {
        checkUpgradeResult = (CheckUpgradeResult) getArguments().getParcelable(EXTRA_UPGRADE);

        if (null != checkUpgradeResult ) {
            tvMsgUpgrade.setText(checkUpgradeResult.getDescription());
            //tvSize.setText("0M/" + checkUpgradeResult.getFile_size());
        }

        AppDownloadServiceBinder.getBinder().bind();
    }

    @Override
    public void onStop()
    {
        super.onStop();
        AppDownloadServiceBinder.getBinder().unregisterListener(getContext().getPackageName());
    }

    @OnClick({R.id.btn_cancel_upgrade, R.id.btn_confirm_upgrade})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btn_cancel_upgrade:
                dismiss();
                break;
            case R.id.btn_confirm_upgrade:
                //兼容8.0
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
                    boolean hasInstallPermission = getActivity().getPackageManager().canRequestPackageInstalls();
                    if (!hasInstallPermission) {
                        InstallHelper.startInstallPermissionSettingActivity(getContext());
                        return;
                    }
                }
                //启动下载
                checkUpgradeResult.setFile_path(checkUpgradeResult.getFile_path());
                if(btnConfirm.getText().toString().equals("马上安装")){
                    File file = new File(intent.dir,intent.fileName);
                    InstallHelper.attemptIntallApp(getContext(),file);
                    return;
                }
                //checkUpgradeResult.setFile_path("https://hg006668.firebaseapp.com/d/cfqp.apk");
                if (null != checkUpgradeResult) {
                    intent = new DownloadIntent();
                    intent.packageName = getContext().getPackageName();
                    intent.dir = getContext().getCacheDir().getAbsolutePath();
                    intent.fileName = getContext().getPackageName()+".apk";
                    intent.tempFileName = "temp" + intent.fileName;
                    intent.url = checkUpgradeResult.getFile_path();
                    AppDownloadServiceBinder binder = AppDownloadServiceBinder.getBinder();
                    binder.registerListener(intent.packageName,fileDownloaderListener);
                    binder.downloadUpgradeApp(intent);
                }

                /*DownloadManager manager = DownloadManager.getInstance(this.getContext());
                manager.setApkName("Wandoujia_web_seo_google_homepage.apk")
                        .setApkUrl("http://ucan.25pp.com/Wandoujia_web_seo_google_homepage.apk")
                        .setDownloadPath(Environment.getExternalStorageDirectory() + "/Wandoujia")
                        .setSmallIcon(R.mipmap.ic_launcher)
                        //可设置，可不设置
                        .download();*/
                break;
        }
    }

    private FileDownloaderListener fileDownloaderListener = new FileDownloaderListener()
    {

        @Override
        public void onBegin(String packagename) {
            groupSize.setVisibility(View.VISIBLE);
            progressBar.setVisibility(View.VISIBLE);
            btnCancel.setEnabled(false);
        }

        @Override
        public void onProgress(DownloadProgress progress) {
            Timber.i("升级进度:%s",progress.toString());
            GameLog.log("升级进度 totalSize ["+progress.totalSize+ "]  sofarSize ["+progress.sofarSize+"] percent  -> "+progress.percent);
            progressBar.setProgress(progress.percent);
            //tvSize.setText(progress.getSofarSizeInM()+"/" + progress.getTotalSizeInM());
        }

        @Override
        public void onComplete(String packagename) {
            btnCancel.setEnabled(true);
            if(null != intent)
            {
                File file = new File(intent.dir,intent.fileName);
                InstallHelper.attemptIntallApp(getContext(),file);
                tvMsgUpgrade.setText("亲，更新版本体验会更好！");
                btnConfirm.setText("马上安装");
                //dismiss();
            }
            AppDownloadServiceBinder.getBinder().unbind();
        }

        @Override
        public void onError(String packagename, int errcode) {
            btnConfirm.setText("重新下载");
            tvMsgUpgrade.setText("更新错误，请重新下载");
            groupSize.setVisibility(View.GONE);
            progressBar.setVisibility(View.GONE);
            btnCancel.setEnabled(true);
        }
    };
}

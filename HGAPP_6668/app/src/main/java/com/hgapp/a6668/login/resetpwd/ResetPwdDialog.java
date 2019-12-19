package com.hgapp.a6668.login.resetpwd;

import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseDialogFragment;
import com.hgapp.a6668.data.CheckUpgradeResult;
import com.hgapp.a6668.login.fastregister.RegisterFragment;
import com.hgapp.a6668.upgrade.downunit.AppDownloadServiceBinder;
import com.hgapp.a6668.upgrade.downunit.DownloadIntent;

import org.greenrobot.eventbus.EventBus;

import java.io.File;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

/**
 * Created by Daniel on 2019/8/17.
 */

public class ResetPwdDialog extends HGBaseDialogFragment {

    public static final String EXTRA_UPGRADE = "extra_upgrade";
    @BindView(R.id.tv_titleupgrade)
    TextView tvTitle;
    @BindView(R.id.tv_msg_upgrade)
    TextView tvMsgUpgrade;
    @BindView(R.id.btn_confirm_upgrade)
    TextView btnConfirm;
    @BindView(R.id.btn_cancel_upgrade)
    TextView btnCancel;
    public static ResetPwdDialog newInstance(String message) {
        Bundle bundle = new Bundle();
        bundle.putString(EXTRA_UPGRADE, message);
        ResetPwdDialog dialog = new ResetPwdDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.dialog_reset_pwd;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        String checkUpgradeResult = getArguments().getString(EXTRA_UPGRADE);
        tvMsgUpgrade.setText(checkUpgradeResult);
    }

    @Override
    public void onStop()
    {
        super.onStop();
    }

    @OnClick({R.id.btn_cancel_upgrade, R.id.btn_confirm_upgrade})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btn_cancel_upgrade:
                dismiss();
                break;
            case R.id.btn_confirm_upgrade:
                dismiss();
                EventBus.getDefault().post(new StartBrotherEvent(ResetPwdFragment.newInstance(), SupportFragment.SINGLETASK));
                break;
        }
    }

}

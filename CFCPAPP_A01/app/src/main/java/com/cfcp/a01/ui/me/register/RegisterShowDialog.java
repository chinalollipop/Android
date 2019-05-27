package com.cfcp.a01.ui.me.register;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseDialogFragment;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class RegisterShowDialog extends BaseDialogFragment {

    public static final String PARAM0 = "param0";
    String getParam0 = "";

    RegisterNameEvent data;
    @BindView(R.id.registerDType)
    TextView registerDType;
    @BindView(R.id.registerDUserName)
    TextView registerDUserName;
    @BindView(R.id.registerDPwd)
    TextView registerDPwd;
    @BindView(R.id.registerDNickName)
    TextView registerDNickName;
    @BindView(R.id.registerDName)
    TextView registerDName;
    @BindView(R.id.registerDSubmit)
    TextView registerDSubmit;
    @BindView(R.id.registerDCancel)
    TextView registerDCancel;

    public static RegisterShowDialog newInstance(RegisterNameEvent data, String param1) {
        Bundle bundle = new Bundle();
        bundle.putParcelable(PARAM0, data);
        RegisterShowDialog dialog = new RegisterShowDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int setLayoutId() {
        return R.layout.dialog_register_show;
    }

    @Override
    protected void setEvents(View view, Bundle bundle) {
        EventBus.getDefault().register(this);
        data = getArguments().getParcelable(PARAM0);
        registerDType.setText(data.type.equals("1")?"代理":"会员");
        registerDUserName.setText(data.accountName);
        registerDPwd.setText(data.pwd);
        registerDNickName.setText(data.nickName);
        registerDName.setText(data.className);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(CancelRegisterEvent cancelRegisterEvent){
        hide();
    }

    @OnClick({R.id.registerDSubmit, R.id.registerDCancel})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.registerDCancel:
                this.dismiss();
                break;
            case R.id.registerDSubmit:
                EventBus.getDefault().post(data);
                break;
        }
    }

}

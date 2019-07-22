package com.qpweb.a01.ui.loginhome.fastregister;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;

import com.alibaba.fastjson.JSON;
import com.qpweb.a01.Injections;
import com.qpweb.a01.QPApplication;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.ui.loginhome.LoginHomeActivity;
import com.qpweb.a01.ui.loginhome.LoginSuccessEvent;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class RegisterFragment extends BaseDialogFragment implements RegisterContract.View {

    RegisterContract.Presenter presenter;
    @BindView(R.id.registerAccount)
    EditText registerAccount;
    @BindView(R.id.registerPwd)
    EditText registerPwd;
    @BindView(R.id.registerPwd2)
    EditText registerPwd2;
    @BindView(R.id.registerSubmit)
    ImageView registerSubmit;
    @BindView(R.id.registerClose)
    ImageView registerClose;

    public static RegisterFragment newInstance() {
        Bundle bundle = new Bundle();
        RegisterFragment dialog = new RegisterFragment();
        dialog.setArguments(bundle);
        Injections.inject(dialog, null);
        return dialog;
    }

    @Override
    public int setLayoutId() {
        return R.layout.register_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
    }

    private void onCheckDataAndSubmit() {
        String registerAccounts = registerAccount.getText().toString().trim();
        String registerPwds = registerPwd.getText().toString().trim();
        String registerPwd2s = registerPwd2.getText().toString().trim();
        if (Check.isEmpty(registerAccounts)) {
            showMessage("请输入合法的用户账号");
            return;
        }
        if (Check.isEmpty(registerPwds)) {
            showMessage("请输入会员密码");
            return;
        }
        if (Check.isEmpty(registerPwd2s)) {
            showMessage("请再次输入会员密码");
            return;
        }
        if (!registerPwds.equals(registerPwd2s)) {
            showMessage("两次输入的会员密码不一致");
            return;
        }
        presenter.postRegisterMember(QPConstant.PRODUCT_PLATFORM, "register", QPApplication.instance().getCommentData(), registerAccounts, registerPwds, registerPwd2s, "ZRN7", "1");
    }

    @Override
    public void postRegisterMemberResult(LoginResult loginResult) {
        GameLog.log("用户注册成功 别名是 " + loginResult.getAlias());
        showMessage("注册成功！");
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT,loginResult.getUserName());
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_PWD,loginResult.getPassWord());
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_ALIAS,loginResult.getAlias());
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_MONEY,loginResult.getMoney());
        ACache.get(getContext()).put("loginResult", JSON.toJSONString(loginResult));
        EventBus.getDefault().post(new LoginSuccessEvent());
        startActivity(new Intent(getContext(), LoginHomeActivity.class));
        hide();
    }

    @Override
    public void setPresenter(RegisterContract.Presenter presenter) {
        this.presenter = presenter;
    }


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @OnClick({R.id.registerSubmit, R.id.registerClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.registerSubmit:
                onCheckDataAndSubmit();
                break;
            case R.id.registerClose:
                dismiss();
                break;
        }
    }
}

package com.qpweb.a01.ui.loginhome.sign;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class SignTodayFragment extends BaseDialogFragment implements SignTodayContract.View{

    @BindView(R.id.loginAccount)
    EditText loginAccount;
    @BindView(R.id.loginPwd)
    EditText loginPwd;
    @BindView(R.id.loginRemeberPwd)
    CheckBox loginRemeberPwd;
    @BindView(R.id.loginForgetPwd)
    TextView loginForgetPwd;
    @BindView(R.id.loginSubmit)
    ImageView loginSubmit;
    @BindView(R.id.loginClose)
    ImageView loginClose;


    SignTodayContract.Presenter presenter;

    public static SignTodayFragment newInstance() {
        Bundle bundle = new Bundle();
        SignTodayFragment loginFragment = new SignTodayFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.sign_today_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
        if(!Check.isEmpty(userName)){
            loginAccount.setText(userName);
        }
        if(!Check.isEmpty(pwd)){
            loginRemeberPwd.setChecked(true);
            loginPwd.setText(pwd);
        }else{
            loginRemeberPwd.setChecked(false);
        }
    }

    @OnClick({R.id.loginRemeberPwd, R.id.loginForgetPwd, R.id.loginSubmit, R.id.loginClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.loginRemeberPwd:
                break;
            case R.id.loginForgetPwd:
                break;
            case R.id.loginSubmit:
                onCheckAndSubmit();
                break;
            case R.id.loginClose:
                hide();
                break;
        }
    }

    private void onCheckAndSubmit() {
        String loginAccounts = loginAccount.getText().toString().trim();
        String loginPwdPwds = loginPwd.getText().toString().trim();
        if(Check.isEmpty(loginAccounts)){
            showMessage("请输入合法的用户账号");
        }
        if(Check.isEmpty(loginPwdPwds)){
            showMessage("请输入密码");
        }
        presenter.postLogin("",loginAccounts,loginPwdPwds);
    }

    @Override
    public void postLoginResult(LoginResult loginResult) {
        String loginAccounts = loginAccount.getText().toString().trim();
        String loginPwdPwds = loginPwd.getText().toString().trim();
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT,loginAccounts);
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_PWD,loginPwdPwds);
        showMessage("登录成功！");
        EventBus.getDefault().post(loginResult);
        hide();
    }

    @Override
    public void setPresenter(SignTodayContract.Presenter presenter) {
        this.presenter  = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}

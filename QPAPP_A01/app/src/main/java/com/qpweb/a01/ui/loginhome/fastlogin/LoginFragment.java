package com.qpweb.a01.ui.loginhome.fastlogin;

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
import com.qpweb.a01.ui.loginhome.fastregister.RegisterContract;
import com.qpweb.a01.utils.Check;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class LoginFragment extends BaseDialogFragment implements LoginContract.View{

    @BindView(R.id.loginAccount)
    EditText loginAccount;
    @BindView(R.id.registerPwd)
    EditText registerPwd;
    @BindView(R.id.loginRemeberPwd)
    CheckBox loginRemeberPwd;
    @BindView(R.id.loginForgetPwd)
    TextView loginForgetPwd;
    @BindView(R.id.loginSubmit)
    ImageView loginSubmit;
    @BindView(R.id.loginClose)
    ImageView loginClose;


    LoginContract.Presenter presenter;

    public static LoginFragment newInstance() {
        Bundle bundle = new Bundle();
        LoginFragment loginFragment = new LoginFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.login_fragment;
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

    @OnClick({R.id.loginRemeberPwd, R.id.loginForgetPwd, R.id.loginSubmit, R.id.loginClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.loginRemeberPwd:
                break;
            case R.id.loginForgetPwd:
                break;
            case R.id.loginSubmit:
                onCheckAndSubmit();
                hide();
                break;
            case R.id.loginClose:
                hide();
                break;
        }
    }

    private void onCheckAndSubmit() {
        String loginAccounts = loginAccount.getText().toString().trim();
        String registerPwds = registerPwd.getText().toString().trim();
        if(Check.isEmpty(loginAccounts)){
            showMessage("请输入合法的用户账号");
        }
        if(Check.isEmpty(registerPwds)){
            showMessage("请输入密码");
        }
        presenter.postLogin("",loginAccounts,registerPwds);
    }

    @Override
    public void postLoginResult(LoginResult loginResult) {
        showMessage("登录成功！");
    }

    @Override
    public void setPresenter(LoginContract.Presenter presenter) {
        this.presenter  = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}

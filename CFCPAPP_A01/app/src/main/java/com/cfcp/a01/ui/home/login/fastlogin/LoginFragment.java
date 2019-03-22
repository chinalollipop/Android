package com.cfcp.a01.ui.home.login.fastlogin;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.TextView;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.http.util.Md5Utils;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.ui.home.login.fastregister.RegisterFragment;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

public class LoginFragment extends BaseFragment implements LoginContract.View {
    LoginContract.Presenter presenter;

    @BindView(R.id.loginBack)
    NTitleBar loginBack;
    @BindView(R.id.loginName)
    EditText loginName;
    @BindView(R.id.loginPwd)
    EditText loginPwd;
    @BindView(R.id.loginRememberPwd)
    CheckBox loginRememberPwd;
    @BindView(R.id.loginForgetPwd)
    TextView loginForgetPwd;
    @BindView(R.id.loginGoSubmitX)
    TextView loginGoSubmit;
    @BindView(R.id.loginGoRegister)
    TextView loginGoRegister;
    @BindView(R.id.loginGoDemo)
    TextView loginGoDemo;

    public static LoginFragment newInstance() {
        LoginFragment loginFragment = new LoginFragment();
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_login_home;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        loginBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                EventBus.getDefault().post(new LogoutResult("您已登出!"));
                finish();
            }
        });
        String userName = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_PWD);
        if (!Check.isEmpty(userName)) {
            loginName.setText(userName);
        }
        if (!Check.isEmpty(pwd)) {
            loginRememberPwd.setChecked(true);
            loginPwd.setText(pwd);
        } else {
            loginRememberPwd.setChecked(false);
        }
    }

    private void onSubmit() {
        String uName = loginName.getText().toString().trim();
        String uPwd = loginPwd.getText().toString().trim();

        if (Check.isEmpty(uName)) {
            showMessage("请输入账号");
        }
        if (Check.isEmpty(uPwd)) {
            showMessage("请输入密码");
        }
        uPwd = Md5Utils.getMd5(Md5Utils.getMd5(Md5Utils.getMd5(uName + uPwd)));
        presenter.postLogin("", uName, uPwd);
        //
    }

    @Override
    public boolean onBackPressedSupport() {
        String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        GameLog.log(" LoginFragment onBackPressedSupport   个人的token是 "+token );
        if(Check.isEmpty(token)){
            EventBus.getDefault().post(new LogoutResult("您已登出!"));
        }
        //finish();  如果打开会白板
        return super.onBackPressedSupport();
    }

    @Override
    public void postLoginResult(LoginResult loginResult) {
        //保存用户登录成功之后的消息
        if (loginRememberPwd.isChecked()) {
            ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PWD, loginPwd.getText().toString());
        } else {
            ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PWD, "");
        }
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_ACCOUNT, loginResult.getUsername());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, loginResult.getToken());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_BALANCE,loginResult.getAbalance());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PARENT_ID,loginResult.getId()+"");
        EventBus.getDefault().post(loginResult);
        finish();
    }

    @Override
    public void setPresenter(LoginContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @OnClick({R.id.loginForgetPwd, R.id.loginGoSubmitX, R.id.loginGoRegister, R.id.loginGoDemo})
    public void onViewClicked(View view) {

        switch (view.getId()) {
            case R.id.loginForgetPwd:
                break;
            case R.id.loginGoSubmitX:
                onSubmit();
                //EventBus.getDefault().post(new LoginResult());
                break;
            case R.id.loginGoRegister:
                //处理用户登录接口的数据请求
                EventBus.getDefault().post(new StartBrotherEvent(RegisterFragment.newInstance(), SupportFragment.SINGLETASK));
                break;
            case R.id.loginGoDemo:
                break;
        }
    }
}

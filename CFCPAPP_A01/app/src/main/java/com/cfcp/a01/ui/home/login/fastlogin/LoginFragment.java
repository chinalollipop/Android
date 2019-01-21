package com.cfcp.a01.ui.home.login.fastlogin;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.TextView;

import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.http.util.Md5Utils;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.home.login.fastregister.RegisterFragment;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

public class LoginFragment extends BaseFragment implements LoginContract.View {
    @BindView(R.id.loginBack)
    NTitleBar loginBack;
    LoginContract.Presenter presenter;
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
        LoginFragment homeFragment = new LoginFragment();
        Injections.inject(homeFragment, null);
        return homeFragment;
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
                finish();
            }
        });
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
        finish();
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
        uPwd = Md5Utils.getMd5(Md5Utils.getMd5(Md5Utils.getMd5(uName+uPwd)));
        presenter.postLogin("", uName, uPwd);
        //
    }

    @Override
    public void postLoginResult(LoginResult loginResult) {
        EventBus.getDefault().post(loginResult);
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

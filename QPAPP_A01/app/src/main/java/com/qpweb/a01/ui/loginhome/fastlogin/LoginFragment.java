package com.qpweb.a01.ui.loginhome.fastlogin;

import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.qpweb.a01.Injections;
import com.qpweb.a01.QPApplication;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.ui.loginhome.LoginHomeActivity;
import com.qpweb.a01.ui.loginhome.LoginSuccessEvent;
import com.qpweb.a01.ui.loginhome.fastregister.RegisterContract;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class LoginFragment extends BaseDialogFragment implements LoginContract.View{

    @BindView(R.id.flayBg)
    FrameLayout flayBg;
    @BindView(R.id.loginAccountTitle)
    TextView loginAccountTitle;
    @BindView(R.id.loginAccount)
    EditText loginAccount;
    @BindView(R.id.loginPwd)
    EditText loginPwd;
    @BindView(R.id.loginCode)
    EditText loginCode;
    @BindView(R.id.loginSubmit)
    ImageView loginSubmit;
    @BindView(R.id.layoutPwd)
    LinearLayout layoutPwd;
    @BindView(R.id.layoutCode)
    LinearLayout layoutCode;
    @BindView(R.id.loginClose)
    ImageView loginClose;
    @BindView(R.id.loginVerifyCode)
    TextView loginVerifyCode;
    @BindView(R.id.loginGetPhoneCode)
    TextView loginGetPhoneCode;
    @BindView(R.id.loginGetPhoneCodeTView)
    TextView loginGetPhoneCodeTView;
    private int loginType = 0;//账号登录 0  验证码登录
    private MyCountDownTimer mCountDownTimer;

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
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
        if(!Check.isEmpty(userName)){
            loginAccount.setText(userName);
        }
        loginVerifyCode.performClick();
    }

    @OnClick({ R.id.loginSubmit, R.id.loginClose,R.id.loginVerifyCode,R.id.loginGetPhoneCode})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.loginSubmit:
                onCheckAndSubmit();
                break;
            case R.id.loginClose:
                hide();
                break;
            case R.id.loginGetPhoneCode:
                onGetCode();
                break;
            case R.id.loginVerifyCode:
                if(loginType==1){
                    loginType = 0;
                    loginVerifyCode.setText("账号登录");
                    loginAccountTitle.setText("手机号");
                    loginAccount.setHint("请输入手机号码");
                    layoutCode.setVisibility(View.VISIBLE);
                    layoutPwd.setVisibility(View.GONE);
                    flayBg.setBackground(getResources().getDrawable(R.mipmap.login_phone));
                }else{
                    loginType = 1;
                    loginVerifyCode.setText("验证码登录");
                    loginAccountTitle.setText("账号/手机号");
                    loginAccount.setHint("请输入账号或手机号码");
                    layoutCode.setVisibility(View.GONE);
                    layoutPwd.setVisibility(View.VISIBLE);
                    flayBg.setBackground(getResources().getDrawable(R.mipmap.login_account_bg));
                }
                break;
        }
    }

    class MyCountDownTimer extends CountDownTimer {
        /**
         * @param millisInFuture
         *      表示以「 毫秒 」为单位倒计时的总数
         *      例如 millisInFuture = 1000 表示1秒
         *
         * @param countDownInterval
         *      表示 间隔 多少微秒 调用一次 onTick()
         *      例如: countDownInterval = 1000 ; 表示每 1000 毫秒调用一次 onTick()
         *
         */

        public MyCountDownTimer(long millisInFuture, long countDownInterval) {
            super(millisInFuture, countDownInterval);
        }


        public void onFinish() {
            loginGetPhoneCode.setVisibility(View.VISIBLE);
            loginGetPhoneCodeTView.setVisibility(View.GONE);
            loginGetPhoneCodeTView.setText("60秒重试");
        }

        public void onTick(long millisUntilFinished) {
            if(!Check.isNull(loginGetPhoneCodeTView)){
                loginGetPhoneCodeTView.setText( millisUntilFinished / 1000 + "秒重试");
            }
        }

    }

    private void onGetCode(){
        String loginAccounts = loginAccount.getText().toString().trim();
        if(Check.isEmpty(loginAccounts)){
            showMessage("请输入有效的用户手机账号");
            return;
        }
        presenter.postPhone("",loginAccounts,"");
    }

    private void onCheckAndSubmit() {
        String loginAccounts = loginAccount.getText().toString().trim();
        String loginPwdPwds = loginPwd.getText().toString().trim();
        String loginCodes = loginCode.getText().toString().trim();
        if(Check.isEmpty(loginAccounts)){
            showMessage("请输入合法的用户账号");
            return;
        }
        if(loginType==0){
            if(Check.isEmpty(loginCodes)){
                showMessage("请输入验证码");
                return;
            }
            presenter.postLoginPhone("",loginAccounts,loginCodes, QPApplication.instance().getCommentData(),QPApplication.instance().getCommentData());
        }else{
            if(Check.isEmpty(loginPwdPwds)){
                showMessage("请输入密码");
                return;
            }
            presenter.postLogin("",loginAccounts,loginPwdPwds);
        }
    }

    @Override
    public void postLoginResult(LoginResult loginResult) {
        ACache.get(getContext()).put("isChangeUser","NO");
        String loginAccounts = loginAccount.getText().toString().trim();
        String loginPwdPwds = loginPwd.getText().toString().trim();
        ACache.get(getContext()).put("loginResult", JSON.toJSONString(loginResult));
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT,loginAccounts);
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_ALIAS,loginResult.getAlias());
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_PWD,loginPwdPwds);
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_MONEY,loginResult.getMoney());
        showMessage("登录成功！");
        EventBus.getDefault().post(new LoginSuccessEvent());
        Intent intent = new Intent(getContext(), LoginHomeActivity.class);
        intent.putExtra("LoginResult",loginResult);
        startActivity(intent);
        hide();
    }

    @Override
    public void postPhoneResult(String errorMessage) {
        showMessage(errorMessage);
        loginGetPhoneCode.setVisibility(View.GONE);
        loginGetPhoneCodeTView.setVisibility(View.VISIBLE);
        mCountDownTimer = new MyCountDownTimer(60000, 1000);
        mCountDownTimer.start();
    }

    @Override
    public void setPresenter(LoginContract.Presenter presenter) {
        this.presenter  = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if (mCountDownTimer != null) {
            mCountDownTimer.cancel();
            mCountDownTimer = null;
        }
    }
}

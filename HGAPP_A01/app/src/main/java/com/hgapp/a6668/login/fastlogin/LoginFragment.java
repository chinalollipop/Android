package com.hgapp.a6668.login.fastlogin;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.InputType;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.InputCodeLayout;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.common.widgets.VerificationCodeView;
import com.hgapp.a6668.data.LoginResult;
import com.hgapp.a6668.data.SportsPlayMethodRBResult;
import com.hgapp.a6668.homepage.handicap.BottombarViewManager;
import com.hgapp.a6668.homepage.handicap.betnew.CloseBottomEvent;
import com.hgapp.a6668.login.fastregister.RegisterFragment;
import com.hgapp.a6668.login.forgetpwd.ForgetPwdFragment;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;
import java.util.Random;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class LoginFragment extends HGBaseFragment implements LoginContract.View {

    LoginContract.Presenter presenter;
    @BindView(R.id.tvLoginBack)
    NTitleBar tvLoginBack;
    @BindView(R.id.inputCodeLayout)
    InputCodeLayout inputCodeLayout;

    @BindView(R.id.verificationCodeView)
    VerificationCodeView verificationCodeView;
    Unbinder unbinder;
    @BindView(R.id.tvLoginUserName)
    TextView tvLoginUserName;
    @BindView(R.id.tvLoginUserPhone)
    TextView tvLoginUserPhone;
    @BindView(R.id.ivLoginType)
    ImageView ivLoginType;
    @BindView(R.id.etLoginType)
    EditText etLoginType;
    @BindView(R.id.tvLoginPwd)
    ImageView tvLoginPwd;
    @BindView(R.id.etLoginPwd)
    EditText etLoginPwd;
    @BindView(R.id.etLoginEyes)
    ImageView etLoginEyes;
    @BindView(R.id.cbLoginRemeber)
    CheckBox cbLoginRemeber;
    @BindView(R.id.loginRemeberPwd)
    CheckBox loginRemeberPwd;
    @BindView(R.id.btnLoginSubmit)
    Button btnLoginSubmit;
    @BindView(R.id.tvLoginForgetPwd)
    TextView tvLoginForgetPwd;
    @BindView(R.id.btnLoginRegister)
    Button btnLoginRegister;
    @BindView(R.id.btnLoginDemo)
    Button btnLoginDemo;
    Unbinder unbinder1;
    private Random mRandom = new Random();



    public static LoginFragment newInstance() {
        LoginFragment loginFragment = new LoginFragment();
        Bundle args = new Bundle();
        loginFragment.setArguments(args);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_login;
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        BottombarViewManager.getSingleton().onCloseView();
        String userName = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_PWD);
        if(!Check.isEmpty(userName)){
            etLoginType.setText(userName);
        }
        if(!Check.isEmpty(pwd)){
            loginRemeberPwd.setChecked(true);
            etLoginPwd.setText(pwd);
        }else{
            loginRemeberPwd.setChecked(false);
        }
        if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
            etLoginType.setText("");
            etLoginPwd.setText("");
        }
        //etLoginPwd.setText("123qwe");
        tvLoginBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
                EventBus.getDefault().post(new CloseBottomEvent());
            }
        });

        inputCodeLayout.setOnInputCompleteListener(new InputCodeLayout.OnInputCompleteCallback() {
            @Override
            public void onInputCompleteListener(String code) {
                GameLog.log("输入的验证码为：" + code);
                showMessage("输入的验证码为：" + code);
            }
        });

        verificationCodeView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                String s = String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10));

                verificationCodeView.setVerificationText(s);
            }
        });
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postLoginResult(LoginResult loginResult) {

        showMessage("登录成功");
        //String userName, String agents, String loginTime, String birthday, String money, String phone, String test_flag, String oid, String alias) {
        EventBus.getDefault().post(new LoginResult(loginResult.getUserName(),loginResult.getAgents(),loginResult.getLoginTime(),loginResult.getBirthday(),loginResult.getMoney(),loginResult.getPhone(),loginResult.getTest_flag(),loginResult.getOid(),loginResult.getAlias(),loginResult.getUserid(),loginResult.getMembermessage().getMem_message()));
        if(loginRemeberPwd.isChecked()){
            ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_PWD, etLoginPwd.getText().toString());
        }else{
            ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_PWD, "");
        }
        pop();
        GameLog.log("用户登录成功：别名是 "+loginResult.getAlias());
        //正对每一个用户做数据缓存
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_STATUS+loginResult.getUserName(), "1");
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT, loginResult.getUserName());
        ACache.get(getContext()).put(HGConstant.USERNAME_ALIAS, loginResult.getAlias());
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT+loginResult.getUserName()+HGConstant.USERNAME_BIND_CARD, loginResult.getBindCard_Flag());
        ACache.get(getContext()).put(HGConstant.USERNAME_BUY_MIN, loginResult.getBetMinMoney());
        ACache.get(getContext()).put(HGConstant.USERNAME_BUY_MAX, loginResult.getBetMaxMoney());
        ACache.get(getContext()).put(HGConstant.DOWNLOAD_APP_GIFT_GOLD, loginResult.getDOWNLOAD_APP_GIFT_GOLD());
        ACache.get(getContext()).put(HGConstant.DOWNLOAD_APP_GIFT_DEPOSIT, loginResult.getDOWNLOAD_APP_GIFT_DEPOSIT());
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_INFO, JSON.toJSONString(loginResult));
        //试玩的时候有返回
        ACache.get(getContext()).put(HGConstant.KY_DEMO_URL, loginResult.getChess_demo_url().getKy_demo_url());
        ACache.get(getContext()).put(HGConstant.LY_DEMO_URL, loginResult.getChess_demo_url().getLy_demo_url());
        ACache.get(getContext()).put(HGConstant.HG_DEMO_URL, loginResult.getChess_demo_url().getHg_demo_url());
        ACache.get(getContext()).put(HGConstant.VG_DEMO_URL, loginResult.getChess_demo_url().getVg_demo_url());
    }

    @Override
    public void success(SportsPlayMethodRBResult fullPayGameResult) {
        showMessage("获取玩法成化工");
    }

    @Override
    public void successGet(LoginResult loginResult) {


        ACache.get(getContext()).put(HGConstant.USERNAME_ALIAS, loginResult.getAlias());
    }


    @Override
    public void setPresenter(LoginContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


    private void btnLoginSubmit(){
        String loginType = etLoginType.getText().toString().trim();
        String loginPwd= etLoginPwd.getText().toString().trim();
        if(Check.isEmpty(loginType)){
            showMessage("账号格式错误！");
            return;
        }

        if(Check.isEmpty(loginPwd)||loginPwd.length()<6){
            showMessage("请输入有效密码！");
            return;
        }
        presenter.postLogin(HGConstant.PRODUCT_PLATFORM, loginType, loginPwd);
    }



    @OnClick({R.id.etLoginEyes,R.id.tvLoginForgetPwd,R.id.tvLoginUserName, R.id.tvLoginUserPhone, R.id.cbLoginRemeber, R.id.btnLoginSubmit, R.id.btnLoginRegister,R.id.btnLoginDemo})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.etLoginEyes:
                if (etLoginPwd.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
                    etLoginEyes.setBackgroundResource(R.mipmap.icon_eye);
                    etLoginPwd.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_NORMAL);
                } else {
                    etLoginEyes.setBackgroundResource(R.mipmap.icon_eye_close);
                    etLoginPwd.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                }
                etLoginPwd.setSelection(etLoginPwd.getText().toString().length());
                break;
            case R.id.tvLoginForgetPwd:
                EventBus.getDefault().post(new StartBrotherEvent(ForgetPwdFragment.newInstance(),SupportFragment.SINGLETASK));
                break;
            case R.id.tvLoginUserName:
                ivLoginType.setBackground(getActivity().getResources().getDrawable(R.mipmap.login_hy));
                tvLoginUserName.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_hight));
                tvLoginUserPhone.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_normal));
                cbLoginRemeber.setVisibility(View.GONE);
                etLoginType.setHint("您的会员账号");
                //presenter.loginGet();

                break;
            case R.id.tvLoginUserPhone:
                ivLoginType.setBackground(getActivity().getResources().getDrawable(R.mipmap.login_sj));
                tvLoginUserName.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_normal));
                tvLoginUserPhone.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_hight));
                cbLoginRemeber.setVisibility(View.VISIBLE);
                etLoginType.setHint("输入手机号");
                break;
            case R.id.cbLoginRemeber:
                break;
            case R.id.btnLoginSubmit:
                btnLoginSubmit();

                break;
            case R.id.btnLoginRegister:
                EventBus.getDefault().post(new StartBrotherEvent(RegisterFragment.newInstance(), SupportFragment.SINGLETASK));
                //start(RegisterFragment.newInstance());
                break;
            case R.id.btnLoginDemo:
                presenter.postLoginDemo(HGConstant.PRODUCT_PLATFORM,"demoguest","nicainicainicaicaicaicai");
                break;
        }
    }
}

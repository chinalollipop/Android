package com.vene.tian.login.fastlogin;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.InputType;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.vene.tian.HGApplication;
import com.vene.tian.Injections;
import com.vene.tian.R;
import com.vene.tian.base.HGBaseFragment;
import com.vene.tian.base.IPresenter;
import com.vene.tian.common.util.ACache;
import com.vene.tian.common.util.HGConstant;
import com.vene.tian.data.LoginResult;
import com.vene.tian.data.SportsPlayMethodRBResult;
import com.vene.tian.homepage.handicap.BottombarViewManager;
import com.vene.tian.login.fastregister.RegisterFragment;
import com.vene.tian.login.forgetpwd.ForgetPwdFragment;
import com.vene.tian.login.resetpwd.ResetPwdDialog;
import com.vene.tian.login.resetpwd.ResetPwdEvent;
import com.vene.common.util.Check;
import com.vene.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

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
    /*@BindView(R.id.inputCodeLayout)
    InputCodeLayout inputCodeLayout;

    @BindView(R.id.verificationCodeView)
    VerificationCodeView verificationCodeView;*/
    @BindView(R.id.sScrollView)
    LinearLayout sScrollView;
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
        EventBus.getDefault().register(this);
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
        /*tvLoginBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
                EventBus.getDefault().post(new CloseBottomEvent());
            }
        });*/

        /*inputCodeLayout.setOnInputCompleteListener(new InputCodeLayout.OnInputCompleteCallback() {
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
        });*/
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
        popTo(LoginFragment.class,true);
        /*String resetPwf = ACache.get(getContext()).getAsString("ResetPwdEvent");
        GameLog.log("ResetPwdEvent before "+ACache.get(getContext()).getAsString("ResetPwdEvent"));
        if(resetPwf.equals("1")){
            ACache.get(getContext()).put("ResetPwdEvent","0");
            popTo(LoginFragment.class,true);
        }else{
            pop();
        }*/
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
        if(!Check.isNull(loginResult.getChess_demo_url())){
            ACache.get(getContext()).put(HGConstant.KY_DEMO_URL, loginResult.getChess_demo_url().getKy_demo_url());
            ACache.get(getContext()).put(HGConstant.LY_DEMO_URL, loginResult.getChess_demo_url().getLy_demo_url());
            ACache.get(getContext()).put(HGConstant.VG_DEMO_URL, loginResult.getChess_demo_url().getVg_demo_url());
        }
        //ACache.get(getContext()).put(HGConstant.HG_DEMO_URL, loginResult.getChess_demo_url().getHg_demo_url());
        //ACache.get(getContext()).put("HGConstant.uid", loginResult.getOid());
        GameLog.log("登录执行完成 "+loginResult.getAlias());
    }

    @Override
    public void postLoginResultError(String message) {
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT,etLoginType.getText().toString().trim());
        ResetPwdDialog.newInstance(message).show(getFragmentManager());
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

    @Subscribe
    public void onEventMain(ResetPwdEvent resetPwdEvent){
        GameLog.log("重置密码成功 需要重新登录");
        etLoginPwd.setText(resetPwdEvent.getPwd());
        if(!Check.isNull(presenter)) {
            presenter.postLogin(HGConstant.PRODUCT_PLATFORM, resetPwdEvent.getName(), resetPwdEvent.getPwd());
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    private void btnLoginSubmit(){
        sScrollView.scrollTo(0,0);
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



    @OnClick({R.id.etLoginEyes,R.id.tvLoginForgetPwd,  R.id.btnLoginSubmit, R.id.btnLoginRegister,R.id.btnLoginDemo})
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
            case R.id.etLoginType:
                sScrollView.scrollTo(0,400);
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

package com.hg3366.a3366.login.fastlogin;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.FragmentTransaction;
import android.text.InputType;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.hg3366.a3366.HGApplication;
import com.hg3366.a3366.Injections;
import com.hg3366.a3366.R;
import com.hg3366.a3366.base.HGBaseFragment;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.common.util.ACache;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.data.LoginResult;
import com.hg3366.a3366.data.SportsPlayMethodRBResult;
import com.hg3366.a3366.homepage.handicap.BottombarViewManager;
import com.hg3366.a3366.login.fastregister.RegisterFragment;
import com.hg3366.a3366.login.forgetpwd.ForgetPwdFragment;
import com.hg3366.a3366.login.resetpwd.ResetPwdDialog;
import com.hg3366.a3366.login.resetpwd.ResetPwdEvent;
import com.hg3366.common.util.Check;
import com.hg3366.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
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
    @BindView(R.id.fgtLogin)
    LinearLayout fgtLogin;
    @BindView(R.id.fgtResgiter)
    FrameLayout fgtResgiter;
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
    @BindView(R.id.btnLoginUser)
    TextView btnLoginUser;
    @BindView(R.id.btnLoginRegister)
    TextView btnLoginRegister;
    private Random mRandom = new Random();

    @BindView(R.id.etRegisterIntro)
    EditText etRegisterIntro;
    @BindView(R.id.etRegisterUserName)
    EditText etRegisterUserName;
    @BindView(R.id.etRegisterPwd)
    EditText etRegisterPwd;
    @BindView(R.id.etRegisterPwdVerify)
    EditText etRegisterPwdVerify;
    @BindView(R.id.etRegisterWithDrawName)
    EditText etRegisterWithDrawName;
    @BindView(R.id.etRegisterWithDrawPwd)
    EditText etRegisterWithDrawPwd;
    @BindView(R.id.etRegisterBrithday)
    EditText etRegisterBrithday;
    @BindView(R.id.etRegisterAccountPhone)
    EditText etRegisterAccountPhone;
    @BindView(R.id.etRegisterResource)
    EditText etRegisterResource;
    @BindView(R.id.btnLoginDemo)
    Button btnLoginDemo;
    @BindView(R.id.btnLoginLayDemo)
    LinearLayout btnLoginLayDemo;
    @BindView(R.id.etRegisterAccountPhoneDemo)
    EditText etRegisterAccountPhoneDemo;
    @BindView(R.id.btnRegisterSubmit)
    Button btnRegisterSubmit;
    @BindView(R.id.btnRegisterSubmitDemo)
    Button btnRegisterSubmitDemo;
    OptionsPickerView optionsPickerViewState;
    private int resource = 1;
    static  List<String> stateList  = new ArrayList<String>();
    static {
        stateList.add("网络广告");
        stateList.add("比分网");
        stateList.add("朋友推荐");
        stateList.add("论坛");
    }

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

        optionsPickerViewState = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                resource = options1;
                etRegisterResource.setText(stateList.get(options1));
            }
        }).build();
        optionsPickerViewState.setPicker(stateList);
        etRegisterResource.setText("网络广告");
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



    @OnClick({R.id.etLoginEyes,R.id.tvLoginForgetPwd,  R.id.btnLoginSubmit, R.id.btnLoginUser,R.id.btnLoginRegister,R.id.btnLoginDemo,R.id.btnRegisterSubmitDemo,R.id.etRegisterResource,R.id.btnRegisterSubmit})
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
            case R.id.btnLoginUser:
                btnLoginLayDemo.setVisibility(View.GONE);
                fgtLogin.setVisibility(View.VISIBLE);
                fgtResgiter.setVisibility(View.GONE);
                RegisterFragment registerFragment =  RegisterFragment.newInstance();
                FragmentTransaction ft =getFragmentManager().beginTransaction().hide(registerFragment);
                ft.show(registerFragment);
                ft.commit();
                btnLoginUser.setBackground(getResources().getDrawable(R.drawable.btn_normal_top_click));
                btnLoginRegister.setBackground(null);
                break;
            case R.id.btnLoginRegister:
                btnLoginLayDemo.setVisibility(View.GONE);
                fgtLogin.setVisibility(View.GONE);
                fgtResgiter.setVisibility(View.VISIBLE);
                btnLoginUser.setBackground(null);
                btnLoginRegister.setBackground(getResources().getDrawable(R.drawable.btn_normal_top_click));
                //EventBus.getDefault().post(new StartBrotherEvent(RegisterFragment.newInstance(), SupportFragment.SINGLETASK));
                //start(RegisterFragment.newInstance());
                break;
            case R.id.btnLoginDemo:
                btnLoginLayDemo.setVisibility(View.VISIBLE);
                fgtLogin.setVisibility(View.GONE);
                fgtResgiter.setVisibility(View.GONE);
                //presenter.postLoginDemo(HGConstant.PRODUCT_PLATFORM,"demoguest","nicainicainicaicaicaicai");
                break;
            case R.id.btnRegisterSubmitDemo:
                presenter.postLoginDemo(HGConstant.PRODUCT_PLATFORM,"demoguest","nicainicainicaicaicaicai");
                break;
            case R.id.etRegisterResource:
                optionsPickerViewState.show();
                break;
            case R.id.btnRegisterSubmit:
                onCheckRegisterMember();
                break;
        }

    }

    private void onCheckRegisterMember(){
        String introducer = etRegisterIntro.getText().toString().trim();
        String userName = etRegisterUserName.getText().toString().trim();
        String userPwd = etRegisterPwd.getText().toString().trim();
        String userBrithday = etRegisterBrithday.getText().toString().trim();
        String userPwdVerify = etRegisterPwdVerify.getText().toString().trim();
        String userDrawName = etRegisterWithDrawName.getText().toString().trim();
        String userDrawPwd = etRegisterWithDrawPwd.getText().toString().trim();
        String userPhone = etRegisterAccountPhone.getText().toString().trim();
        if(Check.isEmpty(userName)){
            showMessage("请输入账号！");
            return;
        }

        if(Check.isEmpty(userPwd)||userPwd.length()<6){
            showMessage("请输入有效密码！");
            return;
        }

        if(Check.isEmpty(userPwdVerify)||userPwdVerify.length()<6){
            showMessage("请输入有效确认密码！");
            return;
        }

        if(!userPwdVerify.equals(userPwd)){
            showMessage("2次输入密码不一致，请重新输入！");
            return;
        }

        if(Check.isEmpty(userPhone)){
            showMessage("请输入手机号！");
            return;
        }

        /*if(Check.isEmpty(userDrawName)){
            showMessage("请输入真实姓名！");
            return;
        }
        if(Check.isEmpty(userDrawPwd)){
            showMessage("请输入提款密码！");
            return;
        }


        if(Check.isEmpty(userWechat)){
            showMessage("请输入微信号码！");
            return;
        }

        if(Check.isEmpty(userBrithday)){
            showMessage("请输入出生日期！");
            return;
        }*/

        /*if(Check.isEmpty(userVerificationCode)){
            showMessage("请输入正确的验证码");
            return;
        }*/
        //String appRefer,String introducer,String keys,String username,String password, String password2,String alias,
        //                                   String paypassword,String phone,String wechat,String birthday,String know_site

        presenter.postRegisterMember("",introducer,"add",userName,userPwd,userPwdVerify,userDrawName,userDrawPwd,userPhone,"",userBrithday,resource+"");

    }

    @Override
    public void postRegisterMemberResult(LoginResult loginResult) {
        showMessage("恭喜您，账号注册成功！");
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
        popTo(LoginFragment.class,true);
        EventBus.getDefault().post(loginResult);
    }




}

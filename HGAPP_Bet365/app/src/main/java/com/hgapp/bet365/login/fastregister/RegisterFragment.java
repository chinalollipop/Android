package com.hgapp.bet365.login.fastregister;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.hgapp.bet365.Injections;
import com.hgapp.bet365.R;
import com.hgapp.bet365.base.HGBaseFragment;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.common.util.ACache;
import com.hgapp.bet365.common.util.DoubleClickHelper;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.widgets.NTitleBar;
import com.hgapp.bet365.common.widgets.verifycodeview.VerificationCodeView;
import com.hgapp.bet365.data.LoginResult;
import com.hgapp.bet365.login.fastlogin.LoginFragment;
import com.hgapp.common.util.Check;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;
import java.util.Random;

import butterknife.BindView;
import butterknife.OnClick;

public class RegisterFragment extends HGBaseFragment implements RegisterContract.View {

    RegisterContract.Presenter presenter;
    @BindView(R.id.tvRegisterBack)
    NTitleBar tvRegisterBack;
    @BindView(R.id.tvRegisterUserName)
    TextView tvRegisterUserName;
    @BindView(R.id.tvRegisterUserPhone)
    TextView tvRegisterUserPhone;
    @BindView(R.id.etRegisterIntro)
    EditText etRegisterIntro;
    @BindView(R.id.tvRegisterType)
    TextView tvRegisterType;
    @BindView(R.id.etRegisterUserName)
    EditText etRegisterUserName;
    @BindView(R.id.tvRegisterPwd)
    TextView tvRegisterPwd;
    @BindView(R.id.etRegisterPwd)
    EditText etRegisterPwd;
    @BindView(R.id.tvRegisterPwdVerify)
    TextView tvRegisterPwdVerify;
    @BindView(R.id.etRegisterPwdVerify)
    EditText etRegisterPwdVerify;
    @BindView(R.id.etRegisterWithDrawName)
    EditText etRegisterWithDrawName;
    @BindView(R.id.etRegisterWithDrawPwd)
    EditText etRegisterWithDrawPwd;
    @BindView(R.id.etRegisterBrithday)
    EditText etRegisterBrithday;
    @BindView(R.id.tvRegisterAccountPhone)
    TextView tvRegisterAccountPhone;
    @BindView(R.id.etRegisterAccountPhone)
    EditText etRegisterAccountPhone;
    @BindView(R.id.tvRegisterEmail)
    TextView tvRegisterEmail;
    @BindView(R.id.etRegisterWechat)
    EditText etRegisterWechat;
    @BindView(R.id.tvRegisterVerificationCode)
    TextView tvRegisterVerificationCode;
    @BindView(R.id.etRegisterVerificationCode)
    EditText etRegisterVerificationCode;
    @BindView(R.id.registerVerificationCodeView)
    VerificationCodeView registerVerificationCodeView;
    @BindView(R.id.llRegisterAccount)
    LinearLayout llRegisterAccount;
    @BindView(R.id.tvRegisterPhone)
    TextView tvRegisterPhone;
    @BindView(R.id.etRegisterPhone)
    EditText etRegisterPhone;
    @BindView(R.id.tvRegisterPhonePwd)
    TextView tvRegisterPhonePwd;
    @BindView(R.id.etRegisterPhonePwd)
    EditText etRegisterPhonePwd;
    @BindView(R.id.tvRegisterPhoneVerificationCode)
    TextView tvRegisterPhoneVerificationCode;
    @BindView(R.id.etRegisterPhoneVerificationCode)
    EditText etRegisterPhoneVerificationCode;

    @BindView(R.id.etRegisterResource)
    EditText etRegisterResource;
    @BindView(R.id.btnRegisterGetVerificationCode)
    Button btnRegisterGetVerificationCode;
    @BindView(R.id.llRegisterPhone)
    LinearLayout llRegisterPhone;
    @BindView(R.id.cbRegisterProtocol)
    CheckBox cbRegisterProtocol;
    @BindView(R.id.btnRegisterSubmit)
    Button btnRegisterSubmit;


    TimePickerView tpRegisterBrithday;
    OptionsPickerView optionsPickerViewState;

    private Random mRandom = new Random();
    private int resource = 1;
    static  List<String> stateList  = new ArrayList<String>();
    static {
        stateList.add("网络广告");
        stateList.add("比分网");
        stateList.add("朋友推荐");
        stateList.add("论坛");
    }
    public static RegisterFragment newInstance() {
        RegisterFragment loginFragment = new RegisterFragment();
        Bundle args = new Bundle();
        loginFragment.setArguments(args);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_register;
    }

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        //时间选择器
        tpRegisterBrithday = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                etRegisterBrithday.setText(getTime(date));
            }
        })
                .build();

        optionsPickerViewState = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                resource = options1;
                etRegisterResource.setText(stateList.get(options1));
            }
        }).build();
        optionsPickerViewState.setPicker(stateList);
        etRegisterResource.setText("网络广告");
        registerVerificationCodeView.refreshCode();
        tvRegisterBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                finish();
            }
        });
        /*String s = String.valueOf(mRandom.nextInt(10)) +
                String.valueOf(mRandom.nextInt(10)) +
                String.valueOf(mRandom.nextInt(10)) +
                String.valueOf(mRandom.nextInt(10));
        registerVerificationCodeView.s(s);
        registerVerificationCodeView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                String s = String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10));

                registerVerificationCodeView.setVerificationText(s);
            }
        });*/
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void setPresenter(RegisterContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


    private void btnLoginSubmit() {
        /*String loginType = etLoginType.getText().toString().trim();
        String loginPwd= etLoginPwd.getText().toString().trim();
        if(Check.isEmpty(loginType)){
            showMessage("账号格式错误！");
            return;
        }

        if(Check.isEmpty(loginPwd)){
            showMessage("请输入有效密码！");
            return;
        }
        presenter.login("13", loginType, loginPwd);*/
    }


    @OnClick({R.id.etRegisterResource,R.id.etRegisterBrithday,R.id.registerVerificationCodeView, R.id.tvRegisterUserName, R.id.tvRegisterUserPhone, R.id.btnRegisterGetVerificationCode, R.id.cbRegisterProtocol, R.id.btnRegisterSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.etRegisterResource:
                optionsPickerViewState.show();
                break;
            case R.id.etRegisterBrithday:
                tpRegisterBrithday.show();
                break;

            case R.id.registerVerificationCodeView:
                registerVerificationCodeView.refreshCode();
                break;
            case R.id.tvRegisterUserName:
                tvRegisterUserName.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_hight));
                tvRegisterUserPhone.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_normal));
                llRegisterAccount.setVisibility(View.VISIBLE);
                llRegisterPhone.setVisibility(View.GONE);

                break;
            case R.id.tvRegisterUserPhone:
                tvRegisterUserPhone.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_hight));
                tvRegisterUserName.setBackgroundColor(getActivity().getResources().getColor(R.color.login_title_normal));
                llRegisterAccount.setVisibility(View.GONE);
                llRegisterPhone.setVisibility(View.VISIBLE);
                break;
            case R.id.btnRegisterGetVerificationCode:
                break;
            case R.id.cbRegisterProtocol:
                break;
            case R.id.btnRegisterSubmit:
                DoubleClickHelper.getNewInstance().disabledView(btnRegisterSubmit);
                onCheckRegisterMember();
                break;
        }
    }

    private void onCheckRegisterMember(){
        String userIntor = etRegisterIntro.getText().toString().trim();
        String userName = etRegisterUserName.getText().toString().trim();
        String userPwd = etRegisterPwd.getText().toString().trim();
        String userBrithday = etRegisterBrithday.getText().toString().trim();
        String userPwdVerify = etRegisterPwdVerify.getText().toString().trim();
        String userDrawName = etRegisterWithDrawName.getText().toString().trim();
        String userDrawPwd = etRegisterWithDrawPwd.getText().toString().trim();
        String userPhone = etRegisterAccountPhone.getText().toString().trim();
        String userWechat = etRegisterWechat.getText().toString().trim();
        String userVerificationCode = etRegisterVerificationCode.getText().toString().trim();
        String userResource = etRegisterResource.getText().toString().trim();
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

        presenter.postRegisterMember("",userIntor,"add",userName,userPwd,userPwdVerify,userDrawName,userDrawPwd,userPhone,userWechat,userBrithday,resource+"");

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




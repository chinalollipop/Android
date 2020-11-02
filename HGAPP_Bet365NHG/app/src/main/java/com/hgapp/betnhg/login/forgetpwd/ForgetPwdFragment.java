package com.hgapp.betnhg.login.forgetpwd;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.TimePickerView;
import com.hgapp.betnhg.Injections;
import com.hgapp.betnhg.R;
import com.hgapp.betnhg.base.HGBaseFragment;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.common.widgets.NTitleBar;
import com.hgapp.betnhg.data.LoginResult;
import com.hgapp.betnhg.login.fastlogin.LoginFragment;
import com.hgapp.common.util.Check;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.List;
import java.util.Random;

import butterknife.BindView;
import butterknife.OnClick;

public class ForgetPwdFragment extends HGBaseFragment implements ForgetPwdContract.View {

    ForgetPwdContract.Presenter presenter;
    @BindView(R.id.tvRegisterBack)
    NTitleBar tvRegisterBack;
    @BindView(R.id.tvRegisterUserName)
    TextView tvRegisterUserName;
    @BindView(R.id.tvRegisterUserPhone)
    TextView tvRegisterUserPhone;
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
    @BindView(R.id.tvRegisterAccountPhone)
    TextView tvRegisterAccountPhone;
    @BindView(R.id.etRegisterAccountPhone)
    EditText etRegisterAccountPhone;
    @BindView(R.id.tvRegisterEmail)
    TextView tvRegisterEmail;
    @BindView(R.id.etRegisterWechat)
    EditText etRegisterWechat;
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

    @BindView(R.id.btnRegisterGetVerificationCode)
    Button btnRegisterGetVerificationCode;
    @BindView(R.id.llRegisterPhone)
    LinearLayout llRegisterPhone;
    @BindView(R.id.btnRegisterSubmit)
    Button btnRegisterSubmit;
    TimePickerView tpRegisterBrithday;

    private Random mRandom = new Random();
    public static ForgetPwdFragment newInstance() {
        ForgetPwdFragment loginFragment = new ForgetPwdFragment();
        Bundle args = new Bundle();
        loginFragment.setArguments(args);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_forgetpwd;
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

        tvRegisterBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
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
    public void setPresenter(ForgetPwdContract.Presenter presenter) {
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


    @OnClick({R.id.etRegisterBrithday,R.id.tvRegisterUserName, R.id.tvRegisterUserPhone, R.id.btnRegisterGetVerificationCode, R.id.btnRegisterSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.etRegisterBrithday:
                hideKeyboard();
                tpRegisterBrithday.show();;
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
            case R.id.btnRegisterSubmit:
                onCheckRegisterMember();
                break;
        }
    }

    private void onCheckRegisterMember(){
        String userName = etRegisterUserName.getText().toString().trim();
        String userPwd = etRegisterPwd.getText().toString().trim();
        //String userBrithday = etRegisterBrithday.getText().toString().trim();
        String userPwdVerify = etRegisterPwdVerify.getText().toString().trim();
        String userDrawName = etRegisterWithDrawName.getText().toString().trim();
        String userDrawPwd = etRegisterWithDrawPwd.getText().toString().trim();
        String userPhone = etRegisterAccountPhone.getText().toString().trim();
        String userWechat = etRegisterWechat.getText().toString().trim();
        if(Check.isEmpty(userName)){
            showMessage("请输入账号！");
            return;
        }

        if(Check.isEmpty(userDrawName)){
            showMessage("请输入真实姓名！");
            return;
        }
        if(Check.isEmpty(userDrawPwd)){
            showMessage("请输入提款密码！");
            return;
        }

        /*if(Check.isEmpty(userBrithday)){
            showMessage("请输入出生日期！");
            return;
        }*/

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

        presenter.postForgetPwd("","reset",userName,userDrawName,userDrawPwd,"",userPwd,userPwdVerify);

    }



    @Override
    public void postForgetPwdResult(LoginResult loginResult) {
        popTo(LoginFragment.class,false);
//        finish();
        //正对每一个用户做数据缓存
        /*ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_STATUS+loginResult.getUserName(), "1");
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT, loginResult.getUserName());
        ACache.get(getContext()).put(HGConstant.USERNAME_ALIAS, loginResult.getAlias());
        ACache.get(getContext()).put(HGConstant.USERNAME_ALIAS, loginResult.getAlias());*/
    }
}

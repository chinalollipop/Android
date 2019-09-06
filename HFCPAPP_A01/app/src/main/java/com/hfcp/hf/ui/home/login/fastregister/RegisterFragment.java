package com.hfcp.hf.ui.home.login.fastregister;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.hfcp.hf.CFApplication;
import com.hfcp.hf.CFConstant;
import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.common.utils.Check;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.common.widget.NTitleBar;
import com.hfcp.hf.common.widget.verifycodeview.VerificationCodeView;
import com.hfcp.hf.data.LoginResult;
import com.hfcp.hf.ui.home.login.fastlogin.LoginFragment;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class RegisterFragment extends BaseFragment implements RegisterContract.View {
    @BindView(R.id.registerBack)
    NTitleBar registerBack;
    RegisterContract.Presenter presenter;
    @BindView(R.id.registerUserName)
    EditText registerUserName;
    @BindView(R.id.registerPwd1)
    EditText registerPwd1;
    @BindView(R.id.registerPwd2)
    EditText registerPwd2;
    @BindView(R.id.registerQQ)
    EditText registerQQ;
    @BindView(R.id.registerCode)
    EditText registerCode;
    @BindView(R.id.registerVerificationCodeView)
    VerificationCodeView registerVerificationCodeView;
    @BindView(R.id.registerSubmit)
    TextView registerSubmit;
    String vCode="";
//    private Random mRandom = new Random();

    public static RegisterFragment newInstance() {
        RegisterFragment registerFragment = new RegisterFragment();
        Injections.inject(registerFragment, null);
        return registerFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_register;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        String qq = ACache.get(getContext()).getAsString("USERNAME_SERVICE_Is_reg_qq");
        if(!Check.isEmpty(qq)&&qq.equals("1")){
            registerQQ.setVisibility(View.VISIBLE);
        }
        registerBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        registerVerificationCodeView.refreshCode();
        /*vCode = String.valueOf(mRandom.nextInt(10)) +
                String.valueOf(mRandom.nextInt(10)) +
                String.valueOf(mRandom.nextInt(10)) +
                String.valueOf(mRandom.nextInt(10));
        registerVerificationCodeView.setvCode(vCode);*/
        vCode = registerVerificationCodeView.getvCode();
        GameLog.log("当前的值是 "+vCode);
        registerVerificationCodeView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                /*vCode = String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10)) +
                        String.valueOf(mRandom.nextInt(10));
                */
                if(!Check.isNull(registerVerificationCodeView)) {
                    registerVerificationCodeView.setvCode(vCode);
                    vCode = registerVerificationCodeView.getvCode();
                    GameLog.log("当前的值是 " + vCode);
                }
            }
        });
    }

    @Override
    public void postRegisterMemberResult(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
        showMessage("注册账号成功！");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PWD, "");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_ACCOUNT, loginResult.getUsername());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, loginResult.getToken());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_BALANCE, loginResult.getAbalance());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PARENT_ID, loginResult.getId() + "");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_USER_ID,loginResult.getId()+"");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PARENT,loginResult.getParent()+"");
        EventBus.getDefault().post(loginResult);
        popTo(LoginFragment.class, true);
    }

    @Override
    public void setPresenter(RegisterContract.Presenter presenter) {
        this.presenter = presenter;
    }


    @OnClick(R.id.registerSubmit)
    public void onViewClicked() {
        onRequestRegister();
    }

    private void onRequestRegister() {
        String userName = registerUserName.getText().toString().trim();
        String userPwd = registerPwd1.getText().toString().trim();
        String userPwd2 = registerPwd2.getText().toString().trim();
        String userCode = registerCode.getText().toString().trim();
        String qq = registerQQ.getText().toString().trim();
        if(Check.isEmpty(userName)){
            showMessage("请输入用户名");
            return;
        }
        if(Check.isEmpty(userPwd)){
            showMessage("请输入密码");
            return;
        }
        if(Check.isEmpty(userPwd2)){
            showMessage("请输入确认密码");
            return;
        }

        if(ACache.get(getContext()).getAsString("USERNAME_SERVICE_Is_reg_qq").equals("1")){
            if(Check.isEmpty(qq)){
                showMessage("请输入QQ号码");
                return;
            }
        }

        if(!userPwd.equals(userPwd2)){
            showMessage("两次输入的密码不一致");
            return;
        }
        if(!vCode.equals(userCode)){
            showMessage("验证码错误");
            return;
        }
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this,null);
        }
        presenter.postRegisterMember(CFApplication.instance().getCommentData(),userName,userPwd,userPwd2,qq);
    }
}

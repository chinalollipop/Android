package com.qpweb.a01.ui.home.set;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class SetPwdFragment extends BaseDialogFragment implements SetPwdContract.View{

    @BindView(R.id.setLoginPwd1)
    EditText setLoginPwd1;
    @BindView(R.id.setLoginPwd2)
    EditText setLoginPwd2;
    @BindView(R.id.setLoginPwd3)
    EditText setLoginPwd3;
    @BindView(R.id.setLoginPwdSubmit)
    TextView setLoginPwdSubmit;
    @BindView(R.id.setWithdrawName)
    EditText setWithdrawName;
    @BindView(R.id.setWithdrawPwd1)
    EditText setWithdrawPwd1;
    @BindView(R.id.setWithdrawPwd2)
    EditText setWithdrawPwd2;
    @BindView(R.id.setWithdrawPwdSubmit)
    TextView setWithdrawPwdSubmit;
    @BindView(R.id.setPwdClose)
    ImageView setPwdClose;

    SetPwdContract.Presenter presenter;

    public static SetPwdFragment newInstance() {
        Bundle bundle = new Bundle();
        SetPwdFragment loginFragment = new SetPwdFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment,null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.set_pwd_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT_ALIAS);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
        GameLog.log("用户的真实姓名 "+userName);
        if(!Check.isEmpty(userName)){
            setWithdrawName.setText(userName);
            setWithdrawName.clearFocus();
            setWithdrawName.setClickable(false);
            setWithdrawName.setFocusable(false);
        }
    }


    @OnClick({R.id.setLoginPwdSubmit, R.id.setWithdrawPwdSubmit, R.id.setPwdClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.setLoginPwdSubmit:
                onCheckAndLoginPwdSubmit();
                break;
            case R.id.setWithdrawPwdSubmit:
                onCheckAndWithdrawPwdSubmit();
                break;
            case R.id.setPwdClose:
                hide();
                break;
        }
    }

    private void onCheckAndLoginPwdSubmit() {
        String pwd1 = setLoginPwd1.getText().toString().trim();
        String pwd2 = setLoginPwd2.getText().toString().trim();
        String pwd3 = setLoginPwd3.getText().toString().trim();
        if(Check.isEmpty(pwd1)){
            showMessage("请输入原登录密码");
            return;
        }
        if(Check.isEmpty(pwd2)){
            showMessage("请输入新登录密码");
            return;
        }
        if(Check.isEmpty(pwd3)){
            showMessage("请输入确认密码");
            return;
        }
        if(!pwd3.equals(pwd2)){
            showMessage("新登录密码和确认密码不一致！");
            return;
        }
        presenter.postChangLoginPwd("","login",pwd1,pwd2,pwd3);
    }

    private void onCheckAndWithdrawPwdSubmit() {
        String realName = setWithdrawName.getText().toString().trim();
        String pwd1 = setWithdrawPwd1.getText().toString().trim();
        String pwd2 = setWithdrawPwd2.getText().toString().trim();
        if(Check.isEmpty(realName)){
            showMessage("请输入用户姓名");
            return;
        }
        if(Check.isEmpty(pwd1)){
            showMessage("请输入新取款密码");
            return;
        }
        if(Check.isEmpty(pwd2)){
            showMessage("请输入取款密码确认");
            return;
        }
        if(!pwd1.equals(pwd2)){
            showMessage("新取款密码和确认密码不一致！");
            return;
        }
        presenter.postChangeWithDrawPwd("","safe",realName,pwd1,pwd2);
    }



    @Override
    public void postChangLoginPwdResult(RedPacketResult redPacketResult) {
        ACache.get(getContext()).put("isChangeUser","YES");
        EventBus.getDefault().post(new ChangeAccountEvent());
    }

    @Override
    public void setPresenter(SetPwdContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}

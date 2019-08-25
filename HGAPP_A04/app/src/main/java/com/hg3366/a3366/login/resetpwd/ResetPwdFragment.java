package com.hg3366.a3366.login.resetpwd;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.InputType;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;

import com.hg3366.a3366.Injections;
import com.hg3366.a3366.R;
import com.hg3366.a3366.base.HGBaseFragment;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.common.util.ACache;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.widgets.NTitleBar;
import com.hg3366.a3366.homepage.handicap.BottombarViewManager;
import com.hg3366.a3366.homepage.handicap.betnew.CloseBottomEvent;
import com.hg3366.common.util.Check;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class ResetPwdFragment extends HGBaseFragment implements ResetPwdContract.View {

    ResetPwdContract.Presenter presenter;
    @BindView(R.id.tvLoginBack)
    NTitleBar tvLoginBack;
    @BindView(R.id.etLoginType)
    EditText etLoginType;
    @BindView(R.id.etLoginPwd)
    EditText etLoginPwd;
    @BindView(R.id.etLoginEyes)
    ImageView etLoginEyes;
    @BindView(R.id.etLoginPwd1)
    EditText etLoginPwd1;
    @BindView(R.id.etLoginEyes1)
    ImageView etLoginEyes1;
    @BindView(R.id.etLoginPwd2)
    EditText etLoginPwd2;
    @BindView(R.id.etLoginEyes2)
    ImageView etLoginEyes2;
    @BindView(R.id.btnLoginSubmit)
    Button btnLoginSubmit;

    public static ResetPwdFragment newInstance() {
        ResetPwdFragment loginFragment = new ResetPwdFragment();
        Bundle args = new Bundle();
        loginFragment.setArguments(args);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_reset_pwd;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        BottombarViewManager.getSingleton().onCloseView();
        String userName = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT);
        if(!Check.isEmpty(userName)){
            etLoginType.setText(userName);
        }else{
            etLoginType.setText("用户名可不输入");
        }
        etLoginType.setClickable(false);
        etLoginType.setFocusable(false);
        tvLoginBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
                EventBus.getDefault().post(new CloseBottomEvent());
            }
        });
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void setPresenter(ResetPwdContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


    private void btnLoginSubmit(){
        String loginType = etLoginType.getText().toString().trim();
        String loginPwd= etLoginPwd.getText().toString().trim();
        String loginPwd1= etLoginPwd1.getText().toString().trim();
        String loginPwd2= etLoginPwd2.getText().toString().trim();
        if (Check.isEmpty(loginPwd)) {
            showMessage("请输入原密码！");
            return;
        }

        if(Check.isEmpty(loginPwd)||loginPwd.length()<6){
            showMessage("请输入有效密码！");
            return;
        }

        if (Check.isEmpty(loginPwd1)) {
            showMessage("请输入新密码！");
            return;
        }
        if (Check.isEmpty(loginPwd2)) {
            showMessage("请确认新密码！");
            return;
        }

        if(!loginPwd1.equals(loginPwd2)){
            showMessage("两次输入的密码不一致！");
            return;
        }

        presenter.getChangeLoginPwd(HGConstant.PRODUCT_PLATFORM,"1","1",loginPwd, loginPwd1, loginPwd2);
    }



    @OnClick({R.id.etLoginEyes,R.id.etLoginEyes1,R.id.etLoginEyes2,R.id.btnLoginSubmit})
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
            case R.id.etLoginEyes1:
                if (etLoginPwd1.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
                    etLoginEyes1.setBackgroundResource(R.mipmap.icon_eye);
                    etLoginPwd1.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_NORMAL);
                } else {
                    etLoginEyes1.setBackgroundResource(R.mipmap.icon_eye_close);
                    etLoginPwd1.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                }
                etLoginPwd1.setSelection(etLoginPwd1.getText().toString().length());
                break;
            case R.id.etLoginEyes2:
                if (etLoginPwd2.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
                    etLoginEyes2.setBackgroundResource(R.mipmap.icon_eye);
                    etLoginPwd2.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_NORMAL);
                } else {
                    etLoginEyes2.setBackgroundResource(R.mipmap.icon_eye_close);
                    etLoginPwd2.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                }
                etLoginPwd2.setSelection(etLoginPwd2.getText().toString().length());
                break;
            case R.id.btnLoginSubmit:
                btnLoginSubmit();
                break;
        }
    }

    @Override
    public void onChangeLoginPwdResut(String successMessage) {
        showMessage(successMessage);
        EventBus.getDefault().post(new ResetPwdEvent(etLoginType.getText().toString().trim(),etLoginPwd2.getText().toString().trim()));
        finish();
    }
}

package com.hgapp.a6668.personpage.managepwd;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.InputType;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;

import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.event.LogoutEvent;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.login.fastlogin.LoginFragment;
import com.hgapp.a6668.personpage.accountcenter.AccountCenterFragment;
import com.hgapp.common.util.Check;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class ManagePwdFragment extends HGBaseFragment implements ManagePwdContract.View {


    @BindView(R.id.etOldPassword)
    EditText etOldPassword;
    @BindView(R.id.etPassword)
    EditText etPassword;
    @BindView(R.id.etREPassword)
    EditText etREPassword;
    @BindView(R.id.btnChangeLoginPwd)
    Button btnChangeLoginPwd;
    @BindView(R.id.etPayOldPassword)
    EditText etPayOldPassword;
    @BindView(R.id.etPayPassword)
    EditText etPayPassword;
    @BindView(R.id.etPayREpassword)
    EditText etPayREpassword;
    @BindView(R.id.btnChangeWithdrawPwd)
    Button btnChangeWithdrawPwd;
    Unbinder unbinder;
    @BindView(R.id.OldPasswordEyes)
    ImageView OldPasswordEyes;
    @BindView(R.id.PasswordEyes)
    ImageView PasswordEyes;
    @BindView(R.id.PayOldPasswordEyes)
    ImageView PayOldPasswordEyes;
    @BindView(R.id.PayPasswordEyes)
    ImageView PayPasswordEyes;
    Unbinder unbinder1;
    private ManagePwdContract.Presenter presenter;

    public static ManagePwdFragment newInstance() {
        ManagePwdFragment fragment = new ManagePwdFragment();
        Bundle args = new Bundle();
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_manage_pwd;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

    }

    @Override
    public void setPresenter(ManagePwdContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    private void onCheckLoginPwd() {

        String oldPwd = etOldPassword.getText().toString().trim();
        String newPwd = etPassword.getText().toString().trim();
        String newAgainPwd = etREPassword.getText().toString().trim();

        if (Check.isEmpty(oldPwd)) {
            showMessage("请输入原密码！");
            return;
        }
        if (Check.isEmpty(newPwd)) {
            showMessage("请输入新密码！");
            return;
        }
        if (Check.isEmpty(newAgainPwd)) {
            showMessage("请确认新密码！");
            return;
        }
        presenter.getChangeLoginPwd("", "", "", oldPwd, newPwd, newAgainPwd);
    }

    private void onCheckWithdrawPwd() {
        String oldPwd = etPayOldPassword.getText().toString().trim();
        String newPwd = etPayPassword.getText().toString().trim();
        String newAgainPwd = etPayREpassword.getText().toString().trim();

        if (Check.isEmpty(oldPwd)) {
            showMessage("请输入原密码！");
            return;
        }
        if (Check.isEmpty(newPwd)) {
            showMessage("请输入新密码！");
            return;
        }
        if (Check.isEmpty(newAgainPwd)) {
            showMessage("请确认新密码！");
            return;
        }
        presenter.getChangeWithdrawPwd("", "", "", oldPwd, newPwd, newAgainPwd);
    }

    @OnClick({R.id.btnChangeLoginPwd, R.id.btnChangeWithdrawPwd,R.id.OldPasswordEyes, R.id.PasswordEyes, R.id.PayOldPasswordEyes, R.id.PayPasswordEyes})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btnChangeLoginPwd:
                onCheckLoginPwd();
                break;
            case R.id.btnChangeWithdrawPwd:
                onCheckWithdrawPwd();
                break;
            case R.id.OldPasswordEyes:
                if (etOldPassword.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
                    OldPasswordEyes.setBackgroundResource(R.mipmap.icon_eye);
                    etOldPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_NORMAL);
                } else {
                    OldPasswordEyes.setBackgroundResource(R.mipmap.icon_eye_close);
                    etOldPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                }
                etOldPassword.setSelection(etOldPassword.getText().toString().length());
                break;
            case R.id.PasswordEyes:
                if (etPassword.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
                    PasswordEyes.setBackgroundResource(R.mipmap.icon_eye);
                    etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_NORMAL);
                } else {
                    PasswordEyes.setBackgroundResource(R.mipmap.icon_eye_close);
                    etPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                }
                etPassword.setSelection(etPassword.getText().toString().length());
                break;
            case R.id.PayOldPasswordEyes:
                if (etPayOldPassword.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
                    PayOldPasswordEyes.setBackgroundResource(R.mipmap.icon_eye);
                    etPayOldPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_NORMAL);
                } else {
                    PayOldPasswordEyes.setBackgroundResource(R.mipmap.icon_eye_close);
                    etPayOldPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                }
                etPayOldPassword.setSelection(etPayOldPassword.getText().toString().length());
                break;
            case R.id.PayPasswordEyes:
                if (etPayPassword.getInputType() == (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD)) {
                    PayPasswordEyes.setBackgroundResource(R.mipmap.icon_eye);
                    etPayPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_NORMAL);
                } else {
                    PayPasswordEyes.setBackgroundResource(R.mipmap.icon_eye_close);
                    etPayPassword.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
                }
                etPayPassword.setSelection(etPayPassword.getText().toString().length());
                break;
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // TODO: inflate a fragment view
        View rootView = super.onCreateView(inflater, container, savedInstanceState);
        unbinder1 = ButterKnife.bind(this, rootView);
        return rootView;
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        unbinder1.unbind();
    }

    @Override
    public void onChangeLoginPwdResut(String successMessage) {
        showMessage(successMessage);
        popTo(AccountCenterFragment.class,true);
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT), "0");
        //ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT, "");
        ACache.get(getContext()).put(HGConstant.APP_CP_COOKIE,"");
        ACache.get(getContext()).put(HGConstant.USERNAME_ALIAS, "");
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGOUT, "true");
        EventBus.getDefault().post(new LogoutEvent(successMessage));
        EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
    }
}

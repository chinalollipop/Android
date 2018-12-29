package com.qpweb.a01.ui.loginhome.fastregister;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.QPWebSetting;
import com.tencent.smtt.sdk.WebView;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class RegisterFragment extends BaseDialogFragment implements RegisterContract.View {
    @BindView(R.id.registerReferralCode)
    EditText registerReferralCode;
    @BindView(R.id.registerAccount)
    EditText registerAccount;
    @BindView(R.id.registerPwd)
    EditText registerPwd;
    @BindView(R.id.registerPwd2)
    EditText registerPwd2;
    @BindView(R.id.registerSecurityCode)
    EditText registerSecurityCode;
    @BindView(R.id.registerCodeRequest)
    TextView registerCodeRequest;
    @BindView(R.id.registerSecurityCodeRequest)
    WebView registerSecurityCodeRequest;
    @BindView(R.id.registerSubmit)
    ImageView registerSubmit;
    @BindView(R.id.registerClose)
    ImageView registerClose;

    RegisterContract.Presenter presenter;

    public static RegisterFragment newInstance() {
        Bundle bundle = new Bundle();
        RegisterFragment dialog = new RegisterFragment();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    public int setLayoutId() {
        return R.layout.register_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    @Override
    public void setEvents(View view,@Nullable Bundle savedInstanceState) {
        QPWebSetting.init(registerSecurityCodeRequest);
        //getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE | WindowManager.LayoutParams.SOFT_INPUT_STATE_HIDDEN);

        String url = "http://hg06606.com/include/validatecode/captcha.php?"+System.currentTimeMillis();
        GameLog.log("请求的url地址是 "+url);
        registerSecurityCodeRequest.loadUrl(url);
    }

    @OnClick({R.id.registerCodeRequest, R.id.registerSubmit, R.id.registerClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.registerCodeRequest:
                String url = "http://hg06606.com/include/validatecode/captcha.php?"+System.currentTimeMillis();
                GameLog.log("请求的url地址是 "+url);
                registerSecurityCodeRequest.loadUrl(url);
                break;
            case R.id.registerSubmit:
                onCheckDataAndSubmit();
                break;
            case R.id.registerClose:
                hide();
                break;
        }
    }

    private void onCheckDataAndSubmit() {
        String registerReferralCodes = registerReferralCode.getText().toString().trim();
        String registerAccounts = registerAccount.getText().toString().trim();
        String registerPwds = registerPwd.getText().toString().trim();
        String registerPwd2s = registerPwd2.getText().toString().trim();
        String registerSecurityCodes = registerSecurityCode.getText().toString().trim();
        if(Check.isEmpty(registerAccounts)){
            showMessage("请输入合法的用户账号");
        }
        if(Check.isEmpty(registerPwds)){
            showMessage("请输入密码");
        }
        if(Check.isEmpty(registerPwd2s)){
            showMessage("请输入密码");
        }
        if(!registerPwds.equals(registerPwd2s)){
            showMessage("两次输入的密码不一致");
        }
        if(Check.isEmpty(registerSecurityCodes)){
            showMessage("请输入验证码");
        }

        presenter.postRegisterMember(QPConstant.PRODUCT_PLATFORM,"register",registerReferralCodes,registerAccounts,registerPwds,registerPwd2s,registerSecurityCodes,"");

    }

    @Override
    public void postRegisterMemberResult(LoginResult loginResult) {

    }

    @Override
    public void setPresenter(RegisterContract.Presenter presenter) {
        this.presenter  = presenter;
    }


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}

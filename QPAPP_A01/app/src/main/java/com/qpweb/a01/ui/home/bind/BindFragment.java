package com.qpweb.a01.ui.home.bind;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.util.EventLog;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.ui.home.RefreshMoneyEvent;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.widget.FormatPhoneTextWatcher;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class BindFragment extends BaseDialogFragment implements BindContract.View {


    BindContract.Presenter presenter;
    @BindView(R.id.bindAccount)
    EditText bindAccount;
    @BindView(R.id.loginPhone)
    EditText loginPhone;
    @BindView(R.id.bindCode)
    EditText bindCode;
    @BindView(R.id.bindGetCode)
    TextView bindGetCode;
    @BindView(R.id.bindGetCodeTView)
    TextView bindGetCodeTView;
    @BindView(R.id.layoutCode)
    LinearLayout layoutCode;
    @BindView(R.id.bindSubmit)
    TextView bindSubmit;
    @BindView(R.id.bindClose)
    ImageView bindClose;
    private MyCountDownTimer mCountDownTimer;

    public static BindFragment newInstance() {
        Bundle bundle = new Bundle();
        BindFragment loginFragment = new BindFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.bind_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }

    }

    class MyCountDownTimer extends CountDownTimer {
        /**
         * @param millisInFuture
         *      表示以「 毫秒 」为单位倒计时的总数
         *      例如 millisInFuture = 1000 表示1秒
         *
         * @param countDownInterval
         *      表示 间隔 多少微秒 调用一次 onTick()
         *      例如: countDownInterval = 1000 ; 表示每 1000 毫秒调用一次 onTick()
         *
         */

        public MyCountDownTimer(long millisInFuture, long countDownInterval) {
            super(millisInFuture, countDownInterval);
        }


        public void onFinish() {
            bindGetCode.setVisibility(View.VISIBLE);
            bindGetCodeTView.setVisibility(View.GONE);
            bindGetCodeTView.setText("60秒重试");
        }

        public void onTick(long millisUntilFinished) {
            if(!Check.isNull(bindGetCodeTView)){
                bindGetCodeTView.setText( millisUntilFinished / 1000 + "秒重试");
            }
        }

    }

    private void onGetCode(){
        String loginAccounts = loginPhone.getText().toString().trim();
        if(Check.isEmpty(loginAccounts)){
            showMessage("请输入有效的用户手机账号");
            return;
        }
        presenter.postSendCode("",loginAccounts);
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
        loginPhone.addTextChangedListener(new FormatPhoneTextWatcher(loginPhone));
        if (!Check.isEmpty(userName)) {

        }
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);

    }

    private void onCheckAndSubmit() {
        String nickname = bindAccount.getText().toString().trim();
        String loginAccounts = loginPhone.getText().toString().trim().replace(" ","");
        String loginPwdPwds = bindCode.getText().toString().trim();
        if(Check.isEmpty(loginAccounts)){
            showMessage("请输入合法的用户账号");
            return;
        }
        if(Check.isEmpty(loginPwdPwds)){
            showMessage("请输入验证码");
            return;
        }
        presenter.postCodeSubmit("",nickname,loginAccounts,loginPwdPwds);
    }

    @Override
    public void postSendCodeResult() {
        bindGetCode.setVisibility(View.GONE);
        bindGetCodeTView.setVisibility(View.VISIBLE);
        mCountDownTimer = new MyCountDownTimer(60000, 1000);
        mCountDownTimer.start();
    }

    @Override
    public void postCodeSubmitResult(RedPacketResult redPacketResult) {
        hide();
        //ACache.get(getContext()).put("Bind_phone","1");
        EventBus.getDefault().post(new RefreshMoneyEvent(loginPhone.getText().toString().trim()));
        if(!Check.isNull(mCountDownTimer)){
            mCountDownTimer.onFinish();
            mCountDownTimer = null;
        }
    }

    @Override
    public void setPresenter(BindContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @OnClick({R.id.bindGetCode, R.id.bindSubmit, R.id.bindClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.bindGetCode:
                onGetCode();
                break;
            case R.id.bindSubmit:
                onCheckAndSubmit();
                break;
            case R.id.bindClose:
                hide();
                break;
        }
    }
}

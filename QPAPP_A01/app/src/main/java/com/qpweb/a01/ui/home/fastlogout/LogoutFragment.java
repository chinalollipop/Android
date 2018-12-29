package com.qpweb.a01.ui.home.fastlogout;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.LogoutResult;
import com.qpweb.a01.utils.Check;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class LogoutFragment extends BaseDialogFragment implements LogoutContract.View {


    public static final String PARAM0 = "param0";
    LogoutContract.Presenter presenter;
    @BindView(R.id.logoutAccount)
    TextView logoutAccount;
    @BindView(R.id.logoutLastTime)
    TextView logoutLastTime;
    @BindView(R.id.looutFl1)
    TextView looutFl1;
    @BindView(R.id.looutFl2)
    TextView looutFl2;
    @BindView(R.id.logoutSubmit)
    TextView logoutSubmit;
    @BindView(R.id.loginSet)
    TextView loginSet;
    @BindView(R.id.logoutClose)
    ImageView logoutClose;

    LoginResult loginResult;

    public static LogoutFragment newInstance(LoginResult loginResult) {
        Bundle bundle = new Bundle();
        bundle.putParcelable(PARAM0,loginResult);
        LogoutFragment loginFragment = new LogoutFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_logout;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            loginResult = getArguments().getParcelable(PARAM0);
        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        logoutAccount.setText(loginResult.getUserName());
        logoutLastTime.setText("上次登录时间："+loginResult.getLastLoginTime());
    }

    @Override
    public void setPresenter(LogoutContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postLogoutResult(String logoutResult) {
        showMessage(logoutResult);
        EventBus.getDefault().post(new LogoutResult());
        hide();
    }


    @OnClick({R.id.logoutSubmit, R.id.loginSet, R.id.logoutClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.logoutSubmit:
                presenter.postLogout("");
                break;
            case R.id.loginSet:
                hide();
                break;
            case R.id.logoutClose:
                hide();
                break;
        }
    }
}

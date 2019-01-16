package com.cfcp.a01.ui.login.fastlogin;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;
import com.cfcp.a01.base.event.StartBrotherEvent;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.login.fastregister.RegisterFragment;
import com.cfcp.a01.utils.GameLog;
import com.cfcp.a01.widget.NTitleBar;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

public class LoginFragment extends BaseFragment implements LoginContract.View{
    @BindView(R.id.loginBack)
    NTitleBar loginBack;
    LoginContract.Presenter presenter;

    public static LoginFragment newInstance(){
        LoginFragment homeFragment = new LoginFragment();
        return homeFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_login_home;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        loginBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
        finish();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }

    @OnClick({R.id.loginGoRegister,R.id.loginGoSubmit})
    public void onClickView(View view){
        switch (view.getId()){
            case R.id.loginGoRegister:
                //处理用户登录接口的数据请求
                //presenter.postLogin("","","");
                EventBus.getDefault().post(new StartBrotherEvent(RegisterFragment.newInstance(), SupportFragment.SINGLETASK));
                break;
            case R.id.loginGoSubmit:
                EventBus.getDefault().post(new LoginResult());
                finish();
                break;
        }


    }


    @Override
    public void postLoginResult(LoginResult loginResult) {

    }

    @Override
    public void setPresenter(LoginContract.Presenter presenter) {
        this.presenter = presenter;
    }
}

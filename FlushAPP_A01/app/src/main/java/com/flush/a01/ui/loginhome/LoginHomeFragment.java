package com.flush.a01.ui.loginhome;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;

import com.flush.a01.R;
import com.flush.a01.base.BaseFragment;
import com.flush.a01.data.LoginResult;
import com.flush.a01.ui.loginhome.fastlogin.LoginFragment;
import com.flush.a01.ui.loginhome.fastregister.RegisterFragment;
import com.flush.a01.utils.GameLog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;

public class LoginHomeFragment extends BaseFragment {
    @BindView(R.id.homeRegister)
    ImageView homeRegister;
    @BindView(R.id.homeLogin)
    ImageView homeLogin;
    @BindView(R.id.homeDemo)
    ImageView homeDemo;

    public static LoginHomeFragment newInstance(){
        LoginHomeFragment homeFragment = new LoginHomeFragment();

        return homeFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_login_home;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
        finish();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @OnClick({R.id.homeRegister, R.id.homeLogin, R.id.homeDemo})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.homeRegister:
                //EventBus.getDefault().post(new StartBrotherEvent( RegisterFragment.newInstance(),SupportFragment.SINGLETASK));
                RegisterFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeLogin:
                LoginFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeDemo:
                break;
        }
    }


}

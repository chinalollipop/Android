package com.cfcp.a01.ui.loginhome.fastlogin;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;
import com.cfcp.a01.base.event.StartBrotherEvent;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.loginhome.fastregister.RegisterFragment;
import com.cfcp.a01.utils.GameLog;
import com.cfcp.a01.widget.NTitleBar;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

public class LoginFragment extends BaseFragment {
    @BindView(R.id.loginBack)
    NTitleBar loginBack;

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
        EventBus.getDefault().register(this);
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
        EventBus.getDefault().unregister(this);
    }

    @OnClick(R.id.loginGoRegister)
    public void onClickView(View view){
        EventBus.getDefault().post(new StartBrotherEvent(RegisterFragment.newInstance(), SupportFragment.SINGLETASK));

    }


}

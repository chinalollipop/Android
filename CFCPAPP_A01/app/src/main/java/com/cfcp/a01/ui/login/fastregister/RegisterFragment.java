package com.cfcp.a01.ui.login.fastregister;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.utils.GameLog;
import com.cfcp.a01.widget.NTitleBar;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;

public class RegisterFragment extends BaseFragment {
    @BindView(R.id.registerBack)
    NTitleBar registerBack;

    public static RegisterFragment newInstance(){
        RegisterFragment homeFragment = new RegisterFragment();
        return homeFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_register;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        registerBack.setBackListener(new View.OnClickListener() {
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




}

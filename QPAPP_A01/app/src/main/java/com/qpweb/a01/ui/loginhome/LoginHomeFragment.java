package com.qpweb.a01.ui.loginhome;

import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;

import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseFragment;
import com.qpweb.a01.base.event.StartBrotherEvent;
import com.qpweb.a01.ui.loginhome.fastlogin.LoginFragment;
import com.qpweb.a01.ui.loginhome.fastregister.RegisterFragment;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

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

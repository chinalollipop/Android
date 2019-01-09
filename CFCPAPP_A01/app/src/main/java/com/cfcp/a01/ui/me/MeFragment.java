package com.cfcp.a01.ui.me;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.loginhome.fastlogin.LoginFragment;
import com.cfcp.a01.ui.loginhome.fastregister.RegisterFragment;
import com.cfcp.a01.ui.main.MainEvent;
import com.cfcp.a01.utils.GameLog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;

public class MeFragment extends BaseFragment {

    public static MeFragment newInstance(){
        MeFragment MeFragment = new MeFragment();

        return MeFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_me;
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

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        showMessage("个人信息界面");
        EventBus.getDefault().post(new MainEvent(0));
    }
}

package com.cfcp.a01.ui.lottery;

import android.os.Bundle;
import android.support.annotation.Nullable;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.main.MainEvent;
import com.cfcp.a01.utils.GameLog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

public class LotteryResultFragment extends BaseFragment {

    public static LotteryResultFragment newInstance(){
        LotteryResultFragment MeFragment = new LotteryResultFragment();

        return MeFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_lottery_result;
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
        showMessage("开奖结果界面");
        EventBus.getDefault().post(new MainEvent(0));
    }
}

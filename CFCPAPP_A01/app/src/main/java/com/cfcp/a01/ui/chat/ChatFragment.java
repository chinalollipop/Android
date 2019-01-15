package com.cfcp.a01.ui.chat;

import android.os.Bundle;
import android.support.annotation.Nullable;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;

//优惠活动
public class ChatFragment extends BaseFragment {

    public static ChatFragment newInstance(){
        ChatFragment chatFragment = new ChatFragment();

        return chatFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_chat;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
//        EventBus.getDefault().register(this);
    }


    @Override
    public void onDestroyView() {
        super.onDestroyView();
//        EventBus.getDefault().unregister(this);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        //showMessage("开奖结果界面");
        //EventBus.getDefault().post(new MainEvent(0));
    }
}

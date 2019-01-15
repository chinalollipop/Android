package com.cfcp.a01.ui.activity;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.Unbinder;

//优惠活动
public class ActivityFragment extends BaseFragment {

    @BindView(R.id.activityRView)
    RecyclerView activityRView;

    public static ActivityFragment newInstance() {
        ActivityFragment activityFragment = new ActivityFragment();

        return activityFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_activity;
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

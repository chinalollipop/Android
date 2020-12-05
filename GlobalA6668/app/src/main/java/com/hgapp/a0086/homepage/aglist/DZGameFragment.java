package com.hgapp.a0086.homepage.aglist;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;


import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.util.IntervalClickListener;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.homepage.UserMoneyEvent;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.Arrays;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class DZGameFragment extends HGBaseFragment {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.dzTitleBack)
    NTitleBar dzTitleBack;
    private String getArgParam1;
    private String getArgParam2;

    public static DZGameFragment newInstance(String getArgParam1, String getArgParam2) {
        DZGameFragment fragment = new DZGameFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, getArgParam1);
        args.putString(ARG_PARAM2, getArgParam2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getString(ARG_PARAM2);
        }

        /*getActivity().getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);*/
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_dz_game;
    }

    @Subscribe
    public void onEventMain(UserMoneyEvent userMoneyEvent) {
        dzTitleBack.setMoreText(userMoneyEvent.money);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        dzTitleBack.setMoreText(getArgParam1);
        dzTitleBack.setBackListener(new IntervalClickListener() {
            @Override
            protected void onIntervalClick(View view) {
                finish();
            }
        });
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }



    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }

    @OnClick({R.id.FGGame, R.id.AGGame, R.id.CQ9Game, R.id.MGGame, R.id.MWGame, R.id.WWGame})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.FGGame:
                EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(getArgParam1, getArgParam1, "fg")), SupportFragment.SINGLETASK));
                break;
            case R.id.AGGame:
                EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(getArgParam1, getArgParam1, "game")), SupportFragment.SINGLETASK));
                break;
            case R.id.CQ9Game:
                EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(getArgParam1, getArgParam1, "cq")), SupportFragment.SINGLETASK));
                break;
            case R.id.MGGame:
                EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(getArgParam1, getArgParam1, "mg")), SupportFragment.SINGLETASK));
                break;
            case R.id.MWGame:
                EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(getArgParam1, getArgParam1, "mw")), SupportFragment.SINGLETASK));
                break;
            case R.id.WWGame:
                break;
        }
    }
}

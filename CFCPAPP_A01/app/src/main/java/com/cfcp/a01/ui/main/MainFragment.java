package com.cfcp.a01.ui.main;

import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.cfcp.a01.CFApplication;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.ui.event.EventFragment;
import com.cfcp.a01.ui.chat.ChatFragment;
import com.cfcp.a01.ui.home.HomeContract;
import com.cfcp.a01.ui.home.HomeFragment;
import com.cfcp.a01.ui.lottery.LotteryResultFragment;
import com.cfcp.a01.ui.me.MeFragment;
import com.cfcp.a01.common.widget.NoTouchViewPager;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import me.majiajie.pagerbottomtabstrip.NavigationController;
import me.majiajie.pagerbottomtabstrip.PageNavigationView;
import me.majiajie.pagerbottomtabstrip.item.BaseTabItem;
import me.yokeyword.fragmentation.SupportFragment;

public class MainFragment extends SupportFragment {//implements HomeContract.View

    @BindView(R.id.viewPager)
    NoTouchViewPager viewPager;
    @BindView(R.id.tab)
    PageNavigationView tab;

    public static final int FIRST = 0;
    public static final int SECOND = 1;
    public static final int THIRD = 2;
    public static final int FOURTH = 3;
    public static final int FIVE = 4;

    private SupportFragment[] mFragments = new SupportFragment[5];

    List<SupportFragment> stringList = new ArrayList<SupportFragment>();
    HomeContract.Presenter presenter;


    public static MainFragment newInstance() {
        MainFragment homeFragment = new MainFragment();
        //Injections.inject(homeFragment, null);
        return homeFragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_main, container, false);
        ButterKnife.bind(this, view);

        /*if (savedInstanceState == null) {
            mFragments[FIRST] = LoginHomeFragment.newInstance();
            mFragments[SECOND] = LoginHomeFragment.newInstance();
            mFragments[THIRD] = LoginHomeFragment.newInstance();
            mFragments[FOURTH] = LoginHomeFragment.newInstance();
            mFragments[FIVE] = LoginHomeFragment.newInstance();
            *//*loadMultipleRootFragment(R.id.fl_tab_container, FIRST,
                    mFragments[FIRST],
                    mFragments[SECOND],
                    mFragments[THIRD],
                    mFragments[FOURTH]);*//*
        } else {
            // 这里库已经做了Fragment恢复,所有不需要额外的处理了, 不会出现重叠问题

            // 这里我们需要拿到mFragments的引用,也可以通过getChildFragmentManager.getFragments()自行进行判断查找(效率更高些),用下面的方法查找更方便些
            mFragments[FIRST] = findChildFragment(MeFragment.class);
            mFragments[SECOND] = findChildFragment(LoginHomeFragment.class);
            mFragments[THIRD] = findChildFragment(LoginHomeFragment.class);
            mFragments[FOURTH] = findChildFragment(LoginHomeFragment.class);
            mFragments[FIVE] = findChildFragment(LoginHomeFragment.class);
        }*/


        stringList.add(HomeFragment.newInstance());
        stringList.add(ChatFragment.newInstance());
        stringList.add(EventFragment.newInstance());
        stringList.add(LotteryResultFragment.newInstance());
        stringList.add(MeFragment.newInstance());

        setEvents(savedInstanceState);
        return view;
    }


    private void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        presenters();

        NavigationController navigationController = tab.custom()
                .addItem(newItem(R.drawable.main_game,R.drawable.main_game_click,"游戏大厅"))
                .addItem(newItem(R.drawable.main_chat,R.drawable.main_chat_click,"在线聊天"))
                .addItem(newRoundItem(R.drawable.main_activity,R.drawable.main_activity_click,"优惠活动"))
                .addItem(newItem(R.drawable.main_lottery,R.drawable.main_lottery_click,"开奖结果"))
                .addItem(newItem(R.drawable.main_me,R.drawable.main_me_click,"用户中心"))
                .build();

        viewPager.setAdapter(new MyViewPagerAdapter(getActivity().getSupportFragmentManager(),stringList));
        //自动适配ViewPager页面切换
        navigationController.setupWithViewPager(viewPager);
        /*presenter.postBanner("");
        presenter.postNotice("","1");
        presenter.postWinNews("",System.currentTimeMillis()+"");*/
    }


  class MyViewPagerAdapter extends FragmentPagerAdapter {

        private List<SupportFragment> mFragments;

        public MyViewPagerAdapter(FragmentManager fm, List<SupportFragment> fragments) {
            super(fm);
            this.mFragments = fragments;
        }

        @Override
        public Fragment getItem(int position) {
            return mFragments.get(position);
        }

        @Override
        public int getCount() {
            return mFragments.size();
        }
    }


    /**
     * 正常tab
     */
    private BaseTabItem newItem(int drawable, int checkedDrawable, String text){
        SpecialTab mainTab = new SpecialTab(CFApplication.instance().getApplicationContext());
        mainTab.initialize(drawable,checkedDrawable,text);
        mainTab.setTextDefaultColor(0x56000000);
        mainTab.setTextCheckedColor(0xFFFF0000);
        return mainTab;
    }

    /**
     * 圆形tab
     */
    private BaseTabItem newRoundItem(int drawable,int checkedDrawable,String text){
        SpecialTabRound mainTab = new SpecialTabRound(CFApplication.instance().getApplicationContext());
        mainTab.initialize(drawable,checkedDrawable,text);
        mainTab.setTextDefaultColor(0x56000000);
        mainTab.setTextCheckedColor(0xFFFF0000);
        return mainTab;
    }

    /**
     * start other BrotherFragment
     */
    @Subscribe
    public void startBrother(StartBrotherEvent event) {
        start(event.targetFragment, event.launchmode);
    }

   /* @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
    }

    @Override
    public void setPresenter(HomeContract.Presenter presenter) {
        this.presenter = presenter;
    }*/

    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(MainEvent mainEvent) {
        viewPager.setCurrentItem(mainEvent.postion);
    }



}

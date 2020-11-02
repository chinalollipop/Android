package com.hgapp.betnhg;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.hgapp.betnhg.common.event.LogoutEvent;
import com.hgapp.betnhg.common.event.StartBrotherWithPopEvent;
import com.hgapp.betnhg.data.CheckUpgradeResult;
import com.hgapp.betnhg.depositpage.DepositFragment;
import com.hgapp.betnhg.homepage.HomepageFragment;
import com.hgapp.betnhg.interfaces.IBackPressedSupport;
import com.hgapp.betnhg.personpage.PersonFragment;
import com.hgapp.betnhg.upgrade.CheckUpdateContract;
import com.hgapp.betnhg.withdrawPage.WithdrawFragment;
import com.hgapp.common.util.Timber;
import com.hgapp.common.util.ToastUtils;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.base.BaseFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;
import me.yokeyword.sample.demo_wechat.event.TabSelectedEvent;
import me.yokeyword.sample.demo_wechat.ui.view.BottomBar;
import me.yokeyword.sample.demo_wechat.ui.view.BottomBarTab;

/**
 * Created by YoKeyword on 16/6/30.
 */
public class MainFragment2 extends BaseFragment implements CheckUpdateContract.View{
    private static final int REQ_MSG = 10;
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private String mParam1;
    private String mParam2;
    public static final int FIRST = 0;
    public static final int SECOND = 1;
    public static final int THIRD = 2;
    public static final int FOURTH = 3;

    private SupportFragment[] mFragments = new SupportFragment[4];

    private BottomBar mBottomBar;
    private int selectedPositon = 0;
    private boolean checkUpgradeDone = false;
    private CheckUpdateContract.Presenter presenter;

    public static MainFragment newInstance() {

        Bundle args = new Bundle();

        MainFragment fragment = new MainFragment();
        fragment.setArguments(args);
        return fragment;
    }

    public static MainFragment newInstance(String param1, String param2) {
        MainFragment fragment = new MainFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
        presenter = Injections.inject((CheckUpdateContract.View)this,null);
        if(!checkUpgradeDone)
        {
            presenter.checkupdate();
        }
    }


    /*
     * 添加手机注册成功之后需要直接跳转到存款界面
     * 方便以后扩展，请使用SINGLETASK来启动Fragment
     * add by ak
     */
    @Override
    protected void onNewBundle(Bundle args) {
        super.onNewBundle(args);
        if (args != null) {
            mParam1 = args.getString(ARG_PARAM1);
            mParam2 = args.getString(ARG_PARAM2);
            /*if("registerSuccessByPhone".equals(mParam1)){
                mBottomBar.setCurrentItem(1);
            }else if("distanceLogin".equals(mParam1)){
                mBottomBar.setCurrentItem(0);
            }
            else if(ActionOnFinish.GO_ADD_BANK.equals(mParam1))
            {
                mBottomBar.setCurrentItem(3);
                int banknumber = new Local().getBankNumber();
                boolean firstBank = (banknumber==0);
                start(AddBankFragment.newInstanceForAddBank(firstBank, ActionOnFinish.ADDBANK));
            }*/
        }
    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_main, container, false);

        if (savedInstanceState == null) {
            mFragments[FIRST] = HomepageFragment.newInstance();
            mFragments[SECOND] = DepositFragment.newInstance();
            mFragments[THIRD] = WithdrawFragment.newInstance();
            mFragments[FOURTH] = PersonFragment.newInstance();
            loadMultipleRootFragment(R.id.fl_tab_container, FIRST,
                    mFragments[FIRST],
                    mFragments[SECOND],
                    mFragments[THIRD],
                    mFragments[FOURTH]);
        } else {
            // 这里库已经做了Fragment恢复,所有不需要额外的处理了, 不会出现重叠问题

            // 这里我们需要拿到mFragments的引用,也可以通过getChildFragmentManager.getFragments()自行进行判断查找(效率更高些),用下面的方法查找更方便些
            mFragments[FIRST] = findChildFragment(HomepageFragment.class);
            mFragments[SECOND] = findChildFragment(DepositFragment.class);
            mFragments[THIRD] = findChildFragment(WithdrawFragment.class);
            mFragments[FOURTH] = findChildFragment(PersonFragment.class);
        }

        initView(view);
        return view;
    }

    private void initView(View view) {
        EventBus.getDefault().register(this);
        mBottomBar = (BottomBar) view.findViewById(R.id.bottomBar);

        mBottomBar
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_homepage, getString(R.string.str_title_homepage)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_deposit, getString(R.string.str_title_deposit)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_withdraw, getString(R.string.str_title_withdraw)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.selector_tab_person, getString(R.string.str_title_person)));


        // 模拟未读消息
        //mBottomBar.getItem(FIRST).setUnreadCount(9);

        mBottomBar.setOnTabSelectedListener(new BottomBar.OnTabSelectedListener() {
            @Override
            public void onTabSelected(int position, int prePosition) {
                /*boolean isLogin = UserManagerFactory.get().isLogin();
                if(!isLogin)
                {
                    Timber.d("没有登录，点击进入登陆界面 position:%d prePosition:%d",position,prePosition);
                    if(0 != position)
                    {
                        EventBus.getDefault().post(new StartBrotherEvent(NLoginFragment.newInstance("", ""), SupportFragment.SINGLETASK));
                        mBottomBar.setCurrentItem(0);
                    }
                    return;
                }

                selectedPositon = position;
                //add by AK 需求是每次进入存款界面都需要去抓取最新数据
                if(position==1){
                    EventBus.getDefault().post(new ChoiceBankEvent(position+""));
                }*/
                showHideFragment(mFragments[position], mFragments[prePosition]);
            }

            @Override
            public void onTabUnselected(int position) {

            }

            @Override
            public void onTabReselected(int position) {
                // 这里推荐使用EventBus来实现 -> 解耦
                // 在FirstPagerFragment,FirstHomeFragment中接收, 因为是嵌套的Fragment
                // 主要为了交互: 重选tab 如果列表不在顶部则移动到顶部,如果已经在顶部,则刷新
                EventBus.getDefault().post(new TabSelectedEvent(position));
            }
        });
    }

    @Override
    protected void onFragmentResult(int requestCode, int resultCode, Bundle data) {
        super.onFragmentResult(requestCode, resultCode, data);
        if (requestCode == REQ_MSG && resultCode == RESULT_OK) {
        }
    }

    /**
     * start other BrotherFragment
     */
    @Subscribe
    public void startBrother(StartBrotherEvent event) {
        start(event.targetFragment,event.launchmode);
    }

    @Subscribe
    public void onLogoutEvent(LogoutEvent event)
    {
       setSelectTab(0);
    }
    @Subscribe
    public void startBrotherWithPop(StartBrotherWithPopEvent event)
    {
        Timber.d("收到事件 StartBrotherWithPopEvent --> %s",event.getClass().getName());
        getTopFragment().startWithPop(event.targetFragment);
    }
    @Override
    public void onDestroyView() {
        EventBus.getDefault().unregister(this);
        super.onDestroyView();
    }

    @Override
    public boolean onBackPressedSupport()
    {
        //处理root fragment的返回事件，因为root fragment被嵌套在MainFragment.返回事件只能传达到MainFragment.
        //所以，这里做一个事件传递
        if(selectedPositon >= 0  && selectedPositon < mFragments.length)
        {
            if(mFragments[selectedPositon] instanceof IBackPressedSupport)
            {
                IBackPressedSupport backPressedSupport = (IBackPressedSupport)mFragments[selectedPositon];
                boolean handled = backPressedSupport.backPressedHandled();
                if(handled)
                {
                    return true;
                }
            }
        }
        return false;
    }

    @Override
    public void onStop()
    {
        super.onStop();
        presenter.destroy();
    }
    @Override
    public boolean wantShowMessage() {
        return false;
    }

    @Override
    public void setData(CheckUpgradeResult checkUpgradeResult) {

       /* if(null != checkUpgradeResult && null != checkUpgradeResult.upgradeApkInfo)
        {
            checkUpgradeDone = true;
            UpgradeDialog.newInstance(checkUpgradeResult).show(getFragmentManager());
        }*/
    }

    @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
    }

    @Override
    public void setStart(int action) {

    }

    @Override
    public void setError(int action, int errcode) {

    }

    @Override
    public void setError(int action, String errString) {

    }

    @Override
    public void setComplete(int action) {

    }

    @Override
    public void setPresenter(CheckUpdateContract.Presenter presenter) {
        this.presenter = presenter;
    }

    private void setSelectTab(int position)
    {
        mBottomBar.setCurrentItem(position);
        int prePosition = selectedPositon;
        showHideFragment(mFragments[position], mFragments[prePosition]);
        selectedPositon = position;
    }
}

package com.flush.a01.ui.home;

import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.jude.rollviewpager.RollPagerView;
import com.flush.a01.Injections;
import com.flush.a01.R;
import com.flush.a01.base.IPresenter;
import com.flush.a01.base.event.StartBrotherEvent;
import com.flush.a01.data.BannerResult;
import com.flush.a01.data.LoginResult;
import com.flush.a01.data.LogoutResult;
import com.flush.a01.data.NoticeResult;
import com.flush.a01.data.WinNewsResult;
import com.flush.a01.ui.home.fastlogout.LogoutFragment;
import com.flush.a01.ui.loginhome.LoginHomeFragment;
import com.flush.a01.utils.Check;
import com.flush.a01.utils.GameLog;
import com.flush.a01.utils.ToastUtils;
import com.flush.a01.widget.MarqueeTextView;
import com.flush.a01.widget.RollPagerViewManager;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

public class HomeFragment extends SupportFragment implements HomeContract.View, View.OnTouchListener {

    @BindView(R.id.homeAccountLogo)
    ImageView homeAccountLogo;
    @BindView(R.id.homeAccountName)
    TextView homeAccountName;
    @BindView(R.id.homeUserMoney)
    TextView homeUserMoney;
    @BindView(R.id.homeRefresh)
    ImageView homeRefresh;
    @BindView(R.id.homeMusic)
    ImageView homeMusic;
    @BindView(R.id.homeCheck)
    ImageView homeCheck;
    @BindView(R.id.homeRegent)
    ImageView homeRegent;

    @BindView(R.id.homeKy)
    ImageView homeKy;
    @BindView(R.id.homeVg)
    ImageView homeVg;
    @BindView(R.id.homeBy)
    ImageView homeBy;
    @BindView(R.id.homeHg)
    ImageView homeHg;

    @BindView(R.id.homePop)
    ImageView homePop;
    @BindView(R.id.homeRollpageView)
    RollPagerView homeRollpageView;
    @BindView(R.id.homeGg)
    MarqueeTextView homeGg;
    @BindView(R.id.homeWinNews)
    MarqueeTextView homeWinNews;
    @BindView(R.id.homeDeposit)
    ImageView homeDeposit;
    @BindView(R.id.homeUserCenter)
    ImageView homeUserCenter;
    @BindView(R.id.homeActivity)
    ImageView homeActivity;
    @BindView(R.id.homeService)
    ImageView homeService;
    @BindView(R.id.homeShare)
    ImageView homeShare;

    private RollPagerViewManager rollPagerViewManager;
    private ScheduledExecutorService executorService;
    List<String> stringList = new ArrayList<String>();
    HomeContract.Presenter presenter;

    //通过用户名是否为空来判断是否登录成功
    private String accountName ="";
    LoginResult loginResult;

    public static HomeFragment newInstance() {
        HomeFragment homeFragment = new HomeFragment();
        Injections.inject(homeFragment, null);
        return homeFragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_home, container, false);
        ButterKnife.bind(this, view);
        setEvents(savedInstanceState);
        return view;
    }

    private void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        homeKy.setOnTouchListener(this);
        homeVg.setOnTouchListener(this);
        homeBy.setOnTouchListener(this);
        homeHg.setOnTouchListener(this);
        presenters();
        presenter.postBanner("");
        presenter.postNotice("","1");
        presenter.postWinNews("",System.currentTimeMillis()+"");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(new Runnable() {
            @Override
            public void run() {
                presenter.postWinNews("",System.currentTimeMillis()+"");
            }
        }, 0, 15000, TimeUnit.MILLISECONDS);
    }

    /**
     * start other BrotherFragment
     */
    @Subscribe
    public void startBrother(StartBrotherEvent event) {
        start(event.targetFragment, event.launchmode);
    }

    @OnClick({R.id.homeAccountName, R.id.homeRefresh, R.id.homeMusic, R.id.homeCheck, R.id.homeRegent, R.id.homePop,
            R.id.homeDeposit, R.id.homeUserCenter, R.id.homeActivity, R.id.homeService, R.id.homeShare,
            R.id.homeHg,R.id.homeVg,R.id.homeKy,R.id.homeBy})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.homeAccountName:
                if(Check.isEmpty(accountName)){
                    EventBus.getDefault().post(new StartBrotherEvent(LoginHomeFragment.newInstance(), SupportFragment.SINGLETASK));
                }else{
                    LogoutFragment.newInstance(loginResult).show(getFragmentManager());
                }
                break;
            case R.id.homeRefresh:
                break;
            case R.id.homeMusic:
                break;
            case R.id.homeCheck:
                break;
            case R.id.homeRegent:
                break;
            case R.id.homePop:
                break;
            case R.id.homeDeposit:
                break;
            case R.id.homeUserCenter:
                break;
            case R.id.homeActivity:
                break;
            case R.id.homeService:
                break;
            case R.id.homeShare:
                break;
            case R.id.homeHg:
                showMessage("去皇冠了");
                break;
            case R.id.homeVg:
                showMessage("去VG棋牌");
                break;
            case R.id.homeKy:
                showMessage("去开元棋牌了");
                break;
            case R.id.homeBy:
                showMessage("去捕鱼了");
                break;
        }
    }

    @Override
    public void postBannerResult(BannerResult bannerResult) {
        rollPagerViewManager  = new RollPagerViewManager(homeRollpageView, bannerResult.getData());
        //rollPagerViewManager.testImagesLocal(null);
        rollPagerViewManager.testImagesNet(null,null);

    }

    @Override
    public void postNoticeResult(List<NoticeResult> noticeResult) {
        int size =noticeResult.size();
        List<String> stringList = new ArrayList<String>();
        for(int i=0;i<size;++i){
            stringList.add(noticeResult.get(i).getContent());
        }
        if(stringList.size()==1){
            stringList.add(stringList.get(0));
        }
        homeGg.setContentList(stringList);
    }

    @Override
    public void postWinNewsResult(WinNewsResult winNewsResult) {
        stringList.clear();
        stringList.add(winNewsResult.getData());
        stringList.add(winNewsResult.getData());
        homeWinNews.setVisibility(View.VISIBLE);
        homeWinNews.setContentList(stringList);
        homeWinNews.postDelayed(new Runnable() {
            @Override
            public void run() {
                homeWinNews.setVisibility(View.GONE);
            }
        },5000);

    }

    @Override
    public void postLogoutResult(String logoutResult) {
        showMessage(logoutResult);
        accountName = "";
        homeAccountLogo.setImageDrawable(getResources().getDrawable(R.mipmap.home_login_account));
        homeAccountName.setText("请先登录");
        homeUserMoney.setText("0");
    }

    @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
    }

    @Override
    public void setPresenter(HomeContract.Presenter presenter) {
        this.presenter = presenter;
    }

    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if (null != executorService) {
            GameLog.log("关闭计数任务跑马灯");
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================首页获取到消息了================");
        this.loginResult = loginResult;
        accountName = loginResult.getUserName();
        homeAccountLogo.setImageDrawable(getResources().getDrawable(R.mipmap.home_gold));
        homeAccountName.setText(accountName);
        homeUserMoney.setText(loginResult.getMoney());
    }

    @Subscribe
    public void onEventMain(LogoutResult loginResult) {
        GameLog.log("================用户退出了================");
        accountName = "";
        this.loginResult = null;
        homeAccountLogo.setImageDrawable(getResources().getDrawable(R.mipmap.home_login_account));
        homeAccountName.setText("请先登录");
        homeUserMoney.setText("0");
    }

    @Override
    public boolean onTouch(View view, MotionEvent event) {
        switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN:
                if (view.getId() == R.id.homeBy) {
                        homeBy.setScaleX(1.1f);
                        homeBy.setScaleY(1.1f);
                        break;
                }else if(view.getId() == R.id.homeHg) {
                    homeHg.setScaleX(1.1f);
                    homeHg.setScaleY(1.1f);
                }else if(view.getId() == R.id.homeVg) {
                    homeVg.setScaleX(1.1f);
                    homeVg.setScaleY(1.1f);
                }else if(view.getId() == R.id.homeKy) {
                    homeKy.setScaleX(1.1f);
                    homeKy.setScaleY(1.1f);
                }
                break;
            case MotionEvent.ACTION_UP:
                if (view.getId() == R.id.homeBy) {
                    homeBy.setScaleX((float) 0.95);
                    homeBy.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeHg) {
                    homeHg.setScaleX((float) 0.95);
                    homeHg.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeVg) {
                    homeVg.setScaleX((float) 0.95);
                    homeVg.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeKy) {
                    homeKy.setScaleX((float) 0.95);
                    homeKy.setScaleY((float) 0.95);
                }
                break;
        }
        return false;
    }
}

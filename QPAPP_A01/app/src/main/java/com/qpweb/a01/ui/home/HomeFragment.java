package com.qpweb.a01.ui.home;

import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.jude.rollviewpager.RollPagerView;
import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.base.event.StartBrotherEvent;
import com.qpweb.a01.data.BannerResult;
import com.qpweb.a01.data.NoticeResult;
import com.qpweb.a01.data.WinNewsResult;
import com.qpweb.a01.ui.loginhome.LoginHomeFragment;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.widget.MarqueeTextView;
import com.qpweb.a01.widget.RollPagerViewManager;

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

public class HomeFragment extends SupportFragment implements HomeContract.View{

    @BindView(R.id.homeAccountLogo)
    ImageView homeAccountLogo;
    @BindView(R.id.homeLoginName)
    TextView homeLoginName;
    @BindView(R.id.homeRefresh)
    ImageView homeRefresh;
    @BindView(R.id.homeMusic)
    ImageView homeMusic;
    @BindView(R.id.homeCheck)
    ImageView homeCheck;
    @BindView(R.id.homeRegent)
    ImageView homeRegent;
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

    @OnClick({R.id.homeLoginName, R.id.homeRefresh, R.id.homeMusic, R.id.homeCheck, R.id.homeRegent, R.id.homePop, R.id.homeDeposit, R.id.homeUserCenter, R.id.homeActivity, R.id.homeService, R.id.homeShare})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.homeLoginName:
                EventBus.getDefault().post(new StartBrotherEvent(LoginHomeFragment.newInstance(), SupportFragment.SINGLETASK));
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
    public void showMessage(String message) {

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
    }
}

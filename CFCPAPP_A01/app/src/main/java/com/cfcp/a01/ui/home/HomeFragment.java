package com.cfcp.a01.ui.home;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.base.BaseFragment;
import com.cfcp.a01.base.IPresenter;
import com.cfcp.a01.base.event.StartBrotherEvent;
import com.cfcp.a01.common.adapters.AutoSizeRVAdapter;
import com.cfcp.a01.data.BannerResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.data.NoticeResult;
import com.cfcp.a01.data.WinNewsResult;
import com.cfcp.a01.ui.home.enumeration.LotteryType;
import com.cfcp.a01.ui.home.fastlogout.LogoutFragment;
import com.cfcp.a01.ui.loginhome.LoginHomeFragment;
import com.cfcp.a01.utils.Check;
import com.cfcp.a01.utils.GameLog;
import com.cfcp.a01.utils.NetworkUtils;
import com.cfcp.a01.utils.ToastUtils;
import com.cfcp.a01.widget.GridRvItemDecoration;
import com.cfcp.a01.widget.MarqueeTextView;
import com.cfcp.a01.widget.RollPagerViewManager;
import com.jude.rollviewpager.RollPagerView;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.ScheduledExecutorService;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;
import me.yokeyword.fragmentation.SupportFragment;

public class HomeFragment extends BaseFragment implements HomeContract.View {


    @BindView(R.id.homeName)
    TextView homeName;
    @BindView(R.id.homeRollpageView)
    RollPagerView homeRollpageView;
    @BindView(R.id.homeMarquee)
    MarqueeTextView homeMarquee;
    @BindView(R.id.homeDeposit)
    TextView homeDeposit;
    @BindView(R.id.homeDraw)
    TextView homeDraw;
    @BindView(R.id.homeDown)
    TextView homeDown;
    @BindView(R.id.homeService)
    TextView homeService;
    @BindView(R.id.homeOfficial)
    TextView homeOfficial;
    @BindView(R.id.homeCredit)
    TextView homeCredit;
    @BindView(R.id.homeQiPai)
    TextView homeQiPai;
    @BindView(R.id.homeRecView)
    RecyclerView homeRecView;
    Unbinder unbinder;
    private RollPagerViewManager rollPagerViewManager;
    private ScheduledExecutorService executorService;
    List<String> stringList = new ArrayList<String>();
    HomeContract.Presenter presenter;

    //通过用户名是否为空来判断是否登录成功
    private String accountName = "";
    LoginResult loginResult;
    private static List<HomeIconEvent> homeGameList = new ArrayList<HomeIconEvent>();
    static {
        homeGameList.add(new HomeIconEvent("五分彩","每分钟一期",R.mipmap.home_wfc,LotteryType.TYPE_5FC,5));
        homeGameList.add(new HomeIconEvent("极速赛车","每分钟一期",R.mipmap.home_jssc,LotteryType.TYPE_JSSC,5));
        homeGameList.add(new HomeIconEvent("重庆时时彩","每分钟一期",R.mipmap.home_cqssc,LotteryType.TYPE_CQSSC,5));
        homeGameList.add(new HomeIconEvent("北京PK10","每分钟一期",R.mipmap.home_pk10,LotteryType.TYPE_BJPK10,5));
        homeGameList.add(new HomeIconEvent("三分彩","每分钟一期",R.mipmap.home_sfc,LotteryType.TYPE_3FC,5));
        homeGameList.add(new HomeIconEvent("分分彩","每分钟一期",R.mipmap.home_ffc,LotteryType.TYPE_1FC,5));
        homeGameList.add(new HomeIconEvent("11选5","每分钟一期",R.mipmap.home_11ffc,LotteryType.TYPE_11X5,5));
        homeGameList.add(new HomeIconEvent("极速快3","每分钟一期",R.mipmap.home_jsk3,LotteryType.TYPE_JSK3,5));
        homeGameList.add(new HomeIconEvent("广东11选5","每分钟一期",R.mipmap.home_11ffc_gd,LotteryType.TYPE_11X5_GD,5));
        homeGameList.add(new HomeIconEvent("快3分分彩","每分钟一期",R.mipmap.home_k3ff,LotteryType.TYPE_K3FFC,5));
        homeGameList.add(new HomeIconEvent("极速3D","每分钟一期",R.mipmap.home_js3d,LotteryType.TYPE_JS3D,5));
        homeGameList.add(new HomeIconEvent("北京快乐8","每分钟一期",R.mipmap.home_bjkl8,LotteryType.TYPE_BJKL8,5));
        homeGameList.add(new HomeIconEvent("11选5三分彩","每分钟一期",R.mipmap.home_11sfc,LotteryType.TYPE_11X5_3FC,5));
    }

    public static HomeFragment newInstance() {
        HomeFragment homeFragment = new HomeFragment();
        //Injections.inject(homeFragment, null);
        return homeFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_home;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        ArrayList<String> marqueeList = new ArrayList<>();
        marqueeList.add("1,入款停用通知，2，新彩坡马上上线敬请关注，3，添加5分彩，3分彩，请大家玩的愉快！");
        marqueeList.add("1,入款停用通知，2，新彩坡马上上线敬请关注，3，添加5分彩，3分彩，请大家玩的愉快！");
        homeMarquee.setContentList(marqueeList);

        rollPagerViewManager  = new RollPagerViewManager(homeRollpageView, new ArrayList<BannerResult.DataBean>() );
        //rollPagerViewManager.testImagesNet(null);
        rollPagerViewManager.testImagesLocal(null);
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),2, OrientationHelper.VERTICAL,false);
        homeRecView.setLayoutManager(gridLayoutManager);
        homeRecView.setHasFixedSize(true);
        homeRecView.setNestedScrollingEnabled(false);
        homeRecView.addItemDecoration(new GridRvItemDecoration(getContext()));
        homeRecView.setAdapter(new HomaGameAdapter(getContext(),R.layout.item_game_home,homeGameList));

       /* presenter.postBanner("");
        presenter.postNotice("","1");
        presenter.postWinNews("",System.currentTimeMillis()+"");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(new Runnable() {
            @Override
            public void run() {
                presenter.postWinNews("",System.currentTimeMillis()+"");
            }
        }, 0, 15000, TimeUnit.MILLISECONDS);*/
    }

    class HomaGameAdapter extends AutoSizeRVAdapter<HomeIconEvent> {

        public HomaGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
        }

        @Override
        protected void convert(ViewHolder holder, HomeIconEvent data, final int position) {
            /*TextView textView = holder.getView(R.id.itemHomeIconName);
            if(position==8){
                textView.setTextColor(getResources().getColor(R.color.event_red));
            }else{
                textView.setTextColor(getResources().getColor(R.color.login_left));
            }*/
            holder.setText(R.id.itemHomeIconName,data.getIconName());
            holder.setText(R.id.itemHomeIconDescribe,data.getIconDescribe());
            holder.setBackgroundRes(R.id.itemHomeIconDrawable,data.getIconDrawable());
            holder.setOnClickListener(R.id.itemHomeShow, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(!NetworkUtils.isConnected()){
                        showMessage("请检查您的网络！");
                        return;
                    }
                    onHomeGameItemClick(position);
                }
            });
        }
    }

    private void onHomeGameItemClick(int position) {
        showMessage("你点击了 " +position);
    }


    /**
     * start other BrotherFragment
     */
    @Subscribe
    public void startBrother(StartBrotherEvent event) {
        start(event.targetFragment, event.launchmode);
    }


    @Override
    public void postBannerResult(BannerResult bannerResult) {
        rollPagerViewManager = new RollPagerViewManager(homeRollpageView, bannerResult.getData());
        //rollPagerViewManager.testImagesLocal(null);
        rollPagerViewManager.testImagesNet(null, null);

    }

    @Override
    public void postNoticeResult(List<NoticeResult> noticeResult) {
        int size = noticeResult.size();
        List<String> stringList = new ArrayList<String>();
        for (int i = 0; i < size; ++i) {
            stringList.add(noticeResult.get(i).getContent());
        }
        if (stringList.size() == 1) {
            stringList.add(stringList.get(0));
        }
        homeMarquee.setContentList(stringList);
    }

    @Override
    public void postWinNewsResult(WinNewsResult winNewsResult) {
        stringList.clear();
        stringList.add(winNewsResult.getData());
        stringList.add(winNewsResult.getData());

    }

    @Override
    public void postLogoutResult(String logoutResult) {
        showMessage(logoutResult);
        accountName = "";
        homeName.setText("登录");
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
        unbinder.unbind();
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================首页获取到消息了================");
        this.loginResult = loginResult;
        accountName = loginResult.getUserName();
        homeName.setText(accountName);
    }

    @Subscribe
    public void onEventMain(LogoutResult loginResult) {
        GameLog.log("================用户退出了================");
        accountName = "";
        this.loginResult = null;
        homeName.setText("请先登录");
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // TODO: inflate a fragment view
        View rootView = super.onCreateView(inflater, container, savedInstanceState);
        unbinder = ButterKnife.bind(this, rootView);
        return rootView;
    }

    @OnClick({R.id.homeName, R.id.homeDeposit, R.id.homeDraw, R.id.homeDown, R.id.homeService, R.id.homeOfficial, R.id.homeCredit, R.id.homeQiPai})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.homeName:
                if(Check.isEmpty(accountName)){
                    EventBus.getDefault().post(new StartBrotherEvent(LoginHomeFragment.newInstance(), SupportFragment.SINGLETASK));
                }else{
                    LogoutFragment.newInstance(loginResult).show(getFragmentManager());
                }
                break;
            case R.id.homeDeposit:
                break;
            case R.id.homeDraw:
                break;
            case R.id.homeDown:
                break;
            case R.id.homeService:
                break;
            case R.id.homeOfficial:
                break;
            case R.id.homeCredit:
                break;
            case R.id.homeQiPai:
                break;
        }
    }
}

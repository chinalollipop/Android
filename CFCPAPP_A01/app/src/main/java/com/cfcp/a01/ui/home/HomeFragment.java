package com.cfcp.a01.ui.home;

import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.http.Client;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.common.utils.ToastUtils;
import com.cfcp.a01.common.utils.Utils;
import com.cfcp.a01.common.widget.GridRvItemDecoration;
import com.cfcp.a01.common.widget.MarqueeTextView;
import com.cfcp.a01.common.widget.RollPagerViewManager;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BannerResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.data.NoticeResult;
import com.cfcp.a01.ui.home.bet.BetFragment;
import com.cfcp.a01.ui.home.cplist.CPOrderFragment;
import com.cfcp.a01.ui.home.deposit.DepositFragment;
import com.cfcp.a01.ui.home.login.fastlogin.LoginFragment;
import com.cfcp.a01.ui.home.playgame.XPlayGameActivity;
import com.cfcp.a01.ui.home.playgame.XPlayGameFragment;
import com.cfcp.a01.ui.home.sidebar.SideBarFragment;
import com.cfcp.a01.ui.home.withdraw.WithDrawFragment;
import com.cfcp.a01.ui.main.MainEvent;
import com.cfcp.a01.ui.me.bankcard.CardFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.jude.rollviewpager.RollPagerView;
import com.tencent.smtt.export.external.interfaces.JsPromptResult;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.sdk.CookieManager;
import com.tencent.smtt.sdk.CookieSyncManager;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebSettings;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.ScheduledExecutorService;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import okhttp3.internal.Util;

import static com.cfcp.a01.common.utils.Utils.getContext;

public class HomeFragment extends BaseFragment implements HomeContract.View {

    @BindView(R.id.homeMenu)
    ImageView homeMenu;
    @BindView(R.id.homeName)
    TextView homeName;
    @BindView(R.id.homeNotice)
    LinearLayout homeNotice;
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
    TextView homeService;/*
    @BindView(R.id.homeLayout)
    TabLayout homeLayout;*/

    @BindView(R.id.homeOfficial)
    LinearLayout homeOfficial;
    @BindView(R.id.homeCredit)
    LinearLayout homeCredit;
    @BindView(R.id.homeQiPai)
    LinearLayout homeQiPai;
    @BindView(R.id.homeOfficialImg)
    View homeOfficialImg;
    @BindView(R.id.homeCreditImg)
    View homeCreditImg;
    @BindView(R.id.homeQiPaiImg)
    View homeQiPaiImg;
    @BindView(R.id.homeRecView)
    RecyclerView homeRecView;
    HomeGameAdapter homeGameAdapter;
    private RollPagerViewManager rollPagerViewManager;
    private ScheduledExecutorService executorService;

    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLottery  = new ArrayList<>();
    private List<AllGamesResult.DataBean.LotteriesBean> XinYongLotteries = new ArrayList<>();
    private static List<AllGamesResult.DataBean.LotteriesBean> GameVideos  = new ArrayList<>();
    HomeContract.Presenter presenter;

    //通过用户名是否为空来判断是否登录成功
    private String accountName = "";
    LoginResult loginResult;
    boolean isLoadAlread ;
    int postion=0;
    //公告数据
    ArrayList<LoginResult.NoticeListBean> noticeListBeanList;

    //private static List<HomeIconEvent> homeGameList = new ArrayList<HomeIconEvent>();

    static {

        GameVideos.add(new AllGamesResult.DataBean.LotteriesBean(101,"电子游戏","DZYX","更多精彩游戏"));
        GameVideos.add(new AllGamesResult.DataBean.LotteriesBean(101,"真人视讯","ZRSX","更多精彩游戏"));
        GameVideos.add(new AllGamesResult.DataBean.LotteriesBean(101,"AG捕鱼","AGBY","更多精彩游戏"));
        GameVideos.add(new AllGamesResult.DataBean.LotteriesBean(101,"开元棋牌","KYQP","更多精彩游戏"));
       /* homeGameList.add(new HomeIconEvent("五分彩", "每分钟一期", R.mipmap.home_wfc, LotteryType.TYPE_5FC, 1));
        homeGameList.add(new HomeIconEvent("极速赛车", "每分钟一期", R.mipmap.home_jssc, LotteryType.TYPE_JSSC, 2));
        homeGameList.add(new HomeIconEvent("重庆时时彩", "每分钟一期", R.mipmap.home_cqssc, LotteryType.TYPE_CQSSC, 3));
        homeGameList.add(new HomeIconEvent("北京PK10", "每分钟一期", R.mipmap.home_pk10, LotteryType.TYPE_BJPK10, 4));
        homeGameList.add(new HomeIconEvent("三分彩", "每分钟一期", R.mipmap.home_sfc, LotteryType.TYPE_3FC, 5));
        homeGameList.add(new HomeIconEvent("分分彩", "每分钟一期", R.mipmap.home_ffc, LotteryType.TYPE_1FC, 6));
        homeGameList.add(new HomeIconEvent("11选5", "每分钟一期", R.mipmap.home_11ffc, LotteryType.TYPE_11X5, 7));
        homeGameList.add(new HomeIconEvent("极速快3", "每分钟一期", R.mipmap.home_jsk3, LotteryType.TYPE_JSK3, 8));
        homeGameList.add(new HomeIconEvent("广东11选5", "每分钟一期", R.mipmap.home_11ffc_gd, LotteryType.TYPE_11X5_GD, 9));
        homeGameList.add(new HomeIconEvent("快3分分彩", "每分钟一期", R.mipmap.home_k3ff, LotteryType.TYPE_K3FFC, 10));
        homeGameList.add(new HomeIconEvent("极速3D", "每分钟一期", R.mipmap.home_js3d, LotteryType.TYPE_JS3D, 11));
        homeGameList.add(new HomeIconEvent("北京快乐8", "每分钟一期", R.mipmap.home_bjkl8, LotteryType.TYPE_BJKL8, 12));
        homeGameList.add(new HomeIconEvent("11选5三分彩", "每分钟一期", R.mipmap.home_11sfc, LotteryType.TYPE_11X5_3FC, 13));*/
    }

    public static HomeFragment newInstance() {
        HomeFragment homeFragment = new HomeFragment();
        Injections.inject(homeFragment, null);
        return homeFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_home;
    }

    private View tabCustomView(String name,int iconID){
        View newtab =  LayoutInflater.from(getActivity()).inflate(R.layout.item_tablayout,null);
        TextView tv = newtab.findViewById(R.id.tabText);
        tv.setText(name);
        ImageView im = newtab.findViewById(R.id.tabIcon);
        im.setImageResource(iconID);
        return newtab;
    }

    /**
     *  用来改变tabLayout选中后的字体大小及颜色
     * @param tab
     * @param isSelect
     */
    private void updateTabView(TabLayout.Tab tab, boolean isSelect) {
        //找到自定义视图的控件ID
        TextView  tv_tab = tab.getCustomView().findViewById(R.id.tabText);
        if(isSelect) {
            //设置标签选中
            tv_tab.setSelected(true);
            //选中后字体变大
            tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX,getResources().getDimensionPixelSize(R.dimen.sp_18));
            tv_tab.setTextColor(getResources().getColor(R.color.text_bet_submit));
        }else{
            //设置标签取消选中
            tv_tab.setSelected(false);
            //恢复为默认字体大小
            tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX,getResources().getDimensionPixelSize(R.dimen.sp_14));
            tv_tab.setTextColor(getResources().getColor(R.color.text_main));
        }
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        /*if(isLoadAlread){
            GameLog.log("已经加载过数据了 不需要再次加载了");
            return;
        }*/
        //添加tab列表
        /*homeLayout.addTab(homeLayout.newTab().setCustomView(tabCustomView("官方玩法",R.mipmap.home_gf)));
        homeLayout.addTab(homeLayout.newTab().setCustomView(tabCustomView("信用玩法",R.mipmap.home_xy)));
        homeLayout.addTab(homeLayout.newTab().setCustomView(tabCustomView("真人/棋牌/捕鱼",R.mipmap.home_qp)));

        homeLayout.addOnTabSelectedListener(new TabLayout.BaseOnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                updateTabView(tab,true);
                switch (tab.getPosition()) {
                    case 0:
                        homeGameAdapter = new HomeGameAdapter(R.layout.item_game_home, AvailableLottery);
                        homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                            @Override
                            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                                onHomeGameItemClick(AvailableLottery.get(position));
                            }
                        });
                        homeRecView.setAdapter(homeGameAdapter);
                        break;
                    case 1:
                        homeGameAdapter = new HomeGameAdapter( R.layout.item_game_home, XinYongLotteries);
                        homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                            @Override
                            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                                onHomeGameItemClick(XinYongLotteries.get(position));
                            }
                        });
                        homeRecView.setAdapter(homeGameAdapter);
                        break;
                    case 2:

                        break;
                }
            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {
                updateTabView(tab,false);
            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });
        TextView  tv_tab = homeLayout.getTabAt(0).getCustomView().findViewById(R.id.tabText);
        //设置标签选中
        tv_tab.setSelected(true);
        //选中后字体变大
        tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX,getResources().getDimensionPixelSize(R.dimen.sp_18));
        tv_tab.setTextColor(getResources().getColor(R.color.text_bet_submit));*/
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
        homeRecView.setLayoutManager(gridLayoutManager);
        homeRecView.setHasFixedSize(true);
        homeRecView.setNestedScrollingEnabled(false);
        homeRecView.addItemDecoration(new GridRvItemDecoration(getContext()));

        //读取本地官网和信用盘数据并展示
        AvailableLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG), AllGamesResult.DataBean.LotteriesBean.class);
        XinYongLotteries = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_XINYONG), AllGamesResult.DataBean.LotteriesBean.class);
        if (!Check.isNull(AvailableLottery)) {
            GameLog.log("加载本地的官网数据。。。。");
            homeGameAdapter = new HomeGameAdapter( R.layout.item_game_home, AvailableLottery);
            homeRecView.setAdapter(homeGameAdapter);
            homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                @Override
                public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                    onHomeGameItemClick(AvailableLottery.get(position));
                }
            });
        }

        BannerResult bannerResult = JSON.parseObject(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_BANNER), BannerResult.class);
        if (!Check.isNull(bannerResult)) {
            rollPagerViewManager = new RollPagerViewManager(homeRollpageView, bannerResult.getData());
            //rollPagerViewManager.testImagesLocal(null);
            GameLog.log("加载本地的 USERNAME_HOME_BANNER");
            rollPagerViewManager.testImagesNet(this,null, null);
        }

        NoticeResult noticeResult = JSON.parseObject(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_NOTICE), NoticeResult.class);
        if (!Check.isNull(noticeResult)) {
            List<String> stringList = new ArrayList<String>();
            int size = noticeResult.getData().size();
            for (int i = 0; i < size; ++i) {
                stringList.add(noticeResult.getData().get(i).getTitle());
            }
            GameLog.log("加载本地的 USERNAME_HOME_NOTICE");
            homeMarquee.setContentList(stringList);
        }

        //请求数据接口
        presenter.getBanner("");
        presenter.getNotice("");
        presenter.getAllGames("");
       /*  presenter.postWinNews("",System.currentTimeMillis()+"");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(new Runnable() {
            @Override
            public void run() {
                presenter.postWinNews("",System.currentTimeMillis()+"");
            }
        }, 0, 15000, TimeUnit.MILLISECONDS);*/
    }

    class HomeGameAdapter extends BaseQuickAdapter<AllGamesResult.DataBean.LotteriesBean, BaseViewHolder> {

        public HomeGameAdapter(int layoutId, @Nullable List datas) {
            super(layoutId, datas);
        }

        private void onShowImage(String identifier,BaseViewHolder holder){
            int ids =  R.mipmap.gf_ssc;
            switch (identifier){
                case "XYFT":
                    ids = R.mipmap.xy_xyft;
                    break;
                case "CQSSC":
                    ids = R.mipmap.gf_ssc;
                    break;
                case "GD115":
                    ids = R.mipmap.gf_11x5;
                    break;
                case "BJPK10":
                    ids = R.mipmap.gf_pk10;
                    break;
                case "GWFFC":
                    ids = R.mipmap.gf_ffc;
                    break;
                case "GW115":
                    ids = R.mipmap.gf_gd;
                    break;
                case "JSK3":
                    ids = R.mipmap.gf_jsks;//江苏快三
                    break;
                case "GW3FC":
                    ids = R.mipmap.gf_sfc;
                    break;
                case "GWK3":
                    ids = R.mipmap.gf_ks;
                    break;
                case "GWPK10":
                    ids = R.mipmap.gf_jspk10;
                    break;
                case "GW3D":
                    ids = R.mipmap.gf_jisu3d;
                    break;
                case "GW5FC":
                    ids = R.mipmap.gf_wufc;
                    break;
                case "BJKL8":
                    ids = R.mipmap.gf_ks8;
                    break;
                case "GW115SFC":
                    ids = R.mipmap.gf_sanf;
                    break;

                case "LkShip":
                    ids = R.mipmap.xy_xyft;
                    break;
                case "MarkSix":
                    ids = R.mipmap.xy_xglhc;
                    break;
                case "PCEgg":
                    ids = R.mipmap.xy_pcdd;
                    break;
                case "JSQk3":
                    ids = R.mipmap.gf_jsks;
                    break;
                case "FastPK10":
                    ids = R.mipmap.xy_jssc;
                    break;
                case "FastSSC":
                    ids = R.mipmap.gf_ffc;
                    break;
                case "GDHp10":
                    ids = R.mipmap.xy_klsf;
                    break;
                case "CQFarm":
                    ids = R.mipmap.xy_xync;
                    break;
                case "BJHp8":
                    ids = R.mipmap.xy_ks8;
                    break;
                case "ALISSC":
                    ids = R.mipmap.xy_ali2fen;
                    break;
                case "TXSSC":
                    ids = R.mipmap.xy_tx3;
                    break;
                case "BDSSC":
                    ids = R.mipmap.xy_baidu5fc;
                    break;
                case "DZYX":
                    ids = R.mipmap.other_dz;
                    break;
                case "ZRSX":
                    ids = R.mipmap.other_zr;
                    break;
                case "AGBY":
                    ids = R.mipmap.other_ag;
                    break;
                case "KYQP":
                    ids = R.mipmap.other_qp;
                    break;
                case "AHK3":
                    ids = R.mipmap.gf_ahk3;
                    break;

            }
            holder.setBackgroundRes(R.id.itemHomeIconDrawable, ids);
        }

        @Override
        protected void convert(BaseViewHolder holder, final AllGamesResult.DataBean.LotteriesBean data) {
            /*TextView textView = holder.getView(R.id.itemHomeIconName);
            if(position==8){
                textView.setTextColor(getResources().getColor(R.color.event_red));
            }else{
                textView.setTextColor(getResources().getColor(R.color.login_left));
            }*/
            onShowImage(data.getIdentifier(),holder);
            holder.setText(R.id.itemHomeIconName, data.getName()).
                    setText(R.id.itemHomeIconDescribe, data.getSub_title()).
                    addOnClickListener(R.id.itemHomeShow);
            /*holder.setOnClickListener(R.id.itemHomeShow, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if (!NetworkUtils.isConnected()) {
                        showMessage("请检查您的网络！");
                        return;
                    }
                    onHomeGameItemClick(data);
                }
            });*/
        }
    }

    private void onHomeGameItemClick(AllGamesResult.DataBean.LotteriesBean lotteriesBean) {
        //未登录 请先登录再做其他操作
        String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        if(Check.isEmpty(token)){
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
            return;
        }
        if(postion==1){
            Intent intent  = new Intent(getContext(), CPOrderFragment.class);
            intent.putExtra("gameId",lotteriesBean.getId()+"");
            intent.putExtra("gameName",lotteriesBean.getName());
            startActivity(intent);
        }else if(postion==0){
            EventBus.getDefault().post(new StartBrotherEvent(BetFragment.newInstance(lotteriesBean,(ArrayList)AvailableLottery), SupportFragment.SINGLETASK));
        }else{
            //presenter.getKaiYuanGame("");
            String url =  Client.baseUrl()+"service?packet=ThirdGame&action=KaiyuanGame&way=index&token="+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
//            initWebView(url);
            /*EventBus.getDefault().post(new StartBrotherEvent(XPlayGameFragment.newInstance(
                    "开元棋牌",url,""), SupportFragment.SINGLETASK));*/
            Intent intent = new Intent(getContext(), XPlayGameActivity.class);
            intent.putExtra("url",url);
            intent.putExtra("gameCnName","开元棋牌");
            intent.putExtra("hidetitlebar",false);
            getActivity().startActivity(intent);
        }
    }


    /**
     * start other BrotherFragment
     */
    /*@Subscribe
    public void startBrother(StartBrotherEvent event) {
        start(event.targetFragment, event.launchmode);
    }*/


    @Override
    public void getBannerResult(BannerResult bannerResult) {
        GameLog.log("展示接口的 USERNAME_HOME_BANNER");
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_BANNER, JSON.toJSONString(bannerResult));
        rollPagerViewManager = new RollPagerViewManager(homeRollpageView, bannerResult.getData());
        //rollPagerViewManager.testImagesLocal(null);
        rollPagerViewManager.testImagesNet(this,null, null);
    }

    @Override
    public void getNoticeResult(NoticeResult noticeResult) {
        GameLog.log("展示接口的 USERNAME_HOME_NOTICE");
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_NOTICE, JSON.toJSONString(noticeResult));
        int size = noticeResult.getData().size();
        List<String> stringList = new ArrayList<String>();
        for (int i = 0; i < size; ++i) {
            stringList.add(noticeResult.getData().get(i).getTitle());
        }
        if (stringList.size() == 1) {
            stringList.add(stringList.get(0));
        }
        homeMarquee.setContentList(stringList);
    }

    @Override
    public void getAllGamesResult(AllGamesResult allGamesResult) {
        isLoadAlread = true;
        //保存本地数据 用于没有网络时候的展示
        XinYongLotteries = allGamesResult.getData().getXinYongLotteries();
        AvailableLottery = allGamesResult.getData().getAvailableLottery();
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_XINYONG, JSON.toJSONString(XinYongLotteries));
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_GUANWANG, JSON.toJSONString(AvailableLottery));
        homeGameAdapter = new HomeGameAdapter( R.layout.item_game_home, AvailableLottery);
        homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                onHomeGameItemClick(AvailableLottery.get(position));
            }
        });
        homeRecView.setAdapter(homeGameAdapter);
        GameLog.log("信用盘口："+XinYongLotteries.size());
        GameLog.log("官方盘口："+AvailableLottery.size());
    }

    @Override
    public void postLogoutResult(String logoutResult) {
        showMessage(logoutResult);
        accountName = "";
        homeName.setText("登录");
    }

    @Override
    public void getJointLoginResult(String logoutResult) {

        GameLog.log("双面盘联合登录成功 ");
        if(!Check.isEmpty(logoutResult)){
            showMessage(logoutResult);
        }
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

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        //先判断是否登录  如果没有登录 需要登录然后在显示这个界面
        String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        GameLog.log(" onSupportVisible  首页 个人的token是 "+token );
        if(Check.isEmpty(token)){
            accountName = "";
            this.loginResult = null;
            noticeListBeanList = null;
            ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, "");
            homeName.setText("登录/注册");
            homeName.setVisibility(View.VISIBLE);
            homeMenu.setVisibility(View.GONE);
        }else{
            homeName.setVisibility(View.GONE);
            homeMenu.setVisibility(View.VISIBLE);
            homeName.setText("");
        }
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================首页获取到消息了================" + loginResult.getNoticeList());
        if(!Check.isEmpty(loginResult.getNoticeList())){
            noticeListBeanList = (ArrayList<LoginResult.NoticeListBean>) loginResult.getNoticeList();
            ACache.get(getContext()).put(CFConstant.USERNAME_HOME_EVENTLIST, JSON.toJSONString(noticeListBeanList));
            EventShowDialog.newInstance((ArrayList<LoginResult.NoticeListBean>) loginResult.getNoticeList(),"").show(getFragmentManager());
        }

        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, loginResult.getToken());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_NAME, loginResult.getName());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_ACCOUNT, loginResult.getUsername());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_NICK, loginResult.getNickname());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_QQ, loginResult.getQq());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PHONE, loginResult.getMobile());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_EMAIL, loginResult.getEmail());
        if(loginResult.isFund_password_exist()){
            ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_FUND_PWD, "1");
        }else{
            ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_FUND_PWD, "0");
        }
        //presenter.getJointLogin(loginResult.getUsername());

        homeName.setVisibility(View.GONE);
        homeMenu.setVisibility(View.VISIBLE);
        /*this.loginResult = loginResult;
        accountName = loginResult.getUserName();
        homeName.setText(accountName);*/
        //String url =  Client.baseUrl()+"service?packet=ThirdGame&action=KaiyuanGame&way=index&token="+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
       // initWebView(url);
    }

    @Subscribe
    public void onEventMain(LogoutResult logoutResult) {
        GameLog.log("================用户退出了================");
        accountName = "";
        this.loginResult = null;
        noticeListBeanList = null;
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, "");
        homeName.setText("登录/注册");
        homeName.setVisibility(View.VISIBLE);
        homeMenu.setVisibility(View.GONE);
    }

    @OnClick({R.id.homeNotice,R.id.homeMenu, R.id.homeName, R.id.homeDeposit, R.id.homeDraw, R.id.homeDown, R.id.homeService, R.id.homeOfficial, R.id.homeCredit, R.id.homeQiPai})//
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.homeNotice:
                if(!Check.isNull(noticeListBeanList)){
                    EventShowDialog.newInstance(noticeListBeanList,"").show(getFragmentManager());
                }
                break;
            case R.id.homeMenu:
                SideBarFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.homeName:
                //EventBus.getDefault().post(new StartBrotherEvent(SideBarFragment.newInstance(), SupportFragment.SINGLETASK));
                if (Check.isEmpty(accountName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                } else {

                }
                break;
            case R.id.homeDeposit:
                if("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                //检查是否登录 如果未登录  请调整到登录页先登录
                String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if(Check.isEmpty(token)){
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
                }else{
                    EventBus.getDefault().post(new StartBrotherEvent(DepositFragment.newInstance(), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeDraw:
                if("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                //检查是否登录 如果未登录  请调整到登录页先登录
                String token1 = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if(Check.isEmpty(token1)){
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
                }else if(Check.isEmpty(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NAME))){
                    EventBus.getDefault().post(new StartBrotherEvent(CardFragment.newInstance("","")));
                }else {
                    EventBus.getDefault().post(new StartBrotherEvent(WithDrawFragment.newInstance("", ""), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeDown:
                break;
            case R.id.homeService:
                EventBus.getDefault().post(new MainEvent(1));
                break;
            case R.id.homeOfficial:
                postion = 0;
                homeOfficialImg.setBackgroundColor(getResources().getColor(R.color.home_method_line));
                homeCreditImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeQiPaiImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeGameAdapter = new HomeGameAdapter(R.layout.item_game_home, AvailableLottery);
                homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                    @Override
                    public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                        onHomeGameItemClick(AvailableLottery.get(position));
                    }
                });
                homeRecView.setAdapter(homeGameAdapter);
                break;
            case R.id.homeCredit:
                postion = 1;
                homeOfficialImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeCreditImg.setBackgroundColor(getResources().getColor(R.color.home_method_line));
                homeQiPaiImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeGameAdapter = new HomeGameAdapter( R.layout.item_game_home, XinYongLotteries);
                homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                    @Override
                    public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                        onHomeGameItemClick(XinYongLotteries.get(position));
                    }
                });
                homeRecView.setAdapter(homeGameAdapter);
                break;
            case R.id.homeQiPai:
                postion = 2;
                homeOfficialImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeCreditImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeQiPaiImg.setBackgroundColor(getResources().getColor(R.color.home_method_line));

                homeGameAdapter = new HomeGameAdapter( R.layout.item_game_home, GameVideos);
                homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                    @Override
                    public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                        if(position==0){
                            onHomeGameItemClick(GameVideos.get(position));
                        }else{
                            showMessage(GameVideos.get(position).getName()+"敬请期待");
                        }
                    }
                });
                homeRecView.setAdapter(homeGameAdapter);
                break;
        }
    }
}

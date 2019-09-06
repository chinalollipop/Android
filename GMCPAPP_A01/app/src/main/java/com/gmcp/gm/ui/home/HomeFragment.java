package com.gmcp.gm.ui.home;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.app.AppCompatActivity;
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
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.gmcp.gm.CFConstant;
import com.gmcp.gm.Injections;
import com.gmcp.gm.R;
import com.gmcp.gm.common.base.BaseFragment;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.event.StartBrotherEvent;
import com.gmcp.gm.common.http.Client;
import com.gmcp.gm.common.http.MyHttpClient;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.utils.ToastUtils;
import com.gmcp.gm.common.utils.Utils;
import com.gmcp.gm.common.widget.CustomPopWindow;
import com.gmcp.gm.common.widget.GridRvItemDecoration;
import com.gmcp.gm.common.widget.MarqueeTextView;
import com.gmcp.gm.common.widget.RollPagerViewManager;
import com.gmcp.gm.data.AgGamePayResult;
import com.gmcp.gm.data.AllGamesResult;
import com.gmcp.gm.data.BannerResult;
import com.gmcp.gm.data.DomainUrl;
import com.gmcp.gm.data.GameQueueMoneyResult;
import com.gmcp.gm.data.LoginResult;
import com.gmcp.gm.data.LogoutResult;
import com.gmcp.gm.data.NoticeResult;
import com.gmcp.gm.ui.home.bet.BetFragment;
import com.gmcp.gm.ui.home.cplist.CPOrderFragment;
import com.gmcp.gm.ui.home.deposit.DepositFragment;
import com.gmcp.gm.ui.home.dragon.DragonFragment;
import com.gmcp.gm.ui.home.login.fastlogin.LoginFragment;
import com.gmcp.gm.ui.home.playgame.XPlayGameActivity;
import com.gmcp.gm.ui.home.service.ServiceFragment;
import com.gmcp.gm.ui.home.sidebar.SideBarFragment;
import com.gmcp.gm.ui.home.withdraw.WithDrawFragment;
import com.jude.rollviewpager.RollPagerView;
import com.kongzue.dialog.util.DialogSettings;
import com.kongzue.dialog.v3.MessageDialog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.ScheduledExecutorService;

import butterknife.BindView;
import butterknife.OnClick;
import me.jessyan.retrofiturlmanager.RetrofitUrlManager;
import me.yokeyword.fragmentation.SupportFragment;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

import static com.kongzue.dialog.util.DialogSettings.STYLE.STYLE_IOS;

public class HomeFragment extends BaseFragment implements HomeContract.View {

    @BindView(R.id.tvHomePageLine)
    TextView tvHomePageLine;
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
    private CustomPopWindow mCustomPopWindowIn;
    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLottery = new ArrayList<>();
    private List<AllGamesResult.DataBean.LotteriesBean> XinYongLotteries = new ArrayList<>();

    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLotteryNew = new ArrayList<>();
    private List<AllGamesResult.DataBean.LotteriesBean> ThirdGames = new ArrayList<>();
    private List<AllGamesResult.DataBean.LotteriesBean> XinYongLotteriesNew = new ArrayList<>();


    HomeContract.Presenter presenter;

    String blocked = "0";
    //通过用户名是否为空来判断是否登录成功
    private String accountName = "";
    LoginResult loginResult;
    boolean isLoadAlread;
    int postion = 0;
    String gameUrl, gameName;
    String action = "AgGame";
    //公告数据
    ArrayList<LoginResult.NoticeListBean> noticeListBeanList;

    public static HomeFragment newInstance() {
        HomeFragment homeFragment = new HomeFragment();
        Injections.inject(homeFragment, null);
        return homeFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_home;
    }

    private View tabCustomView(String name, int iconID) {
        View newtab = LayoutInflater.from(getActivity()).inflate(R.layout.item_tablayout, null);
        TextView tv = newtab.findViewById(R.id.tabText);
        tv.setText(name);
        ImageView im = newtab.findViewById(R.id.tabIcon);
        im.setImageResource(iconID);
        return newtab;
    }

    /**
     * 用来改变tabLayout选中后的字体大小及颜色
     *
     * @param tab
     * @param isSelect
     */
    private void updateTabView(TabLayout.Tab tab, boolean isSelect) {
        //找到自定义视图的控件ID
        TextView tv_tab = tab.getCustomView().findViewById(R.id.tabText);
        if (isSelect) {
            //设置标签选中
            tv_tab.setSelected(true);
            //选中后字体变大
            tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX, getResources().getDimensionPixelSize(R.dimen.sp_18));
            tv_tab.setTextColor(getResources().getColor(R.color.text_bet_submit));
        } else {
            //设置标签取消选中
            tv_tab.setSelected(false);
            //恢复为默认字体大小
            tv_tab.setTextSize(TypedValue.COMPLEX_UNIT_PX, getResources().getDimensionPixelSize(R.dimen.sp_14));
            tv_tab.setTextColor(getResources().getColor(R.color.text_main));
        }
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        DomainUrl domainUrl = JSON.parseObject(ACache.get(getContext()).getAsString("homeLineChoice"), DomainUrl.class);
        if (!Check.isNull(domainUrl)) {
            int sizeq = domainUrl.getList().size();
            for (int k = 0; k < sizeq; ++k) {
                if (domainUrl.getList().get(k).isChecked()) {
                    tvHomePageLine.setText("线路" + domainUrl.getList().get(k).getPid());
                }
            }
        }

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
        AvailableLotteryNew = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG+"_new"), AllGamesResult.DataBean.LotteriesBean.class);

        XinYongLotteriesNew = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_XINYONG+"_new"), AllGamesResult.DataBean.LotteriesBean.class);

        ThirdGames = JSON.parseArray(ACache.get(getContext()).getAsString("ThirdGames"), AllGamesResult.DataBean.LotteriesBean.class);

        if (!Check.isNull(XinYongLotteriesNew)) {
            GameLog.log("加载本地的官网数据。。。。");
            homeGameAdapter = new HomeGameAdapter(R.layout.item_game_home, XinYongLotteriesNew);
            homeRecView.setAdapter(homeGameAdapter);
            homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                @Override
                public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                    onHomeGameItemClick(XinYongLotteriesNew.get(position));
                }
            });
        }

        BannerResult bannerResult = JSON.parseObject(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_BANNER), BannerResult.class);
        if (!Check.isNull(bannerResult)) {
            rollPagerViewManager = new RollPagerViewManager(homeRollpageView, bannerResult.getData());
            //rollPagerViewManager.testImagesLocal(null);
            GameLog.log("加载本地的 USERNAME_HOME_BANNER");
            rollPagerViewManager.testImagesNet(this, null, null);
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
        postion = 0;
        //请求数据接口
        if (!Check.isNull(presenter)) {
            presenter.getBanner("");
            presenter.getNotice("");
            presenter.getAllGames("");
            presenter.getAllGamesNew("");
        }
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

        public HomeGameAdapter(int layoutId, @Nullable List<AllGamesResult.DataBean.LotteriesBean> datas) {
            super(layoutId, datas);
        }

        private void onShowImage(String identifier, BaseViewHolder holder) {
            int ids = R.mipmap.gf_ssc;
            switch (identifier) {
                case "XYFT":
                    ids = R.mipmap.xy_xyft;
                    break;
                case "CQSSC":
                    ids = R.mipmap.gf_ssc;
                    break;
                case "XCQSSC":
                case "XCQSSC1":
                    ids = R.mipmap.gf_xssc;
                    break;
                case "GD115":
                    ids = R.mipmap.gf_gd;
                    break;
                case "BJPK10":
                    ids = R.mipmap.gf_pk10;
                    break;
                case "BJPK105fc":
                    ids = R.mipmap.xy_js5sc;
                    break;
                case "BJPK105FC":
                    ids = R.mipmap.gf_js5sc;
                    break;
                case "GWFFC":
                    ids = R.mipmap.gf_ffc;
                    break;
                case "GW115":
                    ids = R.mipmap.gf_11x5;
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
                case "JSMarkSix":
                    ids = R.mipmap.xy_jslhc;
                    break;
                case "PCEgg":
                    ids = R.mipmap.xy_pcdd;
                    break;
                case "JSQk3":
                    ids = R.mipmap.gf_jsks;
                    break;
                case "JSQk35fc":
                case "JSK35FC":
                    ids = R.mipmap.gf_kswfc;
                    break;
                case "JSQk33fc":
                case "JSK33FC":
                    ids = R.mipmap.gf_kssfc;
                    break;
                case "JSQk3ffc":
                case "JSQk3FFC":
                    ids = R.mipmap.gf_ksffc;
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
                case "lyqp":
                    ids = R.mipmap.other_ly;
                    break;
                case "dzyx":
                    ids = R.mipmap.other_dz;
                    break;
                case "zrsx":
                    ids = R.mipmap.other_zr;
                    break;
                case "agby":
                    ids = R.mipmap.other_ag;
                    break;
                case "kyqp":
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
            onShowImage(data.getIdentifier(), holder);
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


    private void loadGameData() {

        MyHttpClient myHttpClient = new MyHttpClient();
        myHttpClient.executeGet(gameUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                homeQiPaiImg.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("====================1=======================");
                    }
                });
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                try {
                    final AgGamePayResult agGamePayResult = JSON.parseObject(responseText, AgGamePayResult.class);
                    if (agGamePayResult.getErrno().equals("7503")) {
                        homeQiPaiImg.post(new Runnable() {
                            @Override
                            public void run() {
                                GameLog.log("====================2=======================");
                                GameLog.log("yicyy " + agGamePayResult.getError());
                                ToastUtils.showLongToast(agGamePayResult.getError());
                                   /* DialogSettings.style = DialogSettings.STYLE_IOS;
                                    MessageDialog.show(XPlayGameActivity.this, "提示", agGamePayResult.getError(), "知道了", new DialogInterface.OnClickListener() {
                                        @Override
                                        public void onClick(DialogInterface dialog, int which) {
                                            finish();
                                        }
                                    });*/
                            }
                        });
                    } else if (agGamePayResult.getErrno().equals("7513")) {
                        ToastUtils.showLongToast(agGamePayResult.getError());
                    } else if (agGamePayResult.getErrno().equals("3004")) {
                        ToastUtils.showLongToast(agGamePayResult.getError());
                    } else {
                        Intent intent1 = new Intent(getContext(), XPlayGameActivity.class);
                        intent1.putExtra("url", gameUrl);
                        intent1.putExtra("gameCnName", gameName);
                        intent1.putExtra("hidetitlebar", false);
                        getActivity().startActivity(intent1);
                    }
                } catch (Exception exception) {
                    Intent intent1 = new Intent(getContext(), XPlayGameActivity.class);
                    intent1.putExtra("url", gameUrl);
                    intent1.putExtra("gameCnName", gameName);
                    intent1.putExtra("hidetitlebar", false);
                    getActivity().startActivity(intent1);
                }
            }
        });
    }

    private void onHomeGameItemClick(AllGamesResult.DataBean.LotteriesBean lotteriesBean) {
        //未登录 请先登录再做其他操作
        String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        if (Check.isEmpty(token)) {
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
            return;
        }
        GameLog.log("用时是否锁住了 " + blocked);
        String blocked = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BLOCKED);
        if (!Check.isNull(blocked) && blocked.equals("2")) {
            //showMessage("禁止投注,请联系客服");
            DialogSettings.style = STYLE_IOS;
            MessageDialog.show((AppCompatActivity) _mActivity, "提示", "禁止投注,请联系客服", "知道了");
            return;
        }
        String type = lotteriesBean.getType();
        GameLog.log("当前用户的类型"+type);
        //type: 官方：1  信用：2 棋牌：3 捕鱼 : 4 真人：5 电子：6
        switch (type){
            case "1":
                EventBus.getDefault().post(new StartBrotherEvent(BetFragment.newInstance(lotteriesBean, (ArrayList<AllGamesResult.DataBean.LotteriesBean>) AvailableLottery), SupportFragment.SINGLETASK));
                break;
            case "2":
                Intent intent = new Intent(getContext(), CPOrderFragment.class);
                intent.putExtra("gameId", lotteriesBean.getId() + "");
                intent.putExtra("gameName", lotteriesBean.getName());
                startActivity(intent);
                break;
            case "3":
                if ("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                if (lotteriesBean.getIdentifier().equals("lyqp")) {
                    if ("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))) {
                        showMessage("非常抱歉，请您注册真实会员！");
                        return;
                    }
                    action = "LeyouGame";
                    gameName = "乐游棋牌";
                    gameUrl = Client.baseUrl() + "service?packet=ThirdGame&action=LeyouGame&way=index&token=" + ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                    //loadGameData();
                    Intent intent2 = new Intent(getContext(), XPlayGameActivity.class);
                    intent2.putExtra("url", gameUrl);
                    intent2.putExtra("gameCnName", "乐游棋牌");
                    intent2.putExtra("hidetitlebar", false);
                    getActivity().startActivity(intent2);

                } else if (lotteriesBean.getIdentifier().equals("kyqp")) {
                    if ("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))) {
                        showMessage("非常抱歉，请您注册真实会员！");
                        return;
                    }
                    action = "KaiyuanGame";
                    gameName = "开元棋牌";
                    gameUrl = Client.baseUrl() + "service?packet=ThirdGame&action=KaiyuanGame&way=index&token=" + ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                    //loadGameData();
                    Intent intent1 = new Intent(getContext(), XPlayGameActivity.class);
                    intent1.putExtra("url", gameUrl);
                    intent1.putExtra("gameCnName", gameName);
                    intent1.putExtra("hidetitlebar", false);
                    getActivity().startActivity(intent1);

                }
                break;
            case "4":
                if ("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                action = "AgGame";
                gameName = "AG扑鱼";
                gameUrl = Client.baseUrl() + "service?packet=ThirdGame&gameid=6&gameType=fishes&isTest=0&action=AgGame&way=login&token=" + ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if ("1".equals(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_IS_AGENT))) {
                    showMessage("代理禁止游戏！");
                    return;
                }
                //loadGameData();
                Intent intent5 = new Intent(getContext(), XPlayGameActivity.class);
                intent5.putExtra("url", gameUrl);
                intent5.putExtra("gameCnName", "AG扑鱼");
                intent5.putExtra("hidetitlebar", false);
                getActivity().startActivity(intent5);
                break;
            case "5":
                if ("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                action = "AgGame";
                gameName = "真人视讯";
                gameUrl = Client.baseUrl() + "service?packet=ThirdGame&gameid=8776&gameType=immortal&isTest=0&action=AgGame&way=login&token=" + ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if ("1".equals(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_IS_AGENT))) {
                    showMessage("代理禁止游戏！");
                    return;
                }
                //loadGameData();
                Intent intent4 = new Intent(getContext(), XPlayGameActivity.class);
                intent4.putExtra("url", gameUrl);
                intent4.putExtra("gameCnName", "真人视讯");
                intent4.putExtra("hidetitlebar", false);
                getActivity().startActivity(intent4);
                break;
            case "6":
                if ("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                action = "AgGame";
                gameName = "电子游戏";
                gameUrl = Client.baseUrl() + "service?packet=ThirdGame&gameid=101&gameType=electronic&isTest=0&action=AgGame&way=login&token=" + ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if ("1".equals(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_IS_AGENT))) {
                    showMessage("代理禁止游戏！");
                    return;
                }
                //loadGameData();
                Intent intent3 = new Intent(getContext(), XPlayGameActivity.class);
                intent3.putExtra("url", gameUrl);
                intent3.putExtra("gameCnName", "电子游戏");
                intent3.putExtra("hidetitlebar", false);
                getActivity().startActivity(intent3);
                break;
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
        rollPagerViewManager.testImagesNet(this, null, null);
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
    public void getAllGamesNewResult(AllGamesResult allGamesResult) {
        isLoadAlread = true;
        GameLog.log("获取新的的数据");
        //保存本地数据 用于没有网络时候的展示
        XinYongLotteriesNew = allGamesResult.getData().getXinYongLotteries();
        AvailableLotteryNew = allGamesResult.getData().getAvailableLottery();
        ThirdGames = allGamesResult.getData().getThirdGames();
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_GUANWANG+"_new", JSON.toJSONString(AvailableLotteryNew));
        ACache.get(getContext()).put("ThirdGames", JSON.toJSONString(ThirdGames));
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_XINYONG+"_new", JSON.toJSONString(XinYongLotteriesNew));

        homeGameAdapter = new HomeGameAdapter(R.layout.item_game_home, XinYongLotteriesNew);
        homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                onHomeGameItemClick(XinYongLotteriesNew.get(position));
            }
        });
        homeRecView.setAdapter(homeGameAdapter);
        GameLog.log("信用盘口：" + XinYongLotteriesNew.size());
        GameLog.log("官方盘口：" + AvailableLotteryNew.size());
    }

    @Override
    public void getAllGamesResult(AllGamesResult allGamesResult) {
        GameLog.log("获取以前的数据");
        XinYongLotteries = allGamesResult.getData().getXinYongLotteries();
        AvailableLottery = allGamesResult.getData().getAvailableLottery();
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_GUANWANG, JSON.toJSONString(AvailableLottery));
        ACache.get(getContext()).put(CFConstant.USERNAME_HOME_XINYONG, JSON.toJSONString(XinYongLotteries));

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
        if (!Check.isEmpty(logoutResult)) {
            showMessage(logoutResult);
        }
    }

    @Override
    public void getAGGamesResult(AllGamesResult allGamesResult) {
        GameLog.log("---getAGGamesResult---");

    }

    @Override
    public void getAGVideoGamesResult(AllGamesResult allGamesResult) {
        GameLog.log("---getAGVideoGamesResult---");

    }

    @Override
    public void getAGFishGamesResult(AllGamesResult allGamesResult) {
        GameLog.log("---getAGFishGamesResult---");
    }

    @Override
    public void getPlayOutWithMoneyResult(GameQueueMoneyResult gameQueueMoneyResult) {
        GameLog.log("---getPlayOutWithMoneyResult---");
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
        GameLog.log(" onSupportVisible  首页 个人的token是 " + token);
        if (Check.isEmpty(token)) {
            accountName = "";
            this.loginResult = null;
            noticeListBeanList = null;
            ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, "");
            homeName.setText("登录/注册");
            homeName.setVisibility(View.VISIBLE);
            homeMenu.setVisibility(View.GONE);
        } else {
            homeName.setVisibility(View.GONE);
            homeMenu.setVisibility(View.VISIBLE);
            homeName.setText("");
        }
    }

    @Subscribe
    public void onEventMain(CloseGameViewEvent closeGameViewEvent) {
        GameLog.log("--CloseGameViewEvent---");
        presenter.getPlayOutWithMoney(action);
    }


    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        blocked = loginResult.getBlocked() + "";
        presenter.getPlayOutWithMoney("AgGame");
        presenter.getPlayOutWithMoney("KaiyuanGame");
        presenter.getPlayOutWithMoney("LeyouGame");
        GameLog.log("================首页获取到消息了================" + loginResult.getNoticeList());
        if (!Check.isEmpty(loginResult.getNoticeList())) {
            noticeListBeanList = (ArrayList<LoginResult.NoticeListBean>) loginResult.getNoticeList();
            ACache.get(getContext()).put(CFConstant.USERNAME_HOME_EVENTLIST, JSON.toJSONString(noticeListBeanList));
            homeNotice.performClick();
            //EventShowDialog.newInstance((ArrayList<LoginResult.NoticeListBean>) loginResult.getNoticeList(),"").show(getFragmentManager());
        }

        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, loginResult.getToken());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_BLOCKED, loginResult.getBlocked() + "");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_IS_AGENT, loginResult.getIs_agent() + "");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_NAME, loginResult.getName());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_CHAT_ROOM, loginResult.getChat_domain() + "/room/test22.php");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_ACCOUNT, loginResult.getUsername());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_NICK, loginResult.getNickname());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_QQ, loginResult.getQq());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_PHONE, loginResult.getMobile());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_EMAIL, loginResult.getEmail());
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_DEMO, loginResult.getIs_tester() == 1 ? "true" : "false");
        if (loginResult.isFund_password_exist()) {
            ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_FUND_PWD, "1");
        } else {
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
        GameLog.log("=======首页=========用户退出了================" + logoutResult.getMessage());
        if (!"您已登出".equals(logoutResult.getMessage())) {
            showMessage("用户已退出登录！");
        }
        accountName = "";
        this.loginResult = null;
        noticeListBeanList = null;
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN, "");
        homeName.setText("登录/注册");
        homeName.setVisibility(View.VISIBLE);
        homeMenu.setVisibility(View.GONE);
    }

    private void showPopMenuIn() {
        View contentView = LayoutInflater.from(getContext()).inflate(R.layout.pop_line_choice, null);
        //处理popWindow 显示内容
        DomainUrl domainUrl = JSON.parseObject(ACache.get(getContext()).getAsString("homeLineChoice"), DomainUrl.class);
        if (Check.isNull(domainUrl)) {
            showMessage("目前已是最优线路");
            return;
        }
        RecyclerView recyclerView = contentView.findViewById(R.id.popLineChoice);
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        recyclerView.setLayoutManager(gridLayoutManager);
        recyclerView.setHasFixedSize(true);
        recyclerView.setAdapter(new LineChoiceAdapter(R.layout.pop_line_choice_item, domainUrl.getList()));

        mCustomPopWindowIn = new CustomPopWindow.PopupWindowBuilder(getContext())
                .setView(contentView)
                .enableBackgroundDark(true)
                .create()
                .showAsDropDown(tvHomePageLine, 0, 0);
        //}
    }


    class LineChoiceAdapter extends BaseQuickAdapter<DomainUrl.ListBean, BaseViewHolder> {
        private Context context;

        public LineChoiceAdapter(int layoutId, @Nullable List datas) {
            super(layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(BaseViewHolder holder, final DomainUrl.ListBean data) {
            holder.setText(R.id.popLineName, "线路" + data.getPid());
            if (data.isChecked()) {
                holder.setBackgroundRes(R.id.popLineImg, R.mipmap.line_choice_cheack1);
            } else {
                holder.setBackgroundRes(R.id.popLineImg, R.mipmap.line_choice_cheack2);
            }
            holder.setOnClickListener(R.id.popLineName, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    DomainUrl domainUrl = JSON.parseObject(ACache.get(getContext()).getAsString("homeLineChoice"), DomainUrl.class);
                    int size = domainUrl.getList().size();
                    for (int k = 0; k < size; ++k) {
                        if (domainUrl.getList().get(k).getUrl().equals(data.getUrl())) {
                            domainUrl.getList().get(k).setChecked(true);
                        } else {
                            domainUrl.getList().get(k).setChecked(false);
                        }
                    }
                    ACache.get(getContext()).put("homeLineChoice", JSON.toJSONString(domainUrl));
                    ACache.get(getContext()).put("homeTYUrl", data.getUrl());
                    ACache.get(getContext()).put("homeCPUrl", data.getUrl());
                    //ACache.get(getContext()).put("app_demain_url", data.getUrl());
                    RetrofitUrlManager.getInstance().setGlobalDomain(data.getUrl());
                   /* Client.setClientDomain(data.getUrl());
                    HGApplication.instance().configClient();*/
                    /*CPClient.setClientDomain(data.getUrl().replace("m.","mc."));
                    HGApplication.instance().configCPClient();*/
                    tvHomePageLine.setText("线路" + data.getPid());
                    mCustomPopWindowIn.dissmiss();
                    //onHomeGameItemClick(data.getId());
                }
            });
        }
    }


    @OnClick({R.id.homeNotice, R.id.tvHomePageLine, R.id.homeMenu, R.id.homeName, R.id.homeDeposit, R.id.homeDraw, R.id.homeDown, R.id.homeService, R.id.homeOfficial, R.id.homeCredit, R.id.homeQiPai})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.tvHomePageLine:
                showPopMenuIn();
                break;
            case R.id.homeNotice:
                if (!Check.isNull(noticeListBeanList)) {
                    EventShowDialog.newInstance(noticeListBeanList, "").show(getFragmentManager());
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
                /*if("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                //检查是否登录 如果未登录  请调整到登录页先登录
                String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if (Check.isEmpty(token)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(DepositFragment.newInstance(), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeDraw:
                /*if("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                //检查是否登录 如果未登录  请调整到登录页先登录
                String token1 = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if (Check.isEmpty(token1)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
                } /*else if (Check.isEmpty(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NAME))) {
                    EventBus.getDefault().post(new StartBrotherEvent(CardFragment.newInstance("", "")));
                }*/ else {
                    EventBus.getDefault().post(new StartBrotherEvent(WithDrawFragment.newInstance("", ""), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeDown:
                String token2 = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
                if (Check.isEmpty(token2)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
                } else {
                    String blocked = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BLOCKED);
                    if (!Check.isNull(blocked) && blocked.equals("2")) {
                        //showMessage("禁止投注,请联系客服");
                        DialogSettings.style = STYLE_IOS;
                        MessageDialog.show((AppCompatActivity) _mActivity, "提示", "禁止投注,请联系客服", "知道了");
                        return;
                    }
                    EventBus.getDefault().post(new StartBrotherEvent(DragonFragment.newInstance("", "")));
                }
                break;
            case R.id.homeService:
                EventBus.getDefault().post(new StartBrotherEvent(ServiceFragment.newInstance()));
                //EventBus.getDefault().post(new MainEvent(1));
                break;
            case R.id.homeOfficial:
                postion = 0;
                homeOfficialImg.setBackgroundColor(getResources().getColor(R.color.home_method_line));
                homeCreditImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeQiPaiImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeGameAdapter = new HomeGameAdapter(R.layout.item_game_home, AvailableLotteryNew);
                homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                    @Override
                    public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                        onHomeGameItemClick(AvailableLotteryNew.get(position));
                    }
                });
                homeRecView.setAdapter(homeGameAdapter);
                break;
            case R.id.homeCredit:
                postion = 1;
                homeOfficialImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeCreditImg.setBackgroundColor(getResources().getColor(R.color.home_method_line));
                homeQiPaiImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeGameAdapter = new HomeGameAdapter(R.layout.item_game_home, XinYongLotteriesNew);
                homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                    @Override
                    public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                        onHomeGameItemClick(XinYongLotteriesNew.get(position));
                    }
                });
                homeRecView.setAdapter(homeGameAdapter);
                break;
            case R.id.homeQiPai:
                postion = 2;
                homeOfficialImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeCreditImg.setBackgroundColor(getResources().getColor(R.color.bg_app));
                homeQiPaiImg.setBackgroundColor(getResources().getColor(R.color.home_method_line));

                homeGameAdapter = new HomeGameAdapter(R.layout.item_game_home, ThirdGames);
                homeGameAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                    @Override
                    public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                        onHomeGameItemClick(ThirdGames.get(position));
                    }
                });
                homeRecView.setAdapter(homeGameAdapter);
                break;
        }
    }
}

package com.sunapp.bloc.homepage;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.util.DisplayMetrics;
import android.view.LayoutInflater;
import android.view.View;
import android.view.WindowManager;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.jude.rollviewpager.RollPagerView;
import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.Injections;
import com.sunapp.bloc.R;
import com.sunapp.bloc.base.HGBaseFragment;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.common.adapters.AutoSizeRVAdapter;
import com.sunapp.bloc.common.event.LogoutEvent;
import com.sunapp.bloc.common.http.Client;
import com.sunapp.bloc.common.http.cphttp.CPClient;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.GameShipHelper;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.widgets.CustomPopWindow;
import com.sunapp.bloc.common.widgets.MarqueeTextView;
import com.sunapp.bloc.common.widgets.RoundCornerImageView;
import com.sunapp.bloc.data.AGCheckAcountResult;
import com.sunapp.bloc.data.AGGameLoginResult;
import com.sunapp.bloc.data.BannerResult;
import com.sunapp.bloc.data.CPResult;
import com.sunapp.bloc.data.CheckAgLiveResult;
import com.sunapp.bloc.data.DomainUrl;
import com.sunapp.bloc.data.LoginResult;
import com.sunapp.bloc.data.MaintainResult;
import com.sunapp.bloc.data.NoticeResult;
import com.sunapp.bloc.data.OnlineServiceResult;
import com.sunapp.bloc.data.QipaiResult;
import com.sunapp.bloc.data.ValidResult;
import com.sunapp.bloc.homepage.aglist.AGListFragment;
import com.sunapp.bloc.homepage.aglist.playgame.XPlayGameActivity;
import com.sunapp.bloc.homepage.cplist.CPListFragment;
import com.sunapp.bloc.homepage.events.EventShowDialog;
import com.sunapp.bloc.homepage.events.EventsFragment;
import com.sunapp.bloc.homepage.handicap.HandicapFragment;
import com.sunapp.bloc.homepage.handicap.ShowMainEvent;
import com.sunapp.bloc.homepage.noticelist.NoticeListFragment;
import com.sunapp.bloc.homepage.online.ContractFragment;
import com.sunapp.bloc.homepage.online.OnlineFragment;
import com.sunapp.bloc.login.fastlogin.LoginFragment;
import com.sunapp.bloc.personpage.accountcenter.AccountCenterFragment;
import com.sunapp.bloc.personpage.balancetransfer.BalanceTransferFragment;
import com.sunapp.bloc.withdrawPage.WithdrawFragment;
import com.sunapp.common.util.Check;
import com.sunapp.common.util.GameLog;
import com.sunapp.common.util.NetworkUtils;
import com.sunapp.common.util.Utils;
import com.tencent.smtt.export.external.interfaces.JsPromptResult;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.sdk.CookieManager;
import com.tencent.smtt.sdk.CookieSyncManager;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebSettings;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.jessyan.retrofiturlmanager.RetrofitUrlManager;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

/**
 * AG真人，老虎机，皇冠体育，彩票，优惠活动，联系我们，公告，代理加盟，新手教学（暂时先不做）
 * <p>
 * AG真人，老虎机，皇冠体育，彩票，优惠活动，联系我们，公告，代理加盟，新手教学
 */
public class HomepageFragment extends HGBaseFragment implements HomePageContract.View {

    @BindView(R.id.ivHomePageLogin)
    ImageView ivHomePageLogin;
    @BindView(R.id.tvHomePageLine)
    TextView tvHomePageLine;
    @BindView(R.id.tvHomePageLogin)
    TextView tvHomePageLogin;
    @BindView(R.id.tvHomePageUserMoney)
    TextView tvHomePageUserMoney;
    @BindView(R.id.homeUserName)
    TextView homeUserName;
    @BindView(R.id.homeMoney)
    TextView homeMoney;
    @BindView(R.id.rollpageview)
    RollPagerView rollpageview;
    @BindView(R.id.tv_homapage_bulletin)
    MarqueeTextView tvHomapageBulletin;
    /*@BindView(R.id.rv_homepage_game_hall)
    RecyclerView rvHomapageGameHall;*/
    @BindView(R.id.homeLeft1)
    LinearLayout homeLeft1;
    @BindView(R.id.homeLeftIV1)
    ImageView homeLeftIV1;
    @BindView(R.id.homeLeftTV1)
    TextView homeLeftTV1;
    @BindView(R.id.homeLeft2)
    LinearLayout homeLeft2;
    @BindView(R.id.homeLeftIV2)
    ImageView homeLeftIV2;
    @BindView(R.id.homeLeftTV2)
    TextView homeLeftTV2;
    @BindView(R.id.homeLeft3)
    LinearLayout homeLeft3;
    @BindView(R.id.homeLeftIV3)
    ImageView homeLeftIV3;
    @BindView(R.id.homeLeftTV3)
    TextView homeLeftTV3;
    @BindView(R.id.homeLeft4)
    LinearLayout homeLeft4;
    @BindView(R.id.homeLeftIV4)
    ImageView homeLeftIV4;
    @BindView(R.id.homeLeftTV4)
    TextView homeLeftTV4;
    @BindView(R.id.homeLeft5)
    LinearLayout homeLeft5;
    @BindView(R.id.homeLeftIV5)
    ImageView homeLeftIV5;
    @BindView(R.id.homeLeftTV5)
    TextView homeLeftTV5;
    @BindView(R.id.homeLeft6)
    LinearLayout homeLeft6;
    @BindView(R.id.homeLeftIV6)
    ImageView homeLeftIV6;
    @BindView(R.id.homeLeftTV6)
    TextView homeLeftTV6;
    @BindView(R.id.homeLeft7)
    LinearLayout homeLeft7;
    @BindView(R.id.homeLeftIV7)
    ImageView homeLeftIV7;
    @BindView(R.id.homeLeftTV7)
    TextView homeLeftTV7;

    private static List<HomePageIcon> homeGameList = new ArrayList<HomePageIcon>();
    private static List<HomePageIcon> homeGameNewList = new ArrayList<HomePageIcon>();

    HomePageContract.Presenter presenter;
    @BindView(R.id.homeDeposite1)
    TextView homeDeposite1;
    @BindView(R.id.homeDeposite2)
    TextView homeDeposite2;
    @BindView(R.id.homeDeposite3)
    TextView homeDeposite3;
    @BindView(R.id.homeItem15_1)
    ImageView homeItem151;
    @BindView(R.id.homeItem15_2)
    ImageView homeItem152;
    @BindView(R.id.homeItem15_3)
    ImageView homeItem153;
    @BindView(R.id.homeItem15_4)
    ImageView homeItem154;
    @BindView(R.id.homeItem15)
    LinearLayout homeItem15;
    @BindView(R.id.homeItem16_1)
    ImageView homeItem161;
    @BindView(R.id.homeItem16_2)
    ImageView homeItem162;
    @BindView(R.id.homeItem16)
    LinearLayout homeItem16;
    @BindView(R.id.homeItem17_1)
    ImageView homeItem171;
    @BindView(R.id.homeItem17)
    LinearLayout homeItem17;
    @BindView(R.id.homeItem18_33)
    LinearLayout homeItem1833;
    @BindView(R.id.homeItem18_1)
    ImageView homeItem181;
    @BindView(R.id.homeItem18_2)
    ImageView homeItem182;
    @BindView(R.id.homeItem18_3)
    ImageView homeItem183;
    @BindView(R.id.homeItem18_4)
    ImageView homeItem184;
    @BindView(R.id.homeItem18_5)
    ImageView homeItem185;
    @BindView(R.id.homeItem18)
    LinearLayout homeItem18;
    @BindView(R.id.homeItem19_1)
    ImageView homeItem191;
    @BindView(R.id.homeItem19_2)
    ImageView homeItem192;
    @BindView(R.id.homeItem19_3)
    ImageView homeItem193;
    @BindView(R.id.homeItem19_4)
    ImageView homeItem194;
    @BindView(R.id.homeItem19)
    LinearLayout homeItem19;
    @BindView(R.id.homeItem20_1)
    ImageView homeItem201;
    @BindView(R.id.homeItem20)
    LinearLayout homeItem20;
    @BindView(R.id.homeItem21_1)
    ImageView homeItem211;
    @BindView(R.id.homeItem21)
    LinearLayout homeItem21;

    private RollPagerViewManager rollPagerViewManager;

    private NoticeResult noticeResultList;
    private CPResult cpResult;
    private int isLottery = 2;//2为官方，1位信用
    private CustomPopWindow mCustomPopWindowIn;
    private String userName = "";
    private String playName = "";
    private String pro = "";
    private String userMoney = "";
    private int itemClick = R.mipmap.home_item_click, itemNorm = R.mipmap.home_item_nor;
    private String userState = "19";
    private int height;
    //private CheckUpgradeResult checkUpgradeResult;
    static {
        /*homeGameList.add(new HomePageIcon("体育投注", R.mipmap.home_hgty, 0));
        homeGameList.add(new HomePageIcon("真人视讯", R.mipmap.home_ag, 1));
        homeGameList.add(new HomePageIcon("彩票游戏", R.mipmap.home_vrcp, 2));
        homeGameList.add(new HomePageIcon("开元棋牌", R.mipmap.home_qipai, 4));
        homeGameList.add(new HomePageIcon("乐游棋牌", R.mipmap.home_ly, 13));
        homeGameList.add(new HomePageIcon("皇冠棋牌", R.mipmap.home_hg_qipai, 3));
        homeGameList.add(new HomePageIcon("VG棋牌", R.mipmap.home_vg, 5));
        homeGameList.add(new HomePageIcon("电子游艺", R.mipmap.home_lhj, 6));
        homeGameList.add(new HomePageIcon("电子竞技", R.mipmap.home_avia, 14));
        homeGameList.add(new HomePageIcon("幸运红包", R.mipmap.home_red, 8));
        homeGameList.add(new HomePageIcon("代理加盟", R.mipmap.home_agent, 7));
        homeGameList.add(new HomePageIcon("优惠活动", R.mipmap.home_pro, 9));
        homeGameList.add(new HomePageIcon("联系我们", R.mipmap.home_contact, 10));
        homeGameList.add(new HomePageIcon("新手教学", R.mipmap.home_new, 11));
        homeGameList.add(new HomePageIcon("皇冠公告", R.mipmap.home_remind, 12));*/
//        homeGameList.add(new HomePageIcon("电脑版",R.mipmap.home_pc));
//        homeGameList.add(new HomePageIcon("APP下载区",R.mipmap.home_download));
//        homeGameList.add(new HomePageIcon("线路导航",R.mipmap.home_wifi));
        //新游戏列表
       /* homeGameNewList.add(new HomePageIcon("真人视讯", R.mipmap.home_item_1, R.mipmap.home_item_11, 15, true));
        homeGameNewList.add(new HomePageIcon("彩票游戏", R.mipmap.home_item_2, R.mipmap.home_item_22, 16, false));
        homeGameNewList.add(new HomePageIcon("体育竞技", R.mipmap.home_item_3, R.mipmap.home_item_33, 17, false));
        homeGameNewList.add(new HomePageIcon("电子游戏", R.mipmap.home_item_4, R.mipmap.home_item_44, 18, false));
        homeGameNewList.add(new HomePageIcon("棋牌游戏", R.mipmap.home_item_5, R.mipmap.home_item_55, 19, false));
        homeGameNewList.add(new HomePageIcon("电子竞技", R.mipmap.home_item_6, R.mipmap.home_item_66, 20, false));
        homeGameNewList.add(new HomePageIcon("捕鱼游戏", R.mipmap.home_item_7, R.mipmap.home_item_77, 21, false));
*/
    }

    public static HomepageFragment newInstance() {
        HomepageFragment fragment = new HomepageFragment();
        Bundle args = new Bundle();

        fragment.setArguments(args);
        //注入控制器
        Injections.inject(null, (HomePageContract.View) fragment);
        return fragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_home;
    }

    private void initPX(Context context){
        WindowManager mWindowManager  = (WindowManager) context.getSystemService(Context.WINDOW_SERVICE);
        DisplayMetrics metrics = new DisplayMetrics();
        mWindowManager.getDefaultDisplay().getMetrics(metrics);
        //int width = metrics.widthPixels;//获取到的是px，像素，绝对像素，需要转化为dpi
        height = metrics.heightPixels;
        int heightParam = 0;
        if(height>=2100){
            heightParam = 428;
        }else if(height>=2000){
            heightParam = 300;
        }else if(height>=1920){
            heightParam = 252;
        }else {
            heightParam = 195;
        }
        LinearLayout.LayoutParams p = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT,heightParam);
        p.setMargins(0,0,0,15);
        homeItem151.setLayoutParams(p);
        homeItem152.setLayoutParams(p);
        homeItem153.setLayoutParams(p);
        homeItem154.setLayoutParams(p);
        homeItem181.setLayoutParams(p);
        homeItem182.setLayoutParams(p);
        homeItem1833.setLayoutParams(p);
        homeItem185.setLayoutParams(p);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        initPX(Utils.getContext());
        // EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
        DomainUrl domainUrl = JSON.parseObject(ACache.get(getContext()).getAsString("homeLineChoice"), DomainUrl.class);
        if (!Check.isNull(domainUrl) && domainUrl.getList().size() > 0) {
            int sizeq = domainUrl.getList().size();
            for (int k = 0; k < sizeq; ++k) {
                if (domainUrl.getList().get(k).isChecked()) {
                    tvHomePageLine.setText("线路" + domainUrl.getList().get(k).getPid());
                }
            }
        }
        /*LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        rvHomapageGameHall.setLayoutManager(gridLayoutManager);
        rvHomapageGameHall.setHasFixedSize(true);
        rvHomapageGameHall.setNestedScrollingEnabled(false);
        //rvHomapageGameHall.setAdapter(new HomaPageGameAdapter(getContext(),R.layout.item_game_hall,homeGameList));
        GameLog.log("当前的高度是："+height);
        if(height>2076){
            rvHomapageGameHall.setAdapter(new HomaPageGameNewAdapter(getContext(), R.layout.item_game_hall_new_2, homeGameNewList));
        }else {
            rvHomapageGameHall.setAdapter(new HomaPageGameNewAdapter(getContext(), R.layout.item_game_hall_new, homeGameNewList));
        }*/
        BannerResult bannerResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_HOME_BANNER), BannerResult.class);
        if (!Check.isNull(bannerResult)) {
            rollPagerViewManager = new RollPagerViewManager(rollpageview, bannerResult.getData());
            //rollPagerViewManager.testImagesLocal(null);
            rollPagerViewManager.testImagesNet(null, null);
        }
        NoticeResult noticeResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_HOME_NOTICE), NoticeResult.class);
        if (!Check.isNull(noticeResult)) {
            List<String> stringList = new ArrayList<String>();
            int size = noticeResult.getData().size();
            for (int i = 0; i < size; ++i) {
                stringList.add(noticeResult.getData().get(i).getNotice());
            }
            tvHomapageBulletin.setContentList(stringList);
        }
        if (!NetworkUtils.isConnected()) {
            GameLog.log("无网络连接，请求到的是本地缓存。。。。。");
        } else {
            //presenter.postOnlineService("");
            if (!Check.isNull(presenter)) {
                presenter.postBanner("");
                presenter.postNotice("");
            }
        }

    }

    private void setLayClick(LinearLayout linearLayout, TextView textView){
        linearLayout.setBackgroundResource(itemClick);
        textView.setTextColor(getResources().getColor(R.color.register_left));
    }
    private void setLayNorm(LinearLayout linearLayout,TextView textView){
        linearLayout.setBackgroundResource(itemNorm);
        textView.setTextColor(getResources().getColor(R.color.home_item_normal));
    }

    private void initLeftInfo(int postion) {
        switch (postion){
            case 1:
                setLayClick(homeLeft1,homeLeftTV1);
                setLayNorm(homeLeft2,homeLeftTV2);
                setLayNorm(homeLeft3,homeLeftTV3);
                setLayNorm(homeLeft4,homeLeftTV4);
                setLayNorm(homeLeft5,homeLeftTV5);
                setLayNorm(homeLeft6,homeLeftTV6);
                setLayNorm(homeLeft7,homeLeftTV7);
                homeLeftIV1.setBackground(getResources().getDrawable(R.mipmap.home_item_11));
                homeLeftIV2.setBackground(getResources().getDrawable(R.mipmap.home_item_2));
                homeLeftIV3.setBackground(getResources().getDrawable(R.mipmap.home_item_3));
                homeLeftIV4.setBackground(getResources().getDrawable(R.mipmap.home_item_4));
                homeLeftIV5.setBackground(getResources().getDrawable(R.mipmap.home_item_5));
                homeLeftIV6.setBackground(getResources().getDrawable(R.mipmap.home_item_6));
                homeLeftIV7.setBackground(getResources().getDrawable(R.mipmap.home_item_7));
                break;
            case 2:
                setLayNorm(homeLeft1,homeLeftTV1);
                setLayClick(homeLeft2,homeLeftTV2);
                setLayNorm(homeLeft3,homeLeftTV3);
                setLayNorm(homeLeft4,homeLeftTV4);
                setLayNorm(homeLeft5,homeLeftTV5);
                setLayNorm(homeLeft6,homeLeftTV6);
                setLayNorm(homeLeft7,homeLeftTV7);
                homeLeftIV1.setBackground(getResources().getDrawable(R.mipmap.home_item_1));
                homeLeftIV2.setBackground(getResources().getDrawable(R.mipmap.home_item_22));
                homeLeftIV3.setBackground(getResources().getDrawable(R.mipmap.home_item_3));
                homeLeftIV4.setBackground(getResources().getDrawable(R.mipmap.home_item_4));
                homeLeftIV5.setBackground(getResources().getDrawable(R.mipmap.home_item_5));
                homeLeftIV6.setBackground(getResources().getDrawable(R.mipmap.home_item_6));
                homeLeftIV7.setBackground(getResources().getDrawable(R.mipmap.home_item_7));
                break;
            case 3:
                setLayNorm(homeLeft1,homeLeftTV1);
                setLayNorm(homeLeft2,homeLeftTV2);
                setLayClick(homeLeft3,homeLeftTV3);
                setLayNorm(homeLeft4,homeLeftTV4);
                setLayNorm(homeLeft5,homeLeftTV5);
                setLayNorm(homeLeft6,homeLeftTV6);
                setLayNorm(homeLeft7,homeLeftTV7);
                homeLeftIV1.setBackground(getResources().getDrawable(R.mipmap.home_item_1));
                homeLeftIV2.setBackground(getResources().getDrawable(R.mipmap.home_item_2));
                homeLeftIV3.setBackground(getResources().getDrawable(R.mipmap.home_item_33));
                homeLeftIV4.setBackground(getResources().getDrawable(R.mipmap.home_item_4));
                homeLeftIV5.setBackground(getResources().getDrawable(R.mipmap.home_item_5));
                homeLeftIV6.setBackground(getResources().getDrawable(R.mipmap.home_item_6));
                homeLeftIV7.setBackground(getResources().getDrawable(R.mipmap.home_item_7));
                break;
            case 4:
                setLayNorm(homeLeft1,homeLeftTV1);
                setLayNorm(homeLeft2,homeLeftTV2);
                setLayNorm(homeLeft3,homeLeftTV3);
                setLayClick(homeLeft4,homeLeftTV4);
                setLayNorm(homeLeft5,homeLeftTV5);
                setLayNorm(homeLeft6,homeLeftTV6);
                setLayNorm(homeLeft7,homeLeftTV7);
                homeLeftIV1.setBackground(getResources().getDrawable(R.mipmap.home_item_1));
                homeLeftIV2.setBackground(getResources().getDrawable(R.mipmap.home_item_2));
                homeLeftIV3.setBackground(getResources().getDrawable(R.mipmap.home_item_3));
                homeLeftIV4.setBackground(getResources().getDrawable(R.mipmap.home_item_44));
                homeLeftIV5.setBackground(getResources().getDrawable(R.mipmap.home_item_5));
                homeLeftIV6.setBackground(getResources().getDrawable(R.mipmap.home_item_6));
                homeLeftIV7.setBackground(getResources().getDrawable(R.mipmap.home_item_7));
                break;
            case 5:
                setLayNorm(homeLeft1,homeLeftTV1);
                setLayNorm(homeLeft2,homeLeftTV2);
                setLayNorm(homeLeft3,homeLeftTV3);
                setLayNorm(homeLeft4,homeLeftTV4);
                setLayClick(homeLeft5,homeLeftTV5);
                setLayNorm(homeLeft6,homeLeftTV6);
                setLayNorm(homeLeft7,homeLeftTV7);
                homeLeftIV1.setBackground(getResources().getDrawable(R.mipmap.home_item_1));
                homeLeftIV2.setBackground(getResources().getDrawable(R.mipmap.home_item_2));
                homeLeftIV3.setBackground(getResources().getDrawable(R.mipmap.home_item_3));
                homeLeftIV4.setBackground(getResources().getDrawable(R.mipmap.home_item_4));
                homeLeftIV5.setBackground(getResources().getDrawable(R.mipmap.home_item_55));
                homeLeftIV6.setBackground(getResources().getDrawable(R.mipmap.home_item_6));
                homeLeftIV7.setBackground(getResources().getDrawable(R.mipmap.home_item_7));
                break;
            case 6:
                setLayNorm(homeLeft1,homeLeftTV1);
                setLayNorm(homeLeft2,homeLeftTV2);
                setLayNorm(homeLeft3,homeLeftTV3);
                setLayNorm(homeLeft4,homeLeftTV4);
                setLayNorm(homeLeft5,homeLeftTV5);
                setLayClick(homeLeft6,homeLeftTV6);
                setLayNorm(homeLeft7,homeLeftTV7);
                homeLeftIV1.setBackground(getResources().getDrawable(R.mipmap.home_item_1));
                homeLeftIV2.setBackground(getResources().getDrawable(R.mipmap.home_item_2));
                homeLeftIV3.setBackground(getResources().getDrawable(R.mipmap.home_item_3));
                homeLeftIV4.setBackground(getResources().getDrawable(R.mipmap.home_item_4));
                homeLeftIV5.setBackground(getResources().getDrawable(R.mipmap.home_item_5));
                homeLeftIV6.setBackground(getResources().getDrawable(R.mipmap.home_item_66));
                homeLeftIV7.setBackground(getResources().getDrawable(R.mipmap.home_item_7));
                break;
            case 7:
                setLayNorm(homeLeft1,homeLeftTV1);
                setLayNorm(homeLeft2,homeLeftTV2);
                setLayNorm(homeLeft3,homeLeftTV3);
                setLayNorm(homeLeft4,homeLeftTV4);
                setLayNorm(homeLeft5,homeLeftTV5);
                setLayNorm(homeLeft6,homeLeftTV6);
                setLayClick(homeLeft7,homeLeftTV7);
                homeLeftIV1.setBackground(getResources().getDrawable(R.mipmap.home_item_1));
                homeLeftIV2.setBackground(getResources().getDrawable(R.mipmap.home_item_2));
                homeLeftIV3.setBackground(getResources().getDrawable(R.mipmap.home_item_3));
                homeLeftIV4.setBackground(getResources().getDrawable(R.mipmap.home_item_4));
                homeLeftIV5.setBackground(getResources().getDrawable(R.mipmap.home_item_5));
                homeLeftIV6.setBackground(getResources().getDrawable(R.mipmap.home_item_6));
                homeLeftIV7.setBackground(getResources().getDrawable(R.mipmap.home_item_77));
                break;
        }
        onHomeGameItemNewClick(14+postion);
    }


    @OnClick({R.id.homeLeft1,R.id.homeLeft2,R.id.homeLeft3,R.id.homeLeft4,R.id.homeLeft5,R.id.homeLeft6,R.id.homeLeft7,R.id.tvHomePageLogin, R.id.tvHomePageLine,R.id.homeUserCenter,R.id.homeDeposite1,R.id.homeDeposite2,R.id.homeDeposite3,R.id.homeItem15_1, R.id.homeItem15_2,R.id.homeItem15_3, R.id.homeItem15_4, R.id.homeItem15, R.id.homeItem16_1, R.id.homeItem16_2, R.id.homeItem16, R.id.homeItem17_1, R.id.homeItem17, R.id.homeItem18_1, R.id.homeItem18_2,R.id.homeItem18_3, R.id.homeItem18_4,R.id.homeItem18_5, R.id.homeItem18, R.id.homeItem19_1, R.id.homeItem19_2, R.id.homeItem19_3, R.id.homeItem19_4, R.id.homeItem19, R.id.homeItem20_1, R.id.homeItem20, R.id.homeItem21_1, R.id.homeItem21})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.homeLeft1:
                initLeftInfo(1);
                break;
            case R.id.homeLeft2:
                initLeftInfo(2);
                break;
            case R.id.homeLeft3:
                initLeftInfo(3);
                break;
            case R.id.homeLeft4:
                initLeftInfo(4);
                break;
            case R.id.homeLeft5:
                initLeftInfo(5);
                break;
            case R.id.homeLeft6:
                initLeftInfo(6);
                break;
            case R.id.homeLeft7:
                initLeftInfo(7);
                break;
            case R.id.tvHomePageLogin:
                //start(LoginFragment.newInstance());  启动一个新的Fragment 但是还是覆盖在以前的Fragemnet的基础上
                EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                break;
            case R.id.tvHomePageLine:
                showPopMenuIn();
                break;
            case R.id.homeUserCenter:
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                EventBus.getDefault().post(new StartBrotherEvent(AccountCenterFragment.newInstance(userMoney)));
                break;
            case R.id.homeDeposite1:
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                EventBus.getDefault().post(new ShowMainEvent(1));
                break;
            case R.id.homeDeposite2:
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                EventBus.getDefault().post(new StartBrotherEvent(BalanceTransferFragment.newInstance(userMoney), SupportFragment.SINGLETASK));
                break;
            case R.id.homeDeposite3:
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                EventBus.getDefault().post(new StartBrotherEvent(WithdrawFragment.newInstance(userMoney,""), SupportFragment.SINGLETASK));
                break;
            case R.id.homeItem15_1://AG旗舰厅
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                playName = "AG旗舰厅";
                userState = "1";
                String video_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_VIDEO_MAINTAIN);
                if ("1".equals(video_url)) {
                    presenter.postMaintain();
                } else {
                    presenter.postBYGame("","");
                }
                break;
            case R.id.homeItem15_2://OG视讯
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "9";
                playName = "AG国际厅";
                presenter.postOGGame("","");
                /*String video_url2 = ACache.get(getContext()).getAsString(HGConstant.USERNAME_VIDEO_MAINTAIN);
                if ("1".equals(video_url2)) {
                    presenter.postMaintain();
                } else {
                    presenter.postBYGame("","");
                }*/
                break;
            case R.id.homeItem15_3:
            case R.id.homeItem15_4:
                showMessage("敬请期待！");
                break;
            case R.id.homeItem15:
                break;
            case R.id.homeItem16_1://信用彩票
                isLottery = 1;
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "2";
                String video_urlcp = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOTTERY_MAINTAIN);
                if ("1".equals(video_urlcp)) {
                    presenter.postMaintain();
                    return;
                }
                if(Check.isNull(cpResult)){
                    presenter.postCP();
                    showMessage("正在加载中，请稍后再试!");
                    return;
                }
                goCpView();
                /*String cp_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_URL);
                String cp_inform = ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_INFORM);
                String cp_token = ACache.get(getContext()).getAsString(HGConstant.APP_CP_COOKIE);
                if (Check.isEmpty(cp_url) || Check.isEmpty(cp_inform) || Check.isEmpty(cp_token) || Check.isNull(CPClient.getRetrofit())) {
                } else {
                    this.startActivity(new Intent(getContext(), CPListFragment.class));
                }*/

                break;
            case R.id.homeItem16_2://官方彩票
                isLottery = 2;
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "2";
                String video_urlcps = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOTTERY_MAINTAIN);
                if ("1".equals(video_urlcps)) {
                    presenter.postMaintain();
                    return;
                }
                if(Check.isNull(cpResult)){
                    presenter.postCP();
                    showMessage("正在加载中，请稍后再试!");
                    return;
                }
                //showMessage("敬请期待！！！");
                goCpView();
                break;
            case R.id.homeItem16:
                break;
            case R.id.homeItem17_1://体育竞技
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "0";
                String sport_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_SPORT_MAINTAIN);
                if ("1".equals(sport_url)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(HandicapFragment.newInstance(userName, userMoney), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeItem17:
                break;
            case R.id.homeItem18_1://AG电子
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "5";
                String game_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_GAME_MAINTAIN);
                if ("1".equals(game_url)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(userName, userMoney, "game")), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeItem18_2://MG电子
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "5";
                String game_url2 = ACache.get(getContext()).getAsString("username_dd_maintain_mg");
                if ("1".equals(game_url2)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(userName, userMoney, "MGgame")), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeItem18_3://CQ电子
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "10";
                String game_url3 = ACache.get(getContext()).getAsString("username_dd_maintain_cq");
                if ("1".equals(game_url3)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(userName, userMoney, "cq")), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeItem18_4://MW电子
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "11";
                String game_url4 = ACache.get(getContext()).getAsString("username_dd_maintain_mw");
                if ("1".equals(game_url4)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(userName, userMoney, "mw")), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.homeItem18_5:
                showMessage("敬请期待！");
                break;
            case R.id.homeItem18:
                break;
            case R.id.homeItem19_1://VG棋牌
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "6";
                String game_url1 = ACache.get(getContext()).getAsString(HGConstant.USERNAME_GAME_MAINTAIN);
                if ("1".equals(game_url1)) {
                    presenter.postMaintain();
                } else {
                    postVGQiPaiGo();
                }
                break;
            case R.id.homeItem19_2://乐游棋牌
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                /*if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                userState = "7";
                String ly_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LY_MAINTAIN);
                if ("1".equals(ly_url)) {
                    presenter.postMaintain();
                } else {
                    postLYQiPaiGo();
                }
                break;
            case R.id.homeItem19_3://皇冠棋牌
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "3";
                String qp_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_HG_MAINTAIN);
                if ("1".equals(qp_url)) {
                    presenter.postMaintain();
                } else {
                    postHGQiPaiGo();
                }
                break;
            case R.id.homeItem19_4://开元棋牌
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                userState = "4";
                String hg_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_KY_MAINTAIN);
                if ("1".equals(hg_url)) {
                    presenter.postMaintain();
                } else {
                    postQiPaiGo();
                }
                break;
            case R.id.homeItem19:
                break;
            case R.id.homeItem20_1://电子竞技
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "8";
                String avia_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_AVIA_MAINTAIN);
                if ("1".equals(avia_url)) {
                    presenter.postMaintain();
                } else {
                    postAviaQiPaiGo();
                }
                break;
            case R.id.homeItem20:
                break;
            case R.id.homeItem21_1://捕鱼游戏
                if (Check.isEmpty(userName)) {
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                playName = "捕鱼游戏";
                presenter.postBYGame("","6");
                break;
            case R.id.homeItem21:
                break;
        }
    }

    class HomaPageGameNewAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public HomaPageGameNewAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setBackgroundRes(R.id.iv_item_game_icon, data.getIconId());
            if (data.isClick()) {
                holder.setTextColor(R.id.tv_item_game_name, getResources().getColor(R.color.register_left));
                holder.setImageResource(R.id.iv_item_game_icon, data.getClickId());
                holder.setBackgroundRes(R.id.ll_home_main_show, itemClick);
            } else {
                holder.setTextColor(R.id.tv_item_game_name, getResources().getColor(R.color.home_item_normal));
                holder.setImageResource(R.id.iv_item_game_icon, data.getIconId());
                holder.setBackgroundRes(R.id.ll_home_main_show, itemNorm);
            }
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if (!NetworkUtils.isConnected()) {
                        showMessage("请检查您的网络！");
                        return;
                    }
                    for (int k = 0; k < homeGameNewList.size(); ++k) {
                        homeGameNewList.get(k).setClick(false);
                    }
                    homeGameNewList.get(position).setClick(true);
                    notifyDataSetChanged();
                    onHomeGameItemNewClick(data.getId());
                }
            });
        }
    }

    class HomaPageGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public HomaPageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            RoundCornerImageView roundCornerImageView = (RoundCornerImageView) holder.getView(R.id.iv_item_game_icon);
            roundCornerImageView.onCornerAll(roundCornerImageView);
            holder.setBackgroundRes(R.id.iv_item_game_icon, data.getIconId());
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if (!NetworkUtils.isConnected()) {
                        showMessage("请检查您的网络！");
                        return;
                    }
                    onHomeGameItemClick(data.getId());
                }
            });
        }
    }

    private void onHomeGameItemNewClick(int position) {

        switch (position) {
            case 15:
                homeItem15.setVisibility(View.VISIBLE);
                homeItem16.setVisibility(View.GONE);
                homeItem17.setVisibility(View.GONE);
                homeItem18.setVisibility(View.GONE);
                homeItem19.setVisibility(View.GONE);
                homeItem20.setVisibility(View.GONE);
                homeItem21.setVisibility(View.GONE);
                break;
            case 16:
                homeItem15.setVisibility(View.GONE);
                homeItem16.setVisibility(View.VISIBLE);
                homeItem17.setVisibility(View.GONE);
                homeItem18.setVisibility(View.GONE);
                homeItem19.setVisibility(View.GONE);
                homeItem20.setVisibility(View.GONE);
                homeItem21.setVisibility(View.GONE);
                break;
            case 17:
                homeItem15.setVisibility(View.GONE);
                homeItem16.setVisibility(View.GONE);
                homeItem17.setVisibility(View.VISIBLE);
                homeItem18.setVisibility(View.GONE);
                homeItem19.setVisibility(View.GONE);
                homeItem20.setVisibility(View.GONE);
                homeItem21.setVisibility(View.GONE);
                break;
            case 18:
                homeItem15.setVisibility(View.GONE);
                homeItem16.setVisibility(View.GONE);
                homeItem17.setVisibility(View.GONE);
                homeItem18.setVisibility(View.VISIBLE);
                homeItem19.setVisibility(View.GONE);
                homeItem20.setVisibility(View.GONE);
                homeItem21.setVisibility(View.GONE);
                break;
            case 19:
                homeItem15.setVisibility(View.GONE);
                homeItem16.setVisibility(View.GONE);
                homeItem17.setVisibility(View.GONE);
                homeItem18.setVisibility(View.GONE);
                homeItem19.setVisibility(View.VISIBLE);
                homeItem20.setVisibility(View.GONE);
                homeItem21.setVisibility(View.GONE);
                break;
            case 20:
                homeItem15.setVisibility(View.GONE);
                homeItem16.setVisibility(View.GONE);
                homeItem17.setVisibility(View.GONE);
                homeItem18.setVisibility(View.GONE);
                homeItem19.setVisibility(View.GONE);
                homeItem20.setVisibility(View.VISIBLE);
                homeItem21.setVisibility(View.GONE);
                break;
            case 21:
                homeItem15.setVisibility(View.GONE);
                homeItem16.setVisibility(View.GONE);
                homeItem17.setVisibility(View.GONE);
                homeItem18.setVisibility(View.GONE);
                homeItem19.setVisibility(View.GONE);
                homeItem20.setVisibility(View.GONE);
                homeItem21.setVisibility(View.VISIBLE);
                break;
        }

    }

    private void onHomeGameItemClick(int position) {

        //showMessage("点击的位置是："+position);
        /*if(Check.isNull(checkUpgradeResult)){
            return;
        }*/
        if (position < 7 && Check.isEmpty(userName)) {
            //start(LoginFragment.newInstance());
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
            return;
        }
        switch (position) {
            case 0:
                userState = "0";
                String sport_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_SPORT_MAINTAIN);
                if ("1".equals(sport_url)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(HandicapFragment.newInstance(userName, userMoney), SupportFragment.SINGLETASK));
                }
                break;
            case 1:
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "1";
                String video_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_VIDEO_MAINTAIN);
                if ("1".equals(video_url)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(userName, userMoney, "live")), SupportFragment.SINGLETASK));
                }
                break;
            case 2:
                userState = "2";
                String cp_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_URL);
                String cp_inform = ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_INFORM);
                String cp_token = ACache.get(getContext()).getAsString(HGConstant.APP_CP_COOKIE);
                if (Check.isEmpty(cp_url) || Check.isEmpty(cp_inform) || Check.isEmpty(cp_token) || Check.isNull(CPClient.getRetrofit())) {
                    presenter.postCP();
                    showMessage("正在加载中，请稍后再试!");
                } else {
                    this.startActivity(new Intent(getContext(), CPListFragment.class));
                }

                //EventBus.getDefault().post(new StartBrotherEvent(CPListFragment.newInstance(Arrays.asList(userName,userMoney,"live")), SupportFragment.SINGLETASK));
                /*String cp_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOTTERY_MAINTAIN);
                if("1".equals(cp_url)){
                    presenter.postMaintain();
                }else {
                    postCPGo();
                }*/
                break;
            case 3:
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "3";
                String qp_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_HG_MAINTAIN);
                if ("1".equals(qp_url)) {
                    presenter.postMaintain();
                } else {
                    postHGQiPaiGo();
                }
                break;
            case 4:
                /*if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                userState = "4";
                String hg_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_KY_MAINTAIN);
                if ("1".equals(hg_url)) {
                    presenter.postMaintain();
                } else {
                    postQiPaiGo();
                }
                break;
            case 5:
                /*if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                userState = "6";
                String vg_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_VG_MAINTAIN);
                if ("1".equals(vg_url)) {
                    presenter.postMaintain();
                } else {
                    postVGQiPaiGo();
                }
                break;
            case 6:
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "5";
                String game_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_GAME_MAINTAIN);
                if ("1".equals(game_url)) {
                    presenter.postMaintain();
                } else {
                    EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(userName, userMoney, "game")), SupportFragment.SINGLETASK));
                }
                break;
            case 9:
                //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney,checkUpgradeResult.getDiscount_activity())));
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, Client.baseUrl() + "template/promo.php?tip=app" + pro)));
                break;
            case 7:
                //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney,checkUpgradeResult.getNewcomer_guide())));
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, Client.baseUrl() + "agents_reg.php?tip=app")));
                break;
            case 8:
                if (Check.isEmpty(userName)) {
                    //start(LoginFragment.newInstance());
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                postValidGiftGo();
                //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, Client.baseUrl()+"/template/help.php?tip=app")));
                break;
            case 10:
                //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney,checkUpgradeResult.getBusiness_agent())));
                EventBus.getDefault().post(new StartBrotherEvent(ContractFragment.newInstance(userMoney,
                        ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_QQ),
                        ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_WECHAT))));
                break;
            case 11:
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, Client.baseUrl() + "/template/help.php?tip=app")));
                //presenter.postNoticeList("");
                break;
            case 12:
                presenter.postNoticeList("");
                break;
            case 13:
                if (Check.isEmpty(userName)) {
                    //start(LoginFragment.newInstance());
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                /*if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                userState = "7";
                String ly_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LY_MAINTAIN);
                if ("1".equals(ly_url)) {
                    presenter.postMaintain();
                } else {
                    postLYQiPaiGo();
                }
                break;
            case 14:
                if (Check.isEmpty(userName)) {
                    //start(LoginFragment.newInstance());
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                userState = "8";
                String avia_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_AVIA_MAINTAIN);
                if ("1".equals(avia_url)) {
                    presenter.postMaintain();
                } else {
                    postAviaQiPaiGo();
                }
                break;
            /*case 13:
                if(Check.isEmpty(userName)){
                    //start(LoginFragment.newInstance());
                    EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
                    return;
                }
                if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }
                EventBus.getDefault().post(new StartBrotherEvent(NewEventsFragment.newInstance(userMoney,0), SupportFragment.SINGLETASK));
                break;*/
        }
    }

    @Override
    public void onVisible() {
        super.onVisible();
        // EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));

    }



    private void showPopMenuIn() {
        View contentView = LayoutInflater.from(getContext()).inflate(R.layout.pop_line_choice, null);
        //处理popWindow 显示内容
        DomainUrl domainUrl = JSON.parseObject(ACache.get(getContext()).getAsString("homeLineChoice"), DomainUrl.class);
        if (Check.isNull(domainUrl)) {
            showMessage("已是最优线路！");
            return;
        }
        RecyclerView recyclerView = contentView.findViewById(R.id.popLineChoice);
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        recyclerView.setLayoutManager(gridLayoutManager);
        recyclerView.setHasFixedSize(true);
        recyclerView.setNestedScrollingEnabled(false);
        recyclerView.setAdapter(new LineChoiceAdapter(getContext(), R.layout.pop_line_choice_item, domainUrl.getList()));
        mCustomPopWindowIn = new CustomPopWindow.PopupWindowBuilder(getContext())
                .setView(contentView)
                .enableBackgroundDark(true)
                .create()
                .showAsDropDown(tvHomePageLine, 0, 0);
        //}
    }


    class LineChoiceAdapter extends AutoSizeRVAdapter<DomainUrl.ListBean> {
        private Context context;

        public LineChoiceAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final DomainUrl.ListBean data, final int position) {
            holder.setText(R.id.popLineName, "线路" + data.getPid());
            if (data.isChecked()) {
                holder.setBackgroundRes(R.id.popLineImg, R.mipmap.line_choice_cheack1);
            } else {
                holder.setBackgroundRes(R.id.popLineImg, R.mipmap.line_choice_cheack2);
            }
            holder.setOnClickListener(R.id.popLineName, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if (!NetworkUtils.isConnected()) {
                        showMessage("请检查您的网络！");
                        return;
                    }
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


    @Override
    public void postOnlineServiceResult(OnlineServiceResult onlineServiceResult) {

        GameLog.log("在线客服地址：" + onlineServiceResult.getOnlineserver());
        // onStartOnlineService(onlineServiceResult.getOnlineserver());
        ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL, onlineServiceResult.getOnlineserver());
    }

    @Override
    public void postBannerResult(BannerResult bannerResult) {
        GameLog.log("。。。。。Banner的数据返回。。。。。");
        ACache.get(getContext()).put(HGConstant.USERNAME_HOME_BANNER, JSON.toJSONString(bannerResult));
        rollPagerViewManager = new RollPagerViewManager(rollpageview, bannerResult.getData());
        //rollPagerViewManager.testImagesLocal(null);
        rollPagerViewManager.testImagesNet(null, null);
    }

    @Override
    public void postNoticeResult(NoticeResult noticeResult) {
        GameLog.log("。。。。。公告的数据返回。。。。。");
        ACache.get(getContext()).put(HGConstant.USERNAME_HOME_NOTICE, JSON.toJSONString(noticeResult));
        List<String> stringList = new ArrayList<String>();
        int size = noticeResult.getData().size();
        for (int i = 0; i < size; ++i) {
            stringList.add(noticeResult.getData().get(i).getNotice());
        }
        tvHomapageBulletin.setContentList(stringList);
    }

    @Override
    public void postNoticeListResult(NoticeResult noticeResult) {

        noticeResultList = noticeResult;
        EventBus.getDefault().post(new StartBrotherEvent(NoticeListFragment.newInstance(noticeResultList, "", "")));
    }

    @Override
    public void postAGLiveCheckRegisterResult(CheckAgLiveResult checkAgLiveResult) {
        GameLog.log("AG视讯是否已经注册：" + checkAgLiveResult.getIs_registered());
        if (!"1".equals(checkAgLiveResult.getIs_registered())) {
            presenter.postAGGameRegisterAccount("", "cga");
            GameLog.log("开始注册 AG视讯账号");
        }
    }


    @Override
    public void postAGGameRegisterAccountResult(AGCheckAcountResult agCheckAcountResult) {
        GameLog.log("AG创建账号：" + agCheckAcountResult.toString());
    }

    private void initWebView(String url) {
        final WebView mWebView = new WebView(getContext());
        mWebView.setWebViewClient(new WebViewClient() {

            @Override
            public void onLoadResource(WebView view, String url) {
                super.onLoadResource(view, url);
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView webview, String url) {
                webview.loadUrl(url);
                return true;
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {

                //rl_loading.setVisibility(View.VISIBLE); // 显示加载界面
            }

            @Override
            public void onPageFinished(WebView view, String url) {

                GameLog.log("请求的URL地址 " + url);
                String title = view.getTitle();
                CookieSyncManager.createInstance(getContext());
                CookieManager cookieManager = CookieManager.getInstance();
                if (cookieManager != null) {
                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                        cookieManager.setAcceptThirdPartyCookies(mWebView, true);
                    }
                }
                String CookieStr = cookieManager.getCookie(url);
                if (!Check.isEmpty(CookieStr))
                    ACache.get(getContext()).put(HGConstant.APP_CP_COOKIE, CookieStr);
                GameLog.log("cookie日志：" + CookieStr);
                /*tv_topbar_title.setText(title);
                tv_topbar_title.setVisibility(View.VISIBLE);
                rl_loading.setVisibility(View.GONE); // 隐藏加载界面*/
            }

            @Override
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                //rl_loading.setVisibility(View.GONE); // 隐藏加载界面
            }
        });

        mWebView.setWebChromeClient(new WebChromeClient() {

            @Override
            public boolean onJsAlert(WebView view, String url, String message, JsResult result) {
                result.confirm();
                return true;
            }

            @Override
            public boolean onJsConfirm(WebView view, String url, String message, JsResult result) {
                return super.onJsConfirm(view, url, message, result);
            }

            @Override
            public boolean onJsPrompt(WebView view, String url, String message, String defaultValue, JsPromptResult result) {
                return super.onJsPrompt(view, url, message, defaultValue, result);
            }
        });

        mWebView.loadUrl(url);
        mWebView.getSettings().setJavaScriptEnabled(true);
        mWebView.getSettings().setRenderPriority(WebSettings.RenderPriority.HIGH);
        mWebView.getSettings().setCacheMode(WebSettings.LOAD_DEFAULT);  //设置 缓存模式
        // 开启 DOM storage API 功能
        mWebView.getSettings().setDomStorageEnabled(true);
        //开启 database storage API 功能
        mWebView.getSettings().setDatabaseEnabled(true);
//        String cacheDirPath = getContext().getFilesDir().getAbsolutePath()+APP_CACAHE_DIRNAME;
        String cacheDirPath = getContext().getCacheDir().getAbsolutePath() + HGConstant.APP_DB_DIRNAME;
        GameLog.log("cacheDirPath=" + cacheDirPath);
        //设置数据库缓存路径
        mWebView.getSettings().setDatabasePath(cacheDirPath);
        //设置  Application Caches 缓存目录
        mWebView.getSettings().setAppCachePath(cacheDirPath);
        //开启 Application Caches 功能
        mWebView.getSettings().setAppCacheEnabled(true);

        mWebView.getSettings().setAllowFileAccessFromFileURLs(true);
        mWebView.getSettings().setAllowUniversalAccessFromFileURLs(true);

    }

    @Override
    public void postQipaiResult(QipaiResult qipaiResult) {
        //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, qipaiResult.getUrl())));
        ACache.get(getContext()).put(HGConstant.USERNAME_QIPAI_URL, qipaiResult.getUrl());
        GameLog.log("=============ky棋牌的地址=============");
    }

    @Override
    public void postHGQipaiResult(QipaiResult qipaiResult) {
        //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, qipaiResult.getUrl())));
        ACache.get(getContext()).put(HGConstant.USERNAME_HG_QIPAI_URL, qipaiResult.getUrl());
        GameLog.log("=============皇冠棋牌的地址=============");
    }

    @Override
    public void postVGQipaiResult(QipaiResult qipaiResult) {
        //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, qipaiResult.getUrl())));
        ACache.get(getContext()).put(HGConstant.USERNAME_VG_QIPAI_URL, qipaiResult.getUrl());
        GameLog.log("=============VG棋牌的地址=============");
    }

    @Override
    public void postLYQipaiResult(QipaiResult qipaiResult) {
        ACache.get(getContext()).put(HGConstant.USERNAME_LY_QIPAI_URL, qipaiResult.getUrl());
        GameLog.log("=============LY棋牌的地址=============");
    }

    @Override
    public void postAviaQiPaiResult(QipaiResult qipaiResult) {
        ACache.get(getContext()).put(HGConstant.USERNAME_AVIA_QIPAI_URL, qipaiResult.getUrl());
        GameLog.log("=============泛亚棋牌的地址=============");
    }

    @Override
    public void postOGResult(AGGameLoginResult qipaiResult) {
        GameLog.log("OG的返回数据："+qipaiResult.getUrl());
        //EventBus.getDefault().post(new StartBrotherEvent(XPlayGameFragment.newInstance(dzTitileName,agGameLoginResult.getUrl(),"1"), SupportFragment.SINGLETASK));
        /*Intent intent = new Intent(getContext(),XPlayGameActivity.class);
        intent.putExtra("url",qipaiResult.getUrl());
        intent.putExtra("gameCnName","OG视讯");
        intent.putExtra("hidetitlebar",false);
        getActivity().startActivity(intent);*/
        Intent intent = new Intent(Intent.ACTION_VIEW);
        intent.setData(Uri.parse(qipaiResult.getUrl()));
        startActivity(intent);
        //ACache.get(getContext()).put(HGConstant.USERNAME_OG_QIPAI_URL,qipaiResult.getUrl());
        GameLog.log("=============OG的地址=============");
    }

    @Override
    public void postCPResult(CPResult cpResult) {
        GameLog.log("返回的数据结构："+cpResult.toString());
        this.cpResult = cpResult;

        //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(userMoney, cpResult.getCpUrl())));
        /*CPClient.setClientDomain(cpResult.getCpUrl());
        HGApplication.instance().configCPClient();
        ACache.get(getContext()).put("homeTYUrl", cpResult.getCpUrl().replace("mc.", "m."));
        ACache.get(getContext()).put("homeCPUrl", cpResult.getCpUrl());
        ACache.get(getContext()).put(HGConstant.USERNAME_CP_URL, cpResult.getCpUrl());//+"?tip=app"
        ACache.get(getContext()).put(HGConstant.USERNAME_CP_INFORM, cpResult.getUrlLogin());
        initWebView(cpResult.getUrlLogin());*/
    }

    private void goCpView(){
        Intent intent = new Intent(getContext(), XPlayGameActivity.class);
        String postData ="";
        try {
            postData = "params=" + URLEncoder.encode(cpResult.getParams(), "UTF-8") +
                    "&thirdLotteryId=" + URLEncoder.encode(cpResult.getThirdLotteryId(), "UTF-8")+
                    "&appRefer=" + URLEncoder.encode(HGConstant.PRODUCT_PLATFORM, "UTF-8")+
                    "&toXinyong=" + URLEncoder.encode(isLottery==1?"1":"", "UTF-8");
            if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
                intent.putExtra("type", "get");
                intent.putExtra("url", cpResult.getThird_cpUrl()+"?"+postData);
            }else{
                intent.putExtra("type", "post");
                intent.putExtra("postParam", postData);
                intent.putExtra("url", cpResult.getThird_cpUrl());
            }
            GameLog.log("请求参数： "+postData);
        } catch (Exception e) {
            e.printStackTrace();
        }
        intent.putExtra("gameCnName", "彩票");
        intent.putExtra("hidetitlebar", false);
        getActivity().startActivity(intent);
    }

    @Override
    public void postValidGiftResult(ValidResult validResult) {
        GameLog.log("=============红包的地址是否正常=============");
        //EventBus.getDefault().post(new StartBrotherEvent(EventsFragment.newInstance(null,userMoney,1)));
        ACache.get(getContext()).put(HGConstant.USERNAME_GIFT_URL, "true");
    }

    @Override
    public void postValidGift2Result(ValidResult validResult) {
        GameLog.log("=============红包的地址是否正常=============");
        EventBus.getDefault().post(new StartBrotherEvent(EventsFragment.newInstance(null, userMoney, 1)));
        //ACache.get(getContext()).put(HGConstant.USERNAME_GIFT_URL,"true");
    }

    /**
     * 0:体育
     * 1：AG真人
     * 2：第三方彩票
     * 3：皇冠棋牌
     * 4：开元
     * 5：电子
     * 6：VG
     * 7：乐游
     * 8：泛亚电子
      * @param maintainResult
     */
    @Override
    public void postMaintainResult(List<MaintainResult> maintainResult) {
        GameLog.log("=============维护日志=============");
        for (MaintainResult maintainResult1 : maintainResult) {
            switch (maintainResult1.getType()) {
                case "sport":
                    GameLog.log("sport " + maintainResult1.getState());
                    if (userState.equals("0")) {
                        showMessage(maintainResult1.getContent());
                    }
                    ACache.get(getContext()).put(HGConstant.USERNAME_SPORT_MAINTAIN, maintainResult1.getState());
                    break;
                case "video":
                    if (userState.equals("1")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("video " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_VIDEO_MAINTAIN, maintainResult1.getState());
                    break;
                case "game":
                    if (userState.equals("5")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("game " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_GAME_MAINTAIN, maintainResult1.getState());
                    break;
                case "thirdcp":
                    if (userState.equals("2")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("lottery " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_LOTTERY_MAINTAIN, maintainResult1.getState());
                    break;
                case "ky":
                    if (userState.equals("4")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("ky " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_KY_MAINTAIN, maintainResult1.getState());
                    break;
                case "hgqp":
                    if (userState.equals("3")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("hg " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_HG_MAINTAIN, maintainResult1.getState());
                    break;
                case "vgqp":
                    if (userState.equals("6")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("vg " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_VG_MAINTAIN, maintainResult1.getState());
                    break;
                case "ly":
                    if (userState.equals("7")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("ly " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_LY_MAINTAIN, maintainResult1.getState());
                    break;
                case "avia"://泛亚电竞
                    if (userState.equals("8")) {
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("ly " + maintainResult1.getState());
                    ACache.get(getContext()).put(HGConstant.USERNAME_AVIA_MAINTAIN, maintainResult1.getState());
                    break;
                case "og":
                    if(userState.equals("9")){
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("og "+maintainResult1.getState());
                    ACache.get(getContext()).put("username_dd_maintain_og",maintainResult1.getState());
                    break;
                case "cq":
                    if(userState.equals("10")){
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("cq "+maintainResult1.getState());
                    ACache.get(getContext()).put("username_dd_maintain_cq",maintainResult1.getState());
                    break;
                case "mw":
                    if(userState.equals("11")){
                        showMessage(maintainResult1.getContent());
                    }
                    GameLog.log("mw "+maintainResult1.getState());
                    ACache.get(getContext()).put("username_dd_maintain_mw",maintainResult1.getState());
                    break;
            }
        }

    }

    @Override
    public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult) {
        GameLog.log("游戏弟弟值："+agGameLoginResult.getUrl());
        if(!"捕鱼游戏".equals(playName)){
            Intent intent = new Intent(Intent.ACTION_VIEW);
            intent.setData(Uri.parse(agGameLoginResult.getUrl()));
            startActivity(intent);
            return;
        }
        //EventBus.getDefault().post(new StartBrotherEvent(XPlayGameFragment.newInstance(dzTitileName,agGameLoginResult.getUrl(),"1"), SupportFragment.SINGLETASK));
        Intent intent = new Intent(getContext(),XPlayGameActivity.class);
        intent.putExtra("url",agGameLoginResult.getUrl());
        intent.putExtra("gameCnName",playName);
        intent.putExtra("hidetitlebar",false);
        getActivity().startActivity(intent);
    }

    private void postValidGiftGo() {
        String gift_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL);
        if (Check.isEmpty(gift_url)) {
            showMessage("正在加载中，请稍后再试!");
            presenter.postValidGift2("", "get_valid");
        }/*else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL))){
            showMessage("正在加载中，请稍后再试!");
        }*/ else {
            EventBus.getDefault().post(new StartBrotherEvent(EventsFragment.newInstance(null, userMoney, 1)));
        }
    }

    private void postQiPaiGo() {
        String qipai_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_QIPAI_URL);
        if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
            qipai_url = ACache.get(getContext()).getAsString(HGConstant.KY_DEMO_URL);
        }
        if (Check.isEmpty(qipai_url)) {
            showMessage("正在加载中，请稍后再试!");
            presenter.postQipai("", "");
        }/*else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL))){
            showMessage("正在加载中，请稍后再试!");
        }*/ else {
            Intent intent = new Intent(getContext(), XPlayGameActivity.class);
            intent.putExtra("url", qipai_url);
            intent.putExtra("gameCnName", "开元棋牌");
            intent.putExtra("hidetitlebar", false);
            getActivity().startActivity(intent);
        }

    }

    private void postHGQiPaiGo() {
        String qipai_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_HG_QIPAI_URL);
        if (Check.isEmpty(qipai_url)) {
            showMessage("正在加载中，请稍后再试!");
            presenter.postHGQipai("", "");
        }/*else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL))){
            showMessage("正在加载中，请稍后再试!");
        }*/ else {
            Intent intent = new Intent(getContext(), XPlayGameActivity.class);
            intent.putExtra("url", qipai_url);
            intent.putExtra("gameCnName", "皇冠棋牌");
            intent.putExtra("hidetitlebar", false);
            getActivity().startActivity(intent);
        }

    }

    private void postVGQiPaiGo() {
        String qipai_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_VG_QIPAI_URL);
        if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
            qipai_url = ACache.get(getContext()).getAsString(HGConstant.VG_DEMO_URL);
            //qipai_url = qipai_url+"&flag=test";
        }
        if (Check.isEmpty(qipai_url)) {
            showMessage("正在加载中，请稍后再试!");
            presenter.postVGQipai("", "");
        }/*else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL))){
            showMessage("正在加载中，请稍后再试!");
        }*/ else {
            Intent intent = new Intent(getContext(), XPlayGameActivity.class);
            intent.putExtra("url", qipai_url);
            intent.putExtra("gameCnName", "VG棋牌");
            intent.putExtra("hidetitlebar", false);
            getActivity().startActivity(intent);
        }

    }

    private void postLYQiPaiGo() {
        String qipai_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LY_QIPAI_URL);
        if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
            qipai_url = ACache.get(getContext()).getAsString(HGConstant.LY_DEMO_URL);
        }
        if (Check.isEmpty(qipai_url)) {
            showMessage("正在加载中，请稍后再试!");
            presenter.postLYQipai("", "");
        }/*else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL))){
            showMessage("正在加载中，请稍后再试!");
        }*/ else {
            Intent intent = new Intent(getContext(), XPlayGameActivity.class);
            intent.putExtra("url", qipai_url);
            intent.putExtra("gameCnName", "乐游棋牌");
            intent.putExtra("hidetitlebar", false);
            getActivity().startActivity(intent);
        }

    }

    private void postAviaQiPaiGo() {
        String qipai_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_AVIA_QIPAI_URL);
        if ("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))) {
            qipai_url = ACache.get(getContext()).getAsString(HGConstant.AV_DEMO_URL);
        }
        if (Check.isEmpty(qipai_url)) {
            showMessage("正在加载中，请稍后再试!");
            presenter.postAviaQiPai("", "");
        } else {
            Intent intent = new Intent(getContext(), XPlayGameActivity.class);
            intent.putExtra("url", qipai_url);
            intent.putExtra("gameCnName", "电子竞技");
            intent.putExtra("hidetitlebar", false);
            getActivity().startActivity(intent);
        }

    }


    private void postCPGo() {
        String cp_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_URL);
        if (Check.isEmpty(cp_url)) {
            presenter.postCP();
        } else if (Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.APP_CP_COOKIE))) {
            presenter.postCP();
            showMessage("正在加载中，请稍后再试!");
        } else {
            Intent intent = new Intent(getContext(), XPlayGameActivity.class);
            intent.putExtra("url", cp_url);
            intent.putExtra("gameCnName", "彩票游戏");
            intent.putExtra("gameType", "CP");
            intent.putExtra("hidetitlebar", false);
            getActivity().startActivity(intent);
        }
        /*Intent intent= new Intent();
        intent.setAction("android.intent.action.VIEW");
        Uri content_url = Uri.parse(ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_URL));
        intent.setData(content_url);
        startActivity(intent);*/
    }

    @Override
    public void setPresenter(HomePageContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        EventBus.getDefault().register(this);
    }

    @Override
    public void onDetach() {
        super.onDetach();
        EventBus.getDefault().unregister(this);
    }

   /* @Subscribe
    public void onEventMain(CheckUpgradeResult checkUpgradeResult){

        this.checkUpgradeResult = checkUpgradeResult;
        ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL,checkUpgradeResult.getService_meiqia() );
        ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL_QQ,checkUpgradeResult.getService_qq() );
        ACache.get(getContext()).put(HGConstant.USERNAME_SERVICE_URL_WECHAT,checkUpgradeResult.getService_wechat() );
    }*/

    @Subscribe
    public void onEventMain(UserMoneyEvent userMoneyEvent) {
        userMoney = userMoneyEvent.money;
        tvHomePageUserMoney.setText(userMoney);
        homeMoney.setText("￥:"+userMoney);
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_MONEY, userMoney);
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        if (!Check.isEmpty(loginResult.getNoteMessage())) {
            EventShowDialog.newInstance(loginResult.getNoteMessage(), "").show(getFragmentManager());
        }
        GameLog.log("首页获取的用户余额：" + loginResult.getMoney());
        userName = loginResult.getUserName();
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_USERNAME, userName);
        pro = "&Oid=" + loginResult.getOid() + "&userid=" + loginResult.getUserid() + "&UserName=" + loginResult.getUserName() + "&Agents=" + loginResult.getAgents();
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_BANNER, pro);
        if (!Check.isEmpty(loginResult.getMoney())) {
            ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_MONEY, loginResult.getMoney());
            tvHomePageUserMoney.setVisibility(View.VISIBLE);
            homeMoney.setVisibility(View.VISIBLE);
            userMoney = GameShipHelper.formatMoney(loginResult.getMoney());
            tvHomePageUserMoney.setText(userMoney);
            homeMoney.setText("￥:"+userMoney);
            tvHomePageLogin.setVisibility(View.GONE);
            ivHomePageLogin.setVisibility(View.GONE);
            homeUserName.setText("您好，"+userName);
        }
        //presenter.postAGLiveCheckRegister("");
        presenter.postValidGift("", "get_valid");
        presenter.postMaintain();
        presenter.postCP();
        presenter.postQipai("", "");
        presenter.postHGQipai("", "");
        presenter.postVGQipai("", "");
        presenter.postLYQipai("", "");
        presenter.postAviaQiPai("", "");
    }

    @Subscribe
    public void onEventMain(LogoutEvent logoutEvent) {

        GameLog.log("首页用户退出了");
//        if(Check.isEmpty(loginResult.getMoney())){
//            tvHomePageUserName.setText(loginResult.getMoney());
//
//        }
        pro = "";
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_BANNER, pro);
        tvHomePageLogin.setVisibility(View.VISIBLE);
        ivHomePageLogin.setVisibility(View.VISIBLE);
        tvHomePageUserMoney.setVisibility(View.GONE);
        homeMoney.setText("￥0.00");
        userName = "";
        homeUserName.setText("未登录");
        userMoney = "";
        userState = "19";
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_USERNAME, userName);
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_MONEY, userMoney);
    }

}

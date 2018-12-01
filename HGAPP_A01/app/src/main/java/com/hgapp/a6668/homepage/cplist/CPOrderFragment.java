package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.google.gson.Gson;
import com.hgapp.a6668.CPInjections;
import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.BaseSlidingActivity;
import com.hgapp.a6668.common.adapters.AutoSizeAdapter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.TimeHelper;
import com.hgapp.a6668.data.CPBJSCResult2;
import com.hgapp.a6668.data.CPJSKSResult;
import com.hgapp.a6668.data.CPLastResult;
import com.hgapp.a6668.data.CPLeftInfoResult;
import com.hgapp.a6668.data.CPNextIssueResult;
import com.hgapp.a6668.data.CQSSCResult;
import com.hgapp.a6668.data.PCDDResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.cplist.bet.BetCPOrderDialog;
import com.hgapp.a6668.homepage.cplist.events.CPOrderSuccessEvent;
import com.hgapp.a6668.homepage.cplist.events.LeftEvents;
import com.hgapp.a6668.homepage.cplist.events.LeftMenuEvents;
import com.hgapp.a6668.homepage.cplist.order.CPOrderContract;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.TimeUtils;
import com.huangzj.slidingmenu.SlidingMenu;
import com.zhy.adapter.recyclerview.base.ViewHolder;
import com.zhy.autolayout.AutoLinearLayout;
import com.zhy.autolayout.utils.AutoUtils;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;

public class CPOrderFragment extends BaseSlidingActivity implements CPOrderContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";/*
    @BindView(R.id.drawer_layout)
    HGDrawerLayout drawer_layout;*/
    @BindView(R.id.llCPOrderAll)
    LinearLayout llCPOrderAll;
    @BindView(R.id.cpOrderLotteryOpen1)
    RecyclerView cpOrderLotteryOpen1;
    @BindView(R.id.cpOrderLotteryOpen2)
    RecyclerView cpOrderLotteryOpen2;
    /*@BindView(R.id.cpOrderGameList)
    RecyclerView cpOrderGameList;*/
    @BindView(R.id.cpOrderListLeft)
    RecyclerView cpOrderListLeft;
    /*@BindView(R.id.cpOrderListViewtLeft)
    ListView cpOrderListViewtLeft;*/
    @BindView(R.id.cpOrderListRight)
    RecyclerView cpOrderListRight;
   /* @BindView(R.id.cpOrderListViewRight)
    ListView cpOrderListViewRight;*/
    @BindView(R.id.cpOrderUserMoney)
    TextView cpOrderUserMoney;
    @BindView(R.id.cpOrderTitle)
    TextView cpOrderTitle;
    @BindView(R.id.cpOrderLotteryLastTime)
    TextView cpOrderLotteryLastTime;
    @BindView(R.id.cpOrderLotteryNextTime)
    TextView cpOrderLotteryNextTime;
    @BindView(R.id.rightCloseLotteryTime)
    TextView rightCloseLotteryTime;
    @BindView(R.id.rightOpenLotteryTime)
    TextView rightOpenLotteryTime;
    @BindView(R.id.cpOrderReset)
    TextView cpOrderReset;
    @BindView(R.id.cpOrderNoYet)
    TextView cpOrderNoYet;
    @BindView(R.id.cpOrderSubmit)
    TextView cpOrderSubmit;
    @BindView(R.id.cpOrderNumber)
    TextView cpOrderNumber;
    @BindView(R.id.cpOrderGold)
    EditText cpOrderGold;

    private static List<HomePageIcon> cpGameList = new ArrayList<HomePageIcon>();
    private static List<LeftEvents> cpLeftEventList = new ArrayList<LeftEvents>();

    private static List<String> cpLeftEventList1 = new ArrayList<String>();
    private static List<String> cpLeftEventList2 = new ArrayList<String>();
    private static List<CPOrderAllResult> allResultList = new ArrayList<CPOrderAllResult>();
    List<CPOrderContentListResult> data  = new ArrayList<>();
    private int postionAll;
    private CPOrederListRightGameAdapter cpOrederListRightGameAdapter;
    private CPOrederContentGameAdapter cpOrederContentGameAdapter;
    MyAdapter myAdapter;
    /*@BindView(R.id.main_swipemenu)
    SwipeMenu mainSwipemenu;*/
    SlidingMenu slidingLeftMenu;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CPOrderContract.Presenter presenter;
    private ScheduledExecutorService executorService;
    private onLotteryTimeThread lotteryTimeThread = new onLotteryTimeThread();
    private ScheduledExecutorService executorEndService;
    private onWaitingEndThread onWaitingEndThread = new onWaitingEndThread();
    private long sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
    private long sendEndTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    private String orderStype = "bjsc";
    private String  x_session_token = "";
    private String group ="";
    /** 北京赛车    game_code 51
     *  重庆时时彩    game_code 2
     *  极速赛车    game_code 189
     *  极速飞艇    game_code 222
     *  分分彩    game_code 207
     *  三分彩    game_code 407
     *  五分彩    game_code 507
     *  腾讯二分彩    game_code 607
     *  PC蛋蛋    game_code 304
     *  江苏快3    game_code 159
     *  幸运农场    game_code 47
     *  快乐十分    game_code 3
     *  香港六合彩  game_code 69
     *  极速快三    game_code 384
     *
     */
    private String  game_code = "";
    private String round = "";
    private String  type = "0";

    private boolean isCloseLottery = false;
    static {
        //注意事项  每次投注成功之后都需要刷新一下用户的金额 ，且是全局的金额都需要变动  需要发送一下全部的 Money  message 去
        cpGameList.add(new HomePageIcon("系统菜单", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("返回大厅", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("北京赛车(PK10)", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("重庆时时彩", R.mipmap.home_vrcp));
        cpGameList.add(new HomePageIcon("极速赛车", R.mipmap.home_qipai));
        cpGameList.add(new HomePageIcon("极速飞艇", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("分分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("三分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("五分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("腾讯二分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("PC蛋蛋", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("江苏鼓宝(快3)", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("幸运农场", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("广东快乐十分", R.mipmap.home_vrcp));
        cpGameList.add(new HomePageIcon("香港六合彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("极速快三", R.mipmap.home_lhj));
        cpLeftEventList.add(new LeftEvents("两面", "1", false));
        cpLeftEventList.add(new LeftEvents("1-5球", "2", true));
        cpLeftEventList.add(new LeftEvents("前中后", "3", false));
        cpLeftEventList.add(new LeftEvents("两面", "4", false));
        cpLeftEventList.add(new LeftEvents("1-5球", "8", true));
        cpLeftEventList.add(new LeftEvents("前中后", "9", false));
        cpLeftEventList.add(new LeftEvents("两面", "7", false));
        cpLeftEventList.add(new LeftEvents("1-5球", "6", true));
        cpLeftEventList.add(new LeftEvents("前中后", "10", false));
        cpLeftEventList.add(new LeftEvents("两面", "5", false));
        /*cpLeftEventList2.add("3");
        cpLeftEventList2.add("小");
        cpLeftEventList2.add("单");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("龙");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("龙");*/


    }

   /* public static CPOrderFragment newInstance(List<String> param1) {
        CPOrderFragment fragment = new CPOrderFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        CPInjections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }*/

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(null, this);
        super.onCreate(savedInstanceState);
        Intent intent = getIntent();
        game_code = intent.getStringExtra("gameId");
        titleName = intent.getStringExtra("gameName");
        if(0!= setLayoutId())
        {
            View view = LayoutInflater.from(getContext()).inflate(R.layout.fragment_base,null,false);

            FrameLayout contentLayout = (FrameLayout)view.findViewById(R.id.layout_content);
            View contentview = LayoutInflater.from(getContext()).inflate(setLayoutId(),null,false);
            contentLayout.addView(contentview);

            AutoUtils.auto(view);
            ButterKnife.bind(this,view);
            hideLoadingView();
            setEvents(savedInstanceState);
            setContentView(view);
            setBehindContentView(R.layout.left_menu_frame);
        }

        showLeftMenu();

        /*if (getArguments() != null) {
            game_code = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            titleName = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }*/
        EventBus.getDefault().register(this);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_order;
    }

    private void JSKS(CPJSKSResult cpbjscResult){
        for(int k = 0; k < 10; ++k){
            CPOrderAllResult allResult = new CPOrderAllResult();
            switch (k){
                case 0:
                    allResult.setEventChecked(true);
                    allResult.setOrderAllName("和值");
                    List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                    cpOrderContentListResult.setOrderContentListName("和值");
                    cpOrderContentListResult.setShowNumber(2);

                    List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                    cpOrderContentResult3.setOrderName("3点");
                    cpOrderContentResult3.setFullName("和值");
                    cpOrderContentResult3.setOrderState(cpbjscResult.getdata1611112());
                    cpOrderContentResult3.setOrderId("1611-112");
                    cpOrderContentResultList.add(cpOrderContentResult3);

                    CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                    cpOrderContentResult4.setOrderName("4点");
                    cpOrderContentResult4.setFullName("和值");
                    cpOrderContentResult4.setOrderState(cpbjscResult.getdata151516());
                    cpOrderContentResult4.setOrderId("1515-16");
                    cpOrderContentResultList.add(cpOrderContentResult4);

                    CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                    cpOrderContentResult5.setOrderName("5点");
                    cpOrderContentResult5.setFullName("和值");
                    cpOrderContentResult5.setOrderState(cpbjscResult.getdata151617());
                    cpOrderContentResult5.setOrderId("1516-17");
                    cpOrderContentResultList.add(cpOrderContentResult5);

                    CPOrderContentResult cpOrderContentResult6 = new CPOrderContentResult();
                    cpOrderContentResult6.setOrderName("6点");
                    cpOrderContentResult6.setFullName("和值");
                    cpOrderContentResult6.setOrderState(cpbjscResult.getdata151718());
                    cpOrderContentResult6.setOrderId("1517-18");
                    cpOrderContentResultList.add(cpOrderContentResult6);

                    CPOrderContentResult cpOrderContentResult7 = new CPOrderContentResult();
                    cpOrderContentResult7.setOrderName("7点");
                    cpOrderContentResult7.setFullName("和值");
                    cpOrderContentResult7.setOrderState(cpbjscResult.getdata151819());
                    cpOrderContentResult7.setOrderId("1518-19");
                    cpOrderContentResultList.add(cpOrderContentResult7);

                    CPOrderContentResult cpOrderContentResult8 = new CPOrderContentResult();
                    cpOrderContentResult8.setOrderName("8点");
                    cpOrderContentResult8.setFullName("和值");
                    cpOrderContentResult8.setOrderState(cpbjscResult.getdata151920());
                    cpOrderContentResult8.setOrderId("1519-20");
                    cpOrderContentResultList.add(cpOrderContentResult8);


                    CPOrderContentResult cpOrderContentResult9 = new CPOrderContentResult();
                    cpOrderContentResult9.setOrderName("9点");
                    cpOrderContentResult9.setFullName("和值");
                    cpOrderContentResult9.setOrderState(cpbjscResult.getdata152021());
                    cpOrderContentResult9.setOrderId("1520-21");
                    cpOrderContentResultList.add(cpOrderContentResult9);

                    CPOrderContentResult cpOrderContentResult10 = new CPOrderContentResult();
                    cpOrderContentResult10.setOrderName("10点");
                    cpOrderContentResult10.setFullName("和值");
                    cpOrderContentResult10.setOrderState(cpbjscResult.getdata152122());
                    cpOrderContentResult10.setOrderId("1521-22");
                    cpOrderContentResultList.add(cpOrderContentResult10);

                    CPOrderContentResult cpOrderContentResult11 = new CPOrderContentResult();
                    cpOrderContentResult11.setOrderName("11点");
                    cpOrderContentResult11.setFullName("和值");
                    cpOrderContentResult11.setOrderState(cpbjscResult.getdata152223());
                    cpOrderContentResult11.setOrderId("1522-23");
                    cpOrderContentResultList.add(cpOrderContentResult11);

                    CPOrderContentResult cpOrderContentResult12 = new CPOrderContentResult();
                    cpOrderContentResult12.setOrderName("12点");
                    cpOrderContentResult12.setFullName("和值");
                    cpOrderContentResult12.setOrderState(cpbjscResult.getdata152324());
                    cpOrderContentResult12.setOrderId("1523-24");
                    cpOrderContentResultList.add(cpOrderContentResult12);

                    CPOrderContentResult cpOrderContentResult13 = new CPOrderContentResult();
                    cpOrderContentResult13.setOrderName("13点");
                    cpOrderContentResult13.setFullName("和值");
                    cpOrderContentResult13.setOrderState(cpbjscResult.getdata152425());
                    cpOrderContentResult13.setOrderId("1524-25");
                    cpOrderContentResultList.add(cpOrderContentResult13);

                    
                    CPOrderContentResult cpOrderContentResult14 = new CPOrderContentResult();
                    cpOrderContentResult14.setOrderName("14点");
                    cpOrderContentResult14.setFullName("和值");
                    cpOrderContentResult14.setOrderState(cpbjscResult.getdata152526());
                    cpOrderContentResult14.setOrderId("1525-26");
                    cpOrderContentResultList.add(cpOrderContentResult14);

                    CPOrderContentResult cpOrderContentResult15 = new CPOrderContentResult();
                    cpOrderContentResult15.setOrderName("15点");
                    cpOrderContentResult15.setFullName("和值");
                    cpOrderContentResult15.setOrderState(cpbjscResult.getdata152627());
                    cpOrderContentResult15.setOrderId("1526-27");
                    cpOrderContentResultList.add(cpOrderContentResult15);

                    CPOrderContentResult cpOrderContentResult16 = new CPOrderContentResult();
                    cpOrderContentResult16.setOrderName("16点");
                    cpOrderContentResult16.setFullName("和值");
                    cpOrderContentResult16.setOrderState(cpbjscResult.getdata152728());
                    cpOrderContentResult16.setOrderId("1527-28");
                    cpOrderContentResultList.add(cpOrderContentResult16);

                    CPOrderContentResult cpOrderContentResult17 = new CPOrderContentResult();
                    cpOrderContentResult17.setOrderName("17点");
                    cpOrderContentResult17.setFullName("和值");
                    cpOrderContentResult17.setOrderState(cpbjscResult.getdata152829());
                    cpOrderContentResult17.setOrderId("1528-29");
                    cpOrderContentResultList.add(cpOrderContentResult17);

                    CPOrderContentResult cpOrderContentResult18 = new CPOrderContentResult();
                    cpOrderContentResult18.setOrderName("18点");
                    cpOrderContentResult18.setFullName("和值");
                    cpOrderContentResult18.setOrderState(cpbjscResult.getdata1612113());
                    cpOrderContentResult18.setOrderId("1612-113");
                    cpOrderContentResultList.add(cpOrderContentResult18);


                    cpOrderContentListResult.setData(cpOrderContentResultList);
                    CPOrderContentListResult .add(cpOrderContentListResult);

                    allResult.setData(CPOrderContentListResult);

                    allResultList.add(allResult);
                    break;
                case 1:
                    allResult.setOrderAllName("大小单双");
                    List<CPOrderContentListResult> CPOrderContentListResult2 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                    cpOrderContentListResult2.setOrderContentListName("大小单双");
                    cpOrderContentListResult2.setShowNumber(2);

                    List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult21 = new CPOrderContentResult();
                    cpOrderContentResult21.setOrderName("大");
                    cpOrderContentResult21.setFullName("");
                    cpOrderContentResult21.setOrderState(cpbjscResult.getdata15067());
                    cpOrderContentResult21.setOrderId("1506-7");
                    cpOrderContentResultList2.add(cpOrderContentResult21);

                    CPOrderContentResult cpOrderContentResult22 = new CPOrderContentResult();
                    cpOrderContentResult22.setOrderName("小");
                    cpOrderContentResult22.setFullName("");
                    cpOrderContentResult22.setOrderState(cpbjscResult.getdata15078());
                    cpOrderContentResult22.setOrderId("1507-8");
                    cpOrderContentResultList2.add(cpOrderContentResult22);

                    CPOrderContentResult cpOrderContentResult23 = new CPOrderContentResult();
                    cpOrderContentResult23.setOrderName("单");
                    cpOrderContentResult23.setFullName("");
                    cpOrderContentResult23.setOrderState(cpbjscResult.getdata155657());
                    cpOrderContentResult23.setOrderId("1556-57");
                    cpOrderContentResultList2.add(cpOrderContentResult23);

                    CPOrderContentResult cpOrderContentResult24 = new CPOrderContentResult();
                    cpOrderContentResult24.setOrderName("双");
                    cpOrderContentResult24.setFullName("");
                    cpOrderContentResult24.setOrderState(cpbjscResult.getdata155758());
                    cpOrderContentResult24.setOrderId("1557-58");
                    cpOrderContentResultList2.add(cpOrderContentResult24);

                    cpOrderContentListResult2.setData(cpOrderContentResultList2);

                    CPOrderContentListResult2.add(cpOrderContentListResult2);
                    allResult.setData(CPOrderContentListResult2);
                    allResultList.add(allResult);
                    break;
                case 2:
                    allResult.setOrderAllName("通选");
                    List<CPOrderContentListResult> CPOrderContentListResult3 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult3 = new CPOrderContentListResult();
                    cpOrderContentListResult3.setOrderContentListName("通选");
                    cpOrderContentListResult3.setShowNumber(2);

                    List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult31 = new CPOrderContentResult();
                    cpOrderContentResult31.setOrderName("豹子");
                    cpOrderContentResult31.setFullName("");
                    cpOrderContentResult31.setOrderState(cpbjscResult.getdata15089());
                    cpOrderContentResult31.setOrderId("1508-9");
                    cpOrderContentResultList3.add(cpOrderContentResult31);

                    CPOrderContentResult cpOrderContentResult32 = new CPOrderContentResult();
                    cpOrderContentResult32.setOrderName("顺子");
                    cpOrderContentResult32.setFullName("");
                    cpOrderContentResult32.setOrderState(cpbjscResult.getdata155859());
                    cpOrderContentResult32.setOrderId("1558-59");
                    cpOrderContentResultList3.add(cpOrderContentResult32);

                    CPOrderContentResult cpOrderContentResult33 = new CPOrderContentResult();
                    cpOrderContentResult33.setOrderName("对子");
                    cpOrderContentResult33.setFullName("");
                    cpOrderContentResult33.setOrderState(cpbjscResult.getdata155960());
                    cpOrderContentResult33.setOrderId("1559-60");
                    cpOrderContentResultList3.add(cpOrderContentResult33);

                    CPOrderContentResult cpOrderContentResult34 = new CPOrderContentResult();
                    cpOrderContentResult34.setOrderName("三不同");
                    cpOrderContentResult34.setFullName("");
                    cpOrderContentResult34.setOrderState(cpbjscResult.getdata156061());
                    cpOrderContentResult34.setOrderId("1560-61");
                    cpOrderContentResultList3.add(cpOrderContentResult34);

                    cpOrderContentListResult3.setData(cpOrderContentResultList3);

                    CPOrderContentListResult3.add(cpOrderContentListResult3);
                    allResult.setData(CPOrderContentListResult3);
                    allResultList.add(allResult);
                    break;
                case 3:

                    allResult.setOrderAllName("三同号");
                    List<CPOrderContentListResult> CPOrderContentListResult4 = new ArrayList<CPOrderContentListResult>();
                    
                    CPOrderContentListResult cpOrderContentListResult4 = new CPOrderContentListResult();
                    cpOrderContentListResult4.setOrderContentListName("三同号");
                    cpOrderContentListResult4.setShowNumber(2);
                    cpOrderContentListResult4.setShowType("DANIEL");
                    
                    List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult41 = new CPOrderContentResult();
                    cpOrderContentResult41.setOrderName("1_1_1");
                    cpOrderContentResult41.setFullName("三同号");
                    cpOrderContentResult41.setOrderState(cpbjscResult.getdata150910());
                    cpOrderContentResult41.setOrderId("1509-10");
                    cpOrderContentResultList4.add(cpOrderContentResult41);

                    CPOrderContentResult cpOrderContentResult42 = new CPOrderContentResult();
                    cpOrderContentResult42.setOrderName("2_2_2");
                    cpOrderContentResult42.setFullName("三同号");
                    cpOrderContentResult42.setOrderState(cpbjscResult.getdata151011());
                    cpOrderContentResult42.setOrderId("1510-11");
                    cpOrderContentResultList4.add(cpOrderContentResult42);

                    CPOrderContentResult cpOrderContentResult43 = new CPOrderContentResult();
                    cpOrderContentResult43.setOrderName("3_3_3");
                    cpOrderContentResult43.setFullName("三同号");
                    cpOrderContentResult43.setOrderState(cpbjscResult.getdata151112());
                    cpOrderContentResult43.setOrderId("1511-12");
                    cpOrderContentResultList4.add(cpOrderContentResult43);

                    CPOrderContentResult cpOrderContentResult44 = new CPOrderContentResult();
                    cpOrderContentResult44.setOrderName("4_4_4");
                    cpOrderContentResult44.setFullName("三同号");
                    cpOrderContentResult44.setOrderState(cpbjscResult.getdata151213());
                    cpOrderContentResult44.setOrderId("1512-13");
                    cpOrderContentResultList4.add(cpOrderContentResult44);

                    CPOrderContentResult cpOrderContentResult45 = new CPOrderContentResult();
                    cpOrderContentResult45.setOrderName("5_5_5");
                    cpOrderContentResult45.setFullName("三同号");
                    cpOrderContentResult45.setOrderState(cpbjscResult.getdata151314());
                    cpOrderContentResult45.setOrderId("1513-14");
                    cpOrderContentResultList4.add(cpOrderContentResult45);

                    CPOrderContentResult cpOrderContentResult46 = new CPOrderContentResult();
                    cpOrderContentResult46.setOrderName("6_6_6");
                    cpOrderContentResult46.setFullName("三同号");
                    cpOrderContentResult46.setOrderState(cpbjscResult.getdata151415());
                    cpOrderContentResult46.setOrderId("1514-15");
                    cpOrderContentResultList4.add(cpOrderContentResult46);

                    cpOrderContentListResult4.setData(cpOrderContentResultList4);

                    CPOrderContentListResult4.add(cpOrderContentListResult4);
                    allResult.setData(CPOrderContentListResult4);
                    allResultList.add(allResult);
                    break;
                case 4:

                    allResult.setOrderAllName("三不同");
                    List<CPOrderContentListResult> CPOrderContentListResult5 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult5 = new CPOrderContentListResult();
                    cpOrderContentListResult5.setOrderContentListName("三不同");
                    cpOrderContentListResult5.setShowNumber(2);
                    cpOrderContentListResult5.setShowType("DANIEL");

                    List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult123 = new CPOrderContentResult();
                    cpOrderContentResult123.setOrderName("1_2_3");
                    cpOrderContentResult123.setFullName("三不同");
                    cpOrderContentResult123.setOrderState(cpbjscResult.getdata156162());
                    cpOrderContentResult123.setOrderId("1561-62");
                    cpOrderContentResultList5.add(cpOrderContentResult123);

                    CPOrderContentResult cpOrderContentResult124 = new CPOrderContentResult();
                    cpOrderContentResult124.setOrderName("1_2_4");
                    cpOrderContentResult124.setFullName("三不同");
                    cpOrderContentResult124.setOrderState(cpbjscResult.getdata156263());
                    cpOrderContentResult124.setOrderId("1562-63");
                    cpOrderContentResultList5.add(cpOrderContentResult124);

                    CPOrderContentResult cpOrderContentResult125 = new CPOrderContentResult();
                    cpOrderContentResult125.setOrderName("1_2_5");
                    cpOrderContentResult125.setFullName("三不同");
                    cpOrderContentResult125.setOrderState(cpbjscResult.getdata156364());
                    cpOrderContentResult125.setOrderId("1563-64");
                    cpOrderContentResultList5.add(cpOrderContentResult125);

                    CPOrderContentResult cpOrderContentResult126 = new CPOrderContentResult();
                    cpOrderContentResult126.setOrderName("1_2_6");
                    cpOrderContentResult126.setFullName("三不同");
                    cpOrderContentResult126.setOrderState(cpbjscResult.getdata156465());
                    cpOrderContentResult126.setOrderId("1564-65");
                    cpOrderContentResultList5.add(cpOrderContentResult126);

                    CPOrderContentResult cpOrderContentResult134 = new CPOrderContentResult();
                    cpOrderContentResult134.setOrderName("1_3_4");
                    cpOrderContentResult134.setFullName("三不同");
                    cpOrderContentResult134.setOrderState(cpbjscResult.getdata156566());
                    cpOrderContentResult134.setOrderId("1565-66");
                    cpOrderContentResultList5.add(cpOrderContentResult134);

                    CPOrderContentResult cpOrderContentResult135 = new CPOrderContentResult();
                    cpOrderContentResult135.setOrderName("1_3_5");
                    cpOrderContentResult135.setFullName("三不同");
                    cpOrderContentResult135.setOrderState(cpbjscResult.getdata156667());
                    cpOrderContentResult135.setOrderId("1566-67");
                    cpOrderContentResultList5.add(cpOrderContentResult135);

                    CPOrderContentResult cpOrderContentResult136 = new CPOrderContentResult();
                    cpOrderContentResult136.setOrderName("1_3_6");
                    cpOrderContentResult136.setFullName("三不同");
                    cpOrderContentResult136.setOrderState(cpbjscResult.getdata156768());
                    cpOrderContentResult136.setOrderId("1567-68");
                    cpOrderContentResultList5.add(cpOrderContentResult136);

                    CPOrderContentResult cpOrderContentResult145 = new CPOrderContentResult();
                    cpOrderContentResult145.setOrderName("1_4_5");
                    cpOrderContentResult145.setFullName("三不同");
                    cpOrderContentResult145.setOrderState(cpbjscResult.getdata156869());
                    cpOrderContentResult145.setOrderId("1568-69");
                    cpOrderContentResultList5.add(cpOrderContentResult145);

                    CPOrderContentResult cpOrderContentResult146 = new CPOrderContentResult();
                    cpOrderContentResult146.setOrderName("1_4_6");
                    cpOrderContentResult146.setFullName("三不同");
                    cpOrderContentResult146.setOrderState(cpbjscResult.getdata156970());
                    cpOrderContentResult146.setOrderId("1569-70");
                    cpOrderContentResultList5.add(cpOrderContentResult146);

                    CPOrderContentResult cpOrderContentResult156 = new CPOrderContentResult();
                    cpOrderContentResult156.setOrderName("1_5_6");
                    cpOrderContentResult156.setFullName("三不同");
                    cpOrderContentResult156.setOrderState(cpbjscResult.getdata157071());
                    cpOrderContentResult156.setOrderId("1570-71");
                    cpOrderContentResultList5.add(cpOrderContentResult156);

                    CPOrderContentResult cpOrderContentResult234 = new CPOrderContentResult();
                    cpOrderContentResult234.setOrderName("2_3_4");
                    cpOrderContentResult234.setFullName("三不同");
                    cpOrderContentResult234.setOrderState(cpbjscResult.getdata157172());
                    cpOrderContentResult234.setOrderId("1571-72");
                    cpOrderContentResultList5.add(cpOrderContentResult234);

                    CPOrderContentResult cpOrderContentResult235 = new CPOrderContentResult();
                    cpOrderContentResult235.setOrderName("2_3_5");
                    cpOrderContentResult235.setFullName("三不同");
                    cpOrderContentResult235.setOrderState(cpbjscResult.getdata157273());
                    cpOrderContentResult235.setOrderId("1572-73");
                    cpOrderContentResultList5.add(cpOrderContentResult235);

                    CPOrderContentResult cpOrderContentResult236 = new CPOrderContentResult();
                    cpOrderContentResult236.setOrderName("2_3_6");
                    cpOrderContentResult236.setFullName("三不同");
                    cpOrderContentResult236.setOrderState(cpbjscResult.getdata157374());
                    cpOrderContentResult236.setOrderId("1573-74");
                    cpOrderContentResultList5.add(cpOrderContentResult236);

                    CPOrderContentResult cpOrderContentResult245 = new CPOrderContentResult();
                    cpOrderContentResult245.setOrderName("2_4_5");
                    cpOrderContentResult245.setFullName("三不同");
                    cpOrderContentResult245.setOrderState(cpbjscResult.getdata157475());
                    cpOrderContentResult245.setOrderId("1574-75");
                    cpOrderContentResultList5.add(cpOrderContentResult245);

                    CPOrderContentResult cpOrderContentResult246 = new CPOrderContentResult();
                    cpOrderContentResult246.setOrderName("2_4_6");
                    cpOrderContentResult246.setFullName("三不同");
                    cpOrderContentResult246.setOrderState(cpbjscResult.getdata157576());
                    cpOrderContentResult246.setOrderId("1575-76");
                    cpOrderContentResultList5.add(cpOrderContentResult246);

                    CPOrderContentResult cpOrderContentResult256 = new CPOrderContentResult();
                    cpOrderContentResult256.setOrderName("2_5_6");
                    cpOrderContentResult256.setFullName("三不同");
                    cpOrderContentResult256.setOrderState(cpbjscResult.getdata157677());
                    cpOrderContentResult256.setOrderId("1576-77");
                    cpOrderContentResultList5.add(cpOrderContentResult256);

                    CPOrderContentResult cpOrderContentResult345 = new CPOrderContentResult();
                    cpOrderContentResult345.setOrderName("3_4_5");
                    cpOrderContentResult345.setFullName("三不同");
                    cpOrderContentResult345.setOrderState(cpbjscResult.getdata157778());
                    cpOrderContentResult345.setOrderId("157778");
                    cpOrderContentResultList5.add(cpOrderContentResult345);

                    CPOrderContentResult cpOrderContentResult346 = new CPOrderContentResult();
                    cpOrderContentResult346.setOrderName("3_4_6");
                    cpOrderContentResult346.setFullName("三不同");
                    cpOrderContentResult346.setOrderState(cpbjscResult.getdata157879());
                    cpOrderContentResult346.setOrderId("1578-79");
                    cpOrderContentResultList5.add(cpOrderContentResult346);

                    CPOrderContentResult cpOrderContentResult356 = new CPOrderContentResult();
                    cpOrderContentResult356.setOrderName("3_5_6");
                    cpOrderContentResult356.setFullName("三不同");
                    cpOrderContentResult356.setOrderState(cpbjscResult.getdata157980());
                    cpOrderContentResult356.setOrderId("1579-80");
                    cpOrderContentResultList5.add(cpOrderContentResult356);

                    CPOrderContentResult cpOrderContentResult456 = new CPOrderContentResult();
                    cpOrderContentResult456.setOrderName("4_5_6");
                    cpOrderContentResult456.setFullName("三不同");
                    cpOrderContentResult456.setOrderState(cpbjscResult.getdata158081());
                    cpOrderContentResult456.setOrderId("1580-81");
                    cpOrderContentResultList5.add(cpOrderContentResult456);

                    cpOrderContentListResult5.setData(cpOrderContentResultList5);

                    CPOrderContentListResult5.add(cpOrderContentListResult5);
                    allResult.setData(CPOrderContentListResult5);
                    allResultList.add(allResult);

                    break;
                case 5:

                    allResult.setOrderAllName("二同号复选");

                    List<CPOrderContentListResult> CPOrderContentListResult6 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult6 = new CPOrderContentListResult();
                    cpOrderContentListResult6.setOrderContentListName("二同号复选");
                    cpOrderContentListResult6.setShowNumber(2);
                    cpOrderContentListResult6.setShowType("DANIEL_");

                    List<CPOrderContentResult> cpOrderContentResultList6 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult111 = new CPOrderContentResult();
                    cpOrderContentResult111.setOrderName("1_1");
                    cpOrderContentResult111.setFullName("二同号复选");
                    cpOrderContentResult111.setOrderState(cpbjscResult.getdata154445());
                    cpOrderContentResult111.setOrderId("1544-45");
                    cpOrderContentResultList6.add(cpOrderContentResult111);

                    CPOrderContentResult cpOrderContentResult222 = new CPOrderContentResult();
                    cpOrderContentResult222.setOrderName("2_2");
                    cpOrderContentResult222.setFullName("二同号复选");
                    cpOrderContentResult222.setOrderState(cpbjscResult.getdata154546());
                    cpOrderContentResult222.setOrderId("1545-46");
                    cpOrderContentResultList6.add(cpOrderContentResult222);

                    CPOrderContentResult cpOrderContentResult333 = new CPOrderContentResult();
                    cpOrderContentResult333.setOrderName("3_3");
                    cpOrderContentResult333.setFullName("二同号复选");
                    cpOrderContentResult333.setOrderState(cpbjscResult.getdata154647());
                    cpOrderContentResult333.setOrderId("1546-47");
                    cpOrderContentResultList6.add(cpOrderContentResult333);

                    CPOrderContentResult cpOrderContentResult444 = new CPOrderContentResult();
                    cpOrderContentResult444.setOrderName("4_4");
                    cpOrderContentResult444.setFullName("二同号复选");
                    cpOrderContentResult444.setOrderState(cpbjscResult.getdata154748());
                    cpOrderContentResult444.setOrderId("1547-48");
                    cpOrderContentResultList6.add(cpOrderContentResult444);

                    CPOrderContentResult cpOrderContentResult555 = new CPOrderContentResult();
                    cpOrderContentResult555.setOrderName("5_5");
                    cpOrderContentResult555.setFullName("二同号复选");
                    cpOrderContentResult555.setOrderState(cpbjscResult.getdata154849());
                    cpOrderContentResult555.setOrderId("1548-49");
                    cpOrderContentResultList6.add(cpOrderContentResult555);

                    CPOrderContentResult cpOrderContentResult666 = new CPOrderContentResult();
                    cpOrderContentResult666.setOrderName("6_6");
                    cpOrderContentResult666.setFullName("二同号复选");
                    cpOrderContentResult666.setOrderState(cpbjscResult.getdata154950());
                    cpOrderContentResult666.setOrderId("1549-50");
                    cpOrderContentResultList6.add(cpOrderContentResult666);

                    cpOrderContentListResult6.setData(cpOrderContentResultList6);

                    CPOrderContentListResult6.add(cpOrderContentListResult6);
                    allResult.setData(CPOrderContentListResult6);
                    allResultList.add(allResult);

                    break;
                case 6:

                    allResult.setOrderAllName("二同号单选");

                    List<CPOrderContentListResult> CPOrderContentListResult7 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult7 = new CPOrderContentListResult();
                    cpOrderContentListResult7.setOrderContentListName("二同号单选");
                    cpOrderContentListResult7.setShowNumber(2);
                    cpOrderContentListResult7.setShowType("DANIEL");

                    List<CPOrderContentResult> cpOrderContentResultList7 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult112 = new CPOrderContentResult();
                    cpOrderContentResult112.setOrderName("1_1_2");
                    cpOrderContentResult112.setFullName("二同号单选");
                    cpOrderContentResult112.setOrderState(cpbjscResult.getdata158182());
                    cpOrderContentResult112.setOrderId("1581-82");
                    cpOrderContentResultList7.add(cpOrderContentResult112);

                    CPOrderContentResult cpOrderContentResult113 = new CPOrderContentResult();
                    cpOrderContentResult113.setOrderName("1_1_3");
                    cpOrderContentResult113.setFullName("二同号单选");
                    cpOrderContentResult113.setOrderState(cpbjscResult.getdata158283());
                    cpOrderContentResult113.setOrderId("1582-83");
                    cpOrderContentResultList7.add(cpOrderContentResult113);

                    CPOrderContentResult cpOrderContentResult114 = new CPOrderContentResult();
                    cpOrderContentResult114.setOrderName("1_1_4");
                    cpOrderContentResult114.setFullName("二同号单选");
                    cpOrderContentResult114.setOrderState(cpbjscResult.getdata158384());
                    cpOrderContentResult114.setOrderId("1583-84");
                    cpOrderContentResultList7.add(cpOrderContentResult114);

                    CPOrderContentResult cpOrderContentResult115 = new CPOrderContentResult();
                    cpOrderContentResult115.setOrderName("1_1_5");
                    cpOrderContentResult115.setFullName("二同号单选");
                    cpOrderContentResult115.setOrderState(cpbjscResult.getdata158485());
                    cpOrderContentResult115.setOrderId("1584-85");
                    cpOrderContentResultList7.add(cpOrderContentResult115);

                    CPOrderContentResult cpOrderContentResult116 = new CPOrderContentResult();
                    cpOrderContentResult116.setOrderName("1_1_6");
                    cpOrderContentResult116.setFullName("二同号单选");
                    cpOrderContentResult116.setOrderState(cpbjscResult.getdata158586());
                    cpOrderContentResult116.setOrderId("1585-86");
                    cpOrderContentResultList7.add(cpOrderContentResult116);

                    CPOrderContentResult cpOrderContentResult221 = new CPOrderContentResult();
                    cpOrderContentResult221.setOrderName("2_2_1");
                    cpOrderContentResult221.setFullName("二同号单选");
                    cpOrderContentResult221.setOrderState(cpbjscResult.getdata158687());
                    cpOrderContentResult221.setOrderId("1586-87");
                    cpOrderContentResultList7.add(cpOrderContentResult221);

                    CPOrderContentResult cpOrderContentResult223 = new CPOrderContentResult();
                    cpOrderContentResult223.setOrderName("2_2_3");
                    cpOrderContentResult223.setFullName("二同号单选");
                    cpOrderContentResult223.setOrderState(cpbjscResult.getdata158788());
                    cpOrderContentResult223.setOrderId("1587-88");
                    cpOrderContentResultList7.add(cpOrderContentResult223);

                    CPOrderContentResult cpOrderContentResult224 = new CPOrderContentResult();
                    cpOrderContentResult224.setOrderName("2_2_4");
                    cpOrderContentResult224.setFullName("二同号单选");
                    cpOrderContentResult224.setOrderState(cpbjscResult.getdata158889());
                    cpOrderContentResult224.setOrderId("1588-89");
                    cpOrderContentResultList7.add(cpOrderContentResult224);

                    CPOrderContentResult cpOrderContentResult225 = new CPOrderContentResult();
                    cpOrderContentResult225.setOrderName("2_2_5");
                    cpOrderContentResult225.setFullName("二同号单选");
                    cpOrderContentResult225.setOrderState(cpbjscResult.getdata158990());
                    cpOrderContentResult225.setOrderId("1589-90");
                    cpOrderContentResultList7.add(cpOrderContentResult225);

                    CPOrderContentResult cpOrderContentResult226 = new CPOrderContentResult();
                    cpOrderContentResult226.setOrderName("2_2_6");
                    cpOrderContentResult226.setFullName("二同号单选");
                    cpOrderContentResult226.setOrderState(cpbjscResult.getdata159091());
                    cpOrderContentResult226.setOrderId("1590-91");
                    cpOrderContentResultList7.add(cpOrderContentResult226);

                    CPOrderContentResult cpOrderContentResult331 = new CPOrderContentResult();
                    cpOrderContentResult331.setOrderName("3_3_1");
                    cpOrderContentResult331.setFullName("二同号单选");
                    cpOrderContentResult331.setOrderState(cpbjscResult.getdata159192());
                    cpOrderContentResult331.setOrderId("1591-92");
                    cpOrderContentResultList7.add(cpOrderContentResult331);

                    CPOrderContentResult cpOrderContentResult332 = new CPOrderContentResult();
                    cpOrderContentResult332.setOrderName("3_3_2");
                    cpOrderContentResult332.setFullName("二同号单选");
                    cpOrderContentResult332.setOrderState(cpbjscResult.getdata159293());
                    cpOrderContentResult332.setOrderId("1592-93");
                    cpOrderContentResultList7.add(cpOrderContentResult332);

                    CPOrderContentResult cpOrderContentResult334 = new CPOrderContentResult();
                    cpOrderContentResult334.setOrderName("3_3_4");
                    cpOrderContentResult334.setFullName("二同号单选");
                    cpOrderContentResult334.setOrderState(cpbjscResult.getdata159394());
                    cpOrderContentResult334.setOrderId("1593-94");
                    cpOrderContentResultList7.add(cpOrderContentResult334);

                    CPOrderContentResult cpOrderContentResult335 = new CPOrderContentResult();
                    cpOrderContentResult335.setOrderName("3_3_5");
                    cpOrderContentResult335.setFullName("二同号单选");
                    cpOrderContentResult335.setOrderState(cpbjscResult.getdata159495());
                    cpOrderContentResult335.setOrderId("1594-95");
                    cpOrderContentResultList7.add(cpOrderContentResult335);

                    CPOrderContentResult cpOrderContentResult336 = new CPOrderContentResult();
                    cpOrderContentResult336.setOrderName("3_3_6");
                    cpOrderContentResult336.setFullName("二同号单选");
                    cpOrderContentResult336.setOrderState(cpbjscResult.getdata159596());
                    cpOrderContentResult336.setOrderId("1595-96");
                    cpOrderContentResultList7.add(cpOrderContentResult336);

                    CPOrderContentResult cpOrderContentResult441 = new CPOrderContentResult();
                    cpOrderContentResult441.setOrderName("4_4_1");
                    cpOrderContentResult441.setFullName("二同号单选");
                    cpOrderContentResult441.setOrderState(cpbjscResult.getdata159697());
                    cpOrderContentResult441.setOrderId("1596-97");
                    cpOrderContentResultList7.add(cpOrderContentResult441);

                    CPOrderContentResult cpOrderContentResult442 = new CPOrderContentResult();
                    cpOrderContentResult442.setOrderName("4_4_2");
                    cpOrderContentResult442.setFullName("二同号单选");
                    cpOrderContentResult442.setOrderState(cpbjscResult.getdata159798());
                    cpOrderContentResult442.setOrderId("1597-98");
                    cpOrderContentResultList7.add(cpOrderContentResult442);

                    CPOrderContentResult cpOrderContentResult443 = new CPOrderContentResult();
                    cpOrderContentResult443.setOrderName("4_4_3");
                    cpOrderContentResult443.setFullName("二同号单选");
                    cpOrderContentResult443.setOrderState(cpbjscResult.getdata159899());
                    cpOrderContentResult443.setOrderId("1598-99");
                    cpOrderContentResultList7.add(cpOrderContentResult443);

                    CPOrderContentResult cpOrderContentResult445 = new CPOrderContentResult();
                    cpOrderContentResult445.setOrderName("4_4_5");
                    cpOrderContentResult445.setFullName("二同号单选");
                    cpOrderContentResult445.setOrderState(cpbjscResult.getdata1599100());
                    cpOrderContentResult445.setOrderId("1599-100");
                    cpOrderContentResultList7.add(cpOrderContentResult445);

                    CPOrderContentResult cpOrderContentResult446 = new CPOrderContentResult();
                    cpOrderContentResult446.setOrderName("4_4_6");
                    cpOrderContentResult446.setFullName("二同号单选");
                    cpOrderContentResult446.setOrderState(cpbjscResult.getdata1600101());
                    cpOrderContentResult446.setOrderId("1600-101");
                    cpOrderContentResultList7.add(cpOrderContentResult446);

                    CPOrderContentResult cpOrderContentResult551 = new CPOrderContentResult();
                    cpOrderContentResult551.setOrderName("5_5_1");
                    cpOrderContentResult551.setFullName("二同号单选");
                    cpOrderContentResult551.setOrderState(cpbjscResult.getdata1601102());
                    cpOrderContentResult551.setOrderId("1601-102");
                    cpOrderContentResultList7.add(cpOrderContentResult551);

                    CPOrderContentResult cpOrderContentResult552 = new CPOrderContentResult();
                    cpOrderContentResult552.setOrderName("5_5_2");
                    cpOrderContentResult552.setFullName("二同号单选");
                    cpOrderContentResult552.setOrderState(cpbjscResult.getdata1602103());
                    cpOrderContentResult552.setOrderId("1602-103");
                    cpOrderContentResultList7.add(cpOrderContentResult552);

                    CPOrderContentResult cpOrderContentResult553 = new CPOrderContentResult();
                    cpOrderContentResult553.setOrderName("5_5_3");
                    cpOrderContentResult553.setFullName("二同号单选");
                    cpOrderContentResult553.setOrderState(cpbjscResult.getdata1603104());
                    cpOrderContentResult553.setOrderId("1603-104");
                    cpOrderContentResultList7.add(cpOrderContentResult553);

                    CPOrderContentResult cpOrderContentResult554 = new CPOrderContentResult();
                    cpOrderContentResult554.setOrderName("5_5_4");
                    cpOrderContentResult554.setFullName("二同号单选");
                    cpOrderContentResult554.setOrderState(cpbjscResult.getdata1604105());
                    cpOrderContentResult554.setOrderId("1604-105");
                    cpOrderContentResultList7.add(cpOrderContentResult554);

                    CPOrderContentResult cpOrderContentResult556 = new CPOrderContentResult();
                    cpOrderContentResult556.setOrderName("5_5_6");
                    cpOrderContentResult556.setFullName("二同号单选");
                    cpOrderContentResult556.setOrderState(cpbjscResult.getdata1605106());
                    cpOrderContentResult556.setOrderId("1605-106");
                    cpOrderContentResultList7.add(cpOrderContentResult556);


                    CPOrderContentResult cpOrderContentResult661 = new CPOrderContentResult();
                    cpOrderContentResult661.setOrderName("6_6_1");
                    cpOrderContentResult661.setFullName("二同号单选");
                    cpOrderContentResult661.setOrderState(cpbjscResult.getdata1606107());
                    cpOrderContentResult661.setOrderId("1606-107");
                    cpOrderContentResultList7.add(cpOrderContentResult661);

                    CPOrderContentResult cpOrderContentResult662 = new CPOrderContentResult();
                    cpOrderContentResult662.setOrderName("6_6_2");
                    cpOrderContentResult662.setFullName("二同号单选");
                    cpOrderContentResult662.setOrderState(cpbjscResult.getdata1607108());
                    cpOrderContentResult662.setOrderId("1607-108");
                    cpOrderContentResultList7.add(cpOrderContentResult662);

                    CPOrderContentResult cpOrderContentResult663 = new CPOrderContentResult();
                    cpOrderContentResult663.setOrderName("6_6_3");
                    cpOrderContentResult663.setFullName("二同号单选");
                    cpOrderContentResult663.setOrderState(cpbjscResult.getdata1608109());
                    cpOrderContentResult663.setOrderId("1608-109");
                    cpOrderContentResultList7.add(cpOrderContentResult663);

                    CPOrderContentResult cpOrderContentResult664 = new CPOrderContentResult();
                    cpOrderContentResult664.setOrderName("6_6_4");
                    cpOrderContentResult664.setFullName("二同号单选");
                    cpOrderContentResult664.setOrderState(cpbjscResult.getdata1609110());
                    cpOrderContentResult664.setOrderId("1609-110");
                    cpOrderContentResultList7.add(cpOrderContentResult664);

                    CPOrderContentResult cpOrderContentResult665 = new CPOrderContentResult();
                    cpOrderContentResult665.setOrderName("6_6_5");
                    cpOrderContentResult665.setFullName("二同号单选");
                    cpOrderContentResult665.setOrderState(cpbjscResult.getdata1610111());
                    cpOrderContentResult665.setOrderId("1610-111");
                    cpOrderContentResultList7.add(cpOrderContentResult665);

                    cpOrderContentListResult7.setData(cpOrderContentResultList7);

                    CPOrderContentListResult7.add(cpOrderContentListResult7);
                    allResult.setData(CPOrderContentListResult7);
                    allResultList.add(allResult);

                    break;

                case 7:


                    allResult.setOrderAllName("二不同号");
                    List<CPOrderContentListResult> CPOrderContentListResult8 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult8 = new CPOrderContentListResult();
                    cpOrderContentListResult8.setOrderContentListName("二不同号");
                    cpOrderContentListResult8.setShowNumber(2);
                    cpOrderContentListResult8.setShowType("DANIEL_");

                    List<CPOrderContentResult> cpOrderContentResultList8 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult812 = new CPOrderContentResult();
                    cpOrderContentResult812.setOrderName("1_2");
                    cpOrderContentResult812.setFullName("二不同号");
                    cpOrderContentResult812.setOrderState(cpbjscResult.getdata152930());
                    cpOrderContentResult812.setOrderId("1529-30");
                    cpOrderContentResultList8.add(cpOrderContentResult812);

                    CPOrderContentResult cpOrderContentResult813 = new CPOrderContentResult();
                    cpOrderContentResult813.setOrderName("1_3");
                    cpOrderContentResult813.setFullName("二不同号");
                    cpOrderContentResult813.setOrderState(cpbjscResult.getdata153031());
                    cpOrderContentResult813.setOrderId("1530-31");
                    cpOrderContentResultList8.add(cpOrderContentResult813);

                    CPOrderContentResult cpOrderContentResult814 = new CPOrderContentResult();
                    cpOrderContentResult814.setOrderName("1_4");
                    cpOrderContentResult814.setFullName("二不同号");
                    cpOrderContentResult814.setOrderState(cpbjscResult.getdata153132());
                    cpOrderContentResult814.setOrderId("1531-32");
                    cpOrderContentResultList8.add(cpOrderContentResult814);

                    CPOrderContentResult cpOrderContentResult815 = new CPOrderContentResult();
                    cpOrderContentResult815.setOrderName("1_5");
                    cpOrderContentResult815.setFullName("二不同号");
                    cpOrderContentResult815.setOrderState(cpbjscResult.getdata153233());
                    cpOrderContentResult815.setOrderId("1532-33");
                    cpOrderContentResultList8.add(cpOrderContentResult815);

                    CPOrderContentResult cpOrderContentResult816 = new CPOrderContentResult();
                    cpOrderContentResult816.setOrderName("1_6");
                    cpOrderContentResult816.setFullName("二不同号");
                    cpOrderContentResult816.setOrderState(cpbjscResult.getdata153334());
                    cpOrderContentResult816.setOrderId("1533-34");
                    cpOrderContentResultList8.add(cpOrderContentResult816);

                    CPOrderContentResult cpOrderContentResult823 = new CPOrderContentResult();
                    cpOrderContentResult823.setOrderName("2_3");
                    cpOrderContentResult823.setFullName("二不同号");
                    cpOrderContentResult823.setOrderState(cpbjscResult.getdata153435());
                    cpOrderContentResult823.setOrderId("1534-35");
                    cpOrderContentResultList8.add(cpOrderContentResult823);

                    CPOrderContentResult cpOrderContentResult824 = new CPOrderContentResult();
                    cpOrderContentResult824.setOrderName("2_4");
                    cpOrderContentResult824.setFullName("二不同号");
                    cpOrderContentResult824.setOrderState(cpbjscResult.getdata153536());
                    cpOrderContentResult824.setOrderId("1535-36");
                    cpOrderContentResultList8.add(cpOrderContentResult824);

                    CPOrderContentResult cpOrderContentResult825 = new CPOrderContentResult();
                    cpOrderContentResult825.setOrderName("2_5");
                    cpOrderContentResult825.setFullName("二不同号");
                    cpOrderContentResult825.setOrderState(cpbjscResult.getdata153637());
                    cpOrderContentResult825.setOrderId("1536-37");
                    cpOrderContentResultList8.add(cpOrderContentResult825);

                    CPOrderContentResult cpOrderContentResult826 = new CPOrderContentResult();
                    cpOrderContentResult826.setOrderName("2_5");
                    cpOrderContentResult826.setFullName("二不同号");
                    cpOrderContentResult826.setOrderState(cpbjscResult.getdata153738());
                    cpOrderContentResult826.setOrderId("1537-38");
                    cpOrderContentResultList8.add(cpOrderContentResult826);

                    CPOrderContentResult cpOrderContentResult834 = new CPOrderContentResult();
                    cpOrderContentResult834.setOrderName("3_4");
                    cpOrderContentResult834.setFullName("二不同号");
                    cpOrderContentResult834.setOrderState(cpbjscResult.getdata153839());
                    cpOrderContentResult834.setOrderId("1538-39");
                    cpOrderContentResultList8.add(cpOrderContentResult834);

                    CPOrderContentResult cpOrderContentResult835 = new CPOrderContentResult();
                    cpOrderContentResult835.setOrderName("3_5");
                    cpOrderContentResult835.setFullName("二不同号");
                    cpOrderContentResult835.setOrderState(cpbjscResult.getdata153940());
                    cpOrderContentResult835.setOrderId("1539-40");
                    cpOrderContentResultList8.add(cpOrderContentResult835);


                    CPOrderContentResult cpOrderContentResult836 = new CPOrderContentResult();
                    cpOrderContentResult836.setOrderName("3_6");
                    cpOrderContentResult836.setFullName("二不同号");
                    cpOrderContentResult836.setOrderState(cpbjscResult.getdata154041());
                    cpOrderContentResult836.setOrderId("1540-41");
                    cpOrderContentResultList8.add(cpOrderContentResult836);

                    CPOrderContentResult cpOrderContentResult845 = new CPOrderContentResult();
                    cpOrderContentResult845.setOrderName("4_5");
                    cpOrderContentResult845.setFullName("二不同号");
                    cpOrderContentResult845.setOrderState(cpbjscResult.getdata154142());
                    cpOrderContentResult845.setOrderId("1541-42");
                    cpOrderContentResultList8.add(cpOrderContentResult845);

                    CPOrderContentResult cpOrderContentResult846 = new CPOrderContentResult();
                    cpOrderContentResult846.setOrderName("4_6");
                    cpOrderContentResult846.setFullName("二不同号");
                    cpOrderContentResult846.setOrderState(cpbjscResult.getdata154243());
                    cpOrderContentResult846.setOrderId("1542-43");
                    cpOrderContentResultList8.add(cpOrderContentResult846);

                    CPOrderContentResult cpOrderContentResult856 = new CPOrderContentResult();
                    cpOrderContentResult856.setOrderName("5_6");
                    cpOrderContentResult856.setFullName("二不同号");
                    cpOrderContentResult856.setOrderState(cpbjscResult.getdata154344());
                    cpOrderContentResult856.setOrderId("1543-44");
                    cpOrderContentResultList8.add(cpOrderContentResult856);

                    cpOrderContentListResult8.setData(cpOrderContentResultList8);

                    CPOrderContentListResult8.add(cpOrderContentListResult8);
                    allResult.setData(CPOrderContentListResult8);
                    allResultList.add(allResult);


                    break;
                case 8:

                    allResult.setOrderAllName("猜必出");


                    List<CPOrderContentListResult> CPOrderContentListResult9 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult9 = new CPOrderContentListResult();
                    cpOrderContentListResult9.setOrderContentListName("猜必出");
                    cpOrderContentListResult9.setShowNumber(2);
                    cpOrderContentListResult9.setShowType("DANIEL_");

                    List<CPOrderContentResult> cpOrderContentResultList9 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult91 = new CPOrderContentResult();
                    cpOrderContentResult91.setOrderName("1_");
                    cpOrderContentResult91.setFullName("猜必出");
                    cpOrderContentResult91.setOrderState(cpbjscResult.getdata15001());
                    cpOrderContentResult91.setOrderId("1500-1");
                    cpOrderContentResultList9.add(cpOrderContentResult91);

                    CPOrderContentResult cpOrderContentResult92 = new CPOrderContentResult();
                    cpOrderContentResult92.setOrderName("2_");
                    cpOrderContentResult92.setFullName("猜必出");
                    cpOrderContentResult92.setOrderState(cpbjscResult.getdata15012());
                    cpOrderContentResult92.setOrderId("1501-2");
                    cpOrderContentResultList9.add(cpOrderContentResult92);

                    CPOrderContentResult cpOrderContentResult93 = new CPOrderContentResult();
                    cpOrderContentResult93.setOrderName("3_");
                    cpOrderContentResult93.setFullName("猜必出");
                    cpOrderContentResult93.setOrderState(cpbjscResult.getdata15023());
                    cpOrderContentResult93.setOrderId("1502-3");
                    cpOrderContentResultList9.add(cpOrderContentResult93);

                    CPOrderContentResult cpOrderContentResult94 = new CPOrderContentResult();
                    cpOrderContentResult94.setOrderName("4_");
                    cpOrderContentResult94.setFullName("猜必出");
                    cpOrderContentResult94.setOrderState(cpbjscResult.getdata15034());
                    cpOrderContentResult94.setOrderId("1503-4");
                    cpOrderContentResultList9.add(cpOrderContentResult94);

                    CPOrderContentResult cpOrderContentResult95 = new CPOrderContentResult();
                    cpOrderContentResult95.setOrderName("5_");
                    cpOrderContentResult95.setFullName("猜必出");
                    cpOrderContentResult95.setOrderState(cpbjscResult.getdata15045());
                    cpOrderContentResult95.setOrderId("1504-5");
                    cpOrderContentResultList9.add(cpOrderContentResult95);

                    CPOrderContentResult cpOrderContentResult96 = new CPOrderContentResult();
                    cpOrderContentResult96.setOrderName("6_");
                    cpOrderContentResult96.setFullName("猜必出");
                    cpOrderContentResult96.setOrderState(cpbjscResult.getdata15056());
                    cpOrderContentResult96.setOrderId("1505-6");
                    cpOrderContentResultList9.add(cpOrderContentResult96);

                    cpOrderContentListResult9.setData(cpOrderContentResultList9);

                    CPOrderContentListResult9.add(cpOrderContentListResult9);
                    allResult.setData(CPOrderContentListResult9);
                    allResultList.add(allResult);

                    break;
                case 9:


                    allResult.setOrderAllName("猜必不出");

                    List<CPOrderContentListResult> CPOrderContentListResult10 = new ArrayList<CPOrderContentListResult>();
                    CPOrderContentListResult cpOrderContentListResult10 = new CPOrderContentListResult();
                    cpOrderContentListResult10.setOrderContentListName("猜必不出");
                    cpOrderContentListResult10.setShowNumber(2);
                    cpOrderContentListResult10.setShowType("DANIEL_");

                    List<CPOrderContentResult> cpOrderContentResultList10 = new ArrayList<>();
                    CPOrderContentResult cpOrderContentResult101 = new CPOrderContentResult();
                    cpOrderContentResult101.setOrderName("1_");
                    cpOrderContentResult101.setFullName("猜必不出");
                    cpOrderContentResult101.setOrderState(cpbjscResult.getdata155051());
                    cpOrderContentResult101.setOrderId("1550-51");
                    cpOrderContentResultList10.add(cpOrderContentResult101);

                    CPOrderContentResult cpOrderContentResult102 = new CPOrderContentResult();
                    cpOrderContentResult102.setOrderName("2_");
                    cpOrderContentResult102.setFullName("猜必不出");
                    cpOrderContentResult102.setOrderState(cpbjscResult.getdata155152());
                    cpOrderContentResult102.setOrderId("1551-52");
                    cpOrderContentResultList10.add(cpOrderContentResult102);

                    CPOrderContentResult cpOrderContentResult103 = new CPOrderContentResult();
                    cpOrderContentResult103.setOrderName("3_");
                    cpOrderContentResult103.setFullName("猜必不出");
                    cpOrderContentResult103.setOrderState(cpbjscResult.getdata155253());
                    cpOrderContentResult103.setOrderId("1552-53");
                    cpOrderContentResultList10.add(cpOrderContentResult103);

                    CPOrderContentResult cpOrderContentResult104 = new CPOrderContentResult();
                    cpOrderContentResult104.setOrderName("4_");
                    cpOrderContentResult104.setFullName("猜必不出");
                    cpOrderContentResult104.setOrderState(cpbjscResult.getdata155354());
                    cpOrderContentResult104.setOrderId("1553-54");
                    cpOrderContentResultList10.add(cpOrderContentResult104);

                    CPOrderContentResult cpOrderContentResult105 = new CPOrderContentResult();
                    cpOrderContentResult105.setOrderName("5_");
                    cpOrderContentResult105.setFullName("猜必不出");
                    cpOrderContentResult105.setOrderState(cpbjscResult.getdata155455());
                    cpOrderContentResult105.setOrderId("1554-55");
                    cpOrderContentResultList10.add(cpOrderContentResult105);

                    CPOrderContentResult cpOrderContentResult106 = new CPOrderContentResult();
                    cpOrderContentResult106.setOrderName("6_");
                    cpOrderContentResult106.setFullName("猜必不出");
                    cpOrderContentResult106.setOrderState(cpbjscResult.getdata155556());
                    cpOrderContentResult106.setOrderId("1555-56");
                    cpOrderContentResultList10.add(cpOrderContentResult106);

                    cpOrderContentListResult10.setData(cpOrderContentResultList10);

                    CPOrderContentListResult10.add(cpOrderContentListResult10);
                    allResult.setData(CPOrderContentListResult10);
                    allResultList.add(allResult);

                    break;



            }

        }



    }

    private void PCDD(PCDDResult cpbjscResult){
        for (int k = 0; k < 2; ++k) {
            CPOrderAllResult allResult = new CPOrderAllResult();
            if(k==0){
                allResult.setEventChecked(true);
                allResult.setOrderAllName("混合");
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("混合");
                cpOrderContentListResult.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("大");
                cpOrderContentResult1.setFullName("混合");
                cpOrderContentResult1.setOrderState(cpbjscResult.getData_5031());
                cpOrderContentResult1.setOrderId("5031");
                cpOrderContentResultList.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("小");
                cpOrderContentResult2.setFullName("混合");
                cpOrderContentResult2.setOrderState(cpbjscResult.getData_5032());
                cpOrderContentResult2.setOrderId("5032");
                cpOrderContentResultList.add(cpOrderContentResult2);

                CPOrderContentResult cpOrderContentResult29 = new CPOrderContentResult();
                cpOrderContentResult29.setOrderName("单");
                cpOrderContentResult29.setFullName("混合");
                cpOrderContentResult29.setOrderState(cpbjscResult.getData_5029());
                cpOrderContentResult29.setOrderId("5029");
                cpOrderContentResultList.add(cpOrderContentResult29);

                CPOrderContentResult cpOrderContentResult30 = new CPOrderContentResult();
                cpOrderContentResult30.setOrderName("双");
                cpOrderContentResult30.setFullName("混合");
                cpOrderContentResult30.setOrderState(cpbjscResult.getData_5030());
                cpOrderContentResult30.setOrderId("5030");
                cpOrderContentResultList.add(cpOrderContentResult30);

                CPOrderContentResult cpOrderContentResult33 = new CPOrderContentResult();
                cpOrderContentResult33.setOrderName("大单");
                cpOrderContentResult33.setFullName("混合");
                cpOrderContentResult33.setOrderState(cpbjscResult.getData_5033());
                cpOrderContentResult33.setOrderId("5033");
                cpOrderContentResultList.add(cpOrderContentResult33);

                CPOrderContentResult cpOrderContentResult34 = new CPOrderContentResult();
                cpOrderContentResult34.setOrderName("小单");
                cpOrderContentResult34.setFullName("混合");
                cpOrderContentResult34.setOrderState(cpbjscResult.getData_5034());
                cpOrderContentResult34.setOrderId("5034");
                cpOrderContentResultList.add(cpOrderContentResult34);

                CPOrderContentResult cpOrderContentResult35 = new CPOrderContentResult();
                cpOrderContentResult35.setOrderName("大双");
                cpOrderContentResult35.setFullName("混合");
                cpOrderContentResult35.setOrderState(cpbjscResult.getData_5035());
                cpOrderContentResult35.setOrderId("5035");
                cpOrderContentResultList.add(cpOrderContentResult35);

                CPOrderContentResult cpOrderContentResult36 = new CPOrderContentResult();
                cpOrderContentResult36.setOrderName("小双");
                cpOrderContentResult36.setFullName("混合");
                cpOrderContentResult36.setOrderState(cpbjscResult.getData_5036());
                cpOrderContentResult36.setOrderId("5036");
                cpOrderContentResultList.add(cpOrderContentResult36);

                cpOrderContentListResult.setData(cpOrderContentResultList);

                CPOrderContentListResult.add(cpOrderContentListResult);

                CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                cpOrderContentListResult2.setOrderContentListName("");
                cpOrderContentListResult2.setShowNumber(3);

                List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult37 = new CPOrderContentResult();
                cpOrderContentResult37.setOrderName("极大");
                cpOrderContentResult37.setFullName("混合");
                cpOrderContentResult37.setOrderState(cpbjscResult.getData_5037());
                cpOrderContentResult37.setOrderId("5037");
                cpOrderContentResultList2.add(cpOrderContentResult37);

                CPOrderContentResult cpOrderContentResult38 = new CPOrderContentResult();
                cpOrderContentResult38.setOrderName("极小");
                cpOrderContentResult38.setFullName("混合");
                cpOrderContentResult38.setOrderState(cpbjscResult.getData_5038());
                cpOrderContentResult38.setOrderId("5038");
                cpOrderContentResultList2.add(cpOrderContentResult38);

                CPOrderContentResult cpOrderContentResult42 = new CPOrderContentResult();
                cpOrderContentResult42.setOrderName("豹子");
                cpOrderContentResult42.setFullName("混合");
                cpOrderContentResult42.setOrderState(cpbjscResult.getData_5042());
                cpOrderContentResult42.setOrderId("5042");
                cpOrderContentResultList2.add(cpOrderContentResult42);

                CPOrderContentResult cpOrderContentResult39 = new CPOrderContentResult();
                cpOrderContentResult39.setOrderName("红波");
                cpOrderContentResult39.setFullName("混合");
                cpOrderContentResult39.setOrderState(cpbjscResult.getData_5039());
                cpOrderContentResult39.setOrderId("5039");
                cpOrderContentResultList2.add(cpOrderContentResult39);

                CPOrderContentResult cpOrderContentResult41 = new CPOrderContentResult();
                cpOrderContentResult41.setOrderName("蓝波");
                cpOrderContentResult41.setFullName("混合");
                cpOrderContentResult41.setOrderState(cpbjscResult.getData_5041());
                cpOrderContentResult41.setOrderId("5041");
                cpOrderContentResultList2.add(cpOrderContentResult41);

                CPOrderContentResult cpOrderContentResult40 = new CPOrderContentResult();
                cpOrderContentResult40.setOrderName("绿波");
                cpOrderContentResult40.setFullName("混合");
                cpOrderContentResult40.setOrderState(cpbjscResult.getData_5040());
                cpOrderContentResult40.setOrderId("5040");
                cpOrderContentResultList2.add(cpOrderContentResult40);

                cpOrderContentListResult2.setData(cpOrderContentResultList2);

                CPOrderContentListResult.add(cpOrderContentListResult2);

                allResult.setData(CPOrderContentListResult);
            }else{
                allResult.setOrderAllName("特码");
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("特码");
                cpOrderContentListResult.setShowNumber(3);
                cpOrderContentListResult.setShowType("QIU");

                List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();

                CPOrderContentResult cpOrderContentResult00 = new CPOrderContentResult();
                cpOrderContentResult00.setOrderName("0");
                cpOrderContentResult00.setFullName("特码");
                cpOrderContentResult00.setOrderState(cpbjscResult.getData_5001());
                cpOrderContentResult00.setOrderId("5001");
                cpOrderContentResultList.add(cpOrderContentResult00);

                CPOrderContentResult cpOrderContentResult01 = new CPOrderContentResult();
                cpOrderContentResult01.setOrderName("1");
                cpOrderContentResult01.setFullName("特码");
                cpOrderContentResult01.setOrderState(cpbjscResult.getData_5002());
                cpOrderContentResult01.setOrderId("5002");
                cpOrderContentResultList.add(cpOrderContentResult01);

                CPOrderContentResult cpOrderContentResult02 = new CPOrderContentResult();
                cpOrderContentResult02.setOrderName("2");
                cpOrderContentResult02.setFullName("特码");
                cpOrderContentResult02.setOrderState(cpbjscResult.getData_5003());
                cpOrderContentResult02.setOrderId("5003");
                cpOrderContentResultList.add(cpOrderContentResult02);

                CPOrderContentResult cpOrderContentResult03 = new CPOrderContentResult();
                cpOrderContentResult03.setOrderName("3");
                cpOrderContentResult03.setFullName("特码");
                cpOrderContentResult03.setOrderState(cpbjscResult.getData_5004());
                cpOrderContentResult03.setOrderId("5004");
                cpOrderContentResultList.add(cpOrderContentResult03);

                CPOrderContentResult cpOrderContentResult04 = new CPOrderContentResult();
                cpOrderContentResult04.setOrderName("4");
                cpOrderContentResult04.setFullName("特码");
                cpOrderContentResult04.setOrderState(cpbjscResult.getData_5005());
                cpOrderContentResult04.setOrderId("5005");
                cpOrderContentResultList.add(cpOrderContentResult04);

                CPOrderContentResult cpOrderContentResult05 = new CPOrderContentResult();
                cpOrderContentResult05.setOrderName("5");
                cpOrderContentResult05.setFullName("特码");
                cpOrderContentResult05.setOrderState(cpbjscResult.getData_5006());
                cpOrderContentResult05.setOrderId("5006");
                cpOrderContentResultList.add(cpOrderContentResult05);

                CPOrderContentResult cpOrderContentResult06 = new CPOrderContentResult();
                cpOrderContentResult06.setOrderName("6");
                cpOrderContentResult06.setFullName("特码");
                cpOrderContentResult06.setOrderState(cpbjscResult.getData_5007());
                cpOrderContentResult06.setOrderId("5007");
                cpOrderContentResultList.add(cpOrderContentResult06);

                CPOrderContentResult cpOrderContentResult07 = new CPOrderContentResult();
                cpOrderContentResult07.setOrderName("7");
                cpOrderContentResult07.setFullName("特码");
                cpOrderContentResult07.setOrderState(cpbjscResult.getData_5008());
                cpOrderContentResult07.setOrderId("5008");
                cpOrderContentResultList.add(cpOrderContentResult07);

                CPOrderContentResult cpOrderContentResult08 = new CPOrderContentResult();
                cpOrderContentResult08.setOrderName("8");
                cpOrderContentResult08.setFullName("特码");
                cpOrderContentResult08.setOrderState(cpbjscResult.getData_5009());
                cpOrderContentResult08.setOrderId("5009");
                cpOrderContentResultList.add(cpOrderContentResult08);

                CPOrderContentResult cpOrderContentResult09 = new CPOrderContentResult();
                cpOrderContentResult09.setOrderName("9");
                cpOrderContentResult09.setFullName("特码");
                cpOrderContentResult09.setOrderState(cpbjscResult.getData_5010());
                cpOrderContentResult09.setOrderId("5010");
                cpOrderContentResultList.add(cpOrderContentResult09);

                CPOrderContentResult cpOrderContentResult10 = new CPOrderContentResult();
                cpOrderContentResult10.setOrderName("10");
                cpOrderContentResult10.setFullName("特码");
                cpOrderContentResult10.setOrderState(cpbjscResult.getData_5011());
                cpOrderContentResult10.setOrderId("5011");
                cpOrderContentResultList.add(cpOrderContentResult10);

                CPOrderContentResult cpOrderContentResult11 = new CPOrderContentResult();
                cpOrderContentResult11.setOrderName("11");
                cpOrderContentResult11.setFullName("特码");
                cpOrderContentResult11.setOrderState(cpbjscResult.getData_5012());
                cpOrderContentResult11.setOrderId("5012");
                cpOrderContentResultList.add(cpOrderContentResult11);

                CPOrderContentResult cpOrderContentResult12 = new CPOrderContentResult();
                cpOrderContentResult12.setOrderName("12");
                cpOrderContentResult12.setFullName("特码");
                cpOrderContentResult12.setOrderState(cpbjscResult.getData_5013());
                cpOrderContentResult12.setOrderId("5013");
                cpOrderContentResultList.add(cpOrderContentResult12);

                CPOrderContentResult cpOrderContentResult13 = new CPOrderContentResult();
                cpOrderContentResult13.setOrderName("13");
                cpOrderContentResult13.setFullName("特码");
                cpOrderContentResult13.setOrderState(cpbjscResult.getData_5014());
                cpOrderContentResult13.setOrderId("5014");
                cpOrderContentResultList.add(cpOrderContentResult13);

                CPOrderContentResult cpOrderContentResult14 = new CPOrderContentResult();
                cpOrderContentResult14.setOrderName("14");
                cpOrderContentResult14.setFullName("特码");
                cpOrderContentResult14.setOrderState(cpbjscResult.getData_5015());
                cpOrderContentResult14.setOrderId("5015");
                cpOrderContentResultList.add(cpOrderContentResult14);

                CPOrderContentResult cpOrderContentResult15 = new CPOrderContentResult();
                cpOrderContentResult15.setOrderName("15");
                cpOrderContentResult15.setFullName("特码");
                cpOrderContentResult15.setOrderState(cpbjscResult.getData_5016());
                cpOrderContentResult15.setOrderId("5016");
                cpOrderContentResultList.add(cpOrderContentResult15);

                CPOrderContentResult cpOrderContentResult16 = new CPOrderContentResult();
                cpOrderContentResult16.setOrderName("16");
                cpOrderContentResult16.setFullName("特码");
                cpOrderContentResult16.setOrderState(cpbjscResult.getData_5017());
                cpOrderContentResult16.setOrderId("5017");
                cpOrderContentResultList.add(cpOrderContentResult16);

                CPOrderContentResult cpOrderContentResult17 = new CPOrderContentResult();
                cpOrderContentResult17.setOrderName("17");
                cpOrderContentResult17.setFullName("特码");
                cpOrderContentResult17.setOrderState(cpbjscResult.getData_5018());
                cpOrderContentResult17.setOrderId("5018");
                cpOrderContentResultList.add(cpOrderContentResult17);

                CPOrderContentResult cpOrderContentResult18 = new CPOrderContentResult();
                cpOrderContentResult18.setOrderName("18");
                cpOrderContentResult18.setFullName("特码");
                cpOrderContentResult18.setOrderState(cpbjscResult.getData_5019());
                cpOrderContentResult18.setOrderId("5019");
                cpOrderContentResultList.add(cpOrderContentResult18);

                CPOrderContentResult cpOrderContentResult19 = new CPOrderContentResult();
                cpOrderContentResult19.setOrderName("19");
                cpOrderContentResult19.setFullName("特码");
                cpOrderContentResult19.setOrderState(cpbjscResult.getData_5020());
                cpOrderContentResult19.setOrderId("5020");
                cpOrderContentResultList.add(cpOrderContentResult19);

                CPOrderContentResult cpOrderContentResult20 = new CPOrderContentResult();
                cpOrderContentResult20.setOrderName("20");
                cpOrderContentResult20.setFullName("特码");
                cpOrderContentResult20.setOrderState(cpbjscResult.getData_5021());
                cpOrderContentResult20.setOrderId("5021");
                cpOrderContentResultList.add(cpOrderContentResult20);

                CPOrderContentResult cpOrderContentResult21 = new CPOrderContentResult();
                cpOrderContentResult21.setOrderName("21");
                cpOrderContentResult21.setFullName("特码");
                cpOrderContentResult21.setOrderState(cpbjscResult.getData_5022());
                cpOrderContentResult21.setOrderId("5022");
                cpOrderContentResultList.add(cpOrderContentResult21);

                CPOrderContentResult cpOrderContentResult22 = new CPOrderContentResult();
                cpOrderContentResult22.setOrderName("22");
                cpOrderContentResult22.setFullName("特码");
                cpOrderContentResult22.setOrderState(cpbjscResult.getData_5023());
                cpOrderContentResult22.setOrderId("5023");
                cpOrderContentResultList.add(cpOrderContentResult22);

                CPOrderContentResult cpOrderContentResult23 = new CPOrderContentResult();
                cpOrderContentResult23.setOrderName("23");
                cpOrderContentResult23.setFullName("特码");
                cpOrderContentResult23.setOrderState(cpbjscResult.getData_5024());
                cpOrderContentResult23.setOrderId("5024");
                cpOrderContentResultList.add(cpOrderContentResult23);

                cpOrderContentListResult.setData(cpOrderContentResultList);

                CPOrderContentListResult.add(cpOrderContentListResult);

                CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                cpOrderContentListResult2.setOrderContentListName("");
                cpOrderContentListResult2.setShowNumber(2);
                cpOrderContentListResult2.setShowType("QIU");

                List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult25 = new CPOrderContentResult();
                cpOrderContentResult25.setOrderName("24");
                cpOrderContentResult25.setFullName("特码");
                cpOrderContentResult25.setOrderState(cpbjscResult.getData_5025());
                cpOrderContentResult25.setOrderId("5025");
                cpOrderContentResultList2.add(cpOrderContentResult25);

                CPOrderContentResult cpOrderContentResult26 = new CPOrderContentResult();
                cpOrderContentResult26.setOrderName("25");
                cpOrderContentResult26.setFullName("特码");
                cpOrderContentResult26.setOrderState(cpbjscResult.getData_5026());
                cpOrderContentResult26.setOrderId("5026");
                cpOrderContentResultList2.add(cpOrderContentResult26);

                CPOrderContentResult cpOrderContentResult27 = new CPOrderContentResult();
                cpOrderContentResult27.setOrderName("26");
                cpOrderContentResult27.setFullName("特码");
                cpOrderContentResult27.setOrderState(cpbjscResult.getData_5027());
                cpOrderContentResult27.setOrderId("5027");
                cpOrderContentResultList2.add(cpOrderContentResult27);

                CPOrderContentResult cpOrderContentResult28 = new CPOrderContentResult();
                cpOrderContentResult28.setOrderName("27");
                cpOrderContentResult28.setFullName("特码");
                cpOrderContentResult28.setOrderState(cpbjscResult.getData_5028());
                cpOrderContentResult28.setOrderId("5028");
                cpOrderContentResultList2.add(cpOrderContentResult28);

                cpOrderContentListResult2.setData(cpOrderContentResultList2);

                CPOrderContentListResult.add(cpOrderContentListResult2);

                allResult.setData(CPOrderContentListResult);
            }
            allResultList.add(allResult);
        }
    }


    private void CQSSC(CQSSCResult cpbjscResult){
        for(int index=0;index<3;++index){
            CPOrderAllResult allResult = new CPOrderAllResult();
            if(index==0){
                allResult.setEventChecked(true);
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                for (int l = 0; l < 7; ++l) {
                    CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                    switch (l) {
                        case 0:
                            cpOrderContentListResult.setOrderContentListName("第一球");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                            CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                            cpOrderContentResult0.setOrderName("大");
                            cpOrderContentResult0.setFullName("第一球");
                            cpOrderContentResult0.setOrderState(cpbjscResult.getData_11005());
                            cpOrderContentResult0.setOrderId("1-1005");
                            cpOrderContentResultList.add(cpOrderContentResult0);

                            CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                            cpOrderContentResult1.setOrderName("小");
                            cpOrderContentResult1.setFullName("第一球");
                            cpOrderContentResult1.setOrderState(cpbjscResult.getData_11006());
                            cpOrderContentResult1.setOrderId("1-1006");
                            cpOrderContentResultList.add(cpOrderContentResult1);

                            CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                            cpOrderContentResult2.setOrderName("单");
                            cpOrderContentResult2.setFullName("第一球");
                            cpOrderContentResult2.setOrderState(cpbjscResult.getData_11007());
                            cpOrderContentResult2.setOrderId("1-1007");
                            cpOrderContentResultList.add(cpOrderContentResult2);

                            CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                            cpOrderContentResult3.setOrderName("双");
                            cpOrderContentResult3.setFullName("第一球");
                            cpOrderContentResult3.setOrderState(cpbjscResult.getData_11008());
                            cpOrderContentResult3.setOrderId("1-1008");
                            cpOrderContentResultList.add(cpOrderContentResult3);
                            cpOrderContentListResult.setData(cpOrderContentResultList);

                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 1:
                            cpOrderContentListResult.setOrderContentListName("第二球");
                            cpOrderContentListResult.setShowNumber(2);

                            List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                            CPOrderContentResult cpOrderContentResult20 = new CPOrderContentResult();
                            cpOrderContentResult20.setOrderName("大");
                            cpOrderContentResult20.setFullName("第二球");
                            cpOrderContentResult20.setOrderState(cpbjscResult.getData_21005());
                            cpOrderContentResult20.setOrderId("1-2005");
                            cpOrderContentResultList2.add(cpOrderContentResult20);

                            CPOrderContentResult cpOrderContentResult21 = new CPOrderContentResult();
                            cpOrderContentResult21.setOrderName("小");
                            cpOrderContentResult21.setFullName("第二球");
                            cpOrderContentResult21.setOrderState(cpbjscResult.getData_21006());
                            cpOrderContentResult21.setOrderId("1-2006");
                            cpOrderContentResultList2.add(cpOrderContentResult21);

                            CPOrderContentResult cpOrderContentResult22 = new CPOrderContentResult();
                            cpOrderContentResult22.setOrderName("单");
                            cpOrderContentResult22.setFullName("第二球");
                            cpOrderContentResult22.setOrderState(cpbjscResult.getData_21007());
                            cpOrderContentResult22.setOrderId("1-2007");
                            cpOrderContentResultList2.add(cpOrderContentResult22);

                            CPOrderContentResult cpOrderContentResult23 = new CPOrderContentResult();
                            cpOrderContentResult23.setOrderName("双");
                            cpOrderContentResult23.setFullName("第二球");
                            cpOrderContentResult23.setOrderState(cpbjscResult.getData_21008());
                            cpOrderContentResult23.setOrderId("1-2008");
                            cpOrderContentResultList2.add(cpOrderContentResult23);

                            cpOrderContentListResult.setData(cpOrderContentResultList2);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 2:
                            cpOrderContentListResult.setOrderContentListName("第三球");
                            cpOrderContentListResult.setShowNumber(2);

                            List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                            CPOrderContentResult cpOrderContentResult30 = new CPOrderContentResult();
                            cpOrderContentResult30.setOrderName("大");
                            cpOrderContentResult30.setFullName("第三球");
                            cpOrderContentResult30.setOrderState(cpbjscResult.getData_31005());
                            cpOrderContentResult30.setOrderId("1-3005");
                            cpOrderContentResultList3.add(cpOrderContentResult30);

                            CPOrderContentResult cpOrderContentResult31 = new CPOrderContentResult();
                            cpOrderContentResult31.setOrderName("小");
                            cpOrderContentResult31.setFullName("第三球");
                            cpOrderContentResult31.setOrderState(cpbjscResult.getData_31006());
                            cpOrderContentResult31.setOrderId("1-3006");
                            cpOrderContentResultList3.add(cpOrderContentResult31);

                            CPOrderContentResult cpOrderContentResult32 = new CPOrderContentResult();
                            cpOrderContentResult32.setOrderName("单");
                            cpOrderContentResult32.setFullName("第三球");
                            cpOrderContentResult32.setOrderState(cpbjscResult.getData_31007());
                            cpOrderContentResult32.setOrderId("1-3007");
                            cpOrderContentResultList3.add(cpOrderContentResult32);

                            CPOrderContentResult cpOrderContentResult33 = new CPOrderContentResult();
                            cpOrderContentResult33.setOrderName("双");
                            cpOrderContentResult33.setFullName("第三球");
                            cpOrderContentResult33.setOrderState(cpbjscResult.getData_31008());
                            cpOrderContentResult33.setOrderId("1-3008");
                            cpOrderContentResultList3.add(cpOrderContentResult33);

                            cpOrderContentListResult.setData(cpOrderContentResultList3);
                            CPOrderContentListResult.add(cpOrderContentListResult);

                            break;
                        case 3:
                            cpOrderContentListResult.setOrderContentListName("第四球");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();
                            CPOrderContentResult cpOrderContentResult40 = new CPOrderContentResult();
                            cpOrderContentResult40.setOrderName("大");
                            cpOrderContentResult40.setFullName("第四球");
                            cpOrderContentResult40.setOrderState(cpbjscResult.getData_41005());
                            cpOrderContentResult40.setOrderId("1-4005");
                            cpOrderContentResultList4.add(cpOrderContentResult40);

                            CPOrderContentResult cpOrderContentResult41 = new CPOrderContentResult();
                            cpOrderContentResult41.setOrderName("小");
                            cpOrderContentResult41.setFullName("第四球");
                            cpOrderContentResult41.setOrderState(cpbjscResult.getData_41006());
                            cpOrderContentResult41.setOrderId("1-4006");
                            cpOrderContentResultList4.add(cpOrderContentResult41);

                            CPOrderContentResult cpOrderContentResult42 = new CPOrderContentResult();
                            cpOrderContentResult42.setOrderName("单");
                            cpOrderContentResult42.setFullName("第四球");
                            cpOrderContentResult42.setOrderState(cpbjscResult.getData_41007());
                            cpOrderContentResult42.setOrderId("1-4007");
                            cpOrderContentResultList4.add(cpOrderContentResult42);

                            CPOrderContentResult cpOrderContentResult43 = new CPOrderContentResult();
                            cpOrderContentResult43.setOrderName("双");
                            cpOrderContentResult43.setFullName("第四球");
                            cpOrderContentResult43.setOrderState(cpbjscResult.getData_41008());
                            cpOrderContentResult43.setOrderId("1-4008");
                            cpOrderContentResultList4.add(cpOrderContentResult43);

                            cpOrderContentListResult.setData(cpOrderContentResultList4);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 4:
                            cpOrderContentListResult.setOrderContentListName("第五球");
                            cpOrderContentListResult.setShowNumber(2);

                            List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();
                            CPOrderContentResult cpOrderContentResult50 = new CPOrderContentResult();
                            cpOrderContentResult50.setOrderName("大");
                            cpOrderContentResult50.setFullName("第五球");
                            cpOrderContentResult50.setOrderState(cpbjscResult.getData_51005());
                            cpOrderContentResult50.setOrderId("1-5005");
                            cpOrderContentResultList5.add(cpOrderContentResult50);

                            CPOrderContentResult cpOrderContentResult51 = new CPOrderContentResult();
                            cpOrderContentResult51.setOrderName("小");
                            cpOrderContentResult51.setFullName("第五球");
                            cpOrderContentResult51.setOrderState(cpbjscResult.getData_51006());
                            cpOrderContentResult51.setOrderId("1-5006");
                            cpOrderContentResultList5.add(cpOrderContentResult51);

                            CPOrderContentResult cpOrderContentResult52 = new CPOrderContentResult();
                            cpOrderContentResult52.setOrderName("单");
                            cpOrderContentResult52.setFullName("第五球");
                            cpOrderContentResult52.setOrderState(cpbjscResult.getData_51007());
                            cpOrderContentResult52.setOrderId("1-5007");
                            cpOrderContentResultList5.add(cpOrderContentResult52);

                            CPOrderContentResult cpOrderContentResult53 = new CPOrderContentResult();
                            cpOrderContentResult53.setOrderName("双");
                            cpOrderContentResult53.setFullName("第五球");
                            cpOrderContentResult53.setOrderState(cpbjscResult.getData_51008());
                            cpOrderContentResult53.setOrderId("1-5008");
                            cpOrderContentResultList5.add(cpOrderContentResult53);

                            cpOrderContentListResult.setData(cpOrderContentResultList5);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 5:
                            cpOrderContentListResult.setOrderContentListName("总和、龙虎");
                            cpOrderContentListResult.setShowNumber(2);

                            List<CPOrderContentResult> cpOrderContentResultList6 = new ArrayList<>();
                            CPOrderContentResult cpOrderContentResult60 = new CPOrderContentResult();
                            cpOrderContentResult60.setOrderName("总和大");
                            cpOrderContentResult60.setFullName("");
                            cpOrderContentResult60.setOrderState(cpbjscResult.getData_1009());
                            cpOrderContentResult60.setOrderId("1009");
                            cpOrderContentResultList6.add(cpOrderContentResult60);

                            CPOrderContentResult cpOrderContentResult61 = new CPOrderContentResult();
                            cpOrderContentResult61.setOrderName("总和小");
                            cpOrderContentResult61.setFullName("");
                            cpOrderContentResult61.setOrderState(cpbjscResult.getData_1010());
                            cpOrderContentResult61.setOrderId("1010");
                            cpOrderContentResultList6.add(cpOrderContentResult61);

                            CPOrderContentResult cpOrderContentResult62 = new CPOrderContentResult();
                            cpOrderContentResult62.setOrderName("总和单");
                            cpOrderContentResult62.setFullName("");
                            cpOrderContentResult62.setOrderState(cpbjscResult.getData_1011());
                            cpOrderContentResult62.setOrderId("1011");
                            cpOrderContentResultList6.add(cpOrderContentResult62);

                            CPOrderContentResult cpOrderContentResult63 = new CPOrderContentResult();
                            cpOrderContentResult63.setOrderName("总和双");
                            cpOrderContentResult63.setFullName("");
                            cpOrderContentResult63.setOrderState(cpbjscResult.getData_1012());
                            cpOrderContentResult63.setOrderId("1012");
                            cpOrderContentResultList6.add(cpOrderContentResult63);

                            cpOrderContentListResult.setData(cpOrderContentResultList6);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 6:
                            cpOrderContentListResult.setOrderContentListName("");
                            cpOrderContentListResult.setShowNumber(3);


                            List<CPOrderContentResult> cpOrderContentResultList7 = new ArrayList<>();
                            CPOrderContentResult cpOrderContentResult70 = new CPOrderContentResult();
                            cpOrderContentResult70.setOrderName("龙");
                            cpOrderContentResult70.setFullName("");
                            cpOrderContentResult70.setOrderState(cpbjscResult.getData_1013());
                            cpOrderContentResult70.setOrderId("1013");
                            cpOrderContentResultList7.add(cpOrderContentResult70);

                            CPOrderContentResult cpOrderContentResult71 = new CPOrderContentResult();
                            cpOrderContentResult71.setOrderName("虎");
                            cpOrderContentResult71.setFullName("");
                            cpOrderContentResult71.setOrderState(cpbjscResult.getData_1014());
                            cpOrderContentResult71.setOrderId("1014");
                            cpOrderContentResultList7.add(cpOrderContentResult71);

                            CPOrderContentResult cpOrderContentResult72 = new CPOrderContentResult();
                            cpOrderContentResult72.setOrderName("和");
                            cpOrderContentResult72.setFullName("");
                            cpOrderContentResult72.setOrderState(cpbjscResult.getData_1015());
                            cpOrderContentResult72.setOrderId("1015");
                            cpOrderContentResultList7.add(cpOrderContentResult72);

                            cpOrderContentListResult.setData(cpOrderContentResultList7);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                    }
                }
                allResult.setOrderAllName("两面");
                allResult.setData(CPOrderContentListResult);
            }else if(index==1){
                List<CPOrderContentListResult> cPOrderContentListResultAll = new ArrayList<CPOrderContentListResult>();
                allResult.setOrderAllName("1-5球");
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("第一球");
                cpOrderContentListResult.setShowNumber(2);
                cpOrderContentListResult.setShowType("QIU");

                List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                cpOrderContentResult0.setOrderName("0");
                cpOrderContentResult0.setFullName("第一球");
                cpOrderContentResult0.setOrderState(cpbjscResult.getData_10000());
                cpOrderContentResult0.setOrderId("1000-0");
                cpOrderContentResultList.add(cpOrderContentResult0);

                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("1");
                cpOrderContentResult1.setFullName("第一球");
                cpOrderContentResult1.setOrderState(cpbjscResult.getData_10001());
                cpOrderContentResult1.setOrderId("1000-1");
                cpOrderContentResultList.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("2");
                cpOrderContentResult2.setFullName("第一球");
                cpOrderContentResult2.setOrderState(cpbjscResult.getData_10002());
                cpOrderContentResult2.setOrderId("1000-2");
                cpOrderContentResultList.add(cpOrderContentResult2);

                CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                cpOrderContentResult3.setOrderName("3");
                cpOrderContentResult3.setFullName("第一球");
                cpOrderContentResult3.setOrderState(cpbjscResult.getData_10003());
                cpOrderContentResult3.setOrderId("1000-3");
                cpOrderContentResultList.add(cpOrderContentResult3);

                CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                cpOrderContentResult4.setOrderName("4");
                cpOrderContentResult4.setFullName("第一球");
                cpOrderContentResult4.setOrderState(cpbjscResult.getData_10004());
                cpOrderContentResult4.setOrderId("1000-4");
                cpOrderContentResultList.add(cpOrderContentResult4);


                CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                cpOrderContentResult5.setOrderName("5");
                cpOrderContentResult5.setFullName("第一球");
                cpOrderContentResult5.setOrderState(cpbjscResult.getData_10005());
                cpOrderContentResult5.setOrderId("1000-5");
                cpOrderContentResultList.add(cpOrderContentResult5);

                CPOrderContentResult cpOrderContentResult6 = new CPOrderContentResult();
                cpOrderContentResult6.setOrderName("6");
                cpOrderContentResult6.setFullName("第一球");
                cpOrderContentResult6.setOrderState(cpbjscResult.getData_10006());
                cpOrderContentResult6.setOrderId("1000-6");
                cpOrderContentResultList.add(cpOrderContentResult6);

                CPOrderContentResult cpOrderContentResult7 = new CPOrderContentResult();
                cpOrderContentResult7.setOrderName("7");
                cpOrderContentResult7.setFullName("第一球");
                cpOrderContentResult7.setOrderState(cpbjscResult.getData_10007());
                cpOrderContentResult7.setOrderId("1000-7");
                cpOrderContentResultList.add(cpOrderContentResult7);

                CPOrderContentResult cpOrderContentResult8 = new CPOrderContentResult();
                cpOrderContentResult8.setOrderName("8");
                cpOrderContentResult8.setFullName("第一球");
                cpOrderContentResult8.setOrderState(cpbjscResult.getData_10008());
                cpOrderContentResult8.setOrderId("1000-8");
                cpOrderContentResultList.add(cpOrderContentResult8);

                CPOrderContentResult cpOrderContentResult9 = new CPOrderContentResult();
                cpOrderContentResult9.setOrderName("9");
                cpOrderContentResult9.setFullName("第一球");
                cpOrderContentResult9.setOrderState(cpbjscResult.getData_10009());
                cpOrderContentResult9.setOrderId("1000-9");
                cpOrderContentResultList.add(cpOrderContentResult9);

                cpOrderContentListResult.setData(cpOrderContentResultList);

                CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                cpOrderContentListResult2.setOrderContentListName("第二球");
                cpOrderContentListResult2.setShowNumber(2);
                cpOrderContentListResult2.setShowType("QIU");

                List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();

                CPOrderContentResult cpOrderContentResult20 = new CPOrderContentResult();
                cpOrderContentResult20.setOrderName("0");
                cpOrderContentResult20.setFullName("第二球");
                cpOrderContentResult20.setOrderState(cpbjscResult.getData_10010());
                cpOrderContentResult20.setOrderId("1001-0");
                cpOrderContentResultList2.add(cpOrderContentResult20);

                CPOrderContentResult cpOrderContentResult21 = new CPOrderContentResult();
                cpOrderContentResult21.setOrderName("1");
                cpOrderContentResult21.setFullName("第二球");
                cpOrderContentResult21.setOrderState(cpbjscResult.getData_10011());
                cpOrderContentResult21.setOrderId("1001-1");
                cpOrderContentResultList2.add(cpOrderContentResult21);

                CPOrderContentResult cpOrderContentResult22 = new CPOrderContentResult();
                cpOrderContentResult22.setOrderName("2");
                cpOrderContentResult22.setFullName("第二球");
                cpOrderContentResult22.setOrderState(cpbjscResult.getData_10012());
                cpOrderContentResult22.setOrderId("1001-2");
                cpOrderContentResultList2.add(cpOrderContentResult22);


                CPOrderContentResult cpOrderContentResult23 = new CPOrderContentResult();
                cpOrderContentResult23.setOrderName("3");
                cpOrderContentResult23.setFullName("第二球");
                cpOrderContentResult23.setOrderState(cpbjscResult.getData_10013());
                cpOrderContentResult23.setOrderId("1001-3");
                cpOrderContentResultList2.add(cpOrderContentResult23);


                CPOrderContentResult cpOrderContentResult24 = new CPOrderContentResult();
                cpOrderContentResult24.setOrderName("4");
                cpOrderContentResult24.setFullName("第二球");
                cpOrderContentResult24.setOrderState(cpbjscResult.getData_10014());
                cpOrderContentResult24.setOrderId("1001-4");
                cpOrderContentResultList2.add(cpOrderContentResult24);

                CPOrderContentResult cpOrderContentResult25 = new CPOrderContentResult();
                cpOrderContentResult25.setOrderName("5");
                cpOrderContentResult25.setFullName("第二球");
                cpOrderContentResult25.setOrderState(cpbjscResult.getData_10015());
                cpOrderContentResult25.setOrderId("1001-5");
                cpOrderContentResultList2.add(cpOrderContentResult25);


                CPOrderContentResult cpOrderContentResult26 = new CPOrderContentResult();
                cpOrderContentResult26.setOrderName("6");
                cpOrderContentResult26.setFullName("第二球");
                cpOrderContentResult26.setOrderState(cpbjscResult.getData_10016());
                cpOrderContentResult26.setOrderId("1001-6");
                cpOrderContentResultList2.add(cpOrderContentResult26);


                CPOrderContentResult cpOrderContentResult27 = new CPOrderContentResult();
                cpOrderContentResult27.setOrderName("7");
                cpOrderContentResult27.setFullName("第二球");
                cpOrderContentResult27.setOrderState(cpbjscResult.getData_10017());
                cpOrderContentResult27.setOrderId("1001-7");
                cpOrderContentResultList2.add(cpOrderContentResult27);


                CPOrderContentResult cpOrderContentResult28 = new CPOrderContentResult();
                cpOrderContentResult28.setOrderName("8");
                cpOrderContentResult28.setFullName("第二球");
                cpOrderContentResult28.setOrderState(cpbjscResult.getData_10018());
                cpOrderContentResult28.setOrderId("1001-8");
                cpOrderContentResultList2.add(cpOrderContentResult28);


                CPOrderContentResult cpOrderContentResult29 = new CPOrderContentResult();
                cpOrderContentResult29.setOrderName("9");
                cpOrderContentResult29.setFullName("第二球");
                cpOrderContentResult29.setOrderState(cpbjscResult.getData_10019());
                cpOrderContentResult29.setOrderId("1001-9");
                cpOrderContentResultList2.add(cpOrderContentResult29);

                cpOrderContentListResult2.setData(cpOrderContentResultList2);


                CPOrderContentListResult cpOrderContentListResult3 = new CPOrderContentListResult();
                cpOrderContentListResult3.setOrderContentListName("第三球");
                cpOrderContentListResult3.setShowNumber(2);
                cpOrderContentListResult3.setShowType("QIU");

                List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();

                CPOrderContentResult cpOrderContentResult30 = new CPOrderContentResult();
                cpOrderContentResult30.setOrderName("0");
                cpOrderContentResult30.setFullName("第三球");
                cpOrderContentResult30.setOrderState(cpbjscResult.getData_10020());
                cpOrderContentResult30.setOrderId("1002-0");
                cpOrderContentResultList3.add(cpOrderContentResult30);

                CPOrderContentResult cpOrderContentResult31 = new CPOrderContentResult();
                cpOrderContentResult31.setOrderName("1");
                cpOrderContentResult31.setFullName("第三球");
                cpOrderContentResult31.setOrderState(cpbjscResult.getData_10021());
                cpOrderContentResult31.setOrderId("1002-1");
                cpOrderContentResultList3.add(cpOrderContentResult31);

                CPOrderContentResult cpOrderContentResult32 = new CPOrderContentResult();
                cpOrderContentResult32.setOrderName("2");
                cpOrderContentResult32.setFullName("第三球");
                cpOrderContentResult32.setOrderState(cpbjscResult.getData_10022());
                cpOrderContentResult32.setOrderId("1002-2");
                cpOrderContentResultList3.add(cpOrderContentResult32);

                CPOrderContentResult cpOrderContentResult33 = new CPOrderContentResult();
                cpOrderContentResult33.setOrderName("3");
                cpOrderContentResult33.setFullName("第三球");
                cpOrderContentResult33.setOrderState(cpbjscResult.getData_10023());
                cpOrderContentResult33.setOrderId("1002-3");
                cpOrderContentResultList3.add(cpOrderContentResult33);

                CPOrderContentResult cpOrderContentResult34 = new CPOrderContentResult();
                cpOrderContentResult34.setOrderName("4");
                cpOrderContentResult34.setFullName("第三球");
                cpOrderContentResult34.setOrderState(cpbjscResult.getData_10024());
                cpOrderContentResult34.setOrderId("1002-4");
                cpOrderContentResultList3.add(cpOrderContentResult34);

                CPOrderContentResult cpOrderContentResult35 = new CPOrderContentResult();
                cpOrderContentResult35.setOrderName("5");
                cpOrderContentResult35.setFullName("第三球");
                cpOrderContentResult35.setOrderState(cpbjscResult.getData_10025());
                cpOrderContentResult35.setOrderId("1002-5");
                cpOrderContentResultList3.add(cpOrderContentResult35);


                CPOrderContentResult cpOrderContentResult36 = new CPOrderContentResult();
                cpOrderContentResult36.setOrderName("6");
                cpOrderContentResult36.setFullName("第三球");
                cpOrderContentResult36.setOrderState(cpbjscResult.getData_10026());
                cpOrderContentResult36.setOrderId("1002-6");
                cpOrderContentResultList3.add(cpOrderContentResult36);

                CPOrderContentResult cpOrderContentResult37 = new CPOrderContentResult();
                cpOrderContentResult37.setOrderName("7");
                cpOrderContentResult37.setFullName("第三球");
                cpOrderContentResult37.setOrderState(cpbjscResult.getData_10027());
                cpOrderContentResult37.setOrderId("1002-7");
                cpOrderContentResultList3.add(cpOrderContentResult37);

                CPOrderContentResult cpOrderContentResult38 = new CPOrderContentResult();
                cpOrderContentResult38.setOrderName("8");
                cpOrderContentResult38.setFullName("第三球");
                cpOrderContentResult38.setOrderState(cpbjscResult.getData_10028());
                cpOrderContentResult38.setOrderId("1002-8");
                cpOrderContentResultList3.add(cpOrderContentResult38);

                CPOrderContentResult cpOrderContentResult39 = new CPOrderContentResult();
                cpOrderContentResult39.setOrderName("9");
                cpOrderContentResult39.setFullName("第三球");
                cpOrderContentResult39.setOrderState(cpbjscResult.getData_10029());
                cpOrderContentResult39.setOrderId("1002-9");
                cpOrderContentResultList3.add(cpOrderContentResult39);

                cpOrderContentListResult3.setData(cpOrderContentResultList3);

                CPOrderContentListResult cpOrderContentListResult4 = new CPOrderContentListResult();
                cpOrderContentListResult4.setOrderContentListName("第四球");
                cpOrderContentListResult4.setShowNumber(2);
                cpOrderContentListResult4.setShowType("QIU");
                List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();

                CPOrderContentResult cpOrderContentResult40 = new CPOrderContentResult();
                cpOrderContentResult40.setOrderName("0");
                cpOrderContentResult40.setFullName("第四球");
                cpOrderContentResult40.setOrderState(cpbjscResult.getData_10030());
                cpOrderContentResult40.setOrderId("1003-0");
                cpOrderContentResultList4.add(cpOrderContentResult40);

                CPOrderContentResult cpOrderContentResult41 = new CPOrderContentResult();
                cpOrderContentResult41.setOrderName("1");
                cpOrderContentResult41.setFullName("第四球");
                cpOrderContentResult41.setOrderState(cpbjscResult.getData_10031());
                cpOrderContentResult41.setOrderId("1003-1");
                cpOrderContentResultList4.add(cpOrderContentResult41);

                CPOrderContentResult cpOrderContentResult42 = new CPOrderContentResult();
                cpOrderContentResult42.setOrderName("2");
                cpOrderContentResult42.setFullName("第四球");
                cpOrderContentResult42.setOrderState(cpbjscResult.getData_10032());
                cpOrderContentResult42.setOrderId("1003-2");
                cpOrderContentResultList4.add(cpOrderContentResult42);

                CPOrderContentResult cpOrderContentResult43 = new CPOrderContentResult();
                cpOrderContentResult43.setOrderName("3");
                cpOrderContentResult43.setFullName("第四球");
                cpOrderContentResult43.setOrderState(cpbjscResult.getData_10033());
                cpOrderContentResult43.setOrderId("1003-3");
                cpOrderContentResultList4.add(cpOrderContentResult43);

                CPOrderContentResult cpOrderContentResult44 = new CPOrderContentResult();
                cpOrderContentResult44.setOrderName("4");
                cpOrderContentResult44.setFullName("第四球");
                cpOrderContentResult44.setOrderState(cpbjscResult.getData_10034());
                cpOrderContentResult44.setOrderId("1003-4");
                cpOrderContentResultList4.add(cpOrderContentResult44);

                CPOrderContentResult cpOrderContentResult45 = new CPOrderContentResult();
                cpOrderContentResult45.setOrderName("5");
                cpOrderContentResult45.setFullName("第四球");
                cpOrderContentResult45.setOrderState(cpbjscResult.getData_10035());
                cpOrderContentResult45.setOrderId("1003-5");
                cpOrderContentResultList4.add(cpOrderContentResult45);

                CPOrderContentResult cpOrderContentResult46 = new CPOrderContentResult();
                cpOrderContentResult46.setOrderName("6");
                cpOrderContentResult46.setFullName("第四球");
                cpOrderContentResult46.setOrderState(cpbjscResult.getData_10036());
                cpOrderContentResult46.setOrderId("1003-6");
                cpOrderContentResultList4.add(cpOrderContentResult46);

                CPOrderContentResult cpOrderContentResult47 = new CPOrderContentResult();
                cpOrderContentResult47.setOrderName("7");
                cpOrderContentResult47.setFullName("第四球");
                cpOrderContentResult47.setOrderState(cpbjscResult.getData_10037());
                cpOrderContentResult47.setOrderId("1003-7");
                cpOrderContentResultList4.add(cpOrderContentResult47);

                CPOrderContentResult cpOrderContentResult48 = new CPOrderContentResult();
                cpOrderContentResult48.setOrderName("8");
                cpOrderContentResult48.setFullName("第四球");
                cpOrderContentResult48.setOrderState(cpbjscResult.getData_10038());
                cpOrderContentResult48.setOrderId("1003-8");
                cpOrderContentResultList4.add(cpOrderContentResult48);

                CPOrderContentResult cpOrderContentResult49 = new CPOrderContentResult();
                cpOrderContentResult49.setOrderName("9");
                cpOrderContentResult49.setFullName("第四球");
                cpOrderContentResult49.setOrderState(cpbjscResult.getData_10039());
                cpOrderContentResult49.setOrderId("1003-9");
                cpOrderContentResultList4.add(cpOrderContentResult49);

                cpOrderContentListResult4.setData(cpOrderContentResultList4);


                CPOrderContentListResult cpOrderContentListResult5 = new CPOrderContentListResult();
                cpOrderContentListResult5.setOrderContentListName("第五球");
                cpOrderContentListResult5.setShowNumber(2);
                cpOrderContentListResult3.setShowType("QIU");

                List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();

                CPOrderContentResult cpOrderContentResult50 = new CPOrderContentResult();
                cpOrderContentResult50.setOrderName("0");
                cpOrderContentResult50.setFullName("第五球");
                cpOrderContentResult50.setOrderState(cpbjscResult.getData_10040());
                cpOrderContentResult50.setOrderId("1004-0");
                cpOrderContentResultList5.add(cpOrderContentResult50);

                CPOrderContentResult cpOrderContentResult51 = new CPOrderContentResult();
                cpOrderContentResult51.setOrderName("1");
                cpOrderContentResult51.setFullName("第五球");
                cpOrderContentResult51.setOrderState(cpbjscResult.getData_10041());
                cpOrderContentResult51.setOrderId("1004-1");
                cpOrderContentResultList5.add(cpOrderContentResult51);

                CPOrderContentResult cpOrderContentResult52 = new CPOrderContentResult();
                cpOrderContentResult52.setOrderName("2");
                cpOrderContentResult52.setFullName("第五球");
                cpOrderContentResult52.setOrderState(cpbjscResult.getData_10042());
                cpOrderContentResult52.setOrderId("1004-2");
                cpOrderContentResultList5.add(cpOrderContentResult52);

                CPOrderContentResult cpOrderContentResult53 = new CPOrderContentResult();
                cpOrderContentResult53.setOrderName("3");
                cpOrderContentResult53.setFullName("第五球");
                cpOrderContentResult53.setOrderState(cpbjscResult.getData_10043());
                cpOrderContentResult53.setOrderId("1004-3");
                cpOrderContentResultList5.add(cpOrderContentResult53);

                CPOrderContentResult cpOrderContentResult54 = new CPOrderContentResult();
                cpOrderContentResult54.setOrderName("4");
                cpOrderContentResult54.setFullName("第五球");
                cpOrderContentResult54.setOrderState(cpbjscResult.getData_10044());
                cpOrderContentResult54.setOrderId("1004-4");
                cpOrderContentResultList5.add(cpOrderContentResult54);

                CPOrderContentResult cpOrderContentResult55 = new CPOrderContentResult();
                cpOrderContentResult55.setOrderName("5");
                cpOrderContentResult55.setFullName("第五球");
                cpOrderContentResult55.setOrderState(cpbjscResult.getData_10045());
                cpOrderContentResult55.setOrderId("1004-5");
                cpOrderContentResultList5.add(cpOrderContentResult55);

                CPOrderContentResult cpOrderContentResult56 = new CPOrderContentResult();
                cpOrderContentResult56.setOrderName("6");
                cpOrderContentResult56.setFullName("第五球");
                cpOrderContentResult56.setOrderState(cpbjscResult.getData_10046());
                cpOrderContentResult56.setOrderId("1004-6");
                cpOrderContentResultList5.add(cpOrderContentResult56);

                CPOrderContentResult cpOrderContentResult57 = new CPOrderContentResult();
                cpOrderContentResult57.setOrderName("7");
                cpOrderContentResult57.setFullName("第五球");
                cpOrderContentResult57.setOrderState(cpbjscResult.getData_10047());
                cpOrderContentResult57.setOrderId("1004-7");
                cpOrderContentResultList5.add(cpOrderContentResult57);

                CPOrderContentResult cpOrderContentResult58 = new CPOrderContentResult();
                cpOrderContentResult58.setOrderName("8");
                cpOrderContentResult58.setFullName("第五球");
                cpOrderContentResult58.setOrderState(cpbjscResult.getData_10048());
                cpOrderContentResult58.setOrderId("1004-8");
                cpOrderContentResultList5.add(cpOrderContentResult58);

                CPOrderContentResult cpOrderContentResult59 = new CPOrderContentResult();
                cpOrderContentResult59.setOrderName("9");
                cpOrderContentResult59.setFullName("第五球");
                cpOrderContentResult59.setOrderState(cpbjscResult.getData_10049());
                cpOrderContentResult59.setOrderId("1004-9");
                cpOrderContentResultList5.add(cpOrderContentResult59);

                cpOrderContentListResult5.setData(cpOrderContentResultList5);

                cPOrderContentListResultAll.add(cpOrderContentListResult);
                cPOrderContentListResultAll.add(cpOrderContentListResult2);
                cPOrderContentListResultAll.add(cpOrderContentListResult3);
                cPOrderContentListResultAll.add(cpOrderContentListResult4);
                cPOrderContentListResultAll.add(cpOrderContentListResult5);

                allResult.setData(cPOrderContentListResultAll);
            }else if(index==2){
                allResult.setOrderAllName("前中后");
                List<CPOrderContentListResult> cPOrderContentListResultAll = new ArrayList<CPOrderContentListResult>();
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("前三");
                cpOrderContentListResult.setShowType("ZI");
                cpOrderContentListResult.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList1 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("豹子");
                cpOrderContentResult1.setFullName("前三");
                cpOrderContentResult1.setOrderState(cpbjscResult.getData_1016());
                cpOrderContentResult1.setOrderId("1016");
                cpOrderContentResultList1.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("顺子");
                cpOrderContentResult2.setFullName("前三");
                cpOrderContentResult2.setOrderState(cpbjscResult.getData_1017());
                cpOrderContentResult2.setOrderId("1017");
                cpOrderContentResultList1.add(cpOrderContentResult2);


                CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                cpOrderContentResult3.setOrderName("对子");
                cpOrderContentResult3.setFullName("前三");
                cpOrderContentResult3.setOrderState(cpbjscResult.getData_1018());
                cpOrderContentResult3.setOrderId("1018");
                cpOrderContentResultList1.add(cpOrderContentResult3);

                CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                cpOrderContentResult4.setOrderName("半顺");
                cpOrderContentResult4.setFullName("前三");
                cpOrderContentResult4.setOrderState(cpbjscResult.getData_1019());
                cpOrderContentResult4.setOrderId("1019");
                cpOrderContentResultList1.add(cpOrderContentResult4);

                cpOrderContentListResult.setData(cpOrderContentResultList1);

                CPOrderContentListResult cpOrderContentListResult11 = new CPOrderContentListResult();
                cpOrderContentListResult11.setOrderContentListName("");
                cpOrderContentListResult11.setShowType("ZI");
                cpOrderContentListResult11.setShowNumber(1);

                List<CPOrderContentResult> cpOrderContentResultList11 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                cpOrderContentResult5.setOrderName("杂六");
                cpOrderContentResult5.setFullName("前三");
                cpOrderContentResult5.setOrderState(cpbjscResult.getData_1020());
                cpOrderContentResult5.setOrderId("1020");
                cpOrderContentResultList11.add(cpOrderContentResult5);

                cpOrderContentListResult11.setData(cpOrderContentResultList11);

                CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                cpOrderContentListResult2.setOrderContentListName("中三");
                cpOrderContentListResult2.setShowType("ZI");
                cpOrderContentListResult2.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult21 = new CPOrderContentResult();
                cpOrderContentResult21.setOrderName("豹子");
                cpOrderContentResult21.setFullName("中三");
                cpOrderContentResult21.setOrderState(cpbjscResult.getData_1021());
                cpOrderContentResult21.setOrderId("1021");
                cpOrderContentResultList2.add(cpOrderContentResult21);


                CPOrderContentResult cpOrderContentResult22 = new CPOrderContentResult();
                cpOrderContentResult22.setOrderName("顺子");
                cpOrderContentResult22.setFullName("中三");
                cpOrderContentResult22.setOrderState(cpbjscResult.getData_1022());
                cpOrderContentResult22.setOrderId("1022");
                cpOrderContentResultList2.add(cpOrderContentResult22);

                CPOrderContentResult cpOrderContentResult23 = new CPOrderContentResult();
                cpOrderContentResult23.setOrderName("对子");
                cpOrderContentResult23.setFullName("中三");
                cpOrderContentResult23.setOrderState(cpbjscResult.getData_1023());
                cpOrderContentResult23.setOrderId("1023");
                cpOrderContentResultList2.add(cpOrderContentResult23);

                CPOrderContentResult cpOrderContentResult24 = new CPOrderContentResult();
                cpOrderContentResult24.setOrderName("半顺");
                cpOrderContentResult24.setFullName("中三");
                cpOrderContentResult24.setOrderState(cpbjscResult.getData_1024());
                cpOrderContentResult24.setOrderId("1024");
                cpOrderContentResultList2.add(cpOrderContentResult24);

                CPOrderContentListResult cpOrderContentListResult22 = new CPOrderContentListResult();
                cpOrderContentListResult22.setOrderContentListName("");
                cpOrderContentListResult22.setShowType("ZI");
                cpOrderContentListResult22.setShowNumber(1);

                List<CPOrderContentResult> cpOrderContentResultList22 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult25 = new CPOrderContentResult();
                cpOrderContentResult25.setOrderName("杂六");
                cpOrderContentResult25.setFullName("中三");
                cpOrderContentResult25.setOrderState(cpbjscResult.getData_1025());
                cpOrderContentResult25.setOrderId("1025");
                cpOrderContentResultList22.add(cpOrderContentResult25);

                cpOrderContentListResult2.setData(cpOrderContentResultList2);

                cpOrderContentListResult22.setData(cpOrderContentResultList22);

                CPOrderContentListResult cpOrderContentListResult3 = new CPOrderContentListResult();
                cpOrderContentListResult3.setOrderContentListName("后三");
                cpOrderContentListResult3.setShowType("ZI");
                cpOrderContentListResult3.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult31 = new CPOrderContentResult();
                cpOrderContentResult31.setOrderName("豹子");
                cpOrderContentResult31.setFullName("后三");
                cpOrderContentResult31.setOrderState(cpbjscResult.getData_1026());
                cpOrderContentResult31.setOrderId("1026");
                cpOrderContentResultList3.add(cpOrderContentResult31);

                CPOrderContentResult cpOrderContentResult32 = new CPOrderContentResult();
                cpOrderContentResult32.setOrderName("顺子");
                cpOrderContentResult32.setFullName("后三");
                cpOrderContentResult32.setOrderState(cpbjscResult.getData_1027());
                cpOrderContentResult32.setOrderId("1027");
                cpOrderContentResultList3.add(cpOrderContentResult32);

                CPOrderContentResult cpOrderContentResult33 = new CPOrderContentResult();
                cpOrderContentResult33.setOrderName("对子");
                cpOrderContentResult33.setFullName("后三");
                cpOrderContentResult33.setOrderState(cpbjscResult.getData_1028());
                cpOrderContentResult33.setOrderId("1028");
                cpOrderContentResultList3.add(cpOrderContentResult33);

                CPOrderContentResult cpOrderContentResult34 = new CPOrderContentResult();
                cpOrderContentResult34.setOrderName("半顺");
                cpOrderContentResult34.setFullName("后三");
                cpOrderContentResult34.setOrderState(cpbjscResult.getData_1029());
                cpOrderContentResult34.setOrderId("1029");
                cpOrderContentResultList3.add(cpOrderContentResult34);

                cpOrderContentListResult3.setData(cpOrderContentResultList3);

                CPOrderContentListResult cpOrderContentListResult33 = new CPOrderContentListResult();
                cpOrderContentListResult33.setOrderContentListName("");
                cpOrderContentListResult33.setShowType("ZI");
                cpOrderContentListResult33.setShowNumber(1);

                List<CPOrderContentResult> cpOrderContentResultList33 = new ArrayList<>();

                CPOrderContentResult cpOrderContentResult35 = new CPOrderContentResult();
                cpOrderContentResult35.setOrderName("杂六");
                cpOrderContentResult35.setFullName("后三");
                cpOrderContentResult35.setOrderState(cpbjscResult.getData_1030());
                cpOrderContentResult35.setOrderId("1030");
                cpOrderContentResultList33.add(cpOrderContentResult35);

                cpOrderContentListResult33.setData(cpOrderContentResultList33);

                cPOrderContentListResultAll.add(cpOrderContentListResult);
                cPOrderContentListResultAll.add(cpOrderContentListResult11);
                cPOrderContentListResultAll.add(cpOrderContentListResult2);
                cPOrderContentListResultAll.add(cpOrderContentListResult22);
                cPOrderContentListResultAll.add(cpOrderContentListResult3);
                cPOrderContentListResultAll.add(cpOrderContentListResult33);
                allResult.setData(cPOrderContentListResultAll);
            }
            allResultList.add(index,allResult);
        }
    }

    private void BJPK10(CPBJSCResult2 cpbjscResult){

        for (int k = 0; k < 4; ++k) {
            CPOrderAllResult allResult = new CPOrderAllResult();
            if(k==0){
                allResult.setEventChecked(true);
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                for (int l = 0; l < 11; ++l) {
                    CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                    switch (l) {
                        case 0:
                            cpOrderContentListResult.setOrderContentListName("冠亚和");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("冠军大");
                                        cpOrderContentResult0.setFullName("冠、亚军和");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_3017());
                                        cpOrderContentResult0.setOrderId("3017");
                                        cpOrderContentResultList.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("冠军小");
                                        cpOrderContentResult1.setFullName("冠、亚军和");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_3018());
                                        cpOrderContentResult1.setOrderId("3018");
                                        cpOrderContentResultList.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("冠军单");
                                        cpOrderContentResult2.setFullName("冠、亚军和");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_3019());
                                        cpOrderContentResult2.setOrderId("3019");
                                        cpOrderContentResultList.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("冠军双");
                                        cpOrderContentResult3.setFullName("冠、亚军和");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_3020());
                                        cpOrderContentResult3.setOrderId("3020");
                                        cpOrderContentResultList.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 1:
                            cpOrderContentListResult.setOrderContentListName("冠军");
                            cpOrderContentListResult.setShowNumber(3);
                            List<CPOrderContentResult> cpOrderContentResultList1 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("冠军");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30013011());
                                        cpOrderContentResult0.setOrderId("3001-3011");
                                        cpOrderContentResultList1.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("冠军");
                                        cpOrderContentResult1.setOrderId("3001-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30013012());
                                        cpOrderContentResultList1.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setFullName("冠军");
                                        cpOrderContentResult2.setOrderId("3001-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30013013());
                                        cpOrderContentResultList1.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setFullName("冠军");
                                        cpOrderContentResult3.setOrderId("3001-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30013014());
                                        cpOrderContentResultList1.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setFullName("冠军");
                                        cpOrderContentResult4.setOrderId("3001-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30013015());
                                        cpOrderContentResultList1.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setFullName("冠军");
                                        cpOrderContentResult5.setOrderId("3001-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30013016());
                                        cpOrderContentResultList1.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList1);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 2:
                            cpOrderContentListResult.setOrderContentListName("亚军");
                            cpOrderContentListResult.setShowNumber(3);
                            List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("亚军");
                                        cpOrderContentResult0.setOrderId("3002-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30023011());
                                        cpOrderContentResultList2.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("亚军");
                                        cpOrderContentResult1.setOrderId("3002-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30023012());
                                        cpOrderContentResultList2.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setFullName("亚军");
                                        cpOrderContentResult2.setOrderId("3002-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30023013());
                                        cpOrderContentResultList2.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setFullName("亚军");
                                        cpOrderContentResult3.setOrderId("3002-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30023014());
                                        cpOrderContentResultList2.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setFullName("亚军");
                                        cpOrderContentResult4.setOrderId("3002-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30023015());
                                        cpOrderContentResultList2.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setFullName("亚军");
                                        cpOrderContentResult5.setOrderId("3002-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30023016());
                                        cpOrderContentResultList2.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList2);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 3:
                            cpOrderContentListResult.setOrderContentListName("第三名");
                            cpOrderContentListResult.setShowNumber(3);
                            List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第三名");
                                        cpOrderContentResult0.setOrderId("3003-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30033011());
                                        cpOrderContentResultList3.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第三名");
                                        cpOrderContentResult1.setOrderId("3003-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30033012());
                                        cpOrderContentResultList3.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setFullName("第三名");
                                        cpOrderContentResult2.setOrderId("3003-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30033013());
                                        cpOrderContentResultList3.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setFullName("第三名");
                                        cpOrderContentResult3.setOrderId("3003-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30033014());
                                        cpOrderContentResultList3.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setFullName("第三名");
                                        cpOrderContentResult4.setOrderId("3003-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30033015());
                                        cpOrderContentResultList3.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setFullName("第三名");
                                        cpOrderContentResult5.setOrderId("3003-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30033016());
                                        cpOrderContentResultList3.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList3);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 4:
                            cpOrderContentListResult.setOrderContentListName("第四名");
                            cpOrderContentListResult.setShowNumber(3);
                            List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第四名");
                                        cpOrderContentResult0.setOrderId("3004-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30043011());
                                        cpOrderContentResultList4.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第四名");
                                        cpOrderContentResult1.setOrderId("3004-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30043012());
                                        cpOrderContentResultList4.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setFullName("第四名");
                                        cpOrderContentResult2.setOrderId("3004-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30043013());
                                        cpOrderContentResultList4.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setFullName("第四名");
                                        cpOrderContentResult3.setOrderId("3004-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30043014());
                                        cpOrderContentResultList4.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setFullName("第四名");
                                        cpOrderContentResult4.setOrderId("3004-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30043015());
                                        cpOrderContentResultList4.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setFullName("第四名");
                                        cpOrderContentResult5.setOrderId("3004-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30043016());
                                        cpOrderContentResultList4.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList4);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 5:
                            cpOrderContentListResult.setOrderContentListName("第五名");
                            cpOrderContentListResult.setShowNumber(3);
                            List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第五名");
                                        cpOrderContentResult0.setOrderId("3005-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30053011());
                                        cpOrderContentResultList5.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第五名");
                                        cpOrderContentResult1.setOrderId("3005-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30053012());
                                        cpOrderContentResultList5.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setFullName("第五名");
                                        cpOrderContentResult2.setOrderId("3005-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30053013());
                                        cpOrderContentResultList5.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setFullName("第五名");
                                        cpOrderContentResult3.setOrderId("3005-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30053014());
                                        cpOrderContentResultList5.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setFullName("第五名");
                                        cpOrderContentResult4.setOrderId("3005-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30053015());
                                        cpOrderContentResultList5.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
                                        cpOrderContentResult5.setFullName("第五名");
                                        cpOrderContentResult5.setOrderId("3005-3016");
                                        cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30053016());
                                        cpOrderContentResultList5.add(cpOrderContentResult5);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList5);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 6:
                            cpOrderContentListResult.setOrderContentListName("第六名");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList6 = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第六名");
                                        cpOrderContentResult0.setOrderId("3006-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30063011());
                                        cpOrderContentResultList6.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第六名");
                                        cpOrderContentResult1.setOrderId("3006-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30063012());
                                        cpOrderContentResultList6.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setFullName("第六名");
                                        cpOrderContentResult2.setOrderId("3006-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30063013());
                                        cpOrderContentResultList6.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setFullName("第六名");
                                        cpOrderContentResult3.setOrderId("3006-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30063014());
                                        cpOrderContentResultList6.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList6);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 7:
                            cpOrderContentListResult.setOrderContentListName("第七名");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList7 = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第七名");
                                        cpOrderContentResult0.setOrderId("3007-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30073011());
                                        cpOrderContentResultList7.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第七名");
                                        cpOrderContentResult1.setOrderId("3007-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30073012());
                                        cpOrderContentResultList7.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setFullName("第七名");
                                        cpOrderContentResult2.setOrderId("3007-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30073013());
                                        cpOrderContentResultList7.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setFullName("第七名");
                                        cpOrderContentResult3.setOrderId("3007-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30073014());
                                        cpOrderContentResultList7.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList7);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 8:
                            cpOrderContentListResult.setOrderContentListName("第八名");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList8 = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第八名");
                                        cpOrderContentResult0.setOrderId("3008-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30083011());
                                        cpOrderContentResultList8.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第八名");
                                        cpOrderContentResult1.setOrderId("3008-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30083012());
                                        cpOrderContentResultList8.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setFullName("第八名");
                                        cpOrderContentResult2.setOrderId("3008-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30083013());
                                        cpOrderContentResultList8.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setFullName("第八名");
                                        cpOrderContentResult3.setOrderId("3008-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30083014());
                                        cpOrderContentResultList8.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList8);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 9:
                            cpOrderContentListResult.setOrderContentListName("第九名");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList9 = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第九名");
                                        cpOrderContentResult0.setOrderId("3009-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30093011());
                                        cpOrderContentResultList9.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第九名");
                                        cpOrderContentResult1.setOrderId("3009-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30093012());
                                        cpOrderContentResultList9.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setFullName("第九名");
                                        cpOrderContentResult2.setOrderId("3009-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30093013());
                                        cpOrderContentResultList9.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setFullName("第九名");
                                        cpOrderContentResult3.setOrderId("3009-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30093014());
                                        cpOrderContentResultList9.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList9);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                        case 10:
                            cpOrderContentListResult.setOrderContentListName("第十名");
                            cpOrderContentListResult.setShowNumber(2);
                            List<CPOrderContentResult> cpOrderContentResultList10 = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setFullName("第十名");
                                        cpOrderContentResult0.setOrderId("3010-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30103011());
                                        cpOrderContentResultList10.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setFullName("第十名");
                                        cpOrderContentResult1.setOrderId("3010-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30103012());
                                        cpOrderContentResultList10.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setFullName("第十名");
                                        cpOrderContentResult2.setOrderId("3010-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30103013());
                                        cpOrderContentResultList10.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
                                        cpOrderContentResult3.setFullName("第十名");
                                        cpOrderContentResult3.setOrderId("3010-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30103014());
                                        cpOrderContentResultList10.add(cpOrderContentResult3);
                                        break;
                                }
                            }
                            cpOrderContentListResult.setData(cpOrderContentResultList10);
                            CPOrderContentListResult.add(cpOrderContentListResult);
                            break;
                    }
                }
                allResult.setOrderAllName("两面");
                allResult.setData(CPOrderContentListResult);
            }else if(k==1){

                List<CPOrderContentListResult> cPOrderContentListResultAll = new ArrayList<CPOrderContentListResult>();
                allResult.setOrderAllName("冠亚和");
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("冠、亚军 组合");
                cpOrderContentListResult.setShowNumber(2);
                List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                cpOrderContentResult0.setOrderName("冠军大");
                cpOrderContentResult0.setFullName("冠、亚军和");
                cpOrderContentResult0.setOrderState(cpbjscResult.getdata_3017());
                cpOrderContentResult0.setOrderId("3017");
                cpOrderContentResultList.add(cpOrderContentResult0);

                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("冠军小");
                cpOrderContentResult1.setFullName("冠、亚军和");
                cpOrderContentResult1.setOrderState(cpbjscResult.getdata_3018());
                cpOrderContentResult1.setOrderId("3018");
                cpOrderContentResultList.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("冠军单");
                cpOrderContentResult2.setFullName("冠、亚军和");
                cpOrderContentResult2.setOrderState(cpbjscResult.getdata_3019());
                cpOrderContentResult2.setOrderId("3019");
                cpOrderContentResultList.add(cpOrderContentResult2);

                CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                cpOrderContentResult3.setOrderName("冠军双");
                cpOrderContentResult3.setFullName("冠、亚军和");
                cpOrderContentResult3.setOrderState(cpbjscResult.getdata_3020());
                cpOrderContentResult3.setOrderId("3020");
                cpOrderContentResultList.add(cpOrderContentResult3);


                CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                cpOrderContentListResult2.setShowNumber(3);
                List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                cpOrderContentResult4.setOrderName("3");
                cpOrderContentResult4.setFullName("冠、亚军和");
                cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult4.setOrderId("3021-3");
                cpOrderContentResultList2.add(cpOrderContentResult4);

                CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                cpOrderContentResult5.setOrderName("4");
                cpOrderContentResult5.setFullName("冠、亚军和");
                cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30214());
                cpOrderContentResult5.setOrderId("3021-4");
                cpOrderContentResultList2.add(cpOrderContentResult5);

                CPOrderContentResult cpOrderContentResult6 = new CPOrderContentResult();
                cpOrderContentResult6.setOrderName("5");
                cpOrderContentResult6.setFullName("冠、亚军和");
                cpOrderContentResult6.setOrderState(cpbjscResult.getdata_30215());
                cpOrderContentResult6.setOrderId("3021-5");
                cpOrderContentResultList2.add(cpOrderContentResult6);

                CPOrderContentResult cpOrderContentResult7 = new CPOrderContentResult();
                cpOrderContentResult7.setOrderName("6");
                cpOrderContentResult7.setFullName("冠、亚军和");
                cpOrderContentResult7.setOrderState(cpbjscResult.getdata_30216());
                cpOrderContentResult7.setOrderId("3021-6");
                cpOrderContentResultList2.add(cpOrderContentResult7);

                CPOrderContentResult cpOrderContentResult8 = new CPOrderContentResult();
                cpOrderContentResult8.setOrderName("7");
                cpOrderContentResult8.setFullName("冠、亚军和");
                cpOrderContentResult8.setOrderState(cpbjscResult.getdata_30217());
                cpOrderContentResult8.setOrderId("3021-7");
                cpOrderContentResultList2.add(cpOrderContentResult8);

                CPOrderContentResult cpOrderContentResult9 = new CPOrderContentResult();
                cpOrderContentResult9.setOrderName("8");
                cpOrderContentResult9.setFullName("冠、亚军和");
                cpOrderContentResult9.setOrderState(cpbjscResult.getdata_30218());
                cpOrderContentResult9.setOrderId("3021-8");
                cpOrderContentResultList2.add(cpOrderContentResult9);

                CPOrderContentResult cpOrderContentResult10 = new CPOrderContentResult();
                cpOrderContentResult10.setOrderName("9");
                cpOrderContentResult10.setFullName("冠、亚军和");
                cpOrderContentResult10.setOrderState(cpbjscResult.getdata_30219());
                cpOrderContentResult10.setOrderId("3021-9");
                cpOrderContentResultList2.add(cpOrderContentResult10);

                CPOrderContentResult cpOrderContentResult11 = new CPOrderContentResult();
                cpOrderContentResult11.setOrderName("10");
                cpOrderContentResult11.setFullName("冠、亚军和");
                cpOrderContentResult11.setOrderState(cpbjscResult.getdata_302110());
                cpOrderContentResult11.setOrderId("3021-10");
                cpOrderContentResultList2.add(cpOrderContentResult11);

                CPOrderContentResult cpOrderContentResult12 = new CPOrderContentResult();
                cpOrderContentResult12.setOrderName("11");
                cpOrderContentResult12.setFullName("冠、亚军和");
                cpOrderContentResult12.setOrderState(cpbjscResult.getdata_302111());
                cpOrderContentResult12.setOrderId("3021-11");
                cpOrderContentResultList2.add(cpOrderContentResult12);

                CPOrderContentResult cpOrderContentResult13 = new CPOrderContentResult();
                cpOrderContentResult13.setOrderName("12");
                cpOrderContentResult13.setFullName("冠、亚军和");
                cpOrderContentResult13.setOrderState(cpbjscResult.getdata_302112());
                cpOrderContentResult13.setOrderId("3021-12");
                cpOrderContentResultList2.add(cpOrderContentResult13);

                CPOrderContentResult cpOrderContentResult14 = new CPOrderContentResult();
                cpOrderContentResult14.setOrderName("13");
                cpOrderContentResult14.setFullName("冠、亚军和");
                cpOrderContentResult14.setOrderState(cpbjscResult.getdata_302113());
                cpOrderContentResult14.setOrderId("3021-13");
                cpOrderContentResultList2.add(cpOrderContentResult14);

                CPOrderContentResult cpOrderContentResult15 = new CPOrderContentResult();
                cpOrderContentResult15.setOrderName("14");
                cpOrderContentResult15.setFullName("冠、亚军和");
                cpOrderContentResult15.setOrderState(cpbjscResult.getdata_302114());
                cpOrderContentResult15.setOrderId("3021-14");
                cpOrderContentResultList2.add(cpOrderContentResult15);

                CPOrderContentResult cpOrderContentResult16 = new CPOrderContentResult();
                cpOrderContentResult16.setOrderName("15");
                cpOrderContentResult16.setFullName("冠、亚军和");
                cpOrderContentResult16.setOrderState(cpbjscResult.getdata_302115());
                cpOrderContentResult16.setOrderId("3021-15");
                cpOrderContentResultList2.add(cpOrderContentResult16);

                CPOrderContentResult cpOrderContentResult17 = new CPOrderContentResult();
                cpOrderContentResult17.setOrderName("16");
                cpOrderContentResult17.setFullName("冠、亚军和");
                cpOrderContentResult17.setOrderState(cpbjscResult.getdata_302116());
                cpOrderContentResult17.setOrderId("3021-16");
                cpOrderContentResultList2.add(cpOrderContentResult17);

                CPOrderContentResult cpOrderContentResult18 = new CPOrderContentResult();
                cpOrderContentResult18.setOrderName("17");
                cpOrderContentResult18.setFullName("冠、亚军和");
                cpOrderContentResult18.setOrderState(cpbjscResult.getdata_302117());
                cpOrderContentResult18.setOrderId("3021-17");
                cpOrderContentResultList2.add(cpOrderContentResult18);

                CPOrderContentListResult cpOrderContentListResult3 = new CPOrderContentListResult();
                cpOrderContentListResult3.setShowNumber(2);
                List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult19 = new CPOrderContentResult();
                cpOrderContentResult19.setOrderName("18");
                cpOrderContentResult19.setFullName("冠、亚军和");
                cpOrderContentResult19.setOrderState(cpbjscResult.getdata_302118());
                cpOrderContentResult19.setOrderId("3021-18");
                cpOrderContentResultList3.add(cpOrderContentResult19);

                CPOrderContentResult cpOrderContentResult20 = new CPOrderContentResult();
                cpOrderContentResult20.setOrderName("19");
                cpOrderContentResult20.setFullName("冠、亚军和");
                cpOrderContentResult20.setOrderState(cpbjscResult.getdata_302119());
                cpOrderContentResult20.setOrderId("3021-19");
                cpOrderContentResultList3.add(cpOrderContentResult20);
                cpOrderContentListResult3.setData(cpOrderContentResultList3);

                cpOrderContentListResult.setData(cpOrderContentResultList);
                cpOrderContentListResult2.setData(cpOrderContentResultList2);
                cpOrderContentListResult3.setData(cpOrderContentResultList3);

                cPOrderContentListResultAll.add(cpOrderContentListResult);
                cPOrderContentListResultAll.add(cpOrderContentListResult2);
                cPOrderContentListResultAll.add(cpOrderContentListResult3);

                allResult.setData(cPOrderContentListResultAll);
            }else if(k==2){
                allResult.setOrderAllName("1-5名");
                List<CPOrderContentListResult> cPOrderContentListResultAll = new ArrayList<CPOrderContentListResult>();
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("冠军");
                cpOrderContentListResult.setShowType("TU");
                cpOrderContentListResult.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList1 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("1");
                cpOrderContentResult1.setFullName("冠军");
                cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30011());
                cpOrderContentResult1.setOrderId("3001-1");
                cpOrderContentResultList1.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("2");
                cpOrderContentResult2.setFullName("冠军");
                cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30012());
                cpOrderContentResult2.setOrderId("3001-2");
                cpOrderContentResultList1.add(cpOrderContentResult2);


                CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                cpOrderContentResult3.setOrderName("3");
                cpOrderContentResult3.setFullName("冠军");
                cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30013());
                cpOrderContentResult3.setOrderId("3001-3");
                cpOrderContentResultList1.add(cpOrderContentResult3);

                CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                cpOrderContentResult4.setOrderName("4");
                cpOrderContentResult4.setFullName("冠军");
                cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30014());
                cpOrderContentResult4.setOrderId("3001-4");
                cpOrderContentResultList1.add(cpOrderContentResult4);

                CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                cpOrderContentResult5.setOrderName("5");
                cpOrderContentResult5.setFullName("冠军");
                cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30015());
                cpOrderContentResult5.setOrderId("3001-5");
                cpOrderContentResultList1.add(cpOrderContentResult5);

                CPOrderContentResult cpOrderContentResult6 = new CPOrderContentResult();
                cpOrderContentResult6.setOrderName("6");
                cpOrderContentResult6.setFullName("冠军");
                cpOrderContentResult6.setOrderState(cpbjscResult.getdata_30016());
                cpOrderContentResult6.setOrderId("3001-6");
                cpOrderContentResultList1.add(cpOrderContentResult6);

                CPOrderContentResult cpOrderContentResult7 = new CPOrderContentResult();
                cpOrderContentResult7.setOrderName("7");
                cpOrderContentResult7.setFullName("冠军");
                cpOrderContentResult7.setOrderState(cpbjscResult.getdata_30017());
                cpOrderContentResult7.setOrderId("3001-7");
                cpOrderContentResultList1.add(cpOrderContentResult7);

                CPOrderContentResult cpOrderContentResult8 = new CPOrderContentResult();
                cpOrderContentResult8.setOrderName("8");
                cpOrderContentResult8.setFullName("冠军");
                cpOrderContentResult8.setOrderState(cpbjscResult.getdata_30018());
                cpOrderContentResult8.setOrderId("3001-8");
                cpOrderContentResultList1.add(cpOrderContentResult8);

                CPOrderContentResult cpOrderContentResult9 = new CPOrderContentResult();
                cpOrderContentResult9.setOrderName("9");
                cpOrderContentResult9.setFullName("冠军");
                cpOrderContentResult9.setOrderState(cpbjscResult.getdata_30019());
                cpOrderContentResult9.setOrderId("3001-9");
                cpOrderContentResultList1.add(cpOrderContentResult9);


                CPOrderContentResult cpOrderContentResult10 = new CPOrderContentResult();
                cpOrderContentResult10.setOrderName("10");
                cpOrderContentResult10.setFullName("冠军");
                cpOrderContentResult10.setOrderState(cpbjscResult.getdata_300110());
                cpOrderContentResult10.setOrderId("3001-10");
                cpOrderContentResultList1.add(cpOrderContentResult10);

                cpOrderContentListResult.setData(cpOrderContentResultList1);

                CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                cpOrderContentListResult2.setOrderContentListName("亚军");
                cpOrderContentListResult2.setShowType("TU");
                cpOrderContentListResult2.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult21 = new CPOrderContentResult();
                cpOrderContentResult21.setOrderName("1");
                cpOrderContentResult21.setFullName("亚军");
                cpOrderContentResult21.setOrderState(cpbjscResult.getdata_30021());
                cpOrderContentResult21.setOrderId("3002-1");
                cpOrderContentResultList2.add(cpOrderContentResult21);

                CPOrderContentResult cpOrderContentResult22 = new CPOrderContentResult();
                cpOrderContentResult22.setOrderName("2");
                cpOrderContentResult22.setFullName("亚军");
                cpOrderContentResult22.setOrderState(cpbjscResult.getdata_30022());
                cpOrderContentResult22.setOrderId("3002-2");
                cpOrderContentResultList2.add(cpOrderContentResult22);


                CPOrderContentResult cpOrderContentResult23 = new CPOrderContentResult();
                cpOrderContentResult23.setOrderName("3");
                cpOrderContentResult23.setFullName("亚军");
                cpOrderContentResult23.setOrderState(cpbjscResult.getdata_30023());
                cpOrderContentResult23.setOrderId("3002-3");
                cpOrderContentResultList2.add(cpOrderContentResult23);

                CPOrderContentResult cpOrderContentResult24 = new CPOrderContentResult();
                cpOrderContentResult24.setOrderName("4");
                cpOrderContentResult24.setFullName("亚军");
                cpOrderContentResult24.setOrderState(cpbjscResult.getdata_30024());
                cpOrderContentResult24.setOrderId("3002-4");
                cpOrderContentResultList2.add(cpOrderContentResult24);

                CPOrderContentResult cpOrderContentResult25 = new CPOrderContentResult();
                cpOrderContentResult25.setOrderName("5");
                cpOrderContentResult25.setFullName("亚军");
                cpOrderContentResult25.setOrderState(cpbjscResult.getdata_30025());
                cpOrderContentResult25.setOrderId("3001-5");
                cpOrderContentResultList2.add(cpOrderContentResult25);

                CPOrderContentResult cpOrderContentResult26 = new CPOrderContentResult();
                cpOrderContentResult26.setOrderName("6");
                cpOrderContentResult26.setFullName("亚军");
                cpOrderContentResult26.setOrderState(cpbjscResult.getdata_30026());
                cpOrderContentResult26.setOrderId("3002-6");
                cpOrderContentResultList2.add(cpOrderContentResult26);

                CPOrderContentResult cpOrderContentResult27 = new CPOrderContentResult();
                cpOrderContentResult27.setOrderName("7");
                cpOrderContentResult27.setFullName("亚军");
                cpOrderContentResult27.setOrderState(cpbjscResult.getdata_30027());
                cpOrderContentResult27.setOrderId("3002-7");
                cpOrderContentResultList2.add(cpOrderContentResult27);

                CPOrderContentResult cpOrderContentResult28 = new CPOrderContentResult();
                cpOrderContentResult28.setOrderName("8");
                cpOrderContentResult28.setFullName("亚军");
                cpOrderContentResult28.setOrderState(cpbjscResult.getdata_30028());
                cpOrderContentResult28.setOrderId("3002-8");
                cpOrderContentResultList2.add(cpOrderContentResult28);

                CPOrderContentResult cpOrderContentResult29 = new CPOrderContentResult();
                cpOrderContentResult29.setOrderName("9");
                cpOrderContentResult29.setFullName("亚军");
                cpOrderContentResult29.setOrderState(cpbjscResult.getdata_30029());
                cpOrderContentResult29.setOrderId("3002-9");
                cpOrderContentResultList2.add(cpOrderContentResult29);


                CPOrderContentResult cpOrderContentResult210 = new CPOrderContentResult();
                cpOrderContentResult210.setOrderName("10");
                cpOrderContentResult210.setFullName("亚军");
                cpOrderContentResult210.setOrderState(cpbjscResult.getdata_300210());
                cpOrderContentResult210.setOrderId("3002-10");
                cpOrderContentResultList2.add(cpOrderContentResult210);

                cpOrderContentListResult2.setData(cpOrderContentResultList2);

                CPOrderContentListResult cpOrderContentListResult3 = new CPOrderContentListResult();
                cpOrderContentListResult3.setOrderContentListName("第三名");
                cpOrderContentListResult3.setShowType("TU");
                cpOrderContentListResult3.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult31 = new CPOrderContentResult();
                cpOrderContentResult31.setOrderName("1");
                cpOrderContentResult31.setFullName("第三名");
                cpOrderContentResult31.setOrderState(cpbjscResult.getdata_30031());
                cpOrderContentResult31.setOrderId("3003-1");
                cpOrderContentResultList3.add(cpOrderContentResult31);

                CPOrderContentResult cpOrderContentResult32 = new CPOrderContentResult();
                cpOrderContentResult32.setOrderName("2");
                cpOrderContentResult32.setFullName("第三名");
                cpOrderContentResult32.setOrderState(cpbjscResult.getdata_30032());
                cpOrderContentResult32.setOrderId("3003-2");
                cpOrderContentResultList3.add(cpOrderContentResult32);

                CPOrderContentResult cpOrderContentResult33 = new CPOrderContentResult();
                cpOrderContentResult33.setOrderName("3");
                cpOrderContentResult33.setFullName("第三名");
                cpOrderContentResult33.setOrderState(cpbjscResult.getdata_30033());
                cpOrderContentResult33.setOrderId("3003-3");
                cpOrderContentResultList3.add(cpOrderContentResult33);

                CPOrderContentResult cpOrderContentResult34 = new CPOrderContentResult();
                cpOrderContentResult34.setOrderName("4");
                cpOrderContentResult34.setFullName("第三名");
                cpOrderContentResult34.setOrderState(cpbjscResult.getdata_30034());
                cpOrderContentResult34.setOrderId("3003-4");
                cpOrderContentResultList3.add(cpOrderContentResult34);

                CPOrderContentResult cpOrderContentResult35 = new CPOrderContentResult();
                cpOrderContentResult35.setOrderName("5");
                cpOrderContentResult35.setFullName("第三名");
                cpOrderContentResult35.setOrderState(cpbjscResult.getdata_30035());
                cpOrderContentResult35.setOrderId("3003-5");
                cpOrderContentResultList3.add(cpOrderContentResult35);

                CPOrderContentResult cpOrderContentResult36 = new CPOrderContentResult();
                cpOrderContentResult36.setOrderName("6");
                cpOrderContentResult36.setFullName("第三名");
                cpOrderContentResult36.setOrderState(cpbjscResult.getdata_30036());
                cpOrderContentResult36.setOrderId("3003-6");
                cpOrderContentResultList3.add(cpOrderContentResult36);

                CPOrderContentResult cpOrderContentResult37 = new CPOrderContentResult();
                cpOrderContentResult37.setOrderName("7");
                cpOrderContentResult37.setFullName("第三名");
                cpOrderContentResult37.setOrderState(cpbjscResult.getdata_30037());
                cpOrderContentResult37.setOrderId("3003-7");
                cpOrderContentResultList3.add(cpOrderContentResult37);

                CPOrderContentResult cpOrderContentResult38 = new CPOrderContentResult();
                cpOrderContentResult38.setOrderName("8");
                cpOrderContentResult38.setFullName("第三名");
                cpOrderContentResult38.setOrderState(cpbjscResult.getdata_30038());
                cpOrderContentResult38.setOrderId("3003-8");
                cpOrderContentResultList3.add(cpOrderContentResult38);

                CPOrderContentResult cpOrderContentResult39 = new CPOrderContentResult();
                cpOrderContentResult39.setOrderName("9");
                cpOrderContentResult39.setFullName("第三名");
                cpOrderContentResult39.setOrderState(cpbjscResult.getdata_30039());
                cpOrderContentResult39.setOrderId("3003-9");
                cpOrderContentResultList3.add(cpOrderContentResult39);


                CPOrderContentResult cpOrderContentResult310 = new CPOrderContentResult();
                cpOrderContentResult310.setOrderName("10");
                cpOrderContentResult310.setFullName("第三名");
                cpOrderContentResult310.setOrderState(cpbjscResult.getdata_300310());
                cpOrderContentResult310.setOrderId("3003-10");
                cpOrderContentResultList3.add(cpOrderContentResult310);

                cpOrderContentListResult3.setData(cpOrderContentResultList3);

                CPOrderContentListResult cpOrderContentListResult4 = new CPOrderContentListResult();
                cpOrderContentListResult4.setOrderContentListName("第四名");
                cpOrderContentListResult4.setShowType("TU");
                cpOrderContentListResult4.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult41 = new CPOrderContentResult();
                cpOrderContentResult41.setOrderName("1");
                cpOrderContentResult41.setFullName("第四名");
                cpOrderContentResult41.setOrderState(cpbjscResult.getdata_30041());
                cpOrderContentResult41.setOrderId("3004-1");
                cpOrderContentResultList4.add(cpOrderContentResult41);

                CPOrderContentResult cpOrderContentResult42 = new CPOrderContentResult();
                cpOrderContentResult42.setOrderName("2");
                cpOrderContentResult42.setFullName("第四名");
                cpOrderContentResult42.setOrderState(cpbjscResult.getdata_30042());
                cpOrderContentResult42.setOrderId("3004-2");
                cpOrderContentResultList4.add(cpOrderContentResult42);


                CPOrderContentResult cpOrderContentResult43 = new CPOrderContentResult();
                cpOrderContentResult43.setOrderName("3");
                cpOrderContentResult43.setFullName("第四名");
                cpOrderContentResult43.setOrderState(cpbjscResult.getdata_30043());
                cpOrderContentResult43.setOrderId("3004-3");
                cpOrderContentResultList4.add(cpOrderContentResult43);

                CPOrderContentResult cpOrderContentResult44 = new CPOrderContentResult();
                cpOrderContentResult44.setOrderName("4");
                cpOrderContentResult44.setFullName("第四名");
                cpOrderContentResult44.setOrderState(cpbjscResult.getdata_30044());
                cpOrderContentResult44.setOrderId("3004-4");
                cpOrderContentResultList4.add(cpOrderContentResult44);

                CPOrderContentResult cpOrderContentResult45 = new CPOrderContentResult();
                cpOrderContentResult45.setOrderName("5");
                cpOrderContentResult45.setFullName("第四名");
                cpOrderContentResult45.setOrderState(cpbjscResult.getdata_30045());
                cpOrderContentResult45.setOrderId("3004-5");
                cpOrderContentResultList4.add(cpOrderContentResult45);

                CPOrderContentResult cpOrderContentResult46 = new CPOrderContentResult();
                cpOrderContentResult46.setOrderName("6");
                cpOrderContentResult46.setFullName("第四名");
                cpOrderContentResult46.setOrderState(cpbjscResult.getdata_30046());
                cpOrderContentResult46.setOrderId("3004-6");
                cpOrderContentResultList4.add(cpOrderContentResult46);

                CPOrderContentResult cpOrderContentResult47 = new CPOrderContentResult();
                cpOrderContentResult47.setOrderName("7");
                cpOrderContentResult47.setFullName("第四名");
                cpOrderContentResult47.setOrderState(cpbjscResult.getdata_30047());
                cpOrderContentResult47.setOrderId("3004-7");
                cpOrderContentResultList4.add(cpOrderContentResult47);

                CPOrderContentResult cpOrderContentResult48 = new CPOrderContentResult();
                cpOrderContentResult48.setOrderName("8");
                cpOrderContentResult48.setFullName("第四名");
                cpOrderContentResult48.setOrderState(cpbjscResult.getdata_30048());
                cpOrderContentResult48.setOrderId("3004-8");
                cpOrderContentResultList4.add(cpOrderContentResult48);

                CPOrderContentResult cpOrderContentResult49 = new CPOrderContentResult();
                cpOrderContentResult49.setOrderName("9");
                cpOrderContentResult49.setFullName("第四名");
                cpOrderContentResult49.setOrderState(cpbjscResult.getdata_30049());
                cpOrderContentResult49.setOrderId("3004-9");
                cpOrderContentResultList4.add(cpOrderContentResult49);


                CPOrderContentResult cpOrderContentResult410 = new CPOrderContentResult();
                cpOrderContentResult410.setOrderName("10");
                cpOrderContentResult410.setFullName("第四名");
                cpOrderContentResult410.setOrderState(cpbjscResult.getdata_300410());
                cpOrderContentResult410.setOrderId("3004-10");
                cpOrderContentResultList4.add(cpOrderContentResult410);

                cpOrderContentListResult4.setData(cpOrderContentResultList4);

                CPOrderContentListResult cpOrderContentListResult5 = new CPOrderContentListResult();
                cpOrderContentListResult5.setOrderContentListName("第五名");
                cpOrderContentListResult5.setShowType("TU");
                cpOrderContentListResult5.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult51 = new CPOrderContentResult();
                cpOrderContentResult51.setOrderName("1");
                cpOrderContentResult51.setFullName("第五名");
                cpOrderContentResult51.setOrderState(cpbjscResult.getdata_30051());
                cpOrderContentResult51.setOrderId("3005-1");
                cpOrderContentResultList5.add(cpOrderContentResult51);

                CPOrderContentResult cpOrderContentResult52 = new CPOrderContentResult();
                cpOrderContentResult52.setOrderName("2");
                cpOrderContentResult52.setFullName("第五名");
                cpOrderContentResult52.setOrderState(cpbjscResult.getdata_30052());
                cpOrderContentResult52.setOrderId("3005-2");
                cpOrderContentResultList5.add(cpOrderContentResult52);


                CPOrderContentResult cpOrderContentResult53 = new CPOrderContentResult();
                cpOrderContentResult53.setOrderName("3");
                cpOrderContentResult53.setFullName("第五名");
                cpOrderContentResult53.setOrderState(cpbjscResult.getdata_30053());
                cpOrderContentResult53.setOrderId("3005-3");
                cpOrderContentResultList5.add(cpOrderContentResult53);

                CPOrderContentResult cpOrderContentResult54 = new CPOrderContentResult();
                cpOrderContentResult54.setOrderName("4");
                cpOrderContentResult54.setFullName("第五名");
                cpOrderContentResult54.setOrderState(cpbjscResult.getdata_30054());
                cpOrderContentResult54.setOrderId("3005-4");
                cpOrderContentResultList5.add(cpOrderContentResult54);

                CPOrderContentResult cpOrderContentResult55 = new CPOrderContentResult();
                cpOrderContentResult55.setOrderName("5");
                cpOrderContentResult55.setFullName("第五名");
                cpOrderContentResult55.setOrderState(cpbjscResult.getdata_30055());
                cpOrderContentResult55.setOrderId("3005-5");
                cpOrderContentResultList5.add(cpOrderContentResult55);

                CPOrderContentResult cpOrderContentResult56 = new CPOrderContentResult();
                cpOrderContentResult56.setOrderName("6");
                cpOrderContentResult56.setFullName("第五名");
                cpOrderContentResult56.setOrderState(cpbjscResult.getdata_30056());
                cpOrderContentResult56.setOrderId("3005-6");
                cpOrderContentResultList5.add(cpOrderContentResult56);

                CPOrderContentResult cpOrderContentResult57 = new CPOrderContentResult();
                cpOrderContentResult57.setOrderName("7");
                cpOrderContentResult57.setFullName("第五名");
                cpOrderContentResult57.setOrderState(cpbjscResult.getdata_30057());
                cpOrderContentResult57.setOrderId("3005-7");
                cpOrderContentResultList5.add(cpOrderContentResult57);

                CPOrderContentResult cpOrderContentResult58 = new CPOrderContentResult();
                cpOrderContentResult58.setOrderName("8");
                cpOrderContentResult58.setFullName("第五名");
                cpOrderContentResult58.setOrderState(cpbjscResult.getdata_30058());
                cpOrderContentResult58.setOrderId("3005-8");
                cpOrderContentResultList5.add(cpOrderContentResult58);

                CPOrderContentResult cpOrderContentResult59 = new CPOrderContentResult();
                cpOrderContentResult59.setOrderName("9");
                cpOrderContentResult59.setFullName("第五名");
                cpOrderContentResult59.setOrderState(cpbjscResult.getdata_30059());
                cpOrderContentResult59.setOrderId("3005-9");
                cpOrderContentResultList5.add(cpOrderContentResult59);


                CPOrderContentResult cpOrderContentResult510 = new CPOrderContentResult();
                cpOrderContentResult510.setOrderName("10");
                cpOrderContentResult510.setFullName("第五名");
                cpOrderContentResult510.setOrderState(cpbjscResult.getdata_300410());
                cpOrderContentResult510.setOrderId("3004-10");
                cpOrderContentResultList5.add(cpOrderContentResult510);

                cpOrderContentListResult5.setData(cpOrderContentResultList5);

                cPOrderContentListResultAll.add(cpOrderContentListResult);
                cPOrderContentListResultAll.add(cpOrderContentListResult2);
                cPOrderContentListResultAll.add(cpOrderContentListResult3);
                cPOrderContentListResultAll.add(cpOrderContentListResult4);
                cPOrderContentListResultAll.add(cpOrderContentListResult5);
                allResult.setData(cPOrderContentListResultAll);
            }else{
                allResult.setOrderAllName("6-10名");
                List<CPOrderContentListResult> cPOrderContentListResultAll = new ArrayList<CPOrderContentListResult>();
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("第六名");
                cpOrderContentListResult.setShowType("TU");
                cpOrderContentListResult.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList1 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("1");
                cpOrderContentResult1.setFullName("第六名");
                cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30061());
                cpOrderContentResult1.setOrderId("3006-1");
                cpOrderContentResultList1.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("2");
                cpOrderContentResult2.setFullName("第六名");
                cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30062());
                cpOrderContentResult2.setOrderId("3006-2");
                cpOrderContentResultList1.add(cpOrderContentResult2);


                CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                cpOrderContentResult3.setOrderName("3");
                cpOrderContentResult3.setFullName("第六名");
                cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30063());
                cpOrderContentResult3.setOrderId("3006-3");
                cpOrderContentResultList1.add(cpOrderContentResult3);

                CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                cpOrderContentResult4.setOrderName("4");
                cpOrderContentResult4.setFullName("第六名");
                cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30064());
                cpOrderContentResult4.setOrderId("3006-4");
                cpOrderContentResultList1.add(cpOrderContentResult4);

                CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                cpOrderContentResult5.setOrderName("5");
                cpOrderContentResult5.setFullName("第六名");
                cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30065());
                cpOrderContentResult5.setOrderId("3006-5");
                cpOrderContentResultList1.add(cpOrderContentResult5);

                CPOrderContentResult cpOrderContentResult6 = new CPOrderContentResult();
                cpOrderContentResult6.setOrderName("6");
                cpOrderContentResult6.setFullName("第六名");
                cpOrderContentResult6.setOrderState(cpbjscResult.getdata_30066());
                cpOrderContentResult6.setOrderId("3006-6");
                cpOrderContentResultList1.add(cpOrderContentResult6);

                CPOrderContentResult cpOrderContentResult7 = new CPOrderContentResult();
                cpOrderContentResult7.setOrderName("7");
                cpOrderContentResult7.setFullName("第六名");
                cpOrderContentResult7.setOrderState(cpbjscResult.getdata_30067());
                cpOrderContentResult7.setOrderId("3006-7");
                cpOrderContentResultList1.add(cpOrderContentResult7);

                CPOrderContentResult cpOrderContentResult8 = new CPOrderContentResult();
                cpOrderContentResult8.setOrderName("8");
                cpOrderContentResult8.setFullName("第六名");
                cpOrderContentResult8.setOrderState(cpbjscResult.getdata_30068());
                cpOrderContentResult8.setOrderId("3006-8");
                cpOrderContentResultList1.add(cpOrderContentResult8);

                CPOrderContentResult cpOrderContentResult9 = new CPOrderContentResult();
                cpOrderContentResult9.setOrderName("9");
                cpOrderContentResult9.setFullName("第六名");
                cpOrderContentResult9.setOrderState(cpbjscResult.getdata_30069());
                cpOrderContentResult9.setOrderId("3006-9");
                cpOrderContentResultList1.add(cpOrderContentResult9);


                CPOrderContentResult cpOrderContentResult10 = new CPOrderContentResult();
                cpOrderContentResult10.setOrderName("10");
                cpOrderContentResult10.setFullName("第六名");
                cpOrderContentResult10.setOrderState(cpbjscResult.getdata_300610());
                cpOrderContentResult10.setOrderId("3006-10");
                cpOrderContentResultList1.add(cpOrderContentResult10);

                cpOrderContentListResult.setData(cpOrderContentResultList1);

                CPOrderContentListResult cpOrderContentListResult2 = new CPOrderContentListResult();
                cpOrderContentListResult2.setOrderContentListName("第七名");
                cpOrderContentListResult2.setShowType("TU");
                cpOrderContentListResult2.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult21 = new CPOrderContentResult();
                cpOrderContentResult21.setOrderName("1");
                cpOrderContentResult21.setFullName("第七名");
                cpOrderContentResult21.setOrderState(cpbjscResult.getdata_30071());
                cpOrderContentResult21.setOrderId("3007-1");
                cpOrderContentResultList2.add(cpOrderContentResult21);

                CPOrderContentResult cpOrderContentResult22 = new CPOrderContentResult();
                cpOrderContentResult22.setOrderName("2");
                cpOrderContentResult22.setFullName("第七名");
                cpOrderContentResult22.setOrderState(cpbjscResult.getdata_30072());
                cpOrderContentResult22.setOrderId("3007-2");
                cpOrderContentResultList2.add(cpOrderContentResult22);


                CPOrderContentResult cpOrderContentResult23 = new CPOrderContentResult();
                cpOrderContentResult23.setOrderName("3");
                cpOrderContentResult23.setFullName("第七名");
                cpOrderContentResult23.setOrderState(cpbjscResult.getdata_30073());
                cpOrderContentResult23.setOrderId("3007-3");
                cpOrderContentResultList2.add(cpOrderContentResult23);

                CPOrderContentResult cpOrderContentResult24 = new CPOrderContentResult();
                cpOrderContentResult24.setOrderName("4");
                cpOrderContentResult24.setFullName("第七名");
                cpOrderContentResult24.setOrderState(cpbjscResult.getdata_30074());
                cpOrderContentResult24.setOrderId("3007-4");
                cpOrderContentResultList2.add(cpOrderContentResult24);

                CPOrderContentResult cpOrderContentResult25 = new CPOrderContentResult();
                cpOrderContentResult25.setOrderName("5");
                cpOrderContentResult25.setFullName("第七名");
                cpOrderContentResult25.setOrderState(cpbjscResult.getdata_30075());
                cpOrderContentResult25.setOrderId("3007-5");
                cpOrderContentResultList2.add(cpOrderContentResult25);

                CPOrderContentResult cpOrderContentResult26 = new CPOrderContentResult();
                cpOrderContentResult26.setOrderName("6");
                cpOrderContentResult26.setFullName("第七名");
                cpOrderContentResult26.setOrderState(cpbjscResult.getdata_30076());
                cpOrderContentResult26.setOrderId("3007-6");
                cpOrderContentResultList2.add(cpOrderContentResult26);

                CPOrderContentResult cpOrderContentResult27 = new CPOrderContentResult();
                cpOrderContentResult27.setOrderName("7");
                cpOrderContentResult27.setFullName("第七名");
                cpOrderContentResult27.setOrderState(cpbjscResult.getdata_30077());
                cpOrderContentResult27.setOrderId("3007-7");
                cpOrderContentResultList2.add(cpOrderContentResult27);

                CPOrderContentResult cpOrderContentResult28 = new CPOrderContentResult();
                cpOrderContentResult28.setOrderName("8");
                cpOrderContentResult28.setFullName("第七名");
                cpOrderContentResult28.setOrderState(cpbjscResult.getdata_30078());
                cpOrderContentResult28.setOrderId("3007-8");
                cpOrderContentResultList2.add(cpOrderContentResult28);

                CPOrderContentResult cpOrderContentResult29 = new CPOrderContentResult();
                cpOrderContentResult29.setOrderName("9");
                cpOrderContentResult29.setFullName("第七名");
                cpOrderContentResult29.setOrderState(cpbjscResult.getdata_30079());
                cpOrderContentResult29.setOrderId("3007-9");
                cpOrderContentResultList2.add(cpOrderContentResult29);


                CPOrderContentResult cpOrderContentResult210 = new CPOrderContentResult();
                cpOrderContentResult210.setOrderName("10");
                cpOrderContentResult210.setFullName("第七名");
                cpOrderContentResult210.setOrderState(cpbjscResult.getdata_300710());
                cpOrderContentResult210.setOrderId("3007-10");
                cpOrderContentResultList2.add(cpOrderContentResult210);

                cpOrderContentListResult2.setData(cpOrderContentResultList2);

                CPOrderContentListResult cpOrderContentListResult3 = new CPOrderContentListResult();
                cpOrderContentListResult3.setOrderContentListName("第八名");
                cpOrderContentListResult3.setShowType("TU");
                cpOrderContentListResult3.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult31 = new CPOrderContentResult();
                cpOrderContentResult31.setOrderName("1");
                cpOrderContentResult31.setFullName("第八名");
                cpOrderContentResult31.setOrderState(cpbjscResult.getdata_30081());
                cpOrderContentResult31.setOrderId("3008-1");
                cpOrderContentResultList3.add(cpOrderContentResult31);

                CPOrderContentResult cpOrderContentResult32 = new CPOrderContentResult();
                cpOrderContentResult32.setOrderName("2");
                cpOrderContentResult32.setFullName("第八名");
                cpOrderContentResult32.setOrderState(cpbjscResult.getdata_30082());
                cpOrderContentResult32.setOrderId("3008-2");
                cpOrderContentResultList3.add(cpOrderContentResult32);

                CPOrderContentResult cpOrderContentResult33 = new CPOrderContentResult();
                cpOrderContentResult33.setOrderName("3");
                cpOrderContentResult33.setFullName("第八名");
                cpOrderContentResult33.setOrderState(cpbjscResult.getdata_30083());
                cpOrderContentResult33.setOrderId("3008-3");
                cpOrderContentResultList3.add(cpOrderContentResult33);

                CPOrderContentResult cpOrderContentResult34 = new CPOrderContentResult();
                cpOrderContentResult34.setOrderName("4");
                cpOrderContentResult34.setFullName("第八名");
                cpOrderContentResult34.setOrderState(cpbjscResult.getdata_30084());
                cpOrderContentResult34.setOrderId("3008-4");
                cpOrderContentResultList3.add(cpOrderContentResult34);

                CPOrderContentResult cpOrderContentResult35 = new CPOrderContentResult();
                cpOrderContentResult35.setOrderName("5");
                cpOrderContentResult35.setFullName("第八名");
                cpOrderContentResult35.setOrderState(cpbjscResult.getdata_30085());
                cpOrderContentResult35.setOrderId("3008-5");
                cpOrderContentResultList3.add(cpOrderContentResult35);

                CPOrderContentResult cpOrderContentResult36 = new CPOrderContentResult();
                cpOrderContentResult36.setOrderName("6");
                cpOrderContentResult36.setFullName("第八名");
                cpOrderContentResult36.setOrderState(cpbjscResult.getdata_30086());
                cpOrderContentResult36.setOrderId("3008-6");
                cpOrderContentResultList3.add(cpOrderContentResult36);

                CPOrderContentResult cpOrderContentResult37 = new CPOrderContentResult();
                cpOrderContentResult37.setOrderName("7");
                cpOrderContentResult37.setFullName("第八名");
                cpOrderContentResult37.setOrderState(cpbjscResult.getdata_30037());
                cpOrderContentResult37.setOrderId("3003-7");
                cpOrderContentResultList3.add(cpOrderContentResult37);

                CPOrderContentResult cpOrderContentResult38 = new CPOrderContentResult();
                cpOrderContentResult38.setOrderName("8");
                cpOrderContentResult38.setFullName("第八名");
                cpOrderContentResult38.setOrderState(cpbjscResult.getdata_30088());
                cpOrderContentResult38.setOrderId("3008-8");
                cpOrderContentResultList3.add(cpOrderContentResult38);

                CPOrderContentResult cpOrderContentResult39 = new CPOrderContentResult();
                cpOrderContentResult39.setOrderName("9");
                cpOrderContentResult39.setFullName("第八名");
                cpOrderContentResult39.setOrderState(cpbjscResult.getdata_30089());
                cpOrderContentResult39.setOrderId("3008-9");
                cpOrderContentResultList3.add(cpOrderContentResult39);


                CPOrderContentResult cpOrderContentResult310 = new CPOrderContentResult();
                cpOrderContentResult310.setOrderName("10");
                cpOrderContentResult310.setFullName("第八名");
                cpOrderContentResult310.setOrderState(cpbjscResult.getdata_300810());
                cpOrderContentResult310.setOrderId("3008-10");
                cpOrderContentResultList3.add(cpOrderContentResult310);

                cpOrderContentListResult3.setData(cpOrderContentResultList3);

                CPOrderContentListResult cpOrderContentListResult4 = new CPOrderContentListResult();
                cpOrderContentListResult4.setOrderContentListName("第九名");
                cpOrderContentListResult4.setShowType("TU");
                cpOrderContentListResult4.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult41 = new CPOrderContentResult();
                cpOrderContentResult41.setOrderName("1");
                cpOrderContentResult41.setFullName("第九名");
                cpOrderContentResult41.setOrderState(cpbjscResult.getdata_30091());
                cpOrderContentResult41.setOrderId("3009-1");
                cpOrderContentResultList4.add(cpOrderContentResult41);

                CPOrderContentResult cpOrderContentResult42 = new CPOrderContentResult();
                cpOrderContentResult42.setOrderName("2");
                cpOrderContentResult42.setFullName("第九名");
                cpOrderContentResult42.setOrderState(cpbjscResult.getdata_30092());
                cpOrderContentResult42.setOrderId("3009-2");
                cpOrderContentResultList4.add(cpOrderContentResult42);


                CPOrderContentResult cpOrderContentResult43 = new CPOrderContentResult();
                cpOrderContentResult43.setOrderName("3");
                cpOrderContentResult43.setFullName("第九名");
                cpOrderContentResult43.setOrderState(cpbjscResult.getdata_30093());
                cpOrderContentResult43.setOrderId("3009-3");
                cpOrderContentResultList4.add(cpOrderContentResult43);

                CPOrderContentResult cpOrderContentResult44 = new CPOrderContentResult();
                cpOrderContentResult44.setOrderName("4");
                cpOrderContentResult44.setFullName("第九名");
                cpOrderContentResult44.setOrderState(cpbjscResult.getdata_30094());
                cpOrderContentResult44.setOrderId("3009-4");
                cpOrderContentResultList4.add(cpOrderContentResult44);

                CPOrderContentResult cpOrderContentResult45 = new CPOrderContentResult();
                cpOrderContentResult45.setOrderName("5");
                cpOrderContentResult45.setFullName("第九名");
                cpOrderContentResult45.setOrderState(cpbjscResult.getdata_30095());
                cpOrderContentResult45.setOrderId("3009-5");
                cpOrderContentResultList4.add(cpOrderContentResult45);

                CPOrderContentResult cpOrderContentResult46 = new CPOrderContentResult();
                cpOrderContentResult46.setOrderName("6");
                cpOrderContentResult46.setFullName("第九名");
                cpOrderContentResult46.setOrderState(cpbjscResult.getdata_30096());
                cpOrderContentResult46.setOrderId("3009-6");
                cpOrderContentResultList4.add(cpOrderContentResult46);

                CPOrderContentResult cpOrderContentResult47 = new CPOrderContentResult();
                cpOrderContentResult47.setOrderName("7");
                cpOrderContentResult47.setFullName("第九名");
                cpOrderContentResult47.setOrderState(cpbjscResult.getdata_30097());
                cpOrderContentResult47.setOrderId("3009-7");
                cpOrderContentResultList4.add(cpOrderContentResult47);

                CPOrderContentResult cpOrderContentResult48 = new CPOrderContentResult();
                cpOrderContentResult48.setOrderName("8");
                cpOrderContentResult48.setFullName("第九名");
                cpOrderContentResult48.setOrderState(cpbjscResult.getdata_30098());
                cpOrderContentResult48.setOrderId("3009-8");
                cpOrderContentResultList4.add(cpOrderContentResult48);

                CPOrderContentResult cpOrderContentResult49 = new CPOrderContentResult();
                cpOrderContentResult49.setOrderName("9");
                cpOrderContentResult49.setFullName("第九名");
                cpOrderContentResult49.setOrderState(cpbjscResult.getdata_30099());
                cpOrderContentResult49.setOrderId("3009-9");
                cpOrderContentResultList4.add(cpOrderContentResult49);


                CPOrderContentResult cpOrderContentResult410 = new CPOrderContentResult();
                cpOrderContentResult410.setOrderName("10");
                cpOrderContentResult410.setFullName("第九名");
                cpOrderContentResult410.setOrderState(cpbjscResult.getdata_300910());
                cpOrderContentResult410.setOrderId("3009-10");
                cpOrderContentResultList4.add(cpOrderContentResult410);

                cpOrderContentListResult4.setData(cpOrderContentResultList4);

                CPOrderContentListResult cpOrderContentListResult5 = new CPOrderContentListResult();
                cpOrderContentListResult5.setOrderContentListName("第十名");
                cpOrderContentListResult5.setShowType("TU");
                cpOrderContentListResult5.setShowNumber(2);

                List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult51 = new CPOrderContentResult();
                cpOrderContentResult51.setOrderName("1");
                cpOrderContentResult51.setFullName("第十名");
                cpOrderContentResult51.setOrderState(cpbjscResult.getdata_30101());
                cpOrderContentResult51.setOrderId("3010-1");
                cpOrderContentResultList5.add(cpOrderContentResult51);

                CPOrderContentResult cpOrderContentResult52 = new CPOrderContentResult();
                cpOrderContentResult52.setOrderName("2");
                cpOrderContentResult52.setFullName("第十名");
                cpOrderContentResult52.setOrderState(cpbjscResult.getdata_30102());
                cpOrderContentResult52.setOrderId("3010-2");
                cpOrderContentResultList5.add(cpOrderContentResult52);


                CPOrderContentResult cpOrderContentResult53 = new CPOrderContentResult();
                cpOrderContentResult53.setOrderName("3");
                cpOrderContentResult53.setFullName("第十名");
                cpOrderContentResult53.setOrderState(cpbjscResult.getdata_30103());
                cpOrderContentResult53.setOrderId("3010-3");
                cpOrderContentResultList5.add(cpOrderContentResult53);

                CPOrderContentResult cpOrderContentResult54 = new CPOrderContentResult();
                cpOrderContentResult54.setOrderName("4");
                cpOrderContentResult54.setFullName("第十名");
                cpOrderContentResult54.setOrderState(cpbjscResult.getdata_30104());
                cpOrderContentResult54.setOrderId("3010-4");
                cpOrderContentResultList5.add(cpOrderContentResult54);

                CPOrderContentResult cpOrderContentResult55 = new CPOrderContentResult();
                cpOrderContentResult55.setOrderName("5");
                cpOrderContentResult55.setFullName("第十名");
                cpOrderContentResult55.setOrderState(cpbjscResult.getdata_30105());
                cpOrderContentResult55.setOrderId("3010-5");
                cpOrderContentResultList5.add(cpOrderContentResult55);

                CPOrderContentResult cpOrderContentResult56 = new CPOrderContentResult();
                cpOrderContentResult56.setOrderName("6");
                cpOrderContentResult56.setFullName("第十名");
                cpOrderContentResult56.setOrderState(cpbjscResult.getdata_30106());
                cpOrderContentResult56.setOrderId("3010-6");
                cpOrderContentResultList5.add(cpOrderContentResult56);

                CPOrderContentResult cpOrderContentResult57 = new CPOrderContentResult();
                cpOrderContentResult57.setOrderName("7");
                cpOrderContentResult57.setFullName("第十名");
                cpOrderContentResult57.setOrderState(cpbjscResult.getdata_30107());
                cpOrderContentResult57.setOrderId("3010-7");
                cpOrderContentResultList5.add(cpOrderContentResult57);

                CPOrderContentResult cpOrderContentResult58 = new CPOrderContentResult();
                cpOrderContentResult58.setOrderName("8");
                cpOrderContentResult58.setFullName("第十名");
                cpOrderContentResult58.setOrderState(cpbjscResult.getdata_30108());
                cpOrderContentResult58.setOrderId("3010-8");
                cpOrderContentResultList5.add(cpOrderContentResult58);

                CPOrderContentResult cpOrderContentResult59 = new CPOrderContentResult();
                cpOrderContentResult59.setOrderName("9");
                cpOrderContentResult59.setFullName("第十名");
                cpOrderContentResult59.setOrderState(cpbjscResult.getdata_30109());
                cpOrderContentResult59.setOrderId("3010-9");
                cpOrderContentResultList5.add(cpOrderContentResult59);


                CPOrderContentResult cpOrderContentResult510 = new CPOrderContentResult();
                cpOrderContentResult510.setOrderName("10");
                cpOrderContentResult510.setFullName("第十名");
                cpOrderContentResult510.setOrderState(cpbjscResult.getdata_301010());
                cpOrderContentResult510.setOrderId("3010-10");
                cpOrderContentResultList5.add(cpOrderContentResult510);

                cpOrderContentListResult5.setData(cpOrderContentResultList5);

                cPOrderContentListResultAll.add(cpOrderContentListResult);
                cPOrderContentListResultAll.add(cpOrderContentListResult2);
                cPOrderContentListResultAll.add(cpOrderContentListResult3);
                cPOrderContentListResultAll.add(cpOrderContentListResult4);
                cPOrderContentListResultAll.add(cpOrderContentListResult5);
                allResult.setData(cPOrderContentListResultAll);
            }
            allResultList.add(allResult);

        }
    }


    public String getFromAssets(String fileName) {
        try {
            InputStreamReader inputReader = new InputStreamReader(getResources().getAssets().open(fileName));
            BufferedReader bufReader = new BufferedReader(inputReader);
            String line = "";
            String Result = "";
            while ((line = bufReader.readLine()) != null)
                Result += line;
            return Result;
        } catch (Exception e) {
            e.printStackTrace();
        }
        return "";
    }

    private void showLeftMenu(){
        CPLeftMenuFragment cpLeftMenuFragment = CPLeftMenuFragment.newInstance(Arrays.asList("1","2","3"));
//        MenuLeftFragment  menuLeftFragment = new MenuLeftFragment();
        getSupportFragmentManager().beginTransaction()
                .replace(R.id.left_menu_frame_id, cpLeftMenuFragment).commit();
        slidingLeftMenu = getSlidingMenu();
        slidingLeftMenu.setMode(SlidingMenu.LEFT);
        // 设置触摸屏幕的模式
        slidingLeftMenu.setTouchModeAbove(SlidingMenu.TOUCHMODE_FULLSCREEN);
        slidingLeftMenu.setShadowWidthRes(R.dimen.shadow_width);
        slidingLeftMenu.setShadowDrawable(R.color.cp_order_bg);

        // 设置滑动菜单视图的宽度
        slidingLeftMenu.setBehindOffsetRes(R.dimen.slidingmenu_offset);
        // 设置渐入渐出效果的值
        slidingLeftMenu.setFadeDegree(0.35f);
        /**
         * SLIDING_WINDOW will include the Title/ActionBar in the content
         * section of the SlidingMenu, while SLIDING_CONTENT does not.
         */
//        slidingLeftMenu.attachToActivity(this, SlidingMenu.SLIDING_CONTENT);
//        //为侧滑菜单设置布局
        /*slidingLeftMenu.setMenu(R.layout.left_menu);
        RecyclerView recyclerView = slidingLeftMenu.getMenu().findViewById(R.id.cpOrderGameList);
        LinearLayoutManager linearLayoutManagerRight = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        recyclerView.setLayoutManager(linearLayoutManagerRight);
        recyclerView.setHasFixedSize(true);
        recyclerView.setNestedScrollingEnabled(false);
        CPOrederGameAdapter cpOrederListRightGameAdapter = new CPOrederGameAdapter(getContext(), R.layout.item_cp_order_list, cpGameList);
        recyclerView.setAdapter(cpOrederListRightGameAdapter);*/
    }

    @Override
    public void postRateInfoResult(CQSSCResult cqsscResult) {
        CQSSC(cqsscResult);
    }

    /*@Override
    public void postRateInfo6Result(CQSSCResult cqsscResult) {
        CQSSC(cqsscResult,1);
    }

    @Override
    public void postRateInfo1Result(CQSSCResult cqsscResult) {
        CQSSC(cqsscResult,2);
        showContentView(0);
    }*/

    @Override
    public void postLastResultResult(CPLastResult cpLastResult) {

        cpOrderLotteryLastTime.setText(cpLastResult.getIssue()+"期");
        cpLeftEventList2.clear();
        cpLeftEventList1.clear();
        String[] dataList  =  cpLastResult.getNums().split(",");
        int dataListSize = dataList.length;
        int total= 0;
        for(int k=0;k<dataListSize;++k){
            cpLeftEventList1.add(dataList[k]);
            total += Integer.parseInt(dataList[k]);
        }

        /** 北京赛车    game_code 51
         *  重庆时时彩    game_code 2
         *  极速赛车    game_code 189
         *  极速飞艇    game_code 222
         *  分分彩    game_code 207
         *  三分彩    game_code 407
         *  五分彩    game_code 507
         *  腾讯二分彩    game_code 607
         *  PC蛋蛋    game_code 304
         *  江苏快3    game_code 159
         *  幸运农场    game_code 47
         *  快乐十分    game_code 3
         *  香港六合彩  game_code 69
         *  极速快三    game_code 384
         *
         */
        switch (game_code){
            case "2":
            case "207":
            case "407":
            case "507":
            case "607":
                group = "group2";
                cpLeftEventList2.add(total+"");
                cpLeftEventList2.add((total >= 23)?"大":"小");
                cpLeftEventList2.add((total % 2 ==1)?"单":"双");
                /*if(Integer.parseInt(dataList[0])>Integer.parseInt(dataList[4])){
                    cpLeftEventList2.add("龙");
                }else if(Integer.parseInt(dataList[0])==Integer.parseInt(dataList[4])){
                    cpLeftEventList2.add("和");
                }else{
                    cpLeftEventList2.add("虎");
                }*/
                cpLeftEventList2.add(Integer.parseInt(dataList[0])>=Integer.parseInt(dataList[4])? Integer.parseInt(dataList[0])>Integer.parseInt(dataList[4])?"龙":"和":"虎");
                cpOrderLotteryOpen1.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList1));
                cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                break;
            case "51":
            case "189":
            case "222":
            case "168"://幸运飞艇 暂无
                group = "group1";
                cpLeftEventList2.add(Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1])+"");
                cpLeftEventList2.add((Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1]))>11?"大":"小");
                cpLeftEventList2.add(((Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1]))%2 ==1)?"单":"双");
                cpLeftEventList2.add(Integer.parseInt(dataList[0])>Integer.parseInt(dataList[9])?"龙":"虎");
                cpLeftEventList2.add(Integer.parseInt(dataList[1])>Integer.parseInt(dataList[8])?"龙":"虎");
                cpLeftEventList2.add(Integer.parseInt(dataList[2])>Integer.parseInt(dataList[7])?"龙":"虎");
                cpLeftEventList2.add(Integer.parseInt(dataList[3])>Integer.parseInt(dataList[6])?"龙":"虎");
                cpLeftEventList2.add(Integer.parseInt(dataList[4])>Integer.parseInt(dataList[5])?"龙":"虎");
                cpOrderLotteryOpen1.setAdapter(new Open1GameAdapter(getContext(), R.layout.item_cp_order_open_1, cpLeftEventList1));
                cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                break;
            case "47":
            case "3":
                cpLeftEventList2.add(total+"");
                cpLeftEventList2.add((total >= 84)?total > 84?"大":"和":"小");
                cpLeftEventList2.add((total % 2 == 1) ? "单":"双");
                cpLeftEventList2.add((total % 10 >= 5) ? "大":"小");
                group = "group3";
                cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                break;
            case "21"://广东11选5 暂无
                cpLeftEventList2.add(total+"");
                cpLeftEventList2.add((total >= 30)?total > 30?"大":"和":"小");
                cpLeftEventList2.add((total % 2 == 1) ? "单":"双");
                cpLeftEventList2.add((total % 10 >= 5) ? "大":"小");
                cpLeftEventList2.add((Integer.parseInt(dataList[0])>Integer.parseInt(dataList[4])) ? "龙":"虎");
                group = "group4";
                cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                break;
            case "65"://"北京快乐8" 暂无
                group = "group5";
                break;
            case "159":
            case "384":
                cpLeftEventList2.add(total+"");
                cpLeftEventList2.add((total >= 11) ? "大":"小");
                group = "group6";
                cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                break;
            case "69":
                group = "group7";
                //香港六合彩的没有二
                break;
            case "304":
                cpLeftEventList2.add(total+"");
                cpLeftEventList2.add((total > 13) ? "大":"小");
                cpLeftEventList2.add((total % 2 == 1) ? "单":"双");
                group = "group8";
                cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
                break;
        }



    }

    @Override
    public void postNextIssueResult(CPNextIssueResult cpNextIssueResult) {
        isCloseLottery = false;
        cpOrderGold.setClickable(true);
        cpOrderGold.setFocusable(true);
        cpOrderGold.setFocusableInTouchMode(true);
        cpOrderGold.requestFocus();
        cpOrderNoYet.setVisibility(View.GONE);
        round =cpNextIssueResult.getIssue();
        cpOrderLotteryNextTime.setText(round+"期");
        String systTime =TimeUtils.convertToDetailTime(System.currentTimeMillis());
        sendEndTime = TimeHelper.timeToSecond(cpNextIssueResult.getEndtime(),systTime)+20;
//        sendEndTime = TimeHelper.timeToSecond("2018-11-28 11:28:00","2018-11-28 11:20:15");
        sendAuthTime = TimeHelper.timeToSecond(cpNextIssueResult.getLotteryTime(),systTime)+20;
        GameLog.log("getEndtime："+cpNextIssueResult.getEndtime()+"systTime："+systTime);
        GameLog.log("封盘时间："+sendEndTime+"开奖时间："+sendAuthTime);
        onSartTime();
    }

    @Override
    public void postCPLeftInfoResult(CPLeftInfoResult cpLeftInfoResult) {
        cpOrderUserMoney.setText(cpLeftInfoResult.getMoney());
        GameLog.log("彩票的用户金额 "+cpLeftInfoResult.getMoney());
    }


    class CPLeftMenuGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public CPLeftMenuGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            /*if(position==0){
                TextView tv =  holder.getView(R.id.tv_item_game_name);
                tv.setGravity(Gravity.CENTER);
            }*/
            holder.setText(R.id.itemOrderLeftListTV, data.getIconName());
            holder.setOnClickListener(R.id.itemOrderLeftListTV, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(position==0){
                        return;
                    }else if(position==1){
                        pop();
                        return;
                    }
                    slidingLeftMenu.toggle();

                }
            });
        }
    }


    private void onChangeData(){
        allResultList.clear();
        //北京赛车
        String  data = getFromAssets("data_bak.json");
        //重庆时时彩
        String  data2 = getFromAssets("cqssc.txt");
        //PC蛋蛋
        String  data3 = getFromAssets("pcdd.txt");
        //PC蛋蛋
        String  data4 = getFromAssets("jsks.txt");
        //GameLog.log("屏幕的宽度："+data);
        Gson gson = new Gson();
        if("bjsc".equals(orderStype)){
           /* CPBJSCResult2 cpbjscResult2 = gson.fromJson(data,CPBJSCResult2.class);
            BJPK10(cpbjscResult2);*/
            CPJSKSResult cqsscResult4 = gson.fromJson(data4,CPJSKSResult.class);
            JSKS(cqsscResult4);
        }else if("cqssc".equals(orderStype)){
            CQSSCResult cqsscResult = gson.fromJson(data2,CQSSCResult.class);
            CQSSC(cqsscResult);
        }else if("pcdd".equals(orderStype)){
            PCDDResult cqsscResult3 = gson.fromJson(data3,PCDDResult.class);
            PCDD(cqsscResult3);
        }else if("jsks".equals(orderStype)){
            CPJSKSResult cqsscResult4 = gson.fromJson(data4,CPJSKSResult.class);
            JSKS(cqsscResult4);
        }
        showContentView(0);
        /** 北京赛车    game_code 51
         *  重庆时时彩    game_code 2
         *  极速赛车    game_code 189
         *  极速飞艇    game_code 222
         *  分分彩    game_code 207
         *  三分彩    game_code 407
         *  五分彩    game_code 507
         *  腾讯二分彩    game_code 607
         *  PC蛋蛋    game_code 304
         *  江苏快3    game_code 159
         *  幸运农场    game_code 47
         *  快乐十分    game_code 3
         *  香港六合彩  game_code 69
         *  极速快三    game_code 384
         */

        switch (game_code){
            case "2":
            case "207":
            case "407":
            case "507":
            case "607"://时时彩的请求方式
                /*presenter.postRateInfo(game_code,type,x_session_token);
                presenter.postRateInfo6(game_code,"6",x_session_token);
                presenter.postRateInfo1(game_code,"1",x_session_token);*/
                break;
        }
        presenter.postNextIssue(game_code,x_session_token);
        presenter.postLastResult(game_code,x_session_token);

    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        presenter.postCPLeftInfo("",x_session_token);
        CPBetManager.getSingleton().onClearData();
        setSystemUIVisible(false);
        cpOrderTitle.setText(titleName);
        x_session_token = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        /*ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                getActivity(), drawer_layout, R.string.account, R.string.password);
        drawer_layout.addDrawerListener(toggle);
        toggle.syncState();*/

        /*String  data = getFromAssets("data.json").replace("-","_");
        GameLog.log("屏幕的宽度："+data);
        CpBJSCResult cpBJSCResult = JSON.parseObject(data, CpBJSCResult.class);
        GameLog.log("屏幕的宽度："+cpBJSCResult.toString());*/

        //CPBJSCResult2 cpBJSCResult = JSON.parseObject(data, CPBJSCResult2.class);
//        /GameLog.log("屏幕的宽度："+cpbjscResult2.toString());

        /*WindowManager wm = (WindowManager) getContext().getSystemService(Context.WINDOW_SERVICE);
        DisplayMetrics metrics = new DisplayMetrics();
        wm.getDefaultDisplay().getMetrics(metrics);*/
        /*mScreenWidth = metrics.widthPixels;
        mScreenHeight = metrics.heightPixels;*/
        //mainSwipemenu.setMenuOffset(metrics.widthPixels-Integer.parseInt(SizeUtil.Dp2Px(getContext(),50)+""));
       /* LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpOrderGameList.setLayoutManager(gridLayoutManager);
        cpOrderGameList.setHasFixedSize(true);
        cpOrderGameList.setNestedScrollingEnabled(false);
        cpOrderGameList.setAdapter(new CPOrederGameAdapter(getContext(), R.layout.item_cp_order_list, cpGameList));*/
        LinearLayoutManager linearLayoutManagerLeft = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpOrderListLeft.setLayoutManager(linearLayoutManagerLeft);
        cpOrderListLeft.setHasFixedSize(true);
        cpOrderListLeft.setNestedScrollingEnabled(false);

        LinearLayoutManager linearLayoutManagerRight = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpOrderListRight.setLayoutManager(linearLayoutManagerRight);
        cpOrderListRight.setHasFixedSize(true);
        cpOrderListRight.setNestedScrollingEnabled(false);


        //cpOrderListViewtLeft.setAdapter(new CPOrederListViewLeftGameAdapter(getContext(), R.layout.item_cp_order_left_list, allResultList));
        LinearLayoutManager cpOrderLotteryOpen11 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
        cpOrderLotteryOpen1.setLayoutManager(cpOrderLotteryOpen11);
        cpOrderLotteryOpen1.setHasFixedSize(true);
        cpOrderLotteryOpen1.setNestedScrollingEnabled(false);
        //cpOrderLotteryOpen1.setAdapter(new Open1GameAdapter(getContext(), R.layout.item_cp_order_open_1, cpLeftEventList));

        LinearLayoutManager cpOrderLotteryOpen22 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
        cpOrderLotteryOpen2.setLayoutManager(cpOrderLotteryOpen22);
        cpOrderLotteryOpen2.setHasFixedSize(true);
        cpOrderLotteryOpen2.setNestedScrollingEnabled(false);
       // cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));

        onChangeData();


    }


    /**
     * 设置图片
     */
    class Open1GameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public Open1GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            switch (data){
                case "01":
                case "1":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_one);
                    break;
                case "02":
                case "2":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_two);
                    break;
                case "03":
                case "3":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_three);
                    break;
                case "04":
                case "4":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_four);
                    break;
                case "05":
                case "5":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_five);
                    break;
                case "06":
                case "6":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_six);
                    break;
                case "07":
                case "7":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_seven);
                    break;
                case "08":
                case "8":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_eight);
                    break;
                case "09":
                case "9":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_nine);
                    break;
                case "10":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_ten);
                    break;
            }

        }
    }

    /**
     * 设置文字
     */
    class Open2GameAdapter extends AutoSizeRVAdapter<String> {
        private Context context;

        public Open2GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, String data, final int position) {
            holder.setText(R.id.itemOrderOpen2,data);
        }
    }


    class CPOrederListViewLeftGameAdapter extends AutoSizeAdapter<CPOrderAllResult> {
        private Context context;

        public CPOrederListViewLeftGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(final com.zhy.adapter.abslistview.ViewHolder holder, CPOrderAllResult data, final int position) {
            if(data.isEventChecked()){
                holder.setTextColor(R.id.itemOrderLeftListTV, R.color.title_text);
                holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_checked);
            }else{
                //holder.setTextColor(R.id.itemOrderLeftListTV, R.color.textview_normal);
                holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_normal);
            }
            holder.setText(R.id.itemOrderLeftListTV, data.getOrderAllName());
            holder.setOnClickListener(R.id.itemOrderLeftListTV, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    onRefreshRight(position);
                    for(int i=0;i<allResultList.size();++i){
                        allResultList.get(i).setEventChecked(false);
                    }
                    holder.setTextColor(R.id.itemOrderLeftListTV, R.color.title_text);
                    allResultList.get(position).setEventChecked(true);
                    /*if(data.isEventChecked()){
                        data.setEventChecked(false);
                        holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_normal);
                    }else{
                        data.setEventChecked(true);
                        holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_checked);
                    }*/
                    notifyDataSetChanged();
                }
            });
        }
    }

    class CPOrederListLeftGameAdapter extends AutoSizeRVAdapter<CPOrderAllResult> {
        private Context context;

        public CPOrederListLeftGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }


        @Override
        protected void convert(final ViewHolder holder,final CPOrderAllResult data, final int position) {
           /* if(data.isEventChecked()){
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_checked);
            }else{
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_normal);
            }*/
            if(data.isEventChecked()){
                holder.setTextColorRes(R.id.itemOrderLeftListTV, R.color.title_text);
                GameLog.log(position+" 设置颜色值11111111111111111111111111111");
                holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_checked);
            }else{
                holder.setTextColorRes(R.id.itemOrderLeftListTV, R.color.n_edittext_hint);
                GameLog.log(position+" 设置颜色值222222222222222222222222222222");
                holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_normal);
            }
            holder.setText(R.id.itemOrderLeftListTV, data.getOrderAllName());
            holder.setOnClickListener(R.id.itemOrderLeftListTV, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    onRefreshRight(position);
                    for(int i=0;i<allResultList.size();++i){
                        allResultList.get(i).setEventChecked(false);
                    }
                    //holder.setTextColor(R.id.itemOrderLeftListTV, R.color.title_text);
                    allResultList.get(position).setEventChecked(true);
                    /*if(data.isEventChecked()){
                        data.setEventChecked(false);
                        holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_normal);
                    }else{
                        data.setEventChecked(true);
                        holder.setBackgroundRes(R.id.itemOrderLeftListTV,R.drawable.bg_cp_oder_left_checked);
                    }*/
                    notifyDataSetChanged();
                }
            });
        }
    }


    public class DepositListAdapter extends AutoSizeAdapter<CPOrderContentListResult> {
        private Context context;

        public DepositListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(com.zhy.adapter.abslistview.ViewHolder holder, final CPOrderContentListResult data, final int position) {
            holder.setText(R.id.cpOrderContentName1, data.getOrderContentListName());
            GridLayoutManager gridLayoutManager = null;
            GameLog.log("当前的试试："+data.getShowNumber());
            if(data.getShowNumber()==3){
                gridLayoutManager= new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
            }else{
                gridLayoutManager= new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
            }
            RecyclerView recyclerView = holder.getView(R.id.cpOrderContentList1);
            recyclerView.setLayoutManager(gridLayoutManager);
            /*recyclerView.setHasFixedSize(true);
            recyclerView.setNestedScrollingEnabled(true);*/

            // recyclerView.addItemDecoration(new GridRvItemDecoration(getContext()));
            cpOrederContentGameAdapter = null;
            cpOrederContentGameAdapter = new CPOrederContentGameAdapter(getContext(), R.layout.item_cp_order_content2, data.getData());
            recyclerView.setAdapter(cpOrederContentGameAdapter);
        }
    }

    private void showContentView(int postion){
        postionAll = postion;
        //cpOrderListViewRight.setAdapter(new DepositListAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(postionAll).getData()));

        //cpOrederListRightGameAdapter = new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(postion).getData());
//        data.clear();
//        data.addAll(allResultList.get(postion).getData());
        cpOrderListLeft.setAdapter(new CPOrederListLeftGameAdapter(getContext(), R.layout.item_cp_order_left_list, allResultList));
        cpOrederListRightGameAdapter = new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(postionAll).getData());
        cpOrderListRight.setAdapter(cpOrederListRightGameAdapter);
        cpOrederListRightGameAdapter.notifyDataSetChanged();
        /*cpOrderUserMoney.post(new Runnable() {
            @Override
            public void run() {
                *//*myAdapter = new MyAdapter(data);
                cpOrderListRight.setAdapter(myAdapter);*//*
                cpOrederListRightGameAdapter = new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(postionAll).getData());
                cpOrderListRight.setAdapter(cpOrederListRightGameAdapter);
                //cpOrderListRight.setLayoutManager(new LinearLayoutManager(getActivity()));
                //myAdapter.notifyDataSetChanged();
                cpOrderListRight.scrollToPosition(0);
                cpOrederListRightGameAdapter.notifyDataSetChanged();
            }
        });*/

        //cpOrederListRightGameAdapter.setDataChange(allResultList.get(postion).getData());
        //cpOrederListRightGameAdapter.notifyDataSetChanged();
    }


    private void onRefreshRight(int position){

        showMessage("刷新后边的数据"+position);
        showContentView(position);
    }

    public class MyAdapter extends RecyclerView.Adapter<MyAdapter.ViewHolder> {
        List<CPOrderContentListResult>  datas;
        public MyAdapter(List<CPOrderContentListResult>  datas) {
            this.datas = datas;
        }
        //创建新View，被LayoutManager所调用
        @Override
        public ViewHolder onCreateViewHolder(ViewGroup viewGroup, int viewType) {
            View view = LayoutInflater.from(viewGroup.getContext()).inflate(R.layout.item_cp_order_content1,viewGroup,false);
            ViewHolder vh = new ViewHolder(view);
            return vh;
        }
        //将数据与界面进行绑定的操作
        @Override
        public void onBindViewHolder(ViewHolder viewHolder, int position) {
            viewHolder.mTextView.setText(datas.get(position).getOrderContentListName());
            GridLayoutManager gridLayoutManager = null;
            if(position==1||position==2||position==3||position==4||position==5){
                gridLayoutManager= new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
            }else{
                gridLayoutManager= new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
            }
            viewHolder.recyclerView.setLayoutManager(gridLayoutManager);
            cpOrederContentGameAdapter = null;
            cpOrederContentGameAdapter = new CPOrederContentGameAdapter(getContext(), R.layout.item_cp_order_content2, datas.get(position).getData());
            viewHolder.recyclerView.setAdapter(cpOrederContentGameAdapter);
        }
        //获取数据的数量
        @Override
        public int getItemCount() {
            return datas.size();
        }
        //自定义的ViewHolder，持有每个Item的的所有界面元素
        public  class ViewHolder extends RecyclerView.ViewHolder {
            public TextView mTextView;
            public RecyclerView recyclerView;
            public ViewHolder(View view){
                super(view);
                mTextView = (TextView) view.findViewById(R.id.cpOrderContentName1);
                recyclerView =  (RecyclerView) view.findViewById(R.id.cpOrderContentList1);
            }
        }
    }


    class CPOrederListRightGameAdapter extends AutoSizeRVAdapter<CPOrderContentListResult> {
        private Context context;
        private List<CPOrderContentListResult>  datas;
        public CPOrederListRightGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        public void setDataChange(List<CPOrderContentListResult>  datas){
            this.datas = datas;
            notifyDataSetChanged();
        }

        @Override
        protected void convert(ViewHolder holder, CPOrderContentListResult data, final int position) {
            if(Check.isEmpty(data.getOrderContentListName())){
                holder.setVisible(R.id.cpOrderContentName1, false);
                holder.setVisible(R.id.cpOrderContentLine, false);
            }else{
                holder.setText(R.id.cpOrderContentName1, data.getOrderContentListName());
                holder.setVisible(R.id.cpOrderContentName1, true);
                holder.setVisible(R.id.cpOrderContentLine, true);
            }
            GridLayoutManager gridLayoutManager = null;
            if(data.getShowNumber()==3){
                gridLayoutManager= new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
            }else if(data.getShowNumber()==2){
                gridLayoutManager= new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
            }else{
                gridLayoutManager= new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
            }
            RecyclerView recyclerView = holder.getView(R.id.cpOrderContentList1);
            recyclerView.setLayoutManager(gridLayoutManager);
            /*recyclerView.setHasFixedSize(true);
            recyclerView.setNestedScrollingEnabled(true);*/
           // recyclerView.addItemDecoration(new GridRvItemDecoration(getContext()));
           /* if(data.getShowType().equals("DANIEL_")){
                CPOrederContentGameAdapter2  cpOrederContentGameAdapter2 = new CPOrederContentGameAdapter2(getContext(), R.layout.item_cp_order_content23, data.getData(),data.getShowType());
                recyclerView.setAdapter(cpOrederContentGameAdapter2);
            }else if(data.getShowType().equals("DANIEL")){
                CPOrederContentGameAdapter2  cpOrederContentGameAdapter2 = new CPOrederContentGameAdapter2(getContext(), R.layout.item_cp_order_content22, data.getData(),data.getShowType());
                recyclerView.setAdapter(cpOrederContentGameAdapter2);
            }else{
                cpOrederContentGameAdapter = null;
                cpOrederContentGameAdapter = new CPOrederContentGameAdapter(getContext(), R.layout.item_cp_order_content2, data.getData(),data.getShowType());
                recyclerView.setAdapter(cpOrederContentGameAdapter);
            }*/
            cpOrederContentGameAdapter = null;
            cpOrederContentGameAdapter = new CPOrederContentGameAdapter(getContext(), R.layout.item_cp_order_content2, data.getData(),data.getShowType());
            recyclerView.setAdapter(cpOrederContentGameAdapter);
        }
    }

    class CPOrederContentGameAdapter extends AutoSizeRVAdapter<CPOrderContentResult> {
        private Context context;
        private String showType;

        public CPOrederContentGameAdapter(Context context, int layoutId, List datas,String showType) {
            super(context, layoutId, datas);
            context = context;
            this.showType = showType;
        }
        public CPOrederContentGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }


        private void onSetKSImageView01(String data,ViewHolder holder){
            switch (data){
                case "1":
                    holder.setBackgroundRes(R.id.cpOrderContentIm01,R.mipmap.s_1);
                    break;
                case "2":
                    holder.setBackgroundRes(R.id.cpOrderContentIm01,R.mipmap.s_2);
                    break;
                case "3":
                    holder.setBackgroundRes(R.id.cpOrderContentIm01,R.mipmap.s_3);
                    break;
                case "4":
                    holder.setBackgroundRes(R.id.cpOrderContentIm01,R.mipmap.s_4);
                    break;
                case "5":
                    holder.setBackgroundRes(R.id.cpOrderContentIm01,R.mipmap.s_5);
                    break;
                case "6":
                    holder.setBackgroundRes(R.id.cpOrderContentIm01,R.mipmap.s_6);
                    break;
            }
        }

        private void onSetKSImageView02(String data,ViewHolder holder){
            switch (data){
                case "1":
                    holder.setBackgroundRes(R.id.cpOrderContentIm02,R.mipmap.s_1);
                    break;
                case "2":
                    holder.setBackgroundRes(R.id.cpOrderContentIm02,R.mipmap.s_2);
                    break;
                case "3":
                    holder.setBackgroundRes(R.id.cpOrderContentIm02,R.mipmap.s_3);
                    break;
                case "4":
                    holder.setBackgroundRes(R.id.cpOrderContentIm02,R.mipmap.s_4);
                    break;
                case "5":
                    holder.setBackgroundRes(R.id.cpOrderContentIm02,R.mipmap.s_5);
                    break;
                case "6":
                    holder.setBackgroundRes(R.id.cpOrderContentIm02,R.mipmap.s_6);
                    break;
            }
        }

        private void onSetKSImageView03(String data,ViewHolder holder){
            switch (data){
                case "1":
                    holder.setBackgroundRes(R.id.cpOrderContentIm03,R.mipmap.s_1);
                    break;
                case "2":
                    holder.setBackgroundRes(R.id.cpOrderContentIm03,R.mipmap.s_2);
                    break;
                case "3":
                    holder.setBackgroundRes(R.id.cpOrderContentIm03,R.mipmap.s_3);
                    break;
                case "4":
                    holder.setBackgroundRes(R.id.cpOrderContentIm03,R.mipmap.s_4);
                    break;
                case "5":
                    holder.setBackgroundRes(R.id.cpOrderContentIm03,R.mipmap.s_5);
                    break;
                case "6":
                    holder.setBackgroundRes(R.id.cpOrderContentIm03,R.mipmap.s_6);
                    break;
            }
        }

        @Override
        protected void convert(ViewHolder holder,final CPOrderContentResult data, final int position) {
            switch (showType){
                case "TU":
                    holder.setText(R.id.cpOrderContentState, data.getOrderState());
                    holder.setVisible(R.id.cpOrderContentName2,false);
                    holder.setVisible(R.id.cpOrderContentIm2,true);
                    switch (data.getOrderName()){
                        case "1":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_one);
                            break;
                        case "2":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_two);
                            break;
                        case "3":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_three);
                            break;
                        case "4":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_four);
                            break;
                        case "5":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_five);
                            break;
                        case "6":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_six);
                            break;
                        case "7":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_seven);
                            break;
                        case "8":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_eight);
                            break;
                        case "9":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_nine);
                            break;
                        case "10":
                            holder.setBackgroundRes(R.id.cpOrderContentIm2,R.mipmap.cp_ten);
                            break;
                    }

                    break;
                case "QIU":
                    holder.setText(R.id.cpOrderContentState, data.getOrderState());
                    holder.setText(R.id.cpOrderContentName2, data.getOrderName());
                    holder.setBackgroundRes(R.id.cpOrderContentName2,R.mipmap.cp_qiu);
                    break;
                case "DANIEL":
                    holder.setVisible(R.id.cpOrderContentKS, true);
                    holder.setVisible(R.id.cpOrderContentName2, false);
                    holder.setVisible(R.id.cpOrderContentState04, false);
                    ArrayList<String> dataList = new ArrayList<>();
                    String[] sdata = data.getOrderName().split("_");
                    onSetKSImageView01(sdata[0],holder);
                    onSetKSImageView02(sdata[1],holder);
                    onSetKSImageView03(sdata[2],holder);
                    holder.setText(R.id.cpOrderContentState, data.getOrderState());
                    break;
                case "DANIEL_":
                    holder.setVisible(R.id.cpOrderContentKS, true);
                    holder.setVisible(R.id.cpOrderContentState04, true);
                    holder.setVisible(R.id.cpOrderContentNormal, false);
                    String[] sdat_a = data.getOrderName().split("_");
                    if(sdat_a.length==2){
                        onSetKSImageView01(sdat_a[0],holder);
                        onSetKSImageView02(sdat_a[1],holder);
                        holder.setVisible(R.id.cpOrderContentIm03, false);
                    }else{
                        onSetKSImageView01(sdat_a[0],holder);
                        holder.setVisible(R.id.cpOrderContentIm02, false);
                        holder.setVisible(R.id.cpOrderContentIm03, false);
                    }
                    holder.setVisible(R.id.cpOrderContentName2, false);
                    holder.setVisible(R.id.cpOrderContentState, false);
                    holder.setText(R.id.cpOrderContentState04, data.getOrderState());
                    break;
                default:
                    holder.setText(R.id.cpOrderContentName2, data.getOrderName());
                    holder.setText(R.id.cpOrderContentState, data.getOrderState());
                    break;
            }

            if(data.isChecked()){
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.cp_order_tv_clicked);
            }else{
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.title_text);
            }
            holder.setOnClickListener(R.id.cpOrderContentItem, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(isCloseLottery){
                        onResetData();
                        return;
                    }
                    String name  = data.getFullName().equals("")?(data.getOrderName().equals("龙")?"蛇":data.getOrderName().equals("虎")?"兔":data.getOrderName()):data.getFullName()+" - "+(data.getOrderName().equals("龙")?"蛇":data.getOrderName().equals("虎")?"兔":data.getOrderName());
                    if("_".equals(name.substring(name.length() -1, name.length()))){
                        name = name.substring(0, name.length() -1);
                    }
                    CPBetManager.getSingleton().onAddData(postionAll+"",data.getOrderId(),name,data.getOrderState(),postionAll+"_"+data.getOrderId());
                    if(!data.isChecked()){
                        //allResultList.get(postionAll).getData().get(postions).getData().get(position).setChecked(true);
                        data.setChecked(true);
                    }else{
                        //allResultList.get(postionAll).getData().get(postions).getData().get(position).setChecked(false);
                        data.setChecked(false);
                    }
                    GameLog.log("下注的id是："+data.getOrderId());
                    //myAdapter.notifyDataSetChanged();
                    notifyDataSetChanged();
                    cpOrderNumber.setText(Html.fromHtml("已选中"+onMarkRed(CPBetManager.getSingleton().onListSize()+"")+"注"));
                    /*cpOrederListRightGameAdapter.notifyDataSetChanged();
                    cpOrderListRight.scrollTo(10,0);*/
                }
            });
        }
    }

    class CPOrederContentGameAdapter2 extends AutoSizeRVAdapter<CPOrderContentResult> {
        private Context context;
        private String showType;

        public CPOrederContentGameAdapter2(Context context, int layoutId, List datas,String showType) {
            super(context, layoutId, datas);
            context = context;
            this.showType = showType;
        }
        public CPOrederContentGameAdapter2(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder,final CPOrderContentResult data, final int position) {
            holder.setVisible(R.id.cpOrderContentName2,false);
            RecyclerView recyclerView = holder.getView(R.id.cpOrderContentRecyclerView);
            LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
            recyclerView.setLayoutManager(linearLayoutManager);
            recyclerView.setHasFixedSize(true);
            recyclerView.setNestedScrollingEnabled(false);
            recyclerView.setFocusableInTouchMode(false);
            recyclerView.setFocusable(false);
            recyclerView.setClickable(false);
            CPOrederContentGameAdapter3 cpOrederContentGameAdapter = null;
            ArrayList<String> dataList = new ArrayList<>();
            String[] sdata = data.getOrderName().split("_");
            for(int k=0;k<sdata.length;++k){
                dataList.add(sdata[k]);
            }
            holder.setText(R.id.cpOrderContentState, data.getOrderState());
            cpOrederContentGameAdapter = new CPOrederContentGameAdapter3(getContext(), R.layout.item_cp_order_content3, dataList,"");
            recyclerView.setAdapter(cpOrederContentGameAdapter);
            if(data.isChecked()){
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.cp_order_tv_clicked);
            }else{
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.title_text);
            }
            holder.setOnClickListener(R.id.cpOrderContentItem, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(isCloseLottery){
                        onResetData();
                        return;
                    }
                    String name  = data.getFullName().equals("")?(data.getOrderName().equals("龙")?"蛇":data.getOrderName().equals("虎")?"兔":data.getOrderName()):data.getFullName()+" - "+(data.getOrderName().equals("龙")?"蛇":data.getOrderName().equals("虎")?"兔":data.getOrderName());
                    CPBetManager.getSingleton().onAddData(postionAll+"",data.getOrderId(),name,data.getOrderState(),postionAll+"_"+data.getOrderId());
                    if(!data.isChecked()){
                        //allResultList.get(postionAll).getData().get(postions).getData().get(position).setChecked(true);
                        data.setChecked(true);
                    }else{
                        //allResultList.get(postionAll).getData().get(postions).getData().get(position).setChecked(false);
                        data.setChecked(false);
                    }
                    GameLog.log("下注的id是："+data.getOrderId());
                    //myAdapter.notifyDataSetChanged();
                    notifyDataSetChanged();
                    cpOrderNumber.setText(Html.fromHtml("已选中"+onMarkRed(CPBetManager.getSingleton().onListSize()+"")+"注"));
                    /*cpOrederListRightGameAdapter.notifyDataSetChanged();
                    cpOrderListRight.scrollTo(10,0);*/
                }
            });
        }

    }



    class CPOrederContentGameAdapter3 extends AutoSizeRVAdapter<String> {
        private Context context;
        private String showType;

        public CPOrederContentGameAdapter3(Context context, int layoutId, List datas,String showType) {
            super(context, layoutId, datas);
            context = context;
            this.showType = showType;
        }
        public CPOrederContentGameAdapter3(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder,final String data, final int position) {
            switch (data){
                case "1":
                    holder.setBackgroundRes(R.id.cpOrderContentIm3,R.mipmap.s_1);
                    break;
                case "2":
                    holder.setBackgroundRes(R.id.cpOrderContentIm3,R.mipmap.s_2);
                    break;
                case "3":
                    holder.setBackgroundRes(R.id.cpOrderContentIm3,R.mipmap.s_3);
                    break;
                case "4":
                    holder.setBackgroundRes(R.id.cpOrderContentIm3,R.mipmap.s_4);
                    break;
                case "5":
                    holder.setBackgroundRes(R.id.cpOrderContentIm3,R.mipmap.s_5);
                    break;
                case "6":
                    holder.setBackgroundRes(R.id.cpOrderContentIm3,R.mipmap.s_6);
                    break;
            }
        }

    }


    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#fdb22b'>" + sign+"</font>";
    }

    class CPOrederGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public CPOrederGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            if(position==0){
               TextView tv =  holder.getView(R.id.tv_item_game_name);
                tv.setGravity(Gravity.CENTER);
            }
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setOnClickListener(R.id.tv_item_game_name, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(position==0){
                        return;
                    }else if(position==1){
                        finish();
                        return;
                    }
                    onCpGameItemClick(position);
                }
            });
        }
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CPOrderContract.Presenter presenter) {

        this.presenter = presenter;
    }

    /*@Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }*/


    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult) {
        GameLog.log("通过发送消息得的的数据" + personBalanceResult.getBalance_ag());
        agMoney = personBalanceResult.getBalance_ag();
        hgMoney = personBalanceResult.getBalance_hg();
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }


    @Override
    public void onBackPressedSupport() {
        super.onBackPressedSupport();
        /*if (mainSwipemenu.isMenuShowing()) {
            mainSwipemenu.hideMenu();
        }*/

//        drawer_layout.closeDrawer(GravityCompat.START);
    }

    //等待时长
    class onLotteryTimeThread implements Runnable {
        @Override
        public void run() {
            if (sendAuthTime-- <= 0) {
                sendAuthTime = 0;
                if(null!=executorService){
                    executorService.shutdownNow();
                    executorService.shutdown();
                    executorService = null;
                }
                rightCloseLotteryTime.post(new Runnable() {
                    @Override
                    public void run() {
                        rightOpenLotteryTime.setText("开奖中");
                        presenter.postNextIssue(game_code,x_session_token);
                        presenter.postLastResult(game_code,x_session_token);
                        GameLog.log("开奖中  请求下一个盘口");
                        /*rightCloseLotteryTime.post(new Runnable() {
                            @Override
                            public void run() {
                                if(rightCloseLotteryTime!=null){
                                    rightCloseLotteryTime.setText("已封盘");
                                    //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                                }
                            }
                        });*/
                       // presenter.postNextIssue(game_code,x_session_token);
                    }
                });
            } else {
                rightCloseLotteryTime.post(new Runnable() {
                    @Override
                    public void run() {
                        if(rightOpenLotteryTime!=null){
                            rightOpenLotteryTime.setText(TimeHelper.getTimeString(sendAuthTime));
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }
    class onWaitingEndThread implements Runnable {
        @Override
        public void run() {
            if (sendEndTime-- <= 0) {
                sendEndTime = 0;
                isCloseLottery = true;
                if(null!=executorEndService){
                    executorEndService.shutdownNow();
                    executorEndService.shutdown();
                    executorEndService = null;
                }
                rightCloseLotteryTime.post(new Runnable() {
                    @Override
                    public void run() {
                        cpOrderGold.setClickable(false);
                        cpOrderGold.setFocusable(false);
                        cpOrderGold.setFocusableInTouchMode(false);
                        cpOrderNoYet.setVisibility(View.VISIBLE);
                        onResetData();
                        rightCloseLotteryTime.setText("已封盘");
                        GameLog.log("已封盘  等待开奖");
                    }
                });
            } else {
                rightCloseLotteryTime.post(new Runnable() {
                    @Override
                    public void run() {
                        if(rightCloseLotteryTime!=null){
                            rightCloseLotteryTime.setText(TimeHelper.getTimeString(sendEndTime));
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }


    private void onSartTime(){
        onSendAuthCode();
        onSendEndCode();
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }

        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(lotteryTimeThread, 0, 1000, TimeUnit.MILLISECONDS);
    }

    //计数器，用于倒计时使用
    private void onSendEndCode() {
        GameLog.log("-----开始-----");
        if(null!=executorEndService){
            executorEndService.shutdownNow();
            executorEndService.shutdown();
            executorEndService = null;
        }

        executorEndService = Executors.newScheduledThreadPool(1);
        executorEndService.scheduleAtFixedRate(onWaitingEndThread, 0, 1000, TimeUnit.MILLISECONDS);
    }


    private void onResetDataChecked(){
        int size  = allResultList.size();
        for(int i=0;i<size;i++){
            CPOrderAllResult cpOrderAllResult =  allResultList.get(i);
            int size2 = cpOrderAllResult.getData().size();
            for(int j=0;j<size2;++j){
                CPOrderContentListResult cpOrderContentListResult = cpOrderAllResult.getData().get(j);
                int size3 = cpOrderContentListResult.getData().size();
                for(int k=0;k<size3;++k){
                    CPOrderContentResult cpOrderContentResult = cpOrderContentListResult.getData().get(k);
                    cpOrderContentResult.setChecked(false);
                }
            }
        }
    }

    private void onResetData(){
        onResetDataChecked();
                /*cpOrederListRightGameAdapter = new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(postionAll).getData());
                cpOrderListRight.setAdapter(cpOrederListRightGameAdapter);*/
        cpOrderNumber.setText(Html.fromHtml("已选中"+onMarkRed(0+"")+"注"));
        CPBetManager.getSingleton().onClearData();
        cpOrderGold.setText("");
        cpOrederListRightGameAdapter.notifyDataSetChanged();
        GameLog.log("重置了 ");
    }

    @OnClick({R.id.cpOrderTitle,R.id.cpOrderShow,R.id.llCPOrderAll,R.id.cpOrderMenu,R.id.cpOrderReset,R.id.cpOrderSubmit})
    public void onClickedView(View view ){
        switch (view.getId()){
            case R.id.cpOrderSubmit:
                String gold = cpOrderGold.getText().toString();
                if(Check.isEmpty(gold)){
                    showMessage("请输入投注金额");
                    return;
                }
                if(CPBetManager.getSingleton().onListSize()>0){
                    BetCPOrderDialog.newInstance(CPBetManager.getSingleton().onShowViewListData(),gold,game_code,round,x_session_token).show(getSupportFragmentManager());
                }else{
                    showMessage("请选择玩法");
                }
                break;
            case R.id.cpOrderReset:
                onResetData();
//                allResultList.;
                break;
            case R.id.cpOrderTitle:
            case R.id.cpOrderShow:
                slidingLeftMenu.toggle();
                /*if (mainSwipemenu.isMenuShowing()) {
                    mainSwipemenu.hideMenu();
                } else {
                    mainSwipemenu.showMenu();
                }*/
                /*if (drawer_layout.isDrawerOpen(GravityCompat.START)) {
                    drawer_layout.closeDrawer(GravityCompat.START);
                }*/
//                drawer_layout.openDrawer(cpOrderListLeft);
                break;
            case R.id.llCPOrderAll:
                /*if (mainSwipemenu.isMenuShowing()) {
                    mainSwipemenu.hideMenu();
                }*/
                /*if (drawer_layout.isDrawerOpen(GravityCompat.START)) {
                    drawer_layout.closeDrawer(GravityCompat.START);
                }*/
                break;
            case R.id.cpOrderMenu:
                showMessage("开发中。。。");
                break;
        }

    }

    private void onCpGameItemClick(int position) {
        switch (position){
            case 2:
                orderStype = "bjsc";
                break;
            case 3:
                orderStype = "cqssc";
                break;
            case 10:
                orderStype = "pcdd";
                break;
        }
        onChangeData();
        slidingLeftMenu.toggle();
        cpOrderTitle.setText(cpGameList.get(position).getIconName());
        GameLog.log("你点击了"+cpGameList.get(position).getIconName());
        /*if (mainSwipemenu.isMenuShowing()) {
            mainSwipemenu.hideMenu();
        } else {
            mainSwipemenu.showMenu();
        }*/
//        drawer_layout.closeDrawer(GravityCompat.START);
        onSartTime();
    }

    @Subscribe
    public void onEventMain(CPOrderSuccessEvent cpOrderSuccessEvent){
        presenter.postCPLeftInfo("",x_session_token);
        onResetDataChecked();
        cpOrederListRightGameAdapter.notifyDataSetChanged();
        CPBetManager.getSingleton().onClearData();
        cpOrderNumber.setText(Html.fromHtml("已选中"+onMarkRed(0+"")+"注"));
        cpOrderGold.setText("");
    }


    @Subscribe
    public void onEventMain(LeftMenuEvents leftMenuEvents){
        GameLog.log("LeftMenuEvents "+leftMenuEvents.toString());
        slidingLeftMenu.toggle();
        if (game_code != leftMenuEvents.getEventId()) {
            CPBetManager.getSingleton().onClearData();
            cpOrderNumber.setText(Html.fromHtml("已选中"+onMarkRed(0+"")+"注"));
            titleName = leftMenuEvents.getEventName();
            cpOrderTitle.setText(titleName);
            game_code = leftMenuEvents.getEventId();
            presenter.postNextIssue(game_code, x_session_token);
            presenter.postLastResult(game_code, x_session_token);
            switch (game_code) {
                case "2":
                case "207":
                case "407":
                case "507":
                case "607":
                    orderStype = "cqssc";
                    break;
                case "51":
                case "189":
                case "222":
                case "168"://幸运飞艇 暂无
                    orderStype = "bjsc";
                    break;
                case "47":
                case "3":
                    break;
                case "21"://广东11选5 暂无
                    break;
                case "65"://"北京快乐8" 暂无
                    group = "group5";
                    break;
                case "159":
                case "384":
                    break;
                case "69":
                    group = "group7";
                    //香港六合彩的没有二
                    break;
                case "304":
                    orderStype = "pcdd";
                    break;
            }

            onChangeData();
            onSartTime();
        }
    }


}

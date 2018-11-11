package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.constraint.solver.GoalRow;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.util.DisplayMetrics;
import android.view.Gravity;
import android.view.View;
import android.view.WindowManager;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.brioal.swipemenu.view.SwipeMenu;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.TimeHelper;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CPBJSCResult2;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.CpBJSCResult;
import com.hgapp.a6668.data.NoticeResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.cplist.events.LeftEvents;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

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
import butterknife.OnClick;
import butterknife.Unbinder;

public class CPOrderFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.llCPOrderAll)
    LinearLayout llCPOrderAll;
    @BindView(R.id.cpOrderLotteryOpen1)
    RecyclerView cpOrderLotteryOpen1;
    @BindView(R.id.cpOrderLotteryOpen2)
    RecyclerView cpOrderLotteryOpen2;
    @BindView(R.id.cpOrderGameList)
    RecyclerView cpList;
    @BindView(R.id.cpOrderListLeft)
    RecyclerView cpOrderListLeft;
    @BindView(R.id.cpOrderListRight)
    RecyclerView cpOrderListRight;
    @BindView(R.id.cpOrderUserMoney)
    TextView cpOrderUserMoney;
    @BindView(R.id.cpOrderTitle)
    TextView cpOrderTitle;
    @BindView(R.id.rightCloseLotteryTime)
    TextView rightCloseLotteryTime;
    @BindView(R.id.rightOpenLotteryTime)
    TextView rightOpenLotteryTime;

    private static List<HomePageIcon> cpGameList = new ArrayList<HomePageIcon>();
    private static List<LeftEvents> cpLeftEventList = new ArrayList<LeftEvents>();
    private static List<String> cpLeftEventList2 = new ArrayList<String>();
    private static List<CPOrderAllResult> allResultList = new ArrayList<CPOrderAllResult>();
    private CPOrederListRightGameAdapter cpOrederListRightGameAdapter;
    private CPOrederContentGameAdapter cpOrederContentGameAdapter;
    @BindView(R.id.main_swipemenu)
    SwipeMenu mainSwipemenu;
    Unbinder unbinder;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private ScheduledExecutorService executorService;
    private onWaitingThread onWaitingThread = new onWaitingThread();
    private ScheduledExecutorService executorEndService;
    private onWaitingEndThread onWaitingEndThread = new onWaitingEndThread();
    private int sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
    private int sendEndTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";

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
        cpLeftEventList2.add("3");
        cpLeftEventList2.add("小");
        cpLeftEventList2.add("单");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("龙");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("虎");
        cpLeftEventList2.add("龙");


    }

    public static CPOrderFragment newInstance(List<String> param1) {
        CPOrderFragment fragment = new CPOrderFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_order;
    }

    private void BJPK10(CPBJSCResult2 cpbjscResult){

        for (int k = 0; k < 2; ++k) {
            CPOrderAllResult allResult = new CPOrderAllResult();
            if(k==0){
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                for (int l = 0; l < 11; ++l) {
                    CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                    switch (l) {
                        case 0:
                            cpOrderContentListResult.setOrderContentListName("冠亚和");
                            List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                            for (int j = 0; j < 4; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("冠军大");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_3017());
                                        cpOrderContentResult0.setOrderId("3017");
                                        cpOrderContentResultList.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("冠军小");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_3018());
                                        cpOrderContentResult1.setOrderId("3018");
                                        cpOrderContentResultList.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("冠军单");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_3019());
                                        cpOrderContentResult2.setOrderId("3019");
                                        cpOrderContentResultList.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("冠军双");
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
                            List<CPOrderContentResult> cpOrderContentResultList1 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30013011());
                                        cpOrderContentResult0.setOrderId("3001-3011");
                                        cpOrderContentResultList1.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3001-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30013012());
                                        cpOrderContentResultList1.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3001-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30013013());
                                        cpOrderContentResultList1.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3001-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30013014());
                                        cpOrderContentResultList1.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3001-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30013015());
                                        cpOrderContentResultList1.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
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
                            List<CPOrderContentResult> cpOrderContentResultList2 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3002-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30023011());
                                        cpOrderContentResultList2.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3002-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30023012());
                                        cpOrderContentResultList2.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3002-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30023013());
                                        cpOrderContentResultList2.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3002-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30023014());
                                        cpOrderContentResultList2.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3002-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30023015());
                                        cpOrderContentResultList2.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
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
                            List<CPOrderContentResult> cpOrderContentResultList3 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3003-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30033011());
                                        cpOrderContentResultList3.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3003-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30033012());
                                        cpOrderContentResultList3.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3003-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30033013());
                                        cpOrderContentResultList3.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3003-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30033014());
                                        cpOrderContentResultList3.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3003-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30033015());
                                        cpOrderContentResultList3.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
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
                            List<CPOrderContentResult> cpOrderContentResultList4 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3004-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30043011());
                                        cpOrderContentResultList4.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3004-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30043012());
                                        cpOrderContentResultList4.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3004-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30043013());
                                        cpOrderContentResultList4.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3004-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30043014());
                                        cpOrderContentResultList4.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3004-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30043015());
                                        cpOrderContentResultList4.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
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
                            List<CPOrderContentResult> cpOrderContentResultList5 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3005-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30053011());
                                        cpOrderContentResultList5.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3005-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30053012());
                                        cpOrderContentResultList5.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("龙");
                                        cpOrderContentResult2.setOrderId("3005-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30053013());
                                        cpOrderContentResultList5.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("双");
                                        cpOrderContentResult3.setOrderId("3005-3014");
                                        cpOrderContentResult3.setOrderState(cpbjscResult.getdata_30053014());
                                        cpOrderContentResultList5.add(cpOrderContentResult3);
                                        break;
                                    case 4:
                                        CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                                        cpOrderContentResult4.setOrderName("小");
                                        cpOrderContentResult4.setOrderId("3005-3015");
                                        cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30053015());
                                        cpOrderContentResultList5.add(cpOrderContentResult4);
                                        break;
                                    case 5:
                                        CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                                        cpOrderContentResult5.setOrderName("虎");
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
                            List<CPOrderContentResult> cpOrderContentResultList6 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3006-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30063011());
                                        cpOrderContentResultList6.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3006-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30063012());
                                        cpOrderContentResultList6.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3006-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30063013());
                                        cpOrderContentResultList6.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
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
                            List<CPOrderContentResult> cpOrderContentResultList7 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3007-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30073011());
                                        cpOrderContentResultList7.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3007-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30073012());
                                        cpOrderContentResultList7.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3007-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30073013());
                                        cpOrderContentResultList7.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
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
                            List<CPOrderContentResult> cpOrderContentResultList8 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3008-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30083011());
                                        cpOrderContentResultList8.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3008-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30083012());
                                        cpOrderContentResultList8.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3008-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30083013());
                                        cpOrderContentResultList8.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
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
                            List<CPOrderContentResult> cpOrderContentResultList9 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3009-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30093011());
                                        cpOrderContentResultList9.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3009-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30093012());
                                        cpOrderContentResultList9.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3009-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30093013());
                                        cpOrderContentResultList9.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
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
                            cpOrderContentListResult.setOrderContentListName("第⑩名");
                            List<CPOrderContentResult> cpOrderContentResultList10 = new ArrayList<>();
                            for (int j = 0; j < 6; ++j) {
                                switch (j) {
                                    case 0:
                                        CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                                        cpOrderContentResult0.setOrderName("单");
                                        cpOrderContentResult0.setOrderId("3010-3011");
                                        cpOrderContentResult0.setOrderState(cpbjscResult.getdata_30103011());
                                        cpOrderContentResultList10.add(cpOrderContentResult0);
                                        break;
                                    case 1:
                                        CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                                        cpOrderContentResult1.setOrderName("大");
                                        cpOrderContentResult1.setOrderId("3010-3012");
                                        cpOrderContentResult1.setOrderState(cpbjscResult.getdata_30103012());
                                        cpOrderContentResultList10.add(cpOrderContentResult1);
                                        break;
                                    case 2:
                                        CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                                        cpOrderContentResult2.setOrderName("双");
                                        cpOrderContentResult2.setOrderId("3010-3013");
                                        cpOrderContentResult2.setOrderState(cpbjscResult.getdata_30103013());
                                        cpOrderContentResultList10.add(cpOrderContentResult2);
                                        break;
                                    case 3:
                                        CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                                        cpOrderContentResult3.setOrderName("小");
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
                List<CPOrderContentListResult> CPOrderContentListResult = new ArrayList<CPOrderContentListResult>();
                allResult.setOrderAllName("冠亚和");
                CPOrderContentListResult cpOrderContentListResult = new CPOrderContentListResult();
                cpOrderContentListResult.setOrderContentListName("冠、亚军 组合");

                List<CPOrderContentResult> cpOrderContentResultList = new ArrayList<>();
                CPOrderContentResult cpOrderContentResult0 = new CPOrderContentResult();
                cpOrderContentResult0.setOrderName("冠军大");
                cpOrderContentResult0.setOrderState(cpbjscResult.getdata_3017());
                cpOrderContentResult0.setOrderId("3017");
                cpOrderContentResultList.add(cpOrderContentResult0);

                CPOrderContentResult cpOrderContentResult1 = new CPOrderContentResult();
                cpOrderContentResult1.setOrderName("冠军小");
                cpOrderContentResult1.setOrderState(cpbjscResult.getdata_3018());
                cpOrderContentResult1.setOrderId("3018");
                cpOrderContentResultList.add(cpOrderContentResult1);

                CPOrderContentResult cpOrderContentResult2 = new CPOrderContentResult();
                cpOrderContentResult2.setOrderName("冠军单");
                cpOrderContentResult2.setOrderState(cpbjscResult.getdata_3019());
                cpOrderContentResult2.setOrderId("3019");
                cpOrderContentResultList.add(cpOrderContentResult2);

                CPOrderContentResult cpOrderContentResult3 = new CPOrderContentResult();
                cpOrderContentResult3.setOrderName("冠军双");
                cpOrderContentResult3.setOrderState(cpbjscResult.getdata_3020());
                cpOrderContentResult3.setOrderId("3020");
                cpOrderContentResultList.add(cpOrderContentResult3);

                CPOrderContentResult cpOrderContentResult4 = new CPOrderContentResult();
                cpOrderContentResult4.setOrderName("3");
                cpOrderContentResult4.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult4.setOrderId("3021-3");
                cpOrderContentResultList.add(cpOrderContentResult4);

                CPOrderContentResult cpOrderContentResult5 = new CPOrderContentResult();
                cpOrderContentResult5.setOrderName("4");
                cpOrderContentResult5.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult5.setOrderId("3021-4");
                cpOrderContentResultList.add(cpOrderContentResult5);

                CPOrderContentResult cpOrderContentResult6 = new CPOrderContentResult();
                cpOrderContentResult6.setOrderName("5");
                cpOrderContentResult6.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult6.setOrderId("3021-5");
                cpOrderContentResultList.add(cpOrderContentResult6);

                CPOrderContentResult cpOrderContentResult7 = new CPOrderContentResult();
                cpOrderContentResult7.setOrderName("6");
                cpOrderContentResult7.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult7.setOrderId("3021-6");
                cpOrderContentResultList.add(cpOrderContentResult7);

                CPOrderContentResult cpOrderContentResult8 = new CPOrderContentResult();
                cpOrderContentResult8.setOrderName("7");
                cpOrderContentResult8.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult8.setOrderId("3021-7");
                cpOrderContentResultList.add(cpOrderContentResult8);

                CPOrderContentResult cpOrderContentResult9 = new CPOrderContentResult();
                cpOrderContentResult9.setOrderName("8");
                cpOrderContentResult9.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult9.setOrderId("3021-8");
                cpOrderContentResultList.add(cpOrderContentResult9);

                CPOrderContentResult cpOrderContentResult10 = new CPOrderContentResult();
                cpOrderContentResult10.setOrderName("9");
                cpOrderContentResult10.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult10.setOrderId("3021-9");
                cpOrderContentResultList.add(cpOrderContentResult10);

                CPOrderContentResult cpOrderContentResult11 = new CPOrderContentResult();
                cpOrderContentResult11.setOrderName("10");
                cpOrderContentResult11.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult11.setOrderId("3021-10");
                cpOrderContentResultList.add(cpOrderContentResult11);

                CPOrderContentResult cpOrderContentResult12 = new CPOrderContentResult();
                cpOrderContentResult12.setOrderName("11");
                cpOrderContentResult12.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult12.setOrderId("3021-11");
                cpOrderContentResultList.add(cpOrderContentResult12);

                CPOrderContentResult cpOrderContentResult13 = new CPOrderContentResult();
                cpOrderContentResult13.setOrderName("12");
                cpOrderContentResult13.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult13.setOrderId("3021-12");
                cpOrderContentResultList.add(cpOrderContentResult13);

                CPOrderContentResult cpOrderContentResult14 = new CPOrderContentResult();
                cpOrderContentResult14.setOrderName("13");
                cpOrderContentResult14.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult14.setOrderId("3021-13");
                cpOrderContentResultList.add(cpOrderContentResult14);

                CPOrderContentResult cpOrderContentResult15 = new CPOrderContentResult();
                cpOrderContentResult15.setOrderName("14");
                cpOrderContentResult15.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult15.setOrderId("3021-14");
                cpOrderContentResultList.add(cpOrderContentResult15);

                CPOrderContentResult cpOrderContentResult16 = new CPOrderContentResult();
                cpOrderContentResult16.setOrderName("15");
                cpOrderContentResult16.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult16.setOrderId("3021-15");
                cpOrderContentResultList.add(cpOrderContentResult16);

                CPOrderContentResult cpOrderContentResult17 = new CPOrderContentResult();
                cpOrderContentResult17.setOrderName("16");
                cpOrderContentResult17.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult17.setOrderId("3021-16");
                cpOrderContentResultList.add(cpOrderContentResult17);

                CPOrderContentResult cpOrderContentResult18 = new CPOrderContentResult();
                cpOrderContentResult18.setOrderName("17");
                cpOrderContentResult18.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult18.setOrderId("3021-17");
                cpOrderContentResultList.add(cpOrderContentResult18);

                CPOrderContentResult cpOrderContentResult19 = new CPOrderContentResult();
                cpOrderContentResult19.setOrderName("18");
                cpOrderContentResult19.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult19.setOrderId("3021-18");
                cpOrderContentResultList.add(cpOrderContentResult19);

                CPOrderContentResult cpOrderContentResult20 = new CPOrderContentResult();
                cpOrderContentResult20.setOrderName("19");
                cpOrderContentResult20.setOrderState(cpbjscResult.getdata_30213());
                cpOrderContentResult20.setOrderId("3021-19");
                cpOrderContentResultList.add(cpOrderContentResult20);

                cpOrderContentListResult.setData(cpOrderContentResultList);
                CPOrderContentListResult.add(cpOrderContentListResult);
                allResult.setData(CPOrderContentListResult);
            }else if(k==2){
                allResult.setOrderAllName("1-5名");
            }else{
                allResult.setOrderAllName("6-10名");
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

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        /*String  data = getFromAssets("data.json").replace("-","_");
        GameLog.log("屏幕的宽度："+data);
        CpBJSCResult cpBJSCResult = JSON.parseObject(data, CpBJSCResult.class);
        GameLog.log("屏幕的宽度："+cpBJSCResult.toString());*/
        String  data = getFromAssets("data_bak.json");
        //GameLog.log("屏幕的宽度："+data);
        Gson gson = new Gson();
        CPBJSCResult2 cpbjscResult2 = gson.fromJson(data,CPBJSCResult2.class);
        //CPBJSCResult2 cpBJSCResult = JSON.parseObject(data, CPBJSCResult2.class);
        GameLog.log("屏幕的宽度："+cpbjscResult2.toString());
        BJPK10(cpbjscResult2);
        /*WindowManager wm = (WindowManager) getContext().getSystemService(Context.WINDOW_SERVICE);
        DisplayMetrics metrics = new DisplayMetrics();
        wm.getDefaultDisplay().getMetrics(metrics);*/
        /*mScreenWidth = metrics.widthPixels;
        mScreenHeight = metrics.heightPixels;*/
        //mainSwipemenu.setMenuOffset(metrics.widthPixels-Integer.parseInt(SizeUtil.Dp2Px(getContext(),50)+""));
        LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpList.setLayoutManager(gridLayoutManager);
        cpList.setHasFixedSize(true);
        cpList.setNestedScrollingEnabled(false);
        cpList.setAdapter(new CPOrederGameAdapter(getContext(), R.layout.item_cp_order_list, cpGameList));

        LinearLayoutManager linearLayoutManagerLeft = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpOrderListLeft.setLayoutManager(linearLayoutManagerLeft);
        cpOrderListLeft.setHasFixedSize(true);
        cpOrderListLeft.setNestedScrollingEnabled(false);
        cpOrderListLeft.setAdapter(new CPOrederListLeftGameAdapter(getContext(), R.layout.item_cp_order_left_list, allResultList));
        LinearLayoutManager linearLayoutManagerRight = new LinearLayoutManager(getContext(),LinearLayoutManager.VERTICAL, false);
        cpOrderListRight.setLayoutManager(linearLayoutManagerRight);
        cpOrderListRight.setHasFixedSize(true);
        cpOrderListRight.setNestedScrollingEnabled(false);
        showContentView(1);

        LinearLayoutManager cpOrderLotteryOpen11 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
        cpOrderLotteryOpen1.setLayoutManager(cpOrderLotteryOpen11);
        cpOrderLotteryOpen1.setHasFixedSize(true);
        cpOrderLotteryOpen1.setNestedScrollingEnabled(false);
        cpOrderLotteryOpen1.setAdapter(new Open1GameAdapter(getContext(), R.layout.item_cp_order_open_1, cpLeftEventList));

        LinearLayoutManager cpOrderLotteryOpen22 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
        cpOrderLotteryOpen2.setLayoutManager(cpOrderLotteryOpen22);
        cpOrderLotteryOpen2.setHasFixedSize(true);
        cpOrderLotteryOpen2.setNestedScrollingEnabled(false);
        cpOrderLotteryOpen2.setAdapter(new Open2GameAdapter(getContext(), R.layout.item_cp_order_open_2, cpLeftEventList2));
    }
    class Open1GameAdapter extends AutoSizeRVAdapter<LeftEvents> {
        private Context context;

        public Open1GameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, LeftEvents data, final int position) {
            switch (data.getEventId()){
                case "1":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_one);
                    break;
                case "2":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_two);
                    break;
                case "3":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_three);
                    break;
                case "4":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_four);
                    break;
                case "5":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_five);
                    break;
                case "6":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_six);
                    break;
                case "7":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_seven);
                    break;
                case "8":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_eight);
                    break;
                case "9":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_nine);
                    break;
                case "10":
                    holder.setImageResource(R.id.itemOrderOpen1,R.mipmap.cp_order_ten);
                    break;
            }

        }
    }

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

    class CPOrederListLeftGameAdapter extends AutoSizeRVAdapter<CPOrderAllResult> {
        private Context context;

        public CPOrederListLeftGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, CPOrderAllResult data, final int position) {
           /* if(data.isEventChecked()){
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_checked);
            }else{
                holder.setImageResource(R.id.itemOrderLeftListIV,R.drawable.cp_circle_normal);
            }*/
            holder.setText(R.id.itemOrderLeftListTV, data.getOrderAllName());
            holder.setOnClickListener(R.id.itemOrderLeftListTV, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    onRefreshRight(position);
                }
            });
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        allResultList.clear();
    }

    private void showContentView(int postion){

        /*cpOrederListRightGameAdapter  = null;
        cpOrederListRightGameAdapter = new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(postion).getData());*/
        cpOrderListRight.setAdapter(new CPOrederListRightGameAdapter(getContext(), R.layout.item_cp_order_content1, allResultList.get(postion).getData()));

    }


    private void onRefreshRight(int position){
        showMessage("刷新后边的数据"+position);
        showContentView(position);
    }

    class CPOrederListRightGameAdapter extends AutoSizeRVAdapter<CPOrderContentListResult> {
        private Context context;

        public CPOrederListRightGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, CPOrderContentListResult data, final int position) {
            holder.setText(R.id.cpOrderContentName1, data.getOrderContentListName());
            GridLayoutManager gridLayoutManager = null;
            if(position==1||position==2||position==3||position==4||position==5){
                gridLayoutManager= new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
            }else{
                gridLayoutManager= new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
            }
            RecyclerView recyclerView = holder.getView(R.id.cpOrderContentList1);
            recyclerView.setLayoutManager(gridLayoutManager);
            recyclerView.setHasFixedSize(true);
            recyclerView.setNestedScrollingEnabled(false);
           // recyclerView.addItemDecoration(new GridRvItemDecoration(getContext()));
            cpOrederContentGameAdapter = null;
            cpOrederContentGameAdapter = new CPOrederContentGameAdapter(getContext(), R.layout.item_cp_order_content2, data.getData());
            recyclerView.setAdapter(cpOrederContentGameAdapter);
        }
    }

    class CPOrederContentGameAdapter extends AutoSizeRVAdapter<CPOrderContentResult> {
        private Context context;

        public CPOrederContentGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder,final CPOrderContentResult data, final int position) {
            holder.setText(R.id.cpOrderContentName2, data.getOrderName());
            holder.setText(R.id.cpOrderContentState, data.getOrderState());
            if(data.isChecked()){
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.cp_order_tv_clicked);
            }else{
                holder.setBackgroundRes(R.id.cpOrderContentItem,R.color.title_text);
            }
            holder.setOnClickListener(R.id.cpOrderContentItem, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(!data.isChecked()){
                        data.setChecked(true);
                    }else{
                        data.setChecked(false);
                    }
                    GameLog.log("下注的id是："+data.getOrderId());
                    cpOrederContentGameAdapter.notifyDataSetChanged();
                    /*cpOrederListRightGameAdapter.notifyDataSetChanged();
                    cpOrderListRight.scrollTo(10,0);*/
                }
            });
        }
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
                        pop();
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
    public void setPresenter(AGListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult) {

    }

    @Override
    public void postCheckAgLiveAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCheckAgGameAccountResult(CheckAgLiveResult checkAgLiveResult) {
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("用户的真人账户：" + personBalance.getBalance_ag());
    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表：" + agLiveResult);
    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }


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

    public void onBackPressed() {
        if (mainSwipemenu.isMenuShowing()) {
            mainSwipemenu.hideMenu();
        }
    }

    //等待时长
    class onWaitingThread implements Runnable {
        @Override
        public void run() {
            if (sendAuthTime-- <= 0) {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        onSendAuthCode();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
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
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        onSendEndCode();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
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
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(onWaitingThread, 0, 1000, TimeUnit.MILLISECONDS);
    }

    //计数器，用于倒计时使用
    private void onSendEndCode() {
        GameLog.log("-----开始-----");
        if(null!=executorEndService){
            executorEndService.shutdownNow();
            executorEndService.shutdown();
            executorEndService = null;
        }
        sendEndTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
        executorEndService = Executors.newScheduledThreadPool(1);
        executorEndService.scheduleAtFixedRate(onWaitingEndThread, 0, 1000, TimeUnit.MILLISECONDS);
    }

    @OnClick({R.id.cpOrderTitle,R.id.cpOrderShow,R.id.llCPOrderAll,R.id.cpOrderMenu})
    public void onClickedView(View view ){
        switch (view.getId()){
            case R.id.cpOrderTitle:
            case R.id.cpOrderShow:
                if (mainSwipemenu.isMenuShowing()) {
                    mainSwipemenu.hideMenu();
                } else {
                    mainSwipemenu.showMenu();
                }
                break;
            case R.id.llCPOrderAll:
                if (mainSwipemenu.isMenuShowing()) {
                    mainSwipemenu.hideMenu();
                }
                break;
            case R.id.cpOrderMenu:
                showMessage("开发中。。。");
                break;
        }

    }

    private void onCpGameItemClick(int position) {
        cpOrderTitle.setText(cpGameList.get(position).getIconName());
        GameLog.log("你点击了"+cpGameList.get(position).getIconName());
        if (mainSwipemenu.isMenuShowing()) {
            mainSwipemenu.hideMenu();
        } else {
            mainSwipemenu.showMenu();
        }
        onSartTime();
    }
}

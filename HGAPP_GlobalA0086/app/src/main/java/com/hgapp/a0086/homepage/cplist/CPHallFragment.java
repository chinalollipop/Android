package com.hgapp.a0086.homepage.cplist;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hgapp.a0086.CPInjections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.BaseActivity2;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.DateHelper;
import com.hgapp.a0086.common.util.GameShipHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.TimeHelper;
import com.hgapp.a0086.common.widgets.CustomPopWindow;
import com.hgapp.a0086.common.widgets.GridRvItemDecoration;
import com.hgapp.a0086.common.widgets.MarqueeTextView;
import com.hgapp.a0086.data.CPHallResult;
import com.hgapp.a0086.data.CPLeftInfoResult;
import com.hgapp.a0086.data.CPNoteResult;
import com.hgapp.a0086.data.PersonBalanceResult;
import com.hgapp.a0086.homepage.cplist.bet.betrecords.CPBetRecordsFragment;
import com.hgapp.a0086.homepage.cplist.bet.betrecords.betlistrecords.CPBetListRecordsFragment;
import com.hgapp.a0086.homepage.cplist.bet.betrecords.betnow.CPBetNowFragment;
import com.hgapp.a0086.homepage.cplist.hall.CPHallListContract;
import com.hgapp.a0086.homepage.cplist.lottery.CPLotteryListFragment;
import com.hgapp.a0086.homepage.cplist.me.CPMeFragment;
import com.hgapp.a0086.homepage.cplist.role.CPServiceActivity;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class CPHallFragment extends BaseActivity2 implements CPHallListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpHallBackHome)
    ImageView cpHallBackHome;
    @BindView(R.id.cpPageBulletin)
    MarqueeTextView cpPageBulletin;
    @BindView(R.id.cpHallList)
    RecyclerView cpHallList;
    private  List<CPHallIcon> cpGameList = new ArrayList<CPHallIcon>();
    @BindView(R.id.cpHallMenu)
    ImageView cpHallMenu;
    @BindView(R.id.cpHallUserName)
    TextView cpHallUserName;
    @BindView(R.id.cpHallUserMoney)
    TextView cpHallUserMoney;
    Unbinder unbinder;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CPHallListContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    private ScheduledExecutorService executorService;
    private ScheduledExecutorService executorService2;
    private HallPageGameAdapter hallPageGameAdapter;
    private long cpHallIcon0, cpHallIcon1, cpHallIcon2, cpHallIcon3, cpHallIcon4, cpHallIcon5, cpHallIcon6, cpHallIcon7,
            cpHallIcon8, cpHallIcon9, cpHallIcon10, cpHallIcon11, cpHallIcon12, cpHallIcon13,cpHallIcon14;
    private int scpHallIcon0, scpHallIcon1, scpHallIcon2, scpHallIcon3, scpHallIcon4, scpHallIcon5, scpHallIcon6, scpHallIcon7,
            scpHallIcon8, scpHallIcon9, scpHallIcon10, scpHallIcon11, scpHallIcon12, scpHallIcon13,scpHallIcon14;
    private int sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
    private CustomPopWindow mCustomPopWindowIn;
    TextView moneyText;
    String moneyStr="0.00";


   /* public static CPHallFragment newInstance(List<String> param1) {
        CPHallFragment fragment = new CPHallFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        CPInjections.inject(fragment,null);
        fragment.setArguments(args);
        return fragment;
    }*/

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
        /*if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }*/
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_hall;
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        if (null != executorService) {
            GameLog.log("关闭计数任务1");
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }

        if (null != executorService2) {
            GameLog.log("关闭计数任务2");
            executorService2.shutdownNow();
            executorService2.shutdown();
            executorService2 = null;
        }
    }

    private void iniData(){
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_bjsc), R.mipmap.cp_bjsc, 0,51));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_hlsx), R.mipmap.cp_cqssc, 0,2));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_jssc), R.mipmap.cp_jsft, 0,189));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_jsft), R.mipmap.cp_jsfc, 0,222));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_ffc), R.mipmap.cp_ffc, 0,207));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_sfc), R.mipmap.cp_sfc, 0,407));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_wfc), R.mipmap.cp_wfc, 0,507));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_efc), R.mipmap.cp_efc, 0,607));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_pcdd), R.mipmap.cp_pcdd, 0,304));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_k3), R.mipmap.cp_js, 0,159));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_xync), R.mipmap.cp_xync, 0,47));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_klsfc), R.mipmap.cp_klsfc, 0,3));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_lhc), R.mipmap.cp_lhc, 0,69));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_jsk3), R.mipmap.cp_js, 0,384));
        cpGameList.add(new CPHallIcon(getString(R.string.lotter_xyft), R.mipmap.gf_xyft, 0,168));
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        iniData();
        cpHallUserName.setText(Html.fromHtml("<u>"+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_USERNAME)+"</u>"));
        //cpHallUserName.getPaint().setFlags(Paint.UNDERLINE_TEXT_FLAG); //下划线
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService2 = Executors.newScheduledThreadPool(1);
        executorService2.scheduleAtFixedRate(new Runnable() {
            @Override
            public void run() {
                presenter.postCPLeftInfo("");
                presenter.postCPHallList("");
            }
         }, 0, 10000, TimeUnit.MILLISECONDS);
        CPNoteResult noticeResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_HOME_NOTICE), CPNoteResult.class);
        if (!Check.isNull(noticeResult)) {
            List<String> stringList = new ArrayList<String>();
            int size = noticeResult.getData().size();
            for (int i = 0; i < size; ++i) {
                stringList.add(noticeResult.getData().get(i).getComment());
            }
            GameLog.log("本地的公告 "+stringList);
            if(stringList.size()==1){
                stringList.add(noticeResult.getData().get(0).getComment());
            }
            cpPageBulletin.setContentList(stringList);
        }
        /*cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.VERTICAL,5,getContext().getColor(R.color.textview_normal),8));
        cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.HORIZONTAL,5,getContext().getColor(R.color.textview_normal),8));*/
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
        cpHallList.setLayoutManager(gridLayoutManager);
        cpHallList.setHasFixedSize(true);
        cpHallList.setNestedScrollingEnabled(false);
        cpHallList.addItemDecoration(new GridRvItemDecoration(getContext()));
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        executorService = Executors.newScheduledThreadPool(1);
        if(hallPageGameAdapter == null){
            hallPageGameAdapter = new HallPageGameAdapter(getContext(), R.layout.item_cp_hall, cpGameList);
        }
        cpHallList.setAdapter(hallPageGameAdapter);
        cpHallIcon0 = 0;
        cpHallIcon1 = 0;
        cpHallIcon2 = 0;
        cpHallIcon3 = 0;
        cpHallIcon4 = 0;
        cpHallIcon5 = 0;
        cpHallIcon6 = 0;
        cpHallIcon7 = 0;
        cpHallIcon8 = 0;
        cpHallIcon9 = 0;
        cpHallIcon10 = 0;
        cpHallIcon11 = 0;
        cpHallIcon12 = 0;
        cpHallIcon13 = 0;
        cpHallIcon14 = 0;
    }

    private synchronized void onRequestData() {
//        presenter.postCPHallList("");
        /*if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        GameLog.log("=================================================");
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService = Executors.newScheduledThreadPool(14);
        cpHallIcon0 = 11000;
        cpHallIcon1 = 190;
        cpHallIcon2 = 90;
        cpHallIcon3 = 10;
        cpHallIcon4 = 90;
        cpHallIcon5 = 13000;
        cpHallIcon6 = 90;
        cpHallIcon7 = 150;
        cpHallIcon8 = 90;
        cpHallIcon9 = 70;
        cpHallIcon10 = 19000;
        cpHallIcon11 = 20;
        cpHallIcon12 = 190;
        cpHallIcon13 = 50;
        hallPageGameAdapter = null;
        hallPageGameAdapter = new HallPageGameAdapter(getContext(), R.layout.item_cp_hall, cpGameList);
        cpHallList.setAdapter(hallPageGameAdapter);
        hallPageGameAdapter.notifyDataSetChanged();*/
    }

    @OnClick({R.id.cpHallBackHome, R.id.cpHallMenu,R.id.cpHallUserName})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cpHallBackHome:
                finish();
                break;
            case R.id.cpHallMenu:
                showPopMenuIn();
                presenter.postCPLeftInfo("");
                break;
            case R.id.cpHallUserName:
                Intent intent2  = new Intent(getContext(),CPMeFragment.class);
                intent2.putExtra("gameId","51");
                intent2.putExtra("gameName",getString(R.string.lotter_bjsc));
                startActivity(intent2);
                break;
        }
    }

    private void showPopMenuIn(){
        View contentView = LayoutInflater.from(getContext()).inflate(R.layout.pop_cp_hall,null);

        View.OnClickListener listener = new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                /*if(mCustomPopWindow!=null){
                    mCustomPopWindow.dissmiss();
                }*/
                switch (v.getId()){
                    case R.id.popCPOrder2:
                        Intent intent  = new Intent(getContext(),CPBetListRecordsFragment.class);
                        intent.putExtra("gameForm","today");
                        intent.putExtra("gameTime",DateHelper.getToday());
                        startActivity(intent);
                        break;
                    case R.id.popCPOrder1:
                        Intent intent1  = new Intent(getContext(),CPBetNowFragment.class);
                        intent1.putExtra("gameId","51");
                        intent1.putExtra("gameName",getString(R.string.lotter_bjsc));
                        startActivity(intent1);
                        break;
                    case R.id.popCPOrder3:
                        Intent intent3  = new Intent(getContext(),CPBetRecordsFragment.class);
                        intent3.putExtra("gameId","51");
                        intent3.putExtra("gameName",getString(R.string.lotter_bjsc));
                        startActivity(intent3);
                        break;
                    case R.id.popCPOrder4:
                        Intent intent4 = new Intent(getContext(),CPLotteryListFragment.class);
                        intent4.putExtra("gameId","51");
                        intent4.putExtra("gameName",getString(R.string.lotter_bjsc));
                        startActivity(intent4);
                        break;
                    case R.id.popCPOrder5:
                        Intent intent6 = new Intent(getContext(),CPServiceActivity.class);
                        intent6.putExtra("gameId","51");
                        intent6.putExtra("gameName",titleName);
                        startActivity(intent6);
                        break;

                }
                //showMessage(showContent);
                mCustomPopWindowIn.dissmiss();
            }
        };
        //处理popWindow 显示内容
        moneyText = contentView.findViewById(R.id.popCPOrder1);
        moneyText.setOnClickListener(listener);
        contentView.findViewById(R.id.popCPOrder2).setOnClickListener(listener);
        contentView.findViewById(R.id.popCPOrder3).setOnClickListener(listener);
        contentView.findViewById(R.id.popCPOrder4).setOnClickListener(listener);
        contentView.findViewById(R.id.popCPOrder5).setOnClickListener(listener);
        moneyText.setText(Html.fromHtml(getString(R.string.lotter_me_jszd)+"<br>"+onMarkRed("("+GameShipHelper.formatMoney(moneyStr)+")")));

        //创建并显示popWindow
        /*if(mCustomPopWindow !=null){
            mCustomPopWindow.dissmiss();
        }else{*/
        mCustomPopWindowIn= new CustomPopWindow.PopupWindowBuilder(getContext())
                .setView(contentView)
                .size( getWindowManager().getDefaultDisplay().getWidth() * 1 / 4, getWindowManager().getDefaultDisplay().getHeight()* 1 / 2)
                .enableBackgroundDark(true)
                .create()
                .showAsDropDown(cpHallMenu,0,20);
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#fdb22b'>" + sign+"</font>";
    }

    @Override
    public void setPresenter(CPHallListContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void postCPHallListResult(CPHallResult cpHallResult) {

        GameLog.log("彩票大厅的数据 "+cpHallResult.toString());
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        executorService = Executors.newScheduledThreadPool(1);
        /*cpGameList.clear();
        cpGameList.add(new CPHallIcon("北京赛车", R.mipmap.cp_bjsc, 0,51));
        cpGameList.add(new CPHallIcon("重庆时时彩", R.mipmap.cp_cqssc, 0,2));
        cpGameList.add(new CPHallIcon("极速赛车", R.mipmap.cp_jsfc, 0,189));
        cpGameList.add(new CPHallIcon("极速飞艇", R.mipmap.cp_jsft, 0,222));
        cpGameList.add(new CPHallIcon("分分彩", R.mipmap.cp_ffc, 0,207));
        cpGameList.add(new CPHallIcon("三分彩", R.mipmap.cp_sfc, 0,407));
        cpGameList.add(new CPHallIcon("五分彩", R.mipmap.cp_wfc, 0,507));
        cpGameList.add(new CPHallIcon("腾讯二分彩", R.mipmap.cp_efc, 0,607));
        cpGameList.add(new CPHallIcon("PC蛋蛋", R.mipmap.cp_pcdd, 0,304));
        cpGameList.add(new CPHallIcon("江苏快3", R.mipmap.cp_js, 0,159));
        cpGameList.add(new CPHallIcon("幸运农场", R.mipmap.cp_xync, 0,47));
        cpGameList.add(new CPHallIcon("快乐十分", R.mipmap.cp_klsfc, 0,3));
        cpGameList.add(new CPHallIcon("香港六合彩", R.mipmap.cp_lhc, 0,69));
        cpGameList.add(new CPHallIcon("极速快三", R.mipmap.cp_jss, 0,384));*/
       /* CPHallResult.data51Bean _51Bean = cpHallResult.getdata51();
        cpGameList.add(new CPHallIcon("北京赛车", R.mipmap.cp_bjsc, 0,51));
        cpGameList.add(new CPHallIcon("重庆时时彩", R.mipmap.cp_jsft, 0,2));
        cpGameList.add(new CPHallIcon("极速赛车", R.mipmap.cp_cqssc, 0,189));
        cpGameList.add(new CPHallIcon("极速飞艇", R.mipmap.cp_jsfc, 0,222));
        cpGameList.add(new CPHallIcon("分分彩", R.mipmap.cp_ffc, 0,207));
        cpGameList.add(new CPHallIcon("三分彩", R.mipmap.cp_lhc, 0,407));
        cpGameList.add(new CPHallIcon("五分彩", R.mipmap.cp_lhc, 0,507));
        cpGameList.add(new CPHallIcon("腾讯二分彩", R.mipmap.cp_lhc, 0,607));
        cpGameList.add(new CPHallIcon("PC蛋蛋", R.mipmap.cp_pcdd, 0,304));
        cpGameList.add(new CPHallIcon("江苏快3", R.mipmap.cp_js, 0,159));
        cpGameList.add(new CPHallIcon("幸运农场", R.mipmap.cp_xync, 0,47));
        cpGameList.add(new CPHallIcon("快乐十分", R.mipmap.cp_klsfc, 0,3));
        cpGameList.add(new CPHallIcon("香港六合彩", R.mipmap.cp_js, 0,47));
        cpGameList.add(new CPHallIcon("极速快三", R.mipmap.cp_more, 0,384));
        CPHallIcon cpHallIcon = new CPHallIcon();
        cpHallIcon.setIsopen(_51Bean.getIsopen());
        cpHallIcon.setEndtime(_51Bean.getEndtime());
        cpHallIcon.setGameId(_51Bean.getGameId());
        cpHallIcon.setIconName(_51Bean.getEndtime());
        cpHallIcon.setEndtime(_51Bean.getEndtime());
        cpGameList.

        cpGameList.get(0).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(1).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(2).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(3).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(4).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(5).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(6).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(7).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(8).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(9).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(10).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(11).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(12).setIsopen(cpHallResult.getdata51().getIsopen());
        cpGameList.get(13).setIsopen(cpHallResult.getdata51().getIsopen());*/
        //String systTime =TimeUtils.getDateAndTimeString();
        //String systTime = TimeUtils.convertToDetailTime(TrueTime.now());
       if(Check.isNull(cpHallResult.getdata51())||Check.isNumericNull(cpHallResult.getdata51().getEndtime())){
           cpHallIcon0 = 0;
           scpHallIcon0 = 1;
       }else{
           scpHallIcon0 = 0;
           cpHallIcon0 = TimeHelper.timeToSecond(cpHallResult.getdata51().getEndtime(),cpHallResult.getdata51().getServerTime());
       }
        if(Check.isNull(cpHallResult.getdata2())||Check.isNumericNull(cpHallResult.getdata2().getEndtime())){
            cpHallIcon1 = 0;
            scpHallIcon1 = 1;
        }else {
            scpHallIcon1 = 0;
            cpHallIcon1 = TimeHelper.timeToSecond(cpHallResult.getdata2().getEndtime(),cpHallResult.getdata2().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata189())||Check.isNumericNull(cpHallResult.getdata189().getEndtime())){
            cpHallIcon2 = 0;
            scpHallIcon2 = 1;
        }else {
            scpHallIcon2 = 0;
            cpHallIcon2 =  TimeHelper.timeToSecond(cpHallResult.getdata189().getEndtime(),cpHallResult.getdata189().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata222())||Check.isNumericNull(cpHallResult.getdata222().getEndtime())){
            cpHallIcon3 = 0;
            scpHallIcon3 = 1;
        }else {
            scpHallIcon3 = 0;
            cpHallIcon3 =  TimeHelper.timeToSecond(cpHallResult.getdata222().getEndtime(),cpHallResult.getdata222().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata207())||Check.isNumericNull(cpHallResult.getdata207().getEndtime())){
            cpHallIcon4 = 0;
            scpHallIcon4 = 1;
        }else {
            scpHallIcon4 = 0;
            cpHallIcon4 =  TimeHelper.timeToSecond(cpHallResult.getdata207().getEndtime(),cpHallResult.getdata207().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata407())||Check.isNumericNull(cpHallResult.getdata407().getEndtime())){
            cpHallIcon5 = 0;
            scpHallIcon5 = 1;
        }else {
            scpHallIcon5 = 0;
            cpHallIcon5 = TimeHelper.timeToSecond(cpHallResult.getdata407().getEndtime(),cpHallResult.getdata407().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata507())||Check.isNumericNull(cpHallResult.getdata507().getEndtime())){
            cpHallIcon6 = 0;
            scpHallIcon6 = 1;
        }else {
            scpHallIcon6 = 0;
            cpHallIcon6 = TimeHelper.timeToSecond(cpHallResult.getdata507().getEndtime(),cpHallResult.getdata507().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata607())||Check.isNumericNull(cpHallResult.getdata607().getEndtime())){
            cpHallIcon7 = 0;
            scpHallIcon7 = 1;
        }else {
            scpHallIcon7 = 0;
            cpHallIcon7 = TimeHelper.timeToSecond(cpHallResult.getdata607().getEndtime(),cpHallResult.getdata607().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata304())||Check.isNumericNull(cpHallResult.getdata304().getEndtime())){
            cpHallIcon8 = 0;
            scpHallIcon8 = 1;
        }else {
            scpHallIcon8 = 0;
            cpHallIcon8 = TimeHelper.timeToSecond(cpHallResult.getdata304().getEndtime(),cpHallResult.getdata304().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata159())||Check.isNumericNull(cpHallResult.getdata159().getEndtime())){
            cpHallIcon9 = 0;
            scpHallIcon9 = 1;
        }else {
            scpHallIcon9 = 0;
            cpHallIcon9 = TimeHelper.timeToSecond(cpHallResult.getdata159().getEndtime(),cpHallResult.getdata159().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata47())||Check.isNumericNull(cpHallResult.getdata47().getEndtime())){
            cpHallIcon10 = 0;
            scpHallIcon10 = 1;
        }else {
            scpHallIcon10 = 0;
            cpHallIcon10 = TimeHelper.timeToSecond(cpHallResult.getdata47().getEndtime(),cpHallResult.getdata47().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata3())||Check.isNumericNull(cpHallResult.getdata3().getEndtime())){
            cpHallIcon11 = 0;
            scpHallIcon11 = 1;
        }else {
            scpHallIcon11 = 0;
            cpHallIcon11 = TimeHelper.timeToSecond(cpHallResult.getdata3().getEndtime(),cpHallResult.getdata3().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata69())||Check.isNumericNull(cpHallResult.getdata69().getEndtime())){
            cpHallIcon12 = 0;
            scpHallIcon12 = 1;
        }else {
            scpHallIcon12 = 0;
            cpHallIcon12 = TimeHelper.timeToSecond(cpHallResult.getdata69().getEndtime(),cpHallResult.getdata69().getServerTime());
        }
        if(Check.isNull(cpHallResult.getdata384())||Check.isNumericNull(cpHallResult.getdata384().getEndtime())){
            cpHallIcon13 = 0;
            scpHallIcon13 = 1;
        }else {
            scpHallIcon13 = 0;
            cpHallIcon13 = TimeHelper.timeToSecond(cpHallResult.getdata384().getEndtime(),cpHallResult.getdata384().getServerTime()) ;
        }
        if(Check.isNull(cpHallResult.getData168())||Check.isNumericNull(cpHallResult.getData168().getEndtime())){
            cpHallIcon14 = 0;
            scpHallIcon14 = 1;
        }else {
            scpHallIcon14 = 0;
            cpHallIcon14 = TimeHelper.timeToSecond(cpHallResult.getData168().getEndtime(),cpHallResult.getData168().getServerTime()) ;
        }
        GameLog.log("最后的时间  "+cpHallIcon0+"|"+cpHallIcon1+"|"+cpHallIcon2+"|"+cpHallIcon3+"|"+cpHallIcon4+"|"+cpHallIcon5+"|"+cpHallIcon6+"|"+cpHallIcon7+"|"+cpHallIcon8+"|"+cpHallIcon9+"|"+cpHallIcon10+"|"+cpHallIcon11+"|"+cpHallIcon12+"|"+cpHallIcon13);
        hallPageGameAdapter.notifyDataSetChanged();
        cpHallList.scrollToPosition(0);
    }

    @Override
    public void postCPLeftInfoResult(CPLeftInfoResult cpLeftInfoResult) {
        GameLog.log("postCPLeftInfoResult "+cpLeftInfoResult.toString());
        moneyStr = cpLeftInfoResult.getUnsettledMoney();
        if(Check.isEmpty(moneyStr)){
            moneyStr = "0.00";
        }
        cpHallUserMoney.setText(GameShipHelper.formatMoney(cpLeftInfoResult.getMoney()));
        if(!Check.isNull(moneyText)) {
            moneyText.setText(Html.fromHtml(getString(R.string.lotter_me_jszd)+"<br>"+onMarkRed("("+GameShipHelper.formatMoney(moneyStr)+")")));
        }
    }

    class HallPageGameAdapter extends AutoSizeRVAdapter<CPHallIcon> {
        private Context context;

        public HallPageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(final ViewHolder holder, final CPHallIcon data, final int position) {
            executorService.scheduleAtFixedRate(new Runnable() {
                @Override
                public void run() {
                    switch (position) {
                        case 0:
                            if (cpHallIcon0-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求0，，，，，，，，，，，，，，");
                                        onRequestData();
                                        if(scpHallIcon0==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon0));
                                    }
                                });
                            }
                            break;
                        case 1:
                            if (cpHallIcon1-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求1，，，，，，，，，，，，，，");
                                        onRequestData();
                                        if(scpHallIcon1==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon1) );
                                    }
                                });
                            }
                            break;
                        case 2:
                            if (cpHallIcon2-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求2，，，，，，，，，，，，，，");
                                        onRequestData();
                                        if(scpHallIcon2==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon2));
                                    }
                                });
                            }
                            break;
                        case 3:
                            if (cpHallIcon3-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon3==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求3，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon3 ));
                                    }
                                });
                            }
                            break;
                        case 4:
                            if (cpHallIcon4-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon4==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求4，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon4));
                                    }
                                });
                            }
                            break;
                        case 5:
                            if (cpHallIcon5-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon5==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求5，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon5));
                                    }
                                });
                            }
                            break;
                        case 6:
                            if (cpHallIcon6-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon6==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求6，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon6));
                                    }
                                });
                            }
                            break;
                        case 7:
                            if (cpHallIcon7-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon7==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求7，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon7 ));
                                    }
                                });
                            }
                            break;
                        case 8:
                            if (cpHallIcon8-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon8==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求8，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon8));
                                    }
                                });
                            }
                            break;
                        case 9:
                            if (cpHallIcon9-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon9==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求9，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon9));
                                    }
                                });
                            }
                            break;
                        case 10:
                            if (cpHallIcon10-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon10==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求10，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon10));
                                    }
                                });
                            }
                            break;
                        case 11:
                            if (cpHallIcon11-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon11==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求11，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon11));
                                    }
                                });
                            }
                            break;
                        case 12:
                            if (cpHallIcon12-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon12==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求12，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon12));
                                    }
                                });
                            }
                            break;
                        case 13:
                            if (cpHallIcon13-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon13==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求13，，，，，，，，，，，，，，");
                                    }
                                });

                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon13));
                                    }
                                });
                            }
                            break;
                        case 14:
                            if (cpHallIcon14-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon14==1){
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_not_open));
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, getString(R.string.lotter_ing));
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求14，，，，，，，，，，，，，，");
                                    }
                                });

                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon14));
                                    }
                                });
                            }
                            break;
                    }
                }
            }, 0, 1000, TimeUnit.MILLISECONDS);
            holder.setText(R.id.cpHallItemName, data.getIconName());
            if (position == 2 || position == 3 || position == 6 || position == 7 || position == 10 || position == 11) {//(position & 1) != 0
                holder.setBackgroundRes(R.id.cpHallItemShow, R.color.cp_hall_cline);
            }else{
                holder.setBackgroundRes(R.id.cpHallItemShow, R.color.title_text);
            }
            holder.setBackgroundRes(R.id.cpHallItemIcon, data.getIconId());
            holder.setOnClickListener(R.id.cpHallItemShow, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    GameLog.log("传递的 gameId 【"+data.getGameId()+" 】gameName --->"+data.getIconName());
                    Intent intent  = new Intent(getContext(),CPOrderFragment.class);
                    intent.putExtra("gameId",data.getGameId()+"");
                    intent.putExtra("gameName",data.getIconName());
                    startActivity(intent);
                    //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList(data.getGameId()+"", data.getIconName(), "333"))));
                }
            });
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult) {
        GameLog.log("通过发送消息得的的数据" + personBalanceResult.getBalance_ag());
        agMoney = personBalanceResult.getBalance_ag();
        hgMoney = personBalanceResult.getBalance_hg();
    }

}

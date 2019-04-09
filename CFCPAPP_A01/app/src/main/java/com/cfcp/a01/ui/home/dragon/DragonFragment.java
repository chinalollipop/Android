package com.cfcp.a01.ui.home.dragon;

import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.SimpleItemAnimator;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.DoubleClickHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.TimeHelper;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.common.widget.TimeTextView;
import com.cfcp.a01.data.BetDragonResult;
import com.cfcp.a01.data.BetRecordsResult;
import com.cfcp.a01.data.CPBetResult;
import com.cfcp.a01.ui.home.cplist.bet.BetParam;
import com.cfcp.a01.ui.home.cplist.events.CloseLotteryEvent;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.kongzue.dialog.v2.WaitDialog;
import com.zhy.adapter.recyclerview.CommonAdapter;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;
import java.util.Objects;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class DragonFragment extends BaseFragment implements DragonContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.dragonBack)
    NTitleBar dragonBack;
    @BindView(R.id.dragonTab)
    TabLayout dragonTab;
    @BindView(R.id.dragonBetList)
    RecyclerView dragonBetList;
    @BindView(R.id.dragonBetLay)
    LinearLayout dragonBetLay;
    @BindView(R.id.dragonBetGold)
    EditText dragonBetGold;
    @BindView(R.id.dragonBetClear)
    TextView dragonBetClear;
    @BindView(R.id.dragonBetNumberAndMoney)
    TextView dragonBetNumberAndMoney;
    @BindView(R.id.dragonBetSubmit)
    TextView dragonBetSubmit;
    @BindView(R.id.dragonMyBetRecordList)
    RecyclerView dragonMyBetRecordList;
    @BindView(R.id.dragonBetListFrame)
    FrameLayout dragonBetListFrame;
    BetDragonListBaseAdapter betDragonListBaseAdapter;
    private String typeArgs2, typeArgs3;
    DragonContract.Presenter presenter;
    String name,fTime, game_code,  round,payId="0",odds, totalNums,totalMoney,number,betGold="",betType;
    //代表彩种ID
    private String lotteryId = "1";
    private int clickPostion =0;
    String startTime, endTime;
    private long cpHallIcon0, cpHallIcon1, cpHallIcon2, cpHallIcon3, cpHallIcon4, cpHallIcon5, cpHallIcon6, cpHallIcon7,
            cpHallIcon8, cpHallIcon9, cpHallIcon10, cpHallIcon11, cpHallIcon12, cpHallIcon13, cpHallIcon14, cpHallIcon15,
            cpHallIcon16, cpHallIcon17, cpHallIcon18, cpHallIcon19, cpHallIcon20, cpHallIcon21, cpHallIcon22, cpHallIcon23,
            cpHallIcon24, cpHallIcon25, cpHallIcon26, cpHallIcon27, cpHallIcon28, cpHallIcon29, cpHallIcon30, cpHallIcon31;
    int positionl;
    private ScheduledExecutorService executorService;
    private ScheduledExecutorService executorServiceTime;
    long serviceTime;
    List<BetRecordsResult.ListBean> projectsBeansData = new ArrayList<>();
    List<BetDragonResult.DataBean> dataBeansData = new ArrayList<>();
    List<BetDragonResult.DataBean> dataBeansDataTemp = new ArrayList<>();
    public static DragonFragment newInstance(String deposit_mode, String money) {
        DragonFragment betFragment = new DragonFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_dragon;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    private void initPwdStyle() {
        //presenter.getDepositSubmit(typeArgs2,"","","");
        dragonTab.addTab(dragonTab.newTab().setText("最新长龙"));
        dragonTab.addTab(dragonTab.newTab().setText("我的投注"));
        dragonTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                WaitDialog.show(getActivity(), "加载中...").setCanCancel(true);
                positionl = tab.getPosition();
                switch (positionl) {
                    case 0:
                        presenter.getDragonBetList("", "");
                        dragonBetListFrame.setVisibility(View.VISIBLE);
                        dragonMyBetRecordList.setVisibility(View.GONE);
                        break;
                    case 1:
                        presenter.getDragonBetRecordList("", "");
                        /*String  data = getFromAssets("DragonRecord.json");
                        BetRecordsResult betDragonResult = JSON.parseObject(data, BetRecordsResult.class);
                        projectsBeansData = betDragonResult.getList();
                        BetDragonRecordAdapter betDragonRecordAdapter = new BetDragonRecordAdapter(R.layout.item_bet_record, projectsBeansData);
                        dragonBetList.setAdapter(betDragonRecordAdapter);*/
                        dragonBetListFrame.setVisibility(View.GONE);
                        dragonMyBetRecordList.setVisibility(View.VISIBLE);
                        break;
                }
            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {
            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {
            }
        });
    }


    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }


    TextWatcher dragonBetGoldListener = new TextWatcher() {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {
        }

        @Override
        public void afterTextChanged(Editable s) {
            String ms = s.toString().replace(" ","");
            if (Check.isEmpty(ms)) {
                dragonBetNumberAndMoney.setText("共0注,0元");
                return;
            }
            dragonBetNumberAndMoney.setText("共1注,"+ms+"元");
        }

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
        }
    };


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        dragonBetList.setLayoutManager(linearLayoutManager);
        LinearLayoutManager linearRecordLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        dragonMyBetRecordList.setLayoutManager(linearRecordLayoutManager);
        initPwdStyle();
        dragonBetGold.addTextChangedListener(dragonBetGoldListener);
        dragonBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        WaitDialog.show(getActivity(), "加载中...").setCanCancel(true);
        presenter.getDragonBetList("", "");
        executorServiceTime = Executors.newScheduledThreadPool(1);
        executorServiceTime.scheduleAtFixedRate(new Runnable() {
            @Override
            public void run() {
                presenter.getDragonBetList("", "");
            }
        }, 0, 8000, TimeUnit.MILLISECONDS);
        /*String  data = getFromAssets("Dragon.json");
        BetDragonResult betDragonResult = JSON.parseObject(data, BetDragonResult.class);
        dataBeansData = betDragonResult.getData();
        long  serviceTime = 1554543100;
        int size = dataBeansData.size();
        for(int k=0;k<size;++k){
            switch (k){
                case 0:
                    cpHallIcon0 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 1:
                    cpHallIcon1 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 2:
                    cpHallIcon2 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 3:
                    cpHallIcon3 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 4:
                    cpHallIcon4 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 5:
                    cpHallIcon5 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 6:
                    cpHallIcon6 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 7:
                    cpHallIcon7 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 8:
                    cpHallIcon8 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 9:
                    cpHallIcon9 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 10:
                    cpHallIcon10 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 11:
                    cpHallIcon11 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 12:
                    cpHallIcon12 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 13:
                    cpHallIcon13 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 14:
                    cpHallIcon14 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 15:
                    cpHallIcon15 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 16:
                    cpHallIcon16 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 17:
                    cpHallIcon17 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 18:
                    cpHallIcon18 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 19:
                    cpHallIcon19 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 20:
                    cpHallIcon20 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 21:
                    cpHallIcon21 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 22:
                    cpHallIcon22 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 23:
                    cpHallIcon23 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 24:
                    cpHallIcon24 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 25:
                    cpHallIcon25 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 26:
                    cpHallIcon26 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 27:
                    cpHallIcon27 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 28:
                    cpHallIcon28 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 29:
                    cpHallIcon29 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 30:
                    cpHallIcon30 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 31:
                    cpHallIcon31 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
            }
        }
        executorService = Executors.newScheduledThreadPool(1);
        BetDragonListBaseAdapter betDragonRecordAdapter = new BetDragonListBaseAdapter(getContext(),R.layout.item_bet_dragon, dataBeansData);
        //BetDragonListAdapter betDragonRecordAdapter = new BetDragonListAdapter(R.layout.item_bet_dragon, dataBeansData);
        dragonBetList.setItemViewCacheSize(100);
        dragonBetList.setAdapter(betDragonRecordAdapter);*/
        /*String  data = getFromAssets("DragonRecord.json");
        BetRecordsResult betDragonResult = JSON.parseObject(data, BetRecordsResult.class);
        projectsBeansData = betDragonResult.getList();
        BetDragonRecordAdapter betDragonRecordAdapter = new BetDragonRecordAdapter(R.layout.item_bet_record, projectsBeansData);
        dragonBetList.setAdapter(betDragonRecordAdapter);*/
    }


    private void onSetData(){
        betGold = dragonBetGold.getText().toString().trim();
        if(Check.isEmpty(betGold)&&!Check.isEmpty(payId)){
            dragonBetNumberAndMoney.setText("共1注,0元");
            return;
        }
        if(!Check.isEmpty(payId)){
            dragonBetNumberAndMoney.setText("共1注,"+betGold+"元");
        }else if(Check.isEmpty(betGold)){
            dragonBetNumberAndMoney.setText("共0注,0元");
        }else{
            dragonBetNumberAndMoney.setText("共0注,"+betGold+"元");
        }
    }

    //请求数据接口
    private void onRequsetData() {
        betGold = dragonBetGold.getText().toString().trim();
        if(Check.isEmpty(betGold)){
            super.showMessage("请输入购买金额");
            return;
        }
        if(Check.isEmpty(number)){
            super.showMessage("请先选择下注");
            return;
        }
        BetDragonOrderDialog.newInstance(name,round,betGold,number).show(getFragmentManager());
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
    public void postCpBetResult(CPBetResult betResult) {
        EventBus.getDefault().post(new DragonBetCloseEvent("投注成功"));
        //dragonBetNumberAndMoney.setText("共0注,0元");
        number = "";
        payId  ="99";
    }

    @Subscribe
    public void onEventMain(DragonBetEvent dragonBetEvent){
        ArrayList<BetParam.BetdataBean.BetBeanBean> beanArrayList = new ArrayList<>();
        BetParam.BetdataBean betParam = new BetParam.BetdataBean();
        betParam.setBetSrc(CFConstant.PRODUCT_PLATFORM);
        betParam.setFtime(fTime);
        betParam.setGameId(game_code);
        betParam.setTotalMoney(betGold);
        betParam.setTotalNums("1");
        betParam.setTurnNum(round);
        BetParam.BetdataBean.BetBeanBean betBeanBean= new BetParam.BetdataBean.BetBeanBean();
        betBeanBean.setMoney(betGold);
        betBeanBean.setOdds(odds);
        betBeanBean.setPlayId(payId);
        betBeanBean.setRebate("0");
        betBeanBean.setBetInfo(number);
        beanArrayList.add(betBeanBean);
        betParam.setBetBean(beanArrayList);
        presenter.postCpBets(game_code,  round, totalNums,totalMoney,"",null, JSON.toJSONString(betParam));
    }

    @Subscribe
    public void onEventMain(CloseLotteryEvent closeLotteryEvent){
        showMessage("已封盘，请稍后下注！");
    }

    @Override
    public void getDragonBetListResult(BetDragonResult betDragonResult) {
        WaitDialog.dismiss();
        GameLog.log("获取长龙投注列表 成功");
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
            dataBeansDataTemp.clear();
        }
        //number = "";

        dataBeansData = betDragonResult.getData();

        int size = dataBeansData.size();
        if (size == 0) {
            showMessage("暂无数据！");
            return;
        }

        executorService = Executors.newScheduledThreadPool(1);
        serviceTime = betDragonResult.getServerTime();
        for (int k = 0; k < size; ++k) {
            if(dataBeansData.get(k).getEndtime() - serviceTime>0){
                if(payId.equals(dataBeansData.get(k).getADXDSPlayed().get(0).getId()+"")){
                    dataBeansData.get(k).setCheckedId(Integer.parseInt(payId));
                }else if(payId.equals(dataBeansData.get(k).getADXDSPlayed().get(1).getId()+"")){
                    dataBeansData.get(k).setCheckedId(Integer.parseInt(payId));
                }
                dataBeansDataTemp.add(dataBeansData.get(k));
            }
        }
        dataBeansData = dataBeansDataTemp;
        size = dataBeansData.size();
        if (size == 0) {
            showMessage("暂无可投注的数据！");
            return;
        }
        for (int k = 0; k < dataBeansDataTemp.size(); ++k) {
            switch (k) {
                case 0:
                    cpHallIcon0 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 1:
                    cpHallIcon1 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 2:
                    cpHallIcon2 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 3:
                    cpHallIcon3 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 4:
                    cpHallIcon4 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 5:
                    cpHallIcon5 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 6:
                    cpHallIcon6 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 7:
                    cpHallIcon7 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 8:
                    cpHallIcon8 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 9:
                    cpHallIcon9 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 10:
                    cpHallIcon10 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 11:
                    cpHallIcon11 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 12:
                    cpHallIcon12 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 13:
                    cpHallIcon13 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 14:
                    cpHallIcon14 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 15:
                    cpHallIcon15 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 16:
                    cpHallIcon16 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 17:
                    cpHallIcon17 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 18:
                    cpHallIcon18 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 19:
                    cpHallIcon19 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 20:
                    cpHallIcon20 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 21:
                    cpHallIcon21 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 22:
                    cpHallIcon22 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 23:
                    cpHallIcon23 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 24:
                    cpHallIcon24 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 25:
                    cpHallIcon25 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 26:
                    cpHallIcon26 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 27:
                    cpHallIcon27 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 28:
                    cpHallIcon28 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 29:
                    cpHallIcon29 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 30:
                    cpHallIcon30 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
                case 31:
                    cpHallIcon31 = dataBeansData.get(k).getEndtime() - serviceTime;
                    break;
            }
        }

//         BetDragonListAdapter betDragonListBaseAdapter = new BetDragonListAdapter(R.layout.item_bet_dragon, dataBeansData);
        betDragonListBaseAdapter = new BetDragonListBaseAdapter(getContext(), R.layout.item_bet_dragon, dataBeansData);
        dragonBetList.scrollToPosition(clickPostion);
        dragonBetList.setItemViewCacheSize(100);
       //((SimpleItemAnimator) Objects.requireNonNull(dragonBetList.getItemAnimator())).setSupportsChangeAnimations(false);
        dragonBetList.setAdapter(betDragonListBaseAdapter);
        dragonBetList.addOnScrollListener(new RecyclerView.OnScrollListener() {
            @Override
            public void onScrolled(@NonNull RecyclerView recyclerView, int dx, int dy) {
                super.onScrolled(recyclerView, dx, dy);
                LinearLayoutManager layoutManager = (LinearLayoutManager) dragonBetList.getLayoutManager();
                clickPostion = layoutManager.findFirstCompletelyVisibleItemPosition();
            }
        });
        /*betDragonListBaseAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                switch (view.getId()){
                    case R.id.itemDragonBetLayName1:
                        if(dataBeansData.get(position).getCheckedId()==0){
                            for(int k=0;k<size;++k){
                                dataBeansData.get(k).setCheckedId(0);
                            }
                            dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(0).getId());
                        }else if(dataBeansData.get(position).getCheckedId()!=dataBeansData.get(position).getADXDSPlayed().get(0).getId()){
                            for(int k=0;k<size;++k){
                                dataBeansData.get(k).setCheckedId(0);
                            }
                            dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(0).getId());
                        }else{
                            dataBeansData.get(position).setCheckedId(0);
                        }
                        name = dataBeansData.get(position).getLotteryName();
                        game_code = dataBeansData.get(position).getGameId();
                        fTime = dataBeansData.get(position).getEndtime()+"";
                        round = dataBeansData.get(position).getCurrIssue();
                        payId = dataBeansData.get(position).getADXDSPlayed().get(0).getId()+"";
                        odds = dataBeansData.get(position).getADXDSPlayed().get(0).getOdds()+"";
                        number = dataBeansData.get(position).getADXDSPlayed().get(0).getName();
                        break;
                    case R.id.itemDragonBetLayName2:
                        if(dataBeansData.get(position).getCheckedId()==0){
                            for(int k=0;k<size;++k){
                                dataBeansData.get(k).setCheckedId(0);
                            }
                            dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(1).getId());
                        }else if(dataBeansData.get(position).getCheckedId()!=dataBeansData.get(position).getADXDSPlayed().get(1).getId()){
                            for(int k=0;k<size;++k){
                                dataBeansData.get(k).setCheckedId(0);
                            }
                            dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(1).getId());
                        }else{
                            dataBeansData.get(position).setCheckedId(0);
                        }
                        name = dataBeansData.get(position).getLotteryName();
                        game_code = dataBeansData.get(position).getGameId();
                        fTime = dataBeansData.get(position).getEndtime()+"";
                        round = dataBeansData.get(position).getCurrIssue();
                        payId = dataBeansData.get(position).getADXDSPlayed().get(1).getId()+"";
                        odds = dataBeansData.get(position).getADXDSPlayed().get(1).getOdds()+"";
                        number = dataBeansData.get(position).getADXDSPlayed().get(1).getName();
                        break;
                }
                dragonBetList.setItemViewCacheSize(100);
                adapter.notifyDataSetChanged();
            }
        });*/
        /*int size = projectsBeansData.size();
        if(size==0){
            //recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansAll);
            //betDragonRecordAdapter.setEmptyView(showNoData());
            //recordBetRView.setAdapter(recordBetAdapter);
            return;
        }*/

    }

    //公告部分 无数据的时候展示
    private View showNoData() {
        View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
        TextView textView = view.findViewById(R.id.itemNoDate);
        textView.setText("当前查询条件下暂无查询数据");
        textView.setTextColor(Color.parseColor("#C52133"));
        return view;
    }

    @Override
    public void getDragonBetRecordListResult(BetRecordsResult teamReportResult) {
        WaitDialog.dismiss();
        GameLog.log("获取长龙投注记录列表 成功");
        projectsBeansData = teamReportResult.getList();
        BetDragonRecordAdapter betDragonRecordAdapter = new BetDragonRecordAdapter(R.layout.item_bet_record, projectsBeansData);
        dragonMyBetRecordList.setAdapter(betDragonRecordAdapter);
        int size = projectsBeansData.size();
        if (size == 0) {
            //recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansAll);
            betDragonRecordAdapter.setEmptyView(showNoData());
            //recordBetRView.setAdapter(recordBetAdapter);
            return;
        }
    }


    @OnClick({R.id.dragonBetClear, R.id.dragonBetSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.dragonBetClear:
                dragonBetGold.setText("");
                dragonBetNumberAndMoney.setText("共0注,0元");
                break;
            case R.id.dragonBetSubmit:
                onRequsetData();
                break;
        }
    }


    class BetDragonListBaseAdapter extends CommonAdapter<BetDragonResult.DataBean> {
        public BetDragonListBaseAdapter(Context context, int layoutId, List<BetDragonResult.DataBean> datas) {
            super(context, layoutId, datas);
        }

        @Override
        protected void convert(final ViewHolder helper, final BetDragonResult.DataBean dataBean, final int position) {
            onShowImage(dataBean.getGameId(), helper);
            switch (dataBean.getPlayName()) {
                case "大":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_login_text);
                    break;
                case "小":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_btn_green);
                    break;
                case "单":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_btn_purple);
                    break;
                case "双":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_btn_shuang);
                    break;
            }
            helper.setText(R.id.itemDragonlotteryName, dataBean.getLotteryName());
            helper.setText(R.id.itemDragoncurrIssue, dataBean.getCurrIssue() + "期").
                    setText(R.id.itemDragonplayCateName, dataBean.getPlayCateName()).
                    setText(R.id.itemDragonplayName, dataBean.getPlayName()).
                    setText(R.id.itemDragonlotteryCount, dataBean.getCount() + "期").
                    setText(R.id.itemDragonBetName1, dataBean.getADXDSPlayed().get(0).getName()).
                    setText(R.id.itemDragonBetOdds1, "赔" + dataBean.getADXDSPlayed().get(0).getOdds()).
                    setText(R.id.itemDragonBetName2, dataBean.getADXDSPlayed().get(1).getName()).
                    setText(R.id.itemDragonBetOdds2, "赔" + dataBean.getADXDSPlayed().get(1).getOdds());
//                    setText(R.id.itemDragonBetName1,item.getADXDSPlayed().get(0).getName()).
//                    setText(R.id.itemDragonBetOdds1,item.getADXDSPlayed().get(0).getRebate()).
//                    setText(R.id.itemDragonBetName2,item.getADXDSPlayed().get(1).getName()).
//                    setText(R.id.itemDragonBetOdds2, item.getADXDSPlayed().get(1).getRebate()).
            //helper.setText(R.id.itemDragonlotteryTime,item.getLotteryTime());
            if (dataBean.getCheckedId() == dataBean.getADXDSPlayed().get(0).getId()) {
                helper.setBackgroundRes(R.id.itemDragonBetLayName1, R.drawable.bg_login_text);
                helper.setTextColor(R.id.itemDragonBetName1, Color.parseColor("#ffffff"));
                helper.setTextColor(R.id.itemDragonBetOdds1, Color.parseColor("#ffffff"));
            } else {
                helper.setTextColor(R.id.itemDragonBetName1, Color.parseColor("#dc3b40"));
                helper.setTextColor(R.id.itemDragonBetOdds1, Color.parseColor("#989898"));
                helper.setBackgroundRes(R.id.itemDragonBetLayName1, R.drawable.bg_deposit_input);
            }
            if (dataBean.getCheckedId() == dataBean.getADXDSPlayed().get(1).getId()) {
                helper.setBackgroundRes(R.id.itemDragonBetLayName2, R.drawable.bg_login_text);
                helper.setTextColor(R.id.itemDragonBetName2, Color.parseColor("#ffffff"));
                helper.setTextColor(R.id.itemDragonBetOdds2, Color.parseColor("#ffffff"));
            } else {
                helper.setTextColor(R.id.itemDragonBetName2, Color.parseColor("#dc3b40"));
                helper.setTextColor(R.id.itemDragonBetOdds2, Color.parseColor("#989898"));
                helper.setBackgroundRes(R.id.itemDragonBetLayName2, R.drawable.bg_deposit_input);
            }

            helper.setOnClickListener(R.id.itemDragonBetLayName1, new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //clickPostion = position;
                    if(DoubleClickHelper.getNewInstance().isFastClick()){
                        return;
                    }
                    TimeTextView timeTextView = helper.getView(R.id.itemDragonlotteryTime);
                    //LinearLayout itemDragonBetLayName1 = helper.getView(R.id.itemDragonBetLayName1);
                    //DoubleClickHelper.getNewInstance().disabledView1(itemDragonBetLayName1);
                    String textMsg = timeTextView.getText().toString();
                    if("封盘中".equals(textMsg)){
                        return;
                    }
                    if (dataBeansData.get(position).getCheckedId() == 0) {
                        for (int k = 0; k < dataBeansData.size(); ++k) {
                            dataBeansData.get(k).setCheckedId(0);
                        }
                        dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(0).getId());
                        name = dataBeansData.get(position).getLotteryName();
                        game_code = dataBeansData.get(position).getGameId();
                        fTime = dataBeansData.get(position).getEndtime()+"";
                        round = dataBeansData.get(position).getCurrIssue();
                        payId = dataBeansData.get(position).getADXDSPlayed().get(0).getId()+"";
                        odds = dataBeansData.get(position).getADXDSPlayed().get(0).getOdds()+"";
                        number = dataBeansData.get(position).getADXDSPlayed().get(0).getName();

                    } else if (dataBeansData.get(position).getCheckedId() != dataBeansData.get(position).getADXDSPlayed().get(0).getId()) {
                        for (int k = 0; k < dataBeansData.size(); ++k) {
                            dataBeansData.get(k).setCheckedId(0);
                        }
                        dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(0).getId());
                        name = dataBeansData.get(position).getLotteryName();
                        game_code = dataBeansData.get(position).getGameId();
                        fTime = dataBeansData.get(position).getEndtime()+"";
                        round = dataBeansData.get(position).getCurrIssue();
                        payId = dataBeansData.get(position).getADXDSPlayed().get(0).getId()+"";
                        odds = dataBeansData.get(position).getADXDSPlayed().get(0).getOdds()+"";
                        number = dataBeansData.get(position).getADXDSPlayed().get(0).getName();
                    } else {
                        dataBeansData.get(position).setCheckedId(0);
                        payId = "";
                        number = "";
                    }
                    if (null != executorService) {
                        executorService.shutdownNow();
                        executorService.shutdown();
                        executorService = null;
                    }
                    onSetData();
                    executorService = Executors.newScheduledThreadPool(1);

                    notifyDataSetChanged();
                    /*betDragonListBaseAdapter = new BetDragonListBaseAdapter(getContext(), R.layout.item_bet_dragon, dataBeansData);
                    dragonBetList.setItemViewCacheSize(100);
                    dragonBetList.setAdapter(betDragonListBaseAdapter);*/
                }
            });
            helper.setOnClickListener(R.id.itemDragonBetLayName2, new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    //clickPostion = position;
                    if(DoubleClickHelper.getNewInstance().isFastClick2()){
                        return;
                    }
                    TimeTextView timeTextView = helper.getView(R.id.itemDragonlotteryTime);
                    /*LinearLayout itemDragonBetLayName2 = helper.getView(R.id.itemDragonBetLayName2);
                    DoubleClickHelper.getNewInstance().disabledView1(itemDragonBetLayName2);*/

                    String textMsg = timeTextView.getText().toString();
                    if("封盘中".equals(textMsg)){
                        return;
                    }
                    if (dataBeansData.get(position).getCheckedId() == 0) {
                        for (int k = 0; k < dataBeansData.size(); ++k) {
                            dataBeansData.get(k).setCheckedId(0);
                        }
                        dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(1).getId());
                        name = dataBeansData.get(position).getLotteryName();
                        game_code = dataBeansData.get(position).getGameId();
                        fTime = dataBeansData.get(position).getEndtime()+"";
                        round = dataBeansData.get(position).getCurrIssue();
                        payId = dataBeansData.get(position).getADXDSPlayed().get(1).getId()+"";
                        odds = dataBeansData.get(position).getADXDSPlayed().get(1).getOdds()+"";
                        number = dataBeansData.get(position).getADXDSPlayed().get(1).getName();
                    } else if (dataBeansData.get(position).getCheckedId() != dataBeansData.get(position).getADXDSPlayed().get(1).getId()) {
                        for (int k = 0; k < dataBeansData.size(); ++k) {
                            dataBeansData.get(k).setCheckedId(0);
                        }
                        dataBeansData.get(position).setCheckedId(dataBeansData.get(position).getADXDSPlayed().get(1).getId());
                        name = dataBeansData.get(position).getLotteryName();
                        game_code = dataBeansData.get(position).getGameId();
                        fTime = dataBeansData.get(position).getEndtime()+"";
                        round = dataBeansData.get(position).getCurrIssue();
                        payId = dataBeansData.get(position).getADXDSPlayed().get(1).getId()+"";
                        odds = dataBeansData.get(position).getADXDSPlayed().get(1).getOdds()+"";
                        number = dataBeansData.get(position).getADXDSPlayed().get(1).getName();
                    } else {
                        dataBeansData.get(position).setCheckedId(0);
                        payId = "";
                        number = "";
                    }

                    if (null != executorService) {
                        executorService.shutdownNow();
                        executorService.shutdown();
                        executorService = null;
                    }
                    onSetData();
                    executorService = Executors.newScheduledThreadPool(1);
                    notifyDataSetChanged();
                    /*betDragonListBaseAdapter = new BetDragonListBaseAdapter(getContext(), R.layout.item_bet_dragon, dataBeansData);
                    dragonBetList.setItemViewCacheSize(100);
                    dragonBetList.setAdapter(betDragonListBaseAdapter);*/
                }
            });

            executorService.scheduleAtFixedRate(new Runnable() {
                @Override
                public void run() {
                    switch (position) {
                        case 0:
                            if (cpHallIcon0-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon0), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 1:
                            if (cpHallIcon1-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon1), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 2:
                            if (cpHallIcon2-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon2), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 3:
                            if (cpHallIcon3-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon3), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 4:
                            if (cpHallIcon4-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon4), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 5:
                            if (cpHallIcon5-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon5), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 6:
                            if (cpHallIcon6-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon6), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 7:
                            if (cpHallIcon7-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon7), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 8:
                            if (cpHallIcon8-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon8), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 9:
                            if (cpHallIcon9-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon9), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 10:
                            if (cpHallIcon10-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon10), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 11:
                            if (cpHallIcon11-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon11), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 12:
                            if (cpHallIcon12-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon12), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 13:
                            if (cpHallIcon13-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon13), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 14:
                            if (cpHallIcon14-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon14), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 15:
                            if (cpHallIcon15-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon15), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 16:
                            if (cpHallIcon16-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon16), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 17:
                            if (cpHallIcon17-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon17), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 18:
                            if (cpHallIcon18-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon18), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 19:
                            if (cpHallIcon19-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon19), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 20:
                            if (cpHallIcon20-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon20), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 21:
                            if (cpHallIcon21-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon21), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 22:
                            if (cpHallIcon22-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon22), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 23:
                            if (cpHallIcon23-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon23), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 24:
                            if (cpHallIcon24-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon24), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 25:
                            if (cpHallIcon25-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon25), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 26:
                            if (cpHallIcon26-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon26), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 27:
                            if (cpHallIcon27-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon27), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 28:
                            if (cpHallIcon28-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon28), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 29:
                            if (cpHallIcon29-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon29), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 30:
                            if (cpHallIcon30-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon30), helper,dataBean);
                                    }
                                });
                            }
                            break;
                        case 31:
                            if (cpHallIcon31-- <= 0) {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText("封盘中", helper,dataBean);
                                    }
                                });
                            } else {
                                dragonBetList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onShowText(TimeHelper.getTimeString(cpHallIcon31), helper,dataBean);
                                    }
                                });
                            }
                            break;
                    }
                }
            }, 0, 1000, TimeUnit.MILLISECONDS);
/*
            TimeTextView timeTextView = helper.getView(R.id.itemDragonlotteryTime);
            timeTextView.setEndInfo("封盘中");
            timeTextView.setLeftTime(dataBean.getLotteryTime() - dataBean.getEndtime(), true, new TimeTextView.onCountDownListener() {
                @Override
                public void onTimeOverListener() {
                    //notifyDataSetChanged();
                }
            });*/
        }

        private void onShowText(String text, ViewHolder holder,BetDragonResult.DataBean dataBean) {
            holder.setText(R.id.itemDragonlotteryTime, text);
            if("封盘中".equals(text)){
                    dataBean.setCheckedId(0);
                    holder.setTextColor(R.id.itemDragonBetName1, Color.parseColor("#dc3b40"));
                    holder.setTextColor(R.id.itemDragonBetOdds1, Color.parseColor("#989898"));
                    holder.setBackgroundRes(R.id.itemDragonBetLayName1, R.drawable.bg_deposit_input);

                    holder.setTextColor(R.id.itemDragonBetName2, Color.parseColor("#dc3b40"));
                    holder.setTextColor(R.id.itemDragonBetOdds2, Color.parseColor("#989898"));
                    holder.setBackgroundRes(R.id.itemDragonBetLayName2, R.drawable.bg_deposit_input);
            }
        }

        private void onShowImage(String identifier, ViewHolder holder) {
            int ids = R.mipmap.gf_ssc;
            String name = "";
            switch (identifier) {
                case "50":
                    name = "北京PK拾";
                    ids = R.mipmap.gf_pk10;
                    break;
                case "1":
                    name = "欢乐生肖";
                    ids = R.mipmap.gf_ssc;
                    break;
                case "55":
                    name = "幸运飞艇";
                    ids = R.mipmap.xy_xyft;
                    break;
                case "70":
                    name = "香港六合彩";
                    ids = R.mipmap.xy_xglhc;
                    break;
                case "72":
                    name = "极速六合彩";
                    ids = R.mipmap.xy_jslhc;
                    break;
                case "66":
                    name = "PC蛋蛋";
                    ids = R.mipmap.xy_pcdd;
                    break;
                case "10":
                    name = "江苏骰宝(快3)";
                    ids = R.mipmap.gf_jsks;
                    break;
                case "73":
                    name = "极速快3五分彩";
                    ids = R.mipmap.gf_kswfc;
                    break;
                case "74":
                    name = "极速快3三分彩";
                    ids = R.mipmap.gf_kssfc;
                    break;
                case "75":
                    name = "极速快3分分彩";
                    ids = R.mipmap.gf_ksffc;
                    break;
                case "51":
                    name = "极速赛车";
                    ids = R.mipmap.xy_jssc;
                    break;
                case "2":
                    name = "官方分分彩";
                    ids = R.mipmap.gf_ffc;
                    break;
                case "60":
                    name = "广东快乐十分";
                    ids = R.mipmap.xy_klsf;
                    break;
                case "61":
                    name = "重庆幸运农场";
                    ids = R.mipmap.xy_xync;
                    break;
                case "65":
                    name = "北京快乐8";
                    ids = R.mipmap.xy_ks8;
                    break;
                case "21":
                    name = "广东11选5";
                    ids = R.mipmap.gf_11x5;
                    break;
                case "4":
                    name = "阿里二分彩";
                    ids = R.mipmap.xy_ali2fen;
                    break;
                case "5":
                    name = "腾讯三分彩";
                    ids = R.mipmap.xy_tx3;
                    break;
                case "6":
                    name = "百度五分彩";
                    ids = R.mipmap.xy_baidu5fc;
                    break;
            }
            holder.setBackgroundRes(R.id.itemBetDragonImg, ids);
        }
    }


    class BetDragonListAdapter extends BaseQuickAdapter<BetDragonResult.DataBean, BaseViewHolder> {

        public BetDragonListAdapter(int layoutResId, @Nullable List<BetDragonResult.DataBean> data) {
            super(layoutResId, data);
        }

        private void onShowImage(String identifier, BaseViewHolder holder) {
            int ids = R.mipmap.gf_ssc;
            String name = "";
            switch (identifier) {
                case "50":
                    name = "北京PK拾";
                    ids = R.mipmap.gf_pk10;
                    break;
                case "1":
                    name = "欢乐生肖";
                    ids = R.mipmap.gf_ssc;
                    break;
                case "55":
                    name = "幸运飞艇";
                    ids = R.mipmap.xy_xyft;
                    break;
                case "70":
                    name = "香港六合彩";
                    ids = R.mipmap.xy_xglhc;
                    break;
                case "72":
                    name = "极速六合彩";
                    ids = R.mipmap.xy_jslhc;
                    break;
                case "66":
                    name = "PC蛋蛋";
                    ids = R.mipmap.xy_pcdd;
                    break;
                case "10":
                    name = "江苏骰宝(快3)";
                    ids = R.mipmap.gf_jsks;
                    break;
                case "73":
                    name = "极速快3五分彩";
                    ids = R.mipmap.gf_kswfc;
                    break;
                case "74":
                    name = "极速快3三分彩";
                    ids = R.mipmap.gf_kssfc;
                    break;
                case "75":
                    name = "极速快3分分彩";
                    ids = R.mipmap.gf_ksffc;
                    break;
                case "51":
                    name = "极速赛车";
                    ids = R.mipmap.xy_jssc;
                    break;
                case "2":
                    name = "官方分分彩";
                    ids = R.mipmap.gf_ffc;
                    break;
                case "60":
                    name = "广东快乐十分";
                    ids = R.mipmap.xy_klsf;
                    break;
                case "61":
                    name = "重庆幸运农场";
                    ids = R.mipmap.xy_xync;
                    break;
                case "65":
                    name = "北京快乐8";
                    ids = R.mipmap.xy_ks8;
                    break;
                case "21":
                    name = "广东11选5";
                    ids = R.mipmap.gf_11x5;
                    break;
                case "4":
                    name = "阿里二分彩";
                    ids = R.mipmap.xy_ali2fen;
                    break;
                case "5":
                    name = "腾讯三分彩";
                    ids = R.mipmap.xy_tx3;
                    break;
                case "6":
                    name = "百度五分彩";
                    ids = R.mipmap.xy_baidu5fc;
                    break;
            }
            holder.setBackgroundRes(R.id.itemBetDragonImg, ids);
        }

        @Override
        protected void convert(final BaseViewHolder helper, BetDragonResult.DataBean item) {

            GameLog.log("当前期数 " + item.getCurrIssue() + "期" + " >>>>> 时间：" + (item.getLotteryTime() - item.getEndtime()));
            onShowImage(item.getGameId(), helper);
            switch (item.getPlayName()) {
                case "大":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_login_text);
                    break;
                case "小":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_btn_green);
                    break;
                case "单":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_btn_purple);
                    break;
                case "双":
                    helper.setBackgroundRes(R.id.itemDragonplayName, R.drawable.bg_btn_shuang);
                    break;
            }
            if (item.getCheckedId() == item.getADXDSPlayed().get(0).getId()) {
                helper.setBackgroundRes(R.id.itemDragonBetLayName1, R.drawable.bg_login_text);
                helper.setTextColor(R.id.itemDragonBetName1, Color.parseColor("#ffffff"));
                helper.setTextColor(R.id.itemDragonBetOdds1, Color.parseColor("#ffffff"));
            } else {
                helper.setTextColor(R.id.itemDragonBetName1, Color.parseColor("#dc3b40"));
                helper.setTextColor(R.id.itemDragonBetOdds1, Color.parseColor("#989898"));
                helper.setBackgroundRes(R.id.itemDragonBetLayName1, R.drawable.bg_deposit_input);
            }
            if (item.getCheckedId() == item.getADXDSPlayed().get(1).getId()) {
                helper.setBackgroundRes(R.id.itemDragonBetLayName2, R.drawable.bg_login_text);
                helper.setTextColor(R.id.itemDragonBetName2, Color.parseColor("#ffffff"));
                helper.setTextColor(R.id.itemDragonBetOdds2, Color.parseColor("#ffffff"));
            } else {
                helper.setTextColor(R.id.itemDragonBetName2, Color.parseColor("#dc3b40"));
                helper.setTextColor(R.id.itemDragonBetOdds2, Color.parseColor("#989898"));
                helper.setBackgroundRes(R.id.itemDragonBetLayName2, R.drawable.bg_deposit_input);
            }
            GameLog.log("数-----------------------------据");
            helper.setText(R.id.itemDragonlotteryName, item.getLotteryName());
            helper.setText(R.id.itemDragoncurrIssue, item.getCurrIssue() + "期").
                    setText(R.id.itemDragonplayCateName, item.getPlayCateName()).
                    setText(R.id.itemDragonplayName, item.getPlayName()).
                    setText(R.id.itemDragonlotteryCount, item.getCount() + "期").
                    setText(R.id.itemDragonBetName1, item.getADXDSPlayed().get(0).getName()).
                    setText(R.id.itemDragonBetOdds1, "赔" + item.getADXDSPlayed().get(0).getOdds()).
                    setText(R.id.itemDragonBetName2, item.getADXDSPlayed().get(1).getName()).
                    setText(R.id.itemDragonBetOdds2, "赔" + item.getADXDSPlayed().get(1).getOdds()).
                    addOnClickListener(R.id.itemDragonBetLayName1).
                    addOnClickListener(R.id.itemDragonBetLayName2);
            //helper.setText(R.id.itemDragonlotteryTime,item.getLotteryTime());
            TimeTextView timeTextView = helper.getView(R.id.itemDragonlotteryTime);
            timeTextView.setEndInfo("封盘中");
            timeTextView.setLeftTime(item.getEndtime() - serviceTime, true, new TimeTextView.onCountDownListener() {
                @Override
                public void onTimeOverListener() {
                    //notifyDataSetChanged();
                    helper.getView(R.id.itemDragonBetLayName1).setClickable(false);
                    helper.getView(R.id.itemDragonBetLayName2).setClickable(false);
                }
            });
        }
    }


    class BetDragonRecordAdapter extends BaseQuickAdapter<BetRecordsResult.ListBean, BaseViewHolder> {

        public BetDragonRecordAdapter(int layoutResId, @Nullable List<BetRecordsResult.ListBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, BetRecordsResult.ListBean item) {
            String name = "";
            switch (item.getType() + "") {
                case "50":
                    name = "北京PK拾";
                    break;
                case "1":
                    name = "重庆时时彩";
                    break;
                case "55":
                    name = "幸运飞艇";
                    break;
                case "70":
                    name = "香港六合彩";
                    break;
                case "72":
                    name = "极速六合彩";
                    break;
                case "66":
                    name = "PC蛋蛋";
                    break;
                case "10":
                    name = "江苏骰宝(快3)";
                    break;
                case "73":
                    name = "极速快3五分彩";
                    break;
                case "74":
                    name = "极速快3三分彩";
                    break;
                case "75":
                    name = "极速快3分分彩";
                    break;
                case "51":
                    name = "极速赛车";
                    break;
                case "2":
                    name = "官方分分彩";
                    break;
                case "60":
                    name = "广东快乐十分";
                    break;
                case "61":
                    name = "重庆幸运农场";
                    break;
                case "65":
                    name = "北京快乐8";
                    break;
                case "21":
                    name = "广东11选5";
                    break;
                case "4":
                    name = "阿里二分彩";
                    break;
                case "5":
                    name = "腾讯三分彩";
                    break;
                case "6":
                    name = "百度五分彩";
                    break;
            }
            helper.setText(R.id.itemBetRecordName, name);

            switch (item.getStatus()) {
                case 0:
                    helper.setText(R.id.itemBetRecordStatus, "待开奖");
                    helper.setTextColor(R.id.itemBetRecordStatus, Color.parseColor("#2c77ba"));
                    break;
                case 1:
                    helper.setTextColor(R.id.itemBetRecordStatus, Color.parseColor("#908e8e"));
                    helper.setText(R.id.itemBetRecordStatus, "已撤销");
                    break;
                case 2:
                    helper.setTextColor(R.id.itemBetRecordStatus, Color.parseColor("#908e8e"));
                    helper.setText(R.id.itemBetRecordStatus, "未中奖");
                    break;
                case 3:
                    helper.setTextColor(R.id.itemBetRecordStatus, Color.parseColor("#c52133"));
                    helper.setText(R.id.itemBetRecordStatus, "已中奖");
            }
            helper.setText(R.id.itemBetRecordWay, item.getGroupname()).
                    setText(R.id.itemBetRecordAmount, item.getMoney()).
                    setText(R.id.itemBetRecordBought, item.getActionTime()).
                    setText(R.id.itemBetRecordBetNumber, item.getActionData()).
                    setText(R.id.itemBetRecordIssue, item.getActionNo() + "期").
                    setText(R.id.itemBetRecordSerialNumber, item.getWjorderId()).
                    setText(R.id.itemBetRecordWinNumber, item.getLotteryNo()).
                    setText(R.id.itemBetRecordPrize, item.getBonus()).
                    setText(R.id.itemBetRecordUserName, item.getUsername()).
                    addOnClickListener(R.id.itemPersonDetail);
        }
    }


    @Override
    public void setPresenter(DragonContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
    }

    @Override
    public void showMessage(String message) {
        //super.showMessage(message);
        number = "";
        EventBus.getDefault().post(new DragonBetCloseEvent(message));
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
        //关闭计时器
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        if (null != executorServiceTime) {
            executorServiceTime.shutdownNow();
            executorServiceTime.shutdown();
            executorServiceTime = null;
        }
    }
}

package com.cfcp.a01.ui.home.cplist.bet.betrecords;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;


import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.CPInjections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseActivity2;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BetRecordsResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CPBetRecordsFragment extends BaseActivity2 implements CpBetRecordsContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.recordBetRView)
    RecyclerView recordBetRView;
    @BindView(R.id.cpBetRecordsbackHome)
    ImageView cpBetRecordsbackHome;
    @BindView(R.id.recordBetStartTime)
    TextView recordBetStartTime;
    @BindView(R.id.cpLotteryName)
    TextView cpLotteryName;
    @BindView(R.id.recordBetEndTime)
    TextView recordBetEndTime;
    @BindView(R.id.recordBetStyle)
    TabLayout recordBetStyle;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CpBetRecordsContract.Presenter presenter;
    List<BetRecordsResult.ListBean> projectsBeansAll = new ArrayList<>();
    RecordBetAdapter recordBetAdapter;
    List<BetRecordsResult.ListBean> projectsBeansW = new ArrayList<>();
    List<BetRecordsResult.ListBean> projectsBeansZ = new ArrayList<>();
    List<BetRecordsResult.ListBean> projectsBeansWZ = new ArrayList<>();
    List<BetRecordsResult.ListBean> projectsBeansData = new ArrayList<>();
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    OptionsPickerView typeOptionsPicker;

    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLottery  = new ArrayList<>();
    String startTime,endTime;
    String lottery_id="";
    int position = -1;
    int pageTotal = 1;
    int page = 1;
    boolean isNew;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_bet_records;
    }


    private void onDataRequest(){
        presenter.getCpBetRecords(lottery_id,"1",startTime,endTime);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        initBetStyle();
        recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
        recordBetRView.setAdapter(recordBetAdapter);
        startTime = DateHelper.getToday();
        endTime = DateHelper.getTom();
        recordBetStartTime.setText(startTime);
        recordBetEndTime.setText(endTime);
        //开始时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                startTime = getTime(date);
                page =1;
                isNew = true;
                projectsBeansData.clear();
                recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
                recordBetRView.setAdapter(recordBetAdapter);
                onDataRequest();
                recordBetStartTime.setText(startTime);
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
        //结束时间选择器
        pvEndTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                endTime = getTime(date);
                page =1;
                isNew = true;
                projectsBeansData.clear();
                recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
                recordBetRView.setAdapter(recordBetAdapter);
                onDataRequest();
                recordBetEndTime.setText(endTime);
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
        Calendar cd = Calendar.getInstance();
        cd.add(Calendar.DAY_OF_YEAR, +1);
        pvEndTime.setDate(cd);
        AvailableLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_XINYONG), AllGamesResult.DataBean.LotteriesBean.class);
        typeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                lottery_id = AvailableLottery.get(options1).getId()+"";
                isNew = true;
                projectsBeansData.clear();
                recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
                recordBetRView.setAdapter(recordBetAdapter);
                onDataRequest();
                cpLotteryName.setText(AvailableLottery.get(options1).getName());
            }
        }).build();
        typeOptionsPicker.setPicker(AvailableLottery);
        typeOptionsPicker.setSelectOptions (1);

        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        recordBetRView.setLayoutManager(linearLayoutManager);
        onDataRequest();
    }

    class RecordBetAdapter extends BaseQuickAdapter<BetRecordsResult.ListBean, BaseViewHolder> {

        public RecordBetAdapter(int layoutResId, @Nullable List<BetRecordsResult.ListBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, BetRecordsResult.ListBean item) {
            String name ="";
            switch (item.getType()+""){
                case "50":
                    name ="北京PK拾";
                    break;
                case "76":
                    name ="北京赛车(5分彩)";
                    break;
                case "1":
                    name ="欢乐生肖";
                    break;
                case "7":
                    name ="重庆时时彩";
                    break;
                case "55":
                    name ="幸运飞艇";
                    break;
                case "70":
                    name ="香港六合彩";
                    break;
                case "72":
                    name ="极速六合彩";
                    break;
                case "66":
                    name ="PC蛋蛋";
                    break;
                case "10":
                    name ="江苏骰宝(快3)";
                    break;
                case "73":
                    name ="五分快三";
                    break;
                case "74":
                    name ="三分快三";
                    break;
                case "75":
                    name ="一分快三";
                    break;
                case "51":
                    name ="极速赛车";
                    break;
                case "2":
                    name ="分分彩";
                    break;
                case "60":
                    name ="广东快乐十分";
                    break;
                case "61":
                    name ="重庆幸运农场";
                    break;
                case "65":
                    name ="北京快乐8";
                    break;
                case "21":
                    name ="广东11选5";
                    break;
                case "4":
                    name ="二分彩";
                    break;
                case "5":
                    name ="三分彩";
                    break;
                case "6":
                    name ="五分彩";
                    break;
            }
            helper.setText(R.id.itemBetRecordName, name);

            switch (item.getStatus()){
                case 0:
                    helper.setText(R.id.itemBetRecordStatus,"待开奖");
                    helper.setTextColor(R.id.itemBetRecordStatus, Color.parseColor("#2c77ba"));
                    break;
                case 1:
                    helper.setTextColor(R.id.itemBetRecordStatus,Color.parseColor("#908e8e"));
                    helper.setText(R.id.itemBetRecordStatus,"已撤销");
                    break;
                case 2:
                    helper.setTextColor(R.id.itemBetRecordStatus,Color.parseColor("#908e8e"));
                    helper.setText(R.id.itemBetRecordStatus,"未中奖");
                    break;
                case 3:
                    helper.setTextColor(R.id.itemBetRecordStatus,Color.parseColor("#c52133"));
                    helper.setText(R.id.itemBetRecordStatus,"已中奖");
            }
            helper.setText(R.id.itemBetRecordWay, item.getGroupname()).
                    setText(R.id.itemBetRecordAmount,item.getMoney()).
                    setText(R.id.itemBetRecordBought,item.getActionTime()).
                    setText(R.id.itemBetRecordBetNumber,item.getActionData()).
                    setText(R.id.itemBetRecordIssue,item.getActionNo()+"期" ).
                    setText(R.id.itemBetRecordSerialNumber,item.getWjorderId()).
                    setText(R.id.itemBetRecordWinNumber,item.getLotteryNo()).
                    setText(R.id.itemBetRecordPrize,item.getBonus()).
                    setText(R.id.itemBetRecordUserName, item.getUsername()).
                    addOnClickListener(R.id.itemPersonDetail);
        }
    }


    private void initBetStyle(){
        recordBetStyle.addTab(recordBetStyle.newTab().setText("全部"));
        recordBetStyle.addTab(recordBetStyle.newTab().setText("未开奖"));
        recordBetStyle.addTab(recordBetStyle.newTab().setText("中奖"));
        recordBetStyle.addTab(recordBetStyle.newTab().setText("未中奖"));
        recordBetStyle.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()){
                    case 0:
                        position =-1;
                        projectsBeansData = projectsBeansAll;
                        recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansAll);
                        break;
                    case 1:
                        position =0;
                        projectsBeansData = projectsBeansW;
                        recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansW);
                        break;
                    case 2:
                        position =2;
                        projectsBeansData = projectsBeansWZ;
                        recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansWZ);
                        break;
                    case 3:
                        position =3;
                        projectsBeansData = projectsBeansZ;
                        recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansZ);
                        break;
                }
                //recordBetAdapter.addData(projectsBeansData);
                recordBetRView.setAdapter(recordBetAdapter);
                recordBetAdapter.setOnLoadMoreListener(new BaseQuickAdapter.RequestLoadMoreListener() {
                    @Override
                    public void onLoadMoreRequested() {
                        post(new Runnable() {
                            @Override
                            public void run() {
                                if(pageTotal>page) {
                                    ++page;
                                    onDataRequest();
                                }else{
                                    //数据全部加载完毕
                                    recordBetAdapter.loadMoreEnd();
                                }
                            }
                        });
                    }
                },recordBetRView);
                recordBetAdapter.notifyDataSetChanged();
                if(projectsBeansData.size()==0){
                    View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
                    recordBetAdapter.setEmptyView(view);
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

    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CpBetRecordsContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @OnClick({R.id.cpLotteryName,R.id.recordBetStartTime, R.id.recordBetEndTime,R.id.cpBetRecordsbackHome})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cpLotteryName:
                typeOptionsPicker.show();
                break;
            case R.id.recordBetStartTime:
                pvStartTime.show();
                break;
            case R.id.recordBetEndTime:
                pvEndTime.show();
                break;
            case R.id.cpBetRecordsbackHome:
                //刷新用户余额
                finish();
                break;
        }
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#ff0000'>" + sign+"</font>";
    }

    @Override
    public void getBetRecordsResult(BetRecordsResult betRecordsResult) {
        GameLog.log("注单列表 成功"+ betRecordsResult.getList().size());
        projectsBeansData = betRecordsResult.getList();
        if(projectsBeansData.size()>0){
            if(page==1){
                projectsBeansAll.clear();
            }
            projectsBeansAll.addAll(projectsBeansData);
        }else{
            projectsBeansAll.clear();
        }
        int size = projectsBeansAll.size();
        GameLog.log("当前数据的大小"+size);
        if(size==0){
            //recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansAll);
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("当前查询条件下暂无查询数据");
            textView.setTextColor(Color.parseColor("#C52133"));
            recordBetAdapter.setEmptyView(view);
            //recordBetRView.setAdapter(recordBetAdapter);
            return;
        }
        /*if(betRecordResult.getCount()>Integer.parseInt(betRecordResult.getPagesize())){
            int page1 = betRecordResult.getCount()/Integer.parseInt(betRecordResult.getPagesize());
            int page2 = betRecordResult.getCount()%Integer.parseInt(betRecordResult.getPagesize());
            if(page2>0){
                pageTotal = page1+1;
            }else{
                pageTotal = page1;
            }
        }else{
            pageTotal = 1;
        }*/
        projectsBeansW.clear();
        projectsBeansZ.clear();
        projectsBeansWZ.clear();
        for(int k=0;k<size;++k){
            switch (projectsBeansAll.get(k).getStatus()){
                case 0://未开奖
                    projectsBeansW.add(projectsBeansAll.get(k));
                    break;
                case 1://已撤销
                    break;
                case 2://未中奖
                    projectsBeansZ.add(projectsBeansAll.get(k));
                    break;
                case 3://已中奖
                    projectsBeansWZ.add(projectsBeansAll.get(k));
                    break;
            }
        }

        List<BetRecordsResult.ListBean> projectsBeansData0 = new ArrayList<>();
        List<BetRecordsResult.ListBean> projectsBeansData2 = new ArrayList<>();
        List<BetRecordsResult.ListBean> projectsBeansData3 = new ArrayList<>();
        if(position!=-1){
            for(int k=0;k<projectsBeansData.size();++k){
                switch (projectsBeansData.get(k).getStatus()){
                    case 0://未开奖
                        projectsBeansData0.add(projectsBeansData.get(k));
                        break;
                    case 1://已撤销
                        break;
                    case 2://未中奖
                        projectsBeansData2.add(projectsBeansData.get(k));
                        break;
                    case 3://已中奖
                        projectsBeansData3.add(projectsBeansData.get(k));
                        break;
                }
            }
            if(position==0){
                projectsBeansData = projectsBeansData0;
            }else if(position==2){
                projectsBeansData = projectsBeansData2;
            }else{
                projectsBeansData = projectsBeansData3;
            }

        }
        /*recordBetAdapter.setOnLoadMoreListener(new BaseQuickAdapter.RequestLoadMoreListener() {
            @Override
            public void onLoadMoreRequested() {
                post(new Runnable() {
                    @Override
                    public void run() {
                        if(pageTotal>page) {
                            ++page;
                            onDataRequest();
                        }else{
                            //数据全部加载完毕
                            recordBetAdapter.loadMoreEnd();
                        }
                    }
                });
            }
        },recordBetRView);*/
        if(!isNew){
            recordBetAdapter.addData(projectsBeansData);
        }else{
            recordBetAdapter.setNewData(projectsBeansData);
            isNew = false;
        }
        recordBetAdapter.notifyDataSetChanged();
        /*if(pageTotal<=page) {
            recordBetAdapter.loadMoreEnd();
        }else{
            recordBetAdapter.loadMoreComplete();
        }*/
        recordBetAdapter.loadMoreComplete();
    }
}

package com.gmcp.gm.ui.me.record;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.gmcp.gm.CFConstant;
import com.gmcp.gm.Injections;
import com.gmcp.gm.R;
import com.gmcp.gm.common.base.BaseFragment;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.event.StartBrotherEvent;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.DateHelper;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.widget.NTitleBar;
import com.gmcp.gm.data.AllGamesResult;
import com.gmcp.gm.data.BetRecordResult;
import com.gmcp.gm.data.BetRecordsResult;
import com.gmcp.gm.ui.me.record.betdetail.BetDetailFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class BetRecordFragment extends BaseFragment implements BetRecordContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private String typeArgs2,typeArgs3;
    BetRecordContract.Presenter presenter;
    @BindView(R.id.recordBetBack)
    NTitleBar recordBetBack;
    @BindView(R.id.recordBetType)
    TextView recordBetType;
    @BindView(R.id.cpLotteryName)
    TextView cpLotteryName;
    @BindView(R.id.recordBetStartTime)
    TextView recordBetStartTime;
    @BindView(R.id.recordBetEndTime)
    TextView recordBetEndTime;
    @BindView(R.id.recordBetStyle)
    TabLayout recordBetStyle;
    @BindView(R.id.recordBetRView)
    RecyclerView recordBetRView;
    List<BetRecordResult.ProjectsBean> projectsBeansAll = new ArrayList<>();
    RecordBetAdapter recordBetAdapter;
    RecordBetAdapterXY recordBetAdapterXY;
    List<BetRecordResult.ProjectsBean> projectsBeansW = new ArrayList<>();
    List<BetRecordResult.ProjectsBean> projectsBeansZ = new ArrayList<>();
    List<BetRecordResult.ProjectsBean> projectsBeansWZ = new ArrayList<>();
    List<BetRecordResult.ProjectsBean> projectsBeansData = new ArrayList<>();

    List<BetRecordsResult.ListBean> projectsBeansAllXY = new ArrayList<>();
    List<BetRecordsResult.ListBean> projectsBeansWXY = new ArrayList<>();
    List<BetRecordsResult.ListBean> projectsBeansZXY = new ArrayList<>();
    List<BetRecordsResult.ListBean> projectsBeansWZXY = new ArrayList<>();
    List<BetRecordsResult.ListBean> projectsBeansDataXY = new ArrayList<>();

    static List<String> projectsBeansType = new ArrayList<>();
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    //官方盘 0 和 信用盘  1
    OptionsPickerView OptionsType;

    OptionsPickerView OptionsPickerGF,OptionsPickerXY;
    String startTime,endTime;
    int position = -1;
    int pageTotal = 1;
    int page = 1;
    int pageType = 1 ;
    boolean isNew;
    String lottery_id="";
    static {
        projectsBeansType.add("官方盘");
        projectsBeansType.add("信用盘");
    }
    //官方盘
    private List<AllGamesResult.DataBean.LotteriesBean> GFLottery  = new ArrayList<>();
    //信用盘
    private List<AllGamesResult.DataBean.LotteriesBean> XYLottery  = new ArrayList<>();
    public static BetRecordFragment newInstance(String deposit_mode, String money) {
        BetRecordFragment betFragment = new BetRecordFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_record_bet;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
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
                        if(pageType==0){
                            projectsBeansData = projectsBeansAll;
                            recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansAll);
                        }else{
                            projectsBeansDataXY = projectsBeansAllXY;
                            recordBetAdapterXY = new RecordBetAdapterXY(R.layout.item_bet_record, projectsBeansAllXY);
                        }
                        break;
                    case 1:
                        position =0;
                        if(pageType==0){
                            projectsBeansData = projectsBeansW;
                            recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansW);
                        }else{
                            projectsBeansDataXY = projectsBeansWXY;
                            recordBetAdapterXY = new RecordBetAdapterXY(R.layout.item_bet_record, projectsBeansWXY);
                        }
                        break;
                    case 2:
                        position =2;
                        if(pageType==0){
                            projectsBeansData = projectsBeansWZ;
                            recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansWZ);
                        }else{
                            projectsBeansDataXY = projectsBeansWZXY;
                            recordBetAdapterXY = new RecordBetAdapterXY(R.layout.item_bet_record, projectsBeansWZXY);
                        }
                        break;
                    case 3:
                        position =3;
                        if(pageType==0){
                            projectsBeansData = projectsBeansZ;
                            recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansZ);
                        }else{
                            projectsBeansDataXY = projectsBeansZXY;
                            recordBetAdapterXY = new RecordBetAdapterXY(R.layout.item_bet_record, projectsBeansZXY);
                        }
                        break;
                }

                if(pageType==0){
                    recordBetRView.setAdapter(recordBetAdapter);
                    recordBetAdapter.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
                        @Override
                        public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                            //获取ID
                            GameLog.log("当前投注的ID 是 "+projectsBeansData.get(position).getId());
                            EventBus.getDefault().post(new StartBrotherEvent(BetDetailFragment.newInstance(projectsBeansData.get(position).getId()+"","")));
                        }
                    });
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
                }else{
                    recordBetRView.setAdapter(recordBetAdapterXY);
                    /*recordBetAdapterXY.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
                        @Override
                        public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                            //获取ID
                            GameLog.log("当前投注的ID 是 "+projectsBeansData.get(position).getId());
                            EventBus.getDefault().post(new StartBrotherEvent(BetDetailFragment.newInstance(projectsBeansData.get(position).getId()+"","")));
                        }
                    });*/
                    recordBetAdapterXY.setOnLoadMoreListener(new BaseQuickAdapter.RequestLoadMoreListener() {
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
                                        recordBetAdapterXY.loadMoreEnd();
                                    }
                                }
                            });
                        }
                    },recordBetRView);
                    recordBetAdapterXY.notifyDataSetChanged();
                    if(projectsBeansDataXY.size()==0){
                        View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
                        recordBetAdapterXY.setEmptyView(view);
                    }
                }
                //recordBetAdapter.addData(projectsBeansData);


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

    private void onDataRequest(){
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this,null);
        }
        if(pageType==0){
            presenter.getProjectList(lottery_id,page+"","20",startTime,endTime);
        }else{
            presenter.getCpBetRecords(lottery_id,"1",startTime,endTime);
        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        initBetStyle();
        //recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
        recordBetAdapterXY = new RecordBetAdapterXY(R.layout.item_bet_record, projectsBeansAllXY);
        recordBetRView.setAdapter(recordBetAdapterXY);
//        recordBetAdapter.setUpFetchEnable(true);
        startTime = DateHelper.getToday();
        endTime = DateHelper.getTom();
        onDataRequest();
        recordBetStartTime.setText(startTime);
        recordBetEndTime.setText(endTime);
        recordBetBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
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

        OptionsType= new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                pageType = options1;
                lottery_id = "";
                recordBetType.setText(projectsBeansType.get(options1));
                cpLotteryName.setText("全部游戏");
                if(pageType==0){
                    isNew = true;
                    projectsBeansData.clear();
                    recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
                    recordBetRView.setAdapter(recordBetAdapter);
                }else{
                    isNew = true;
                    projectsBeansDataXY.clear();
                    recordBetAdapterXY = new RecordBetAdapterXY(R.layout.item_bet_record, projectsBeansDataXY);
                    recordBetRView.setAdapter(recordBetAdapterXY);
                }
                onDataRequest();
                GameLog.log("目前的位置是 "+pageType);
            }
        }).build();
        OptionsType.setPicker(projectsBeansType);

        GFLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG), AllGamesResult.DataBean.LotteriesBean.class);
        XYLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_XINYONG), AllGamesResult.DataBean.LotteriesBean.class);
        OptionsPickerGF = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                lottery_id = GFLottery.get(options1).getLottery_id()+"";
                isNew = true;
                projectsBeansData.clear();
                recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
                recordBetRView.setAdapter(recordBetAdapter);
                onDataRequest();
                cpLotteryName.setText(GFLottery.get(options1).getName());
            }
        }).build();
        if (GFLottery.size() > 0) {
            OptionsPickerGF.setPicker(GFLottery);
        }
        OptionsPickerXY = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                lottery_id = XYLottery.get(options1).getId()+"";
                isNew = true;
                projectsBeansDataXY.clear();
                recordBetAdapterXY = new RecordBetAdapterXY(R.layout.item_bet_record, projectsBeansDataXY);
                recordBetRView.setAdapter(recordBetAdapterXY);
                onDataRequest();
                cpLotteryName.setText(XYLottery.get(options1).getName());
            }
        }).build();
        if (XYLottery.size() > 0) {
            OptionsPickerXY.setPicker(XYLottery);
        }
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        recordBetRView.setLayoutManager(linearLayoutManager);
    }

    @Subscribe
    public void onEventMain(BetDropEvent betDropEvent) {
        GameLog.log("=======注单详情界面EVENT================");
        isNew = true;
        onDataRequest();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Override
    public void getProjectListResult(BetRecordResult betRecordResult) {
        //转账前渠道确认
        GameLog.log("官方盘注单列表 成功"+betRecordResult.getProjects().size());
        projectsBeansData = betRecordResult.getProjects();
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
            /*TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("当前查询条件下暂无查询数据");
            textView.setTextColor(Color.parseColor("#C52133"));*/
            recordBetAdapter.setEmptyView(view);
            //recordBetRView.setAdapter(recordBetAdapter);
            return;
        }
        if(betRecordResult.getCount()>Integer.parseInt(betRecordResult.getPagesize())){
            int page1 = betRecordResult.getCount()/Integer.parseInt(betRecordResult.getPagesize());
            int page2 = betRecordResult.getCount()%Integer.parseInt(betRecordResult.getPagesize());
            if(page2>0){
                pageTotal = page1+1;
            }else{
                pageTotal = page1;
            }
        }else{
            pageTotal = 1;
        }
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

        List<BetRecordResult.ProjectsBean> projectsBeansData0 = new ArrayList<>();
        List<BetRecordResult.ProjectsBean> projectsBeansData2 = new ArrayList<>();
        List<BetRecordResult.ProjectsBean> projectsBeansData3 = new ArrayList<>();
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
        recordBetAdapter.setUpFetchListener(new BaseQuickAdapter.UpFetchListener() {
            @Override
            public void onUpFetch() {
                page =1;
                recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
                //recordBetAdapter.addData(projectsBeansData);
                recordBetRView.setAdapter(recordBetAdapter);
                onDataRequest();
            }
        });
        recordBetAdapter.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                //获取ID
                GameLog.log("当前投注的ID 是 "+projectsBeansAll.get(position).getId());
                EventBus.getDefault().post(new StartBrotherEvent(BetDetailFragment.newInstance(projectsBeansAll.get(position).getId()+"","")));
            }
        });
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
        if(!isNew){
            recordBetAdapter.addData(projectsBeansData);
        }else{
            recordBetAdapter.setNewData(projectsBeansData);
            isNew = false;
        }
        recordBetAdapter.notifyDataSetChanged();
        if(pageTotal<=page) {
            recordBetAdapter.loadMoreEnd();
        }else{
            recordBetAdapter.loadMoreComplete();
        }

    }

    @Override
    public void getBetRecordsResult(BetRecordsResult betRecordsResult) {
        GameLog.log("信用盘注单列表 成功"+ betRecordsResult.getList().size());
        projectsBeansDataXY = betRecordsResult.getList();
        if(projectsBeansDataXY.size()>0){
            if(page==1){
                projectsBeansAllXY.clear();
            }
            projectsBeansAllXY.addAll(projectsBeansDataXY);
        }else{
            projectsBeansAllXY.clear();
        }
        int size = projectsBeansAllXY.size();
        GameLog.log("当前数据的大小"+size);
        if(size==0){
            //recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansAll);
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("当前查询条件下暂无查询数据");
            textView.setTextColor(Color.parseColor("#C52133"));
            recordBetAdapterXY.setEmptyView(view);
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
        projectsBeansWXY.clear();
        projectsBeansZXY.clear();
        projectsBeansWZXY.clear();
        for(int k=0;k<size;++k){
            switch (projectsBeansAllXY.get(k).getStatus()){
                case 0://未开奖
                    projectsBeansWXY.add(projectsBeansAllXY.get(k));
                    break;
                case 1://已撤销
                    break;
                case 2://未中奖
                    projectsBeansZXY.add(projectsBeansAllXY.get(k));
                    break;
                case 3://已中奖
                    projectsBeansWZXY.add(projectsBeansAllXY.get(k));
                    break;
            }
        }

        List<BetRecordsResult.ListBean> projectsBeansData0 = new ArrayList<>();
        List<BetRecordsResult.ListBean> projectsBeansData2 = new ArrayList<>();
        List<BetRecordsResult.ListBean> projectsBeansData3 = new ArrayList<>();
        if(position!=-1){
            for(int k=0;k<projectsBeansDataXY.size();++k){
                switch (projectsBeansDataXY.get(k).getStatus()){
                    case 0://未开奖
                        projectsBeansData0.add(projectsBeansDataXY.get(k));
                        break;
                    case 1://已撤销
                        break;
                    case 2://未中奖
                        projectsBeansData2.add(projectsBeansDataXY.get(k));
                        break;
                    case 3://已中奖
                        projectsBeansData3.add(projectsBeansDataXY.get(k));
                        break;
                }
            }
            if(position==0){
                projectsBeansDataXY = projectsBeansData0;
            }else if(position==2){
                projectsBeansDataXY = projectsBeansData2;
            }else{
                projectsBeansDataXY = projectsBeansData3;
            }

        }
        if(!isNew){
            recordBetAdapterXY.addData(projectsBeansDataXY);
        }else{
            recordBetAdapterXY.setNewData(projectsBeansDataXY);
            isNew = false;
        }
        recordBetAdapterXY.notifyDataSetChanged();
        /*if(pageTotal<=page) {
            recordBetAdapter.loadMoreEnd();
        }else{
            recordBetAdapter.loadMoreComplete();
        }*/
        recordBetAdapterXY.loadMoreComplete();
    }

class RecordBetAdapterXY extends BaseQuickAdapter<BetRecordsResult.ListBean, BaseViewHolder> {

    public RecordBetAdapterXY(int layoutResId, @Nullable List<BetRecordsResult.ListBean> data) {
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


    class RecordBetAdapter extends BaseQuickAdapter<BetRecordResult.ProjectsBean, BaseViewHolder> {

        public RecordBetAdapter(int layoutResId, @Nullable List<BetRecordResult.ProjectsBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, BetRecordResult.ProjectsBean item) {

            switch (item.getLottery_id()){
                case 49:
                case 48:
                    helper.setText(R.id.itemBetRecordName, "幸运飞艇");
                    break;
                case 52:
                    helper.setText(R.id.itemBetRecordName, "北京赛车五分彩");
                    break;
                case 1:
                    helper.setText(R.id.itemBetRecordName, "欢乐生肖");
                    break;
                case 53:
                    helper.setText(R.id.itemBetRecordName, "重庆时时彩");
                    break;
                case 9:
                    helper.setText(R.id.itemBetRecordName, "广东11选5");
                    break;
                case 10:
                    helper.setText(R.id.itemBetRecordName, "北京PK10");
                    break;
                case 13:
                    helper.setText(R.id.itemBetRecordName, "分分彩");
                    break;
                case 14:
                    helper.setText(R.id.itemBetRecordName, "11选5");
                    break;
                case 15:
                    helper.setText(R.id.itemBetRecordName, "江苏快三");
                    break;
                case 16:
                    helper.setText(R.id.itemBetRecordName, "三分彩");
                    break;
                case 50:
                    helper.setText(R.id.itemBetRecordName, "五分快三");
                    break;
                case 51:
                    helper.setText(R.id.itemBetRecordName, "三分快三");
                    break;
                case 17:
                    helper.setText(R.id.itemBetRecordName, "一分快三");
                    break;
                case 19:
                    helper.setText(R.id.itemBetRecordName, "极速赛车");
                    break;
                case 20:
                    helper.setText(R.id.itemBetRecordName, "极速3D");
                    break;
                case 28:
                    helper.setText(R.id.itemBetRecordName, "五分彩");
                    break;
                case 30:
                    helper.setText(R.id.itemBetRecordName, "安徽快三");
                    break;
                case 37:
                    helper.setText(R.id.itemBetRecordName, "北京快乐8");
                    break;
                case 44:
                    helper.setText(R.id.itemBetRecordName, "11选5三分彩");
                    break;
            }
            switch (item.getStatus()){
                case 0:
                    helper.setText(R.id.itemBetRecordStatus,"待开奖");
                    helper.setTextColor(R.id.itemBetRecordStatus,Color.parseColor("#2c77ba"));
                    break;
                case 1:
                    helper.setTextColor(R.id.itemBetRecordStatus,Color.parseColor("#908e8e"));
                    helper.setText(R.id.itemBetRecordStatus,"已撤销");
                    break;
                case 2:
                    helper.setTextColor(R.id.itemBetRecordStatus,Color.parseColor("#c52133"));
                    helper.setText(R.id.itemBetRecordStatus,"未中奖");
                    break;
                case 3:
                    helper.setTextColor(R.id.itemBetRecordStatus,Color.parseColor("#c52133"));
                    helper.setText(R.id.itemBetRecordStatus,"已中奖");
            }
            helper.setText(R.id.itemBetRecordWay, item.getWay()).
                    setText(R.id.itemBetRecordAmount,item.getAmount()).
                    setText(R.id.itemBetRecordBought,item.getBought_at()).
                    setText(R.id.itemBetRecordBetNumber,item.getBet_number()).
                    setText(R.id.itemBetRecordIssue,item.getIssue()+"期" ).
                    setText(R.id.itemBetRecordSerialNumber,item.getSerial_number()).
                    setText(R.id.itemBetRecordWinNumber,item.getWinning_number()).
                    setText(R.id.itemBetRecordPrize,item.getPrize()).
                    setText(R.id.itemBetRecordUserName, ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT)).
                    addOnClickListener(R.id.itemPersonDetail);
        }
    }

    @Override
    public void setPresenter(BetRecordContract.Presenter presenter) {
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

    @OnClick({R.id.recordBetType,R.id.cpLotteryName,R.id.recordBetStartTime, R.id.recordBetEndTime})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.recordBetType:
                OptionsType.show();
                break;
            case R.id.cpLotteryName:
                if(pageType==0){
                    OptionsPickerGF.show();
                }else{
                    OptionsPickerXY.show();
                }
                break;
            case R.id.recordBetStartTime:
                pvStartTime.show();
                break;
            case R.id.recordBetEndTime:
                pvEndTime.show();
                break;
        }
    }
}
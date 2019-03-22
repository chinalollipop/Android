package com.cfcp.a01.ui.me.record;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.TimePickerView;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.BetRecordResult;
import com.cfcp.a01.data.DepositTypeResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.data.PersonReportResult;
import com.cfcp.a01.ui.home.texthtml.html.HtmlUtils;
import com.cfcp.a01.ui.lottery.LotteryResultFragment;
import com.cfcp.a01.ui.main.MainEvent;
import com.cfcp.a01.ui.me.record.betdetail.BetDetailFragment;
import com.cfcp.a01.ui.me.report.PersonFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class BetFragment extends BaseFragment implements BetContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private String typeArgs2,typeArgs3;
    BetContract.Presenter presenter;
    @BindView(R.id.recordBetBack)
    NTitleBar recordBetBack;
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
    List<BetRecordResult.ProjectsBean> projectsBeansW = new ArrayList<>();
    List<BetRecordResult.ProjectsBean> projectsBeansZ = new ArrayList<>();
    List<BetRecordResult.ProjectsBean> projectsBeansWZ = new ArrayList<>();
    List<BetRecordResult.ProjectsBean> projectsBeansData = new ArrayList<>();
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;

    String startTime,endTime;
    int position = -1;
    int pageTotal = 1;
    int page = 1;
    boolean isNew;

    public static BetFragment newInstance(String deposit_mode, String money) {
        BetFragment betFragment = new BetFragment();
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
        presenter.getProjectList(page+"","20",startTime,endTime);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        initBetStyle();
        recordBetAdapter = new RecordBetAdapter(R.layout.item_bet_record, projectsBeansData);
        recordBetRView.setAdapter(recordBetAdapter);
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
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        recordBetRView.setLayoutManager(linearLayoutManager);
    }

    @Subscribe
    public void onEventMain(BetDropEvent betDropEvent) {
        GameLog.log("=======注单详情界面EVENT================");
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
        GameLog.log("注单列表 成功"+betRecordResult.getProjects().size());
        projectsBeansData = betRecordResult.getProjects();
        if(projectsBeansData.size()>0){
            if(page==1){
                projectsBeansAll.clear();
            }
            projectsBeansAll.addAll(projectsBeansData);
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

    class RecordBetAdapter extends BaseQuickAdapter<BetRecordResult.ProjectsBean, BaseViewHolder> {

        public RecordBetAdapter(int layoutResId, @Nullable List<BetRecordResult.ProjectsBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, BetRecordResult.ProjectsBean item) {

            switch (item.getLottery_id()){
                case 49:
                case 48:
                case 50:
                    helper.setText(R.id.itemBetRecordName, "幸运飞艇");
                    break;
                case 1:
                    helper.setText(R.id.itemBetRecordName, "重庆时时彩");
                    break;
                case 9:
                    helper.setText(R.id.itemBetRecordName, "广东11选5");
                    break;
                case 10:
                    helper.setText(R.id.itemBetRecordName, "北京PK10");
                    break;
                case 13:
                    helper.setText(R.id.itemBetRecordName, "官网分分彩");
                    break;
                case 14:
                    helper.setText(R.id.itemBetRecordName, "官网11选5");
                    break;
                case 15:
                    helper.setText(R.id.itemBetRecordName, "江苏快三");
                    break;
                case 16:
                    helper.setText(R.id.itemBetRecordName, "官网三分彩");
                    break;
                case 17:
                    helper.setText(R.id.itemBetRecordName, "官网快三分分彩");
                    break;
                case 19:
                    helper.setText(R.id.itemBetRecordName, "官网极速PK10");
                    break;
                case 20:
                    helper.setText(R.id.itemBetRecordName, "官网极速3D");
                    break;
                case 28:
                    helper.setText(R.id.itemBetRecordName, "官网五分彩");
                    break;
                case 30:
                    helper.setText(R.id.itemBetRecordName, "安徽快三");
                    break;
                case 37:
                    helper.setText(R.id.itemBetRecordName, "北京快乐8");
                    break;
                case 40:
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
    public void setPresenter(BetContract.Presenter presenter) {
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

    @OnClick({R.id.recordBetStartTime, R.id.recordBetEndTime})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.recordBetStartTime:
                pvStartTime.show();
                break;
            case R.id.recordBetEndTime:
                pvEndTime.show();
                break;
        }
    }
}
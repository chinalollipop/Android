package com.cfcp.a01.ui.me.record.overbet;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
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
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.TraceListResult;
import com.cfcp.a01.ui.me.record.tracedetail.TraceDetailFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class TraceListFragment extends BaseFragment implements TraceListContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private String typeArgs2,typeArgs3;
    TraceListContract.Presenter presenter;
    @BindView(R.id.recordBetAddBack)
    NTitleBar recordBetAddBack;
    @BindView(R.id.recordBetAddStartTime)
    TextView recordBetAddStartTime;
    @BindView(R.id.recordBetAddEndTime)
    TextView recordBetAddEndTime;
    @BindView(R.id.recordBetAddGameStyle)
    TextView recordBetAddGameStyle;
    @BindView(R.id.recordBetAddRView)
    RecyclerView recordBetAddRView;
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    TraceListAdapter traceListAdapter;
    List<TraceListResult.TracesBean> tracesBeanList = new ArrayList<>();
    List<TraceListResult.TracesBean> tracesBeanListAll = new ArrayList<>();
    //代表彩种ID
    private String  lotteryId = "";
    int page = 1;
    boolean isNew;
    int pageTotal = 1;
    OptionsPickerView gtypeOptionsPicker;
    String startTime,endTime;
    //官方盘的列表
    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLottery  = new ArrayList<>();

    public static TraceListFragment newInstance(String deposit_mode, String money) {
        TraceListFragment betFragment = new TraceListFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_trace_list;
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



    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        AvailableLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG), AllGamesResult.DataBean.LotteriesBean.class);
        //lotteryId = AvailableLottery.get(0).getId()+"";
        startTime = DateHelper.getToday();
        endTime = DateHelper.getTom();
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        recordBetAddRView.setLayoutManager(linearLayoutManager);
        traceListAdapter = new TraceListAdapter(R.layout.item_trace_record,tracesBeanList);
        recordBetAddRView.setAdapter(traceListAdapter);
        onRequsetData();
        recordBetAddStartTime.setText(startTime);
        recordBetAddEndTime.setText(endTime);
        recordBetAddBack.setBackListener(new View.OnClickListener() {
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
                tracesBeanList.clear();
                traceListAdapter = new TraceListAdapter(R.layout.item_trace_record, tracesBeanList);
                recordBetAddRView.setAdapter(traceListAdapter);
                recordBetAddStartTime.setText(startTime);
                onRequsetData();
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
                tracesBeanList.clear();
                traceListAdapter = new TraceListAdapter(R.layout.item_trace_record, tracesBeanList);
                recordBetAddRView.setAdapter(traceListAdapter);
                recordBetAddEndTime.setText(endTime);
                onRequsetData();
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
        gtypeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {

                String text = AvailableLottery.get(options1).getName();
                recordBetAddGameStyle.setText(text);
                lotteryId = AvailableLottery.get(options1).getLottery_id()+"";
                page =1;
                isNew = true;
                tracesBeanList.clear();
                traceListAdapter = new TraceListAdapter(R.layout.item_trace_record, tracesBeanList);
                recordBetAddRView.setAdapter(traceListAdapter);
                onRequsetData();

            }
        }).build();
        gtypeOptionsPicker.setPicker(AvailableLottery);
    }


    //请求数据接口
    private void onRequsetData(){
        presenter.getTraceList(lotteryId,page+"","20",startTime,endTime);
    }


    @Override
    public void getTraceListResult(TraceListResult traceListResult) {
        GameLog.log("查询追号列表 成功");
        if(page == 1){
            tracesBeanList.clear();
            tracesBeanListAll.clear();
        }
        tracesBeanList = traceListResult.getTraces();
        tracesBeanListAll.addAll(tracesBeanList);
        int size   = tracesBeanList.size();
        if(size==0){
            //traceListAdapter = new TraceListAdapter(R.layout.item_trace_record,tracesBeanList);
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            traceListAdapter.setEmptyView(view);
            return;
        }
        if(traceListResult.getCount()>Integer.parseInt(traceListResult.getPagesize())){
            int page1 = traceListResult.getCount()/Integer.parseInt(traceListResult.getPagesize());
            int page2 = traceListResult.getCount()%Integer.parseInt(traceListResult.getPagesize());
            if(page2>0){
                pageTotal = page1+1;
            }else{
                pageTotal = page1;
            }
        }else{
            pageTotal = 1;
        }

        traceListAdapter.setOnItemClickListener(new BaseQuickAdapter.OnItemClickListener() {
            @Override
            public void onItemClick(BaseQuickAdapter adapter, View view, int position) {
                //获取ID
                GameLog.log("当前追号的ID 是 "+tracesBeanListAll.get(position).getId());
                EventBus.getDefault().post(new StartBrotherEvent(TraceDetailFragment.newInstance(tracesBeanListAll.get(position).getId()+"","")));
            }
        });
        traceListAdapter.setOnLoadMoreListener(new BaseQuickAdapter.RequestLoadMoreListener() {
            @Override
            public void onLoadMoreRequested() {
                post(new Runnable() {
                    @Override
                    public void run() {
                        if(pageTotal>page) {
                            ++page;
                            onRequsetData();
                        }else{
                            //数据全部加载完毕
                            traceListAdapter.loadMoreEnd();
                        }
                    }
                });
            }
        },recordBetAddRView);
        if(!isNew){
            traceListAdapter.addData(tracesBeanList);
        }else{
            traceListAdapter.setNewData(tracesBeanList);
            isNew = false;
        }
        traceListAdapter.notifyDataSetChanged();
        if(pageTotal<=page) {
            traceListAdapter.loadMoreEnd();
        }else{
            traceListAdapter.loadMoreComplete();
        }
    }


    class TraceListAdapter extends BaseQuickAdapter<TraceListResult.TracesBean, BaseViewHolder> {

        public TraceListAdapter(int layoutResId, @Nullable List<TraceListResult.TracesBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, TraceListResult.TracesBean item) {

            switch (item.getLottery_id()) {
                case 49:
                case 48:
                case 50:
                    helper.setText(R.id.itemTraceRecordName, "幸运飞艇");
                    break;
                case 1:
                    helper.setText(R.id.itemTraceRecordName, "重庆时时彩");
                    break;
                case 9:
                    helper.setText(R.id.itemTraceRecordName, "广东11选5");
                    break;
                case 10:
                    helper.setText(R.id.itemTraceRecordName, "北京PK10");
                    break;
                case 13:
                    helper.setText(R.id.itemTraceRecordName, "分分彩");
                    break;
                case 14:
                    helper.setText(R.id.itemTraceRecordName, "11选5");
                    break;
                case 15:
                    helper.setText(R.id.itemTraceRecordName, "江苏快三");
                    break;
                case 16:
                    helper.setText(R.id.itemTraceRecordName, "三分彩");
                    break;
                case 17:
                    helper.setText(R.id.itemTraceRecordName, "快三分分彩");
                    break;
                case 19:
                    helper.setText(R.id.itemTraceRecordName, "极速PK10");
                    break;
                case 20:
                    helper.setText(R.id.itemTraceRecordName, "极速3D");
                    break;
                case 28:
                    helper.setText(R.id.itemTraceRecordName, "五分彩");
                    break;
                case 30:
                    helper.setText(R.id.itemTraceRecordName, "安徽快三");
                    break;
                case 37:
                    helper.setText(R.id.itemTraceRecordName, "北京快乐8");
                    break;
                case 40:
                    helper.setText(R.id.itemTraceRecordName, "11选5三分彩");
                    break;
            }
            switch (item.getStatus()) {
                case 0:
                    helper.setText(R.id.itemTraceRecordStatus, "待开奖");
                    helper.setTextColor(R.id.itemTraceRecordStatus, Color.parseColor("#2c77ba"));
                    break;
                case 1:
                    helper.setTextColor(R.id.itemTraceRecordStatus, Color.parseColor("#908e8e"));
                    helper.setText(R.id.itemTraceRecordStatus, "已撤销");
                    break;
                case 2:
                    helper.setTextColor(R.id.itemTraceRecordStatus, Color.parseColor("#c52133"));
                    helper.setText(R.id.itemTraceRecordStatus, "未中奖");
                    break;
                case 3:
                    helper.setTextColor(R.id.itemTraceRecordStatus, Color.parseColor("#c52133"));
                    helper.setText(R.id.itemTraceRecordStatus, "已中奖");
            }
            helper.setText(R.id.itemTraceRecordWay, item.getWay()).
                    setText(R.id.itemTraceRecordamount, item.getAmount()).
                    setText(R.id.itemTraceRecordbought_at, item.getBought_at()).
                    setText(R.id.itemTraceRecordbet_number, item.getBet_number()).
                    setText(R.id.itemTraceRecordstart_issue, item.getStart_issue() + "期").
                    setText(R.id.itemTraceRecordserial_number, item.getSerial_number()).
                    setText(R.id.itemTraceRecordsingle_amount, item.getSingle_amount()).
                    setText(R.id.itemTraceRecordfinished_issues, item.getFinished_issues()+"").
                    setText(R.id.itemTraceRecordtotal_issues, item.getTotal_issues()+"").
                    setText(R.id.itemTraceRecordUserName, ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT));
        }
    }


        @Override
        public void setPresenter(TraceListContract.Presenter presenter) {
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

        @OnClick({R.id.recordBetAddStartTime, R.id.recordBetAddEndTime, R.id.recordBetAddGameStyle})
        public void onViewClicked(View view) {
            switch (view.getId()) {
                case R.id.recordBetAddStartTime:
                    pvStartTime.show();
                    break;
                case R.id.recordBetAddEndTime:
                    pvEndTime.show();
                    break;
                case R.id.recordBetAddGameStyle:
                    gtypeOptionsPicker.show();
                    break;
            }
        }
}

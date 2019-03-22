package com.cfcp.a01.ui.me.report.myreport;

import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.MyReportResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class MyReportFragment extends BaseFragment implements MyReportContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.personTurnover)
    TextView personTurnover;
    @BindView(R.id.personPrize)
    TextView personPrize;
    @BindView(R.id.personProfit)
    TextView personProfit;
    @BindView(R.id.personDetail)
    TextView personDetail;
    @BindView(R.id.personWithDraw)
    TextView personWithDraw;
    @BindView(R.id.personDeposit)
    TextView personDeposit;
    @BindView(R.id.personPrize1)
    TextView personPrize1;
    @BindView(R.id.personBonus)
    TextView personBonus;
    @BindView(R.id.personCom)
    TextView personCom;
    @BindView(R.id.personDetailLay)
    LinearLayout personDetailLay;
    //typeArgs2 {0 账变报表 ,1 为充值记录 2提现记录}
    private String typeArgs2, typeArgs3="";
    MyReportContract.Presenter presenter;
    @BindView(R.id.myReportBack)
    NTitleBar myReportBack;
    @BindView(R.id.myRepostTime)
    LinearLayout myRepostTime;
    @BindView(R.id.myReportTab)
    TabLayout myReportTab;
    @BindView(R.id.myReportCount)
    TextView myReportCount;
    @BindView(R.id.myReportExpenses)
    TextView myReportExpenses;
    @BindView(R.id.myReportRevenue)
    TextView myReportRevenue;
    @BindView(R.id.myReportStartTime)
    TextView myReportStartTime;
    @BindView(R.id.myReportEndTime)
    TextView myReportEndTime;
    @BindView(R.id.myReportRView)
    RecyclerView myReportRView;
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    OptionsPickerView gtypeOptionsPicker;
    MyReportAdapter myReportAdapter;
    String startTime, endTime,type_id="";
    int page = 1;
    boolean isNew;
    int pageTotal = 1;
    List<MyReportResult.ATransactionsBean> userProfitsBeanList = new ArrayList<>();
    int position;
    public static MyReportFragment newInstance(String modeType, String user_id) {
        MyReportFragment betFragment = new MyReportFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, modeType);
        args.putString(TYPE3, user_id);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_report_mine;
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

    //充提记录 {1,2}
    private void initMyReportStyle() {
        //presenter.getDepositSubmit(typeArgs2,"","","");
        myReportTab.addTab(myReportTab.newTab().setText("充值记录"));
        myReportTab.addTab(myReportTab.newTab().setText("提现记录"));
        myReportTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                position = tab.getPosition();
                switch (position) {
                    case 0:
                        type_id ="1";
                        break;
                    case 1:
                        type_id ="2";
                        break;
                }
                page =1;
                isNew = true;
                onRequsetData();
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
    public void setEvents(@Nullable Bundle savedInstanceState) {
        endTime = DateHelper.getTom();
        if(typeArgs2.equals("0")){
            type_id ="";
            startTime = DateHelper.getToday();
            myReportBack.setTitle("账变报表");
            myRepostTime.setVisibility(View.VISIBLE);
            myReportTab.setVisibility(View.GONE);
        }else{
            type_id ="1";
            startTime = DateHelper.getHalfYearStartTime();
            //startTime = DateHelper.getHalfYearEndTime();
            myReportBack.setTitle("充提记录");
            myRepostTime.setVisibility(View.GONE);
            myReportTab.setVisibility(View.VISIBLE);
            initMyReportStyle();
        }
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        myReportRView.setLayoutManager(linearLayoutManager);
        myReportAdapter = new MyReportAdapter(R.layout.item_me_report, userProfitsBeanList);
        myReportRView.setAdapter(myReportAdapter);
        onRequsetData();
        myReportStartTime.setText(startTime);
        myReportEndTime.setText(endTime);
        myReportBack.setBackListener(new View.OnClickListener() {
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
                onRequsetData();
                myReportStartTime.setText(startTime);
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
                onRequsetData();
                myReportEndTime.setText(endTime);
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
    }

    //请求数据接口
    private void onRequsetData() {
        presenter.getPersonReport(typeArgs3,type_id,startTime, endTime,page+"","20");
    }

    @Override
    public void getPersonReportResult(MyReportResult myReportResult) {
        GameLog.log("账变记录 成功");
        if(page == 1){
            userProfitsBeanList.clear();
        }
        userProfitsBeanList = myReportResult.getATransactions();
        myReportCount.setText("小计 "+myReportResult.getCount()+"笔");
        myReportExpenses.setText("总支出 "+myReportResult.getBTotalExpenses());
        myReportRevenue.setText("总收入 "+myReportResult.getBTotalRevenue());
        if(userProfitsBeanList.size()==0){
            myReportAdapter = new MyReportAdapter(R.layout.item_me_report, userProfitsBeanList);
            myReportRView.setAdapter(myReportAdapter);
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("当前查询条件下暂无查询数据");
            textView.setTextColor(Color.parseColor("#C52133"));
            myReportAdapter.setEmptyView(view);
            return;
        }

        if(myReportResult.getCount()>Integer.parseInt(myReportResult.getIPageSize())){
            int page1 = myReportResult.getCount()/Integer.parseInt(myReportResult.getIPageSize());
            int page2 = myReportResult.getCount()%Integer.parseInt(myReportResult.getIPageSize());
            if(page2>0){
                pageTotal = page1+1;
            }else{
                pageTotal = page1;
            }
            if(pageTotal>page){//最后一页的时候需要再次计算
                myReportCount.setText("小计 "+(Integer.parseInt(myReportResult.getIPage())*Integer.parseInt(myReportResult.getIPageSize()))+"笔");
            }else{
                myReportCount.setText("小计 "+myReportResult.getCount()+"笔");
            }
        }else{
            pageTotal = 1;
        }
        myReportAdapter.setOnLoadMoreListener(new BaseQuickAdapter.RequestLoadMoreListener() {
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
                            myReportAdapter.loadMoreEnd();
                        }
                    }
                });
            }
        },myReportRView);
        if(!isNew){
            myReportAdapter.addData(userProfitsBeanList);
        }else{
            myReportAdapter.setNewData(userProfitsBeanList);
            isNew = false;
        }
        myReportAdapter.notifyDataSetChanged();
        if(pageTotal<=page) {
            myReportAdapter.loadMoreEnd();
        }else{
            myReportAdapter.loadMoreComplete();
        }

    }


    class MyReportAdapter extends BaseQuickAdapter<MyReportResult.ATransactionsBean, BaseViewHolder> {

        public MyReportAdapter(int layoutResId, @Nullable List<MyReportResult.ATransactionsBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, MyReportResult.ATransactionsBean item) {

            String lotteryName = "";
            switch (item.getLottery_id()) {
                case 49:
                case 48:
                case 50:
                    lotteryName="幸运飞艇";
                    break;
                case 1:
                    lotteryName="重庆时时彩";
                    break;
                case 9:
                    lotteryName="广东11选5";
                    break;
                case 10:
                    lotteryName="北京PK10";
                    break;
                case 13:
                    lotteryName= "官网分分彩";
                    break;
                case 14:
                    lotteryName= "官网11选5";
                    break;
                case 15:
                    lotteryName= "江苏快三";
                    break;
                case 16:
                    lotteryName= "官网三分彩";
                    break;
                case 17:
                    lotteryName= "官网快三分分彩";
                    break;
                case 19:
                    lotteryName= "官网极速PK10";
                    break;
                case 20:
                    lotteryName= "官网极速3D";
                    break;
                case 28:
                    lotteryName= "官网五分彩";
                    break;
                case 30:
                    lotteryName= "安徽快三";
                    break;
                case 37:
                    lotteryName= "北京快乐8";
                    break;
                case 40:
                    lotteryName= "11选5三分彩";
                    break;
            }
            if(item.getIs_income()==1){
                helper.setText(R.id.itemMyReportamount, "+"+GameShipHelper.formatMoney(item.getAmount()));
                helper.setText(R.id.itemMyReportcoefficient, "/");
            }else{
                helper.setText(R.id.itemMyReportamount, "/");
                helper.setText(R.id.itemMyReportcoefficient, "-"+GameShipHelper.formatMoney(item.getAmount()));
            }

            helper.setText(R.id.itemMyReportcreated_at, item.getCreated_at()+"").
                    setText(R.id.itemMyReportablance, GameShipHelper.formatMoney(item.getAblance())).
                    setText(R.id.itemMyReportdescription, item.getDescription()).
                    setText(R.id.itemMyReportUserName, ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT));
            if(typeArgs2.equals("0")){
                helper.setText(R.id.itemMyReportReMarket, "官方盘 "+lotteryName+"ID"+item.getLottery_id()+" 注单ID"+item.getProject_id());
            }else{
                helper.setText(R.id.itemMyReportReMarket,item.getNote());
            }
        }
    }

    @Override
    public void setPresenter(MyReportContract.Presenter presenter) {
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

    @OnClick({R.id.myReportStartTime, R.id.myReportEndTime,R.id.personDetail})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.myReportStartTime:
                pvStartTime.show();
                break;
            case R.id.myReportEndTime:
                pvEndTime.show();
                break;
            case R.id.personDetail:
                if(personDetailLay.isShown()){
                    personDetailLay.setVisibility(View.GONE);
                }else{
                    personDetailLay.setVisibility(View.VISIBLE);
                }
                break;
        }
    }
}

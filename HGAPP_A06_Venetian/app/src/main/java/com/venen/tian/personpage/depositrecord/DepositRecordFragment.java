package com.venen.tian.personpage.depositrecord;

import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.TimePickerView;
import com.venen.tian.Injections;
import com.venen.tian.R;
import com.venen.tian.base.HGBaseFragment;
import com.venen.tian.base.IPresenter;
import com.venen.tian.common.adapters.AutoSizeRVAdapter;
import com.venen.tian.common.util.CLipHelper;
import com.venen.tian.common.util.DateHelper;
import com.venen.tian.common.widgets.CustomPopWindow;
import com.venen.tian.common.widgets.NTitleBar;
import com.venen.tian.data.RecordResult;
import com.venen.common.util.Check;
import com.venen.common.util.GameLog;
import com.jcodecraeer.xrecyclerview.ProgressStyle;
import com.jcodecraeer.xrecyclerview.XRecyclerView;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class DepositRecordFragment extends HGBaseFragment implements DepositRecordContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    @BindView(R.id.llDepositRecordRemit)
    LinearLayout llDepositRecordRemit;
   /* @BindView(R.id.lvDepositRecord)
    ListView lvDepositRecord;*/
    @BindView(R.id.lvDepositRecor2)
   XRecyclerView lvDepositRecord2;
    @BindView(R.id.depositeTTop)
    ImageView depositeTop;
    @BindView(R.id.tvDepositNodata)
    TextView tvDepositNodata;
    @BindView(R.id.tvDepositRecordBack)
    NTitleBar tvDepositRecordBack;
    @BindView(R.id.tvDepositRecordType)
    TextView tvDepositRecordType;
    @BindView(R.id.tvDepositRecordStatus)
    TextView tvDepositRecordStatus;
    @BindView(R.id.tvDepositRecordStartTime)
    TextView tvDepositRecordStartTime;
    @BindView(R.id.tvDepositRecordEndTime)
    TextView tvDepositRecordEndTime;
    @BindView(R.id.tvDepositRecordToday)
    TextView tvDepositRecordToday;
    @BindView(R.id.tvDepositRecordLastDay)
    TextView tvDepositRecordLastDay;
    @BindView(R.id.tvDepositRecordLastWeek)
    TextView tvDepositRecordLastWeek;
    @BindView(R.id.tvDepositRecordLastMonth)
    TextView tvDepositRecordLastMonth;
    @BindView(R.id.btnDepositRecordSubmit)
    Button btnDepositRecordSubmit;
    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    private DepositRecordContract.Presenter presenter;

    private String typeArgs1;
    private String typeArgs2;

    LinearLayout popMenuDepositStatusAll,popMenuDepositStatusDeposit,popMenuDepositStatusWithDraw,popMenuDepositStatusRed,popMenuDepositStatusFan;

    TextView tvDepositStatusAll,tvDepositStatusDeposit,tvDepositStatusWithdraw,tvDepositStatusRed,tvDepositStatusFan;
    ImageView ivDepositStatusAll,ivDepositStatusDeposit,ivDepositStatusWithdraw,ivDepositStatusRed,ivDepositStatusFan;
    private String stype ="";// S 存款，T 提款
    private String type_status ="";//// 审核状态: 0 首次提交订单 2 二次审核  1成功 -1失败
    private String data_start,data_end;
    private CustomPopWindow mCustomPopWindowIn;
    private CustomPopWindow mCustomPopWindowOut;
    int page=0;
    String tinme;
    boolean isNow;
    List<RecordResult.RowsBean> rowsBeanList = new ArrayList();
    RecordListAdapter recordListAdapter;

    public static DepositRecordFragment newInstance(String type1, String type2) {
        DepositRecordFragment fragment = new DepositRecordFragment();
        Bundle args = new Bundle();
        args.putString(TYPE1, type1);
        args.putString(TYPE2, type2);
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs1 = getArguments().getString(TYPE1);
            typeArgs2 = getArguments().getString(TYPE2);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_deposit_record;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        tinme= getTime2(new Date());
        if(Integer.parseInt("12")>Integer.parseInt(tinme)){
            isNow = false;
            data_end = DateHelper.getYesterday();
            data_start = DateHelper.getYesterday();
        }else{
            isNow = true;
            data_end = DateHelper.getToday();
            data_start = DateHelper.getToday();
        }
        tvDepositRecordStartTime.setText(data_start);
        tvDepositRecordEndTime.setText(data_end);
        tvDepositRecordBack.setMoreText(typeArgs2);
        tvDepositRecordBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });

        //onSearchRecordList();

        //时间选择器
        pvStartTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvDepositRecordStartTime.setText(getTime(date));
            }
        })
                // .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();

        //时间选择器
        pvEndTime = new TimePickerBuilder(getContext(), new OnTimeSelectListener() {
            @Override
            public void onTimeSelect(Date date, View v) {
                tvDepositRecordEndTime.setText(getTime(date));
            }
        })
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();

        final LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        lvDepositRecord2.setLayoutManager(gridLayoutManager);
        lvDepositRecord2.setHasFixedSize(true);
        lvDepositRecord2.setNestedScrollingEnabled(true);
        lvDepositRecord2.setRefreshProgressStyle(ProgressStyle.BallSpinFadeLoader);
        lvDepositRecord2.setLoadingMoreProgressStyle(ProgressStyle.BallRotate);
        //lvDepositRecord2.addItemDecoration(new DividerItemDecoration(getContext(),DividerItemDecoration.VERTICAL));
        lvDepositRecord2.setOnScrollListener(new RecyclerView.OnScrollListener() {
            @Override
            public void onScrollStateChanged(RecyclerView recyclerView, int newState) {
                super.onScrollStateChanged(recyclerView, newState);
                int childCount = gridLayoutManager.getChildCount();
                int first = gridLayoutManager.findLastVisibleItemPosition();
                GameLog.log("当前可见的位置是："+first);
                if(first >= 10){//first>=childCount/2
                    if(!Check.isNull(depositeTop)){
                        depositeTop.setVisibility(View.VISIBLE);
                    }
                }else{
                    if(!Check.isNull(depositeTop)){
                        depositeTop.setVisibility(View.GONE);
                    }
                }
            }

            @Override
            public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
                super.onScrolled(recyclerView, dx, dy);
            }
        });
        /*lvDepositRecord2.setScrollAlphaChangeListener(new XRecyclerView.ScrollAlphaChangeListener() {
            @Override
            public void onAlphaChange(int alpha) {
                depositeTop.getBackground().setAlpha(alpha);
            }

            @Override
            public int setLimitHeight() {
                return 1300;
            }
        });*/
        lvDepositRecord2.setLoadingListener(new XRecyclerView.LoadingListener() {
            @Override
            public void onRefresh() {
                page =0;
                onSearchRecordList();
            }

            @Override
            public void onLoadMore() {
                ++page;
                onSearchRecordList();
            }
        });

    }

    private void onSearchRecordList(){
        //第一个参数如果是S就是存款记录 page 是分页加载
        //第二个参数如果是T就是取款记录
        /*if ("T".equals(typeArgs1)) {//转账记录
            llDepositRecordRemit.setVisibility(View.GONE);
            presenter.getDepositRecord("", stype, "0");
        } else {//交易记录
            presenter.getDepositRecord("", stype, "0");
        }*/
        data_start = tvDepositRecordStartTime.getText().toString();
        data_end = tvDepositRecordEndTime.getText().toString();
        presenter.getDepositRecord("", stype, page+"",type_status,data_start,data_end);
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }
    public static String getTime2(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("HH");
        return format.format(date);
    }

    @Override
    public void postDepositRecordResult(RecordResult message) {
        GameLog.log("总共充值多少：" + message.getTotal());

        if(!Check.isNull(message.getRows())&&message.getRows().size()>0){
            tvDepositNodata.setVisibility(View.GONE);
            /*lvDepositRecord.setVisibility(View.VISIBLE);
            lvDepositRecord.setAdapter(new RecordListAdapter(getContext(), R.layout.item_record, message.getRows()));*/
            lvDepositRecord2.setVisibility(View.VISIBLE);
            if(page==0){
                rowsBeanList.clear();
                lvDepositRecord2.refreshComplete();
                GameLog.log("刷新完成");
            }else{
                if(page >= message.getPage_count()-1){
                    lvDepositRecord2.setNoMore(true);
                    GameLog.log("无更多数据完成");
                }else{
                    lvDepositRecord2.loadMoreComplete();
                    GameLog.log("加载更多完成");
                }
            }
            rowsBeanList.addAll(message.getRows());
            if(recordListAdapter ==null){
                recordListAdapter =   new RecordListAdapter(getContext(), R.layout.item_record,rowsBeanList );
                lvDepositRecord2.setAdapter(recordListAdapter);
            }
            recordListAdapter.notifyDataSetChanged();
        }else{
            if(rowsBeanList.size()>0){
                lvDepositRecord2.setNoMore(true);
            }else{
                tvDepositNodata.setVisibility(View.VISIBLE);
                //lvDepositRecord.setVisibility(View.GONE);
                lvDepositRecord2.setVisibility(View.GONE);
            }
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


    @Override
    public void setPresenter(DepositRecordContract.Presenter presenter) {

        this.presenter = presenter;
    }


    private void handleLogicPopMenuDepositType(View contentView){

        View.OnClickListener listener = new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                /*if(mCustomPopWindow!=null){
                    mCustomPopWindow.dissmiss();
                }*/
                String showContent = "";
                switch (v.getId()){
                    case R.id.popMenuDepositTypeAll:
                        stype = "";
                        tvDepositRecordType.setText("全部");
                        showContent = "Out点击 Item菜单1";
                        break;
                    case R.id.popMenuDepositTypeDeposit:
                        stype = "S";
                        tvDepositRecordType.setText("充值");
                        showContent = "Out 点击 Item菜单2";
                        break;
                    case R.id.popMenuDepositTypeWithDraw:
                        stype = "T";
                        tvDepositRecordType.setText("提现");
                        showContent = "Out 点击 Item菜单3";
                        break;
                    case R.id.popMenuDepositTypeRed:
                        stype = "Q";
                        tvDepositRecordType.setText("额度转换");
                        showContent = "Out 点击 Item菜单4";
                        break;
                    case R.id.popMenuDepositTypeFan:
                        stype = "R";
                        tvDepositRecordType.setText("返水");
                        showContent = "Out 点击 Item菜单5";
                        break;
                }
                GameLog.log("转入："+showContent);
                //showMessage(showContent);
                mCustomPopWindowIn.dissmiss();
            }
        };
        popMenuDepositStatusAll = (LinearLayout) contentView.findViewById(R.id.popMenuDepositTypeAll);
        popMenuDepositStatusDeposit = (LinearLayout) contentView.findViewById(R.id.popMenuDepositTypeDeposit);
        popMenuDepositStatusWithDraw = (LinearLayout) contentView.findViewById(R.id.popMenuDepositTypeWithDraw);
        popMenuDepositStatusRed = (LinearLayout) contentView.findViewById(R.id.popMenuDepositTypeRed);
        popMenuDepositStatusFan = (LinearLayout) contentView.findViewById(R.id.popMenuDepositTypeFan);
        ivDepositStatusAll = (ImageView) contentView.findViewById(R.id.ivDepositTypeAll);
        ivDepositStatusDeposit = (ImageView) contentView.findViewById(R.id.ivDepositTypeDeposit);
        ivDepositStatusWithdraw = (ImageView) contentView.findViewById(R.id.ivDepositTypeWithDraw);
        ivDepositStatusRed = (ImageView) contentView.findViewById(R.id.ivDepositTypeRed);
        ivDepositStatusFan = (ImageView) contentView.findViewById(R.id.ivDepositTypeFan);
        tvDepositStatusAll = (TextView) contentView.findViewById(R.id.tvDepositTypeAll);
        tvDepositStatusDeposit = (TextView) contentView.findViewById(R.id.tvDepositTypeDeposit);
        tvDepositStatusWithdraw = (TextView) contentView.findViewById(R.id.tvDepositTypeWithDraw);
        tvDepositStatusRed = (TextView) contentView.findViewById(R.id.tvDepositTypeRed);
        tvDepositStatusFan = (TextView) contentView.findViewById(R.id.tvDepositTypeFan);
        popMenuDepositStatusAll.setOnClickListener(listener);
        popMenuDepositStatusDeposit.setOnClickListener(listener);
        popMenuDepositStatusWithDraw.setOnClickListener(listener);
        popMenuDepositStatusRed.setOnClickListener(listener);
        popMenuDepositStatusFan.setOnClickListener(listener);
        // if(!Check.isNull(popMenuHGtv)&&!Check.isNull(popMenuCPtv)&&!Check.isNull(popMenuAGtv)){
        switch (stype){
            case "":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusFan.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                ivDepositStatusAll.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                ivDepositStatusDeposit.setBackgroundResource(0);
                ivDepositStatusWithdraw.setBackgroundResource(0);
                ivDepositStatusRed.setBackgroundResource(0);
                ivDepositStatusFan.setBackgroundResource(0);
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_hight));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusFan.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "S":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusFan.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                ivDepositStatusAll.setBackgroundResource(0);
                ivDepositStatusDeposit.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                ivDepositStatusWithdraw.setBackgroundResource(0);
                ivDepositStatusRed.setBackgroundResource(0);
                ivDepositStatusFan.setBackgroundResource(0);
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_hight));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusFan.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "T":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusFan.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                ivDepositStatusAll.setBackgroundResource(0);
                ivDepositStatusDeposit.setBackgroundResource(0);
                ivDepositStatusWithdraw.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                ivDepositStatusRed.setBackgroundResource(0);
                ivDepositStatusFan.setBackgroundResource(0);
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_hight));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusFan.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "Q":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuDepositStatusFan.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                ivDepositStatusAll.setBackgroundResource(0);
                ivDepositStatusDeposit.setBackgroundResource(0);
                ivDepositStatusWithdraw.setBackgroundResource(0);
                ivDepositStatusRed.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                ivDepositStatusFan.setBackgroundResource(0);
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_hight));
                tvDepositStatusFan.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "R":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusFan.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                ivDepositStatusAll.setBackgroundResource(0);
                ivDepositStatusDeposit.setBackgroundResource(0);
                ivDepositStatusWithdraw.setBackgroundResource(0);
                ivDepositStatusRed.setBackgroundResource(0);
                ivDepositStatusFan.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusFan.setTextColor(getResources().getColor(R.color.pop_hight));
                break;
        }

    }

    /**
     * 处理弹出显示内容、点击事件等逻辑
     * @param contentView
     */
    private void handleLogicPopMenuDepositStatus(View contentView){


        View.OnClickListener listener = new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                /*if(mCustomPopWindow!=null){
                    mCustomPopWindow.dissmiss();
                }*/
                String showContent = "";
                switch (v.getId()){
                    case R.id.popMenuDepositStatusAll:
                        type_status = "";
                        tvDepositRecordStatus.setText("全部");
                        showContent = "Out点击 Item菜单1";
                        break;
                    case R.id.popMenuDepositStatusDeposit:
                        type_status = "1";
                        tvDepositRecordStatus.setText("成功");
                        showContent = "Out 点击 Item菜单2";
                        break;
                    case R.id.popMenuDepositStatusWithDraw:
                        type_status = "-1";
                        tvDepositRecordStatus.setText("失败");
                        showContent = "Out 点击 Item菜单3";
                        break;
                    case R.id.popMenuDepositStatusRed:
                        type_status = "0,2";
                        tvDepositRecordStatus.setText("审核中");
                        showContent = "Out 点击 Item菜单3";
                        break;
                }
                GameLog.log("转出："+showContent);
                //showMessage(showContent);
                mCustomPopWindowOut.dissmiss();
            }
        };
        popMenuDepositStatusAll = (LinearLayout) contentView.findViewById(R.id.popMenuDepositStatusAll);
        popMenuDepositStatusDeposit = (LinearLayout) contentView.findViewById(R.id.popMenuDepositStatusDeposit);
        popMenuDepositStatusWithDraw = (LinearLayout) contentView.findViewById(R.id.popMenuDepositStatusWithDraw);
        popMenuDepositStatusRed = (LinearLayout) contentView.findViewById(R.id.popMenuDepositStatusRed);
        ivDepositStatusAll = (ImageView) contentView.findViewById(R.id.ivDepositStatusAll);
        ivDepositStatusDeposit = (ImageView) contentView.findViewById(R.id.ivDepositStatusDeposit);
        ivDepositStatusWithdraw = (ImageView) contentView.findViewById(R.id.ivDepositStatusWithDraw);
        ivDepositStatusRed = (ImageView) contentView.findViewById(R.id.ivDepositStatusRed);
        tvDepositStatusAll = (TextView) contentView.findViewById(R.id.tvDepositStatusAll);
        tvDepositStatusDeposit = (TextView) contentView.findViewById(R.id.tvDepositStatusDeposit);
        tvDepositStatusWithdraw = (TextView) contentView.findViewById(R.id.tvDepositStatusWithDraw);
        tvDepositStatusRed = (TextView) contentView.findViewById(R.id.tvDepositStatusRed);
        popMenuDepositStatusAll.setOnClickListener(listener);
        popMenuDepositStatusDeposit.setOnClickListener(listener);
        popMenuDepositStatusWithDraw.setOnClickListener(listener);
        popMenuDepositStatusRed.setOnClickListener(listener);
        // if(!Check.isNull(popMenuHGtv)&&!Check.isNull(popMenuCPtv)&&!Check.isNull(popMenuAGtv)){
        switch (type_status){
            case "":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                ivDepositStatusAll.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                ivDepositStatusDeposit.setBackgroundResource(0);
                ivDepositStatusWithdraw.setBackgroundResource(0);
                ivDepositStatusRed.setBackgroundResource(0);
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_hight));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "1":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                ivDepositStatusAll.setBackgroundResource(0);
                ivDepositStatusDeposit.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                ivDepositStatusWithdraw.setBackgroundResource(0);
                ivDepositStatusRed.setBackgroundResource(0);
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_hight));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "-1":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                ivDepositStatusAll.setBackgroundResource(0);
                ivDepositStatusDeposit.setBackgroundResource(0);
                ivDepositStatusWithdraw.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                ivDepositStatusRed.setBackgroundResource(0);
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_hight));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_normal));
                break;
            case "0,2":
                popMenuDepositStatusAll.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusDeposit.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusWithDraw.setBackgroundColor(getResources().getColor(R.color.pop_ll_normal));
                popMenuDepositStatusRed.setBackgroundColor(getResources().getColor(R.color.pop_ll_hight));
                ivDepositStatusAll.setBackgroundResource(0);
                ivDepositStatusDeposit.setBackgroundResource(0);
                ivDepositStatusWithdraw.setBackgroundResource(0);
                ivDepositStatusRed.setBackground(getResources().getDrawable(R.mipmap.pop_item));
                tvDepositStatusAll.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusDeposit.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusWithdraw.setTextColor(getResources().getColor(R.color.pop_normal));
                tvDepositStatusRed.setTextColor(getResources().getColor(R.color.pop_hight));
                break;
        }

    }

    private void showPopMenuDepositType(){
        View contentView = LayoutInflater.from(getContext()).inflate(R.layout.pop_deposit_type_menu,null);
        //处理popWindow 显示内容
        handleLogicPopMenuDepositType(contentView);
        //创建并显示popWindow
        /*if(mCustomPopWindow !=null){
            mCustomPopWindow.dissmiss();
        }else{*/
        mCustomPopWindowIn= new CustomPopWindow.PopupWindowBuilder(getContext())
                .setView(contentView)
                .enableBackgroundDark(true)
                .create()
                .showAsDropDown(tvDepositRecordType,0,0);
        //}
    }

    private void showPopMenuDepositStatus(){
        View contentView = LayoutInflater.from(getContext()).inflate(R.layout.pop_deposit_status_menu,null);
        //处理popWindow 显示内容
        handleLogicPopMenuDepositStatus(contentView);
        //创建并显示popWindow
        mCustomPopWindowOut= new CustomPopWindow.PopupWindowBuilder(getContext())
                .setView(contentView)
                .enableBackgroundDark(true)
                .create()
                .showAsDropDown(tvDepositRecordStatus,0,0);

    }


    @OnClick({R.id.depositeTTop, R.id.tvDepositRecordStatus,R.id.tvDepositRecordType,R.id.tvDepositRecordStartTime, R.id.tvDepositRecordEndTime, R.id.tvDepositRecordToday, R.id.tvDepositRecordLastDay, R.id.tvDepositRecordLastWeek, R.id.tvDepositRecordLastMonth, R.id.btnDepositRecordSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.depositeTTop:
                lvDepositRecord2.scrollToPosition(0);
                depositeTop.setVisibility(View.GONE);
                break;
            case R.id.tvDepositRecordStatus:
                showPopMenuDepositStatus();
                break;
            case R.id.tvDepositRecordType:
                showPopMenuDepositType();
                break;
            case R.id.tvDepositRecordStartTime:
                pvStartTime.show();
                break;
            case R.id.tvDepositRecordEndTime:
                pvEndTime.show();
                break;
            case R.id.tvDepositRecordToday:
                if(isNow){
                    data_start = DateHelper.getToday();
                    data_end = DateHelper.getToday();
                }else{
                    data_start = DateHelper.getYesterday();
                    data_end = DateHelper.getYesterday();
                }
                tvDepositRecordStartTime.setText(data_start);
                tvDepositRecordEndTime.setText(data_end);
                tvDepositRecordToday.setTextColor(getResources().getColor(R.color.title_text));
                tvDepositRecordLastDay.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastWeek.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastMonth.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordToday.setBackgroundResource(R.drawable.bg_btn_focus);
                tvDepositRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvDepositRecordLastDay:
                if(!isNow){
                    data_start = DateHelper.getYesterday2();
                    data_end = DateHelper.getYesterday2();
                }else{
                    data_start = DateHelper.getYesterday();
                    data_end = DateHelper.getYesterday();
                }
                tvDepositRecordStartTime.setText(data_start);
                tvDepositRecordEndTime.setText(data_end);
                tvDepositRecordToday.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastDay.setTextColor(getResources().getColor(R.color.title_text));
                tvDepositRecordLastWeek.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastMonth.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastDay.setBackgroundResource(R.drawable.bg_btn_focus);
                tvDepositRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvDepositRecordLastWeek:
                if(!isNow){
                    data_start = DateHelper.getLastWeek2();
                    data_end = DateHelper.getYesterday();
                }else{
                    data_start = DateHelper.getLastWeek();
                    data_end = DateHelper.getToday();
                }
                tvDepositRecordStartTime.setText(data_start);
                tvDepositRecordEndTime.setText(data_end);
                tvDepositRecordToday.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastDay.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastWeek.setTextColor(getResources().getColor(R.color.title_text));
                tvDepositRecordLastMonth.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_focus);
                tvDepositRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_no_focus);
                break;
            case R.id.tvDepositRecordLastMonth:
                if(!isNow){
                    data_start = DateHelper.getCurrentMonthDayBegin();
                    data_end = DateHelper.getYesterday();
                }else{
                    data_start = DateHelper.getCurrentMonthDayBegin();
                    data_end = DateHelper.getToday();
                }
                tvDepositRecordStartTime.setText(data_start);
                tvDepositRecordEndTime.setText(data_end);
                tvDepositRecordToday.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastDay.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastWeek.setTextColor(getResources().getColor(R.color.n_edittext_pwd));
                tvDepositRecordLastMonth.setTextColor(getResources().getColor(R.color.title_text));
                tvDepositRecordToday.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastDay.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastWeek.setBackgroundResource(R.drawable.bg_btn_no_focus);
                tvDepositRecordLastMonth.setBackgroundResource(R.drawable.bg_btn_focus);
                break;
            case R.id.btnDepositRecordSubmit:
                page =0;
                onSearchRecordList();
                break;
        }
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#FF0000'>" + sign+"</font>";
    }

    public class RecordListAdapter extends AutoSizeRVAdapter<RecordResult.RowsBean> {
        private Context context;

        public RecordListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }
        @Override
        protected void convert(final com.zhy.adapter.recyclerview.base.ViewHolder holder, RecordResult.RowsBean rowsBean, int position) {
            GameLog.log("订单的状态："+rowsBean.getChecked());
            String from = "",to= "";
            switch (rowsBean.getFrom()){
                case "sc":
                    from = "皇冠体育";
                    break;
                case "hg":
                    from = "体育余额";
                    break;
                case "cp":
                    from = "彩票平台";
                    break;
                case "ky":
                    from = "开元棋牌";
                    break;
                case "kl":
                    from = "快乐棋牌";
                    break;
                case "ag":
                    from = "AG平台";
                    break;
                case "vg":
                    from = "VG平台";
                    break;
                case "ly":
                    from = "乐游棋牌";
                    break;
                case "mg":
                    from = "MG电子";
                    break;
                case "avia":
                    from = "泛亚电竞";
                    break;
                case "gmcp":
                    from = "国民彩票";
                    break;
                case "og":
                    from = "OG视讯";
                    break;
                case "cq":
                    from = "CQ9电子";
                    break;
                case "mw":
                    from = "MW电子";
                    break;
                case "fg":
                    from = "FG电子";
                    break;
                case "bbin":
                    from = "BBIN视讯";
                    break;
                case "fire":
                    from = "雷火电竞";
                    break;
            }
            switch (rowsBean.getTo()){
                case "sc":
                    to = "皇冠体育";
                    break;
                case "hg":
                    to = "体育余额";
                    break;
                case "cp":
                    to = "彩票平台";
                    break;
                case "ky":
                    to = "开元棋牌";
                    break;
                case "kl":
                    to = "快乐棋牌";
                    break;
                case "ag":
                    to = "AG平台";
                    break;
                case "vg":
                    to = "VG平台";
                    break;
                case "ly":
                    to = "乐游棋牌";
                    break;
                case "mg":
                    to = "MG电子";
                    break;
                case "avia":
                    to = "泛亚电竞";
                    break;
                case "gmcp":
                    to = "国民彩票";
                    break;
                case "og":
                    to = "OG视讯";
                    break;
                case "cq":
                    to = "CQ9电子";
                    break;
                case "mw":
                    to = "MW电子";
                    break;
                case "fg":
                    to = "FG电子";
                    break;
                case "bbin":
                    to = "BBIN视讯";
                    break;
                case "fire":
                    to = "雷火电竞";
                    break;
            }
            if(rowsBean.getNotes().equals("APP幸运红包活动")||rowsBean.getNotes().contains("新春红包活动")){
                holder.setText(R.id.tvRecordName, "红包");
            }else{
                if (rowsBean.getType().equals("S")) {
                    holder.setText(R.id.tvRecordName, "存款");
                }else if(rowsBean.getType().equals("T")){
                    holder.setText(R.id.tvRecordName, "取款");
                }else if(rowsBean.getType().equals("Q")){
                    TextView textView = holder.getView(R.id.tvRecordName);
                    textView.setText(Html.fromHtml(from+onMarkRed("<br>转入<br>")+to));
                    //holder.setText(R.id.tvRecordName, );
                }else if(rowsBean.getType().equals("R")){
                    holder.setText(R.id.tvRecordName, "返水");
                }
            }

            holder.setText(R.id.tvRecordOrderCode, rowsBean.getOrder_code());
            holder.setText(R.id.tvRecordTime, rowsBean.getDate());

            switch (rowsBean.getChecked()){
                case "0": // 审核状态: 0 首次提交订单 2 二次审核  1成功 -1失败
                    holder.setText(R.id.tvRecordOrderStatus, "审核中");
                    holder.setTextColor(R.id.tvRecordOrderStatus,Color.GREEN);
                    break;
                case "1":
                    holder.setText(R.id.tvRecordOrderStatus, "成功");
                    holder.setTextColor(R.id.tvRecordOrderStatus, Color.RED);
                    break;
                case "2":
                    holder.setText(R.id.tvRecordOrderStatus, "二次审核");
                    holder.setTextColor(R.id.tvRecordOrderStatus,Color.GREEN);
                    break;
                case "-1":
                    holder.setText(R.id.tvRecordOrderStatus, "失败");
                    holder.setTextColor(R.id.tvRecordOrderStatus,Color.GREEN);
                    break;
            }
            holder.setText(R.id.tvRecordMoney,  rowsBean.getGold());
            holder.setOnLongClickListener(R.id.tvRecordOrderCode, new View.OnLongClickListener() {
                @Override
                public boolean onLongClick(View view) {
                    TextView textView = holder.getView(R.id.tvRecordOrderCode);
                    CLipHelper.copy(getContext(),textView.getText().toString());
                    showMessage("复制成功！");
                    return false;
                }
            });
           /* if (rowsBean.getType().equals("S")) {//交易金额
                holder.setText(R.id.tvRecordName, rowsBean.getBank());
                holder.setText(R.id.tvRecordOrderStatus, rowsBean.getChecked());
                holder.setText(R.id.tvRecordOrderCode, rowsBean.getOrder_code().substring(rowsBean.getOrder_code().length()-7));
                holder.setText(R.id.tvRecordTime, rowsBean.getDate());
                holder.setText(R.id.tvRecordMoney,  rowsBean.getGold());
            } else {//转账记录
                holder.setText(R.id.tvRecordName, "转出到" + rowsBean.getBank_Address());
                holder.setText(R.id.tvRecordOrderCode, rowsBean.getName());
                holder.setText(R.id.tvRecordTime, rowsBean.getDate());
                holder.setText(R.id.tvRecordMoney, "-" + rowsBean.getGold());
            }*/
        }
    }


    /*public class RecordListAdapter extends AutoSizeAdapter<RecordResult.RowsBean> {
        private Context context;

        public RecordListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final RecordResult.RowsBean rowsBean, final int position) {
            GameLog.log("订单的状态："+rowsBean.getChecked());
            if (rowsBean.getType().equals("S")) {
                holder.setText(R.id.tvRecordName, "存款");
            }else if(rowsBean.getType().equals("T")){
                holder.setText(R.id.tvRecordName, "取款");
            }else if(rowsBean.getType().equals("Q")){
                holder.setText(R.id.tvRecordName, "额度转换");
            }else if(rowsBean.getType().equals("R")){
                holder.setText(R.id.tvRecordName, "返水");
            }
            holder.setText(R.id.tvRecordOrderCode, rowsBean.getOrder_code());
            holder.setText(R.id.tvRecordTime, rowsBean.getDate());

            switch (rowsBean.getChecked()){
                case "0": // 审核状态: 0 首次提交订单 2 二次审核  1成功 -1失败
                    holder.setText(R.id.tvRecordOrderStatus, "审核中");
                    holder.setTextColor(R.id.tvRecordOrderStatus,Color.GREEN);
                    break;
                case "1":
                    holder.setText(R.id.tvRecordOrderStatus, "成功");
                    holder.setTextColor(R.id.tvRecordOrderStatus, Color.RED);
                    break;
                case "2":
                    holder.setText(R.id.tvRecordOrderStatus, "二次审核");
                    holder.setTextColor(R.id.tvRecordOrderStatus,Color.GREEN);
                    break;
                case "-1":
                    holder.setText(R.id.tvRecordOrderStatus, "失败");
                    holder.setTextColor(R.id.tvRecordOrderStatus,Color.GREEN);
                    break;
            }
            holder.setText(R.id.tvRecordMoney,  rowsBean.getGold());
        }
    }*/

}

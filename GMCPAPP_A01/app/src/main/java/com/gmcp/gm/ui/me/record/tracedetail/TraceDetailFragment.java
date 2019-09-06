package com.gmcp.gm.ui.me.record.tracedetail;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.Injections;
import com.gmcp.gm.R;
import com.gmcp.gm.common.base.BaseFragment;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.event.StartBrotherEvent;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.widget.NTitleBar;
import com.gmcp.gm.data.TraceDetailResult;
import com.gmcp.gm.ui.me.record.BetDropEvent;
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

public class TraceDetailFragment extends BaseFragment implements TraceDetailContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.itemTraceRecordserial_number)
    TextView itemTraceRecordserialNumber;
    @BindView(R.id.itemTraceDetailbought_at)
    TextView itemTraceDetailboughtAt;
    @BindView(R.id.itemTraceDetailUserName)
    TextView itemTraceDetailUserName;
    @BindView(R.id.itemTraceDetailtype)
    TextView itemTraceDetailtype;
    @BindView(R.id.itemTraceDetailway)
    TextView itemTraceDetailway;
    @BindView(R.id.itemTraceDetailformatted_coefficient)
    TextView itemTraceDetailformattedCoefficient;
    @BindView(R.id.itemTraceDetailstart_issue)
    TextView itemTraceDetailstartIssue;
    @BindView(R.id.itemTraceDetailtotal_issues)
    TextView itemTraceDetailtotalIssues;
    @BindView(R.id.itemTraceDetailfinished_issues)
    TextView itemTraceDetailfinishedIssues;
    @BindView(R.id.itemTraceDetailcanceled_issues)
    TextView itemTraceDetailcanceledIssues;
    @BindView(R.id.itemTraceDetailsingle_amount)
    TextView itemTraceDetailsingleAmount;
    @BindView(R.id.itemTraceDetailamount)
    TextView itemTraceDetailamount;
    @BindView(R.id.itemTraceDetailcanceled_amount_formatted)
    TextView itemTraceDetailcanceledAmountFormatted;
    @BindView(R.id.itemTraceDetailstatus)
    TextView itemTraceDetailstatus;
    @BindView(R.id.itemTraceDetailopen_then_stop)
    TextView itemTraceDetailopenThenStop;
    @BindView(R.id.itemTraceDetailformatted_stop_on_won)
    TextView itemTraceDetailformattedStopOnWon;
    @BindView(R.id.itemTraceDetailbet_number)
    TextView itemTraceDetailbetNumber;
    @BindView(R.id.traceDetailRView)
    RecyclerView traceDetailRView;
    private String typeArgs2, typeArgs3;
    TraceDetailContract.Presenter presenter;
    @BindView(R.id.traceDetailAddBack)
    NTitleBar traceDetailAddBack;
    List<TraceDetailResult.IssuesBean> issuesBeanList = new ArrayList<>();

    String trace_id;
    public static TraceDetailFragment newInstance(String deposit_mode, String money) {
        TraceDetailFragment betFragment = new TraceDetailFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_trace_detail;
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
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        traceDetailRView.setLayoutManager(linearLayoutManager);
        onRequsetData();
        traceDetailAddBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
    }


    //请求数据接口
    private void onRequsetData() {
        presenter.getTraceDetail(typeArgs2);
    }

    @Override
    public void getTraceDetailResult(TraceDetailResult traceDetailResult) {
        itemTraceRecordserialNumber.setText(traceDetailResult.getBasic().getSerial_number());
        itemTraceDetailboughtAt.setText(traceDetailResult.getBasic().getBought_at());
        itemTraceDetailUserName.setText( ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT));
        switch (Integer.parseInt(traceDetailResult.getBasic().getLottery_id())){
            case 49:
            case 48:
            case 50:
                itemTraceDetailtype.setText("幸运飞艇");
                break;
            case 1:
                itemTraceDetailtype.setText("重庆时时彩");
                break;
            case 9:
                itemTraceDetailtype.setText("广东11选5");
                break;
            case 10:
                itemTraceDetailtype.setText("北京PK10");
                break;
            case 13:
                itemTraceDetailtype.setText("官网分分彩");
                break;
            case 14:
                itemTraceDetailtype.setText("官网11选5");
                break;
            case 15:
                itemTraceDetailtype.setText("江苏快三");
                break;
            case 16:
                itemTraceDetailtype.setText("官网三分彩");
                break;
            case 17:
                itemTraceDetailtype.setText("官网快三分分彩");
                break;
            case 19:
                itemTraceDetailtype.setText("官网极速PK10");
                break;
            case 20:
                itemTraceDetailtype.setText("官网极速3D");
                break;
            case 28:
                itemTraceDetailtype.setText("官网五分彩");
                break;
            case 30:
                itemTraceDetailtype.setText("安徽快三");
                break;
            case 37:
                itemTraceDetailtype.setText("北京快乐8");
                break;
            case 40:
                itemTraceDetailtype.setText("11选5三分彩");
                break;
        }
        itemTraceDetailway.setText(traceDetailResult.getBasic().getWay());
        itemTraceDetailformattedCoefficient.setText(traceDetailResult.getBasic().getFormatted_coefficient());
        itemTraceDetailstartIssue.setText(traceDetailResult.getBasic().getStart_issue());
        itemTraceDetailtotalIssues.setText(traceDetailResult.getBasic().getTotal_issues()+"");
        itemTraceDetailfinishedIssues.setText(traceDetailResult.getBasic().getFinished_issues()+"");
        itemTraceDetailcanceledIssues.setText(traceDetailResult.getBasic().getCanceled_issues()+"");
        itemTraceDetailsingleAmount.setText(traceDetailResult.getBasic().getSingle_amount());
        itemTraceDetailamount.setText(traceDetailResult.getBasic().getAmount_formatted());
        itemTraceDetailcanceledAmountFormatted.setText(traceDetailResult.getBasic().getCanceled_amount_formatted());
        switch (traceDetailResult.getBasic().getStatus()) {
            case 0:
                itemTraceDetailstatus.setText("进行中");
                break;
            case 1:
                itemTraceDetailstatus.setText("已投注");
                break;
            case 2:
                itemTraceDetailstatus.setText("用户取消");
                break;
            case 3:
                itemTraceDetailstatus.setText("系统终止");
        }
        itemTraceDetailopenThenStop.setText(traceDetailResult.getBasic().getJump_Open_then_stop());
        itemTraceDetailformattedStopOnWon.setText(traceDetailResult.getBasic().getFormatted_stop_on_won());
        itemTraceDetailbetNumber.setText(traceDetailResult.getBasic().getBet_number());
        issuesBeanList = traceDetailResult.getIssues();
        trace_id = traceDetailResult.getBasic().getId();
        TraceDetailAdapter traceDetailAdapter = new TraceDetailAdapter(R.layout.item_trace_detail,issuesBeanList);
        traceDetailRView.setAdapter(traceDetailAdapter);
        traceDetailAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
            @Override
            public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                GameLog.log("当前用户注单的详情是 "+issuesBeanList.get(position).getStatus());
                if(issuesBeanList.get(position).getStatus()==0){
                    presenter.getCancelTraceReserve(trace_id,"["+issuesBeanList.get(position).getId()+"]");
                }else if(issuesBeanList.get(position).getStatus()==1){
                    EventBus.getDefault().post(new StartBrotherEvent(BetDetailFragment.newInstance(issuesBeanList.get(position).getProject_id()+"","")));
                }
            }
        });

    }

    @Override
    public void getCancelTraceReserveResult() {
        showMessage("此注单已取消追号");
        GameLog.log("=======取消追号成功================");
        onRequsetData();
    }

    @Subscribe
    public void onEventMain(BetDropEvent betDropEvent) {
        GameLog.log("=======追号详情界面EVENT================");
        onRequsetData();
    }

    class TraceDetailAdapter extends BaseQuickAdapter<TraceDetailResult.IssuesBean, BaseViewHolder> {

        public TraceDetailAdapter(int layoutResId, @Nullable List<TraceDetailResult.IssuesBean> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, TraceDetailResult.IssuesBean item) {

            switch (item.getStatus()) {
                case 0:
                    helper.setText(R.id.itemTraceDetailstatus, "进行中");
                    helper.setText(R.id.itemTraceDetailT,"取消本期追号");
                    break;
                case 1:
                    helper.setText(R.id.itemTraceDetailstatus, "已投注");
                    helper.setText(R.id.itemTraceDetailT,"详情>");
                    break;
                case 2:
                    helper.setText(R.id.itemTraceDetailstatus, "用户取消");
                    helper.setText(R.id.itemTraceDetailT,"");
                    break;
                case 3:
                    helper.setText(R.id.itemTraceDetailstatus, "系统终止");
                    helper.setText(R.id.itemTraceDetailT,"");
                    break;
            }
            helper.setText(R.id.itemTraceDetailissue, item.getIssue()).
                    setText(R.id.itemTraceDetailmultiple, item.getMultiple()+"").
            addOnClickListener(R.id.itemTraceDetailT);
        }
    }


    @Override
    public void setPresenter(TraceDetailContract.Presenter presenter) {
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

}

package com.cfcp.a01.ui.me.report;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
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
import com.cfcp.a01.common.utils.CalcHelper;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.PersonReportResult;
import com.cfcp.a01.data.TeamReportResult;

import java.text.SimpleDateFormat;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class TeamFragment extends BaseFragment implements TeamContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.reportTeamBack)
    NTitleBar reportTeamBack;
    @BindView(R.id.reportTeamStartTime)
    TextView reportTeamStartTime;
    @BindView(R.id.reportTeamEndTime)
    TextView reportTeamEndTime;
    @BindView(R.id.reportType)
    TextView reportType;
    @BindView(R.id.reportMethod)
    TextView reportMethod;
    @BindView(R.id.reportTeamBalance)
    TextView reportTeamBalance;
    @BindView(R.id.reportTeamRecharge)
    TextView reportTeamRecharge;
    @BindView(R.id.reportTeamWithdraw)
    TextView reportTeamWithdraw;
    @BindView(R.id.reportTeamBetBalance)
    TextView reportTeamBetBalance;
    @BindView(R.id.reportTeamWin)
    TextView reportTeamWin;
    @BindView(R.id.reportTeamWinBalance)
    TextView reportTeamWinBalance;
    @BindView(R.id.reportTeamBetBalanceCp)
    TextView reportTeamBetBalanceCp;
    @BindView(R.id.reportTeamWinCp)
    TextView reportTeamWinCp;
    @BindView(R.id.reportTeamWinBalanceCp)
    TextView reportTeamWinBalanceCp;
    @BindView(R.id.reportTeamBetBalanceXyCp)
    TextView reportTeamBetBalanceXyCp;
    @BindView(R.id.reportTeamWinXyCp)
    TextView reportTeamWinXyCp;
    @BindView(R.id.reportTeamWinBalanceXyCp)
    TextView reportTeamWinBalanceXyCp;
    @BindView(R.id.reportTeamBetBalanceKy)
    TextView reportTeamBetBalanceKy;
    @BindView(R.id.reportTeamWinKy)
    TextView reportTeamWinKy;
    @BindView(R.id.reportTeamWinBalanceKy)
    TextView reportTeamWinBalanceKy;
    @BindView(R.id.reportTeamKyWater)
    TextView reportTeamKyWater;
    @BindView(R.id.reportTeamBetBalanceLy)
    TextView reportTeamBetBalanceLy;
    @BindView(R.id.reportTeamWinLy)
    TextView reportTeamWinLy;
    @BindView(R.id.reportTeamWinBalanceLy)
    TextView reportTeamWinBalanceLy;
    @BindView(R.id.reportTeamLyWater)
    TextView reportTeamLyWater;
    @BindView(R.id.reportTeamBetBalanceDz)
    TextView reportTeamBetBalanceDz;
    @BindView(R.id.reportTeamWinDz)
    TextView reportTeamWinDz;
    @BindView(R.id.reportTeamWinBalanceDz)
    TextView reportTeamWinBalanceDz;
    @BindView(R.id.reportTeamBetBalanceZr)
    TextView reportTeamBetBalanceZr;
    @BindView(R.id.reportTeamWinZr)
    TextView reportTeamWinZr;
    @BindView(R.id.reportTeamWinBalanceZr)
    TextView reportTeamWinBalanceZr;
    @BindView(R.id.reportTeamBetBalanceBy)
    TextView reportTeamBetBalanceBy;
    @BindView(R.id.reportTeamWinBy)
    TextView reportTeamWinBy;
    @BindView(R.id.reportTeamWinBalanceBy)
    TextView reportTeamWinBalanceBy;
    @BindView(R.id.reportTeamCookerRefund)
    TextView reportTeamCookerRefund;
    @BindView(R.id.reportTeamAgencyRefund)
    TextView reportTeamAgencyRefund;
    @BindView(R.id.reportTeamBetRefund)
    TextView reportTeamBetRefund;
    @BindView(R.id.reportTeamActivityAward)
    TextView reportTeamActivityAward;
    private String typeArgs2, typeArgs3;
    TeamContract.Presenter presenter;

    TimePickerView pvStartTime;
    TimePickerView pvEndTime;
    //代表彩种ID
    private String lotteryId = "1";
    OptionsPickerView gtypeOptionsPicker;
    String startTime, endTime;

    public static TeamFragment newInstance(String deposit_mode, String type) {
        TeamFragment betFragment = new TeamFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, type);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_report_team;
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
        startTime = DateHelper.getToday();
        endTime = DateHelper.getTom();
        onRequsetData();
        if(typeArgs3.equals("person")){
            reportTeamBack.setTitle("个人报表");
            reportType.setText("个人详情");
            reportMethod.setText("净盈利=中奖金额+活动奖励-投注金额");
        }
        reportTeamStartTime.setText(startTime);
        reportTeamEndTime.setText(endTime);
        reportTeamBack.setBackListener(new View.OnClickListener() {
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
                onRequsetData();
                reportTeamStartTime.setText(startTime);
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
                onRequsetData();
                reportTeamEndTime.setText(endTime);
            }
        })
                .setType(new boolean[]{true, true, true, false, false, false})// 默认全部显示
                //  .setLabel("年","月","日","时","分","秒")//默认设置为年月日时分秒
                .build();
    }


    //请求数据接口
    private void onRequsetData() {
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this, null);
        }
        if(typeArgs3.equals("person")){
            presenter.getPersonReport(startTime,endTime);
        }else{
            presenter.getTeamReport(typeArgs2,startTime,endTime);
        }
    }

    @Override
    public void getTeamReportResult(TeamReportResult teamReportResult) {
        GameLog.log("团队区间报表 成功");
        //reportTeamBalance.setText(GameShipHelper.formatMoney(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BALANCE)));
        reportTeamBalance.setText(teamReportResult.getAvailable());
        reportTeamRecharge.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_deposit()));
        reportTeamWithdraw.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_withdrawal()));
        reportTeamBetBalance.setText(GameShipHelper.formatMoney(teamReportResult.getBet_total_turnover()));
        //净盈利=中奖金额+打和返款+投注返点+代理返点+活动奖励-投注金额
        /*Double dataMoney = CalcHelper.sub(String.valueOf(CalcHelper.add(String.valueOf(CalcHelper.add(String.valueOf(CalcHelper.add(teamReportResult.getTotal_commission(),teamReportResult.getTotal_profit())),
                String.valueOf(CalcHelper.add(teamReportResult.getTotal_lose_commission(),teamReportResult.getTotal_prize())))),teamReportResult.getTotal_bonus())),teamReportResult.getTotal_turnover());
        */
        reportTeamWin.setText(GameShipHelper.formatMoney(teamReportResult.getBet_total_profit()));
        reportTeamWinBalance.setText(GameShipHelper.formatMoney(teamReportResult.getBet_total_prize()));
        reportTeamCookerRefund.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_commission()));
        reportTeamAgencyRefund.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_commission()));
        reportTeamBetRefund.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_lose_commission()));
        reportTeamActivityAward.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_bonus()));

        //官方彩票
        reportTeamBetBalanceCp.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_turnover()));
        reportTeamWinBalanceCp.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_prize()));
        reportTeamWinCp.setText(GameShipHelper.formatMoney(teamReportResult.getTotal_profit()));

        //信用彩票
        reportTeamBetBalanceXyCp.setText(GameShipHelper.formatMoney(teamReportResult.getXy_total_turnover()));
        reportTeamWinBalanceXyCp.setText(GameShipHelper.formatMoney(teamReportResult.getXy_total_prize()));
        reportTeamWinXyCp.setText(GameShipHelper.formatMoney(teamReportResult.getXy_total_profit()));

        //开元
        reportTeamBetBalanceKy.setText(GameShipHelper.formatMoney(teamReportResult.getKy_total_turnover()));
        reportTeamWinBalanceKy.setText(GameShipHelper.formatMoney(teamReportResult.getKy_total_prize()));
        reportTeamWinKy.setText(GameShipHelper.formatMoney(teamReportResult.getKy_total_profit()));
        reportTeamKyWater.setText(GameShipHelper.formatMoney(teamReportResult.getKy_total_revenue()));

        //乐游
        reportTeamBetBalanceLy.setText(GameShipHelper.formatMoney(teamReportResult.getLy_total_turnover()));
        reportTeamWinBalanceLy.setText(GameShipHelper.formatMoney(teamReportResult.getLy_total_prize()));
        reportTeamWinLy.setText(GameShipHelper.formatMoney(teamReportResult.getLy_total_profit()));
        reportTeamLyWater.setText(GameShipHelper.formatMoney(teamReportResult.getLy_total_revenue()));

        //电子
        reportTeamBetBalanceDz.setText(GameShipHelper.formatMoney(teamReportResult.getAggame_total_turnover()));
        reportTeamWinBalanceDz.setText(GameShipHelper.formatMoney(teamReportResult.getAggame_total_profit()));
        reportTeamWinDz.setText(GameShipHelper.formatMoney(teamReportResult.getAglive_total_profit()));

        //真人
        reportTeamBetBalanceZr.setText(GameShipHelper.formatMoney(teamReportResult.getAglive_total_turnover()));
        reportTeamWinBalanceZr.setText(GameShipHelper.formatMoney(teamReportResult.getAglive_total_profit()));
        reportTeamWinZr.setText(GameShipHelper.formatMoney(teamReportResult.getAglive_total_profit()));

        //捕鱼
        reportTeamBetBalanceBy.setText(GameShipHelper.formatMoney(teamReportResult.getAgby_total_turnover()));
        reportTeamWinBalanceBy.setText(GameShipHelper.formatMoney(teamReportResult.getAgby_total_prize()));
        reportTeamWinBy.setText(GameShipHelper.formatMoney(teamReportResult.getAgby_total_profit()));

    }

    @Override
    public void setPresenter(TeamContract.Presenter presenter) {
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

    @OnClick({R.id.reportTeamStartTime, R.id.reportTeamEndTime})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.reportTeamStartTime:
                pvStartTime.show();
                break;
            case R.id.reportTeamEndTime:
                pvEndTime.show();
                break;
        }
    }

}

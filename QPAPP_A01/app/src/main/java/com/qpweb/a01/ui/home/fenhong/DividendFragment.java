package com.qpweb.a01.ui.home.fenhong;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.TouziResult;
import com.qpweb.a01.data.TouziYestodayResult;
import com.qpweb.a01.ui.home.RefreshMoneyEvent;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.CalcHelper;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.DoubleClickHelper;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.TimeHelper;

import org.greenrobot.eventbus.EventBus;

import java.math.RoundingMode;
import java.text.DecimalFormat;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class DividendFragment extends BaseDialogFragment implements DividendContract.View {


    DividendContract.Presenter presenter;
    @BindView(R.id.dTView1)
    TextView dTView1;
    @BindView(R.id.dTView2)
    TextView dTView2;
    @BindView(R.id.dTView3)
    TextView dTView3;
    @BindView(R.id.dRView1)
    RecyclerView dRView1;
    @BindView(R.id.dSignTime)
    TextView dSignTime;
    @BindView(R.id.dividendOpenLottery)
    TextView dividendOpenLottery;
    @BindView(R.id.dSignPerson)
    TextView dSignPerson;
    @BindView(R.id.dSignTimeLeft)
    TextView dSignTimeLeft;
    @BindView(R.id.dLastDayBetMoneyLeft)
    TextView dLastDayBetMoneyLeft;
    @BindView(R.id.dLastDayBetLeft)
    TextView dLastDayBetLeft;
    @BindView(R.id.dLastDayBetMoney)
    TextView dLastDayBetMoney;
    @BindView(R.id.dLastDayBetPerson)
    TextView dLastDayBetPerson;
    @BindView(R.id.dLastDayBet)
    TextView dLastDayBet;
    @BindView(R.id.dSingToday)
    TextView dSingToday;
    @BindView(R.id.dTZSubMit)
    TextView dTZSubMit;
    @BindView(R.id.dTZLay)
    LinearLayout dTZLay;
    @BindView(R.id.dTZEditView)
    EditText dTZEditView;
    @BindView(R.id.dTZ1)
    ImageView dTZ1;
    @BindView(R.id.dTZ10)
    ImageView dTZ2;
    @BindView(R.id.dTZ50)
    ImageView dTZ50;

    @BindView(R.id.dSingTodayTView)
    TextView dSingTodayTView;
    @BindView(R.id.dividendLay1)
    LinearLayout dividendLay1;
    @BindView(R.id.dividendLay2)
    LinearLayout dividendLay2;
    @BindView(R.id.dRView3)
    RecyclerView dRView3;
    @BindView(R.id.dividendLay3)
    LinearLayout dividendLay3;
    @BindView(R.id.dividendClose)
    ImageView dividendClose;
    private OpenLotteryTimer openLotteryTimer;
    private SignLotteryTimer signLotteryTimer;
    private String currentData;
    private long dCurrentTime;

    public static DividendFragment newInstance() {
        Bundle bundle = new Bundle();
        DividendFragment dividendFragment = new DividendFragment();
        dividendFragment.setArguments(bundle);
        Injections.inject(dividendFragment, null);
        return dividendFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.dividend_fragment;
    }

    class OpenLotteryTimer extends CountDownTimer {

        public OpenLotteryTimer(long millisInFuture, long countDownInterval) {
            super(millisInFuture, countDownInterval);
        }


        public void onFinish() {
            dividendOpenLottery.setText("0");
        }

        public void onTick(long millisUntilFinished) {
            //dividendOpenLottery.setText(currentData+" "+TimeHelper.getTimeString(millisUntilFinished / 1000));
            dCurrentTime +=1000;
            /*GameLog.log("当前毫秒数 "+dCurrentTime );
            GameLog.log("当转换  "+TimeHelper.convertToDetailTime(dCurrentTime));*/
            dividendOpenLottery.setText(TimeHelper.convertToDetailTime(dCurrentTime));
        }
    }

    class SignLotteryTimer extends CountDownTimer {

        public SignLotteryTimer(long millisInFuture, long countDownInterval) {
            super(millisInFuture, countDownInterval);
        }


        public void onFinish() {
            dSignTime.setText("0");
            presenter.postTouziYestodayList("","");
        }

        public void onTick(long millisUntilFinished) {
            dSignTime.setText(TimeHelper.getTimeString(millisUntilFinished / 1000));
        }

    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {

        }
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT_ALIAS);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);

        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        dRView3.setLayoutManager(linearLayoutManager);
        LinearLayoutManager bangDanLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        dRView1.setLayoutManager(bangDanLayoutManager);
        presenter.postTouziYestodayList("","");
    }


    private String getString2Pt(String money){
        DecimalFormat df = new DecimalFormat("0.00");
        //DecimalFormat df = new DecimalFormat("#0.00");//与上一行代码的区别是：#表示如果不存在则显示为空，0表示如果没有则该位补0.
        //DecimalFormat df = new DecimalFormat("#,###.00"); //将数据转换成以3位逗号隔开的字符串，并保留两位小数
        df.setRoundingMode(RoundingMode.FLOOR);//不四舍五入
        GameLog.log("需要格式化的值是 "+money);
        return df.format(Double.parseDouble(money));
    }

    class TouziRecordAdapter extends BaseQuickAdapter<TouziResult, BaseViewHolder> {
        public TouziRecordAdapter( int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final TouziResult data) {
            holder.setText(R.id.itemTouziRecordTime,data.getAdd_time());
            holder.setText(R.id.itemTouziRecordNickName,data.getNickname());
            holder.setText(R.id.itemTouziRecordMoney,getString2Pt(data.getGold()));
            holder.setText(R.id.itemTouziRecordBack,getString2Pt(data.getPay_back_gold()));
            holder.setText(R.id.itemTouziRecordBackN,data.getPay_back_rate());
            holder.setText(R.id.itemTouziRecordState,data.getChecked().equals("0")?"未分红":"已分红");
        }
    }

    class BangDanRecordAdapter extends BaseQuickAdapter<TouziYestodayResult.YestodayListBean, BaseViewHolder> {
        public BangDanRecordAdapter( int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final TouziYestodayResult.YestodayListBean data) {
            holder.setText(R.id.itemBangDanName,data.getNickname());
            holder.setText(R.id.itemBangDanGold,getString2Pt(data.getGold()));
            holder.setText(R.id.itemBangDanBackGold,getString2Pt(data.getPay_back_gold()));
        }
    }


    private void onCheckAndLoginPwdSubmit() {
        /*String pwd1 = setLoginPwd1.getText().toString().trim();
        String pwd2 = setLoginPwd2.getText().toString().trim();
        String pwd3 = setLoginPwd3.getText().toString().trim();
        if (Check.isEmpty(pwd1)) {
            showMessage("请输入原登录密码");
            return;
        }
        if (Check.isEmpty(pwd2)) {
            showMessage("请输入新登录密码");
            return;
        }
        if (Check.isEmpty(pwd3)) {
            showMessage("请输入确认密码");
            return;
        }
        if (!pwd3.equals(pwd2)) {
            showMessage("新登录密码和确认密码不一致！");
            return;
        }
        presenter.postChangLoginPwd("", "login", pwd1, pwd2, pwd3);*/
    }

    private void onCheckAndWithdrawPwdSubmit() {
        /*String realName = setWithdrawName.getText().toString().trim();
        String pwd1 = setWithdrawPwd1.getText().toString().trim();
        String pwd2 = setWithdrawPwd2.getText().toString().trim();
        if (Check.isEmpty(realName)) {
            showMessage("请输入用户姓名");
            return;
        }
        if (Check.isEmpty(pwd1)) {
            showMessage("请输入新取款密码");
            return;
        }
        if (Check.isEmpty(pwd2)) {
            showMessage("请输入取款密码确认");
            return;
        }
        if (!pwd1.equals(pwd2)) {
            showMessage("新取款密码和确认密码不一致！");
            return;
        }
        presenter.postChangeWithDrawPwd("", "safe", realName, pwd1, pwd2);*/
    }

    @Override
    public void postTouziYestodayListResult(TouziYestodayResult touziYestodayResult) {
        /*touziYestodayResult.getYestoday_list().addAll(touziYestodayResult.getYestoday_list());
        touziYestodayResult.getYestoday_list().addAll(touziYestodayResult.getYestoday_list());
        touziYestodayResult.getYestoday_list().addAll(touziYestodayResult.getYestoday_list());
        touziYestodayResult.getYestoday_list().addAll(touziYestodayResult.getYestoday_list());*/
        dRView1.setAdapter(new BangDanRecordAdapter(R.layout.item_bangdan_record,touziYestodayResult.getYestoday_list()));
        if(touziYestodayResult.getDay_part()==1){//【1 签到时间】 【2 投资金额正在分配】 【3 投资时间】

            dSignTimeLeft.setText("签到倒计时");
            dSignPerson.setText(touziYestodayResult.getSignin_people_number()+"人已签到");

            dLastDayBetMoneyLeft.setText("昨日投资额");
            dLastDayBetMoney.setText(touziYestodayResult.getYestoday_touzi_gold());
            dLastDayBetPerson.setText(touziYestodayResult.getYestoday_touzi_count()+"人已投资");

            dLastDayBetLeft.setText("昨日已投资");
            dLastDayBet.setText(touziYestodayResult.getYestoday_my_touzi_gold());

            //签到倒计时
            signLotteryTimer = new SignLotteryTimer(Long.valueOf(touziYestodayResult.getSignin_count_down())*1000, 1000);
            signLotteryTimer.start();

        }else if(touziYestodayResult.getDay_part()==2){
            dSignTimeLeft.setText("投资金额分配中");
            dSignPerson.setText(touziYestodayResult.getSignin_people_number()+"人已签到");

            dLastDayBetMoneyLeft.setText("昨日投资额");
            dLastDayBetMoney.setText(touziYestodayResult.getYestoday_touzi_gold());
            dLastDayBetPerson.setText(touziYestodayResult.getYestoday_touzi_count()+"人已投资");

            dLastDayBetLeft.setText("昨日已投资");
            dLastDayBet.setText(touziYestodayResult.getYestoday_my_touzi_gold());

            //签到倒计时
            signLotteryTimer = new SignLotteryTimer(Long.valueOf(touziYestodayResult.getSignin_count_down())*1000, 1000);
            signLotteryTimer.start();

        }else if(touziYestodayResult.getDay_part()==3){
            dTZLay.setVisibility(View.VISIBLE);
            dSingToday.setVisibility(View.GONE);
            dSingTodayTView.setVisibility(View.GONE);

            dSignTimeLeft.setText("投资倒计时");
            dSignPerson.setVisibility(View.GONE);

            dLastDayBetMoneyLeft.setText("今日投资额");
            dLastDayBetMoney.setText(touziYestodayResult.getToday_touzi_gold());
            dLastDayBetPerson.setText(touziYestodayResult.getToday_touzi_count()+"人已投资");

            dLastDayBetLeft.setText("今日已投资");
            dLastDayBet.setText(touziYestodayResult.getToday_my_touzi_gold());

            //投资倒计时
            signLotteryTimer = new SignLotteryTimer(Long.valueOf(touziYestodayResult.getTouzi_count_down())*1000, 1000);
            signLotteryTimer.start();
        }else{

        }
        //GameLog.log("手机当前时间 ： "+dCurrentTime+" 转换 "+TimeHelper.convertToDetailTime(dCurrentTime));
        /*String[] dataTime = touziYestodayResult.getTouzi_count_down().split(":");
        long dataLong = Long.valueOf(dataTime[0].substring(0,1).equals("0")?dataTime[0].substring(1):dataTime[0])*3600+
                Long.valueOf(dataTime[1].substring(0,1).equals("0")?dataTime[1].substring(1):dataTime[1])*60+
                Long.valueOf(dataTime[2].substring(0,1).equals("0")?dataTime[2].substring(1):dataTime[2]);
        GameLog.log("时间戳 2"+dataLong);*/
        //currentData = touziYestodayResult.getCurrent_time().split(" ")[0];
        //今日时间
        dividendOpenLottery.setText(touziYestodayResult.getCurrent_time());
        dCurrentTime = TimeHelper.getStringToDate(touziYestodayResult.getCurrent_time());
        openLotteryTimer = new OpenLotteryTimer(dCurrentTime, 1000);
        openLotteryTimer.start();
    }

    @Override
    public void postTouziRecordResult(List<TouziResult> touziResult) {

        //添加适配器以及展示
        dRView3.setAdapter(new TouziRecordAdapter(R.layout.item_touzi_record,touziResult));
    }

    @Override
    public void postTouziResult() {
        EventBus.getDefault().post(new RefreshMoneyEvent());
    }

    @Override
    public void setPresenter(DividendContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @OnClick({R.id.dTView1, R.id.dTView2, R.id.dTView3, R.id.dSingToday,R.id.dTZSubMit, R.id.dTZ1,R.id.dTZ10,R.id.dTZ50,R.id.dividendClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.dTView1:
                dividendLay1.setVisibility(View.VISIBLE);
                dividendLay2.setVisibility(View.GONE);
                dividendLay3.setVisibility(View.GONE);
                /*dTView1.setBackground(getResources().getDrawable(R.mipmap.d_btn_click));
                dTView2.setBackground(getResources().getDrawable(R.mipmap.d_btn_unclick));
                dTView3.setBackground(getResources().getDrawable(R.mipmap.d_btn_unclick));*/
                dTView1.setBackgroundResource(R.mipmap.d_btn_click);
                dTView2.setBackgroundResource(R.mipmap.d_btn_unclick);
                dTView3.setBackgroundResource(R.mipmap.d_btn_unclick);
                dTView1.setTextColor(getResources().getColor(R.color.d_btn_click));
                dTView2.setTextColor(getResources().getColor(R.color.d_btn_unclick));
                dTView3.setTextColor(getResources().getColor(R.color.d_btn_unclick));

                break;
            case R.id.dTView2:
                dividendLay1.setVisibility(View.GONE);
                dividendLay2.setVisibility(View.VISIBLE);
                dividendLay3.setVisibility(View.GONE);
                dTView1.setBackground(getResources().getDrawable(R.mipmap.d_btn_unclick));
                dTView2.setBackground(getResources().getDrawable(R.mipmap.d_btn_click));
                dTView3.setBackground(getResources().getDrawable(R.mipmap.d_btn_unclick));
                dTView1.setTextColor(getResources().getColor(R.color.d_btn_unclick));
                dTView2.setTextColor(getResources().getColor(R.color.d_btn_click));
                dTView3.setTextColor(getResources().getColor(R.color.d_btn_unclick));
                break;
            case R.id.dTView3:
                dividendLay1.setVisibility(View.GONE);
                dividendLay2.setVisibility(View.GONE);
                dividendLay3.setVisibility(View.VISIBLE);
                dTView1.setBackground(getResources().getDrawable(R.mipmap.d_btn_unclick));
                dTView2.setBackground(getResources().getDrawable(R.mipmap.d_btn_unclick));
                dTView3.setBackground(getResources().getDrawable(R.mipmap.d_btn_click));
                dTView1.setTextColor(getResources().getColor(R.color.d_btn_unclick));
                dTView2.setTextColor(getResources().getColor(R.color.d_btn_unclick));
                dTView3.setTextColor(getResources().getColor(R.color.d_btn_click));
                presenter.postTouziRecord("","");
                break;
            case R.id.dSingToday:
                DoubleClickHelper.getNewInstance().disabledView(dSingToday);
                presenter.postTouziSign("","");
                break;
            case  R.id.dTZ1:
                onTz1();
                break;
            case R.id.dTZ10:
                onTz10();
                break;
            case R.id.dTZ50:
                onTz50();
                break;
            case R.id.dTZSubMit:
                DoubleClickHelper.getNewInstance().disabledView(dTZSubMit);
                onTzSubmit();
                break;

            case R.id.dividendClose:
                hide();
                break;
        }
    }

    private void onTzSubmit() {
        String tz1 = dTZEditView.getText().toString().trim();
        presenter.postTouzi("",tz1);

    }

    private void onTz1() {
        String tz1 = dTZEditView.getText().toString().trim();
        if(Check.isEmpty(tz1)){
            tz1 ="1";
        }
        String moeny = CalcHelper.multiplyString(tz1,"10");
        dTZEditView.setText(moeny+"");
    }

    private void onTz10() {
        String tz1 = dTZEditView.getText().toString().trim();
        if(Check.isEmpty(tz1)){
            tz1 ="1";
        }
        String moeny = CalcHelper.multiplyString(tz1,"20");
        dTZEditView.setText(moeny+"");
    }

    private void onTz50() {
        String tz1 = dTZEditView.getText().toString().trim();
        if(Check.isEmpty(tz1)){
            tz1 ="1";
        }
        String moeny = CalcHelper.multiplyString(tz1,"50");
        dTZEditView.setText(moeny+"");
    }

    @Override
    public void onDestroyView() {
        GameLog.log("关闭当前界面； "+ openLotteryTimer);
        super.onDestroyView();
        if (openLotteryTimer != null) {
            openLotteryTimer.cancel();
            openLotteryTimer = null;
        }
        if (signLotteryTimer != null) {
            signLotteryTimer.cancel();
            signLotteryTimer = null;
        }
        GameLog.log("关闭当前界面； "+ openLotteryTimer);
    }
}

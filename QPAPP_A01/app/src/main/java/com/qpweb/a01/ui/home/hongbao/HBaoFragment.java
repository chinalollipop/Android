package com.qpweb.a01.ui.home.hongbao;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.TouziYestodayResult;
import com.qpweb.a01.data.ValidResult;
import com.qpweb.a01.ui.home.RefreshMoneyEvent;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.DoubleClickHelper;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.TimeHelper;
import com.qpweb.a01.widget.MarqueeTextView;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.math.RoundingMode;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class HBaoFragment extends BaseDialogFragment implements HBaoContract.View {


    HBaoContract.Presenter presenter;
    @BindView(R.id.hBaoMTView)
    MarqueeTextView hBaoMTView;
    @BindView(R.id.hBaoOpenTime)
    TextView hBaoOpenTime;
    @BindView(R.id.hBaoOpen)
    ImageView hBaoOpen;
    @BindView(R.id.hBaoRView)
    RecyclerView hBaoRView;
    @BindView(R.id.hBaoClose)
    ImageView hBaoClose;

    private OpenLotteryTimer openLotteryTimer;

    public static HBaoFragment newInstance() {
        Bundle bundle = new Bundle();
        HBaoFragment loginFragment = new HBaoFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.hbao_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {

        }

    }

    @Override
    public void postValidResult(ValidResult validResult) {

    }

    @Override
    public void postLuckEnvelopeResult(RedPacketResult redPacketResult) {
        showMessage("您已领取到"+redPacketResult+" 元红包");
        presenter.postLuckEnvelopeRecord("","");
        EventBus.getDefault().post(new RefreshMoneyEvent());
    }

    @Override
    public void postLuckEnvelopeErrorResult(String message) {
        showMessage(message);
        HBaoNoticeFragment.newInstance().show(getFragmentManager());
    }

    @Override
    public void postLuckEnvelopeRecordResult(List<ValidResult> redPacketResult) {
        GameLog.log("红包记录的 大小 "+redPacketResult.size());
        if(redPacketResult.size()>0){
            hBaoRView.setAdapter(new HBaoRecordAdapter(R.layout.item_hongbao_record,redPacketResult));
        }
    }

    class OpenLotteryTimer extends CountDownTimer {

        public OpenLotteryTimer(long millisInFuture, long countDownInterval) {
            super(millisInFuture, countDownInterval);
        }


        public void onFinish() {
            hBaoOpenTime.setText("0");
        }

        public void onTick(long millisUntilFinished) {
            hBaoOpenTime.setText(TimeHelper.getTimeString(millisUntilFinished / 1000));
        }

    }


    private String getString2Pt(String money){
        DecimalFormat df = new DecimalFormat("0.00");
        //DecimalFormat df = new DecimalFormat("#0.00");//与上一行代码的区别是：#表示如果不存在则显示为空，0表示如果没有则该位补0.
        //DecimalFormat df = new DecimalFormat("#,###.00"); //将数据转换成以3位逗号隔开的字符串，并保留两位小数
        df.setRoundingMode(RoundingMode.FLOOR);//不四舍五入
        return df.format(Double.parseDouble(money));
    }

    class HBaoRecordAdapter extends BaseQuickAdapter<ValidResult, BaseViewHolder> {
        public HBaoRecordAdapter( int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final ValidResult data) {
            holder.setText(R.id.itemHongBaoGold,(data.getLuckyRedEnvelopeGold())+"元");
            holder.setText(R.id.itemHongBaoBackGold,(data.getValid_money())+"元");
        }
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        LinearLayoutManager bangDanLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        hBaoRView.setLayoutManager(bangDanLayoutManager);
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT_ALIAS);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
        List<String> stringList = new ArrayList<String>();
        stringList.add("阿果,你妈妈叫你回家吃饭咯！");
        stringList.add("阿莱，你小子有中奖了！1000元现金哦！");
        stringList.add("阿果，你小子也中奖了！10000元现金哦!");
        hBaoMTView.setContentList(stringList);
        GameLog.log("用户的真实姓名 " + userName);
        presenter.postLuckEnvelopeRecord("","");
        //创建倒计时类
        openLotteryTimer = new OpenLotteryTimer(600000, 1000);
        openLotteryTimer.start();
    }


    private void onCheckAndLoginPwdSubmit() {
        /*String pwd1 = setLoginPwd1.getText().toString().trim();
        String pwd2 = setLoginPwd2.getText().toString().trim();
        String pwd3 = setLoginPwd3.getText().toString().trim();
        if(Check.isEmpty(pwd1)){
            showMessage("请输入原登录密码");
            return;
        }
        if(Check.isEmpty(pwd2)){
            showMessage("请输入新登录密码");
            return;
        }
        if(Check.isEmpty(pwd3)){
            showMessage("请输入确认密码");
            return;
        }
        if(!pwd3.equals(pwd2)){
            showMessage("新登录密码和确认密码不一致！");
            return;
        }
        presenter.postChangLoginPwd("","login",pwd1,pwd2,pwd3);*/
    }

    private void onCheckAndWithdrawPwdSubmit() {
        /*String realName = setWithdrawName.getText().toString().trim();
        String pwd1 = setWithdrawPwd1.getText().toString().trim();
        String pwd2 = setWithdrawPwd2.getText().toString().trim();
        if(Check.isEmpty(realName)){
            showMessage("请输入用户姓名");
            return;
        }
        if(Check.isEmpty(pwd1)){
            showMessage("请输入新取款密码");
            return;
        }
        if(Check.isEmpty(pwd2)){
            showMessage("请输入取款密码确认");
            return;
        }
        if(!pwd1.equals(pwd2)){
            showMessage("新取款密码和确认密码不一致！");
            return;
        }
        presenter.postChangeWithDrawPwd("","safe",realName,pwd1,pwd2);*/
    }


    @Override
    public void onDestroyView() {
        EventBus.getDefault().unregister(this);
        GameLog.log("关闭当前界面； "+ openLotteryTimer);
        super.onDestroyView();
        if (openLotteryTimer != null) {
            openLotteryTimer.cancel();
            openLotteryTimer = null;
        }
        GameLog.log("关闭当前界面； "+ openLotteryTimer);
    }


    @Subscribe
    public void onMainEvent(GoPlayEvent goPlayEvent){
        hide();
    }

    @Override
    public void setPresenter(HBaoContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @OnClick({R.id.hBaoOpen, R.id.hBaoClose})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.hBaoOpen:
                //showMessage("当前没有适合你的红包！");
                DoubleClickHelper.getNewInstance().disabledView(hBaoOpen);
                presenter.postLuckEnvelope("","");
                break;
            case R.id.hBaoClose:
                hide();
                break;
        }
    }
}

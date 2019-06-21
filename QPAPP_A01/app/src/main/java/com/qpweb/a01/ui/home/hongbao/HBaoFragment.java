package com.qpweb.a01.ui.home.hongbao;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.ChangeAccountEvent;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.TimeHelper;
import com.qpweb.a01.widget.MarqueeTextView;

import org.greenrobot.eventbus.EventBus;

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

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT_ALIAS);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
        List<String> stringList = new ArrayList<String>();
        stringList.add("阿果,你妈妈叫你回家吃饭咯！");
        stringList.add("阿莱，你小子有中奖了！1000元现金哦！");
        stringList.add("阿果，你小子也中奖了！10000元现金哦!");
        hBaoMTView.setContentList(stringList);
        GameLog.log("用户的真实姓名 " + userName);
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
        GameLog.log("关闭当前界面； "+ openLotteryTimer);
        super.onDestroyView();
        if (openLotteryTimer != null) {
            openLotteryTimer.cancel();
            openLotteryTimer = null;
        }
        GameLog.log("关闭当前界面； "+ openLotteryTimer);
    }

    @Override
    public void postChangLoginPwdResult(RedPacketResult redPacketResult) {
        EventBus.getDefault().post(new ChangeAccountEvent());
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
                showMessage("当前没有适合你的红包！");
                break;
            case R.id.hBaoClose:
                hide();
                break;
        }
    }
}

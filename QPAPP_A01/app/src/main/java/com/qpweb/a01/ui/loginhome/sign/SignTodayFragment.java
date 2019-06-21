package com.qpweb.a01.ui.loginhome.sign;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.dingmouren.fallingview.FallingView;
import com.qpweb.a01.Injections;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.LuckGiftResult;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.SignTodayResult;
import com.qpweb.a01.ui.home.RefreshMoneyEvent;
import com.qpweb.a01.ui.loginhome.sign.anim.Swing;
import com.qpweb.a01.ui.loginhome.sign.anim.ZoomOutRightExit;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.CalcHelper;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.widget.FallingViewO;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.math.RoundingMode;
import java.text.DecimalFormat;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

import static cn.jpush.android.api.b.v;

public class SignTodayFragment extends BaseDialogFragment implements SignTodayContract.View{

    @BindView(R.id.loginClose)
    ImageView loginClose;
    @BindView(R.id.imageOpen1)
    ImageView imageOpen1;
    @BindView(R.id.imageOpen2)
    ImageView imageOpen2;
    @BindView(R.id.imageOpen3)
    ImageView imageOpen3;
    @BindView(R.id.imageOpen4)
    ImageView imageOpen4;
    @BindView(R.id.imageOpen5)
    ImageView imageOpen5;
    @BindView(R.id.imageOpen6)
    ImageView imageOpen6;
    @BindView(R.id.imageOpen7)
    ImageView imageOpen7;

    @BindView(R.id.tvOpen1)
    TextView tvOpen1;
    @BindView(R.id.tvOpen2)
    TextView tvOpen2;
    @BindView(R.id.tvOpen3)
    TextView tvOpen3;
    @BindView(R.id.tvOpen4)
    TextView tvOpen4;
    @BindView(R.id.tvOpen5)
    TextView tvOpen5;
    @BindView(R.id.tvOpen6)
    TextView tvOpen6;
    @BindView(R.id.tvOpen7)
    TextView tvOpen7;
    @BindView(R.id.redTotal)
    TextView redTotal;
    @BindView(R.id.fallingView)
    FallingView fallingView;
    @BindView(R.id.fallingViewO)
    FallingViewO fallingViewO;
    String currentDays="0",currentMoney="0",currentGetRed="0",totalMoney="0";
    String days_1="",days_2="",days_3="",days_4="",days_5="",days_6="",days_7="";
    SignTodayContract.Presenter presenter;
    SignTodayResult signTodayResult;

    public static SignTodayFragment newInstance(SignTodayResult signTodayResult) {
        Bundle bundle = new Bundle();
        bundle.putParcelable("signTodayResult",signTodayResult);
        SignTodayFragment loginFragment = new SignTodayFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment, null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.sign_today_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            signTodayResult = getArguments().getParcelable("signTodayResult");
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(RedEventD redEventD) {
        presenter.postRed("","","");
    }

    @Subscribe
    public void onEventMain(RedPacketEntity redPacketEntity) {
        EventBus.getDefault().post(new RefreshMoneyEvent());
        redTotal.setText(getString2Pt(CalcHelper.add(totalMoney,redPacketEntity.avatar)+""));
        GameLog.log("================RedPacketEntity================"+currentDays+"金额 "+redPacketEntity.avatar);
        switch (currentDays){
            case "1":
                onShowView(imageOpen1,tvOpen1,redPacketEntity.avatar);
                break;
            case "2":
                onShowView(imageOpen2,tvOpen2,redPacketEntity.avatar);
                break;
            case "3":
                onShowView(imageOpen3,tvOpen3,redPacketEntity.avatar);
                break;
            case "4":
                onShowView(imageOpen4,tvOpen4,redPacketEntity.avatar);
                break;
            case "5":
                onShowView(imageOpen5,tvOpen5,redPacketEntity.avatar);
                break;
            case "6":
                onShowView(imageOpen6,tvOpen6,redPacketEntity.avatar);
                break;
            case "0":
                onShowView(imageOpen7,tvOpen7,redPacketEntity.avatar);
                break;
        }
    }


    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        GameLog.log(" 用户的签到红包是 "+signTodayResult);
        if(Check.isNull(signTodayResult)){
            presenter.postSignTodays("","","");
        }else{
            postSignTodaysResult(signTodayResult);
        }
        //初始化一个雪花样式的fallObject
       /* FallObject.Builder builder = new FallObject.Builder(getResources().getDrawable(R.mipmap.event_fulldiscount));
        FallObject fallObject = builder
                .setSpeed(15,true)
//                .setSize(150,150,true)
                .setWind(5,true,true)
                .build();
        fallingViewO.addFallObject(fallObject,200);//添加50个下落物体对象*/
        /*fallingView.postDelayed(new Runnable() {
            @Override
            public void run() {
                fallingView.setVisibility(View.GONE);
                LuckGiftResult luckGiftResult  = new LuckGiftResult();
                luckGiftResult.setData_gold("10.23");
                luckGiftResult.setValid_money("5.23");
                showRedDialog(luckGiftResult.getData_gold()+"");
            }
        },3000);*/
    }

    @OnClick({ R.id.loginClose,R.id.imageOpen1,R.id.imageOpen2,R.id.imageOpen3,R.id.imageOpen4,R.id.imageOpen5,R.id.imageOpen6,R.id.imageOpen7,
            R.id.sign_today_help1,R.id.redTotalAll})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.loginClose:
                hide();
                break;
            case R.id.imageOpen1:
                /*LuckGiftResult luckGiftResult  = new LuckGiftResult();
                luckGiftResult.setData_gold("10.23");
                luckGiftResult.setValid_money("5.23");
                postLuckGiftResult(luckGiftResult);*/
                showMessage("未满足签到条件，加油哟！");
                break;
            case R.id.imageOpen2:
                showMessage("未满足签到条件，加油哟！");
                break;
            case R.id.imageOpen3:
                showMessage("未满足签到条件，加油哟！");
                break;
            case R.id.imageOpen4:
                showMessage("未满足签到条件，加油哟！");
                break;
            case R.id.imageOpen5:
                showMessage("未满足签到条件，加油哟！");
                break;
            case R.id.imageOpen6:
                showMessage("未满足签到条件，加油哟！");
                break;
            case R.id.imageOpen7:
                showMessage("未满足签到条件，加油哟！");
                break;
            case R.id.sign_today_help1:
                SignHelpFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.redTotalAll:
                showMessage("签到需满7天才可以领取，加油哟！");
                break;
        }
    }

    public void postLuckGiftResult(final LuckGiftResult luckGiftResult) {





    }

    private void onCheckAndSubmit() {
        /*String loginAccounts = loginAccount.getText().toString().trim();
        String loginPwdPwds = loginPwd.getText().toString().trim();
        if(Check.isEmpty(loginAccounts)){
            showMessage("请输入合法的用户账号");
        }
        if(Check.isEmpty(loginPwdPwds)){
            showMessage("请输入密码");
        }
        presenter.postLogin("",loginAccounts,loginPwdPwds);*/
    }

    private void onShowView(ImageView imageView,TextView textView,String days){
        if("0".equals(days)){
            imageView.setVisibility(View.VISIBLE);
            textView.setVisibility(View.GONE);
        }else{
            imageView.setVisibility(View.GONE);
            textView.setVisibility(View.VISIBLE);
            textView.setText(getString2Pt(days));
        }
    }

    private String getString2Pt(String money){
        DecimalFormat df = new DecimalFormat("0.00");
        //DecimalFormat df = new DecimalFormat("#0.00");//与上一行代码的区别是：#表示如果不存在则显示为空，0表示如果没有则该位补0.
        //DecimalFormat df = new DecimalFormat("#,###.00"); //将数据转换成以3位逗号隔开的字符串，并保留两位小数
        df.setRoundingMode(RoundingMode.FLOOR);//不四舍五入
        GameLog.log("需要格式化的值是 "+money);
        return df.format(Double.parseDouble(money));
    }

    @Override
    public void postSignTodaysResult(SignTodayResult signTodayResult) {
//        hide();
        totalMoney = signTodayResult.getTotal_money();
        redTotal.setText(getString2Pt(totalMoney));
        days_1 = signTodayResult.getSign_days_1();
        days_2 = signTodayResult.getSign_days_2();
        days_3 = signTodayResult.getSign_days_3();
        days_4 = signTodayResult.getSign_days_4();
        days_5 = signTodayResult.getSign_days_5();
        days_6 = signTodayResult.getSign_days_6();
        days_7 = signTodayResult.getSign_days_0();
        onShowView(imageOpen1,tvOpen1,days_1);
        onShowView(imageOpen2,tvOpen2,days_2);
        onShowView(imageOpen3,tvOpen3,days_3);
        onShowView(imageOpen4,tvOpen4,days_4);
        onShowView(imageOpen5,tvOpen5,days_5);
        onShowView(imageOpen6,tvOpen6,days_6);
        onShowView(imageOpen7,tvOpen7,days_7);
        currentDays = signTodayResult.getCurrent_week_day();
        switch (currentDays){
            case "1":
                currentGetRed = days_1;
                //currentMoney = signTodayResult.getSign_in_config().getWeek_1();
                break;
            case "2":
                currentGetRed = days_2;
                //currentMoney = signTodayResult.getSign_in_config().getWeek_2();
                break;
            case "3":
                currentGetRed = days_3;
                //currentMoney = signTodayResult.getSign_in_config().getWeek_3();
                break;
            case "4":
                currentGetRed = days_4;
                //currentMoney = signTodayResult.getSign_in_config().getWeek_4();
                break;
            case "5":
                currentGetRed = days_5;
                //currentMoney = signTodayResult.getSign_in_config().getWeek_5();
                break;
            case "6":
                currentGetRed = days_6;
                //currentMoney = signTodayResult.getSign_in_config().getWeek_6();
                break;
            case "0":
                currentGetRed = days_7;
                //currentMoney = signTodayResult.getSign_in_config().getWeek_7();
                break;
        }
        GameLog.log(" 红包 currentDays "+signTodayResult.getCurrent_week_day()+" currentMoney "+ currentMoney);
        if(currentGetRed.equals("0")){
            RedFragment.newInstance(signTodayResult.getCurrent_week_day(),currentMoney).show(getFragmentManager());
        }

    }

    @Override
    public void postRedResult(RedPacketResult redPacketResult) {
        EventBus.getDefault().post(new RedEvent(redPacketResult.getGold()));
    }

    @Override
    public void setPresenter(SignTodayContract.Presenter presenter) {
        this.presenter  = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}

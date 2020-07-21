package com.hgapp.bet365.homepage.signtoday;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.text.Html;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.bet365.Injections;
import com.hgapp.bet365.R;
import com.hgapp.bet365.base.HGBaseDialogFragment;
import com.hgapp.bet365.common.util.DoubleClickHelper;
import com.hgapp.bet365.data.ReceiveSignTidayResults;
import com.hgapp.bet365.data.SignTodayResults;
import com.hgapp.bet365.homepage.UserMoneyEvent;
import com.hgapp.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class SignTodayFragment extends HGBaseDialogFragment implements SignTodayContract.View {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    @BindView(R.id.eventShowCancel)
    ImageView eventShowCancel;
    @BindView(R.id.tv_sign_today_days)
    TextView tvSignTodayDays;
    @BindView(R.id.im_sign_today_days)
    ImageView imSignTodayDays;
    @BindView(R.id.sign_today_8)
    ImageView signToday8;
    @BindView(R.id.sign_today01)
    ImageView signToday01;
    @BindView(R.id.sign_today01_start)
    ImageView signToday01Start;
    @BindView(R.id.sign_today02)
    ImageView signToday02;
    @BindView(R.id.sign_today02_down)
    ImageView signToday02Down;
    @BindView(R.id.sign_today03)
    ImageView signToday03;
    @BindView(R.id.sign_today03_down)
    ImageView signToday03Down;
    @BindView(R.id.sign_today04)
    ImageView signToday04;
    @BindView(R.id.sign_today04_down)
    ImageView signToday04Down;
    @BindView(R.id.sign_today05)
    ImageView signToday05;
    @BindView(R.id.sign_today05_down)
    ImageView signToday05Down;
    @BindView(R.id.sign_today06)
    ImageView signToday06;
    @BindView(R.id.sign_today06_down)
    ImageView signToday06Down;
    @BindView(R.id.sign_today07)
    ImageView signToday07;
    @BindView(R.id.sign_today07_down)
    ImageView signToday07Down;
    @BindView(R.id.sign_today_now)
    ImageView signTodayNow;
    @BindView(R.id.sign_today_now_text)
    TextView signTodayNowText;
    @BindView(R.id.sign_today_now_take)
    ImageView signTodayNowTake;
    private String payId;
    private String getArgParam1;
    private int lastweekday,getArgParam2;
    private SignTodayContract.Presenter presenter;
    private boolean isShow = false;

    public static SignTodayFragment newInstance( String getArgParam1, int getArgParam2) {
        SignTodayFragment fragment = new SignTodayFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, getArgParam1);
        args.putInt(ARG_PARAM2, getArgParam2);
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getInt(ARG_PARAM2);
        }

        /*getActivity().getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);*/
    }


    @Override
    public int getLayoutResId() {
        return R.layout.dialog_sign_today;
    }


    @Override
    public void initView(View view, @Nullable Bundle savedInstanceState) {
        presenter.postSignTodayCheck("", "checked");
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(SignTodayContract.Presenter presenter) {
        this.presenter = presenter;
    }



    @Override
    public void postSignTodayCheckResult(SignTodayResults signTodayResults) {

        GameLog.log("检查日志信息：" + signTodayResults);
        lastweekday =  signTodayResults.getLastweekday();
        tvSignTodayDays.setText("当前连续 "+signTodayResults.getCurweekday()+" 天");
        switch (signTodayResults.getCurweekday()){
            case "0":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_0));
                break;
            case "1":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_1));
                break;
            case "2":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_2));
                break;
            case "3":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_3));
                break;
            case "4":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_4));
                break;
            case "5":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_5));
                break;
            case "6":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_6));
                break;
            case "7":
                imSignTodayDays.setBackground(getResources().getDrawable(R.mipmap.sign_today_7));
                break;
        }
        List<SignTodayResults.RowsBean> rowsBeanList =  signTodayResults.getRows();
        if(rowsBeanList.size()>=7){
            if(rowsBeanList.get(0).getStatus().equals("1")){
                signToday01.setBackground(getResources().getDrawable(R.mipmap.sign_today01_c));
                signToday01Start.setBackground(getResources().getDrawable(R.mipmap.sign_today_start_c));
            }
            if(rowsBeanList.get(1).getStatus().equals("1")){
                signToday02.setBackground(getResources().getDrawable(R.mipmap.sign_today02_c));
                signToday02Down.setBackground(getResources().getDrawable(R.mipmap.sign_today_mid_c));
            }
            if(rowsBeanList.get(2).getStatus().equals("1")){
                signToday03.setBackground(getResources().getDrawable(R.mipmap.sign_today03_c));
                signToday03Down.setBackground(getResources().getDrawable(R.mipmap.sign_today_mid_c));
            }
            if(rowsBeanList.get(3).getStatus().equals("1")){
                signToday04.setBackground(getResources().getDrawable(R.mipmap.sign_today04_c));
                signToday04Down.setBackground(getResources().getDrawable(R.mipmap.sign_today_mid_c));
            }
            if(rowsBeanList.get(4).getStatus().equals("1")){
                signToday05.setBackground(getResources().getDrawable(R.mipmap.sign_today05_c));
                signToday05Down.setBackground(getResources().getDrawable(R.mipmap.sign_today_mid_c));
            }
            if(rowsBeanList.get(5).getStatus().equals("1")){
                signToday06.setBackground(getResources().getDrawable(R.mipmap.sign_today06_c));
                signToday06Down.setBackground(getResources().getDrawable(R.mipmap.sign_today_mid_c));
            }
            if(rowsBeanList.get(6).getStatus().equals("1")){
                signToday07.setBackground(getResources().getDrawable(R.mipmap.sign_today07_c));
                signToday07Down.setBackground(getResources().getDrawable(R.mipmap.sign_today_end_c));
            }
        }
        String tyexx = "<br>活动规则：<br>" +
                "1. 活动期间登录"+onMarkRed("APP")+"，每天累计存款金额达到"+onMarkRed(signTodayResults.getStandardmoney())+"元均可点击签到" +
                "动态图进入活动页面参与签到。<br>"+
                "2. 签到以美东时间星期一至星期日为一个周期，完成一个周期玩家可登录活动页面领取红包，24小时未领取视为自动放弃。<br>"+
                "3. 签到活动期间用户累计签到"+onMarkRed(signTodayResults.getAttendanceDay().get(0)+"天、"+signTodayResults.getAttendanceDay().get(1)+"天、"+signTodayResults.getAttendanceDay().get(2)+"天")+"分别有不同等级的红包。<br>"+
                "4. 签到天数等级越高，获得高金额红包的几率越大，最高可获得"+onMarkRed(signTodayResults.getMaxstandardMoney())+"红包大奖!";
        signTodayNowText.setText(Html.fromHtml(tyexx));
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#FF0000'>" + sign+"</font>";
    }

    @Override
    public void postSignTodayReceiveResult(ReceiveSignTidayResults receiveSignTidayResults) {
        EventBus.getDefault().post(new UserMoneyEvent(receiveSignTidayResults.getBalance_hg()+""));
    }


    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }

    @OnClick({R.id.sign_today_now, R.id.sign_today_now_take,R.id.eventShowCancel})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.sign_today_now:
                DoubleClickHelper.getNewInstance().disabledView(signTodayNow);
                presenter.postSignTodaySign("","sign");
                break;
            case R.id.sign_today_now_take:
                DoubleClickHelper.getNewInstance().disabledView(signTodayNowTake);
                if(lastweekday>=3){
                    presenter.postSignTodayReceive("","receive");
                }else{
                    showMessage("当前不满足领取规则！");
                }
                break;
            case R.id.eventShowCancel:
                hide();
                break;
        }
    }
}

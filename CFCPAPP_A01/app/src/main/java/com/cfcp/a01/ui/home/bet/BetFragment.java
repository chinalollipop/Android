package com.cfcp.a01.ui.home.bet;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.home.HomeIconEvent;
import com.cfcp.a01.ui.home.sidebar.SideBarFragment;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.TouchProgressView;

import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;

public class BetFragment extends BaseFragment {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";

    @BindView(R.id.betTitleBack)
    TextView betTitleBack;
    @BindView(R.id.betTitleName)
    TextView betTitleName;
    @BindView(R.id.betTitleArrows)
    ImageView betTitleArrows;
    @BindView(R.id.betTitleLay)
    LinearLayout betTitleLay;
    @BindView(R.id.betTitleSet)
    ImageView betTitleSet;
    @BindView(R.id.betTitleMenu)
    ImageView betTitleMenu;
    @BindView(R.id.betArea)
    TextView betArea;
    @BindView(R.id.betChat)
    TextView betChat;
    @BindView(R.id.betMethodName)
    TextView betMethodName;
    @BindView(R.id.betMethodDown)
    ImageView betMethodDown;
    @BindView(R.id.betMethodNameLay)
    LinearLayout betMethodNameLay;
    @BindView(R.id.betIssue)
    TextView betIssue;
    @BindView(R.id.betLastIssue)
    TextView betLastIssue;
    @BindView(R.id.betTime)
    TextView betTime;
    @BindView(R.id.betDaysProfit)
    TextView betDaysProfit;
    @BindView(R.id.betModel)
    TextView betModel;
    @BindView(R.id.betTimes)
    EditText betTimes;
    @BindView(R.id.betMinusTxt)
    TextView betMinusTxt;
    @BindView(R.id.betMinus)
    ImageView betMinus;
    @BindView(R.id.betTouchPiew)
    TouchProgressView betTouchPiew;
    @BindView(R.id.betPlus)
    ImageView betPlus;
    @BindView(R.id.betPlusTxt)
    TextView betPlusTxt;
    @BindView(R.id.betClear)
    ImageView betClear;
    @BindView(R.id.betMoney)
    TextView betMoney;
    @BindView(R.id.betSubmit)
    TextView betSubmit;
    @BindView(R.id.betSure)
    TextView betSure;

    private HomeIconEvent mHomeIconEvent;
    private String typeArgs2;
    //赔率的进度条
    private int mProgress;
    //赔率的进度条  减法不能小于最小值
    private int mProgressMins = 0;
    public static BetFragment newInstance(HomeIconEvent homeIconEvent) {
        BetFragment betFragment = new BetFragment();
        Bundle args = new Bundle();
        args.putParcelable(TYPE1, homeIconEvent);
        //args.putString(TYPE2, type2);
        betFragment.setArguments(args);
        return betFragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            mHomeIconEvent = getArguments().getParcelable(TYPE1);
            //typeArgs2 = getArguments().getString(TYPE2);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_bet;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        betTitleName.setText(mHomeIconEvent.getIconName());
        betTouchPiew.setLineHeight(5);
        betTouchPiew.setPointColor(R.color.text_bet_submit);
        betTouchPiew.setPointRadius(20);
        betTouchPiew.setProgress(20);
        betTouchPiew.setOnProgressChangedListener(new TouchProgressView.OnProgressChangedListener() {
            @Override
            public void onProgressChanged(View view, int progress) {
                //设置赔率的大小 ，根据初始值来做
                mProgress = progress;
                betPlusTxt.setText(mProgress + "%");
            }
        });
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("================注册页需要消失的================");
        finish();
    }


    @OnClick({R.id.betTitleBack, R.id.betTitleLay, R.id.betTitleSet, R.id.betTitleMenu, R.id.betArea, R.id.betChat, R.id.betMethodNameLay, R.id.betModel, R.id.betTimes, R.id.betMinus, R.id.betPlus, R.id.betClear, R.id.betSubmit, R.id.betSure})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.betTitleBack:
                finish();
                break;
            case R.id.betTitleLay:
                break;
            case R.id.betTitleSet:
                break;
            case R.id.betTitleMenu:
                SideBarFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.betArea:
                break;
            case R.id.betChat:
                break;
            case R.id.betMethodNameLay:
                break;
            case R.id.betModel:
                break;
            case R.id.betTimes:
                break;
            case R.id.betMinus:
                --mProgress;
                if(mProgress<mProgressMins){
                    mProgress = mProgressMins;
                }
                betPlusTxt.setText(mProgress+"%");
                break;
            case R.id.betPlus:
                ++mProgress;
                if(mProgress>100){
                    mProgress = 100;
                }
                betPlusTxt.setText(mProgress+"%");
                break;
            case R.id.betClear:
                break;
            case R.id.betSubmit:
                break;
            case R.id.betSure:
                break;
        }
    }
}

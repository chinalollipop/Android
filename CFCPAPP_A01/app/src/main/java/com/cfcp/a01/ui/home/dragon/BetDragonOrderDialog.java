package com.cfcp.a01.ui.home.dragon;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseDialogFragment;
import com.cfcp.a01.data.CPBetResult;
import com.cfcp.a01.data.DragonBetCloseEvent;
import com.cfcp.a01.data.DragonBetEvent;
import com.kongzue.dialog.v3.WaitDialog;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;

public class BetDragonOrderDialog extends BaseDialogFragment {
    public static final String PARAM0 = "betResult";
    public static final String PARAM1 = "gold";
    public static final String PARAM2 = "game_code";
    public static final String PARAM3 = "round";
    public static final String PARAM4 = "x_session_token";
    @BindView(R.id.betOrderCpBottom)
    LinearLayout betOrderCpBottom;
    @BindView(R.id.dragonName)
    TextView dragonName;
    @BindView(R.id.dragonIssue)
    TextView dragonIssue;
    @BindView(R.id.betOrderCpNumber)
    TextView betOrderCpNumber;
    @BindView(R.id.betOrderCpMoney)
    TextView betOrderCpMoney;
    @BindView(R.id.betOrderCpSubmit)
    Button betOrderCpSubmit;
    @BindView(R.id.betOrderCpCancel)
    Button betOrderCpCancel;
    private String aram1="",aram2,aram3,aram4;


    public static BetDragonOrderDialog newInstance(String name, String issue, String money, String content) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM1, name);
        bundle.putString(PARAM2, issue);
        bundle.putString(PARAM3, money);
        bundle.putString(PARAM4, content);
        BetDragonOrderDialog dialog = new BetDragonOrderDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int setLayoutId() {
        return R.layout.dialog_bet_order_dragon;
    }

    @Override
    protected void setEvents(View view, Bundle bundle) {
        EventBus.getDefault().register(this);
        aram1 = getArguments().getString(PARAM1);
        aram2 = getArguments().getString(PARAM2);
        aram3 = getArguments().getString(PARAM3);
        aram4 = getArguments().getString(PARAM4);
        dragonName.setText(aram1+" : ");
        dragonIssue.setText(aram2+"期");
        betOrderCpMoney.setText(aram3+" 元");
        betOrderCpNumber.setText(aram4);

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        WaitDialog.dismiss();
        hide();
    }


    @OnClick({R.id.betOrderCpCancel,R.id.betOrderCpSubmit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.betOrderCpCancel:
                hide();
                break;
            case R.id.betOrderCpSubmit:
                EventBus.getDefault().post(new DragonBetEvent("提交中"));
                WaitDialog.show((AppCompatActivity) getActivity(), "提交中...");
                break;
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(CPBetResult cpBetResult){
        showMessage("下注成功");
        hide();
    }
    @Subscribe
    public void onEventMain(DragonBetCloseEvent cpBetResult){
        //showMessage(cpBetResult.mesg);
        WaitDialog.dismiss();
        hide();
    }

}

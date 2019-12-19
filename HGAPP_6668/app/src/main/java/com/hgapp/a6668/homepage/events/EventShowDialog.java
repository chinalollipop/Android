package com.hgapp.a6668.homepage.events;

import android.os.Bundle;
import android.text.Editable;
import android.text.Html;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseDialogFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.CalcHelper;
import com.hgapp.a6668.common.util.DoubleClickHelper;
import com.hgapp.a6668.common.util.GameShipHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.data.BetResult;
import com.hgapp.a6668.data.GameAllPlayBKResult;
import com.hgapp.a6668.data.GameAllPlayFTResult;
import com.hgapp.a6668.data.GameAllPlayRBKResult;
import com.hgapp.a6668.data.GameAllPlayRFTResult;
import com.hgapp.a6668.data.PrepareBetResult;
import com.hgapp.a6668.homepage.handicap.betapi.PrepareBetApiContract;
import com.hgapp.a6668.homepage.handicap.betapi.PrepareRequestParams;
import com.hgapp.a6668.homepage.handicap.leaguedetail.PrepareBetEvent;
import com.hgapp.a6668.homepage.sportslist.bet.BetOrderSubmitSuccessDialog;
import com.hgapp.a6668.homepage.sportslist.bet.OrderNumber;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class EventShowDialog extends HGBaseDialogFragment {

    public static final String PARAM0 = "param0";
    @BindView(R.id.eventShowText)
    TextView eventShowText;
    String getParam0 = "";

    public static EventShowDialog newInstance(String param0, String param1) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM0, param0);
        EventShowDialog dialog = new EventShowDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.dialog_event_show;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        //EventBus.getDefault().register(this);
        getParam0 = getArguments().getString(PARAM0);
        eventShowText.setText(getParam0);
    }

    @OnClick({R.id.eventShowCancel, R.id.eventShowSuccess})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.eventShowCancel:
                this.dismiss();
                break;
            case R.id.eventShowSuccess:
                this.dismiss();
                break;
        }
    }

    @Override
    public void onDestroyView() {
        //EventBus.getDefault().unregister(this);
        super.onDestroyView();
    }
}

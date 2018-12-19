package com.hgapp.a6668.homepage.events;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.view.animation.ScaleAnimation;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.GameShipHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.redpacket.RedPacketsLayout;
import com.hgapp.a6668.data.DepositAliPayQCCodeResult;
import com.hgapp.a6668.data.DownAppGiftResult;
import com.hgapp.a6668.data.LuckGiftResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.data.ValidResult;
import com.hgapp.a6668.homepage.UserMoneyEvent;
import com.hgapp.a6668.homepage.events.anim.Swing;
import com.hgapp.a6668.homepage.events.anim.ZoomOutRightExit;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.Date;

import butterknife.BindView;
import butterknife.OnClick;

public class NewEventsFragment extends HGBaseFragment implements EventsContract.View {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    private String payId;
    private String getArgParam1;
    private int getArgParam2;
    private EventsContract.Presenter presenter;
    private View mRedPacketDialogView;
    private boolean isShow = false;
    public static NewEventsFragment newInstance( String getArgParam1, int getArgParam2) {
        NewEventsFragment fragment = new NewEventsFragment();
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
    public int setLayoutId() {
        return R.layout.fragment_newyear_event;
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(EventsContract.Presenter presenter) {
        this.presenter = presenter;
    }


    @OnClick({R.id.eventTitleBack})
    public void onViewClicked(final View view) {
        switch (view.getId()) {
            case R.id.eventTitleBack:
                finish();
                break;
        }
    }

    @Override
    public void postDownAppGiftResult(final DownAppGiftResult data) {
        //showMessage(data);
    }

    @Override
    public void postLuckGiftResult(final LuckGiftResult luckGiftResult) {
    }

    @Override
    public void postValidGiftResult(ValidResult validResult) {
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
    }

}

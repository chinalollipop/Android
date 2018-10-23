package com.hgapp.a0086.homepage.events;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;

import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.common.widgets.RoundRainbowTextView;
import com.hgapp.a0086.common.widgets.redpacket.RedPacketsLayout;
import com.hgapp.a0086.data.DepositAliPayQCCodeResult;
import com.hgapp.a0086.depositpage.aliqcpay.AliQCPayContract;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import java.text.SimpleDateFormat;
import java.util.Date;

import butterknife.BindView;
import butterknife.OnClick;

public class EventsFragment extends HGBaseFragment implements AliQCPayContract.View {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    @BindView(R.id.titleEventBack)
    NTitleBar titleEventBack;
    @BindView(R.id.packets_layout)
    RedPacketsLayout packets_layout;
    @BindView(R.id.roundtv)
    RoundRainbowTextView roundtv;
    @BindView(R.id.ivClickOne)
    ImageView ivClickOne;
    @BindView(R.id.btnClickRed)
    Button btnClickRed;
    private String payId;
    private String getArgParam1;
    private int getArgParam2;

    private AliQCPayContract.Presenter presenter;
    private View mRedPacketDialogView;
    private RedPacketViewHolder mRedPacketViewHolder;
    private RedCustomDialog mRedPacketDialog;
    public static EventsFragment newInstance(DepositAliPayQCCodeResult dataBean, String getArgParam1, int getArgParam2) {
        EventsFragment fragment = new EventsFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM0, dataBean);
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
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_event;
    }


    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        titleEventBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(AliQCPayContract.Presenter presenter) {
        this.presenter = presenter;
    }

    public void showRedDialog(View view){
        String alias = ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS);
        RedPacketEntity entity = new RedPacketEntity(alias, "http://xxx.xxx.com/20171205180511192.png", "恭喜发财，大吉大利");
        showRedPacketDialog(entity);
    }

    public void showRedPacketDialog(RedPacketEntity entity) {
        if (mRedPacketDialogView == null) {
            mRedPacketDialogView = View.inflate(getContext(), R.layout.dialog_red_packet, null);
            mRedPacketViewHolder = new RedPacketViewHolder(getContext(), mRedPacketDialogView);
            mRedPacketDialog = new RedCustomDialog(getContext(), mRedPacketDialogView, R.style.red_custom_dialog);
            mRedPacketDialog.setCancelable(false);
        }
        mRedPacketViewHolder.setData(entity);
        mRedPacketViewHolder.setOnRedPacketDialogClickListener(new OnRedPacketDialogClickListener() {
            @Override
            public void onCloseClick() {
                mRedPacketDialog.dismiss();
            }

            @Override
            public void onOpenClick() {
                //领取红包,调用接口
            }
        });
        mRedPacketDialog.show();
    }

    @OnClick({R.id.ivClickOne, R.id.btnClickRed})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.ivClickOne:
                showRedDialog(view);
                /*packets_layout.setVisibility(View.VISIBLE);
                packets_layout.post(new Runnable() {
                    @Override
                    public void run() {
                        packets_layout.startRain();
                        GameLog.log("开始下雨了");
                    }
                });
                ivClickOne.setClickable(false);
                ivClickOne.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        if(!Check.isNull(ivClickOne)) {
                            ivClickOne.setClickable(true);
                        }
                    }
                },5000);
                packets_layout.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        packets_layout.stopRain();
                        packets_layout.setVisibility(View.GONE);
                        GameLog.log("停止下雨了");
                    }
                },5000);*/

                break;
            case R.id.btnClickRed:
                btnClickRed.setClickable(false);
                btnClickRed.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        if(!Check.isNull(btnClickRed)){
                            btnClickRed.setClickable(true);
                        }
                    }
                },5000);
                packets_layout.setVisibility(View.VISIBLE);
                packets_layout.post(new Runnable() {
                    @Override
                    public void run() {
                        packets_layout.startRain();
                    }
                });
                packets_layout.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("停止下雨了");
                        packets_layout.stopRain();
                        packets_layout.setVisibility(View.GONE);
                        GameLog.log("停止下雨了");
                    }
                },5000);
                break;
        }
    }
}

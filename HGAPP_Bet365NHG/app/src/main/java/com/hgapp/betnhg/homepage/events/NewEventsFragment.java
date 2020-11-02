package com.hgapp.betnhg.homepage.events;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.TextView;

import com.hgapp.betnhg.Injections;
import com.hgapp.betnhg.R;
import com.hgapp.betnhg.base.HGBaseFragment;
import com.hgapp.betnhg.common.util.ACache;
import com.hgapp.betnhg.common.util.GameShipHelper;
import com.hgapp.betnhg.common.util.HGConstant;
import com.hgapp.betnhg.common.widgets.redpacket.RedPacketsLayout;
import com.hgapp.betnhg.data.DownAppGiftResult;
import com.hgapp.betnhg.data.LuckGiftResult;
import com.hgapp.betnhg.data.PersonBalanceResult;
import com.hgapp.betnhg.data.ValidResult;
import com.hgapp.betnhg.homepage.UserMoneyEvent;
import com.hgapp.betnhg.homepage.events.anim.Swing;
import com.hgapp.betnhg.homepage.events.anim.ZoomOutRightExit;
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
    @BindView(R.id.packets_layout)
    RedPacketsLayout packets_layout;
    @BindView(R.id.eventTitleUserMoney)
    TextView eventTitleUserMoney;
    private String payId;
    private String getArgParam1;
    private int getArgParam2;
    private EventsContract.Presenter presenter;
    private View mRedPacketDialogView;
    private RedPacketViewHolder mRedPacketViewHolder;
    private RedCustomDialog mRedPacketDialog;
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
        eventTitleUserMoney.setText(getArgParam1);
    }

    public void showRedDialog(String data){
        String alias = ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS);
        RedPacketEntity entity = new RedPacketEntity(alias, "http://xxx.xxx.com/20171205180511192.png", "恭喜发财，大吉大利");
        showRedPacketDialog(entity,data);
    }

    public void showRedPacketDialog(RedPacketEntity entity, final String data) {
        if (mRedPacketDialogView == null) {
            mRedPacketDialogView = View.inflate(getContext(), R.layout.dialog_red_packet, null);
            mRedPacketViewHolder = new RedPacketViewHolder(getContext(), mRedPacketDialogView);
            mRedPacketDialog = new RedCustomDialog(getContext(), mRedPacketDialogView, R.style.red_custom_dialog);
            mRedPacketDialog.setCancelable(false);
        }
        new Swing().start(mRedPacketDialogView);
        mRedPacketViewHolder.setData(entity);
        mRedPacketViewHolder.setOnRedPacketDialogClickListener(new OnRedPacketDialogClickListener() {
            @Override
            public void onCloseClick() {
                new ZoomOutRightExit().start(mRedPacketDialogView);
                mRedPacketDialogView.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        /*if(isShow){
                            showMessage("彩金将在24小时内自动派发到账!");
                        }*/
                        presenter.postPersonBalance("","");
                        mRedPacketDialog.dismiss();
                        new Swing().start(eventTitleUserMoney);
                    }
                },1000);
            }

            @Override
            public void onOpenClick() {
                //领取红包,调用接口
                mRedPacketDialogView.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        mRedPacketViewHolder.setData(data);
                    }
                },2000);
            }
        });
        mRedPacketDialog.show();
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(EventsContract.Presenter presenter) {
        this.presenter = presenter;
    }


    @OnClick({R.id.eventTitleBack,R.id.nyearRed})
    public void onViewClicked(final View view) {
        switch (view.getId()) {
            case R.id.eventTitleBack:
                finish();
                break;
            case R.id.nyearRed:
                presenter.postNewYearRed("","");
                break;
        }
    }

    @Override
    public void postDownAppGiftResult(final DownAppGiftResult data) {
        //showMessage(data);
    }

    @Override
    public void postLuckGiftResult(final LuckGiftResult luckGiftResult) {
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
                showRedDialog(luckGiftResult.getData_gold()+"");
            }
        },2000);
        packets_layout.postDelayed(new Runnable() {
            @Override
            public void run() {
                packets_layout.stopRain();
                packets_layout.setVisibility(View.GONE);
                GameLog.log("停止下雨了");
            }
        },2500);
    }

    @Override
    public void postValidGiftResult(ValidResult validResult) {
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        eventTitleUserMoney.setText(GameShipHelper.formatMoney(personBalance.getBalance_hg()));
        EventBus.getDefault().post(new UserMoneyEvent(GameShipHelper.formatMoney(personBalance.getBalance_hg())));
    }

}

package com.hgapp.a0086.homepage.events;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;

import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.GameShipHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.redpacket.RedPacketsLayout;
import com.hgapp.a0086.data.DownAppGiftResult;
import com.hgapp.a0086.data.LuckGiftResult;
import com.hgapp.a0086.data.PersonBalanceResult;
import com.hgapp.a0086.data.ValidResult;
import com.hgapp.a0086.homepage.UserMoneyEvent;
import com.hgapp.a0086.homepage.events.anim.Swing;
import com.hgapp.a0086.homepage.events.anim.ZoomOutRightExit;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.RegexUtils;

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
    @BindView(R.id.nyearSignEdit)
    EditText nyearSignEdit;
    @BindView(R.id.eventTitleUserMoney)
    TextView eventTitleUserMoney;
    @BindView(R.id.nyearSignNum)
    TextView nyearSignNum;
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
        //进来就要请求红包的次数
        presenter.postNewUserSignValidNum("","","get_remain_num");
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(EventsContract.Presenter presenter) {
        this.presenter = presenter;
    }


    @OnClick({R.id.eventTitleBack,R.id.nyearSign,R.id.nyearTake})
    public void onViewClicked(final View view) {
        switch (view.getId()) {
            case R.id.eventTitleBack:
                hideKeyboard();
                finish();
                break;
            case R.id.nyearSign:
                checkPhone();
                break;
            case R.id.nyearTake:
                hideKeyboard();
                presenter.postNewUserRed("","receive_red_envelope");
                //presenter.postNewUserSignValidNum("","","get_remain_num");
                break;
        }
    }

    private void checkPhone() {
        String nyearSign = nyearSignEdit.getText().toString().trim();
        if(!RegexUtils.isMobilePCExact(nyearSign)){
            showMessage("手机号码不符合规范！");
            return;
        }
        hideKeyboard();
        presenter.postNewUserSign("",nyearSign,"mobilesign");
    }

    @Override
    public void postDownAppGiftResult(final DownAppGiftResult data) {
        //showMessage(data);
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
    public void postLuckGiftResult(final LuckGiftResult luckGiftResult) {
        isShow = false;
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
        presenter.postNewUserSignValidNum("","","get_remain_num");
    }

    @Override
    public void postValidGiftResult(ValidResult validResult) {
        GameLog.log("用户的剩余次数是 "+validResult.getLast_times());
        nyearSignNum.setText(validResult.getLast_times()+"");
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        eventTitleUserMoney.setText(GameShipHelper.formatMoney(personBalance.getBalance_hg()));
        EventBus.getDefault().post(new UserMoneyEvent(GameShipHelper.formatMoney(personBalance.getBalance_hg())));
    }

}

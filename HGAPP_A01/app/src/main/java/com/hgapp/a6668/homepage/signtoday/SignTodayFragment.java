package com.hgapp.a6668.homepage.signtoday;

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
import com.hgapp.a6668.base.HGBaseDialogFragment;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.GameShipHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.redpacket.RedPacketsLayout;
import com.hgapp.a6668.data.DepositAliPayQCCodeResult;
import com.hgapp.a6668.data.DownAppGiftResult;
import com.hgapp.a6668.data.LuckGiftResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.data.SignTodayResults;
import com.hgapp.a6668.data.ValidResult;
import com.hgapp.a6668.homepage.UserMoneyEvent;
import com.hgapp.a6668.homepage.events.OnRedPacketDialogClickListener;
import com.hgapp.a6668.homepage.events.RedCustomDialog;
import com.hgapp.a6668.homepage.events.RedPacketEntity;
import com.hgapp.a6668.homepage.events.RedPacketViewHolder;
import com.hgapp.a6668.homepage.events.anim.Swing;
import com.hgapp.a6668.homepage.events.anim.ZoomOutRightExit;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.Date;

import butterknife.BindView;
import butterknife.OnClick;

public class SignTodayFragment extends HGBaseDialogFragment implements SignTodayContract.View {

    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    private String payId;
    private String getArgParam1;
    private int getArgParam2;
    private SignTodayContract.Presenter presenter;
    private View mRedPacketDialogView;
    private RedPacketViewHolder mRedPacketViewHolder;
    private RedCustomDialog mRedPacketDialog;
    private boolean isShow = false;
    public static SignTodayFragment newInstance(DepositAliPayQCCodeResult dataBean, String getArgParam1, int getArgParam2) {
        SignTodayFragment fragment = new SignTodayFragment();
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

        /*getActivity().getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);*/
    }


    @Override
    public int getLayoutResId() {
        return R.layout.dialog_sign_today;
    }



    @Override
    public void initView(View view,@Nullable Bundle savedInstanceState) {
        presenter.postSignTodayCheck("","checked");
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(SignTodayContract.Presenter presenter) {
        this.presenter = presenter;
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
                        if(isShow) {
                            showMessage("彩金将在24小时内自动派发到账!");
                        }
                        mRedPacketDialog.dismiss();
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

        //showDialog();
    }

    private void showDialog() {
        /** 设置缩放动画 */
        final ScaleAnimation animation = new ScaleAnimation(0.0f, 1.4f, 0.0f, 1.4f,
                Animation.RELATIVE_TO_SELF, 0.5f, Animation.RELATIVE_TO_SELF, 0.5f);
        animation.setDuration(2000);//设置动画持续时间
/** 常用方法 */
//animation.setRepeatCount(int repeatCount);//设置重复次数
//animation.setFillAfter(boolean);//动画执行完后是否停留在执行完的状态
//animation.setStartOffset(long startOffset);//执行前的等待时间
        animation.startNow();
        mRedPacketDialogView.setAnimation(animation);
    }

    private void hideDialog() {
        /** 设置缩放动画 */
        final ScaleAnimation animation = new ScaleAnimation(1.4f, 0.0f, 1.4f,0.0f,
                Animation.RELATIVE_TO_SELF, 0.5f, Animation.RELATIVE_TO_SELF, 0.5f);
        animation.setDuration(2000);//设置动画持续时间
/** 常用方法 */
//animation.setRepeatCount(int repeatCount);//设置重复次数
//animation.setFillAfter(boolean);//动画执行完后是否停留在执行完的状态
//animation.setStartOffset(long startOffset);//执行前的等待时间
        animation.startNow();
        mRedPacketDialogView.setAnimation(animation);
    }


    @Override
    public void postSignTodayCheckResult(SignTodayResults signTodayResults) {

        GameLog.log("检查日志信息："+signTodayResults);
    }

    @Override
    public void postSignTodayReceiveResult(SignTodayResults signTodayResults) {

    }
}

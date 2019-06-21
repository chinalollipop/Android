package com.qpweb.a01.ui.loginhome.sign;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.LinearInterpolator;
import android.view.animation.RotateAnimation;
import android.widget.ImageView;
import android.widget.TextView;

import com.dingmouren.fallingview.FallingView;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.widget.IconRainView;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;

public class RedFragment extends BaseDialogFragment {

    int postion = 1;
    int gameMusic = 1;
    int bgMusic = 1;
    String current_week_day, sign_days_money;
    private FrameAnimation mFrameAnimation;
    @BindView(R.id.redImage)
    ImageView redImage;
    @BindView(R.id.redOpen)
    ImageView redOpen;
    @BindView(R.id.redIView)
    ImageView redIView;
    @BindView(R.id.fallingView)
    FallingView fallingView;
    @BindView(R.id.iconRain)
    IconRainView iconRain;
    @BindView(R.id.redTView)
    TextView redTView;
    @BindView(R.id.redText)
    TextView redText;
    private int[] mImgResIds = new int[]{
            R.mipmap.icon_open_red_packet2,
            R.mipmap.icon_open_red_packet3,
            R.mipmap.icon_open_red_packet4,
            R.mipmap.icon_open_red_packet5,
            R.mipmap.icon_open_red_packet6,
            R.mipmap.icon_open_red_packet7,
            R.mipmap.icon_open_red_packet8,
            R.mipmap.icon_open_red_packet9,
            R.mipmap.icon_open_red_packet2,
    };

    public static RedFragment newInstance(String current_week_day,String sign_days_money) {
        Bundle bundle = new Bundle();
        bundle.putString("current_week_day",current_week_day);
        bundle.putString("sign_days_money",sign_days_money);
        RedFragment loginFragment = new RedFragment();
        loginFragment.setArguments(bundle);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.red_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            current_week_day = getArguments().getString("current_week_day");
            sign_days_money = getArguments().getString("sign_days_money");
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        switch (current_week_day){
            case "1":
                redTView.setText("第一天");
                redIView.setBackgroundResource(R.mipmap.check_sign_1);
                break;
            case "2":
                redTView.setText("第二天");
                redIView.setBackgroundResource(R.mipmap.check_sign_2);
                break;
            case "3":
                redTView.setText("第三天");
                redIView.setBackgroundResource(R.mipmap.check_sign_3);
                break;
            case "4":
                redTView.setText("第四天");
                redIView.setBackgroundResource(R.mipmap.check_sign_4);
                break;
            case "5":
                redTView.setText("第五天");
                redIView.setBackgroundResource(R.mipmap.check_sign_5);
                break;
            case "6":
                redTView.setText("第六天");
                redIView.setBackgroundResource(R.mipmap.check_sign_6);
                break;
            case "7":
                redTView.setText("第七天");
                redIView.setBackgroundResource(R.mipmap.check_sign_7);
                break;
        }
        redText.setText(sign_days_money);
        RotateAnimation animation= new RotateAnimation(0,360f, Animation.RELATIVE_TO_SELF,0.5f,Animation.RELATIVE_TO_SELF,0.5f);
        animation.setDuration(2000);
        animation.setFillAfter(true);
        animation.setInterpolator(new LinearInterpolator());
        animation.setRepeatMode(Animation.RESTART);
        animation.setRepeatCount(Animation.INFINITE);
        if (null != redImage) {
            redImage.startAnimation(animation);
        }
        String userName = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_ACCOUNT);
        String pwd = ACache.get(getContext()).getAsString(QPConstant.USERNAME_LOGIN_PWD);
    }

    public void startAnim() {
        mFrameAnimation = new FrameAnimation(redOpen, mImgResIds, 50, true);
        mFrameAnimation.setAnimationListener(new FrameAnimation.AnimationListener() {
            @Override
            public void onAnimationStart() {
            }

            @Override
            public void onAnimationEnd() {
            }

            @Override
            public void onAnimationRepeat() {
            }

            @Override
            public void onAnimationPause() {
                redOpen.setBackgroundResource(R.mipmap.icon_open_red_packet2);
            }
        });
    }

    public void stopAnim() {
        if (mFrameAnimation != null) {
            mFrameAnimation.release();
            mFrameAnimation = null;
        }
    }

    @Subscribe
    public void onEventMain(RedEvent redEvent) {
        sign_days_money = redEvent.getGold();
        GameLog.log("领取红包的金额是 "+sign_days_money);
        if(Check.isEmpty(sign_days_money)){
            hide();
            return;
        }
        redText.setText(sign_days_money);
        redOpen.postDelayed(new Runnable() {
            @Override
            public void run() {
                iconRain.setVisibility(View.VISIBLE);
                iconRain.startRainFall(100);
                redOpen.setVisibility(View.GONE);
                redText.setVisibility(View.VISIBLE);
                redOpen.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        EventBus.getDefault().post(new RedPacketEntity(sign_days_money,sign_days_money,sign_days_money));
                        hide();
                    }
                },2000);
                startAnim();
            }
        },3000);
        startAnim();
    }


    @OnClick(R.id.redOpen)
    public void onViewClicked() {
        /*fallingView.setVisibility(View.VISIBLE);
        fallingView.postDelayed(new Runnable() {
            @Override
            public void run() {
                fallingView.setVisibility(View.GONE);
            }
        },1500);*/
        EventBus.getDefault().post(new RedEventD());

    }
}

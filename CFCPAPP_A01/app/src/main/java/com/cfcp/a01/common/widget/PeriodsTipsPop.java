package com.cfcp.a01.common.widget;

import android.annotation.SuppressLint;
import android.content.Context;
import android.os.CountDownTimer;
import android.view.View;
import android.view.animation.Animation;
import android.widget.TextView;

import com.cfcp.a01.R;

import razerdp.basepopup.BasePopupWindow;

/**
 * Created by Colin on 2019/3/1.
 * 彩票期数变化的弹窗提醒
 */
public class PeriodsTipsPop extends BasePopupWindow {

    private CountDownTimer countDownTimer;
    private TextView tvIssue;
    private TextView tvTime;

    @SuppressLint("SetTextI18n")
    public PeriodsTipsPop(Context context) {
        super(context);
        tvIssue = findViewById(R.id.tv_issue);
        tvTime = findViewById(R.id.tv_time);
    }

    @Override
    protected Animation onCreateShowAnimation() {
        return getDefaultScaleAnimation();
    }

    @Override
    protected Animation onCreateDismissAnimation() {
        return getDefaultScaleAnimation(false);
    }

    @Override
    public View onCreateContentView() {
        return createPopupById(R.layout.pop_periods_tips);
    }

    @SuppressLint("SetTextI18n")
    public void setPeriods(String periods) {
        tvIssue.setText("第 " + periods + " 期");
        // 倒计时5秒，一次1秒
        countDownTimer = new CountDownTimer(5000, 1000) {
            @Override
            public void onTick(long millisUntilFinished) {
                String second = String.valueOf(millisUntilFinished / 1000);
                tvTime.setText("请留意期号变化 [ " + second + " ]");
            }

            @Override
            public void onFinish() {
                dismiss();
            }
        }.start();
    }

    @Override
    public void onDismiss() {
        super.onDismiss();
        if (countDownTimer != null) {
            countDownTimer.cancel();
            countDownTimer = null;
        }
    }
}
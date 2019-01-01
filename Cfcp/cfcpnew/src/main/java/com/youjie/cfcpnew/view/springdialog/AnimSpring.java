package com.youjie.cfcpnew.view.springdialog;

import android.annotation.SuppressLint;
import android.view.View;

import com.facebook.rebound.Spring;
import com.facebook.rebound.SpringConfig;
import com.facebook.rebound.SpringListener;
import com.facebook.rebound.SpringSystem;

/**
 * 说明 Spring弹性动画
 * 创建时间 2017/1/16.
 */

class AnimSpring {
    private static SpringSystem springSystem;
    private SpringConfig springConfig = SpringConfig.fromBouncinessAndSpeed(8, 2);

    private View animView;//需要操作的视图

    private AnimSpring(View animView) {
        this.animView = animView;
    }

    static synchronized AnimSpring getInstance(View view) {
        AnimSpring animSpring = new AnimSpring(view);
        if (springSystem == null) {
            springSystem = SpringSystem.create();
        }
        return animSpring;
    }

    /**
     * @param startX 开始X坐标(像素为单位)
     * @param startY 开始Y坐标
     * @param endX   结束X坐标
     * @param endY   结束Y坐标
*               说明 设置平移动画
     */
    void startTranslationAnim(final double startX, final double startY, double endX, double endY) {
        //获取Spring对象
        Spring transSpringX = springSystem.createSpring();
        Spring transSpringY = springSystem.createSpring();

        //设置Spring动画配置
        transSpringX.setSpringConfig(springConfig);
        transSpringY.setSpringConfig(springConfig);

        //设置动画开始的值
        transSpringX.setCurrentValue(startX);
        transSpringY.setCurrentValue(startY);

        //设置动画结束值
        transSpringX.setEndValue(endX);
        transSpringY.setEndValue(endY);

        //设置动画监听
        transSpringX.addListener(new SpringListener() {
            @Override
            public void onSpringUpdate(Spring spring) {
                animView.setTranslationX((float) spring.getCurrentValue());
            }

            @Override
            public void onSpringAtRest(Spring spring) {

            }

            @Override
            public void onSpringActivate(Spring spring) {

            }

            @Override
            public void onSpringEndStateChange(Spring spring) {

            }
        });
        transSpringY.addListener(new SpringListener() {
            @Override
            public void onSpringUpdate(Spring spring) {
                animView.setTranslationY((float) spring.getCurrentValue());
            }

            @Override
            public void onSpringAtRest(Spring spring) {

            }

            @Override
            public void onSpringActivate(Spring spring) {

            }

            @Override
            public void onSpringEndStateChange(Spring spring) {

            }
        });
    }
}

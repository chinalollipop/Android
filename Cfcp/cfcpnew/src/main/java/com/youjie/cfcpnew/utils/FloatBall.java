package com.youjie.cfcpnew.utils;

import android.app.Activity;
import android.content.Context;
import android.graphics.drawable.Drawable;

import com.youjie.cfcpnew.view.floatingball.FloatBallManager;
import com.youjie.cfcpnew.view.floatingball.floatball.FloatBallCfg;
import com.youjie.cfcpnew.view.floatingball.menu.FloatMenuCfg;
import com.youjie.cfcpnew.view.floatingball.utils.BackGroudSeletor;


public class FloatBall {

    public static FloatBallManager initSinglePageFloatball(Context context) {
        //1 初始化悬浮球配置，定义好悬浮球大小和icon的drawable
        int ballSize =DensityUtil.dp2px(context.getApplicationContext(), 36);
        Drawable ballIcon = BackGroudSeletor.getdrawble("ic_floatball", context.getApplicationContext());
        FloatBallCfg ballCfg = new FloatBallCfg(ballSize, ballIcon, FloatBallCfg.Gravity.RIGHT_CENTER);
        //设置悬浮球不半隐藏
        ballCfg.setHideHalfLater(false);
        //2 需要显示悬浮菜单
        //2.1 初始化悬浮菜单配置，有菜单item的大小和菜单item的个数
        int menuSize = DensityUtil.dp2px(context.getApplicationContext(), 180);
        int menuItemSize = DensityUtil.dp2px(context.getApplicationContext(), 36);
        FloatMenuCfg menuCfg = new FloatMenuCfg(menuSize, menuItemSize);
        //3 生成floatballManager
        return new FloatBallManager((Activity) context, ballCfg, menuCfg);
    }
}

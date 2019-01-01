package com.youjie.cfcpnew.utils;

import android.content.Context;

/**
 * Created by Colin on 2017/12/14.
 * 像素转换
 *
 * @author Colin
 */
public class DensityUtil {

    public static int densityDPI;//屏幕密度
    public static int screenWidthPx; //屏幕宽 px
    public static int screenhightPx; //屏幕高 px
    public static float density;//屏幕密度
    public static float screenWidthDip;//dp单位
    public static float screenHightDip;//dp单位

    /**
     * 根据手机的分辨率从 dp 的单位 转成为 px(像素)
     */
    public static int dp2px(Context context, float dpValue) {
        final float scale = context.getResources().getDisplayMetrics().density;
        return (int) (dpValue * scale + 0.5f);
    }

    /**
     * 根据手机的分辨率从 px(像素) 的单位 转成为 dp
     */
    public static int px2dp(Context context, float pxValue) {
        final float scale = context.getResources().getDisplayMetrics().density;
        return (int) (pxValue / scale + 0.5f);
    }
}

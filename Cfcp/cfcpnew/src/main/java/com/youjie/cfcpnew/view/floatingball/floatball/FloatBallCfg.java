package com.youjie.cfcpnew.view.floatingball.floatball;

import android.annotation.SuppressLint;
import android.graphics.drawable.Drawable;

public class FloatBallCfg {
    Drawable mIcon;
    int mSize;
    /**
     * 标记悬浮球所处于屏幕中的位置
     *
     * @see Gravity#LEFT_TOP
     * @see Gravity#LEFT_CENTER
     * @see Gravity#LEFT_BOTTOM
     * @see Gravity#RIGHT_TOP
     * @see Gravity#RIGHT_CENTER
     * @see Gravity#RIGHT_BOTTOM
     */
    Gravity mGravity;
    //第一次显示的y坐标偏移量，左上角是原点。
    int mOffsetY = 0;
    boolean mHideHalfLater = true;

    public FloatBallCfg(int size, Drawable icon) {
        this(size, icon, Gravity.LEFT_TOP, 0);
    }

    public FloatBallCfg(int size, Drawable icon, Gravity gravity) {
        this(size, icon, gravity, 0);
    }

    private FloatBallCfg(int size, Drawable icon, Gravity gravity, int offsetY) {
        mSize = size;
        mIcon = icon;
        mGravity = gravity;
        mOffsetY = offsetY;
    }

    public FloatBallCfg(int size, Drawable icon, Gravity gravity, boolean hideHalfLater) {
        mSize = size;
        mIcon = icon;
        mGravity = gravity;
        mHideHalfLater = hideHalfLater;
    }

    public FloatBallCfg(int size, Drawable icon, Gravity gravity, int offsetY, boolean hideHalfLater) {
        mSize = size;
        mIcon = icon;
        mGravity = gravity;
        mOffsetY = offsetY;
        mHideHalfLater = hideHalfLater;
    }

    public void setGravity(Gravity gravity) {
        mGravity = gravity;
    }

    public void setHideHalfLater(boolean hideHalfLater) {
        mHideHalfLater = hideHalfLater;
    }

    public enum Gravity {
        @SuppressLint("RtlHardcoded") LEFT_TOP(android.view.Gravity.LEFT | android.view.Gravity.TOP),
        @SuppressLint("RtlHardcoded") LEFT_CENTER(android.view.Gravity.LEFT | android.view.Gravity.CENTER),
        @SuppressLint("RtlHardcoded") LEFT_BOTTOM(android.view.Gravity.LEFT | android.view.Gravity.BOTTOM),
        @SuppressLint("RtlHardcoded") RIGHT_TOP(android.view.Gravity.RIGHT | android.view.Gravity.TOP),
        @SuppressLint("RtlHardcoded") RIGHT_CENTER(android.view.Gravity.RIGHT | android.view.Gravity.CENTER),
        @SuppressLint("RtlHardcoded") RIGHT_BOTTOM(android.view.Gravity.RIGHT | android.view.Gravity.BOTTOM);

        int mValue;

        Gravity(int gravity) {
            mValue = gravity;
        }

        public int getGravity() {
            return mValue;
        }
    }
}

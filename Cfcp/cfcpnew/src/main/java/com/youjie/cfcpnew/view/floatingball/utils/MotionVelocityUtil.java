package com.youjie.cfcpnew.view.floatingball.utils;

import android.content.Context;
import android.view.MotionEvent;
import android.view.VelocityTracker;
import android.view.ViewConfiguration;

public class MotionVelocityUtil {
    private VelocityTracker mVelocityTracker;
    private int mMaxVelocity, mMinVelocity;

    public MotionVelocityUtil(Context context) {
        mMaxVelocity = ViewConfiguration.get(context).getScaledMaximumFlingVelocity();
        mMinVelocity = ViewConfiguration.get(context).getScaledMinimumFlingVelocity();
    }

    public int getMinVelocity() {
        return mMinVelocity < 1000 ? 1000 : mMinVelocity;
    }

    public void acquireVelocityTracker(final MotionEvent event) {
        if (null == mVelocityTracker) {
            mVelocityTracker = VelocityTracker.obtain();
        }
        mVelocityTracker.addMovement(event);
    }

    public void computeCurrentVelocity() {
        mVelocityTracker.computeCurrentVelocity(1000, mMaxVelocity);
    }

    public float getXVelocity() {
        return mVelocityTracker.getXVelocity();
    }

    public float getYVelocity() {
        return mVelocityTracker.getYVelocity();
    }

    /**
     * 释放VelocityTracker
     */
    public void releaseVelocityTracker() {
        if (null != mVelocityTracker) {
            mVelocityTracker.clear();
            mVelocityTracker.recycle();
            mVelocityTracker = null;
        }
    }
}

package com.youjie.cfcpnew.view.floatingball;

import android.app.Activity;
import android.content.Context;
import android.content.res.Configuration;
import android.graphics.Point;
import android.view.View;
import android.view.WindowManager;


import com.youjie.cfcpnew.view.floatingball.floatball.FloatBall;
import com.youjie.cfcpnew.view.floatingball.floatball.FloatBallCfg;
import com.youjie.cfcpnew.view.floatingball.floatball.StatusBarView;
import com.youjie.cfcpnew.view.floatingball.menu.FloatMenu;
import com.youjie.cfcpnew.view.floatingball.menu.FloatMenuCfg;
import com.youjie.cfcpnew.view.floatingball.menu.MenuItem;

import java.util.ArrayList;
import java.util.List;

public class FloatBallManager {
    public int mScreenWidth, mScreenHeight;

    private IFloatBallPermission mPermission;
    private OnFloatBallClickListener mFloatballClickListener;
    private WindowManager mWindowManager;
    private Context mContext;
    private FloatBall floatBall;
    private FloatMenu floatMenu;
    private StatusBarView statusBarView;
    public int floatballX, floatballY;
    private boolean isShowing = false;
    private List<MenuItem> menuItems = new ArrayList<>();
    private Activity mActivity;

    public FloatBallManager(Context application, FloatBallCfg ballCfg) {
        this(application, ballCfg, null);
    }

    private FloatBallManager(Context application, FloatBallCfg ballCfg, FloatMenuCfg menuCfg) {
        mContext = application.getApplicationContext();
        FloatBallUtil.inSingleActivity = false;
        mWindowManager = (WindowManager) mContext.getSystemService(Context.WINDOW_SERVICE);
        computeScreenSize();
        floatBall = new FloatBall(mContext, this, ballCfg);
        floatMenu = new FloatMenu(mContext, this, menuCfg);
        statusBarView = new StatusBarView(mContext, this);
    }

    public FloatBallManager(Activity activity, FloatBallCfg ballCfg) {
        this(activity, ballCfg, null);
    }

    public FloatBallManager(Activity activity, FloatBallCfg ballCfg, FloatMenuCfg menuCfg) {
        mActivity = activity;
       FloatBallUtil.inSingleActivity = true;
        mWindowManager = (WindowManager) mActivity.getSystemService(Context.WINDOW_SERVICE);
        computeScreenSize();
        floatBall = new FloatBall(mActivity, this, ballCfg);
        floatMenu = new FloatMenu(mActivity, this, menuCfg);
        statusBarView = new StatusBarView(mActivity, this);
    }

    public void buildMenu() {
        inflateMenuItem();
    }

    /**
     * 添加一个菜单条目
     */
    public FloatBallManager addMenuItem(MenuItem item) {
        menuItems.add(item);
        return this;
    }

    public int getMenuItemSize() {
        return menuItems != null ? menuItems.size() : 0;
    }

    /**
     * 设置菜单
     */
    public FloatBallManager setMenu(List<MenuItem> items) {
        menuItems = items;
        return this;
    }

    private void inflateMenuItem() {
        floatMenu.removeAllItemViews();
        for (MenuItem item : menuItems) {
            floatMenu.addItem(item);
        }
    }

    public int getBallSize() {
        return floatBall.getSize();
    }

    private void computeScreenSize() {
        Point point = new Point();
        mWindowManager.getDefaultDisplay().getSize(point);
        mScreenWidth = point.x;
        mScreenHeight = point.y;
    }

    public int getStatusBarHeight() {
        return statusBarView.getStatusBarHeight();
    }

    public void onStatusBarHeightChange() {
        floatBall.onLayoutChange();
    }

    public void show() {
        if (mActivity == null) {
            if (mPermission == null) {
                return;
            }
            if (!mPermission.hasFloatBallPermission(mContext)) {
                mPermission.onRequestFloatBallPermission();
                return;
            }
        }
        if (isShowing) return;
        isShowing = true;
        floatBall.setVisibility(View.VISIBLE);
        statusBarView.attachToWindow(mWindowManager);
        floatBall.attachToWindow(mWindowManager);
        floatMenu.detachFromWindow(mWindowManager);
    }

    public void closeMenu() {
        floatMenu.closeMenu();
    }

    public void reset() {
        floatBall.setVisibility(View.VISIBLE);
        floatBall.postSleepRunnable();
        floatMenu.detachFromWindow(mWindowManager);
    }

    public void onFloatBallClick() {
        if (menuItems != null && menuItems.size() > 0) {
            floatMenu.attachToWindow(mWindowManager);
        } else {
            if (mFloatballClickListener != null) {
                mFloatballClickListener.onFloatBallClick();
            }
        }
    }

    public void hide() {
        if (!isShowing) return;
        isShowing = false;
        floatBall.detachFromWindow(mWindowManager);
        floatMenu.detachFromWindow(mWindowManager);
        statusBarView.detachFromWindow(mWindowManager);
    }

    public void onConfigurationChanged(Configuration newConfig) {
        computeScreenSize();
        reset();
    }

    public void setPermission(IFloatBallPermission iPermission) {
        this.mPermission = iPermission;
    }

    public void setOnFloatBallClickListener(OnFloatBallClickListener listener) {
        mFloatballClickListener = listener;
    }

    public interface OnFloatBallClickListener {
        void onFloatBallClick();
    }

    public interface IFloatBallPermission {
        /**
         * request the permission of floatball,just use {@link #requestFloatBallPermission(Activity)},
         * or use your custom method.
         * @see #requestFloatBallPermission(Activity)
         */
        void onRequestFloatBallPermission();

        /**
         * detect whether allow  using floatball here or not.
         */
        boolean hasFloatBallPermission(Context context);

        /**
         * request floatball permission
         */
        void requestFloatBallPermission(Activity activity);
    }
}

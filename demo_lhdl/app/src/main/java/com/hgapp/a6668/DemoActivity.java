package com.hgapp.a6668;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.support.annotation.Nullable;
import android.support.v7.app.ActionBar;
import android.os.Bundle;
import android.os.Handler;
import android.view.MotionEvent;
import android.view.View;

import com.hgapp.a6668.base.BaseActivity2;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.http.cphttp.CPClient;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.data.CPResult;
import com.hgapp.a6668.data.LoginResult;
import com.hgapp.a6668.data.SportsPlayMethodRBResult;
import com.hgapp.a6668.demo.CPDemoFragment;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.CPBetRecordsFragment;
import com.hgapp.a6668.login.fastlogin.LoginContract;
import com.hgapp.common.util.GameLog;

import java.util.Arrays;
import java.util.List;


/**
 * An example full-screen activity that shows and hides the system UI (i.e.
 * status bar and navigation/system bar) with user interaction.
 */
public class DemoActivity extends BaseActivity2 implements LoginContract.View {
    /**
     * Whether or not the system UI should be auto-hidden after
     * {@link #AUTO_HIDE_DELAY_MILLIS} milliseconds.
     */
    private static final boolean AUTO_HIDE = true;

    /**
     * If {@link #AUTO_HIDE} is set, the number of milliseconds to wait after
     * user interaction before hiding the system UI.
     */
    private static final int AUTO_HIDE_DELAY_MILLIS = 3000;

    /**
     * Some older devices needs a small delay between UI widget updates
     * and a change of the status and navigation bar.
     */
    private static final int UI_ANIMATION_DELAY = 300;
    private final Handler mHideHandler = new Handler();
    private View mContentView;
    private final Runnable mHidePart2Runnable = new Runnable() {
        @SuppressLint("InlinedApi")
        @Override
        public void run() {
            // Delayed removal of status and navigation bar

            // Note that some of these constants are new as of API 16 (Jelly Bean)
            // and API 19 (KitKat). It is safe to use them, as they are inlined
            // at compile-time and do nothing on earlier devices.
            mContentView.setSystemUiVisibility(View.SYSTEM_UI_FLAG_LOW_PROFILE
                    | View.SYSTEM_UI_FLAG_FULLSCREEN
                    | View.SYSTEM_UI_FLAG_LAYOUT_STABLE
                    | View.SYSTEM_UI_FLAG_IMMERSIVE_STICKY
                    | View.SYSTEM_UI_FLAG_LAYOUT_HIDE_NAVIGATION
                    | View.SYSTEM_UI_FLAG_HIDE_NAVIGATION);
        }
    };
    private View mControlsView;
    private final Runnable mShowPart2Runnable = new Runnable() {
        @Override
        public void run() {
            // Delayed display of UI elements
            ActionBar actionBar = getSupportActionBar();
            if (actionBar != null) {
                actionBar.show();
            }
            mControlsView.setVisibility(View.VISIBLE);
        }
    };
    private boolean mVisible;
    private final Runnable mHideRunnable = new Runnable() {
        @Override
        public void run() {
            hide();
        }
    };
    /**
     * Touch listener to use for in-layout UI controls to delay hiding the
     * system UI. This is to prevent the jarring behavior of controls going away
     * while interacting with activity UI.
     */
    private final View.OnTouchListener mDelayHideTouchListener = new View.OnTouchListener() {
        @Override
        public boolean onTouch(View view, MotionEvent motionEvent) {
            if (AUTO_HIDE) {
                delayedHide(AUTO_HIDE_DELAY_MILLIS);
            }
            return false;
        }
    };

    LoginContract.Presenter presenter;


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        Injections.inject(this, null);
        super.onCreate(savedInstanceState);


        mVisible = true;
        mControlsView = findViewById(R.id.fullscreen_content_controls);
        mContentView = findViewById(R.id.fullscreen_content);


        // Set up the user interaction to manually show or hide the system UI.
        mContentView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                toggle();
            }
        });

        // Upon interacting with UI controls, delay any scheduled hide()
        // operations to prevent the jarring behavior of controls going away
        // while interacting with the UI.
        findViewById(R.id.dummy_button1).setOnTouchListener(mDelayHideTouchListener);
        findViewById(R.id.dummy_button2).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                presenter.postLogin(HGConstant.PRODUCT_PLATFORM, "daniel50080", "aaaqqq");
            }
        });
    }

    @Override
    public int setLayoutId() {
        return R.layout.activity_demo;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);

        // Trigger the initial hide() shortly after the activity has been
        // created, to briefly hint to the user that UI controls
        // are available.
        delayedHide(100);
    }

    private void toggle() {
        if (mVisible) {
            hide();
        } else {
            show();
        }
    }

    private void hide() {
        // Hide UI first
        ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            actionBar.hide();
        }
        mControlsView.setVisibility(View.GONE);
        mVisible = false;

        // Schedule a runnable to remove the status and navigation bar after a delay
        mHideHandler.removeCallbacks(mShowPart2Runnable);
        mHideHandler.postDelayed(mHidePart2Runnable, UI_ANIMATION_DELAY);
    }

    @SuppressLint("InlinedApi")
    private void show() {
        // Show the system bar
        mContentView.setSystemUiVisibility(View.SYSTEM_UI_FLAG_LAYOUT_FULLSCREEN
                | View.SYSTEM_UI_FLAG_LAYOUT_HIDE_NAVIGATION);
        mVisible = true;

        // Schedule a runnable to display UI elements after a delay
        mHideHandler.removeCallbacks(mHidePart2Runnable);
        mHideHandler.postDelayed(mShowPart2Runnable, UI_ANIMATION_DELAY);
    }

    /**
     * Schedules a call to hide() in delay milliseconds, canceling any
     * previously scheduled calls.
     */
    private void delayedHide(int delayMillis) {
        mHideHandler.removeCallbacks(mHideRunnable);
        mHideHandler.postDelayed(mHideRunnable, delayMillis);
    }

    @Override
    public void postCPResult(CPResult cpResult) {
        GameLog.log("联合等考虑的："+cpResult.getUrlLogin());
        ACache.get(getContext()).put(HGConstant.USERNAME_CP_INFORM,cpResult.getUrlLogin());
        ACache.get(getContext()).put(HGConstant.USERNAME_CP_URL,cpResult.getCpUrl());
        CPClient.setClientDomain(cpResult.getCpUrl());
        HGApplication.instance().configCPClient();
        Intent intent  = new Intent(getContext(),CPDemoFragment.class);
        intent.putExtra("urlLogin",cpResult.getUrlLogin());
        intent.putExtra("urlCp",cpResult.getCpUrl());
        startActivity(intent);
    }

    @Override
    public void successGet(LoginResult loginResult) {

    }

    @Override
    public void postLoginResult(LoginResult loginResult) {
        GameLog.log("登录成功之后的日志信息："+loginResult.toString());

        presenter.postCP();

    }

    @Override
    public void success(SportsPlayMethodRBResult fullPayGameResult) {

    }

    @Override
    public void setPresenter(LoginContract.Presenter presenter) {
            this.presenter = presenter;
    }
}

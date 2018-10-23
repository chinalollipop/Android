package com.hgapp.a0086.base;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.graphics.Rect;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.FrameLayout;

import com.hgapp.a0086.R;
import com.hgapp.a0086.common.widgets.GifView;
import com.hgapp.common.util.ToastUtils;
import com.zhy.autolayout.AutoLayoutActivity;
import com.zhy.autolayout.utils.AutoUtils;

import java.util.Timer;
import java.util.TimerTask;

import butterknife.ButterKnife;
import butterknife.Unbinder;
import rx.Subscription;

/**
 * Created by Windows 10 on 2016/12/9.
 */

public abstract class BaseActivity extends AutoLayoutActivity  implements IMessageView,IProgressView {


    protected Subscription mSubscription;
    protected BroadcastReceiver mBr;
    protected BroadcastReceiver mScreenLockBr, mScreenOffBr;
    private Context baseContext;
    Unbinder unbinder;
    private View layoutLoading;
    private GifView ivloading;
    @Override
    public void showMessage(String message)
    {
        ToastUtils.showLongToast(message);
    }
    public void showToast(CharSequence text){
        ToastUtils.showShortToastSafe(text);
    }
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        baseContext = this;
        if(0!= setLayoutId())
        {
            View view = LayoutInflater.from(getContext()).inflate(R.layout.fragment_base,null,false);

            FrameLayout contentLayout = (FrameLayout)view.findViewById(R.id.layout_content);
            View contentview = LayoutInflater.from(getContext()).inflate(setLayoutId(),null,false);
            contentLayout.addView(contentview);

            AutoUtils.auto(view);
            unbinder = ButterKnife.bind(this,view);
            layoutLoading = view.findViewById(R.id.layout_loading);
            ivloading = (GifView)view.findViewById(R.id.iv_loading);
            hideLoadingView();
            setEvents(savedInstanceState);
            setContentView(view);
        }
    }

    //设置布局文件的id
    public abstract  int setLayoutId();

    //初始化一些必要的数据，如第一次进来请求刷新等
    public abstract void setEvents(@Nullable Bundle savedInstanceState);
    public void hideLoadingView()
    {
        if(null == layoutLoading)
        {
            return;
        }
        if(View.VISIBLE == layoutLoading.getVisibility())
        {
            ivloading.setPaused(true);
            layoutLoading.setVisibility(View.GONE);
        }
    }


    protected int mGestureTimerLimit = 30;
    protected int timeStart = 0;
    protected boolean ifNeedShowGesture;

    protected void resetTime() {
        timeStart = 0;
    }


    protected TimerTask task;



    protected Context getContext() {
        return baseContext;
    }

    @Override
    protected void onDestroy() {

        if (mSubscription != null &&
                !mSubscription.isUnsubscribed()) {
            mSubscription.unsubscribe();
        }

        super.onDestroy();
    }

    @Override
    protected void onResume() {
        super.onResume();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
    }

    @Override
    protected void onPause() {
        super.onPause();
    }


    public class SpacesItemDecoration extends RecyclerView.ItemDecoration {
        private int space;

        public SpacesItemDecoration(int space) {
            this.space = space;
        }

        @Override
        public void getItemOffsets(Rect outRect, View view,
                                   RecyclerView parent, RecyclerView.State state) {
            outRect.right = space;
        }
    }

    protected int backGroundTime;

    protected void resetBackGroundTime() {
        backGroundTime = 0;
    }


    @Override
    protected void onRestart() {
        super.onRestart();
    }

    private boolean ifAlreadyShowGesture;
    private int mBGTimeStart = 0;
    private boolean ifAlreadyCanceled;

    protected Timer mTimer = new Timer();
    @Override
    public void onTrimMemory(int level) {
        super.onTrimMemory(level);
        if (!ifAlreadyCanceled) {
            mTimer.cancel();
        }
        ifAlreadyCanceled = true;
        mTimer = new Timer();
        mTimer.schedule(new TimerTask() {
            @Override
            public void run() {
                if (!ifAlreadyShowGesture) {
                    mBGTimeStart++;
                    if (mBGTimeStart >= mGestureTimerLimit) {
                        ifAlreadyShowGesture = true;
                        this.cancel();
                        ifAlreadyCanceled = true;
                    }
                }
            }

        }, 0, 1000);
    }

}

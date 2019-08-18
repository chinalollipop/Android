package com.sunapp.bloc.base;
import android.content.Context;
import android.graphics.Rect;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.RecyclerView;
import android.util.AttributeSet;
import android.view.LayoutInflater;
import android.view.View;
import android.view.WindowManager;
import android.widget.FrameLayout;

import com.sunapp.bloc.R;
import com.sunapp.bloc.common.widgets.GifView;
import com.sunapp.common.util.ToastUtils;
import com.jaeger.library.StatusBarUtil;
import com.zhy.autolayout.utils.AutoUtils;

import java.util.List;

import butterknife.ButterKnife;
import butterknife.Unbinder;
import me.yokeyword.fragmentation.SupportActivity;
import rx.Subscription;

/**
 * Created by Windows 10 on 2016/12/9.
 */

public abstract class BaseActivity2 extends SupportActivity implements IMessageView,IProgressView{

    protected Subscription mSubscription;
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
    public void onWindowFocusChanged(boolean hasFocus) {
        super.onWindowFocusChanged(hasFocus);
        if( hasFocus ) {
            hideNavigationBar();
        }
    }

    private void hideNavigationBar() {
        WindowManager.LayoutParams params = getWindow().getAttributes();
        params.systemUiVisibility = View.SYSTEM_UI_FLAG_HIDE_NAVIGATION|View.SYSTEM_UI_FLAG_IMMERSIVE;
        getWindow().setAttributes(params);
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        baseContext = this;
        StatusBarUtil.setColor(this, getResources().getColor(R.color.cp_status_bar));
        hideNavigationBar();
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

    protected Context getContext() {
        return baseContext;
    }

    @Override
    protected void onResume() {
        super.onResume();
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

    @Override
    public void onTrimMemory(int level) {
        super.onTrimMemory(level);
    }
    @Override
    public void setStart(int action){

    }
    @Override
    public void setError(int action,int errcode){

    }
    @Override
    public void setError(int action,String  errString){

    }
    @Override
    public void setComplete(int action){

    }


    @Override
    public View onCreateView(View parent, String name, Context context, AttributeSet attrs) {
        //初始化控制器
        if(null != presenters())
        {
            for(IPresenter presenter : presenters())
            {
                if(null != presenter)
                {
                    presenter.start();
                }
            }
        }
        return super.onCreateView(parent, name, context, attrs);
    }


    protected List<IPresenter> presenters()
    {
        return null;
    }

    @Override
    protected void onDestroy() {

        if (mSubscription != null &&
                !mSubscription.isUnsubscribed()) {
            mSubscription.unsubscribe();
        }
        //销毁控制器
        if(null != presenters())
        {
            for(IPresenter presenter : presenters())
            {
                if(null != presenter)
                {
                    presenter.destroy();
                }
            }
        }
        super.onDestroy();
    }



}

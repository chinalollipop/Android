package com.hgapp.bet365.base;
import android.content.Context;
import android.graphics.Rect;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.RecyclerView;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewGroup;

import com.hgapp.bet365.common.widgets.HGGifView;
import com.hgapp.common.util.ToastUtils;
import com.huangzj.slidingmenu.SlidingMenu;
import com.huangzj.slidingmenu.app.SlidingActivityBase;
import com.huangzj.slidingmenu.app.SlidingActivityHelper;

import butterknife.Unbinder;
import me.yokeyword.fragmentation.SupportActivity;
import rx.Subscription;

/**
 * Created by Windows 10 on 2016/12/9.
 */

public abstract class BaseActivity extends SupportActivity implements IMessageView,SlidingActivityBase {

    protected Subscription mSubscription;
    private Context baseContext;
    private View layoutLoading;
    private HGGifView ivloading;
    Unbinder unbinder;
    @Override
    public void showMessage(String message)
    {
        ToastUtils.showLongToast(message);
    }
    public void showToast(CharSequence text){
        ToastUtils.showShortToastSafe(text);
    }

    private SlidingActivityHelper mHelper;


    /* (non-Javadoc)
     * @see android.app.Activity#onPostCreate(android.os.Bundle)
     */
    @Override
    public void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);
        mHelper.onPostCreate(savedInstanceState);
    }

    /* (non-Javadoc)
     * @see android.app.Activity#findViewById(int)
     */
    @Override
    public View findViewById(int id) {
        View v = super.findViewById(id);
        if (v != null)
            return v;
        return mHelper.findViewById(id);
    }

    /* (non-Javadoc)
     * @see android.app.Activity#onSaveInstanceState(android.os.Bundle)
     */
    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        mHelper.onSaveInstanceState(outState);
    }

    /* (non-Javadoc)
     * @see android.app.Activity#setContentView(int)
     */
    @Override
    public void setContentView(int id) {
        setContentView(getLayoutInflater().inflate(id, null));
    }

    /* (non-Javadoc)
     * @see android.app.Activity#setContentView(android.view.View)
     */
    @Override
    public void setContentView(View v) {
        setContentView(v, new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
    }

    /* (non-Javadoc)
     * @see android.app.Activity#setContentView(android.view.View, android.view.ViewGroup.LayoutParams)
     */
    @Override
    public void setContentView(View v, ViewGroup.LayoutParams params) {
        super.setContentView(v, params);
        mHelper.registerAboveContentView(v, params);
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#setBehindContentView(int)
     */
    public void setBehindContentView(int id) {
        setBehindContentView(getLayoutInflater().inflate(id, null));
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#setBehindContentView(android.view.View)
     */
    public void setBehindContentView(View v) {
        setBehindContentView(v, new ViewGroup.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.MATCH_PARENT));
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#setBehindContentView(android.view.View, android.view.ViewGroup.LayoutParams)
     */
    public void setBehindContentView(View v, ViewGroup.LayoutParams params) {
        mHelper.setBehindContentView(v, params);
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#getSlidingMenu()
     */
    public SlidingMenu getSlidingMenu() {
        return mHelper.getSlidingMenu();
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#toggle()
     */
    public void toggle() {
        mHelper.toggle();
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#showAbove()
     */
    public void showContent() {
        mHelper.showContent();
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#showBehind()
     */
    public void showMenu() {
        mHelper.showMenu();
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#showSecondaryMenu()
     */
    public void showSecondaryMenu() {
        mHelper.showSecondaryMenu();
    }

    /* (non-Javadoc)
     * @see com.jeremyfeinstein.slidingmenu.lib.app.SlidingActivityBase#setSlidingActionBarEnabled(boolean)
     */
    public void setSlidingActionBarEnabled(boolean b) {
        mHelper.setSlidingActionBarEnabled(b);
    }

    /* (non-Javadoc)
     * @see android.app.Activity#onKeyUp(int, android.view.KeyEvent)
     */
    @Override
    public boolean onKeyUp(int keyCode, KeyEvent event) {
        boolean b = mHelper.onKeyUp(keyCode, event);
        if (b) return b;
        return super.onKeyUp(keyCode, event);
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mHelper = new SlidingActivityHelper(this);
        mHelper.onCreate(savedInstanceState);
        baseContext = this;
        /*if(0!= setLayoutId())
        {
            View view = LayoutInflater.from(getContext()).inflate(R.layout.fragment_base,null,false);

            FrameLayout contentLayout = (FrameLayout)view.findViewById(R.id.layout_content);
            View contentview = LayoutInflater.from(getContext()).inflate(setLayoutId(),null,false);
            contentLayout.addView(contentview);

            AutoUtils.auto(view);
            unbinder = ButterKnife.bind(this,view);
            layoutLoading = view.findViewById(R.id.layout_loading);
            layoutLoading.setOnClickListener(null);
            ivloading = (GifView)view.findViewById(R.id.iv_loading);
            hideLoadingView();
            setEvents(savedInstanceState);
        }*/
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

}

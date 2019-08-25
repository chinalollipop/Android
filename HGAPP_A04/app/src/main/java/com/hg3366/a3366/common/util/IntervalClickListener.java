package com.hg3366.a3366.common.util;

import android.view.View;

import com.hg3366.common.util.Timber;

/**
 * Created by Nereus on 2017/9/7.
 * 避免重复点击视图(按钮/文本框等等)
 */

public abstract class IntervalClickListener implements  View.OnClickListener{

    private static final long DEFAULT_INTERVAL=1*1000;
    private long lastClickTime = 0;
    public void onClick(View v)
    {
        long curClickTime = System.currentTimeMillis();
        long interval = curClickTime - lastClickTime;
        Timber.d("click interval:%d",interval);
        if( interval >= getInterval())
        {
            lastClickTime = curClickTime;
            Timber.d("onIntervalClick is triggered");
            onIntervalClick(v);
        }
    }

    protected long getInterval()
    {
        return DEFAULT_INTERVAL;
    }

    protected abstract void onIntervalClick(View view);
}

package com.hg3366.a3366.common.widgets;

import android.content.Context;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.widget.RelativeLayout;

/**
 * Created by Nereus on 2017/8/5.
 */

public class   LoadingViewGroup extends RelativeLayout {
    public LoadingViewGroup(Context context) {
        super(context);
        init();
    }

    public LoadingViewGroup(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();
    }

    public LoadingViewGroup(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
        init();
    }

    @Override
    public boolean onInterceptTouchEvent(MotionEvent ev)
    {
        return true;
    }

    private void init()
    {
        /*setBackgroundColor(getResources().getColor(R.color.n_app_bg));
        setOnTouchListener(new OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                return true;
            }
        });*/
    }
}

package com.qpweb.a01.widget;

import android.content.Context;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;

public class ScareImageView extends android.support.v7.widget.AppCompatImageView implements View.OnTouchListener{
    public ScareImageView(Context context) {
        super(context);
    }

    public ScareImageView(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public ScareImageView(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }

    @Override
    public boolean onTouch(View view, MotionEvent event) {
        switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN:
                view.setScaleX(1.1f);
                view.setScaleY(1.1f);
                break;
            case MotionEvent.ACTION_UP:
                view.setScaleX((float) 0.95);
                view.setScaleY((float) 0.95);
                break;
        }
        return false;
    }
}

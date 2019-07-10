package com.qpweb.a01.widget;

import android.content.Context;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;
import android.widget.ImageView;

public class SImageView extends ImageView implements View.OnTouchListener{
    public SImageView(Context context) {
        super(context);
    }

    public SImageView(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public SImageView(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }

    @Override
    public boolean onTouch(View v, MotionEvent event) {
        switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN:
                this.setScaleX(1.1f);
                this.setScaleY(1.1f);
                break;
            case MotionEvent.ACTION_UP:
                this.setScaleX((float) 0.91);
                this.setScaleY((float) 0.91);
                break;
        }
        return false;
    }
}

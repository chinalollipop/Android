package com.sands.common.widgets;


import android.content.Context;
import android.util.AttributeSet;
import android.widget.TextView;

/**
 * Created by ak on 2017/5/19.
 * 跑马灯特效自定义控件
 */

public class FocusedTextView extends TextView {
    public FocusedTextView(Context context, AttributeSet attrs, int defStyle){
        super(context, attrs, defStyle);
    }

    public FocusedTextView(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public FocusedTextView(Context context) {
        super(context);
    }

    //重写isFocused方法，让其一直返回true
    public boolean isFocused() {
        return true;
    }
}

package com.sunapp.bloc.common.widgets;

import android.content.Context;
import android.util.AttributeSet;
import android.widget.ListView;

/**
 * Created by ak on 2017/8/2.
 * 从新计算高度，防止跟ListView 的自适配冲突
 */

public class NListView  extends ListView{
    public NListView(Context context) {
        super(context);
    }

    public NListView(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public NListView(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }

    @Override
    protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {
        int expendSpec = MeasureSpec.makeMeasureSpec(Integer.MAX_VALUE >> 2,MeasureSpec.AT_MOST);
        super.onMeasure(widthMeasureSpec, expendSpec);
    }
}

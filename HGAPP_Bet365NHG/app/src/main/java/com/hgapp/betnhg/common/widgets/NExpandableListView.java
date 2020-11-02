package com.hgapp.betnhg.common.widgets;

import android.content.Context;
import android.util.AttributeSet;
import android.widget.ExpandableListView;

public class NExpandableListView extends ExpandableListView {
    public NExpandableListView(Context context) {
        super(context);
    }

    public NExpandableListView(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public NExpandableListView(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }

    public NExpandableListView(Context context, AttributeSet attrs, int defStyleAttr, int defStyleRes) {
        super(context, attrs, defStyleAttr, defStyleRes);
    }

    @Override
    protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {
        int expendSpec = MeasureSpec.makeMeasureSpec(Integer.MAX_VALUE >> 2,MeasureSpec.AT_MOST);
        super.onMeasure(widthMeasureSpec, expendSpec);
    }
}

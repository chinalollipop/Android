package com.hgapp.betnhg.common.widgets;

import android.content.Context;
import android.support.v4.widget.DrawerLayout;
import android.util.AttributeSet;

import android.annotation.TargetApi;
import android.os.Build;
import android.view.ViewGroup;

import com.zhy.autolayout.AutoLayoutInfo;
import com.zhy.autolayout.AutoLinearLayout;
import com.zhy.autolayout.utils.AutoLayoutHelper;

public class HGDrawerLayout2 extends DrawerLayout {
    private final AutoLayoutHelper mHelper = new AutoLayoutHelper(this);

    public HGDrawerLayout2(Context context) {
        super(context);
    }

    public HGDrawerLayout2(Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    @TargetApi(Build.VERSION_CODES.HONEYCOMB)
    public HGDrawerLayout2(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }

    @Override
    protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec)
    {
        if (!isInEditMode())
        {
            mHelper.adjustChildren();
        }
        super.onMeasure(widthMeasureSpec, heightMeasureSpec);
    }

    @Override
    protected void onLayout(boolean changed, int l, int t, int r, int b)
    {
        super.onLayout(changed, l, t, r, b);
    }


    @Override
    protected ViewGroup.LayoutParams generateDefaultLayoutParams() {
        return new HGDrawerLayout.LayoutParams(DrawerLayout.LayoutParams.MATCH_PARENT, DrawerLayout.LayoutParams.MATCH_PARENT);
    }

    @Override
    public LayoutParams generateLayoutParams(AttributeSet attrs)
    {
        return new HGDrawerLayout2.LayoutParams(getContext(), attrs);
    }


    public static class LayoutParams extends AutoLinearLayout.LayoutParams
            implements AutoLayoutHelper.AutoLayoutParams
    {
        private AutoLayoutInfo mAutoLayoutInfo;

        public LayoutParams(Context c, AttributeSet attrs)
        {
            super(c, attrs);
            mAutoLayoutInfo = AutoLayoutHelper.getAutoLayoutInfo(c, attrs);
        }

        @Override
        public AutoLayoutInfo getAutoLayoutInfo()
        {
            return mAutoLayoutInfo;
        }


        public LayoutParams(int width, int height)
        {
            super(width, height);
        }


        public LayoutParams(ViewGroup.LayoutParams source)
        {
            super(source);
        }

        public LayoutParams(MarginLayoutParams source)
        {
            super(source);
        }

    }
}

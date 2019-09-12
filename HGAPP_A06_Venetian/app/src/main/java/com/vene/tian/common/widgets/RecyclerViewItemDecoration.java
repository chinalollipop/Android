package com.vene.tian.common.widgets;

import android.graphics.Canvas;
import android.graphics.Rect;
import android.graphics.drawable.ColorDrawable;
import android.support.annotation.ColorInt;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutCompat;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;

/**
 * Created by ak on 2017/5/29.
 */

public class RecyclerViewItemDecoration extends RecyclerView.ItemDecoration {
    private final int orientation;//方向
    private final int decoration;//边距大小 px
    private final int lineSize ;//分割线厚度
    private final ColorDrawable mDivider;

    public RecyclerViewItemDecoration(@LinearLayoutCompat.OrientationMode int orientation, int decoration, @ColorInt int color, int lineSize) {
        mDivider = new ColorDrawable(color);
        this.orientation = orientation;
        this.decoration = decoration;
        this.lineSize = lineSize;
    }

    @Override
    public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
        super.getItemOffsets(outRect, view, parent, state);
        final RecyclerView.LayoutManager layoutManager = parent.getLayoutManager();
        final int lastPosition = state.getItemCount() -1;//整个RecyclerView最后一个item的position
        final int current = parent.getChildLayoutPosition(view);//获取当前要进行布局的item的position
        if (current == -1) return;
        if (layoutManager instanceof LinearLayoutManager && !(layoutManager instanceof GridLayoutManager)) {//LinearLayoutManager
            if (orientation == LinearLayoutManager.VERTICAL) {//垂直
                if (current == lastPosition) {//判断是否为最后一个item
                    outRect.set(0, 0, 0, 0);
                } else {
                    outRect.set(0, 0, 0, decoration);
                }
            } else {//水平
                if (current == lastPosition) {//判断是否为最后一个item
                    outRect.set(0, 0, 0, 0);
                } else {
                    outRect.set(0, 0, decoration, 0);
                }
            }
        }
    }

    /**
     * 绘制装饰
     */
    @Override
    public void onDraw(Canvas c, RecyclerView parent, RecyclerView.State state) {
        super.onDraw(c, parent, state);
        if (orientation == LinearLayoutManager.VERTICAL) {//垂直
            drawHorizontalLines(c, parent);
        } else {//水平
            drawVerticalLines(c, parent);
        }
    }

    /**
     * 绘制垂直布局 水平分割线
     */
    private void drawHorizontalLines(Canvas c, RecyclerView parent) {
        //  final int itemCount = parent.getChildCount()-1;//出现问题的地方  下面有解释
        final int itemCount = parent.getChildCount();
        Log.e("item","---->"+itemCount);
        final int left = parent.getPaddingLeft();
        final int right = parent.getWidth() - parent.getPaddingRight();
        for (int i = 0; i < itemCount; i++) {
            final View child = parent.getChildAt(i);
            if (child == null) return;
            final RecyclerView.LayoutParams params = (RecyclerView.LayoutParams) child.getLayoutParams();
            final int top = child.getBottom() + params.bottomMargin;
            final int bottom = top +lineSize;
            mDivider.setBounds(left, top, right, bottom);
            mDivider.draw(c);
        }
    }

    /**
     * 绘制水平布局 竖直的分割线
     */
    private void drawVerticalLines(Canvas c, RecyclerView parent) {
        final int itemCount = parent.getChildCount();
        final int top = parent.getPaddingTop();
        for (int i = 0; i < itemCount; i++) {
            final View child = parent.getChildAt(i);
            if (child == null) return;
            final RecyclerView.LayoutParams params = (RecyclerView.LayoutParams) child.getLayoutParams();
            final int bottom = child.getHeight() - parent.getPaddingBottom();
            final int left = child.getRight() + params.rightMargin;
            final int right = left +lineSize;
            if (mDivider == null) return;
            mDivider.setBounds(left, top, right, bottom);
            mDivider.draw(c);
        }
    }
}

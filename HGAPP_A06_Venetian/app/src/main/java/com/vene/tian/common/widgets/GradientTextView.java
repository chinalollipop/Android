package com.vene.tian.common.widgets;


import android.content.Context;
import android.graphics.Canvas;
import android.graphics.LinearGradient;
import android.graphics.Paint;
import android.graphics.Rect;
import android.graphics.Shader;
import android.support.annotation.Nullable;
import android.support.v7.widget.AppCompatTextView;
import android.util.AttributeSet;

/**
 * Created by ak on 2017/6/28.
 * 自定义TextView，设置Textview文字颜色渐变
 */

public class GradientTextView extends AppCompatTextView {
    private LinearGradient mLinearGradient;
    private Paint mPaint;
    private int mViewWidth = 0;
    private Rect mTextBound = new Rect();

    public GradientTextView(Context context) {
        super(context);
    }

    public GradientTextView(Context context, @Nullable AttributeSet attrs) {
        super(context, attrs);
    }

    public GradientTextView(Context context, @Nullable AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
    }


    @Override
    protected void onDraw(Canvas canvas) {
        //super.onDraw(canvas);
        mViewWidth = getMeasuredWidth();
        mPaint = getPaint();
        String mTipText = getText().toString().trim();
        mPaint.getTextBounds(mTipText,0,mTipText.length(),mTextBound);
        mLinearGradient = new LinearGradient(0,0,mViewWidth,0,new int[]{0xFF333333,0xFF333333 },null, Shader.TileMode.REPEAT);
        mPaint.setShader(mLinearGradient);
        canvas.drawText(mTipText,getMeasuredWidth()/2-mTextBound.width()/2,getMeasuredHeight()/2+mTextBound.height()/2,mPaint);
    }
}

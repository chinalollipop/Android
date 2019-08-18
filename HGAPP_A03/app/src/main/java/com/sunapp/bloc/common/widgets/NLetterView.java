package com.sunapp.bloc.common.widgets;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Rect;
import android.graphics.Typeface;
import android.support.annotation.Nullable;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;
import android.widget.TextView;

import com.sunapp.common.util.DensityUtil;
import com.sunapp.common.util.GameLog;
import com.sunapp.common.util.Timber;

import java.util.Timer;
import java.util.TimerTask;


/**
 * Created by ak on 2017/5/27.
 * 银行选择/城市选择等界面
 * {@linkplain Timber}
 */

public class NLetterView extends View {
    /*private static String[] letters = {
            "定位","热门", "A", "B", "C", "D",
            "E", "F", "G", "H","J", "K", "L", "M", "N","P", "Q",
            "R", "S", "T","W", "X", "Y", "Z"
    };*/
    private static String[] letters = {
            "A", "B", "C", "D","E", "F", "G",
            "H", "J", "K", "L", "M", "N","P", "Q",
            "R", "S", "T","W", "X", "Y", "Z"
    };

    private float itemHeight;
    private float itemWidth;
    private Paint mPaint;
    private boolean isShowBg = false;
    private int currentPosition = -1;
    private TextView textView;
    private Timer timer;

    //侧栏字母的颜色
    private final int letters_color = Color.parseColor("#727272");
    //侧栏字母背景的颜色
    private final int letters_bg_color = Color.parseColor("#20000000");

    public NLetterView(Context context) {
        this(context,null);
    }

    public NLetterView(Context context, @Nullable AttributeSet attrs) {
        this(context, attrs,0);
    }

    public NLetterView(Context context, @Nullable AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
        init(context);
    }

    private void init(Context context) {
        mPaint = new Paint(Paint.ANTI_ALIAS_FLAG);
        mPaint.setTypeface(Typeface.DEFAULT);
        mPaint.setTextSize(DensityUtil.dip2px(context,14));
        mPaint.setColor(letters_color);
    }

    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);
        if(isShowBg){
            canvas.drawColor(letters_bg_color);
        }

        for (int i = 0; i < letters.length; i++) {
            String letter = letters[i];
            float x = itemWidth / 2 - mPaint.measureText(letter) / 2;
            Rect bounds = new Rect();
            mPaint.getTextBounds(letter,0,letter.length(), bounds);
            float y = bounds.height() / 2 + itemHeight / 2 + ( i * itemHeight);
            canvas.drawText(letter,x,y,mPaint);
        }

    }

    @Override
    public boolean onTouchEvent(MotionEvent event) {
        int position = (int) (event.getY() / itemHeight);
        GameLog.log("onTouchEvent "+position + "");
        switch (event.getAction()){
            case MotionEvent.ACTION_DOWN:
            case MotionEvent.ACTION_MOVE:
                if(timer != null) timer.cancel();
                isShowBg = true;
                if(currentPosition != position){
                    if(position >= 0 && position < letters.length){
                        if(mSlidingListaner != null){
                            mSlidingListaner.onSliding(letters[position]);
                        }

                        currentPosition = position;
                        if(textView != null){
                            textView.setVisibility(VISIBLE);
                            textView.setText(letters[position]);
                        }

                    }
                    invalidate();
                }

                break;
            case MotionEvent.ACTION_UP:
                isShowBg = false;
                currentPosition = -1;
                if(textView != null){
                    timer = new Timer(false);
                    timer.schedule(new TimerTask() {
                        @Override
                        public void run() {
                            textView.post(new TimerTask() {
                                @Override
                                public void run() {
                                    textView.setVisibility(GONE);
                                }
                            });

                        }
                    },400);
                }
                invalidate();
                break;
        }
        return true;

    }

    @Override
    protected void onSizeChanged(int w, int h, int oldw, int oldh) {
        super.onSizeChanged(w, h, oldw, oldh);
        int viewHeight = getMeasuredHeight();
        //计算宽高
        itemHeight = viewHeight / (letters.length * 1.0f);
        itemWidth = getMeasuredWidth();
    }

    private OnSlidingListaner mSlidingListaner;

    public void setOnSlidingListaner(OnSlidingListaner slidingListaner){
        this.mSlidingListaner = slidingListaner;
    }
    public interface  OnSlidingListaner{
        void onSliding(String letter);
    }

    public void setText(TextView textView){

        this.textView = textView;
    }
}

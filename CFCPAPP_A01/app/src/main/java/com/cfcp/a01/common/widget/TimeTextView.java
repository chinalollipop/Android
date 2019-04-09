package com.cfcp.a01.common.widget;

import android.content.Context;
import android.graphics.Color;
import android.os.CountDownTimer;
import android.text.SpannableString;
import android.text.Spanned;
import android.text.style.ForegroundColorSpan;
import android.util.AttributeSet;
import android.util.Log;
import android.widget.TextView;

import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

/**
 * 自带倒计时效果的TextView
 * @author Iven
 * @version 1.0
 * @Date 2016/9/3 21:55
 * Email: yanfengqiu06@163.com
 */
public class TimeTextView extends TextView {
    private long leftTime;//剩余的时间
    private long endTime;//倒计时，要结束的时间
    private int mTextColor;//TextView的字体的颜色
    private float mTextSize;//TextView的字体的大小
    private boolean isRun = true;//是否启动倒计时
    private boolean isEnd = false;//是否已经结束
    private String endInfo;//倒计时结束的提示语
    private String headInfo="";//前边的提示语
    private final String TAG = "TimeTextView";
    private boolean isStyleable = false;//设置是否对倒计时的时间进行特殊颜色处理
    private int partOfTextViewColor;//倒计时的数字部分的颜色
    private onCountDownListener listener;//事件监听接口
    private boolean isListener;
    private ScheduledExecutorService executorService;

    public TimeTextView(Context context) {
        super(context);
        endInfo = "";
    }

    public TimeTextView(Context context, AttributeSet attrs) {
        super(context, attrs);
        endInfo = "";
    }
    public interface onCountDownListener{
        void onTimeOverListener();
    }

    /**
     * 设置剩余的时间，用来实现倒计时的时间
     * @param leftTime long型的倒计时时间
     * @param listener 倒计时结束的事件监听
     */
    public void setLeftTime(long leftTime,boolean isListener,onCountDownListener listener) {
        this.leftTime = leftTime;
        this.isListener = isListener;
        this.listener = listener;
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        executorService = Executors.newScheduledThreadPool(1);
        setMain();
    }

    /**
     * 设置倒计时的结束时间，实现倒计时功能
     * @param endTime 倒计时结束时间
     */
    public void setEndTime(long endTime,boolean isListener){
        this.endTime = endTime;
        this.isListener = isListener;
        leftTime = endTime - getSystemCurrentTime();
        setLeftTime(leftTime,isListener,listener);
    }

    /**
     * 设置是否以特殊颜色显示倒计时的时间
     * @param styleable true：多样式  false：单样式
     */
    public void setIsStyleable(boolean styleable){
        this.isStyleable = styleable;
    }

    /**
     * 设置倒计时时间部分的特殊的颜色
     * @param red
     * @param green
     * @param blue
     */
    public void setPartColor(int red,int green,int blue){
        this.partOfTextViewColor = Color.rgb(red,green,blue);
    }
    /**
     * 获得系统的当前时间
     */
    private long getSystemCurrentTime(){
        long currentTimeMillis = System.currentTimeMillis();
        Log.i(TAG, "当前系统时间："+currentTimeMillis);
        return currentTimeMillis;
    }
    /**
     * 实现倒计时的功能
     */
    private void setMain(){
        if (isRun && !isEnd) {
            if (!isStyleable) {//单样式
                executorService.scheduleAtFixedRate(new Runnable() {
                    @Override
                    public void run() {
                       // TimeTextView.this.setText(headInfo + ":"+ FormatMiss(l / 1000));//格式化时间并显示

                        if (leftTime-- <= 0) {
                            post(new Runnable() {
                                @Override
                                public void run() {
                                    if (null != executorService) {
                                        executorService.shutdownNow();
                                        executorService.shutdown();
                                        executorService = null;
                                    }
                                    isEnd = true;//设置为已经结束了Boolean
                                    isRun = false;//设置为停止运行
                                    TimeTextView.this.setText(endInfo);
                                    if (isListener) {
                                        listener.onTimeOverListener();
                                    }
                                }
                            });

                        } else {
                            post(new Runnable() {
                                @Override
                                public void run() {
                                    TimeTextView.this.setText(headInfo + ":"+ FormatMiss(leftTime));//格式化时间并显示

                                }
                            });
                        }

                    }
                    }, 0, 1000, TimeUnit.MILLISECONDS);

                /*new CountDownTimer(leftTime*1000+50, 1000) {
                    @Override
                    public void onTick(long l) {
                        TimeTextView.this.setText(headInfo + ":"+ FormatMiss(l / 1000));//格式化时间并显示
                    }

                    @Override
                    public void onFinish() {
                        isEnd = true;//设置为已经结束了Boolean
                        isRun = false;//设置为停止运行
                        TimeTextView.this.setText(endInfo);
                        if (isListener) {
                            listener.onTimeOverListener();
                        }
                    }
                }.start();*/
            } else {
                executorService.scheduleAtFixedRate(new Runnable() {
                    @Override
                    public void run() {
                       // TimeTextView.this.setText(headInfo + ":"+ FormatMiss(l / 1000));//格式化时间并显示

                        if (leftTime-- <= 0) {
                            post(new Runnable() {
                                @Override
                                public void run() {
                                    if (null != executorService) {
                                        executorService.shutdownNow();
                                        executorService.shutdown();
                                        executorService = null;
                                    }
                                    isEnd = true;//设置为已经结束了Boolean
                                    isRun = false;//设置为停止运行
                                    TimeTextView.this.setText(endInfo);
                                    if (isListener) {
                                        listener.onTimeOverListener();
                                    }
                                }
                            });

                        } else {
                            post(new Runnable() {
                                @Override
                                public void run() {
                                    //TimeTextView.this.setText(headInfo + ":"+ FormatMiss(leftTime));//格式化时间并显示
                                    String str = headInfo + ":"+FormatMiss(leftTime/1000);
                                    int lastIndexOf = str.lastIndexOf(":");
                                    SpannableString ssb = new SpannableString(str);
                                    ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+1,lastIndexOf+3, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                                    ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+4,lastIndexOf+6, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                                    ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+7,lastIndexOf+9, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                                    ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+10,lastIndexOf+12, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                                    TimeTextView.this.setText(ssb);

                                }
                            });
                        }

                    }
                }, 0, 1000, TimeUnit.MILLISECONDS);

                /*new CountDownTimer(leftTime, 1000) {
                    @Override
                    public void onTick(long l) {
                        String str = headInfo + ":"+FormatMiss(l/1000);
                        int lastIndexOf = str.lastIndexOf(":");
                        SpannableString ssb = new SpannableString(str);
                        ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+1,lastIndexOf+3, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                        ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+4,lastIndexOf+6, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                        ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+7,lastIndexOf+9, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                        ssb.setSpan(new ForegroundColorSpan(partOfTextViewColor),lastIndexOf+10,lastIndexOf+12, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE);
                        TimeTextView.this.setText(ssb);
                    }

                    @Override
                    public void onFinish() {
                        isEnd = true;//设置为已经结束了Boolean
                        isRun = false;//设置为停止运行
                        TimeTextView.this.setText(endInfo);
                        if (isListener) {
                            listener.onTimeOverListener();
                        }
                    }
                }.start();*/

            }
        } else {
            return;
        }
    }

    /**
     * 设置倒计时结束的提示语
     *
     * @param endInfoStr 提示信息
     */
    public void setEndInfo(String endInfoStr) {
        this.endInfo = endInfoStr;
    }

    /**
     * 设置倒计时的提示语
     *
     * @param headInfo 提示信息
     */
    public void setHeadInfo(String headInfo) {
        this.headInfo = headInfo;
    }

    /**
     * 设置全部字体颜色
     * 可以通过Color.rgb()方法直接填入16进制的值来确定颜色
     * @param colorId 颜色int值
     */
    public void setAllTimeTextColor(int colorId) {
        this.mTextColor = colorId;
    }

    /**
     * 设置全部字体颜色
     * @param red 红色的16进制值
     * @param green 绿色的16进制值
     * @param blue 蓝色的16进制值
     */
    public void setAllTimeTextColor(int red,int green,int blue) {
        int rgb = Color.rgb(red, green, blue);
        this.mTextColor = rgb;
        TimeTextView.this.setTextColor(rgb);
    }

    /**
     * 设置全部字体的大小
     *
     * @param textSize 字体大小
     */
    public void setAllTimeTextSize(float textSize) {
        this.mTextSize = textSize;
        TimeTextView.this.setTextSize(mTextSize);
    }

    /**
     * 格式化时间的显示格式
     * @param miss long型的毫秒数
     * @return 09天09时09分09秒
     */
    public static String FormatMiss(long miss) {
        String day = miss / 3600 / 24 > 9 ? miss / 3600 / 24 + "" : "0" + miss / 3600 / 24;
        String hh = (miss % (3600 * 24)) / 3600 > 9 ? (miss % (3600 * 24)) / 3600 + ""
                : "0" + (miss % (3600 * 24)) / 3600;
        String mm = (miss % 3600) / 60 > 9 ? (miss % 3600) / 60 + "" : "0" + (miss % 3600) / 60;
        String ss = (miss % 3600) % 60 > 9 ? (miss % 3600) % 60 + "" : "0" + (miss % 3600) % 60;
        // TODO只将时间用颜色显示出来
        //return day + "天" + hh + "时" + mm + "分" + ss + "秒";
        return  hh + ":" + mm + ":" + ss;
    }

    /**
     * 格式化时间的显示格式
     * @param miss long型的毫秒数
     * @param red 红色
     * @param green 绿色
     * @param blue 蓝色
     * @return 09天09时09分09秒
     */
    public static String FormatMiss(long miss, int red, int green, int blue) {
        String day = miss / 3600 / 24 > 9 ? miss / 3600 / 24 + "" : "0" + miss / 3600 / 24;
        String hh = (miss % (3600 * 24)) / 3600 > 9 ? (miss % (3600 * 24)) / 3600 + ""
                : "0" + (miss % (3600 * 24)) / 3600;
        String mm = (miss % 3600) / 60 > 9 ? (miss % 3600) / 60 + "" : "0" + (miss % 3600) / 60;
        String ss = (miss % 3600) % 60 > 9 ? (miss % 3600) % 60 + "" : "0" + (miss % 3600) % 60;
        // TODO只将时间用颜色显示出来
        String timeStr = day + "天" + hh + "时" + mm + "分" + ss + "秒";
        SpannableString ssb = new SpannableString(timeStr);
        //
        return day + "天" + hh + "时" + mm + "分" + ss + "秒";
    }
}

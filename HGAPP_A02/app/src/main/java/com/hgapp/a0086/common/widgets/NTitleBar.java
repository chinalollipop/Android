package com.hgapp.a0086.common.widgets;

import android.content.Context;
import android.content.res.TypedArray;
import android.graphics.drawable.Drawable;
import android.text.TextUtils;
import android.util.AttributeSet;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.hgapp.a0086.R;
import com.hgapp.common.util.Check;


/**
 * Created by Danie; on 2018/7/9.
 * 自定义公共标题控件
 *
 */

public class NTitleBar extends RelativeLayout {
    //是否显示返回按钮的图标
    private boolean titleTextShow;
    //是否显示返回按钮的图标
    private boolean backImageShow;
    //标题文字
    private String titleText;
    //返回按钮的文字
    private String backText;
    //标题文字的颜色
    private int textColor;
    //标题更多文字的颜色
    private int moreColor;
    //是否显示更多的图标
    private boolean moreImageShow;
    //更多的文字
    private String moreText;
    //返回按钮
    private TextView tvBackText;
    //标题
    private TextView tvTitle;
    //更多
    private TextView tvMore;
    //默认显示更多的图标
    private int moreImage;
    //只显示更多文本
    private boolean moreTextOnly;
    public NTitleBar(Context context) {
        super(context);
    }

    public NTitleBar(Context context, AttributeSet attrs) {
        super(context, attrs);
        LayoutInflater.from(context).inflate(R.layout.n_title_bar, this);
        TypedArray ta = context.obtainStyledAttributes(attrs, R.styleable.NTitleBar, 0, 0);
        try {
            titleText = ta.getString(R.styleable.NTitleBar_titleText);
            titleTextShow = ta.getBoolean(R.styleable.NTitleBar_titleTextShow, true);
            backImageShow = ta.getBoolean(R.styleable.NTitleBar_backImageShow, true);
            moreImageShow = ta.getBoolean(R.styleable.NTitleBar_moreImageShow, true);
            backText = ta.getString(R.styleable.NTitleBar_backText);
            moreText = ta.getString(R.styleable.NTitleBar_moreText);
            moreImage = ta.getResourceId(R.styleable.NTitleBar_moreImage,R.mipmap.title_right);
            textColor = ta.getColor(R.styleable.NTitleBar_titleColor,context.getResources().getColor(R.color.title_text));
            moreColor = ta.getColor(R.styleable.NTitleBar_moreColor,context.getResources().getColor(R.color.title_text));
            moreTextOnly=ta.getBoolean(R.styleable.NTitleBar_moreTextOnly, false);
            setUpView(context);
        } finally {
            ta.recycle();
        }
    }

    //代码里面更改更多的图标
    public void setMoreImage(Drawable drawable){
        if(null==drawable){
            tvMore.setCompoundDrawables(null,null,null,null);
            return;
        }
        drawable.setBounds(0, 0, drawable.getMinimumWidth(), drawable.getMinimumHeight());
        tvMore.setCompoundDrawables(drawable,null,null,null);
    }


    private void setUpView(Context context){
        tvBackText = (TextView)findViewById(R.id.iv_com_title_back);
        tvTitle = (TextView)findViewById(R.id.tv_com_title_name);
        tvMore = (TextView)findViewById(R.id.tv_com_title_more);
        tvTitle.setTextColor(textColor);
        tvMore.setTextColor(moreColor);
        //返回按钮是否显示
        if(!titleTextShow){
            tvTitle.setVisibility(View.GONE);
        }
        if(!backImageShow){
            tvBackText.setVisibility(View.GONE);
        }
        //更多图标或者更多文字是否显示，如果只显示文字，可以设置moreImage为一个背景色即可，当然这里可以改成一个TextView和ImageView一起来做，但是目前已经满足需求了，如果后期有需要再重写
        if (!moreImageShow){
            tvMore.setVisibility(View.GONE);
        }
        if(!moreTextOnly)
        {
            Drawable drawable = context.getResources().getDrawable(moreImage);
            /// 获取图标的大小，这一步必须要做,否则不会显示
            drawable.setBounds(0, 0, drawable.getMinimumWidth(), drawable.getMinimumHeight());
            tvMore.setCompoundDrawables(drawable,null,null,null);
        }
        tvBackText.setText(backText);
        tvMore.setText(moreText);
        tvTitle.setText(titleText);
        //tvTitle.setTextSize(TypedValue.COMPLEX_UNIT_PX,getResources().getInteger(R.integer.titlebar_text_size));
    }

    /**
     * 设置标题
     * @param title 标题
     */
    public void setTitle(String title){
        titleText = title;
        tvTitle.setText(title);
    }

    /**
     * 设置返回按钮的文字
     * @param backText 返回按钮的文字
     */
    public void setBackText(String backText){
        backText = backText;
        tvBackText.setText(backText);
    }

    /**
     * 设置扩展消息
     * @param moreText 扩展消息
     */
    public void setMoreText(String moreText){
        moreText = moreText;
        tvMore.setText(moreText);
    }

    /**
     * 设置返回消息事件
     * @param listener 返回消息listener
     */
    public void setBackListener(OnClickListener listener){
        tvBackText.setOnClickListener(listener);
    }

    /**
     * 设置扩展事件
     * @param listener 扩展事件listener
     */
    public void setMoreListener(OnClickListener listener){
        if (!TextUtils.isEmpty(moreText)||!Check.isNull(moreImage)) {
            tvMore.setOnClickListener(listener);
        }
    }
}

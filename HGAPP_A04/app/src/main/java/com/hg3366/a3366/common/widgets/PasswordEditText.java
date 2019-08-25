package com.hg3366.a3366.common.widgets;

import android.annotation.SuppressLint;
import android.content.Context;
import android.graphics.drawable.Drawable;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;
import android.widget.EditText;

import com.hg3366.a3366.R;


/**
 * Created by ak on 2017/5/27.
 */

@SuppressLint("AppCompatCustomView")
public class PasswordEditText  extends EditText implements
        View.OnFocusChangeListener, TextWatcher {

    private Drawable mShowDrawable;//显示密码的图片
    private Drawable mHideDrawable;//隐藏密码的图片

    private boolean isShowPassword = false;//是否显示密码
    /**
     * 控件是否有焦点
     */
    private boolean hasFoucs;

    public PasswordEditText(Context context) {
        this(context, null);
    }

    private OnTextChangedListener textChangedListener;


    public PasswordEditText(Context context, AttributeSet attrs) {
        //这里构造方法也很重要，不加这个很多属性不能再XML里面定义
        this(context, attrs, android.R.attr.editTextStyle);
    }

    public PasswordEditText(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        init();
    }


    private void init() {
        mShowDrawable = getCompoundDrawables()[2];
        if (mShowDrawable == null) {
            mShowDrawable = getResources().getDrawable(R.mipmap.login_pwd_open);
            mHideDrawable = getResources().getDrawable(R.mipmap.login_pwd_hide);


        }

        mShowDrawable.setBounds(0, 0, mShowDrawable.getIntrinsicWidth(), mShowDrawable.getIntrinsicHeight());
        mHideDrawable.setBounds(0, 0, mHideDrawable.getIntrinsicWidth(), mHideDrawable.getIntrinsicHeight());

        setShowPassword(isShowPassword);
        //设置焦点改变的监听
        setOnFocusChangeListener(this);
        //设置输入框里面内容发生改变的监听
        addTextChangedListener(this);


    }

    public OnTextChangedListener getTextChangedListener() {
        return textChangedListener;
    }

    public void setTextChangedListener(OnTextChangedListener textChangedListener) {
        this.textChangedListener = textChangedListener;
    }

    /**
     * 因为我们不能直接给EditText设置点击事件，所以我们用记住我们按下的位置来模拟点击事件
     * 当我们按下的位置 在  EditText的宽度 - 图标到控件右边的间距 - 图标的宽度  和
     * EditText的宽度 - 图标到控件右边的间距之间我们就算点击了图标，竖直方向就没有考虑
     */
    @Override
    public boolean onTouchEvent(MotionEvent event) {
        if (event.getAction() == MotionEvent.ACTION_UP) {
            if (getCompoundDrawables()[2] != null) {

                boolean touchable = event.getX() > (getWidth() - getTotalPaddingRight())
                        && (event.getX() < ((getWidth() - getPaddingRight())));
                if (touchable) {

                    isShowPassword = !isShowPassword;
                    setShowPassword(isShowPassword);
                    setSelection(getText().length());
                }
            }
        }

        return super.onTouchEvent(event);
    }

    @Override
    public void onFocusChange(View v, boolean hasFocus) {
        this.hasFoucs = hasFocus;
    }

    /**
     * 是否显示密码
     *
     * @param isShow
     */
    protected void setShowPassword(boolean isShow) {

        Drawable right = isShow ? mShowDrawable : mHideDrawable;
        setCompoundDrawables(getCompoundDrawables()[0],
                getCompoundDrawables()[1], right, getCompoundDrawables()[3]);

        this.setInputType(isShow ? InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD : (InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD));

    }


    @Override
    public void onTextChanged(CharSequence s, int start, int count,
                              int after) {

        if (textChangedListener != null) {
            textChangedListener.onTextChanged(s, start, count, after);
        }
    }

    @Override
    public void beforeTextChanged(CharSequence s, int start, int count,
                                  int after) {
        if (textChangedListener != null) {
            textChangedListener.beforeTextChanged(s, start, count, after);
        }
    }

    @Override
    public void afterTextChanged(Editable s) {
        if (textChangedListener != null) {
            textChangedListener.afterTextChanged(s);
        }
    }


    public interface OnTextChangedListener {
        void onTextChanged(CharSequence s, int start, int count,
                           int after);

        void beforeTextChanged(CharSequence s, int start, int count,
                               int after);

        void afterTextChanged(Editable s);

    }
}

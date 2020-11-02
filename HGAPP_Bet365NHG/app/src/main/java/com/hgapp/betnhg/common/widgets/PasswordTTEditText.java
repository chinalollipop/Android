package com.hgapp.betnhg.common.widgets;

/**
 * Created by ak on 2017/5/26.
 */
import android.annotation.SuppressLint;
import android.content.Context;
import android.graphics.drawable.Drawable;
import android.support.v4.content.ContextCompat;
import android.text.Editable;
import android.text.Selection;
import android.text.Spannable;
import android.text.TextWatcher;
import android.text.method.HideReturnsTransformationMethod;
import android.text.method.PasswordTransformationMethod;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnFocusChangeListener;
import android.view.animation.Animation;
import android.view.animation.CycleInterpolator;
import android.view.animation.TranslateAnimation;
import android.widget.EditText;

import com.hgapp.betnhg.R;


@SuppressLint("AppCompatCustomView")
public class PasswordTTEditText extends EditText implements
        OnFocusChangeListener, TextWatcher {
    /**
     * 眼睛按钮
     */
    private Drawable mToggleDrawable;

    public PasswordTTEditText(Context context) {
        this(context, null);
    }

    public PasswordTTEditText(Context context, AttributeSet attrs) {
        this(context, attrs, android.R.attr.editTextStyle);
    }

    public PasswordTTEditText(Context context, AttributeSet attrs, int defStyle) {
        super(context, attrs, defStyle);
        init();
    }

    /**
     * 初始化右边小眼睛的控件
     */
    private void init() {
        //获取EditText的DrawableRight,主要是通过xml或者外部设置右边的按钮，如果没有设置就采用默认的
        mToggleDrawable = getCompoundDrawables()[2];
        if (mToggleDrawable == null) {
            mToggleDrawable = ContextCompat.getDrawable(getContext(), R.mipmap.login_pwd_open);
        }
        mToggleDrawable.setBounds(0, 0, mToggleDrawable.getIntrinsicWidth(), mToggleDrawable.getIntrinsicHeight());
        setToggleIconVisible(false);
        setOnFocusChangeListener(this);
        addTextChangedListener(this);
    }


    /**
     * 因为我们不能直接给EditText设置点击事件，所以我们用记住我们按下的位置来模拟点击事件
     * 当我们按下的位置 在  EditText的宽度 - 图标到控件右边的间距 - 图标的宽度  和
     * EditText的宽度 - 图标到控件右边的间距之间我们就算点击了图标，竖直方向没有考虑
     */
    @Override
    public boolean onTouchEvent(MotionEvent event) {
        if (getCompoundDrawables()[2] != null) {
            if (event.getAction() == MotionEvent.ACTION_DOWN) {
                boolean touchable = event.getX() > (getWidth()
                        - getPaddingRight() - mToggleDrawable.getIntrinsicWidth())
                        && (event.getX() < ((getWidth() - getPaddingRight())));
                if (touchable) {
                    //显示密码明文
                    setTransformationMethod(HideReturnsTransformationMethod.getInstance());
                    postInvalidate();
                    CharSequence charSequence = getText();
                    //为了保证体验效果，需要保持输入焦点在文本最后一位
                    if (charSequence != null) {
                        Spannable spanText = (Spannable) charSequence;
                        Selection.setSelection(spanText, charSequence.length());
                    }
                }
            }else if(event.getAction() == MotionEvent.ACTION_UP){
                //隐藏密码明文
                setTransformationMethod(PasswordTransformationMethod.getInstance());
                postInvalidate();
                setSelection(getText().length());
            }
        }

        return super.onTouchEvent(event);
    }

    /**
     * 当EditText焦点发生变化的时候，判断里面字符串长度设置清除图标的显示与隐藏
     */
    @Override
    public void onFocusChange(View v, boolean hasFocus) {
        if (hasFocus) {
            setToggleIconVisible(getText().length() > 0);
        } else {
            setToggleIconVisible(false);
            setShakeAnimation();
        }
    }


    /**
     * 设置清除图标的显示与隐藏，调用setCompoundDrawables为EditText绘制上去
     * @param visible
     */
    public void setToggleIconVisible(boolean visible) {
        Drawable right = visible ? mToggleDrawable : null;
        setCompoundDrawables(getCompoundDrawables()[0],
                getCompoundDrawables()[1], right, getCompoundDrawables()[3]);
    }


    /**
     * 当输入框里面内容发生变化的时候回调的方法
     */
    @Override
    public void onTextChanged(CharSequence s, int start, int count,
                              int after) {
        setToggleIconVisible(s.length() > 0);
    }

    @Override
    public void beforeTextChanged(CharSequence s, int start, int count,
                                  int after) {

    }

    @Override
    public void afterTextChanged(Editable s) {

    }


    /**
     * 设置晃动动画
     */
    public void setShakeAnimation(){
        this.setAnimation(shakeAnimation(3));
    }


    /**
     * 晃动动画
     * @param counts 1秒钟晃动多少下
     * @return
     */
    public Animation shakeAnimation(int counts){
        Animation translateAnimation = new TranslateAnimation(0, 10, 0, 0);
        translateAnimation.setInterpolator(new CycleInterpolator(counts));
        translateAnimation.setDuration(500);
        return translateAnimation;
    }
}

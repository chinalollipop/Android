package com.cfcp.a01.common.widget;

import android.app.Activity;
import android.content.Context;
import android.inputmethodservice.Keyboard;
import android.inputmethodservice.KeyboardView;
import android.os.Build;
import android.text.Editable;
import android.text.InputType;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;

import com.cfcp.a01.R;

import java.lang.reflect.Method;

public class AnKeyboardUtils {

    private Activity activity;
    private KeyboardView keyboardView;
    private Keyboard keyboardNumber;
    private EditText editText;

    public AnKeyboardUtils(Activity context, KeyboardView keyboardView) {
        this.activity = context;
        this.keyboardView = keyboardView;
        keyboardView.setPreviewEnabled(false);
        keyboardView.setOnKeyboardActionListener(onKeyboardActionListener);
        keyboardNumber = new Keyboard(activity, R.xml.keyboard_number);
    }

    //点击事件触发
    private void attachTo(EditText editText) {
        hideSystemKeyboard(activity, editText);
        this.editText = editText;
        showKeyboard();
    }

    /**
     * 隐藏系统键盘
     */
    private static void hideSystemKeyboard(Context context, EditText editText) {
        int sdkInt = Build.VERSION.SDK_INT;
        if (sdkInt >= 11) {
            try {
                Class<EditText> cls = EditText.class;
                Method setShowSoftInputOnFocus;
                setShowSoftInputOnFocus = cls.getMethod("setShowSoftInputOnFocus", boolean.class);
                setShowSoftInputOnFocus.setAccessible(true);
                setShowSoftInputOnFocus.invoke(editText, false);
            } catch (SecurityException e) {
                e.printStackTrace();
            } catch (NoSuchMethodException e) {
                e.printStackTrace();
            } catch (Exception e) {
                e.printStackTrace();
            }
        } else {
            editText.setInputType(InputType.TYPE_NULL);
        }
        // 如果软键盘已经显示，则隐藏
        InputMethodManager imm = (InputMethodManager) context.getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(editText.getWindowToken(), 0);
    }

    public void hideKeyBoard() {
        int visibility = keyboardView.getVisibility();
        if (visibility == KeyboardView.VISIBLE) {
            keyboardView.setVisibility(KeyboardView.GONE);
        }
    }

    private void showKeyboard() {
        if (keyboardView == null) {
            return;
        }
        keyboardView.setKeyboard(keyboardNumber);
        keyboardView.setEnabled(true);
        keyboardView.setPreviewEnabled(false);
        keyboardView.setVisibility(View.VISIBLE);
        keyboardView.setOnKeyboardActionListener(onKeyboardActionListener);
    }

    /**
     * 软键盘点击监听
     */
    private KeyboardView.OnKeyboardActionListener onKeyboardActionListener = new KeyboardView
            .OnKeyboardActionListener() {
        @Override
        public void onPress(int primaryCode) {

        }

        @Override
        public void onRelease(int primaryCode) {

        }

        @Override
        public void onKey(int primaryCode, int[] keyCodes) {
            if (editText == null) {
                return;
            }
            Editable editable = editText.getText();
            int start = editText.getSelectionStart();
            switch (primaryCode) {
                case Keyboard.KEYCODE_DELETE:// 删除
                    if (editable != null && editable.length() > 0) {
                        if (start > 0) {
                            editable.delete(start - 1, start);
                        }
                    }
                    break;
                case Keyboard.KEYCODE_CANCEL://取消
                    hideKeyBoard();
                    break;
                case Keyboard.KEYCODE_SHIFT://换行
                    editable.insert(start, "\n");
                    break;
                default:
                    editable.insert(start, Character.toString((char) primaryCode));
                    break;
            }
        }

        @Override
        public void onText(CharSequence text) {

        }

        @Override
        public void swipeLeft() {

        }

        @Override
        public void swipeRight() {

        }

        @Override
        public void swipeDown() {

        }

        @Override
        public void swipeUp() {

        }
    };

    public boolean isShow() {
        return keyboardView.isShown();
    }

    public void bindEditTextEvent(final EditText editText) {

        editText.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if (hasFocus) {
                    attachTo(editText);
                } else {
                    hideKeyBoard();
                }
            }
        });

        editText.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                attachTo(editText);
            }
        });
    }
}

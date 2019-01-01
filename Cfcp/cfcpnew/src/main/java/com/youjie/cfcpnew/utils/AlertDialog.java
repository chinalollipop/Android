package com.youjie.cfcpnew.utils;

import android.annotation.SuppressLint;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup.LayoutParams;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.youjie.cfcpnew.R;

import java.util.Objects;

/**
 * 提示对话框(可以自定义布局)
 */
public class AlertDialog extends Dialog {

    public AlertDialog(Context context) {
        super(context);
    }

    public AlertDialog(Context context, int theme) {
        super(context, theme);
    }

    /**
     * 建造者类
     */
    public static class Builder {
        private Context context;

        private int positiveButtonText;
        private int negativeButtonText;
        private OnClickListener positiveButtonClickListener;
        private OnClickListener negativeButtonClickListener;

        public Builder(Context context) {
            this.context = context;
        }

        public void setPositiveButton(int positiveButtonText,
                                      OnClickListener listener) {
            this.positiveButtonText = positiveButtonText;
            this.positiveButtonClickListener = listener;
        }

        public Builder setNegativeButton(int negativeButtonText,
                                         OnClickListener listener) {
            this.negativeButtonText = negativeButtonText;
            this.negativeButtonClickListener = listener;
            return this;
        }

        /**
         * 创建一个AlertDialog
         */
        @SuppressLint("InflateParams")
        public AlertDialog create() {
            LayoutInflater inflater = LayoutInflater.from(context);
            final AlertDialog dialog = new AlertDialog(context,
                    R.style.DialogStyle);
            Objects.requireNonNull(dialog.getWindow()).getAttributes().windowAnimations = R.style.AnimLeftRight;
            View layout = inflater.inflate(R.layout.layout_alert_dialog, null);

            // 设置确认按钮
            RelativeLayout sure_layout = layout.findViewById(R.id.sure_layout);
            TextView sure_text = layout.findViewById(R.id.sure_text);
            if (positiveButtonText == 0 || null == positiveButtonClickListener) {
                sure_layout.setVisibility(View.GONE);
            } else {
                sure_text.setText(positiveButtonText);
                sure_layout.setOnClickListener(v -> {
                    dialog.dismiss();
                    positiveButtonClickListener.onClick(dialog,
                            DialogInterface.BUTTON_POSITIVE);
                });
            }

            // 设置取消按钮
            RelativeLayout quit_layout = layout.findViewById(R.id.quit_layout);
            TextView quit_text = layout.findViewById(R.id.quit_text);
            if (negativeButtonText == 0 || null == negativeButtonClickListener) {
                quit_layout.setVisibility(View.GONE);
            } else {
                quit_text.setText(negativeButtonText);
                quit_layout.setOnClickListener(v -> {
                    dialog.dismiss();
                    negativeButtonClickListener.onClick(dialog,
                            DialogInterface.BUTTON_NEGATIVE);
                });
            }

            if (positiveButtonText != 0 && negativeButtonText != 0) {
                if (null == positiveButtonClickListener) {
                    sure_layout.setVisibility(View.VISIBLE);
                    sure_layout.setOnClickListener(v -> dialog.dismiss());
                }
            }

            // 设置对话框的视图
            LayoutParams params = new LayoutParams(LayoutParams.WRAP_CONTENT,
                    LayoutParams.WRAP_CONTENT);
            dialog.setContentView(layout, params);
            return dialog;
        }
    }
}

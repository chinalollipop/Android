package com.youjie.cfcpnew.utils;

import android.annotation.SuppressLint;
import android.content.Context;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.youjie.cfcpnew.R;

/**
 * Created by Colin on 2017/12/14.
 * 吐司
 */
public class AppToast {
    private static Toast toast;

    public static void showShortText(Context context, int text) {
        if (context == null) return;
        if (toast != null)
            toast.cancel();
        LayoutInflater inflater = (LayoutInflater) context.getApplicationContext()
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        assert inflater != null;
        @SuppressLint("InflateParams") View view = inflater.inflate(R.layout.layout_custom_toast, null);
        TextView contentText = view.findViewById(R.id.toast_message);
        contentText.setText(text);
        toast = new Toast(context.getApplicationContext());
        toast.setDuration(Toast.LENGTH_SHORT);
        toast.setGravity(Gravity.BOTTOM, 0, 300);
        toast.setView(view);
        toast.show();
    }

    public static void showLongText(Context context, int text) {
        if (context == null) return;
        if (toast != null)
            toast.cancel();
        LayoutInflater inflater = (LayoutInflater) context.getApplicationContext()
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        assert inflater != null;
        @SuppressLint("InflateParams") View view = inflater.inflate(R.layout.layout_custom_toast, null);
        TextView contentText = view.findViewById(R.id.toast_message);
        contentText.setText(text);
        toast = new Toast(context.getApplicationContext());
        toast.setDuration(Toast.LENGTH_LONG);
        toast.setGravity(Gravity.BOTTOM, 0, 300);
        toast.setView(view);
        toast.show();
    }
}

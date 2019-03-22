package com.cfcp.a01.common.utils;

import android.content.ClipboardManager;
import android.content.Context;

/**
 * Created by ak on 2017/7/25.
 */

public class CLipHelper {

    /**
     * 复制
     * @param context
     * @param copyString
     */
    public static void copy(Context context,String copyString){
        ClipboardManager clipboardManager = (ClipboardManager) context.getSystemService(Context.CLIPBOARD_SERVICE);
        clipboardManager.setText(copyString);
    }

    public static String paste(Context context){
        ClipboardManager clipboardManager = (ClipboardManager) context.getSystemService(Context.CLIPBOARD_SERVICE);
        return clipboardManager.getText().toString().trim();
    }
}

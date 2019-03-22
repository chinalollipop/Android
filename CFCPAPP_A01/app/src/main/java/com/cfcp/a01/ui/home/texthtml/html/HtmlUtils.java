package com.cfcp.a01.ui.home.texthtml.html;

import android.content.Context;
import android.text.Html;
import android.text.Spanned;
import android.text.method.LinkMovementMethod;
import android.text.method.ScrollingMovementMethod;
import android.widget.TextView;

/**
 * Created by Daniel on 2019/3/2.
 */

public class HtmlUtils {

    public static Spanned getHtml(Context context, TextView textView, String string) {
        textView.setMovementMethod(ScrollingMovementMethod.getInstance());// 滚动
        textView.setMovementMethod(LinkMovementMethod.getInstance());//设置超链接可以打开网页//click must
        return Html.fromHtml(string.replace("span","daniel_style"), new URLImageGetter(textView, context), new URLTagHandler(context,textView.getTextColors()));
    }

}

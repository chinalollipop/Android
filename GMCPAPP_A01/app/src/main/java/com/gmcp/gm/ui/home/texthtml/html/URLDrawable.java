package com.gmcp.gm.ui.home.texthtml.html;

import android.graphics.Canvas;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
/**
 * Created by Daniel on 2019/3/2.
 */
public class URLDrawable extends BitmapDrawable {
    private Drawable drawable;

    @Override
    public void draw(Canvas canvas) {
        if (drawable != null) {
            drawable.draw(canvas);
        }
    }

    public void setDrawable(Drawable drawable) {
        this.drawable = drawable;
    }

}

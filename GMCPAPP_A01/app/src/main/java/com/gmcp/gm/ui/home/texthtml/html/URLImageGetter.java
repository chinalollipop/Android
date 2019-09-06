package com.gmcp.gm.ui.home.texthtml.html;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.text.Html.ImageGetter;
import android.widget.TextView;

import com.gmcp.gm.ui.home.texthtml.imageload.ImageLoad;
import com.squareup.picasso.Picasso;
import com.squareup.picasso.Target;

import java.util.ArrayList;
import java.util.List;
/**
 * Created by Daniel on 2019/3/2.
 */
public class URLImageGetter implements ImageGetter {
    Context c;
    TextView tv_image;
    private List<Target> targets = new ArrayList<>();

    public URLImageGetter(TextView t, Context c) {
        this.tv_image = t;
        this.c = c;
        tv_image.setTag(targets);
    }

    @Override
    public Drawable getDrawable(final String source) {
        final URLDrawable urlDrawable = new URLDrawable();
        final Target target = new Target() {
            @Override
            public void onBitmapLoaded(Bitmap bitmap, Picasso.LoadedFrom from) {
                Drawable drawable = new BitmapDrawable(bitmap);
                drawable.setBounds(0, 0, bitmap.getWidth()/2, bitmap.getHeight()/2);
                urlDrawable.setDrawable(drawable);
                urlDrawable.setBounds(0, 0, bitmap.getWidth()/2, bitmap.getHeight()/2);
                tv_image.invalidate();
                tv_image.setText(tv_image.getText());
            }

            @Override
            public void onBitmapFailed(Drawable errorDrawable) {
                errorDrawable.setBounds(0, 0, errorDrawable.getIntrinsicWidth(), errorDrawable.getIntrinsicHeight());
                urlDrawable.setBounds(0, 0, errorDrawable.getIntrinsicWidth(), errorDrawable.getIntrinsicHeight());
                urlDrawable.setDrawable(errorDrawable);
                tv_image.invalidate();
            }

            @Override
            public void onPrepareLoad(Drawable placeHolderDrawable) {
                placeHolderDrawable.setBounds(0, 0, placeHolderDrawable.getIntrinsicWidth(), placeHolderDrawable.getIntrinsicHeight());
                urlDrawable.setBounds(0, 0, placeHolderDrawable.getIntrinsicWidth(), placeHolderDrawable.getIntrinsicHeight());
                urlDrawable.setDrawable(placeHolderDrawable);
                tv_image.invalidate();
            }
        };

        targets.add(target);
        ImageLoad.loadPlaceholder(c, source, target);
        return urlDrawable;
    }
}


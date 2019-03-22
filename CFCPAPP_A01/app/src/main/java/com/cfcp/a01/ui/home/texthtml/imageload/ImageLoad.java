package com.cfcp.a01.ui.home.texthtml.imageload;

import android.content.Context;

import com.cfcp.a01.R;
import com.squareup.picasso.Picasso;
import com.squareup.picasso.Target;

/**
 * Created by shucheng.qu on 2017/8/24.
 */

public class ImageLoad {

    public static void loadPlaceholder(Context context, String url, Target target) {

        Picasso picasso = new Picasso.Builder(context).loggingEnabled(true).build();
        picasso.load("http://dh5588.com"+url)//"https://img.alicdn.com/imgextra/i4/725677994/TB27EY6hypnpuFjSZFIXXXh2VXa_!!725677994.jpg"
                .placeholder(R.drawable.moren)
                .error(R.drawable.moren)
                .transform(new ImageTransform())
//                .transform(new CompressTransformation())
                .into(target);
    }

}

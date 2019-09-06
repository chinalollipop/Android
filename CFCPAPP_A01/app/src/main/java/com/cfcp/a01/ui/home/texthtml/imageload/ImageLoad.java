package com.cfcp.a01.ui.home.texthtml.imageload;

import android.content.Context;

import com.cfcp.a01.R;
import com.cfcp.a01.common.http.Client;
import com.cfcp.a01.common.utils.GameLog;
import com.squareup.picasso.MemoryPolicy;
import com.squareup.picasso.Picasso;
import com.squareup.picasso.Target;

/**
 * Created by shucheng.qu on 2017/8/24.
 */

public class ImageLoad {

    public static void loadPlaceholder(Context context, String url, Target target) {

        Picasso picasso = new Picasso.Builder(context).loggingEnabled(true).build();
        GameLog.log(" Daniel 加载的图片是： "+url);
        if(url.contains("http")){
            picasso.load(url)//"https://img.alicdn.com/imgextra/i4/725677994/TB27EY6hypnpuFjSZFIXXXh2VXa_!!725677994.jpg"
                    .memoryPolicy(MemoryPolicy.NO_CACHE)
                    .placeholder(R.drawable.loading)
                    .error(R.drawable.error)
                    .transform(new ImageTransform())
//                .transform(new CompressTransformation())
                    .into(target);
            picasso.invalidate(url);
            return;
        }
        picasso.load(Client.baseUrl()+url.substring(1))//"https://img.alicdn.com/imgextra/i4/725677994/TB27EY6hypnpuFjSZFIXXXh2VXa_!!725677994.jpg"
                .memoryPolicy(MemoryPolicy.NO_CACHE)
                .placeholder(R.drawable.loading)
                .error(R.drawable.error)
                .transform(new ImageTransform())
//                .transform(new CompressTransformation())
                .into(target);
        picasso.invalidate(Client.baseUrl()+url.substring(1));
    }

}

package com.hgapp.a6668.common.http.util;


import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.common.util.GameLog;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashSet;

import okhttp3.Interceptor;
import okhttp3.Response;

public class SaveCookiesInterceptor implements Interceptor {
    @Override
    public Response intercept(Chain chain) throws IOException {
        Response originalResponse = chain.proceed(chain.request());

        if (!originalResponse.headers("Set-Cookie").isEmpty()) {
            ArrayList<String> cookies = new ArrayList<>();
            String cookie = " ";
            for (String header : originalResponse.headers("Set-Cookie")) {
                cookies.add(header);
               // GameLog.log("Cookie "+header);
                cookie += header+" ";
                ACache.get(HGApplication.instance().getApplicationContext()).put("tySet-Cookie",header);
            }
//            GameLog.log("Save 最终的cookie是'"+cookie);
//            GameLog.log("Save 当前的可用转台'"+ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_COOKIE_AVIABLE));
        }

        return originalResponse;

    }
}

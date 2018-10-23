package com.hgapp.a0086.common.http.util;


import com.hgapp.a0086.HGApplication;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.common.util.GameLog;

import java.io.IOException;
import java.util.HashSet;

import okhttp3.Interceptor;
import okhttp3.Response;

public class SaveCookiesInterceptor implements Interceptor {
    @Override
    public Response intercept(Chain chain) throws IOException {
        Response originalResponse = chain.proceed(chain.request());

        if (!originalResponse.headers("Set-Cookie").isEmpty()) {
            HashSet<String> cookies = new HashSet<>();

            for (String header : originalResponse.headers("Set-Cookie")) {
                cookies.add(header);
                GameLog.log("Cookie "+header);
                ACache.get(HGApplication.instance().getApplicationContext()).put("Set-Cookie",header);
            }


        }

        return originalResponse;

    }
}

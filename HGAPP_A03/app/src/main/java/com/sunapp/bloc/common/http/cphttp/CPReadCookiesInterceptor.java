package com.sunapp.bloc.common.http.cphttp;


import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.common.util.DeviceUtils;
import com.sunapp.common.util.GameLog;

import java.io.IOException;

import okhttp3.Interceptor;
import okhttp3.Request;
import okhttp3.Response;

public class CPReadCookiesInterceptor implements Interceptor {
    @Override
    public Response intercept(Chain chain) throws IOException {
        Request.Builder builder = chain.request().newBuilder();

       /* CookieManager manager = new CookieManager();
        CookieHandler.setDefault(manager);
        //因为http已经做了请求，所以会得到cookie
        CookieStore cookieJar = manager.getCookieStore();
        List<HttpCookie> cookies = cookieJar.getCookies();*/
       try{
           builder.addHeader("User-Agent",DeviceUtils.getUserAgent());
           builder.addHeader("Cookie",ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_COOKIE));
           //builder.addHeader("Cookie",ACache.get(HGApplication.instance().getApplicationContext()).getAsString("Set-Cookie") );

       }catch (Exception exception){
           GameLog.log("cookie异常：\n"+exception);
       }

        return chain.proceed(builder.build());

    }
}

package com.sands.corp.common.http.cphttp;


import com.sands.corp.HGApplication;
import com.sands.corp.common.util.ACache;
import com.sands.corp.common.util.HGConstant;
import com.sands.common.util.DeviceUtils;
import com.sands.common.util.GameLog;

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

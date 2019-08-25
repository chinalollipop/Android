package com.hg3366.a3366.common.http.cphttp;


import com.hg3366.a3366.HGApplication;
import com.hg3366.a3366.common.util.ACache;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.common.util.DeviceUtils;
import com.hg3366.common.util.GameLog;

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

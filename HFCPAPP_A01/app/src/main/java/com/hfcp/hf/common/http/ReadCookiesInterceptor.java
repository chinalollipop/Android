package com.hfcp.hf.common.http;



import com.hfcp.hf.CFApplication;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.common.utils.DeviceUtils;
import com.hfcp.hf.common.utils.GameLog;

import java.io.IOException;

import okhttp3.Interceptor;
import okhttp3.Request;
import okhttp3.Response;

public class ReadCookiesInterceptor implements Interceptor {
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
           builder.addHeader("Cookie",ACache.get(CFApplication.instance().getApplicationContext()).getAsString("tySet-Cookie") );
           //builder.addHeader("Cookie",ACache.get(HGApplication.instance().getApplicationContext()).getAsString("Set-Cookie") );

       }catch (Exception exception){
           GameLog.log("cookie异常：\n"+exception);
       }

        return chain.proceed(builder.build());

    }
}

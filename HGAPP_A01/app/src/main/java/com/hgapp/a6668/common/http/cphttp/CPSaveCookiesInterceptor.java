package com.hgapp.a6668.common.http.cphttp;


import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.common.util.GameLog;

import java.io.IOException;
import java.util.ArrayList;

import okhttp3.Interceptor;
import okhttp3.Response;

public class CPSaveCookiesInterceptor implements Interceptor {
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
                //ACache.get(HGApplication.instance().getApplicationContext()).put("Set-Cookie",header);
            }
//            GameLog.log("Save 最终的cookie是'"+cookie);
//            GameLog.log("Save 当前的可用转台'"+ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_COOKIE_AVIABLE));
                if(cookies.size()>=10){
                    ACache.get(HGApplication.instance().getApplicationContext()).put("cPsjsjsjj",cookie);
                    ACache.get(HGApplication.instance().getApplicationContext()).put("Set-Cookie",ACache.get(HGApplication.instance().getApplicationContext()).getAsString("cPsjsjsjj"));
                    for(int k=0;k<cookies.size();k++){
                        if(cookies.get(k).contains("token=")){
                            GameLog.log("token 等荣誉 "+cookies.get(k));
                            ACache.get(HGApplication.instance().getApplicationContext()).put("COOKIE_token",cookies.get(k));
                            if(k==12){
                                ACache.get(HGApplication.instance().getApplicationContext()).put("COOKIE_win_session",cookies.get(k-1));
                            }else{
                                ACache.get(HGApplication.instance().getApplicationContext()).put("COOKIE_win_session",cookies.get(k));
                            }
                        }
                    }
                }else{
                    GameLog.log("彩票的cookie===========================");
                    String isLogin = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_COOKIE_AVIABLE);
                    if("true".equals(isLogin)){
                        GameLog.log("登录成功之后的 彩票的cookie===========================");
                        ACache.get(HGApplication.instance().getApplicationContext()).put("Set-Cookie",ACache.get(HGApplication.instance().getApplicationContext()).getAsString("cPsjsjsjj"));
                    }
                }
        }

        return originalResponse;

    }
}

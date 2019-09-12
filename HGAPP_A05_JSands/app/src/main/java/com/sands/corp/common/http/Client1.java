package com.sands.corp.common.http;


import android.os.Build;
import android.webkit.CookieManager;
import android.webkit.CookieSyncManager;

import com.sands.common.util.GameLog;
import com.sands.common.util.LoggerInterceptor;
import com.sands.common.util.PNThreadFactory;
import com.sands.common.util.Utils;

import java.util.List;
import java.util.concurrent.TimeUnit;

import okhttp3.Call;
import okhttp3.Cookie;
import okhttp3.HttpUrl;
import okhttp3.OkHttpClient;
import retrofit2.Retrofit;
import retrofit2.adapter.rxjava.RxJavaCallAdapterFactory;
import retrofit2.converter.gson.GsonConverterFactory;

/**
 * Created by AK on 2018/1/22.
 */

public class Client1 {
    private static Retrofit.Builder builder;
    private static Retrofit retrofit;
    private static OkHttpClient client;
    private static ClientConfig clientConfig;
    private static ProxyCallFactory proxyCallFactory;
    private static String domainUrl = "http://appfront.f6607.com/";
    /**
     * 应该在Application onCreate中使用
     * @param config
     */
    public static void config(ClientConfig config)
    {
        clientConfig = config;
        proxyCallFactory = new ProxyCallFactory(getClient(),clientConfig);
}

    /**
     * 基本域名（+path）
     * @return
     */
    public static String baseUrl()
    {
//        domainUrl = "http://uatappfront-e03.agg013.com/";//运测环境
//        domainUrl = "http://10.91.6.11:6080/p01-app-front/";//开发环境http://10.91.6.17:8080/app-front_E03
//        domainUrl = "http://10.91.35.42:8086/e03-p01-app-front/";// 本地环境http://10.91.35.42:8086/p01-app-front/
//        domainUrl = "http://appfront.w11-online.com/";//运营环境
        //GameLog.log("get domainUrl:"+domainUrl);
        return domainUrl;
        //return Check.isEmpty(domainUrl)?"http://appfront.h88992.com/":domainUrl;
    }

    public static void setClientDomain(String url){
        domainUrl = url;
        GameLog.log("set domainUrl:"+domainUrl);
    }

    public static OkHttpClient getClient()
    {
        if(null == client)
        {
            client = new OkHttpClient.Builder()
                    .connectTimeout(30,TimeUnit.SECONDS)
                    .readTimeout(60, TimeUnit.SECONDS)
                    .writeTimeout(30,TimeUnit.SECONDS)
                    //.addInterceptor(new AppInterceptor())
                    .addInterceptor(new LoggerInterceptor())
                    .addInterceptor(new TokenInterceptor(clientConfig))
                    /*.cookieJar(new CookieJar() {
                        //cookie的自动化管理
                        private PersistentCookieStore cookieStore = new PersistentCookieStore(Utils.getContext());
                        @Override
                        public void saveFromResponse(HttpUrl httpUrl, List<Cookie> list) {
                            if(null == httpUrl || null == list || list.isEmpty())
                            {
                                GameLog.log("no cookie from url:" + httpUrl);
                                return;
                            }

                            for(Cookie cookie:list)
                            {
                                cookieStore.add(httpUrl,cookie);
                            }
                            synCookieToWebview(httpUrl, list);
                        }

                        @Override
                        public List<Cookie> loadForRequest(HttpUrl httpUrl) {
                            return cookieStore.get(httpUrl);
                        }
                    })*/
                    .build();

        }

        return client;
    }


    /**
     * 关闭所有OkHttpClient的请求
     */
    public  static void cancelAllRequest(){
        client.dispatcher().cancelAll();
    }

    /**
     * 关闭OkHttpClient某一个标签的请求
     * @param tag
     */
    public static void cancelTag(Object tag){
        for(Call call:client.dispatcher().runningCalls()){
            if(tag.equals(call.request().tag())){
                call.cancel();
            }
        }

        for(Call call:client.dispatcher().queuedCalls()){
            if(tag.equals(call.request().tag())){
                call.cancel();
            }
        }
    }

    /**
     * 同步本地cookie到webview，实现免登陆，要不然webview不能持有本地的cookie。
     * @param httpUrl
     * @param list
     */
    private static void synCookieToWebview(HttpUrl httpUrl, List<Cookie> list)
    {
        if(null == httpUrl || list == null || list.isEmpty())
        {
            GameLog.log("synCookieToWebview null cookie");
            return;
        }
        final CookieManager cookieManager = CookieManager.getInstance();
        cookieManager.setAcceptCookie(true);
        for(Cookie cookie : list)
        {
            cookieManager.setCookie(httpUrl.url().toString(),cookie.value());
            GameLog.log("syn ur:" + httpUrl.url().toString() + ",cookie:" + cookie.value());
        }

        //立即同步cookie到webview，这是个耗时的工作
        PNThreadFactory.createThread(new Runnable() {
            @Override
            public void run() {
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                    cookieManager.flush();
                }
                else
                {
                    CookieSyncManager.createInstance(Utils.getContext());
                    CookieSyncManager.getInstance().sync();
                }
            }
        });

    }
    public static boolean hasCookieForUrl(String url)
    {
        List<Cookie> cookies = client.cookieJar().loadForRequest(HttpUrl.parse(url));
        return (null != cookies) && (!cookies.isEmpty());
    }

    public static Retrofit getRetrofit()
    {
        if(null == retrofit)
        {
            if(null == clientConfig)
            {
                throw new NullPointerException("client config has to  be configed in the Application onCreate");
            }

            retrofit = new Retrofit.Builder()
                    .baseUrl(baseUrl())
                    .callFactory(proxyCallFactory)
                    .addConverterFactory(GsonConverterFactory.create())
                    .addCallAdapterFactory(RxJavaCallAdapterFactory.create())
                    .build();
        }


           /* if(null == builder){
                builder = new Retrofit.Builder();
                builder.baseUrl(baseUrl())
                        .callFactory(proxyCallFactory)
                        .addConverterFactory(GsonConverterFactory.create())
                        .addCallAdapterFactory(RxJavaCallAdapterFactory.create());
            }
            }
            retrofit = builder.baseUrl(baseUrl()).build();
        */
        return retrofit;
    }

}

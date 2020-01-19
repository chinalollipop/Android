package com.hgapp.m8.common.http;


import android.os.Build;
import android.webkit.CookieManager;
import android.webkit.CookieSyncManager;

import com.google.gson.GsonBuilder;
import com.hgapp.m8.common.http.util.SaveCookiesInterceptor;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.LoggerInterceptor;
import com.hgapp.common.util.PNThreadFactory;
import com.hgapp.common.util.Utils;

import java.util.List;
import java.util.concurrent.TimeUnit;

import me.jessyan.retrofiturlmanager.RetrofitUrlManager;
import okhttp3.Call;
import okhttp3.Cookie;
import okhttp3.HttpUrl;
import okhttp3.OkHttpClient;
import retrofit2.Retrofit;
import retrofit2.adapter.rxjava.RxJavaCallAdapterFactory;
import retrofit2.converter.gson.GsonConverterFactory;

/**
 * Created by Daniel on 2017/4/17.
 */

public class Client {

    private static Retrofit retrofit;
    private static OkHttpClient client;
    private static ClientConfig clientConfig;
    private static ProxyCallFactory proxyCallFactory;
    public static String domainUrl = "https://m.606668.com/";
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
//        domainUrl = "http://uatappfront-e03.agg013.com/";//运测环境 http://m.hhhg6668.com/ http://192.168.1.6/
//        domainUrl = "http://m.dh77.com/";//开发环境http://10.91.6.17:8080/app-front_E03 http://10.91.6.17:8091
//        domainUrl = "http://m.hg01455.com/";// 本地环境http://m.hg3088_da1.lcn  http://m.hg3088.lcn/  http://192.168.1.15 http://192.168.1.6
//        domainUrl = "https://m.app008602.com/";//运营环境 http://m.hgw777.co http://m.hg50080.com/
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
            client = RetrofitUrlManager.getInstance().with(new OkHttpClient.Builder())
                    .connectTimeout(30,TimeUnit.SECONDS)
                    .readTimeout(60, TimeUnit.SECONDS)
                    .writeTimeout(30,TimeUnit.SECONDS)
                    //.addInterceptor(new AppInterceptor())
                    .addInterceptor(new ReadCookiesInterceptor())
                    .addInterceptor(new SaveCookiesInterceptor())
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
                    .addConverterFactory(GsonConverterFactory.create(new GsonBuilder().setLenient().create()))//new GsonBuilder().setLenient().create()
                    .addCallAdapterFactory(RxJavaCallAdapterFactory.create())
                    .build();
        }

        return retrofit;
    }

}

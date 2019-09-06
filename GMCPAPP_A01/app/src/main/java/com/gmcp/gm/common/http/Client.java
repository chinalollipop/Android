package com.gmcp.gm.common.http;

import android.webkit.CookieManager;

import com.gmcp.gm.common.http.util.LoggerInterceptor;
import com.gmcp.gm.common.http.util.SaveCookiesInterceptor;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.GameLog;

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

import static com.gmcp.gm.common.utils.Utils.getContext;

/**
 * Created by Daniel on 2019/12/17.
 */

public class Client {

    private static Retrofit retrofit;
    private static OkHttpClient client;
    private static ClientConfig clientConfig;
    private static ProxyCallFactory proxyCallFactory;
    public static String domainUrl = "http://gmapp01.com/";

    /**
     * 应该在Application onCreate中使用
     */
    public static void config(ClientConfig config) {
        clientConfig = config;
        proxyCallFactory = new ProxyCallFactory(getClient(), clientConfig);
    }

    /**
     * 基本域名（+path）
     */
    public static String baseUrl() {
        //domainUrl = "http://cf5501.com/"; //线上
        domainUrl = ACache.get(getContext()).getAsString("app_demain_url"); //线上
        if(Check.isNull(domainUrl)){
            domainUrl = "http://gmapp01.com/";
        }
//        domainUrl = "http://api.dh5588.com/";//测试
// 本地环境http://m.hg3088_da1.lcn  http://m.hg3088.lcn/  http://192.168.1.15 http://192.168.1.6
        GameLog.log("get domainUrl:" + domainUrl);
        return domainUrl;
    }

    public static void setClientDomain(String url) {
        domainUrl = url;
        GameLog.log("set domainUrl:" + domainUrl);
    }

    public static OkHttpClient getClient() {
        if (null == client) {
            client = RetrofitUrlManager.getInstance().with(new OkHttpClient.Builder())
                    .connectTimeout(30, TimeUnit.SECONDS)
                    .readTimeout(60, TimeUnit.SECONDS)
                    .writeTimeout(30, TimeUnit.SECONDS)
                    .addInterceptor(new ReadCookiesInterceptor())
                    .addInterceptor(new SaveCookiesInterceptor())
                    .addInterceptor(new LoggerInterceptor())
                    .addInterceptor(new TokenInterceptor(clientConfig))
                    .build();
        }
        return client;
    }

    /**
     * 关闭所有OkHttpClient的请求
     */
    public static void cancelAllRequest() {
        client.dispatcher().cancelAll();
    }

    /**
     * 关闭OkHttpClient某一个标签的请求
     *
     * @param tag
     */
    public static void cancelTag(Object tag) {
        for (Call call : client.dispatcher().runningCalls()) {
            if (tag.equals(call.request().tag())) {
                call.cancel();
            }
        }

        for (Call call : client.dispatcher().queuedCalls()) {
            if (tag.equals(call.request().tag())) {
                call.cancel();
            }
        }
    }

    /**
     * 同步本地cookie到webview，实现免登陆，要不然webview不能持有本地的cookie。
     *
     * @param httpUrl
     * @param list
     */
    private static void synCookieToWebview(HttpUrl httpUrl, List<Cookie> list) {
        if (null == httpUrl || list == null || list.isEmpty()) {
            GameLog.log("synCookieToWebview null cookie");
            return;
        }
        final CookieManager cookieManager = CookieManager.getInstance();
        cookieManager.setAcceptCookie(true);
        for (Cookie cookie : list) {
            cookieManager.setCookie(httpUrl.url().toString(), cookie.value());
            GameLog.log("syn ur:" + httpUrl.url().toString() + ",cookie:" + cookie.value());
        }
    }

    public static boolean hasCookieForUrl(String url) {
        List<Cookie> cookies = client.cookieJar().loadForRequest(HttpUrl.parse(url));
        return (null != cookies) && (!cookies.isEmpty());
    }

    public static Retrofit getRetrofit() {
        if (null == retrofit) {
            if (null == clientConfig) {
                throw new NullPointerException("client config has to  be configed in the Application onCreate");
            }

            retrofit = new Retrofit.Builder()
                    .baseUrl(baseUrl())
                    .callFactory(proxyCallFactory)
                    .addConverterFactory(GsonConverterFactory.create())//new GsonBuilder().setLenient().create() // RxJava2与Gson混用
                    .addCallAdapterFactory(RxJavaCallAdapterFactory.create())// RxJava2与Retrofit混用
                    .build();
        }
        return retrofit;
    }
}

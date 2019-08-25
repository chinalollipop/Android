package com.hg3366.a3366.common.http.cphttp;


import com.google.gson.GsonBuilder;
import com.hg3366.a3366.common.http.ClientConfig;
import com.hg3366.a3366.common.http.ProxyCallFactory;
import com.hg3366.common.util.GameLog;

import java.util.concurrent.TimeUnit;

import me.jessyan.retrofiturlmanager.RetrofitUrlManager;
import okhttp3.Call;
import okhttp3.OkHttpClient;
import retrofit2.Retrofit;
import retrofit2.adapter.rxjava.RxJavaCallAdapterFactory;
import retrofit2.converter.gson.GsonConverterFactory;

/**
 * Created by Daniel on 2017/4/17.
 */

public class CPClient {

    private static Retrofit retrofit;
    private static OkHttpClient client;
    private static ClientConfig clientConfig;
    private static ProxyCallFactory proxyCallFactory;
    public static String domainUrl = "http://mck.hg01455.com/";
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
//        domainUrl = "http://10.91.6.1:8082/e03_p01_app_front/";//开发环境http://10.91.6.17:8080/app-front_E03 http://10.91.6.17:8091
//        domainUrl = "http://mc.hg01455.com/";// 本地环境http://m.hg3088_da1.lcn  http://m.hg3088.lcn/  http://192.168.1.15 http://192.168.1.6
//        domainUrl = "http://appfront-e03.w11-online.com/";//运营环境 http://m.hgw777.co http://m.hg50080.com/
        //GameLog.log("get domainUrl:"+domainUrl);
        return domainUrl;
        //return Check.isEmpty(domainUrl)?"http://appfront.h88992.com/":domainUrl;
    }

    public static void setClientDomain(String url){
        domainUrl = url;
        GameLog.log("设置 CP 的域名 domainUrl:"+domainUrl);
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
                    .addInterceptor(new CPReadCookiesInterceptor())
//                    .addInterceptor(new CPSaveCookiesInterceptor())
                    .addInterceptor(new CPLoggerInterceptor())
                    .addInterceptor(new CPTokenInterceptor(clientConfig))
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

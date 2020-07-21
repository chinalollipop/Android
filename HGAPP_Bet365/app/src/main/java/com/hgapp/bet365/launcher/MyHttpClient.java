package com.hgapp.bet365.launcher;

import com.google.gson.Gson;
import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.common.util.GameLog;

import java.util.concurrent.TimeUnit;

import okhttp3.Callback;
import okhttp3.MediaType;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;


/**
 * Created by AK on 2017/8/10.
 */
public class MyHttpClient {

    private String  getRequestBody(Object data)
    {
        Gson gson = new Gson();
        String jsonstr = gson.toJson(data);
        GameLog.log("Game request data:"+jsonstr);
        return gson.toJson(jsonstr);
    }

    public AppTextMessageResponse execute(String url, Object data, Callback callback)  {
        GameLog.log("===== execute url:" + url);
        OkHttpClient client = new OkHttpClient.Builder()
                .readTimeout(30, TimeUnit.SECONDS)
                .connectTimeout(10,TimeUnit.SECONDS)
                .build();
        RequestBody requestBody =  RequestBody.create(MediaType.parse("application/json"),getRequestBody(data));
        Request request = new Request.Builder().post(requestBody).url(url).build();
        GameLog.log("Request "+request.headers().toString());
        /*Response response = client.newCall(request).execute();
        GameLog.log("onResponse:\n" + response.toString());
        String responseText =  response.body().string();
        if(response.isSuccessful()){
            GameLog.log("=====response body:" + responseText);
        }*/
        client.newCall(request).enqueue(callback);

        return new AppTextMessageResponse();
    }

    public AppTextMessageResponse executeGet(String url, Callback callback)  {
        GameLog.log("===== executeGet url:" + url);
        OkHttpClient client = new OkHttpClient.Builder()
                .readTimeout(30, TimeUnit.SECONDS)
                .connectTimeout(10,TimeUnit.SECONDS)
                .build();
//        Request request = new Request.Builder().url(url).addHeader("User-Agent","Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36").build();
        Request request = new Request.Builder().get().url(url).build();
        GameLog.log("Request "+request.headers().toString());
        client.newCall(request).enqueue(callback);

        return new AppTextMessageResponse();
    }
}

package com.sands.corp.common.http;

import com.google.gson.Gson;
import com.google.gson.annotations.SerializedName;
import com.sands.common.util.GameLog;

import java.io.IOException;
import java.util.List;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Request;
import okhttp3.Response;

/**
 * Created by Nereus on 2017/7/25.
 */

public class UrlCenter {

    public interface OnCompleteListener
    {
        public void onComplete(String url);
        public void onError(String msg);
    }
    private String pid;
    private OnCompleteListener listener;
    public UrlCenter(String pid, OnCompleteListener listener)
    {
        this.pid = pid;
        this.listener = listener;
    }

    public void getUrl()
    {
        Request request = new Request.Builder().url("http://b79-01.cdnp1.com/mobile/B79/mobileweb.json").get().build();

        Client.getClient().newCall(request).enqueue(new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                GameLog.loge("获取主站地址出错了 " +e.getMessage());
                if(null != listener)
                {
                    listener.onError("获取主站地址出错了 " + e.getMessage());
                }
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                if(response.isSuccessful()) {
                    String body = response.body().string();
                    GameLog.log("从中心得到:" + body);
                    Gson gson = new Gson();
                    UrlCenterInfo urlCenterInfo = gson.fromJson(body, UrlCenterInfo.class);
                    if(null != urlCenterInfo && null != urlCenterInfo.list )
                    {
                        for(UrlInfo urlInfo : urlCenterInfo.list)
                        {
                            if(pid.equals(urlInfo.pid))
                            {
                                GameLog.log("找到:" + pid + " --> " + urlInfo.url);
                                if(null != listener)
                                {
                                    listener.onComplete(urlInfo.url);
                                }
                                return;
                            }
                        }
                    }
            }
        }});
    }

    class UrlCenterInfo
    {
        @SerializedName("title")
        public String title;
        @SerializedName("list")
        public List<UrlInfo> list;
    }
    class UrlInfo
    {
        @SerializedName("pid")
        public String pid;
        @SerializedName("url")
        public String url;
    }
}

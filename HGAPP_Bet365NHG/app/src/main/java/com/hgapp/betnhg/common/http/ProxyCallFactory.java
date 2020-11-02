package com.hgapp.betnhg.common.http;

import com.hgapp.betnhg.common.http.util.RequestBuilder;
import com.hgapp.common.util.Timber;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.OkHttpClient;
import okhttp3.Request;

/**
 * Created by Nereus on 2017/5/12.
 * 这是一个OkHttpClient(callfactory)代理，它修改了请求信息，用于如下场景
 * 请求参数需要加密
 * 请求体需要加密
 *
 * 这样的场景下，代理可以统一将明文加密成密文，然后交给OkHttpClient执行
 */

public class ProxyCallFactory implements Call.Factory {
    private OkHttpClient client;
    private ClientConfig clientConfig;
    public ProxyCallFactory(OkHttpClient client,ClientConfig clientConfig)
    {
        this.clientConfig = clientConfig;
        this.client = client;
    }

    @Override
    public Call newCall(Request request) {
        try {
            //String token = userManager.getLocalUserInfo().getToken();
            RequestBuilder builder = new RequestBuilder(clientConfig,"");
            return client.newCall(builder.newRequest(request));
        } catch (IOException e) {
            Timber.e(e,"有毛病，不能CallFactory中转换请求");
        }
        return client.newCall(request);
    }
}

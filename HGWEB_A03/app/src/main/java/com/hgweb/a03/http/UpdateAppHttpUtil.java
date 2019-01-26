package com.hgweb.a03.http;

import android.support.annotation.NonNull;

import com.hgweb.a03.utils.GameLog;
import com.vector.update_app.HttpManager;
import com.zhy.http.okhttp.OkHttpUtils;
import com.zhy.http.okhttp.callback.FileCallBack;
import com.zhy.http.okhttp.callback.StringCallback;

import java.io.File;
import java.util.Map;

import okhttp3.Call;
import okhttp3.Request;
import okhttp3.Response;


public class UpdateAppHttpUtil implements HttpManager {
    @Override
    public void asyncGet(@NonNull String url, @NonNull Map<String, String> params, @NonNull final Callback callBack) {
        OkHttpUtils.get()
                .url(url)
                .params(params)
                .build()
                .execute(new StringCallback() {
                    @Override
                    public void onError(Call call, Response response, Exception e, int id) {
                        GameLog.log("GET获取的错误信息是："+response);
                        callBack.onError(validateError(e, response));
                    }

                    @Override
                    public void onResponse(String response, int id) {
                        GameLog.log("GET获取的数据信息是："+response);
                        //callBack.onError(response);
                        callBack.onResponse(response);
                    }
                });
    }

    @Override
    public void asyncPost(@NonNull String url, @NonNull Map<String, String> params, @NonNull final Callback callBack) {

        OkHttpUtils.post()
                .url(url)
                .params(params)
                .build()
                .execute(new StringCallback() {
                    @Override
                    public void onError(Call call, Response response, Exception e, int id) {
                        GameLog.log("POST获取的错误信息是："+response);
                        callBack.onError(validateError(e, response));
                    }

                    @Override
                    public void onResponse(String response, int id) {
                        GameLog.log("POST获取的数据信息是："+response);
                        callBack.onResponse(response);
                    }
                });

    }

    @Override
    public void download(@NonNull String url, @NonNull String path, @NonNull String fileName, @NonNull final FileCallback callback) {
        OkHttpUtils.get()
                .url(url)
                .build()
                .execute(new FileCallBack(path, fileName) {
                    @Override
                    public void inProgress(float progress, long total, int id) {
                        super.inProgress(progress, total, id);
                        callback.onProgress(progress, total);
                    }

                    @Override
                    public void onError(Call call, Response response, Exception e, int id) {
                        callback.onError(validateError(e, response));
                    }

                    @Override
                    public void onResponse(File response, int id) {
                        callback.onResponse(response);

                    }

                    @Override
                    public void onBefore(Request request, int id) {
                        super.onBefore(request, id);
                        callback.onBefore();
                    }
                });
    }
}

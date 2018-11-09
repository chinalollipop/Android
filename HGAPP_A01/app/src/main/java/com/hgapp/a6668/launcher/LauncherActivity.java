package com.hgapp.a6668.launcher;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;

import com.google.gson.Gson;
import com.hgapp.a6668.MainActivity;
import com.hgapp.a6668.R;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.data.DomainUrl;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.NetworkUtils;
import com.hgapp.common.util.ToastUtils;

import java.io.IOException;
import java.util.List;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

/**
 * Created by Daniel on 2018/8/18.
 */

public class LauncherActivity extends AppCompatActivity{

    private boolean ifStop = false;
    MyHttpClient myHttpClient = new MyHttpClient();
    Button button;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_launcher);
        button = (Button)findViewById(R.id.retry);
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                onGetAvailableDomain();
            }
        });
        onGetAvailableDomain();
    }

    //获取可用域名
    public void onGetAvailableDomain() {
        /**
         * https://hg00086.firebaseapp.com/y/hg6668.ini     6668的域名地址
         * https://hg00086.firebaseapp.com/y/hg0086.ini     0086的域名地址
         * https://hg00086.firebaseapp.com/ym.conf
         */
        if(!NetworkUtils.isConnected())
        {
            ToastUtils.showLongToast("无网络连接！");
        }
        //String domainUrl = "https://hg00086.firebaseapp.com/y/hg0086.ini";
        String domainUrl = "https://hg00086.firebaseapp.com/y/hg6668_1.txt";
        myHttpClient.executeGet(domainUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                button.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("====================1=======================");
                    }
                });
                String demainUrl =  ACache.get(getApplicationContext()).getAsString(HGConstant.APP_DEMAIN_URL);
                if(!Check.isEmpty(demainUrl)){
                    Client.setClientDomain(demainUrl);
                }else{
                    Client.setClientDomain(Client.domainUrl );
                }
                enterMain();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                if(response.isSuccessful()){
                    onGetSuccessDomain(responseText);
                }
            }
        });
    }

    public void enterMain()
    {
        startActivity(new Intent(LauncherActivity.this,MainActivity.class));
        finish();
    }

    private void postDomain(final String demain){
        myHttpClient.executeGet(demain+"answer.php", new Callback() {//
            @Override
            public void onFailure(Call call, final IOException e) {
                GameLog.log("request url error: " + e.toString());
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                if(ifStop){
                    GameLog.log("====================2=======================");
                    return;
                }
                if(response.isSuccessful()){
                    ifStop = true;
                    GameLog.log("最终的域名是："+demain);
                    ACache.get(getApplicationContext()).put(HGConstant.APP_DEMAIN_URL,demain);
                    Client.setClientDomain(demain);
                    enterMain();
                }
            }
        });
    }

    private void onGetSuccessDomain(String responseText) {
        try {
            DomainUrl domainUrl = new Gson().fromJson(responseText, DomainUrl.class);
            final List<DomainUrl.ListBean> domains = domainUrl.getList();
            for(int k=0;k<domains.size();++k){
                if(ifStop){
                    return ;
                }
                postDomain(domains.get(k).getUrl());
            }
        } catch (Exception e) {
            GameLog.log("request url : " + e.toString());
        }
        if(!ifStop){
            button.postDelayed(new Runnable() {
                @Override
                public void run() {
                    if(!ifStop){
                        String demainUrl =  ACache.get(getApplicationContext()).getAsString(HGConstant.APP_DEMAIN_URL);
                        if(!Check.isEmpty(demainUrl)){
                            Client.setClientDomain(demainUrl);
                        }else{
                            Client.setClientDomain(Client.domainUrl );
                        }
                        if(ifStop){
                            GameLog.log("====================3=======================");
                            return;
                        }
                        enterMain();
                        ifStop = true;
                        ToastUtils.showLongToast("网络缓慢，请切换网络或联系客服");
                        GameLog.log("网络缓慢，请切换网络或联系客服");
                    }

                }
            },6000);
        }
    }

}

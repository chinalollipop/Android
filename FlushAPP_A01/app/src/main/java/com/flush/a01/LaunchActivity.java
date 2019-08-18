package com.flush.a01;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.ImageView;

import com.google.gson.Gson;
import com.flush.a01.http.DomainUrl;
import com.flush.a01.http.MyHttpClient;
import com.flush.a01.utils.ACache;
import com.flush.a01.utils.GameLog;
import com.flush.a01.utils.NetworkUtils;
import com.flush.a01.utils.ToastUtils;

import java.io.IOException;
import java.util.List;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

public class LaunchActivity extends AppCompatActivity implements View.OnClickListener {
    private boolean ifStop = false;
    MyHttpClient myHttpClient = new MyHttpClient();
    ImageView homeRegister,homeLogin,homeDemo;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_launch);
        homeRegister = (ImageView)findViewById(R.id.homeRegister);
        homeLogin = (ImageView)findViewById(R.id.homeLogin);
        homeDemo = (ImageView)findViewById(R.id.homeDemo);
        homeRegister.setOnClickListener(this);
        homeLogin.setOnClickListener(this);
        homeDemo.setOnClickListener(this);
        //onGetAvailableDomain();
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
        String domainUrl = "https://hg00086.firebaseapp.com/y/cf.txt";
        myHttpClient.executeGet(domainUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                homeRegister.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("====================1=======================");
                    }
                });
                String demainUrl =  ACache.get(getApplicationContext()).getAsString("app_demain_url");
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

    private void enterMain(){
        startActivity(new Intent(LaunchActivity.this, MainActivity.class));
        finish();
    }

    private synchronized void postDomain(final String demain){
        homeRegister.post(new Runnable() {
            @Override
            public void run() {
                GameLog.log("====================请求的域名是======================="+demain);
            }
        });
        myHttpClient.executeGet(demain+"api/answer.php", new Callback() {//
            @Override
            public void onFailure(Call call, final IOException e) {
                GameLog.log("request url error: " + e.toString());
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                if(ifStop){
                    homeRegister.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("停止请求："+demain);
                        }
                    });
                    GameLog.log("====================2=======================");
                    return;
                }
                if(response.isSuccessful()){
                    ifStop = true;
                    homeRegister.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("最终的域名是："+demain);
                        }
                    });

                    ACache.get(getApplicationContext()).put("app_demain_url",demain);
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
            homeRegister.postDelayed(new Runnable() {
                @Override
                public void run() {
                    if(!ifStop){
                        String demainUrl =  ACache.get(getApplicationContext()).getAsString("app_demain_url");
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

    @Override
    public void onClick(View v) {
        switch (v.getId()){
            case R.id.homeRegister:
                break;
            case R.id.homeLogin:

                break;
            case R.id.homeDemo:
                break;
        }
    }
}

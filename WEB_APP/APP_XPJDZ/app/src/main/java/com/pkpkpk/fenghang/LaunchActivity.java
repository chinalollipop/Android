package com.pkpkpk.fenghang;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;

import com.google.gson.Gson;
import com.pkpkpk.fenghang.http.DomainUrl;
import com.pkpkpk.fenghang.http.MyHttpClient;
import com.pkpkpk.fenghang.utils.ACache;
import com.pkpkpk.fenghang.utils.GameLog;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

public class LaunchActivity extends AppCompatActivity {

    private boolean ifStop = false;
    private boolean isEnter = false;
    MyHttpClient myHttpClient = new MyHttpClient();
    Button button;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_launch);
        button = (Button)findViewById(R.id.retry);
        button.postDelayed(new Runnable() {
            @Override
            public void run() {
                enterMain();
            }
        },1500);
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                enterMain();
            }
        });
        //onGetAvailableDomain();
    }


    //获取可用域名
    public void onGetAvailableDomain() {
        /**
         * https://hg00086.firebaseapp.com/y/hg6668.ini     6668的域名地址
         * https://hg00086.firebaseapp.com/y/hg0086.ini     0086的域名地址
         * https://hg00086.firebaseapp.com/ym.conf
         */
        //String domainUrl = "https://hg00086.firebaseapp.com/y/hg0086.ini";
        /*String domainUrl = "https://hg-test.gz.bcebos.com/xpj.txt";
//        String domainUrl = "https://new-domain.gz.bcebos.com/8m.txt";
        myHttpClient.executeGet(domainUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                enterMain();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                if(response.isSuccessful()){
                    onGetSuccessDomain(responseText);
                }
            }
        });*/
       // String domainUrl = "https://3013777.com/m/login/logindo?username=coco333&password=qqq111&vlcodes=&mobile=&code=&nickname=";
        /*String domainUrl = "https://3013777.com/m/login/logindo";
        myHttpClient.execute(domainUrl,"username=coco333&password=qqq111&vlcodes=&mobile=&code=&nickname=", new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                enterMain();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                if(response.isSuccessful()){
                    onGetSuccessDomain(responseText);
                }
            }
        });*/
    }

    public void enterMain()
    {
        if(isEnter){
            return;
        }
        startActivity(new Intent(LaunchActivity.this,MainActivity.class));
        finish();
        isEnter = true;
    }

    private void postDomain(final String demain){
        myHttpClient.executeGet(demain,new Callback() {//
            @Override
            public void onFailure(Call call, final IOException e) {
                GameLog.log("request url error: " + e.toString());
                //enterMain();
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
                    ACache.get(getApplicationContext()).put("APP_DEMAIN_URL",demain);
                    enterMain();
                }
            }
        });
    }

    private void onGetSuccessDomain(String responseText) {
        try {
            DomainUrl domainUrl = new Gson().fromJson(responseText, DomainUrl.class);
            ACache.get(getApplicationContext()).put("APP_DEMAIN_URL",domainUrl.getDomainUrl());
            ACache.get(getApplicationContext()).put("APP_DEMAIN_URL_DepositsUrl",domainUrl.getDomainUrl()+domainUrl.getDepositsUrl());
            ACache.get(getApplicationContext()).put("APP_DEMAIN_URL_WithdrawUrl",domainUrl.getDomainUrl()+domainUrl.getWithdrawUrl());
            enterMain();
        } catch (Exception e) {
            GameLog.log("request url : " + e.toString());
        }
    }

}

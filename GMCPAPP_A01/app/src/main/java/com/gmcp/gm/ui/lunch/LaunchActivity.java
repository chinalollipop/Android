package com.gmcp.gm.ui.lunch;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;

import com.alibaba.fastjson.JSON;
import com.gmcp.gm.CFConstant;
import com.gmcp.gm.R;
import com.gmcp.gm.common.http.Client;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.data.DomainUrl;
import com.gmcp.gm.common.http.MyHttpClient;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.utils.NetworkUtils;
import com.gmcp.gm.common.utils.ToastUtils;
import com.gmcp.gm.ui.main.MainActivity;
import com.google.gson.Gson;

import java.io.IOException;
import java.util.List;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

import static com.gmcp.gm.common.utils.Utils.getContext;

public class LaunchActivity extends Activity {
    private boolean ifStop = false;
    MyHttpClient myHttpClient = new MyHttpClient();
    Button button;
    private MyCountDownTimer mCountDownTimer;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //去除标题栏
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        //去除状态栏
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
                WindowManager.LayoutParams.FLAG_FULLSCREEN);
        try {
            setContentView(R.layout.activity_launch);
        }catch (Exception e){
            e.printStackTrace();
        }
        button = findViewById(R.id.retry);
        //创建倒计时类
        mCountDownTimer = new MyCountDownTimer(6000, 1000);
        mCountDownTimer.start();
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String demainUrl =  ACache.get(getApplicationContext()).getAsString("app_demain_url");
                if(!Check.isEmpty(demainUrl)){
                    enterMain();
                }else{
                    onGetAvailableDomain();
                }
            }
        });
        String urlLoad = ACache.get(getApplicationContext()).getAsString(CFConstant.USERNAME_LOAD_LUNCHER);
        if("0".equals(urlLoad)||Check.isEmpty(urlLoad)){
            onGetAvailableDomain();
        }else{
            enterMain();
        }
    }


    //获取可用域名
    public void onGetAvailableDomain() {
        /**
         * https://hg00086.firebaseapp.com/y/hg6668.ini     6668的域名地址
         * https://hg00086.firebaseapp.com/y/hg0086.ini     0086的域名地址
         * https://hg00086.firebaseapp.com/ym.conf
         */
        if (!NetworkUtils.isConnected()) {
            ToastUtils.showLongToast("无网络连接！");
        }
        //String domainUrl = "https://hg00086.firebaseapp.com/y/hg0086.ini";
//        String domainUrl = "https://hg00086.firebaseapp.com/y/cf.txt";
        String domainUrl = "https://cp-appdown.firebaseapp.com/gm.txt";
        myHttpClient.executeGet(domainUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                button.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("====================1=======================");
                    }
                });
                String demainUrl = ACache.get(getContext()).getAsString("app_demain_url");
                if(!Check.isEmpty(demainUrl)){
                    Client.setClientDomain(demainUrl);
                }else{
                    Client.setClientDomain(Client.domainUrl);
                }
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                if (response.isSuccessful()) {
                    onGetSuccessDomain(responseText);
                }else{
                    String demainUrl = ACache.get(getContext()).getAsString("app_demain_url");
                    if(!Check.isEmpty(demainUrl)){
                        Client.setClientDomain(demainUrl);
                    }else{
                        Client.setClientDomain(Client.domainUrl);
                    }
                }
            }
        });
    }

    private void enterMain() {
        if (mCountDownTimer != null) {
            mCountDownTimer.cancel();
            mCountDownTimer = null;
            GameLog.log("===============enterMain====================加载了数据========================");
        }
        ACache.get(getApplicationContext()).put(CFConstant.USERNAME_LOAD_LUNCHER,"1");
        startActivity(new Intent(LaunchActivity.this, MainActivity.class));
        finish();
    }

    private synchronized void postDomain(final String demain) {
        button.post(new Runnable() {
            @Override
            public void run() {
                GameLog.log("====================请求的域名是=======================" + demain);
            }
        });
        myHttpClient.executeGet(demain + "service?packet=Release&action=Answer&terminal_id=2", new Callback() {//
            @Override
            public void onFailure(Call call, final IOException e) {
                GameLog.log("request url error: " + e.toString());
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                if (ifStop) {

                    button.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("停止请求：" + demain);
                            GameLog.log("停止请求请求返回的消息：" + responseText);
                            GameLog.log("====================2=======================");
                        }
                    });
                    return;
                }
                if (response.isSuccessful()) {
                    ifStop = true;
                    button.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("最终的域名是：" + demain);
                            GameLog.log("最终的域名请求返回的消息：" + responseText);
                            ACache.get(getContext()).put("app_demain_url", demain);
                            int size = domainUrl.getList().size();
                            for(int k=0;k<size;++k){
                                if(domainUrl.getList().get(k).getUrl().equals(demain)){
                                    domainUrl.getList().get(k).setChecked(true);
                                }else{
                                    domainUrl.getList().get(k).setChecked(false);
                                }
                            }
                            ACache.get(getContext()).put("homeLineChoice", JSON.toJSONString(domainUrl));
                            Client.setClientDomain(demain);
                        }
                    });

                }
            }
        });
    }
    DomainUrl domainUrl;
    private void onGetSuccessDomain(String responseText) {
        try {
            domainUrl= new Gson().fromJson(responseText, DomainUrl.class);
            final List<DomainUrl.ListBean> domains = domainUrl.getList();
            for (int k = 0; k < domains.size(); ++k) {
                if (ifStop) {
                    return;
                }
                postDomain(domains.get(k).getUrl());
            }
        } catch (Exception e) {
            GameLog.log("request url : " + e.toString());
            String demainUrl = ACache.get(getContext()).getAsString("app_demain_url");
            if(!Check.isEmpty(demainUrl)){
                Client.setClientDomain(demainUrl);
            }else{
                Client.setClientDomain(Client.domainUrl);
            }
        }
    }

    class MyCountDownTimer extends CountDownTimer {
        /**
         * @param millisInFuture
         *      表示以「 毫秒 」为单位倒计时的总数
         *      例如 millisInFuture = 1000 表示1秒
         *
         * @param countDownInterval
         *      表示 间隔 多少微秒 调用一次 onTick()
         *      例如: countDownInterval = 1000 ; 表示每 1000 毫秒调用一次 onTick()
         *
         */

        public MyCountDownTimer(long millisInFuture, long countDownInterval) {
            super(millisInFuture, countDownInterval);
        }


        public void onFinish() {
            button.setText("0s 跳过");
            enterMain();
        }

        public void onTick(long millisUntilFinished) {
            button.setText( millisUntilFinished / 1000 + "s 跳过");
        }

    }

}

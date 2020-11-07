package com.hgapp.betnhg.launcher;

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
import com.google.gson.Gson;
import com.hgapp.betnhg.MainActivity;
import com.hgapp.betnhg.R;
import com.hgapp.betnhg.common.http.Client;
import com.hgapp.betnhg.common.util.ACache;
import com.hgapp.betnhg.common.util.HGConstant;
import com.hgapp.betnhg.data.DomainUrl;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.NetworkUtils;
import com.hgapp.common.util.ToastUtils;

import java.io.IOException;
import java.util.List;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

import static com.hgapp.common.util.Utils.getContext;

/**
 * Created by Daniel on 2018/8/18.
 */

public class LauncherActivity extends AppCompatActivity{

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
        setContentView(R.layout.activity_launcher);
        button = (Button)findViewById(R.id.retry);
        //创建倒计时类
        mCountDownTimer = new MyCountDownTimer(6000, 1000);
        mCountDownTimer.start();
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String demainUrl =  ACache.get(getApplicationContext()).getAsString(HGConstant.APP_DEMAIN_URL);
                if(!Check.isEmpty(demainUrl)){
                    enterMain();
                }else{
                    onGetAvailableDomain();
                }
            }
        });
        onGetAvailableDomain();
        /*EntranceUtils.getInstance().init(this
                ,"com.hgapp.a6668.EntranceDefault"
                ,"com.hgapp.a6668.EntranceSpec");*/
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


    @Override
    protected void onDestroy() {
        if (mCountDownTimer != null) {
            mCountDownTimer.cancel();
            GameLog.log("===============mCountDownTimer.cancel====================加载了数据========================");
        }
        super.onDestroy();

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
        String domainUrl = "https://new-domain.gz.bcebos.com/newhg.txt";
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
                //enterMain();
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
        if (mCountDownTimer != null) {
            mCountDownTimer.cancel();
            mCountDownTimer = null;
            GameLog.log("===============enterMain====================加载了数据========================");
        }
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
                    button.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("停止请求：" + demain);
                        }
                    });
                    GameLog.log("====================2=======================");
                    return;
                }
                if(response.isSuccessful()){
                    button.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("最终的域名是：" + demain);
                            ifStop = true;
                            ACache.get(LauncherActivity.this).put(HGConstant.APP_DEMAIN_URL,demain);
                            int size = domainUrl.getList().size();
                            for(int k=0;k<size;++k){
                                if(domainUrl.getList().get(k).getUrl().equals(demain)){
                                    domainUrl.getList().get(k).setChecked(true);
                                }
                            }
                            ACache.get(getContext()).put("homeLineChoice", JSON.toJSONString(domainUrl));
                            Client.setClientDomain(demain);
                        }
                    });
                    //enterMain();
                }
            }
        });
    }
    DomainUrl domainUrl;
    private void onGetSuccessDomain(String responseText) {
        try {
            domainUrl = new Gson().fromJson(responseText, DomainUrl.class);
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
        /*if(!ifStop){
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
                        //enterMain();
                        ifStop = true;
                        ToastUtils.showLongToast("网络缓慢，请切换网络或联系客服");
                        GameLog.log("网络缓慢，请切换网络或联系客服");
                    }

                }
            },6000);
        }*/
    }

}

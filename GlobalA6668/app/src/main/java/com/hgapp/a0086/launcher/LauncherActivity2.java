package com.hgapp.a0086.launcher;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;

import com.hgapp.a0086.MainActivity;
import com.hgapp.a0086.R;
import com.hgapp.a0086.common.http.Client;
import com.hgapp.common.util.NetworkUtils;
import com.hgapp.common.util.ToastUtils;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

/**
 * Created by Daniel on 2018/8/18.
 */

public class LauncherActivity2 extends AppCompatActivity{

    private Button retryButton;
    private Handler handler = new Handler();
    private int DELAY=2*1000;
    private boolean ready = false;
    private boolean ifStop = false;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_launcher);
        retryButton = (Button)findViewById(R.id.retry);
        retryButton.setVisibility(View.GONE);
        retryButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                login();
            }
        });
        if(!NetworkUtils.isConnected())
        {
            ToastUtils.showLongToast("无网络连接！");
            ready = true;
            handler.postDelayed(task,DELAY);
            return;
        }
        onGetAvailableDomain();
    }

    //获取可用域名
    public void onGetAvailableDomain() {
        MyHttpClient myHttpClient = new MyHttpClient();
        /**
         * https://hg00086.firebaseapp.com/y/hg6668.ini     6668的域名地址
         * https://hg00086.firebaseapp.com/y/hg0086.ini     0086的域名地址
         * https://hg00086.firebaseapp.com/ym.conf
         */
        String domainUrl = "https://hg00086.firebaseapp.com/y/hg0086.ini";
        myHttpClient.executeGet(domainUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                /*retryButton.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("onFailure:\n" + e.toString());
                        ToastUtils.showLongToast("当前高峰期，正在搜索最佳线路...");
                    }
                });*/
                Client.setClientDomain(Client.domainUrl );
                enterMain();
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                /*retryButton.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("onResponse body:" + responseText);
                    }
                });*/
                //GameLog.log("onResponse body:" + responseText);
                if(response.isSuccessful()){
                    Client.setClientDomain(responseText );
                    enterMain();
                }
            }
        });
    }

    private void login()
    {
        handler.postDelayed(task,DELAY);
    }

    @Override
    public void onDestroy()
    {
        super.onDestroy();
        handler.removeCallbacks(task);
    }

    private Runnable task = new Runnable() {
        @Override
        public void run() {
            if(ready)
            {
                enterMain();
            }
            else
            {
                handler.postDelayed(this,1000);
            }

        }
    };

    private void showRetryButton()
    {
        retryButton.setVisibility(View.VISIBLE);
    }

    public void enterMain()
    {
        startActivity(new Intent(LauncherActivity2.this,MainActivity.class));
        finish();
    }

}

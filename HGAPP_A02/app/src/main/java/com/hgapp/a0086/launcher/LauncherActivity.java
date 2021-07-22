package com.hgapp.a0086.launcher;

import android.content.ComponentName;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;

import com.alibaba.fastjson.JSON;
import com.google.gson.Gson;
import com.hgapp.a0086.MainActivity;
import com.hgapp.a0086.R;
import com.hgapp.a0086.common.http.Client;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.data.DomainUrl;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.NetworkUtils;
import com.hgapp.common.util.ToastUtils;

import java.io.IOException;
import java.util.List;

import me.jessyan.retrofiturlmanager.RetrofitUrlManager;
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

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        RetrofitUrlManager.getInstance().setGlobalDomain(Client.domainUrl);
        setContentView(R.layout.activity_launcher);
        button = (Button)findViewById(R.id.retry);
        button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                onGetAvailableDomain();
            }
        });
        onGetAvailableDomain();
        /*String isLogoChange = ACache.get(LauncherActivity.this).getAsString("change_logo");
        GameLog.log("目前的状态是  " +isLogoChange);
        if(Check.isEmpty(isLogoChange)){
            changeLauncher( "com.hgapp.a0086.LauncherActivity1");
        }else{
            changeLauncher( "com.hgapp.a0086.launcher.LauncherActivity");
        }*/

    }

    private void changeLauncher(String name) {
        PackageManager pm = getPackageManager();
        //隐藏之前显示的桌面组件
        pm.setComponentEnabledSetting(getComponentName(),
                PackageManager.COMPONENT_ENABLED_STATE_DISABLED, PackageManager.DONT_KILL_APP);
        //显示新的桌面组件
        pm.setComponentEnabledSetting(new ComponentName(LauncherActivity.this, name),
                PackageManager.COMPONENT_ENABLED_STATE_ENABLED, PackageManager.DONT_KILL_APP);
        ACache.get(LauncherActivity.this).put("change_logo","1");
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
        //String domainUrl = "https://hg00086.firebaseapp.com/y/hg0086_1.txt";
        String domainUrl = "https://hg0086.appdolo.com/hg0086.txt";
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
        startActivity(new Intent(LauncherActivity.this, MainActivity.class));
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
                    button.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("最终的域名是："+demain);
                            ACache.get(LauncherActivity.this).put(HGConstant.APP_DEMAIN_URL,demain);
                            int size = domainUrl.getList().size();
                            for(int k=0;k<size;++k){
                                if(domainUrl.getList().get(k).getUrl().equals(demain)){
                                    domainUrl.getList().get(k).setChecked(true);
                                }
                            }
                            ACache.get(getContext()).put("homeLineChoice", JSON.toJSONString(domainUrl));
                            Client.setClientDomain(demain);
                            RetrofitUrlManager.getInstance().setGlobalDomain(demain);
                            enterMain();
                        }
                    });


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
        if(!ifStop){
            button.postDelayed(new Runnable() {
                @Override
                public void run() {
                    if(!ifStop) {
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

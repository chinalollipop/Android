package com.hgapp.m8.launcher;

import android.app.Activity;
import android.app.ActivityManager;
import android.content.ComponentName;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
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
import com.hgapp.m8.MainActivity;
import com.hgapp.m8.R;
import com.hgapp.m8.common.http.Client;
import com.hgapp.m8.common.util.ACache;
import com.hgapp.m8.common.util.EntranceUtils;
import com.hgapp.m8.common.util.HGConstant;
import com.hgapp.m8.data.DomainUrl;
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
       /* GameLog.log("===================================加载了数据========================");
        String isLogoChange = ACache.get(LauncherActivity.this).getAsString("change_logo");
        GameLog.log("目前的状态是  " +isLogoChange);
        //changeLauncher("com.hgapp.a6668.LauncherActivity2");
        if(Check.isEmpty(isLogoChange)){
            ACache.get(LauncherActivity.this).put("change_logo","1");
            EntranceUtils.getInstance().enable(this,"com.hgapp.a6668.EntranceSpec");
            //changeLauncher("com.hgapp.a6668.launcher.LauncherAliasActivity");
            //start();
        }*//*else{
            changeLauncher( "com.hgapp.a6668.launcher.LauncherActivity");
        }*/
    }

    /**
     * @param useCode =1、为活动图标 =2 为用普通图标 =3、不启用判断
     */
    private void switchIcon(int useCode) {

        try {
            //要跟manifest的activity-alias 的name保持一致
            String icon_tag = "com.weechan.shidexianapp.icon_tag";
            String icon_tag_1212 = "com.weechan.shidexianapp.icon_tag_1212";

            if (useCode != 3) {

                PackageManager pm = getPackageManager();

                ComponentName normalComponentName = new ComponentName(
                        getBaseContext(),
                        icon_tag);
                //正常图标新状态
                int normalNewState = useCode == 2 ? PackageManager.COMPONENT_ENABLED_STATE_ENABLED
                        : PackageManager.COMPONENT_ENABLED_STATE_DISABLED;
                if (pm.getComponentEnabledSetting(normalComponentName) != normalNewState) {//新状态跟当前状态不一样才执行
                    pm.setComponentEnabledSetting(
                            normalComponentName,
                            normalNewState,
                            PackageManager.DONT_KILL_APP);
                }

                ComponentName actComponentName = new ComponentName(
                        getBaseContext(),
                        icon_tag_1212);
                //正常图标新状态
                int actNewState = useCode == 1 ? PackageManager.COMPONENT_ENABLED_STATE_ENABLED
                        : PackageManager.COMPONENT_ENABLED_STATE_DISABLED;
                if (pm.getComponentEnabledSetting(actComponentName) != actNewState) {//新状态跟当前状态不一样才执行

                    pm.setComponentEnabledSetting(
                            actComponentName,
                            actNewState,
                            PackageManager.DONT_KILL_APP);
                }

            }
        } catch (Exception e) {
        }

    }



    /**
     * 立即开始执行，如果不执行start方法，根据ROM的不同，在禁用了组件之后，会等一会，Launcher也会自动刷新图标。
     */
    /*private void start() {
        Intent intent = new Intent(Intent.ACTION_MAIN);
        intent.addCategory(Intent.CATEGORY_HOME);
        intent.addCategory(Intent.CATEGORY_DEFAULT);
        PackageManager packageManager = getPackageManager();
        ActivityManager activityManager = (ActivityManager) getSystemService(Activity.ACTIVITY_SERVICE);
        List<ResolveInfo> resolves = packageManager.queryIntentActivities(intent, PackageManager.COMPONENT_ENABLED_STATE_DEFAULT); // 默认启用状态
        for (ResolveInfo res : resolves) {
            if (res.activityInfo != null) {
                activityManager.killBackgroundProcesses(res.activityInfo.packageName); // 杀死后台进程
            }
        }
    }*/

    private void changeLauncher(String name) {
        PackageManager pm = getPackageManager();
        //隐藏之前显示的桌面组件
        pm.setComponentEnabledSetting(new ComponentName(LauncherActivity.this, "com.hgapp.a6668.launcher.LauncherActivity"),
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
        String domainUrl = "https://new-domain.gz.bcebos.com/8m.txt";
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

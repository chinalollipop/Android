package com.youjie.cfcpnew.activity;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Intent;
import android.content.IntentFilter;
import android.net.ConnectivityManager;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Handler;
import android.text.TextUtils;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions;
import com.bumptech.glide.request.RequestOptions;
import com.google.gson.Gson;
import com.lzy.okgo.OkGo;
import com.lzy.okgo.callback.StringCallback;
import com.lzy.okgo.model.Response;
import com.youjie.cfcpnew.BuildConfig;
import com.youjie.cfcpnew.R;
import com.youjie.cfcpnew.YouJieApplication;
import com.youjie.cfcpnew.http.Constant;
import com.youjie.cfcpnew.model.DomainBean;
import com.youjie.cfcpnew.model.UrlBean;
import com.youjie.cfcpnew.receiver.NetBroadCastReceiver;
import com.youjie.cfcpnew.utils.ACache;
import com.youjie.cfcpnew.utils.GameLog;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import cn.jiguang.analytics.android.api.JAnalyticsInterface;

/**
 * Created by Colin on 2017/12/14.
 * 启动页
 */
public class SplashActivity extends Activity {

    @BindView(R.id.iv_ads)
    ImageView ivAds;
    @BindView(R.id.ll_skip)
    LinearLayout llSkip;
    @BindView(R.id.tv_skip)
    TextView tvSkip;
    @BindView(R.id.iv_logo)
    ImageView ivLogo;
    @BindView(R.id.rl_ad)
    RelativeLayout rlAd;
    private String URL ;
    private CountDownTimer countDownTimer;
    private boolean ifStop = false;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
        ButterKnife.bind(this);
        JAnalyticsInterface.onPageStart(this, this.getClass().getCanonicalName());
        initDomainUrl();
        //new Handler().postDelayed(this::initView, 1500);//1.5秒后执行Runnable中的run方法
    }


    private void initDomainUrl(){

        OkGo.<String>post("https://hg006668.firebaseapp.com/d/cfqp_init.txt")
                .tag(this)
                .execute(new StringCallback() {
                    @Override
                    public void onError(Response<String> response) {
                        super.onError(response);
                    }

                    @SuppressLint("CheckResult")
                    @Override
                    public void onSuccess(Response<String> response) {
                        if(response.isSuccessful()){
                            onGetSuccessDomain(response.body());
                        }
                    }
                });
    }

    private void onGetSuccessDomain(String responseText) {
        try {
            DomainBean domainUrl = new Gson().fromJson(responseText, DomainBean.class);
            final List<DomainBean.ListBean> domains = domainUrl.getList();
            for(int k=0;k<domains.size();++k){
                if(ifStop){
                    return ;
                }
                postDomain(domains.get(k).getUrl());
            }
        } catch (Exception e) {
            GameLog.log("request url : " + e.toString());
        }

        }

    private synchronized void postDomain(String url) {
        GameLog.log("--------当前执行的url地址是-------- "+url);
        OkGo.<String>post(url+"answer.php")
                .tag(this)
                .execute(new StringCallback() {
                    @Override
                    public void onError(Response<String> response) {
                        super.onError(response);
                    }

                    @SuppressLint("CheckResult")
                    @Override
                    public void onSuccess(Response<String> response) {
                        if(ifStop){
                            return;
                        }
                        if(response.isSuccessful()){
                            ifStop = true;
                            GameLog.log("--------最终的url地址是-------- "+url);
                            ACache.get(getApplicationContext()).put(Constant.APP_URL,url);
                            //onGetSuccessDomain(response.body());
                            initView();
                        }
                    }
                });
    }


    private void initView() {
        //请求chartRoom聊天室、onlineBetting在线投注、lotteryVideo开奖视频、lotteryHint长龙提醒线路
        OkGo.<String>post(ACache.get(getApplicationContext()).getAsString(Constant.APP_URL) + BuildConfig.FLAVOR)
                .tag(this)
                .execute(new StringCallback() {
                    @Override
                    public void onError(Response<String> response) {
                        super.onError(response);
                        gotoMainUI();
                    }

                    @SuppressLint("CheckResult")
                    @Override
                    public void onSuccess(Response<String> response) {
                        URL = response.body();
                        if (TextUtils.isEmpty(new Gson().fromJson(URL, UrlBean.class).img_path)) {
                            gotoMainUI();
                        } else {
                            ivLogo.setVisibility(View.VISIBLE);
                            rlAd.setVisibility(View.VISIBLE);
                            UrlBean urlBean = new Gson().fromJson(URL, UrlBean.class);
                            RequestOptions requestOptions = new RequestOptions();
                            requestOptions.centerCrop();
                            Glide.with(SplashActivity.this).load(urlBean.img_path).transition(DrawableTransitionOptions.withCrossFade()).apply(requestOptions).into(ivAds);
                            // 倒计时5秒，一次1秒
                            countDownTimer = new CountDownTimer(5 * 1000, 1000) {
                                @Override
                                public void onTick(long millisUntilFinished) {
                                    String second = " " + (millisUntilFinished / 1000 + 1);
                                    llSkip.setVisibility(View.VISIBLE);
                                    tvSkip.setText(second);
                                }

                                @Override
                                public void onFinish() {
                                    gotoMainUI();
                                }
                            }.start();
                        }
                    }
                });
    }

    private void gotoMainUI() {
        Intent intent = new Intent(SplashActivity.this, MainActivity.class);
        Bundle bundle = new Bundle();
        bundle.putString(Constant.WEBVIEW_URL, URL);
        intent.putExtras(bundle);
        startActivity(intent);
        this.finish();
    }


    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (countDownTimer != null) {
            countDownTimer.cancel();
        }
        JAnalyticsInterface.onPageEnd(this, this.getClass().getCanonicalName());
    }

    @OnClick(R.id.ll_skip)
    public void onViewClicked() {
        gotoMainUI();
        if (countDownTimer != null) {
            countDownTimer.cancel();
        }
    }
}
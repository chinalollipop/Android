package com.qpweb.a01;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.MotionEvent;
import android.view.View;
import android.widget.ImageView;

import com.alibaba.fastjson.JSON;
import com.google.gson.Gson;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.DemoResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.http.Client;
import com.qpweb.a01.http.DomainUrl;
import com.qpweb.a01.http.MyHttpClient;
import com.qpweb.a01.ui.loginhome.LoginHomeActivity;
import com.qpweb.a01.ui.loginhome.LoginSuccessEvent;
import com.qpweb.a01.ui.loginhome.fastlogin.LoginContract;
import com.qpweb.a01.ui.loginhome.fastlogin.LoginFragment;
import com.qpweb.a01.ui.loginhome.fastregister.RegisterFragment;
import com.qpweb.a01.ui.loginhome.sign.SignTodayFragment;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.NetworkUtils;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.ToastUtils;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

import static com.qpweb.a01.utils.Utils.getContext;

public class LaunchActivity extends AppCompatActivity implements View.OnClickListener,View.OnTouchListener, LoginContract.View {
    private boolean ifStop = false;
    MyHttpClient myHttpClient = new MyHttpClient();
    ImageView homeRegister,homeLogin,homeDemo;
    LoginContract.Presenter presenter;
    @Override
    protected void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onMainEvent(LoginSuccessEvent loginSuccessEvent){
        finish();
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EventBus.getDefault().register(this);
        Injections.inject(this, null);
        presenters();
        onLogin();
        setContentView(R.layout.activity_launch);
        homeRegister = (ImageView)findViewById(R.id.homeRegister);
        homeLogin = (ImageView)findViewById(R.id.homeLogin);
        homeDemo = (ImageView)findViewById(R.id.homeDemo);
        homeRegister.setOnClickListener(this);
        homeLogin.setOnClickListener(this);
        homeDemo.setOnClickListener(this);
        homeRegister.setOnTouchListener(this);
        homeLogin.setOnTouchListener(this);
        homeDemo.setOnTouchListener(this);
        //onGetAvailableDomain();
    }

    private void onLogin(){
        GameLog.log("正式用户登录");
        String isChangeUser = ACache.get(getContext()).getAsString("isChangeUser");
        if("YES".equals(isChangeUser)){

        }else{
            LoginResult loginResult = JSON.parseObject(ACache.get(getContext()).getAsString("loginResult"), LoginResult.class);
            if(!Check.isNull(loginResult)){
                GameLog.log("用户名 "+loginResult.getUserName()+" 密码 "+loginResult.getPassWord());
                //onDemo(Client.baseUrl()+"api/login.php?appRefer=14&username="+loginResult.getUserName()+"&passwd="+loginResult.getPassWord());
                presenter.postLogin("",loginResult.getUserName(),loginResult.getPassWord());
                ACache.get(getContext()).put("isChangeUser","NO");
            }
        }
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
                RegisterFragment.newInstance().show(getSupportFragmentManager());
                break;
            case R.id.homeLogin:
                LoginFragment.newInstance().show(getSupportFragmentManager());
                break;
            case R.id.homeDemo:
                /*startActivity(new Intent(getApplicationContext(), LoginHomeActivity.class));
                finish();*/
                LoginResult loginResult = JSON.parseObject(ACache.get(getContext()).getAsString("loginResult"), LoginResult.class);
                if(!Check.isNull(loginResult)){
                    GameLog.log("用户名 "+loginResult.getUserName()+" 密码 "+loginResult.getPassWord());
                    presenter.postLogin("",loginResult.getUserName(),loginResult.getPassWord());
                    //ACache.get(getContext()).put("isChangeUser","NO");
                    //onDemo(Client.baseUrl()+"api/login.php?appRefer=14&username="+loginResult.getUserName()+"&passwd="+loginResult.getPassWord());
                }else{
                    presenter.postRegister("","","");
                    //onDemo("http://hg066a.com/api/guest_register.php?appRefer=14&action=register");
                }
                //onDemo("http://hg066a.com/api/guest_register.php?appRefer=14&action=register");
                //presenter.postRegister("","","");
                ACache.get(getContext()).put("isChangeUser","NO");
                break;
        }
    }

    private void onDemo(String url) {
        ACache.get(getContext()).put("isChangeUser","NO");
        MyHttpClient myHttpClient = new MyHttpClient();
//        String url = "http://www.hg066a.com/api/login.php?username=travelVIP&passwd=____&sign=posthasteTry";
        myHttpClient.executeGet(url, new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {

            }

            @Override
            public void onResponse(Call call, final Response response) throws IOException {
                final String resu  = response.body().string();
                homeDemo.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("获取试玩账号 \n"+resu);
                        if(response.isSuccessful()){
                            DemoResult   demoResult = new Gson().fromJson(resu, DemoResult.class);
                            if(demoResult.getStatus().equals("200")){
                                LoginResult loginResult = demoResult.getData();
                                ACache.get(getApplicationContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT,loginResult.getUserName());
                                ACache.get(getApplicationContext()).put("loginResult",JSON.toJSONString(loginResult));
                                ACache.get(getApplicationContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_MONEY,loginResult.getMoney());
                                Intent intent = new Intent(getApplicationContext(), LoginHomeActivity.class);
                                intent.putExtra("LoginResult",loginResult);
                                startActivity(intent);
                                ToastUtils.showLongToast(demoResult.getDescribe());
                                EventBus.getDefault().post(new LoginSuccessEvent());
                            }else{
                                ToastUtils.showLongToast(demoResult.getDescribe());
                            }
                        }
                    }
                });

            }
        });
    }

    @Override
    public boolean onTouch(View view, MotionEvent event) {
        switch (event.getAction()) {
            case MotionEvent.ACTION_DOWN:
                if (view.getId() == R.id.homeRegister) {
                    homeRegister.setScaleX(1.1f);
                    homeRegister.setScaleY(1.1f);
                    break;
                }else if(view.getId() == R.id.homeLogin) {
                    homeLogin.setScaleX(1.1f);
                    homeLogin.setScaleY(1.1f);
                }else if(view.getId() == R.id.homeDemo) {
                    homeDemo.setScaleX(1.1f);
                    homeDemo.setScaleY(1.1f);
                }
                break;
            case MotionEvent.ACTION_UP:
                if (view.getId() == R.id.homeRegister) {
                    homeRegister.setScaleX((float) 0.95);
                    homeRegister.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeLogin) {
                    homeLogin.setScaleX((float) 0.95);
                    homeLogin.setScaleY((float) 0.95);
                }else if(view.getId() == R.id.homeDemo) {
                    homeDemo.setScaleX((float) 0.95);
                    homeDemo.setScaleY((float) 0.95);
                }
                break;
        }
        return false;
    }

    @Override
    public void postLoginResult(LoginResult loginResult) {
        ACache.get(getApplicationContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT,loginResult.getUserName());
        ACache.get(getApplicationContext()).put("loginResult",JSON.toJSONString(loginResult));
        ACache.get(getContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_ALIAS,loginResult.getAlias());
        ACache.get(getApplicationContext()).put(QPConstant.USERNAME_LOGIN_ACCOUNT_MONEY,loginResult.getMoney());
        Intent intent = new Intent(getApplicationContext(), LoginHomeActivity.class);
        intent.putExtra("LoginResult",loginResult);
        startActivity(intent);
//        ToastUtils.showLongToast(demoResult.getDescribe());
        EventBus.getDefault().post(new LoginSuccessEvent());
    }

    @Override
    public void postPhoneResult(String errorMessage) {

    }

    @Override
    public void showMessage(String message) {
        ToastUtils.showLongToast(message);
    }

    @Override
    public void setPresenter(LoginContract.Presenter presenter) {
        this.presenter = presenter;
    }

    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}

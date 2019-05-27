package com.cfcp.a01.ui.chat;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.webkit.JavascriptInterface;
import android.widget.FrameLayout;

import com.alibaba.fastjson.JSON;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.http.MyHttpClient;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.CPIWebSetting;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.NetworkUtils;
import com.cfcp.a01.common.utils.ToastUtils;
import com.cfcp.a01.data.AgGamePayResult;
import com.cfcp.a01.data.JSLoginParam;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.ui.home.login.fastlogin.LoginFragment;
import com.cfcp.a01.ui.home.playgame.XPlayGameActivity;
import com.cfcp.a01.ui.main.MainEvent;
import com.coolindicator.sdk.CoolIndicator;
import com.google.gson.Gson;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.ValueCallback;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.io.IOException;

import butterknife.BindView;
import butterknife.OnClick;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

//在线客服
public class ChatFragment extends BaseFragment {

    @BindView(R.id.flayout_xpay)
    FrameLayout flayoutXpay;

    @BindView(R.id.wv_service_online)
    WebView wvServiceOnlineContent;


    @BindView(R.id.indicator)
    CoolIndicator mCoolIndicator;

    private ValueCallback<Uri> uploadFile;
    private ValueCallback<Uri[]> uploadFiles;

    public static ChatFragment newInstance() {
        ChatFragment chatFragment = new ChatFragment();

        return chatFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_chat;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        /*wvServiceOnlineContent.post(new Runnable() {
            @Override
            public void run() {
                JSLoginParam userInformJS  =new JSLoginParam();
                userInformJS.setUsername(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT));
                userInformJS.setPassword(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_PWD));
                userInformJS.setApi_id("1");
                userInformJS.setParent(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_PARENT));
                userInformJS.setRoomid("0");
                userInformJS.setToken(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
                String userInformJson = new Gson().toJson(userInformJS);
                //wvAd.loadData("","text/html","UTF-8");
                if(!Check.isNull(wvServiceOnlineContent))//chatRoomUserLoginInfo getLoginInfo
                    wvServiceOnlineContent.loadUrl("javascript:chatRoomUserLoginInfo('"+userInformJson+"')");
                //wvAd.loadUrl("javascript:showInfoFromJava('"+userInformJson+ "')");
            }
        });*/
    }

    public class ADInterface{
        private Context mContext;

        public ADInterface(Context mContext){
            this.mContext = mContext;
        }

        @JavascriptInterface
        public void closeWebview(String actionName,String param){
            GameLog.log("javascriptTOjava("+actionName+","+param+")");
        }
        @JavascriptInterface
        public void goLogin(String actionName,String param){
            GameLog.log("javascriptTOjava("+actionName+","+param+")");
            //EventBus.getDefault().post(new StartBrotherEvent(NLoginFragment.newInstance("", ""), SINGLETASK));
        }

        @JavascriptInterface
        public void chatRoomUserLoginInfo(String foo){
            GameLog.log("javascriptTOjava(getLoginInfo");
            //必须开启线程进行JS调用
            wvServiceOnlineContent.post(new Runnable() {
                @Override
                public void run() {
                    JSLoginParam userInformJS  =new JSLoginParam();
                    userInformJS.setUsername(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT));
                    userInformJS.setPassword(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_PWD));
                    userInformJS.setApi_id("1");
                    userInformJS.setParent(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_PARENT));
                    userInformJS.setRoomid("0");
                    userInformJS.setHiddenheader("0");
                    userInformJS.setToken(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
                    String userInformJson = new Gson().toJson(userInformJS);
                    //wvAd.loadData("","text/html","UTF-8");
                    if(!Check.isNull(wvServiceOnlineContent))//chatRoomUserLoginInfo getLoginInfo
                        wvServiceOnlineContent.loadUrl("javascript:chatRoomUserLoginInfo('"+userInformJson+"')");
                    //wvAd.loadUrl("javascript:showInfoFromJava('"+userInformJson+ "')");
                }
            });

        }

        @JavascriptInterface
        public void openNewWebView(String actionName,String param){
            GameLog.log("javascriptTOjava("+actionName+","+param+")");
            //EventBus.getDefault().post(new StartBrotherEvent(IntroduceFragment.newInstance(param,actionName)));
        }

        @JavascriptInterface
        public void goDeposit(String actionName,String param){
            GameLog.log("javascriptTOjava("+actionName+","+param+")");
            //EventBus.getDefault().post(new ADEvent(1,"adEvent"));
        }

    }


    private void webviewsetting(WebView webView) {

        webView.setWebViewClient(new WebViewClient() {

            @Override
            public void onPageStarted(WebView webView, String s, Bitmap bitmap) {
                super.onPageStarted(webView, s, bitmap);
                if(!Check.isNull(mCoolIndicator)) {
                    mCoolIndicator.start();
                }
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                if(!Check.isNull(mCoolIndicator)) {
                    mCoolIndicator.complete();
                }
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                view.loadUrl(url);
                return true;
            }

            @Override
            public void onReceivedSslError(WebView view, SslErrorHandler handler, SslError error) {
                handler.proceed();
            }

        });

        webView.setWebChromeClient(new WebChromeClient() {
            IX5WebChromeClient.CustomViewCallback customViewCallback;

            @Override
            public void onHideCustomView() {
                if(null!=customViewCallback){//!Check.isNull(customViewCallback)
                    customViewCallback.onCustomViewHidden();
                }
                wvServiceOnlineContent.setVisibility(View.VISIBLE);
                flayoutXpay.removeAllViews();
                flayoutXpay.setVisibility(View.GONE);
                super.onHideCustomView();
            }

            @Override
            public void onShowCustomView(View view, IX5WebChromeClient.CustomViewCallback customViewCallback) {
                wvServiceOnlineContent.setVisibility(View.GONE);
                this.customViewCallback = customViewCallback;
                flayoutXpay.removeAllViews();
                flayoutXpay.setVisibility(View.VISIBLE);
                flayoutXpay.addView(view);
                super.onShowCustomView(view, customViewCallback);
            }
            // For Android 3.0+
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType) {
                GameLog.log("openFileChooser 1");
                ChatFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android < 3.0
            public void openFileChooser(ValueCallback<Uri> uploadMsgs) {
                GameLog.log("openFileChooser 2");
                ChatFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  > 4.1.1
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                GameLog.log("openFileChooser 3");
                ChatFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  >= 5.0
            public boolean onShowFileChooser(WebView webView,
                                             ValueCallback<Uri[]> filePathCallback,
                                             FileChooserParams fileChooserParams) {
                GameLog.log("openFileChooser 4:" + filePathCallback.toString());
                ChatFragment.this.uploadFiles = filePathCallback;
                openFileChooseProcess();
                return true;
            }

        });

    }

    private void openFileChooseProcess() {
        Intent i = new Intent(Intent.ACTION_GET_CONTENT);
        i.addCategory(Intent.CATEGORY_OPENABLE);
        i.setType("*/*");
        startActivityForResult(Intent.createChooser(i, "cf_better"), 0);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        // TODO Auto-generated method stub
        super.onActivityResult(requestCode, resultCode, data);

        if (resultCode == RESULT_OK) {
            switch (requestCode) {
                case 0:
                    if (null != uploadFile) {
                        Uri result = data == null || resultCode != RESULT_OK ? null
                                : data.getData();
                        uploadFile.onReceiveValue(result);
                        uploadFile = null;
                    }
                    if (null != uploadFiles) {
                        Uri result = data == null || resultCode != RESULT_OK ? null
                                : data.getData();
                        uploadFiles.onReceiveValue(new Uri[]{result});
                        uploadFiles = null;
                    }
                    break;
                default:
                    break;
            }
        } else if (resultCode == RESULT_CANCELED) {
            if (null != uploadFile) {
                uploadFile.onReceiveValue(null);
                uploadFile = null;
            }

        }
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        try{
            if(!Check.isNull(wvServiceOnlineContent)){
                ViewParent parent = wvServiceOnlineContent.getParent();
                if(!Check.isNull(parent)){
                    ((ViewGroup)parent).removeAllViews();
                }
                wvServiceOnlineContent.stopLoading();
                wvServiceOnlineContent.getSettings().setJavaScriptEnabled(false);
                wvServiceOnlineContent.clearHistory();
                wvServiceOnlineContent.clearView();
                wvServiceOnlineContent.removeAllViews();
                wvServiceOnlineContent.destroy();
                wvServiceOnlineContent = null;
                System.gc();
                GameLog.log("PayGanmeActivity:--------onDestroy()--------");
            }
        }catch (Exception value){
            GameLog.log("PayGanmeActivity异常:"+value);
        }

    }


    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        //showMessage("开奖结果界面");
        //EventBus.getDefault().post(new MainEvent(0));
        //先判断是否登录  如果没有登录 需要登录然后在显示这个界面
        String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        GameLog.log(" onSupportVisible  个人的token是 "+token );
        if(Check.isEmpty(token)){
            EventBus.getDefault().post(new MainEvent(0));
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
        }else{
            mCoolIndicator.setMax(100);
            CPIWebSetting.init(wvServiceOnlineContent);
            webviewsetting(wvServiceOnlineContent);
            wvServiceOnlineContent.addJavascriptInterface(new ADInterface(getActivity()),"AndroidWebView");

            String  webUrl  = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_CHAT_ROOM);//http://58.84.55.207/room/test33.php
//            webUrl = "http://58.84.55.207/room/test22.php";
            if(Check.isEmpty(webUrl)){
                webUrl = "http://58.84.55.207/room/test22.php";
            }
            GameLog.log("加载了在线聊天。。。"+webUrl);
            //wvServiceOnlineContent.clearCache(true);
            if (!NetworkUtils.isConnected()) {
                showMessage("无网络连接！");
            }
            wvServiceOnlineContent.loadUrl(webUrl);
            //loadGameData(webUrl);
        }
    }


    @Subscribe
    public void onEventMain(LogoutResult logoutResult) {
        GameLog.log("=======在线聊天界面=========用户退出了================");
        //meUser.setText("");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN,"");
        EventBus.getDefault().post(new MainEvent(0));
    }

    @OnClick(R.id.servicePageRefresh)
    public void onViewRefreshClicked(){
        String webUrl = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_CHAT_ROOM);
//        webUrl = "http://58.84.55.207/room/test22.php";
        if(Check.isEmpty(webUrl)){
            webUrl = "http://58.84.55.207/room/test22.php";
        }
        wvServiceOnlineContent.loadUrl(webUrl);
        //loadGameData(webUrl);
        if (!NetworkUtils.isConnected()) {
            showMessage("无网络连接！");
        }
    }


    private void loadGameData(String gameUrl) {
        MyHttpClient myHttpClient = new MyHttpClient();
        myHttpClient.executeGet(gameUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                //网络异常或者超时的时候调用此处
                wvServiceOnlineContent.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("无网络的情况下调用了");
                        wvServiceOnlineContent.loadUrl("javascript:chatRoomError()");
                        showMessage("请检查网络是否正常");
                    }
                });
            }

            @Override
            public void onResponse(Call call, final Response response) throws IOException {
                final String responseText = response.body().string();
                try {
                    wvServiceOnlineContent.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("在线聊天 code是 "+ response.code());
                            GameLog.log("在线聊天Json"+responseText);
                            //showMessage("请检查网络是否正常");
                            //wvServiceOnlineContent.loadUrl("javascript:chatRoomError()");
                        }
                    });
                } catch (Exception exception) {
                    wvServiceOnlineContent.post(new Runnable() {
                        @Override
                        public void run() {
                            //wvServiceOnlineContent.loadUrl("javascript:chatRoomError()");
                            wvServiceOnlineContent.loadUrl("javascript:chatRoomError()");
                            showMessage("请检查网络是否正常");
                        }
                    });
                }
                if(!response.isSuccessful()){
                    GameLog.log("我去 ，既然白屏了  "+responseText);
                    wvServiceOnlineContent.post(new Runnable() {
                        @Override
                        public void run() {
                            wvServiceOnlineContent.loadUrl("javascript:chatRoomError()");
                            showMessage("请检查网络是否正常");
                        }
                    });
                }
            }
        });
    }

}

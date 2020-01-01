package com.nhg.xhg.homepage.aglist.playgame;

import android.content.Context;
import android.content.res.Configuration;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.webkit.JavascriptInterface;
import android.widget.FrameLayout;

import com.nhg.xhg.R;
import com.nhg.xhg.base.HGBaseFragment;
import com.nhg.xhg.common.widgets.NTitleBar;
import com.nhg.common.util.Check;
import com.nhg.common.util.GameLog;
import com.nhg.common.util.PNThreadFactory;
import com.nhg.common.util.ToastUtils;
import com.nhg.common.util.Utils;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.CookieManager;
import com.tencent.smtt.sdk.CookieSyncManager;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import butterknife.BindView;


/**
 * Created by daniel on 2018/10/20.
 * 嵌套游戏 X5内核适配
 */

public class XPlayGameFragment extends HGBaseFragment {
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    // The request code must be 0 or greater.
    private static final int PLUS_ONE_REQUEST_CODE = 0;
    // The URL to +1.  Must be a valid URL.
    private final String PLUS_ONE_URL = "http://developer.android.com";
    @BindView(R.id.pay_x5_game_title)
    NTitleBar payGameTitle;
    @BindView(R.id.flayout_xpay)
    FrameLayout flayoutXpay;
    @BindView(R.id.wv_pay_x5_game)
    WebView wvPayGame;
    //接收webview的参数传参
    private String title, url, showtype;
    public static XPlayGameFragment newInstance(String param1, String param2,String param3){
        XPlayGameFragment fragment = new XPlayGameFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1,param1);
        args.putString(ARG_PARAM2,param2);
        args.putString(ARG_PARAM3,param3);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //getContext().getWindow().setFormat(PixelFormat.TRANSLUCENT);
        if (getArguments() != null) {
            title = getArguments().getString(ARG_PARAM1);
            url = getArguments().getString(ARG_PARAM2);
            showtype = getArguments().getString(ARG_PARAM3);
        }
    }

    @Override
    public void onResume() {
        super.onResume();
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        if(newConfig.orientation== Configuration.ORIENTATION_LANDSCAPE){
            GameLog.log("SDK_INT upper ORIENTATION_LANDSCAPE");
            payGameTitle.setVisibility(View.GONE);
        }
        else if(showtype.equals("1"))
        {
            payGameTitle.setVisibility(View.GONE);
        }
        else{
            payGameTitle.setVisibility(View.VISIBLE);
            GameLog.log("SDK_INT upper ......");
            /*if(gameType.equals("shaba")){
                payGameTitle.setVisibility(View.GONE);
            }else{
                payGameTitle.setVisibility(View.VISIBLE);
            }*/
            payGameTitle.setTitle(title);
            payGameTitle.setBackListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    finish();
                }
            });
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_pay_x5_game;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        PTIWebSetting.init(wvPayGame);
        if(getResources().getConfiguration().orientation== Configuration.ORIENTATION_LANDSCAPE){
            GameLog.log("SDK_INT upper ORIENTATION_LANDSCAPE");
            payGameTitle.setVisibility(View.GONE);
        }
        else if(showtype.equals("1"))
        {
            payGameTitle.setVisibility(View.GONE);
        }
        else{
            payGameTitle.setVisibility(View.VISIBLE);
            GameLog.log("SDK_INT upper ......");
            /*if(gameType.equals("shaba")){
                payGameTitle.setVisibility(View.GONE);
            }else{
                payGameTitle.setVisibility(View.VISIBLE);
            }*/
           // payGameTitle.setTitle(FStype);
            payGameTitle.setBackListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    finish();
                }
            });
        }

        wvPayGame.addJavascriptInterface(new JsInterface(getContext()),"AndroidWebView");
        wvPayGame.setWebChromeClient(new WebChromeClient(){

            IX5WebChromeClient.CustomViewCallback customViewCallback;

            @Override
            public void onHideCustomView() {
                if(!Check.isNull(customViewCallback)){
                    customViewCallback.onCustomViewHidden();
                }
                wvPayGame.setVisibility(View.VISIBLE);
                flayoutXpay.removeAllViews();
                flayoutXpay.setVisibility(View.GONE);
                super.onHideCustomView();
            }

            @Override
            public void onShowCustomView(View view, IX5WebChromeClient.CustomViewCallback customViewCallback) {
                wvPayGame.setVisibility(View.GONE);
                this.customViewCallback = customViewCallback;
                flayoutXpay.removeAllViews();
                flayoutXpay.setVisibility(View.VISIBLE);
                flayoutXpay.addView(view);
                super.onShowCustomView(view, customViewCallback);
            }

            @Override
            public boolean onJsAlert(WebView view, String url, String message, JsResult result) {
                return super.onJsAlert(view, url, message, result);
            }

            @Override
            public boolean onJsConfirm(WebView arg0, String arg1, String arg2,
                                       JsResult arg3) {
                return super.onJsConfirm(arg0, arg1, arg2, arg3);
            }

        });
        wvPayGame.setWebViewClient(new WebViewClient()
        {
            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
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
        wvPayGame.loadUrl(url);
    }


    /**
     * 同步cookie到webview
     * @param httpUrl
     * @param cookie
     */
    private  void synCookieToWebviewshaba(String httpUrl, String  cookie)
    {
        if(Check.isNull(httpUrl) || Check.isNull(cookie))
        {
            GameLog.log("synCookieToWebview cookie can't is null ");
            return;
        }
        CookieSyncManager.createInstance(Utils.getContext());
        final CookieManager cookieManager = CookieManager.getInstance();
        cookieManager.setAcceptCookie(true);
        cookieManager.removeSessionCookie();
        cookieManager.removeAllCookie();
        cookieManager.setCookie(httpUrl,cookie);
        String newCookie = cookieManager.getCookie(httpUrl);
        GameLog.log("finsh syn  cookie ---- :" + newCookie);
        //立即同步cookie到webview，这是个耗时的工作
        PNThreadFactory.createThread(new Runnable() {
            @Override
            public void run() {
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                    cookieManager.flush();
                }
                else
                {
                    CookieSyncManager.getInstance().sync();
                }
            }
        });

    }


    /**
     * 同步cookie到webview
     * @param httpUrl
     * @param cookie
     */
    private  void synCookieToWebviewopus(String httpUrl, String  cookie)
    {
        if(Check.isNull(httpUrl) || Check.isNull(cookie))
        {
            GameLog.log("synCookieToWebview cookie can't is null ");
            return;
        }
        CookieSyncManager.createInstance(Utils.getContext());
        final CookieManager cookieManager = CookieManager.getInstance();
        cookieManager.setAcceptCookie(true);
        cookieManager.removeSessionCookie();
        cookieManager.removeAllCookie();
        cookieManager.setCookie(httpUrl,cookie);
        cookieManager.setCookie(httpUrl,"SelectedLanguage=zh-CN");
        String newCookie = cookieManager.getCookie(httpUrl);
        GameLog.log("finsh syn  cookie ---- :" + newCookie);
        //立即同步cookie到webview，这是个耗时的工作
        PNThreadFactory.createThread(new Runnable() {
            @Override
            public void run() {
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                    cookieManager.flush();
                }
                else
                {
                    CookieSyncManager.getInstance().sync();
                }
            }
        });

    }


    public class JsInterface{
        private Context mContext;

        public JsInterface(Context mContext){
            this.mContext = mContext;
        }

        @JavascriptInterface
        public void showInforFroms(String name){
            ToastUtils.showLongToast(name);
            GameLog.log("javascriptTOjava("+name+","+name+")");
        }

        @JavascriptInterface
        public void showInfoFromJs(String name) {
            //Toast.makeText(mContext, name, Toast.LENGTH_SHORT).show();
            ToastUtils.showLongToast(name);
            GameLog.log("showInfoFromJs("+name+")");
        }

        @JavascriptInterface
        public void onCloseFromJS(){
            finish();
            GameLog.log("-----------onCloseFromJS()------------aggjoppo");
        }
    }

   /* @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            if(!Check.isNull(wvPayGame)&&wvPayGame.canGoBack()){
                wvPayGame.goBack();
            }else{
                finish();
            }
        }
        return true;
    }*/

    @Override
    public void onDestroy() {
        super.onDestroy();
        try{
            if(!Check.isNull(wvPayGame)){
                ViewParent parent = wvPayGame.getParent();
                if(!Check.isNull(parent)){
                    ((ViewGroup)parent).removeAllViews();
                }
                wvPayGame.stopLoading();
                wvPayGame.getSettings().setJavaScriptEnabled(false);
                wvPayGame.clearHistory();
                wvPayGame.clearView();
                wvPayGame.removeAllViews();
                wvPayGame.destroy();
                wvPayGame = null;
                System.gc();
                GameLog.log("XplayGameActivity:--------onDestroy()--------");
            }
        }catch (Exception value){
            GameLog.log("XplayGameActivity异常:"+value);
        }
    }

}

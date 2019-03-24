package com.cfcp.a01.ui.home.playgame;

import android.content.Context;
import android.content.res.Configuration;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.view.WindowManager;
import android.webkit.JavascriptInterface;
import android.widget.FrameLayout;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.http.PNThreadFactory;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.ToastUtils;
import com.cfcp.a01.common.utils.Utils;
import com.cfcp.a01.common.widget.NTitleBar;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.CookieManager;
import com.tencent.smtt.sdk.CookieSyncManager;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import java.util.HashMap;

import butterknife.BindView;


/**
 * Created by daniel on 2018/10/20.
 * 嵌套游戏 X5内核适配
 */

public class XPlayGameFragment extends BaseFragment {
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

    private int gameFull = 1;
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
        }else{
            payGameTitle.setVisibility(View.VISIBLE);
            GameLog.log("SDK_INT upper ......");
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_pay_x5_game;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        PTIWebSetting.init(wvPayGame);
        //getActivity().getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,WindowManager.LayoutParams.FLAG_FULLSCREEN);
        payGameTitle.setMoreImage(null);
        payGameTitle.setMoreListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                GameLog.log("设置全屏。。。。");
                if(gameFull==0){
                    gameFull = 1;
                    payGameTitle.setMoreText("正常");
                    payGameTitle.setVisibility(View.GONE);
                    getActivity().getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,WindowManager.LayoutParams.FLAG_FULLSCREEN);
                }else{
                    gameFull = 0;
                    payGameTitle.setMoreText("全屏");
                    getActivity().getWindow().clearFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN);
                }
            }
        });
        if(getResources().getConfiguration().orientation== Configuration.ORIENTATION_LANDSCAPE){
            GameLog.log("SDK_INT upper ORIENTATION_LANDSCAPE");
            payGameTitle.setVisibility(View.GONE);
        }
        /*else if(showtype.equals("1"))
        {
            payGameTitle.setVisibility(View.GONE);
        }*/
        else{
            payGameTitle.setVisibility(View.VISIBLE);
            GameLog.log("SDK_INT upper ......");
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

        GameLog.log("请求的URL地址 "+url);
        wvPayGame.loadUrl(url);
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

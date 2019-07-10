package com.qpweb.a01;

import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.ImageView;

import com.coolindicator.sdk.CoolIndicator;
import com.qpweb.a01.ui.home.RefreshMoneyEvent;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.CommentUtils;
import com.qpweb.a01.utils.FileIOUtils;
import com.qpweb.a01.utils.FileUtils;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.TBSWebSetting;
import com.qpweb.a01.utils.ToastUtils;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import org.greenrobot.eventbus.EventBus;

import java.io.File;

public class MainActivity extends AppCompatActivity {
    private String mUpdateUrl = "https://raw.githubusercontent.com/WVector/AppUpdateDemo/master/json/json.txt";
    private FrameLayout flayoutXpay;

    private WebView wvPayGame;
    private ImageView gameBack;
    private CoolIndicator mCoolIndicator;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
//        onUpdate();
        setContentView(R.layout.activity_main);
        flayoutXpay = this.findViewById(R.id.flayout_xpay);
        wvPayGame = this.findViewById(R.id.wv_pay_x5_game);
        gameBack = this.findViewById(R.id.gameBack);
        mCoolIndicator = this.findViewById(R.id.indicator);
        mCoolIndicator.setMax(100);
        TBSWebSetting.init(wvPayGame);
        gameBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        String demainUrl =  getIntent().getStringExtra("app_url");
        /*String demainUrl =  ACache.get(getApplicationContext()).getAsString("app_demain_url");
        if(Check.isEmpty(demainUrl)){
            demainUrl = "http://www.cfqp88.com/";
        }*/
        /*for(int ii=100001;ii<101001;++ii){
            GameLog.log(""+ii);
        }*/
        //demainUrl = "http://hg06606.com/";//测试环境的地址
        //demainUrl += "?code="+QPApplication.instance().getCommentData();
        //ToastUtils.showLongToast("请求的地址是："+demainUrl);
        GameLog.log("域名地址是"+demainUrl);
        wvPayGame.loadUrl(demainUrl);
        //wvPayGame.loadUrl("https://m.hhhg6668.com/");
        wvPayGame.setWebChromeClient(new WebChromeClient(){

            IX5WebChromeClient.CustomViewCallback customViewCallback;

            @Override
            public void onHideCustomView() {
                if(null!=customViewCallback){//!Check.isNull(customViewCallback)
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
            public void onPageStarted(WebView webView, String s, Bitmap bitmap) {
                super.onPageStarted(webView, s, bitmap);
                mCoolIndicator.start();
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                //ToastUtils.showLongToast("加载异常,请重试");
                mCoolIndicator.complete();
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                view.loadUrl(url);
                return true;
            }

            @Override
            public void onReceivedSslError(WebView view, SslErrorHandler handler, SslError error) {
                handler.proceed();
                ToastUtils.showLongToast("加载异常,请重试");
                view.loadUrl("https://www.freepik.com/");
            }
        });
    }


    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        GameLog.log( "onActivityResult() called with: requestCode = [" + requestCode + "], resultCode = [" + resultCode + "], data = [" + data + "]");
        switch (resultCode) {
            case Activity.RESULT_CANCELED:
                switch (requestCode){
                    // 得到通过UpdateDialogFragment默认dialog方式安装，用户取消安装的回调通知，以便用户自己去判断，比如这个更新如果是强制的，但是用户下载之后取消了，在这里发起相应的操作
                }
                break;
            default:
        }
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        // TODO Auto-generated method stub
        switch (keyCode) {
            case KeyEvent.KEYCODE_BACK:
                if(event.getAction() == 0 && keyCode == 4 && this.wvPayGame.canGoBack()) {
                    this.wvPayGame.goBack();
                    return true;
                } else {
                    finish();
//                    System.exit(0);
                    return false;
                }
                //this.tbsSuiteExit();
        }
        return super.onKeyDown(keyCode, event);
    }

    private void tbsSuiteExit() {
        // exit TbsSuite?
        AlertDialog.Builder dialog = new AlertDialog.Builder(getApplicationContext());
        dialog.setTitle("X5功能演示");
        dialog.setPositiveButton("OK", new AlertDialog.OnClickListener() {

            @Override
            public void onClick(DialogInterface dialog, int which) {
                // TODO Auto-generated method stub
                //Process.killProcess(Process.myPid());
            }
        });
        dialog.setMessage("quit now?");
        dialog.create().show();
    }

    private void onUpdate(){

        String filePath = FileUtils.getFilePath(getApplicationContext(),"")+"/markets.txt";
        //先读本地文件，没有的话，再读comments，然后在保存到本地
        String comment  = FileIOUtils.readFile2String(filePath);
        if(Check.isEmpty(comment)){
            comment =  CommentUtils.readAPK(new File(getApplicationContext().getPackageCodePath()));
            if(Check.isEmpty(comment)){
                comment = "";//CHANNEL_ID 渠道ID
            }
            FileIOUtils.writeFileFromString(filePath,comment);
        }

    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        GameLog.log("关闭了游戏界面");
        EventBus.getDefault().post(new RefreshMoneyEvent());
    }
}

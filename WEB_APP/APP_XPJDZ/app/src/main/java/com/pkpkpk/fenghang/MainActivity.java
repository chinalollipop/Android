package com.pkpkpk.fenghang;

import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.ActivityInfo;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.graphics.Bitmap;
import android.os.Parcelable;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.KeyEvent;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.coolindicator.sdk.CoolIndicator;
import com.google.gson.Gson;
import com.kongzue.dialog.interfaces.OnDialogButtonClickListener;
import com.kongzue.dialog.util.BaseDialog;
import com.kongzue.dialog.util.DialogSettings;
import com.kongzue.dialog.v3.MessageDialog;
import com.pkpkpk.fenghang.http.MyHttpClient;
import com.pkpkpk.fenghang.http.ReOpenResult;
import com.pkpkpk.fenghang.push.ExampleUtil;
import com.pkpkpk.fenghang.push.LocalBroadcastManager;
import com.pkpkpk.fenghang.utils.ACache;
import com.pkpkpk.fenghang.utils.Check;
import com.pkpkpk.fenghang.utils.FloatButtonLayout;
import com.pkpkpk.fenghang.utils.GameLog;
import com.pkpkpk.fenghang.utils.TBSWebSetting;
import com.pkpkpk.fenghang.utils.ToastUtils;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;
import com.vector.update_app.utils.AppUpdateUtils;


import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import cn.jpush.android.api.JPushInterface;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;

public class MainActivity extends AppCompatActivity implements View.OnClickListener{
    //for receive customer msg from jpush server
    private MessageReceiver mMessageReceiver;
    public static final String MESSAGE_RECEIVED_ACTION = "com.pkpkpk.newatoa.MESSAGE_RECEIVED_ACTION";
    public static final String KEY_TITLE = "title";
    public static final String KEY_MESSAGE = "message";
    public static final String KEY_EXTRAS = "extras";
    public static boolean isForeground = false;
    private String demainUrl ,DepositsUrl,WithdrawUrl;
    private String mUpdateUrl = "https://raw.githubusercontent.com/WVector/AppUpdateDemo/master/json/json.txt";
    private FrameLayout flayoutXpay;
    // 再点一次退出程序时间设置
    private static final long WAIT_TIME = 2000L;
    private long TOUCH_TIME = 0;
    private WebView wvPayGame;
    private CoolIndicator mCoolIndicator;
    private TextView float_home,float_refesh,float_back;
    private TextView homeDeposit,homeRegister,homeActivity,homeExChange,homeWithdraw;
    private FloatButtonLayout floatButton;
    MyHttpClient myHttpClient = new MyHttpClient();
    private ReOpenResult reOpenResult;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //onUpdate();
        setContentView(R.layout.activity_main);
        init();
        mCoolIndicator = this.findViewById(R.id.indicator);
        mCoolIndicator.setMax(100);

        flayoutXpay = this.findViewById(R.id.flayout_xpay);
        wvPayGame = this.findViewById(R.id.wv_pay_x5_game);
        float_home = this.findViewById(R.id.float_home);
        float_refesh = this.findViewById(R.id.float_refesh);
        float_back = this.findViewById(R.id.float_back);
        floatButton = this.findViewById(R.id.float_button_layout);

        homeDeposit = this.findViewById(R.id.homeDeposit);
        homeRegister = this.findViewById(R.id.homeRegister);
        homeActivity = this.findViewById(R.id.homeActivity);
        homeExChange = this.findViewById(R.id.homeExChange);
        homeWithdraw = this.findViewById(R.id.homeWithdraw);
        //设置点击事件
        float_home.setOnClickListener(this);
        float_refesh.setOnClickListener(this);
        float_back.setOnClickListener(this);
        homeDeposit.setOnClickListener(this);
        homeRegister.setOnClickListener(this);
        homeActivity.setOnClickListener(this);
        homeExChange.setOnClickListener(this);
        homeWithdraw.setOnClickListener(this);

        floatButton.setCallback(new FloatButtonLayout.Callback() {
            @Override
            public void onClickFloatButton() {
                GameLog.log("你点击了啥");
                //startActivity(new Intent(MainActivity.this, NewYearActivity.class));
            }
        });

        TBSWebSetting.init(wvPayGame);
        registerMessageReceiver();  // used for receive msg

        demainUrl =  ACache.get(getApplicationContext()).getAsString("APP_DEMAIN_URL");
        DepositsUrl =  ACache.get(getApplicationContext()).getAsString("APP_DEMAIN_URL_DepositsUrl");
        WithdrawUrl =  ACache.get(getApplicationContext()).getAsString("APP_DEMAIN_URL_WithdrawUrl");


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
                mCoolIndicator.complete();
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
               /* if("demainUrl".equals(url)){
                    floatButton.setVisibility(View.GONE);
                }else{
                    floatButton.setVisibility(View.VISIBLE);
                }*/
                view.loadUrl(url);
                return true;
            }

            @Override
            public void onReceivedSslError(WebView view, SslErrorHandler handler, SslError error) {
                handler.proceed();
            }
        });

        if(Check.isEmpty(demainUrl)){
            demainUrl = "https://f00055.com/";
        }
        wvPayGame.loadUrl(demainUrl);

    }


    @Override
    protected void onResume() {
        isForeground = true;
        super.onResume();
    }


    @Override
    protected void onPause() {
        isForeground = false;
        super.onPause();
    }

    // 初始化 JPush。如果已经初始化，但没有登录成功，则执行重新登录。
    private void init(){
        JPushInterface.init(getApplicationContext());
        String domainUrl = "https://hg-test.gz.bcebos.com/hgtest.apk";
        myHttpClient.executeGet(domainUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {

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


    private void tbsSignData() {
        // adopen //【0 未过期】 【1 即将过期】  【2 为 已过期】
        if(!Check.isNull(reOpenResult)){
            demainUrl = reOpenResult.getUrl();
        }

        if(reOpenResult.getAdopen().equals("2")){
            MessageDialog.build(MainActivity.this)
                    .setStyle(DialogSettings.STYLE.STYLE_IOS)
                    .setTheme(DialogSettings.THEME.LIGHT)
                    .setTitle(reOpenResult.getTitle())
                    .setMessage(reOpenResult.getContact()).setCancelable(false)
                    .setOkButton("确认", new OnDialogButtonClickListener() {
                        @Override
                        public boolean onClick(BaseDialog baseDialog, View v) {
                            //tbsSignData();
                            ToastUtils.showLongToast(reOpenResult.getContact());
                            return true;
                        }
                    })
                    .show();
        }else if(reOpenResult.getAdopen().equals("1")){
            MessageDialog.build(MainActivity.this)
                    .setStyle(DialogSettings.STYLE.STYLE_IOS)
                    .setTheme(DialogSettings.THEME.LIGHT)
                    .setTitle(reOpenResult.getTitle())
                    .setMessage(reOpenResult.getContact()).setCancelable(false)
                    .setOkButton("确认", new OnDialogButtonClickListener() {
                        @Override
                        public boolean onClick(BaseDialog baseDialog, View v) {
                            //ToastUtils.showLongToast("签名即将过期！请联系QQ：2893779340!");
                            ToastUtils.showLongToast(reOpenResult.getContact());
                            return false;
                        }
                    })
                    .show();
        }

    }

    private void onGetSuccessDomain(String responseText) {
        reOpenResult = new Gson().fromJson(responseText, ReOpenResult.class);
//        reOpenResult.setTitle("2");
        runOnUiThread(new Runnable() {
            @Override
            public void run() {
                tbsSignData();
                //ToastUtils.showLongToast("您的签名即将过期 ，请联系");
            }
        });


    }

    private void onShareTo(){
        Intent intent = new Intent(Intent.ACTION_SEND);
        intent.setType("text/plain");
        List<ResolveInfo> resolveInfos = getPackageManager().queryIntentActivities(intent, PackageManager.MATCH_DEFAULT_ONLY);
        if (resolveInfos.isEmpty()) {
            return;
        }
        List<Intent> targetIntents = new ArrayList<>();
        for (ResolveInfo info : resolveInfos) {
            ActivityInfo ainfo = info.activityInfo;
            switch (ainfo.packageName) {
                case "com.tencent.mm":
                    addShareIntent(targetIntents, ainfo);
                    break;
                case "com.tencent.mobileqq":
                    addShareIntent(targetIntents, ainfo);
                    break;
                case "com.sina.weibo":
                    addShareIntent(targetIntents, ainfo);
                    break;
            }
        }
        if (targetIntents == null || targetIntents.size() == 0) {
            return;
        }
        Intent chooserIntent = Intent.createChooser(targetIntents.remove(0), "请选择分享平台");
        if (chooserIntent == null) {
            return;
        }
        chooserIntent.putExtra(Intent.EXTRA_INITIAL_INTENTS, targetIntents.toArray(new Parcelable[]{}));
        try {
            startActivity(chooserIntent);
        } catch (android.content.ActivityNotFoundException ex) {
            Toast.makeText(this, "找不到该分享应用组件", Toast.LENGTH_SHORT).show();
        }
        //startActivity(Intent.createChooser(intent, getTitle()));
    }

    private void addShareIntent(List<Intent> list,ActivityInfo ainfo) {
        Intent target = new Intent(Intent.ACTION_SEND);
        target.setType("text/plain");
        target.putExtra(Intent.EXTRA_TEXT, "葡京娱乐场：https://3013777.com/m");
        target.setPackage(ainfo.packageName);
        target.setClassName(ainfo.packageName, ainfo.name);
        list.add(target);
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()){
            case R.id.homeDeposit:
                wvPayGame.loadUrl(demainUrl+"member/GetDepositsBanks");
                break;
            case R.id.homeRegister:
                wvPayGame.loadUrl(demainUrl+"login/Register");
                break;
            case R.id.homeActivity:
                wvPayGame.loadUrl(demainUrl+"youhui/youhuiactivity");
                break;
            case R.id.homeExChange:
                wvPayGame.loadUrl(demainUrl+"member/GetMemberGameState");
                break;
            case R.id.homeWithdraw:
                wvPayGame.loadUrl(demainUrl+"member/withdraw");
                break;
            case R.id.float_home://存款
//                wvPayGame.loadUrl(demainUrl);
                if(TextUtils.isEmpty(DepositsUrl)){
                    DepositsUrl = demainUrl+"member/GetDepositsBanks";
                }
                GameLog.log("存款的URL "+DepositsUrl);
                wvPayGame.loadUrl(DepositsUrl);
                break;
            case R.id.float_refesh://取款
//                wvPayGame.reload();
                if(TextUtils.isEmpty(WithdrawUrl)){
                    WithdrawUrl = demainUrl+"member/withdraw";
                }
                GameLog.log("取款的URL "+WithdrawUrl);
                wvPayGame.loadUrl(WithdrawUrl);
                //onShareTo();
                break;
            case R.id.float_back:
                if(wvPayGame.canGoBack()) {
                    wvPayGame.goBack();
                } else {
                    if (System.currentTimeMillis() - TOUCH_TIME < WAIT_TIME) {
                        finish();
                        System.exit(0);
                    }else{
                        TOUCH_TIME = System.currentTimeMillis();
                        Toast.makeText(getApplicationContext(),"再按一次退出程序", Toast.LENGTH_LONG).show();
                    }
                }
                break;
        }
    }

    public class MessageReceiver extends BroadcastReceiver {

        @Override
        public void onReceive(Context context, Intent intent) {
            try {
                if (MESSAGE_RECEIVED_ACTION.equals(intent.getAction())) {
                    String messge = intent.getStringExtra(KEY_MESSAGE);
                    String extras = intent.getStringExtra(KEY_EXTRAS);
                    StringBuilder showMsg = new StringBuilder();
                    showMsg.append(KEY_MESSAGE + " : " + messge + "\n");
                    if (!ExampleUtil.isEmpty(extras)) {
                        showMsg.append(KEY_EXTRAS + " : " + extras + "\n");
                    }
                   // setCostomMsg(showMsg.toString());
                }
            } catch (Exception e){
            }
        }
    }


    private void registerMessageReceiver() {
        mMessageReceiver = new MessageReceiver();
        IntentFilter filter = new IntentFilter();
        filter.setPriority(IntentFilter.SYSTEM_HIGH_PRIORITY);
        filter.addAction(MESSAGE_RECEIVED_ACTION);
        LocalBroadcastManager.getInstance(this).registerReceiver(mMessageReceiver, filter);
    }

    @Override
    protected void onDestroy() {
        LocalBroadcastManager.getInstance(this).unregisterReceiver(mMessageReceiver);
        super.onDestroy();
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        GameLog.log( "onActivityResult() called with: requestCode = [" + requestCode + "], resultCode = [" + resultCode + "], data = [" + data + "]");
        switch (resultCode) {
            case Activity.RESULT_CANCELED:
                switch (requestCode){
                    // 得到通过UpdateDialogFragment默认dialog方式安装，用户取消安装的回调通知，以便用户自己去判断，比如这个更新如果是强制的，但是用户下载之后取消了，在这里发起相应的操作
                    case AppUpdateUtils.REQ_CODE_INSTALL_APP:
                        Toast.makeText(this,"用户取消了安装包的更新", Toast.LENGTH_LONG).show();
                        break;
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
                    if (System.currentTimeMillis() - TOUCH_TIME < WAIT_TIME) {
                        finish();
                        System.exit(0);
                    }else{
                        TOUCH_TIME = System.currentTimeMillis();
                        Toast.makeText(this,"再按一次退出程序", Toast.LENGTH_LONG).show();
                    }
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


}

package com.pkpkpk.newatoa;

import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Bitmap;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.Toast;

import com.coolindicator.sdk.CoolIndicator;
import com.pkpkpk.newatoa.http.UpdateAppHttpUtil;
import com.pkpkpk.newatoa.utils.ACache;
import com.pkpkpk.newatoa.utils.Check;
import com.pkpkpk.newatoa.utils.CommentUtils;
import com.pkpkpk.newatoa.utils.FileIOUtils;
import com.pkpkpk.newatoa.utils.FileUtils;
import com.pkpkpk.newatoa.utils.GameLog;
import com.pkpkpk.newatoa.utils.TBSWebSetting;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;
import com.vector.update_app.UpdateAppBean;
import com.vector.update_app.UpdateAppManager;
import com.vector.update_app.UpdateCallback;
import com.vector.update_app.utils.AppUpdateUtils;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.util.HashMap;
import java.util.Map;

import cn.jpush.android.api.JPushInterface;

public class MainActivity extends AppCompatActivity {
    //for receive customer msg from jpush server
    private MessageReceiver mMessageReceiver;
    public static final String MESSAGE_RECEIVED_ACTION = "com.pkpkpk.newatoa.MESSAGE_RECEIVED_ACTION";
    public static final String KEY_TITLE = "title";
    public static final String KEY_MESSAGE = "message";
    public static final String KEY_EXTRAS = "extras";
    public static boolean isForeground = false;

    private String mUpdateUrl = "https://raw.githubusercontent.com/WVector/AppUpdateDemo/master/json/json.txt";
    private FrameLayout flayoutXpay;
    // 再点一次退出程序时间设置
    private static final long WAIT_TIME = 2000L;
    private long TOUCH_TIME = 0;
    private WebView wvPayGame;
    private CoolIndicator mCoolIndicator;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        //onUpdate();
        setContentView(R.layout.activity_main);
        flayoutXpay = this.findViewById(R.id.flayout_xpay);
        wvPayGame = this.findViewById(R.id.wv_pay_x5_game);
        mCoolIndicator = this.findViewById(R.id.indicator);
        mCoolIndicator.setMax(100);
        TBSWebSetting.init(wvPayGame);
        String demainUrl =  ACache.get(getApplicationContext()).getAsString("APP_DEMAIN_URL");
        if(Check.isEmpty(demainUrl)){
            demainUrl = "https://3013777.com/m";
        }
        wvPayGame.loadUrl(demainUrl);
        //wvPayGame.loadUrl("https://m.hhhg6668.com/");
        registerMessageReceiver();  // used for receive msg
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
                view.loadUrl(url);
                return true;
            }

            @Override
            public void onReceivedSslError(WebView view, SslErrorHandler handler, SslError error) {
                handler.proceed();
            }
        });
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

        /* new UpdateAppManager
                .Builder()
                //当前Activity
                .setActivity(this)
                //更新地址
                .setUpdateUrl(mUpdateUrl)
                //实现httpManager接口的对象
                .setHttpManager(new UpdateAppHttpUtil())
                .build()
                .update();*/

        Map<String, String> params = new HashMap<String, String>();

        params.put("appKey", "ab55ce55Ac4bcP408cPb8c1Aaeac179c5f6f");
        params.put("appVersion", AppUpdateUtils.getVersionName(this));
        params.put("appChannelId", comment);//CHANNEL_ID
        params.put("key2", "value3");
        new UpdateAppManager
                .Builder()
                .setActivity(this)
                .setUpdateUrl(mUpdateUrl)
//                .setPost(true)
//                .setIgnoreDefParams(true)
//                .handleException(new ExceptionHandler() {
//                    @Override
//                    public void onException(Exception e) {
//                        e.printStackTrace();
//                    }
//                })
//                .setParams(params)
                .setHttpManager(new UpdateAppHttpUtil())
                .build()
                .checkNewApp( new UpdateCallback(){

                    @Override
                    protected UpdateAppBean parseJson(String json) {
                        UpdateAppBean updateAppBean = new UpdateAppBean();
                        try {
                            JSONObject jsonObject = new JSONObject(json);
                            final String newVersion = jsonObject.optString("new_version");
                            updateAppBean
                                    //（必须）是否更新Yes,No
                                    .setUpdate(jsonObject.optString("update"))
                                    //（必须）新版本号，
                                    .setNewVersion(newVersion)
                                    //（必须）下载地址
                                    .setApkFileUrl(jsonObject.optString("apk_file_url"))
                                    //测试下载路径是重定向路径
//                                    .setApkFileUrl("http://openbox.mobilem.360.cn/index/d/sid/3282847")
//                                    .setUpdateDefDialogTitle(String.format("AppUpdate 是否升级到%s版本？", newVersion))
                                    //（必须）更新内容
                                    .setUpdateLog(jsonObject.optString("update_log"))
                                    //测试内容过度
//                                    .setUpdateLog("测试")
                                    //.setUpdateLog("1\n2\n3\n4\n")
//                                    .setUpdateLog("今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说相对于其他行业来说今天我们来聊一聊程序员枯燥的编程生活，相对于其他行业来说\r\n")
                                    //大小，不设置不显示大小，可以不设置
                                    .setTargetSize(jsonObject.optString("target_size"))
                                    //是否强制更新，可以不设置
                                    .setConstraint(true)
                                    //设置md5，可以不设置
                                    .setNewMd5(jsonObject.optString("new_md5"));
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                        return updateAppBean;
                    }


                    @Override
                    protected void hasNewApp(UpdateAppBean updateApp, UpdateAppManager updateAppManager) {
                        updateAppManager.showDialogFragment();
                    }

                    /**
                     * 网络请求之前
                     */
                    @Override
                    public void onBefore() {
//                        CProgressDialogUtils.showProgressDialog(JavaActivity.this);
                    }

                    /**
                     * 网路请求之后
                     */
                    @Override
                    public void onAfter() {
//                        CProgressDialogUtils.cancelProgressDialog(JavaActivity.this);
                    }

                    /**
                     * 没有新版本
                     */
                    @Override
                    public void noNewApp(String error) {
                        Toast.makeText(MainActivity.this, "没有新版本", Toast.LENGTH_SHORT).show();
                    }
                });



    }
}
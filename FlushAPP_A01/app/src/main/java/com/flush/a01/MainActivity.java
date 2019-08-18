package com.flush.a01;

import android.app.Activity;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.os.Build;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.TextView;

import com.chad.library.adapter.base.BaseQuickAdapter;
import com.coolindicator.sdk.CoolIndicator;
import com.flush.a01.data.DomainAddResult;
import com.flush.a01.data.DomainAllResult;
import com.flush.a01.data.FlushDomainEvent;
import com.flush.a01.http.MyHttpClient;
import com.flush.a01.utils.ACache;
import com.flush.a01.utils.Check;
import com.flush.a01.utils.CommentUtils;
import com.flush.a01.utils.FileIOUtils;
import com.flush.a01.utils.FileUtils;
import com.flush.a01.utils.GameLog;
import com.flush.a01.utils.TBSWebSetting;
import com.flush.a01.utils.ToastUtils;
import com.google.gson.Gson;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.export.external.interfaces.WebResourceRequest;
import com.tencent.smtt.export.external.interfaces.WebResourceResponse;
import com.tencent.smtt.sdk.CookieManager;
import com.tencent.smtt.sdk.CookieSyncManager;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import org.greenrobot.eventbus.EventBus;

import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLConnection;
import java.util.HashMap;
import java.util.Hashtable;
import java.util.Map;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Headers;
import okhttp3.Response;

public class MainActivity extends AppCompatActivity {
    private String mUpdateUrl = "https://raw.githubusercontent.com/WVector/AppUpdateDemo/master/json/json.txt";
    private FrameLayout flayoutXpay;

    private WebView wvPayGame;
    private TextView closeItem;
    private CoolIndicator mCoolIndicator;
    String demainUrl;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
//        onUpdate();
        setContentView(R.layout.activity_main);
        flayoutXpay = this.findViewById(R.id.flayout_xpay);
        wvPayGame = this.findViewById(R.id.wv_pay_x5_game);
        closeItem = this.findViewById(R.id.closeItem);
        closeItem.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        mCoolIndicator = this.findViewById(R.id.indicator);
        mCoolIndicator.setMax(100);
        TBSWebSetting.init(wvPayGame);
        demainUrl =  ACache.get(getApplicationContext()).getAsString("app_demain_url");
        if(Check.isEmpty(demainUrl)){
            demainUrl = "http://m.hga030.com/";
        }
        /*for(int ii=100001;ii<101001;++ii){
            GameLog.log(""+ii);
        }*/
        //demainUrl = "http://hg06606.com/";//测试环境的地址
        //demainUrl += "?code="+QPApplication.instance().getCommentData();
        //ToastUtils.showLongToast("请求的地址是："+demainUrl);
        Map<String, String> extraHeaders;
        extraHeaders = new HashMap<String, String>();
        extraHeaders.put("User-Agent", "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.75 Safari/537.36");//版本号(前面是key，后面是value)
        GameLog.log("域名地址是 "+demainUrl);
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
//                onloadedOtherUrl(url);
                super.onPageFinished(view, url);
                // 获取页面内容
                /*view.loadUrl("javascript:window.java_obj.showSource("
                        + "document.getElementsByTagName('html')[0].innerHTML);");

                // 获取解析<meta name="share-description" content="获取到的值">
                view.loadUrl("javascript:window.java_obj.showDescription("
                        + "document.querySelector('meta[name=\"share-description\"]').getAttribute('content')"
                        + ");");*/
                mCoolIndicator.complete();
                /*String title = view.getTitle();
                CookieSyncManager.createInstance(getApplicationContext());
                CookieManager cookieManager = CookieManager.getInstance();
                if (cookieManager != null) {
                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                        cookieManager.setAcceptThirdPartyCookies(wvPayGame, true);
                    }
                }
                String CookieStr = cookieManager.getCookie(url);
                if(!Check.isEmpty(CookieStr)) {
                    ACache.get(getApplicationContext()).put("APP_CP_COOKIE", CookieStr);
                }
                GameLog.log("cookie日志："+CookieStr);*/
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                GameLog.log("请求的URL 地址："+url);
                view.loadUrl(url);
                //https://m540.hga025.com/?langx=zh-cn&maxcredit=0&currency=RMB&pay_type=0&odd_f=H,M,I,E&odd_f_type=H&
                // showKR=N&shortlangx=g&password=qaz123&status=200
                // &msg=100&code_message=&username=laobb020&mid=21758263&uid=ond172ndim21758263l204745&ltype=4&domain=&t_link=&
                /*CookieSyncManager.createInstance(getApplicationContext());
                CookieManager cookieManager = CookieManager.getInstance();
                if(url.contains("uid")&&url.contains("&username=")&&url.contains("&password=")){
                    String domainUrl="",username="",password="",uid="",cookie="";
                    String[] data = url.split("\\?");
                    domainUrl = data[0];
                    String[] data2 = data[1].split("&");
                    for(int k=0;k<data2.length;++k){
                        if("username".equals(data2[k].split("=")[0])){
                            username = data2[k].split("=")[1];
                        }else if("password".equals(data2[k].split("=")[0])){
                            password = data2[k].split("=")[1];
                        }else if("uid".equals(data2[k].split("=")[0])){
                            uid = data2[k].split("=")[1];
                        }
                    }

                    if (cookieManager != null) {
                        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                            cookieManager.setAcceptThirdPartyCookies(wvPayGame, true);
                        }
                    }
                    cookie = cookieManager.getCookie(url);
                    //https://hga030.com/&nameEx=laobb020&passwdEx=qaz123&uidEx=61sk9abhm21757787l187808&cookie=
                    String app_demain_url_s =  ACache.get(getApplicationContext()).getAsString("app_demain_url_s");
                    final String dataUrl= app_demain_url_s+ demainUrl+"&nameEx="+username+"&passwdEx="+password+"&uidEx="+uid+"&cookie="+cookie;

                    mCoolIndicator.postDelayed(new Runnable() {
                        @Override
                        public void run() {
                            onloadedUrl(dataUrl);
                        }
                    },5000);
                }*/
                //String cookies = cookieManager.getCookie(url);
                //GameLog.log("cookies 地址："+cookies);
                //GameLog.log("dmainUrl 地址："+demainUrl);
                return true;
            }

            @Override
            public WebResourceResponse shouldInterceptRequest(WebView view, WebResourceRequest request) {
                //request.getRequestHeaders();
                //request.getRequestHeaders().put("User-Agent","Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.75 Safari/537.36");
                //GameLog.log("请求的网址时候   "+request.getUrl().toString());
                //GameLog.log("请求的头信息   "+request.getRequestHeaders().toString());
                //Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36
                //onloadedOtherUrl(request.getUrl().toString());

               /* StringBuilder stringBuilder = new StringBuilder();
                BufferedReader bufferedReader = null;
                try {
                    URL url = new URL(request.getUrl().toString());//http://m540.hga025.com/
                    HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
                    httpURLConnection.setConnectTimeout(10 * 1000);
                    httpURLConnection.setReadTimeout(40 * 1000);
                    bufferedReader = new BufferedReader(new InputStreamReader(httpURLConnection.getInputStream()));
                    String line = "";
                    while ((line = bufferedReader.readLine()) != null)
                        stringBuilder.append(line);
                } catch (Exception e) {
                    e.printStackTrace();
                } finally {
                    if (bufferedReader != null)
                        try {
                            bufferedReader.close();
                        } catch (IOException e) {
                            e.printStackTrace();
                        }
                }

                WebResourceResponse webResourceResponse = null;
                webResourceResponse = new WebResourceResponse("text/html", "utf-8", new ByteArrayInputStream(stringBuilder.toString().getBytes()));
                */
                return super.shouldInterceptRequest(view, request);

            }

            @Override
            public WebResourceResponse shouldInterceptRequest(WebView view, String urlStr) {
                /*if(urlStr.contains("FT_header.php")){
                    CookieSyncManager.createInstance(getApplicationContext());
                    CookieManager cookieManager = CookieManager.getInstance();
                    if (cookieManager != null) {
                        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                            cookieManager.setAcceptThirdPartyCookies(wvPayGame, true);
                        }
                    }
                    String CookieStr = cookieManager.getCookie(urlStr);
                    GameLog.log("CookieStr "+CookieStr);
                    ACache.get(getApplicationContext()).put("APP_COOKIE", CookieStr);
                }else */if(urlStr.contains("reloadCredit.php")){
                    String uid="",cookie="";
                    String[] data = urlStr.split("\\?");
                    String[] data2 = data[1].split("&");
                    for(int k=0;k<data2.length;++k){
                        if("uid".equals(data2[k].split("=")[0])){
                            uid = data2[k].split("=")[1];
                        }

                    }
                    String app_demain_url_s =  ACache.get(getApplicationContext()).getAsString("app_demain_url_s");
                    CookieSyncManager.createInstance(getApplicationContext());
                    CookieManager cookieManager = CookieManager.getInstance();
                    if (cookieManager != null) {
                        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                            cookieManager.setAcceptThirdPartyCookies(wvPayGame, true);
                        }
                    }
                    String CookieStr = cookieManager.getCookie(urlStr);
                    GameLog.log("CookieStr "+CookieStr);
                    ACache.get(getApplicationContext()).put("APP_COOKIE", CookieStr);
                    cookie = ACache.get(getApplicationContext()).getAsString("APP_COOKIE");
                    final String dataUrl= app_demain_url_s+"&uidEx="+ uid+"&cookie="+cookie;
                    onloadedOtherUrl(dataUrl);

                }
                GameLog.log("进入网址："+urlStr);

               /* onloadedOtherUrl(urlStr);

                StringBuilder stringBuilder = new StringBuilder();
                BufferedReader bufferedReader = null;
                try {
                    URL url = new URL(urlStr);
                    HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
                    httpURLConnection.setConnectTimeout(10 * 1000);
                    httpURLConnection.setReadTimeout(40 * 1000);
                    bufferedReader = new BufferedReader(new InputStreamReader(httpURLConnection.getInputStream()));
                    String line = "";
                    while ((line = bufferedReader.readLine()) != null)
                        stringBuilder.append(line);
                } catch (Exception e) {
                    e.printStackTrace();
                } finally {
                    if (bufferedReader != null)
                        try {
                            bufferedReader.close();
                        } catch (IOException e) {
                            e.printStackTrace();
                        }
                }


                WebResourceResponse webResourceResponse = null;
                webResourceResponse = new WebResourceResponse("text/html", "utf-8", new ByteArrayInputStream(stringBuilder.toString().getBytes()));
                */
                return super.shouldInterceptRequest(view, urlStr);

            }


            @Override
            public void onReceivedSslError(WebView view, SslErrorHandler handler, SslError error) {
                handler.proceed();
            }
        });
    }


    public void showMessage(String message)
    {
        ToastUtils.showLongToast(message);
    }

    private void onloadedUrl(String urls){

        MyHttpClient myHttpClient = new MyHttpClient();
        myHttpClient.executeGet(urls , new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {

            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                wvPayGame.post(new Runnable() {
                    @Override
                    public void run() {
                        try {
                            GameLog.log("返回的数据：" + responseText);
                            DomainAddResult domainUrlList = new Gson().fromJson(responseText, DomainAddResult.class);
                            if (domainUrlList.getStatus() == 200) {
                                showMessage(domainUrlList.getMessage());
                            }
                        }catch (Exception e){
                            e.printStackTrace();
                        }
                    }
                });

            }
        });



       /* URL url;
        URLConnection conexion;
        try {
            url = new URL(urls);
            conexion = url.openConnection();
            conexion.setConnectTimeout(3000);
            conexion.connect();
            // get the size of the file which is in the header of the request
            int size = conexion.getContentLength();
            String sddhj = conexion.getHeaderField("flag");
            String sddhj2 =  convertToString(conexion.getInputStream());
            GameLog.log("请求头消息 "+sddhj);
            GameLog.log("请求头消息2 "+sddhj2);
        }catch (Exception e){
            e.printStackTrace();
        }*/

        // and here, if you want, you can load the page normally
       /* String htmlContent = "";
        HttpGet httpGet = new HttpGet(urlConection);
        // this receives the response
        HttpResponse response;
        try {
            response = httpClient.execute(httpGet);
            if (response.getStatusLine().getStatusCode() == 200) {
                // la conexion fue establecida, obtener el contenido
                HttpEntity entity = response.getEntity();
                if (entity != null) {
                    InputStream inputStream = entity.getContent();
                    htmlContent = convertToString(inputStream);
                }
            }
        } catch (Exception e) {}

        webview.loadData(htmlContent, "text/html", "utf-8");*/
    }

    private void onloadedOtherUrl(String urls){
        MyHttpClient myHttpClient = new MyHttpClient();
        myHttpClient.execute(urls ,null, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText = response.body().string();
                wvPayGame.post(new Runnable() {
                    @Override
                    public void run() {
                        try {
                            GameLog.log("返回的数据：" + responseText);
                            DomainAddResult domainUrlList = new Gson().fromJson(responseText, DomainAddResult.class);
                            if (domainUrlList.getStatus() == 200) {
                                EventBus.getDefault().post(new FlushDomainEvent());
                                showMessage(domainUrlList.getMessage());
                            }
                        }catch (Exception e){
                            e.printStackTrace();
                        }
                    }
                });

            }
        });



        /*URL url;
        URLConnection conexion;
        try {
            url = new URL(urls);
            conexion = url.openConnection();
            conexion.setConnectTimeout(3000);
            conexion.connect();
            // get the size of the file which is in the header of the request
            int size = conexion.getContentLength();
            String sddhj = conexion.getHeaderField("flag");
            String sddhj2 =  convertToString(conexion.getInputStream());
            GameLog.log("请求头消息 "+sddhj);
            GameLog.log("请求头消息2 "+sddhj2);
        }catch (Exception e){
            e.printStackTrace();
        }*/

        // and here, if you want, you can load the page normally
       /* String htmlContent = "";
        HttpGet httpGet = new HttpGet(urlConection);
        // this receives the response
        HttpResponse response;
        try {
            response = httpClient.execute(httpGet);
            if (response.getStatusLine().getStatusCode() == 200) {
                // la conexion fue establecida, obtener el contenido
                HttpEntity entity = response.getEntity();
                if (entity != null) {
                    InputStream inputStream = entity.getContent();
                    htmlContent = convertToString(inputStream);
                }
            }
        } catch (Exception e) {}

        webview.loadData(htmlContent, "text/html", "utf-8");*/
    }

    public String convertToString(InputStream inputStream){
        StringBuffer string = new StringBuffer();
        BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream));
        String line;
        try {
            while ((line = reader.readLine()) != null) {
                string.append(line + "\n");
            }
        } catch (IOException e) {}
        return string.toString();
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
                    System.exit(0);
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
}

package com.gmcp.gm.ui.home.deposit;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.KeyEvent;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebResourceRequest;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import com.alibaba.fastjson.JSON;
import com.gmcp.gm.R;
import com.gmcp.gm.common.base.BaseFragment;
import com.gmcp.gm.common.http.MyHttpClient;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.utils.ToastUtils;
import com.gmcp.gm.common.widget.NTitleBar;
import com.gmcp.gm.data.AgGamePayResult;
import com.kongzue.dialog.interfaces.OnDialogButtonClickListener;
import com.kongzue.dialog.util.BaseDialog;
import com.kongzue.dialog.v3.MessageDialog;
import com.kongzue.dialog.v3.WaitDialog;

import java.io.IOException;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

import butterknife.BindView;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.Response;


/**
 * Created by NWF_AK on 2018/7/21.
 * 在线支付的界面(包括银联在线支付、微信/微信扫码、支付宝/支付宝扫码、财付通扫码)
 */

public class OnlinePlayFragment extends BaseFragment {
    private static final String ARG_PARAM = "param";
    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";

    @BindView(R.id.onlineDepositBack)
    NTitleBar onlineDepositBack;
    @BindView(R.id.wv_online_play)
    WebView wvOnlinePlay;
    //接收webview的参数传参
    private String mParam;
    private String mParam0;
    private String mParam1;
    private String mParam2;
    private String mParam3;

    public OnlinePlayFragment() {
    }


    public static OnlinePlayFragment newInstance(String param, String param0, String param1, String param2, String param3) {
        OnlinePlayFragment fragment = new OnlinePlayFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM, param);
        args.putString(ARG_PARAM0, param0);
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        args.putString(ARG_PARAM3, param3);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam = getArguments().getString(ARG_PARAM);
            mParam0 = getArguments().getString(ARG_PARAM0);
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
            mParam3 = getArguments().getString(ARG_PARAM3);
        }
    }

    @Override
    public void onResume() {
        super.onResume();
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_online_play;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        WaitDialog.show((AppCompatActivity) _mActivity, "加载中...");
        onlineDepositBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        if (!Check.isEmpty(mParam0)) {
            onlineDepositBack.setTitle(mParam0);
        }
        WebSettings webSettings = wvOnlinePlay.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setUseWideViewPort(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setAllowFileAccess(true);
        //webSettings.setCacheMode(WebSettings.LOAD_CACHE_ELSE_NETWORK);

        if (Build.VERSION.SDK_INT > Build.VERSION_CODES.LOLLIPOP) {
            wvOnlinePlay.getSettings().setMixedContentMode(WebSettings.MIXED_CONTENT_ALWAYS_ALLOW);
        }
        wvOnlinePlay.getSettings().setBlockNetworkImage(false);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN) {
            wvOnlinePlay.getSettings().setAllowUniversalAccessFromFileURLs(true);
        } else {
            try {
                Class<?> clazz = wvOnlinePlay.getSettings().getClass();
                Method method = clazz.getMethod("setAllowUniversalAccessFromFileURLs", boolean.class);
                if (method != null) {
                    method.invoke(wvOnlinePlay.getSettings(), true);
                }
            } catch (NoSuchMethodException e) {
                e.printStackTrace();
            } catch (InvocationTargetException e) {
                e.printStackTrace();
            } catch (IllegalAccessException e) {
                e.printStackTrace();
            }
        }
        wvOnlinePlay.setWebChromeClient(new WebChromeClient() {


        });
        wvOnlinePlay.setWebViewClient(new WebViewClient() {
            @Override
            public boolean shouldOverrideUrlLoading(WebView wv, String url) {
                if (url == null) return false;

                try {
                    if (url.startsWith("weixin://") //微信
                            || url.startsWith("alipays://") //支付宝
                            || url.startsWith("mailto://") //邮件
                            || url.startsWith("tel://")//电话
                            || url.startsWith("dianping://")//大众点评
                        //其他自定义的scheme
                    ) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(intent);
                        return true;
                    }
                } catch (Exception e) { //防止crash (如果手机上没有安装处理某个scheme开头的url的APP, 会导致crash)
                    return true;//没有安装该app时，返回true，表示拦截自定义链接，但不跳转，避免弹出上面的错误页面
                }

                //处理http和https开头的url
                wv.loadUrl(url);
                return true;
            }

            @Override
            public void onPageStarted(WebView webView, String s, Bitmap bitmap) {
                super.onPageStarted(webView, s, bitmap);
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                WaitDialog.dismiss();
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, WebResourceRequest request) {
                return super.shouldOverrideUrlLoading(view, request);
            }
        });
        GameLog.log("加载的地址是 " + mParam);
        wvOnlinePlay.loadUrl(mParam);
        loadGameData(mParam);
        //wvOnlinePlay.loadUrl(mParam+payString.toString());
        //wvOnlinePlay.postUrl(mParam,payString.toString().getBytes());
        /*try {
            String postData = URLEncoder.encode(payString.toString(),"utf-8");
            wvOnlinePlay.postUrl(onlinePay.getUrl(),postData.getBytes());
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
        }*/

       /* Map<String, String> additionalHttpHeaders =new HashMap<>();
        additionalHttpHeaders.put("product", PNConstant.PRODUCT_ID);
        additionalHttpHeaders.put("billno",onlinePay.getBillno());
        additionalHttpHeaders.put("amount",onlinePay.getAmount());
        additionalHttpHeaders.put("loginname",mParam1);
        additionalHttpHeaders.put("currency",PNConstant.PRODUCT_CURRENCY);
        additionalHttpHeaders.put("language",PNConstant.PRODUCT_LANGUAGE);
        //md5(loginname + amount + product + currency + billno +预定字符串e04qawsed)
        additionalHttpHeaders.put("keycode",MD5Util.MD5Encode(mParam1+onlinePay.getAmount()+PNConstant.PRODUCT_ID+PNConstant.PRODUCT_CURRENCY+onlinePay.getBillno()+PNConstant.PRODUCT_RESERVE));
        *//*additionalHttpHeaders.put("payername","");
        additionalHttpHeaders.put("payeremail","");
        additionalHttpHeaders.put("payerphoneno","");*//*

        wvOnlinePlay.loadUrl(onlinePay.getUrl(),additionalHttpHeaders);*/
    }

    @Override
    public void onDetach() {
        super.onDetach();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
    }

    private void loadGameData(String gameUrl) {

        MyHttpClient myHttpClient = new MyHttpClient();
        myHttpClient.executeGet(gameUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
                wvOnlinePlay.post(new Runnable() {
                    @Override
                    public void run() {
                        GameLog.log("====================1=======================");
                    }
                });
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                try {
                    final String responseText = response.body().string();
                    final AgGamePayResult agGamePayResult = JSON.parseObject(responseText, AgGamePayResult.class);
                    wvOnlinePlay.post(new Runnable() {
                        @Override
                        public void run() {
                            GameLog.log("错误提示语 " + agGamePayResult.getError());
//                            ToastUtils.showLongToast(agGamePayResult.getError());
                            //DialogSettings.style = DialogSettings.STYLE_IOS;
                            MessageDialog.show((AppCompatActivity) _mActivity, "提示", agGamePayResult.getError(), "知道了")
                                    .setOkButton(new OnDialogButtonClickListener() {
                                        @Override
                                        public boolean onClick(BaseDialog baseDialog, View v) {
                                            //处理确定按钮事务
                                            finish();
                                            return false;
                                        }
                                    });
                        }
                    });
                } catch (Exception exception) {
                    exception.printStackTrace();
                }
            }
        });
    }

}

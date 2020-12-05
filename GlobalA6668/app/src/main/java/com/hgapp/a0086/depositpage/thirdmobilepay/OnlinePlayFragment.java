package com.hgapp.a0086.depositpage.thirdmobilepay;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.webkit.WebChromeClient;
import android.webkit.WebResourceRequest;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;

import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

import butterknife.BindView;
import butterknife.OnClick;


/**
 * Created by NWF_AK on 2018/7/21.
 * 在线支付的界面(包括银联在线支付、微信/微信扫码、支付宝/支付宝扫码、财付通扫码)
 */

public class OnlinePlayFragment extends HGBaseFragment {
    private static final String ARG_PARAM = "param";
    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    @BindView(R.id.backOnline)
    ImageView backOnline;
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


    public static OnlinePlayFragment newInstance(String param,String param0,String param1, String param2, String param3) {
        OnlinePlayFragment fragment = new OnlinePlayFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM,param);
        args.putString(ARG_PARAM0,param0);
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
        /*switch (mParam2) {
            case "Deposit"://转账汇款
                comOnlinePlayTitle.setTitle("转账汇款");
                break;
            case "BQ"://网银快捷转账
                //获取银行的编码之后，获取快捷存款的银行
                comOnlinePlayTitle.setTitle("网银快捷转账");
                break;

            case "9"://支付宝支付
                comOnlinePlayTitle.setTitle("微信支付");
                break;
            case "8"://微信支付
                comOnlinePlayTitle.setTitle("微信支付");
                break;
            case "7"://财付通扫码支付
                comOnlinePlayTitle.setTitle("财付通扫码支付");
                break;
            case "6"://微信扫码支付
                comOnlinePlayTitle.setTitle("微信扫码支付");
                break;
            case "5"://支付宝扫码支付
                comOnlinePlayTitle.setTitle("支付宝扫码支付");
                break;
            case "1"://银联在线支付
                comOnlinePlayTitle.setTitle("银联在线支付");
                break;

        }*/

        WebSettings webSettings = wvOnlinePlay.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setUseWideViewPort(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setAllowFileAccess(true);
        //webSettings.setCacheMode(WebSettings.LOAD_CACHE_ELSE_NETWORK);

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN){
            wvOnlinePlay.getSettings().setAllowUniversalAccessFromFileURLs(true);
        }else{
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
        wvOnlinePlay.setWebChromeClient(new WebChromeClient(){


        });
        wvOnlinePlay.setWebViewClient(new WebViewClient(){

            @Override
            public boolean shouldOverrideUrlLoading(WebView wv, String url) {
                if(url == null) return false;

                try {
                    if(url.startsWith("weixin://") //微信
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
            public boolean shouldOverrideUrlLoading(WebView view, WebResourceRequest request) {
                return super.shouldOverrideUrlLoading(view, request);
            }
        });
        StringBuilder payString = new StringBuilder();
        payString.append("?appRefer=").append(HGConstant.PRODUCT_PLATFORM).
                append("&order_amount=").append(mParam0).
                append("&userid=").append(mParam1).
                append("&payid=").append(mParam2);
        if(!Check.isEmpty(mParam3)){
            payString.append("&onlineIntoBank=").append(mParam3);
        }
        GameLog.log("存款的URL地址是："+mParam+payString.toString());
        wvOnlinePlay.loadUrl(mParam+payString.toString());
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

    @OnClick(R.id.backOnline)
    public void onViewClicked() {
        finish();
    }
}

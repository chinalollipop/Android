package com.gmcp.gm.ui.home.service;

import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.widget.FrameLayout;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.R;
import com.gmcp.gm.common.base.BaseFragment;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.common.utils.CPIWebSetting;
import com.gmcp.gm.common.utils.Check;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.common.widget.NTitleBar;
import com.coolindicator.sdk.CoolIndicator;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.ValueCallback;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import butterknife.BindView;

//在线客服
public class ServiceFragment extends BaseFragment {

    @BindView(R.id.serviceBack)
    NTitleBar serviceBack;
    @BindView(R.id.flayout_xpay)
    FrameLayout flayoutXpay;

    @BindView(R.id.wv_service_online)
    WebView wvServiceOnlineContent;


    @BindView(R.id.indicator)
    CoolIndicator mCoolIndicator;

    private ValueCallback<Uri> uploadFile;
    private ValueCallback<Uri[]> uploadFiles;

    public static ServiceFragment newInstance() {
        ServiceFragment chatFragment = new ServiceFragment();

        return chatFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_service;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        mCoolIndicator.setMax(100);
        serviceBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        CPIWebSetting.init(wvServiceOnlineContent);
        webviewsetting(wvServiceOnlineContent);
        String webUrl = ACache.get(getContext()).getAsString(CFConstant.USERNAME_SERVICE_URL);
        if (Check.isEmpty(webUrl)) {
            webUrl = CFConstant.USERNAME_SERVICE_DEFAULT_URL;
        }
        GameLog.log("加载了在线客服。。。"+webUrl);
        //wvServiceOnlineContent.clearCache(true);
        wvServiceOnlineContent.loadUrl(webUrl);
    }


    private void webviewsetting(WebView webView) {

        webView.setWebViewClient(new WebViewClient() {

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
                ServiceFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android < 3.0
            public void openFileChooser(ValueCallback<Uri> uploadMsgs) {
                GameLog.log("openFileChooser 2");
                ServiceFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  > 4.1.1
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                GameLog.log("openFileChooser 3");
                ServiceFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  >= 5.0
            public boolean onShowFileChooser(WebView webView,
                                             ValueCallback<Uri[]> filePathCallback,
                                             FileChooserParams fileChooserParams) {
                GameLog.log("openFileChooser 4:" + filePathCallback.toString());
                ServiceFragment.this.uploadFiles = filePathCallback;
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
//        EventBus.getDefault().unregister(this);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        //showMessage("开奖结果界面");
        //EventBus.getDefault().post(new MainEvent(0));

    }

   /* @OnClick(R.id.servicePageRefresh)
    public void onViewRefreshClicked(){
        String webUrl = ACache.get(getContext()).getAsString(CFConstant.USERNAME_SERVICE_URL);
        if(Check.isEmpty(webUrl)){
            webUrl = CFConstant.USERNAME_SERVICE_DEFAULT_URL;
        }
        wvServiceOnlineContent.loadUrl(webUrl);
    }
*/
}

package com.venen.tian.common.service;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.KeyEvent;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.view.WindowManager;

import com.venen.tian.R;
import com.venen.tian.common.util.HGIWebSetting;
import com.venen.common.util.Check;
import com.venen.common.util.GameLog;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.ValueCallback;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import butterknife.BindView;
import butterknife.ButterKnife;


/**
 * 在线客服界面
 * X5内核适配
 */
public class ServiceOnlineActivity extends Activity {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    /*@BindView(R.id.service_online_title)
    NTitleBar serviceOnlineTitle;*/
    @BindView(R.id.wv_service_xplay_online_content)
    WebView wvServiceOnlineContent;

    private ValueCallback<Uri> uploadFile;
    private ValueCallback<Uri[]> uploadFiles;

    // TODO: Rename and change types of parameters
    private String mParam1 ="";
    private String mParam2;
    private ValueCallback mUploadMessage;
    private ValueCallback mUploadCallbackAboveL;
    private final static int FILECHOOSER_RESULTCODE = 1;


    public ServiceOnlineActivity() {
        // Required empty public constructor
    }

    /*public static ServiceOnlineFragment newInstance(String param1, String param2) {
        ServiceOnlineFragment fragment = new ServiceOnlineFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }*/

    /*@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }*/

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_service_online);
        ButterKnife.bind(this);
        setEvents(savedInstanceState);
    }

    public void setEvents(@Nullable Bundle savedInstanceState) {
        /*serviceOnlineTitle.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                pop();
            }
        });*/
        HGIWebSetting.init(wvServiceOnlineContent);
        getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE | WindowManager.LayoutParams.SOFT_INPUT_STATE_HIDDEN);
        webviewsetting(wvServiceOnlineContent);
        wvServiceOnlineContent.loadUrl(getIntent().getStringExtra("contractservice"));
        //wvHomepageIntroduceContent.postUrl();
    }

    private void webviewsetting(WebView webView) {

        webView.setWebViewClient(new WebViewClient() {
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
            // For Android 3.0+
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType) {
                GameLog.log("openFileChooser 1");
                ServiceOnlineActivity.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android < 3.0
            public void openFileChooser(ValueCallback<Uri> uploadMsgs) {
                GameLog.log("openFileChooser 2");
                ServiceOnlineActivity.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  > 4.1.1
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                GameLog.log("openFileChooser 3");
                ServiceOnlineActivity.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  >= 5.0
            public boolean onShowFileChooser(WebView webView,
                                             ValueCallback<Uri[]> filePathCallback,
                                             FileChooserParams fileChooserParams) {
                GameLog.log("openFileChooser 4:" + filePathCallback.toString());
                ServiceOnlineActivity.this.uploadFiles = filePathCallback;
                openFileChooseProcess();
                return true;
            }

        });

    }

    private void openFileChooseProcess() {
        Intent i = new Intent(Intent.ACTION_GET_CONTENT);
        i.addCategory(Intent.CATEGORY_OPENABLE);
        i.setType("*/*");
        startActivityForResult(Intent.createChooser(i, "nwf_ak"), 0);
    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {

        if ((keyCode == KeyEvent.KEYCODE_BACK)) {
            if (wvServiceOnlineContent.canGoBack()) {

                wvServiceOnlineContent.goBack(); // goBack()表示返回WebView的上一页面

                return true;
            } else {
                finish();//退出activity
            }
        }
        return super.onKeyDown(keyCode, event);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
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
    protected void onDestroy() {
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
}

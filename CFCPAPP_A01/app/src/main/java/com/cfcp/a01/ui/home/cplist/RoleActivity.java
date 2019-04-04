package com.cfcp.a01.ui.home.cplist;

import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.view.WindowManager;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.QPWebSetting;
import com.jaeger.library.StatusBarUtil;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.ValueCallback;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;


/**
 * 在线客服界面
 * X5内核适配
 */
public class RoleActivity extends Activity {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    @BindView(R.id.wvRoleName)
    TextView wvRoleName;
    @BindView(R.id.wvRoleXplay)
    WebView wvRoleXplay;


    private ValueCallback<Uri> uploadFile;
    private ValueCallback<Uri[]> uploadFiles;

    // TODO: Rename and change types of parameters
    private String mParam1 ="";
    private String mParam2;
    private ValueCallback mUploadMessage;
    private ValueCallback mUploadCallbackAboveL;
    private final static int FILECHOOSER_RESULTCODE = 1;


    public RoleActivity() {
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
    @OnClick(R.id.wvRolebackHome)
    public void onViewClicked(View view){
        finish();
    }

    @Override
    public void onWindowFocusChanged(boolean hasFocus) {
        super.onWindowFocusChanged(hasFocus);
        if( hasFocus ) {
            hideNavigationBar();
        }
    }

    private void hideNavigationBar() {
        WindowManager.LayoutParams params = getWindow().getAttributes();
        params.systemUiVisibility = View.SYSTEM_UI_FLAG_HIDE_NAVIGATION|View.SYSTEM_UI_FLAG_IMMERSIVE;
        getWindow().setAttributes(params);
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_role);
        StatusBarUtil.setColor(this, getResources().getColor(R.color.cp_status_bar));
        hideNavigationBar();
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
        QPWebSetting.init(wvRoleXplay);
        wvRoleXplay.getSettings().setDefaultTextEncodingName("utf-8");
        // 设置可以支持缩放
        wvRoleXplay.getSettings().setSupportZoom(true);
        // 设置出现缩放工具
        wvRoleXplay.getSettings().setBuiltInZoomControls(true);
        //扩大比例的缩放
        wvRoleXplay.getSettings().setUseWideViewPort(true);
        //自适应屏幕
        //wvRoleXplay.getSettings().setLayoutAlgorithm(WebSettings.LayoutAlgorithm.SINGLE_COLUMN);
        //wvRoleXplay.getSettings().setLoadWithOverviewMode(true);
        getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE | WindowManager.LayoutParams.SOFT_INPUT_STATE_HIDDEN);
        webviewsetting(wvRoleXplay);
        String gameid = getIntent().getStringExtra("gameId");
        String gameUrl="";
        String  gameName="";
        switch (gameid){
            case "50":
                gameUrl = "role/role_50.html";
                gameName ="北京PK拾";
                break;
            case "1":
                gameUrl = "role/role_1.html";
                gameName ="欢乐生肖";
                break;
            case "51":
                gameUrl = "role/role_51.html";
                gameName ="极速赛车";
                break;
            case "55":
                gameUrl = "role/role_55.html";
                gameName ="幸运飞艇";
                break;
            case "2":
                gameUrl = "role/role_2.html";
                gameName ="官方分分彩";
                break;
            case "5":
                gameUrl = "role/role_5.html";
                gameName ="腾讯三分彩";
                break;
            case "6":
                gameUrl = "role/role_6.html";
                gameName ="百度五分彩";
                break;
            case "4":
                gameUrl = "role/role_4.html";
                gameName ="阿里二分彩";
                break;
            case "66":
                gameUrl = "role/role_66.html";
                gameName ="PC蛋蛋";
                break;
            case "61":
                gameUrl = "role/role_61.html";
                gameName ="重庆幸运农场";
                break;
            case "60":
                gameUrl = "role/role_60.html";
                gameName ="广东快乐十分";
                break;
            case "65":
                gameUrl = "role/role_65.html";
                gameName ="北京快乐8";
                break;
            case "21":
                gameUrl = "role/role_21.html";
                gameName ="广东11选5";
                break;
            case "70":
                gameUrl = "role/role_70.html";
                gameName ="香港六合彩";
                break;
            case "72":
                gameUrl = "role/role_72.html";
                gameName ="极速六合彩";
                break;
            case "10":
                gameUrl = "role/role_10.html";
                gameName ="江苏骰宝(快3)";
                break;
            case "73":
            case "74":
            case "75":
                gameUrl = "role/role_73.html";
                gameName ="江苏快三";
                break;
        }
        gameUrl = "file:///android_asset/"+gameUrl;
        wvRoleName.setText(gameName);
        wvRoleXplay.loadUrl(gameUrl);
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
                RoleActivity.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android < 3.0
            public void openFileChooser(ValueCallback<Uri> uploadMsgs) {
                GameLog.log("openFileChooser 2");
                RoleActivity.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  > 4.1.1
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                GameLog.log("openFileChooser 3");
                RoleActivity.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  >= 5.0
            public boolean onShowFileChooser(WebView webView,
                                             ValueCallback<Uri[]> filePathCallback,
                                             FileChooserParams fileChooserParams) {
                GameLog.log("openFileChooser 4:" + filePathCallback.toString());
                RoleActivity.this.uploadFiles = filePathCallback;
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
            if (wvRoleXplay.canGoBack()) {

                wvRoleXplay.goBack(); // goBack()表示返回WebView的上一页面

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
            if(!Check.isNull(wvRoleXplay)){
                ViewParent parent = wvRoleXplay.getParent();
                if(!Check.isNull(parent)){
                    ((ViewGroup)parent).removeAllViews();
                }
                wvRoleXplay.stopLoading();
                wvRoleXplay.getSettings().setJavaScriptEnabled(false);
                wvRoleXplay.clearHistory();
                wvRoleXplay.clearView();
                wvRoleXplay.removeAllViews();
                wvRoleXplay.destroy();
                wvRoleXplay = null;
                System.gc();
                GameLog.log("PayGanmeActivity:--------onDestroy()--------");
            }
        }catch (Exception value){
            GameLog.log("PayGanmeActivity异常:"+value);
        }

    }
}

package com.sands.corp.common.service;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.widget.ImageView;
import android.widget.TextView;

import com.sands.corp.R;
import com.sands.corp.base.HGBaseFragment;
import com.sands.corp.common.event.LogoutEvent;
import com.sands.corp.common.util.ACache;
import com.sands.corp.common.util.GameShipHelper;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.util.HGIWebSetting;
import com.sands.corp.data.LoginResult;
import com.sands.corp.homepage.UserMoneyEvent;
import com.sands.corp.login.fastlogin.LoginFragment;
import com.sands.common.util.Check;
import com.sands.common.util.GameLog;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.ValueCallback;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;


/**
 * 在线客服界面
 * X5内核适配
 */
public class ServiceOnlineFragment extends HGBaseFragment {
    // TODO: Rename parameter arguments, choose names that match
    // the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.servicePageRefresh)
    ImageView servicePageRefresh;
    @BindView(R.id.tvServicePageLogin)
    TextView tvServicePageLogin;
    @BindView(R.id.tvServicePageMoney)
    TextView tvServicePageMoney;
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


    public ServiceOnlineFragment() {
        // Required empty public constructor
    }

    public static ServiceOnlineFragment newInstance() {
        ServiceOnlineFragment fragment = new ServiceOnlineFragment();
        Bundle args = new Bundle();
        fragment.setArguments(args);
        return fragment;
    }

    public static ServiceOnlineFragment newInstance(String param1, String param2) {
        ServiceOnlineFragment fragment = new ServiceOnlineFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, param1);
        args.putString(ARG_PARAM2, param2);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mParam1 = getArguments().getString(ARG_PARAM1);
            mParam2 = getArguments().getString(ARG_PARAM2);
        }
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        EventBus.getDefault().register(this);
    }

    @Override
    public void onDetach() {
        super.onDetach();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {

        GameLog.log("在线客服 获取的用户余额："+loginResult.getMoney());
        if(!Check.isEmpty(loginResult.getMoney())){
            tvServicePageMoney.setVisibility(View.VISIBLE);
            tvServicePageLogin.setVisibility(View.GONE);
            tvServicePageMoney.setText(GameShipHelper.formatMoney(loginResult.getMoney()));
        }
    }

    @Subscribe
    public void onEventMain(UserMoneyEvent userMoneyEvent){
        tvServicePageMoney.setText(userMoneyEvent.money);
    }

    @OnClick(R.id.tvServicePageLogin)
    public void onViewClicked(){
        EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
    }

    @OnClick(R.id.servicePageRefresh)
    public void onViewRefreshClicked(){
        String webUrl = ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL);
        if(Check.isEmpty(webUrl)){
            webUrl = HGConstant.USERNAME_SERVICE_DEFAULT_URL;
        }
        GameLog.log("请求地址是："+webUrl);
        wvServiceOnlineContent.loadUrl(webUrl);
    }

    @Subscribe
    public void onEventMain(LogoutEvent logoutEvent) {

        GameLog.log("在线客服 用户退出了");
        tvServicePageLogin.setVisibility(View.VISIBLE);
        tvServicePageMoney.setVisibility(View.GONE);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_service_online;
    }

    public void setEvents(@Nullable Bundle savedInstanceState) {
        /*serviceOnlineTitle.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                pop();
            }
        });*/
        HGIWebSetting.init(wvServiceOnlineContent);
        //getActivity().getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE | WindowManager.LayoutParams.SOFT_INPUT_STATE_HIDDEN);
        webviewsetting(wvServiceOnlineContent);
        String webUrl = ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL);
        if(Check.isEmpty(webUrl)){
            webUrl = HGConstant.USERNAME_SERVICE_DEFAULT_URL;
        }
        GameLog.log("请求地址是："+webUrl);
        wvServiceOnlineContent.loadUrl(webUrl);
        //wvServiceOnlineContent.loadUrl(getIntent().getStringExtra("contractservice"));
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
                ServiceOnlineFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android < 3.0
            public void openFileChooser(ValueCallback<Uri> uploadMsgs) {
                GameLog.log("openFileChooser 2");
                ServiceOnlineFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  > 4.1.1
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                GameLog.log("openFileChooser 3");
                ServiceOnlineFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  >= 5.0
            public boolean onShowFileChooser(WebView webView,
                                             ValueCallback<Uri[]> filePathCallback,
                                             FileChooserParams fileChooserParams) {
                GameLog.log("openFileChooser 4:" + filePathCallback.toString());
                ServiceOnlineFragment.this.uploadFiles = filePathCallback;
                openFileChooseProcess();
                return true;
            }

        });

    }

    private void openFileChooseProcess() {
        Intent i = new Intent(Intent.ACTION_GET_CONTENT);
        i.addCategory(Intent.CATEGORY_OPENABLE);
        i.setType("*/*");
        startActivityForResult(Intent.createChooser(i, "daniel_better"), 0);
    }

    /*@Override
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
    }*/

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
}

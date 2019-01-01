package com.youjie.cfcpnew.fragment;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v4.app.Fragment;
import android.text.TextUtils;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebResourceRequest;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.LinearLayout;

import com.github.ybq.android.spinkit.SpinKitView;
import com.google.gson.Gson;
import com.just.agentweb.AgentWeb;
import com.just.agentweb.DefaultWebClient;
import com.lzy.okgo.OkGo;
import com.lzy.okgo.callback.StringCallback;
import com.lzy.okgo.model.Response;
import com.youjie.cfcpnew.BuildConfig;
import com.youjie.cfcpnew.R;
import com.youjie.cfcpnew.http.Constant;
import com.youjie.cfcpnew.model.UrlBean;
import com.youjie.cfcpnew.utils.AppToast;
import com.youjie.cfcpnew.utils.FloatBall;
import com.youjie.cfcpnew.view.floatingball.FloatBallManager;
import com.youjie.cfcpnew.view.floatingball.menu.MenuItem;
import com.youjie.cfcpnew.view.floatingball.utils.BackGroudSeletor;

import butterknife.BindView;
import butterknife.ButterKnife;
import cn.jiguang.analytics.android.api.JAnalyticsInterface;

/**
 * Created by Colin on 2017/12/18.
 * 开奖视频页面
 */
public class WebFragmentVideo extends Fragment {

    @BindView(R.id.container)
    LinearLayout container;
    @BindView(R.id.load_view)
    SpinKitView loadView;

    private String webViewUrl;
    protected Activity mActivity;
    protected AgentWeb mAgentWeb;
    private WebView mWebView;
    private FloatBallManager mFloatballManagerVideo;

    public static WebFragmentVideo newInstance(String webViewUrl) {
        WebFragmentVideo fragment = new WebFragmentVideo();
        Bundle args = new Bundle();
        args.putString(Constant.WEBVIEW_URL, webViewUrl);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        mFloatballManagerVideo = FloatBall.initSinglePageFloatball(mActivity);
        addFloatMenuItem();
//        mFloatballManagerVideo.show();
        if (getArguments() != null) {
            this.webViewUrl = getArguments().getString(Constant.WEBVIEW_URL);
        }
    }

    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View mView = inflater.inflate(R.layout.fragment_web_view, container, false);
        ButterKnife.bind(this, mView);
        initView();
        return mView;
    }

    @SuppressLint("SetJavaScriptEnabled")
    private void initView() {
        mAgentWeb = AgentWeb.with(mActivity)//
                .setAgentWebParent(container, new LinearLayout.LayoutParams(-1, -1))//
                .useDefaultIndicator()//
                .setWebViewClient(mWebViewClient)
                .setOpenOtherPageWays(DefaultWebClient.OpenOtherPageWays.DISALLOW)
                .interceptUnkownUrl()
                .setMainFrameErrorView(R.layout.error_page, R.id.iv_error)
                .createAgentWeb()//
                .ready()
                .go(webViewUrl);

        mAgentWeb.getAgentWebSettings().getWebSettings().setJavaScriptEnabled(true);
        mWebView = mAgentWeb.getWebCreator().getWebView();

        mWebView.setOnKeyListener((view, keyCode, keyEvent) -> {
            if (keyEvent.getAction() == KeyEvent.ACTION_DOWN) {
                if (keyCode == KeyEvent.KEYCODE_BACK) {
                    if (!TextUtils.isEmpty(mWebView.getUrl())) {
                        return !mWebView.getUrl().equals(webViewUrl) && mAgentWeb.handleKeyEvent(keyCode, keyEvent);
                    }
                }
            }
            return false;
        });
    }

    private WebViewClient mWebViewClient = new WebViewClient() {
        @Override
        public boolean shouldOverrideUrlLoading(WebView view, WebResourceRequest request) {
            return super.shouldOverrideUrlLoading(view, request);
        }

        @Override
        public void onPageStarted(WebView view, String url, Bitmap favicon) {
            super.onPageStarted(view, url, favicon);
            if (loadView.getVisibility() != View.VISIBLE) {
                loadView.setVisibility(View.VISIBLE);
            }
        }

        @Override
        public void onPageFinished(WebView view, String url) {
            super.onPageFinished(view, url);
            loadView.setVisibility(View.GONE);
        }
    };

    private void addFloatMenuItem() {
        MenuItem personItem = new MenuItem(BackGroudSeletor.getdrawble("ic_switch", mActivity)) {
            @Override
            public void action() {
//                AppToast.showShortText(mActivity, "切换线路");
                mFloatballManagerVideo.closeMenu();
            }
        };
        MenuItem walletItem = new MenuItem(BackGroudSeletor.getdrawble("ic_clear", mActivity)) {
            @Override
            public void action() {
                mAgentWeb.clearWebCache();
                AppToast.showShortText(mActivity, R.string.cleanCache);
                mFloatballManagerVideo.closeMenu();
            }
        };
        MenuItem settingItem = new MenuItem(BackGroudSeletor.getdrawble("ic_back", mActivity)) {
            @Override
            public void action() {
                mWebView.goBack();
                AppToast.showShortText(mActivity, R.string.back);
                mFloatballManagerVideo.closeMenu();
            }
        };
        MenuItem RefreshItem = new MenuItem(BackGroudSeletor.getdrawble("ic_refresh", mActivity)) {
            @Override
            public void action() {
                mAgentWeb.getWebCreator().getWebView().reload();
                AppToast.showShortText(mActivity, R.string.refresh);
                mFloatballManagerVideo.closeMenu();
            }
        };
        mFloatballManagerVideo
                .addMenuItem(personItem)
                .addMenuItem(walletItem)
                .addMenuItem(settingItem)
                .addMenuItem(RefreshItem)
                .buildMenu();
    }

    @Override
    public void setUserVisibleHint(boolean isVisibleToUser) {
        super.setUserVisibleHint(isVisibleToUser);
        if (mFloatballManagerVideo != null) {
            if (isVisibleToUser) {
                mFloatballManagerVideo.show();
                JAnalyticsInterface.onPageStart(mActivity, this.getClass().getCanonicalName());
            } else {
                mFloatballManagerVideo.hide();
                JAnalyticsInterface.onPageEnd(mActivity, this.getClass().getCanonicalName());
            }
        }
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        this.mActivity = (Activity) context;
    }
}

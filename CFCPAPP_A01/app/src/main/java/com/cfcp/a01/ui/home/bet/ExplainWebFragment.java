package com.cfcp.a01.ui.home.bet;


import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.just.agentweb.AgentWeb;
import com.kongzue.dialog.v2.WaitDialog;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class ExplainWebFragment extends BaseFragment {

    private static final String ARG_PARAM = "param";
    @BindView(R.id.betTitleBack)
    TextView betTitleBack;
    @BindView(R.id.tv_title)
    TextView tvTitle;
    @BindView(R.id.container)
    LinearLayout container;

    private int lotteryId;
    private String url;

    public static ExplainWebFragment newInstance(int lottery_id) {
        ExplainWebFragment fragment = new ExplainWebFragment();
        Bundle args = new Bundle();
        args.putInt(ARG_PARAM, lottery_id);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            lotteryId = getArguments().getInt(ARG_PARAM);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_explain_web;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        switch (lotteryId) {
            case 1:
                url = "file:///android_asset/official_cqssc.html";
                break;
            case 13:
                url = "file:///android_asset/official_gwffc.html";
                break;
            case 16:
                url = "file:///android_asset/official_gw3fc.html";
                break;
            case 28:
                url = "file:///android_asset/official_gw5fc.html";
                break;
            case 10:
                url = "file:///android_asset/official_bjpk10.html";
                break;
            case 19:
                url = "file:///android_asset/official_gwpk10.html";
                break;
            case 49:
                url = "file:///android_asset/official_xyft.html";
                break;
            case 15:
                url = "file:///android_asset/official_jsk3.html";
                break;
            case 17:
            case 50:
            case 51:
                url = "file:///android_asset/official_gwk3.html";
                break;
            case 9:
                url = "file:///android_asset/official_gd115.html";
                break;
            case 14:
            case 44:
                url = "file:///android_asset/official_gw115.html";
                break;
            case 20:
                url = "file:///android_asset/official_gw3d.html";
                break;
            case 37:
                url = "file:///android_asset/official_bjkl8.html";
                break;
        }
        AgentWeb.with(this)
                .setAgentWebParent(container, new LinearLayout.LayoutParams(-1, -1))
                .useDefaultIndicator()
                .setWebViewClient(mWebViewClient)
                .createAgentWeb()
                .ready()
                .go(url);
    }

    private WebViewClient mWebViewClient = new WebViewClient() {

        @Override
        public void onPageStarted(WebView view, String url, Bitmap favicon) {
            super.onPageStarted(view, url, favicon);
            WaitDialog.show(_mActivity, "加载中...").setCanCancel(true);
        }

        @Override
        public void onPageFinished(WebView view, String url) {
            tvTitle.setText(view.getTitle());
            WaitDialog.dismiss();
        }
    };

    @OnClick(R.id.betTitleBack)
    public void onClick() {
        finish();
    }
}

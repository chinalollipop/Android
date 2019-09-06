package com.hfcp.hf.ui.me;

import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewParent;
import android.widget.FrameLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hfcp.hf.CFConstant;
import com.hfcp.hf.Injections;
import com.hfcp.hf.R;
import com.hfcp.hf.common.base.BaseFragment;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.http.Client;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.common.utils.CPIWebSetting;
import com.hfcp.hf.common.utils.Check;
import com.hfcp.hf.common.utils.GameLog;
import com.hfcp.hf.common.widget.NTitleBar;
import com.hfcp.hf.data.AllGamesResult;
import com.hfcp.hf.data.PersonReportResult;
import com.hfcp.hf.ui.me.report.PersonContract;
import com.coolindicator.sdk.CoolIndicator;
import com.tencent.smtt.export.external.interfaces.IX5WebChromeClient;
import com.tencent.smtt.export.external.interfaces.SslError;
import com.tencent.smtt.export.external.interfaces.SslErrorHandler;
import com.tencent.smtt.sdk.ValueCallback;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;

public class CaiInfoFragment extends BaseFragment implements PersonContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    Unbinder unbinder;
    private String typeArgs2, typeArgs3;
    PersonContract.Presenter presenter;
    @BindView(R.id.flayout_xpay)
    FrameLayout flayoutXpay;

    @BindView(R.id.wv_service_online)
    WebView wvServiceOnlineContent;
    @BindView(R.id.caiInfoLotteryId)
    TextView caiInfoLotteryId;
    @BindView(R.id.indicator)
    CoolIndicator mCoolIndicator;
    @BindView(R.id.eventListBack)
    NTitleBar eventListBack;
    private ValueCallback<Uri> uploadFile;
    private ValueCallback<Uri[]> uploadFiles;
    OptionsPickerView typeOptionsPicker;
    String lotteryId="1";
    //信用盘的列表
    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLottery = new ArrayList<>();

    public static CaiInfoFragment newInstance(String deposit_mode, String money) {
        CaiInfoFragment betFragment = new CaiInfoFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cai_info;
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        eventListBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        mCoolIndicator.setMax(100);
        CPIWebSetting.init(wvServiceOnlineContent);
        AvailableLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG), AllGamesResult.DataBean.LotteriesBean.class);
        caiInfoLotteryId.setText(AvailableLottery.get(0).getName());
        lotteryId = AvailableLottery.get(0).getLottery_id() + "";
        typeOptionsPicker = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = AvailableLottery.get(options1).getName();
                caiInfoLotteryId.setText(text);
                lotteryId = AvailableLottery.get(options1).getLottery_id() + "";
                onLoadView ();
            }
        }).build();
        typeOptionsPicker.setPicker(AvailableLottery);
        webviewsetting(wvServiceOnlineContent);
       /* String webUrl = Client.baseUrl().replace("api.", "") + "prize-sets/game-prize-set?tip=app";
        if (Check.isEmpty(webUrl)) {
            webUrl = "http://dh5588.com/prize-sets/game-prize-set?tip=app";
        }*/

        //wvServiceOnlineContent.clearCache(true);
        onLoadView ();

    }

    private void onLoadView (){
        String webUrl = Client.baseUrl()+"service?packet=Game&action=GetUserPrizeSet&lottery_id="+lotteryId+"&token="+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        GameLog.log("加载了彩种信息地址是 " + webUrl);
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
                if (null != customViewCallback) {//!Check.isNull(customViewCallback)
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
                CaiInfoFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android < 3.0
            public void openFileChooser(ValueCallback<Uri> uploadMsgs) {
                GameLog.log("openFileChooser 2");
                CaiInfoFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  > 4.1.1
            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture) {
                GameLog.log("openFileChooser 3");
                CaiInfoFragment.this.uploadFile = uploadFile;
                openFileChooseProcess();
            }

            // For Android  >= 5.0
            public boolean onShowFileChooser(WebView webView,
                                             ValueCallback<Uri[]> filePathCallback,
                                             FileChooserParams fileChooserParams) {
                GameLog.log("openFileChooser 4:" + filePathCallback.toString());
                CaiInfoFragment.this.uploadFiles = filePathCallback;
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
        try {
            if (!Check.isNull(wvServiceOnlineContent)) {
                ViewParent parent = wvServiceOnlineContent.getParent();
                if (!Check.isNull(parent)) {
                    ((ViewGroup) parent).removeAllViews();
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
        } catch (Exception value) {
            GameLog.log("PayGanmeActivity异常:" + value);
        }

    }


    @Override
    public void getPersonReportResult(PersonReportResult personReportResult) {
    }


    @Override
    public void setPresenter(PersonContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
    }


    @OnClick(R.id.caiInfoLotteryId)
    public void onViewClicked() {
        typeOptionsPicker.show();
    }
}

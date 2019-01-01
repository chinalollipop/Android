package com.youjie.cfcpnew.activity;

import android.annotation.SuppressLint;
import android.content.BroadcastReceiver;
import android.content.IntentFilter;
import android.graphics.Color;
import android.net.ConnectivityManager;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.allenliu.versionchecklib.core.http.HttpParams;
import com.allenliu.versionchecklib.core.http.HttpRequestMethod;
import com.allenliu.versionchecklib.v2.AllenVersionChecker;
import com.allenliu.versionchecklib.v2.builder.DownloadBuilder;
import com.allenliu.versionchecklib.v2.builder.UIData;
import com.allenliu.versionchecklib.v2.callback.RequestVersionListener;
import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.google.gson.Gson;
import com.youjie.cfcpnew.BuildConfig;
import com.youjie.cfcpnew.R;
import com.youjie.cfcpnew.fragment.WebFragmentChart;
import com.youjie.cfcpnew.fragment.WebFragmentKJ;
import com.youjie.cfcpnew.fragment.WebFragmentOnline;
import com.youjie.cfcpnew.fragment.WebFragmentVideo;
import com.youjie.cfcpnew.http.Constant;
import com.youjie.cfcpnew.listener.KeyBoardShowListener;
import com.youjie.cfcpnew.model.UpdateModel;
import com.youjie.cfcpnew.model.UrlBean;
import com.youjie.cfcpnew.receiver.NetBroadCastReceiver;
import com.youjie.cfcpnew.rxbus.EventMsg;
import com.youjie.cfcpnew.rxbus.RxBus;
import com.youjie.cfcpnew.utils.ACache;
import com.youjie.cfcpnew.utils.AppToast;
import com.youjie.cfcpnew.utils.DensityUtil;
import com.youjie.cfcpnew.utils.DeviceUtils;
import com.youjie.cfcpnew.view.springdialog.SpringDiaLog;

import java.util.ArrayList;
import java.util.List;
import java.util.Objects;
import java.util.Timer;
import java.util.TimerTask;

import butterknife.BindView;
import butterknife.ButterKnife;
import cn.jiguang.analytics.android.api.JAnalyticsInterface;
import me.majiajie.pagerbottomtabstrip.NavigationController;
import me.majiajie.pagerbottomtabstrip.PageNavigationView;

/**
 * Created by Colin on 2017/12/18.
 * 主页
 */
public class MainActivity extends AppCompatActivity {

    @BindView(R.id.main_vp)
    ViewPager mainVp;
    @BindView(R.id.main_tab)
    PageNavigationView mainTab;
    private String URL;
    private UrlBean urlBean;
    NavigationController mNavigationController;
    protected MyFragmentPagerAdapter mMyFragmentPagerAdapter;
    private SpringDiaLog springDiaLog;
    private ImageView imageView;
    List<Fragment> fragments = new ArrayList<>();

    private DownloadBuilder builder;
    private UpdateModel updateModel;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        ButterKnife.bind(this);
        JAnalyticsInterface.onPageStart(this, this.getClass().getCanonicalName());
        URL = Objects.requireNonNull(this.getIntent().getExtras()).getString(Constant.WEBVIEW_URL);
        initView();
        initData();//加载数据
    }

    private void initView() {
        @SuppressLint("InflateParams") View view = LayoutInflater.from(this).inflate(R.layout.layout_ads, null);
        springDiaLog = new SpringDiaLog(this, view);
        imageView = view.findViewById(R.id.iv_ads);
        //软键盘
        new KeyBoardShowListener().setKeyboardListener(visible -> {
            if (visible) {
                //软键盘已弹出
                mainTab.setVisibility(View.INVISIBLE);
                RelativeLayout.LayoutParams layoutParams = new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);//定义一个LayoutParams
                layoutParams.setMargins(0, 0, 0, 0);//4个参数按顺序分别是左上右下
                mainVp.setLayoutParams(layoutParams); //mView是控件
            } else {
                //软键盘未弹出
                mainTab.setVisibility(View.VISIBLE);
                RelativeLayout.LayoutParams layoutParams = new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);//定义一个LayoutParams
                layoutParams.setMargins(0, 0, 0, DensityUtil.dp2px(MainActivity.this, 55));//4个参数按顺序分别是左上右下
                mainVp.setLayoutParams(layoutParams); //mView是控件
            }
        }, this);
        mNavigationController = mainTab.material()
                .addItem(R.drawable.tab1_normal, "聊天室", Color.RED)
                .addItem(R.drawable.tab2_normal, "在线投注", Color.RED)
                .addItem(R.drawable.tab3_normal, "开奖视频", Color.RED)
                .addItem(R.drawable.tab4_normal, "计划软件", Color.RED)
                .build();
    }

    private void initData() {
        WebFragmentChart webFragmentChart;
        WebFragmentOnline webFragmentOnline;
        WebFragmentVideo webFragmentVideo;
        WebFragmentKJ webFragmentKJ;
        if (!TextUtils.isEmpty(URL)) {
            urlBean = new Gson().fromJson(URL, UrlBean.class);
            webFragmentChart = WebFragmentChart.newInstance(urlBean.chartRoom);
            webFragmentOnline = WebFragmentOnline.newInstance(urlBean.onlineBetting);
            webFragmentVideo = WebFragmentVideo.newInstance(urlBean.lotteryVideo);
            webFragmentKJ = WebFragmentKJ.newInstance(urlBean.lotteryHint);
        } else {
            webFragmentChart = WebFragmentChart.newInstance("https://chartRoom");
            webFragmentOnline = WebFragmentOnline.newInstance("https://onlineBetting");
            webFragmentVideo = WebFragmentVideo.newInstance("https://lotteryVideo");
            webFragmentKJ = WebFragmentKJ.newInstance("https://lotteryHint");
        }
        fragments.add(webFragmentChart);
        fragments.add(webFragmentOnline);
        fragments.add(webFragmentVideo);
        fragments.add(webFragmentKJ);
        mMyFragmentPagerAdapter = new MyFragmentPagerAdapter(getSupportFragmentManager(), fragments);
        mainVp.setAdapter(mMyFragmentPagerAdapter);
        mainVp.setOffscreenPageLimit(1);
        if (urlBean != null && urlBean.currentItem != null) {
            mainVp.setCurrentItem(Integer.valueOf(urlBean.currentItem));
        }
        mNavigationController.setupWithViewPager(mainVp);
        //检查更新
        sendRequest();
        //广告弹窗
        if (urlBean != null && !TextUtils.isEmpty(urlBean.popimg_path)) {
            showDialog(urlBean.popimg_path);
        }
    }

    @SuppressLint("CheckResult")
    @Override
    protected void onResume() {
        super.onResume();
        //网络监听
        setBroadcast();

        RxBus.getInstance().toObservable().map(o -> (EventMsg) o).subscribe(eventMsg -> {
            if (eventMsg != null) {
                if (eventMsg.getMsg().equals("显示弹窗广告")) {
                    if (urlBean != null && !TextUtils.isEmpty(urlBean.popimg_path)) {
                        showDialog(urlBean.popimg_path);
                    }
                }
            }
        });
    }

    private class MyFragmentPagerAdapter extends FragmentPagerAdapter {

        private List<Fragment> mFragments;

        MyFragmentPagerAdapter(FragmentManager fm, List<Fragment> fragments) {
            super(fm);
            mFragments = fragments;
        }

        @Override
        public Fragment getItem(int position) {
            return mFragments.get(position);
        }

        @Override
        public void destroyItem(@NonNull ViewGroup container, int position, @NonNull Object object) {
        }

        @Override
        public int getCount() {
            return mFragments.size();
        }
    }

    /**
     * APP更新
     */
    private void sendRequest() {
        HttpParams httpParams = new HttpParams();
        httpParams.put(Constant.VERSION_UPDATE, Constant.VERSION_UPDATE);
        builder = AllenVersionChecker
                .getInstance()
                .requestVersion()
                .setRequestMethod(HttpRequestMethod.POST)
                .setRequestParams(httpParams)
                .setRequestUrl(ACache.get(getApplicationContext()).getAsString(Constant.APP_URL) + BuildConfig.FLAVOR)
                .request(new RequestVersionListener() {
                    @Nullable
                    @Override
                    public UIData onRequestVersionSuccess(String result) {
                        updateModel = new Gson().fromJson(result, UpdateModel.class);
                        if (Integer.valueOf(updateModel.version_code) > DeviceUtils.getVersionCode(MainActivity.this)) {
                            UIData uiData = UIData.create();
                            uiData.setTitle(updateModel.title);
                            uiData.setDownloadUrl(updateModel.apk_url);
                            uiData.setContent(updateModel.upgrade_point.replace("\\n", "\n"));
                            if (updateModel.is_force.equals("1")) {
                                builder.setForceUpdateListener(MainActivity.this::forceUpdate);
                                AppToast.showLongText(MainActivity.this, R.string.forceUpdate);
                            }
                            return uiData;
                        } else {
                            return null;
                        }
                    }

                    @Override
                    public void onRequestVersionFailure(String message) {

                    }
                });
        builder.excuteMission(this);
    }

    /**
     * 说明 显示广告弹框
     * 创建时间 2018/7/31
     */
    @SuppressLint("CheckResult")
    private void showDialog(String popimg_path) {
        RequestOptions requestOptions = new RequestOptions();
        requestOptions.centerCrop();
        Glide.with(this).load(popimg_path).apply(requestOptions).into(imageView);
        if (!springDiaLog.getIsClose()) {
            springDiaLog.setShowCloseButton(true)//是否显示关闭按钮
                    .setCanceledOnTouchOutside(false)//触碰外围是否可关闭弹窗
                    .setContentViewWidth(280)//设置内容视图宽度
                    .setContentViewHeight(400)//设置内容视图高度
                    .setStartAnimAngle(6)//设置进场角度,0是3点钟方向从右往左，然后逆时针类推
                    .setUseAnimation(true)//是否使用进场动画
                    .show();
        }
    }

    /**
     * 设置网络监听
     */
    private void setBroadcast() {
        BroadcastReceiver receiver = new NetBroadCastReceiver();
        IntentFilter filter = new IntentFilter();
        filter.addAction(WifiManager.WIFI_STATE_CHANGED_ACTION);
        filter.addAction(WifiManager.NETWORK_STATE_CHANGED_ACTION);
        filter.addAction(ConnectivityManager.CONNECTIVITY_ACTION);
        registerReceiver(receiver, filter);
    }

    /**
     * 强制更新操作
     */
    private void forceUpdate() {
        this.finish();
    }

    /**
     * 再按一次退出程序
     */
    private static Boolean isExit = false;

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
        if (keyCode == KeyEvent.KEYCODE_BACK) {
            if (!isExit) {
                isExit = true;
                AppToast.showShortText(this, R.string.exitTips);
                new Timer().schedule(new TimerTask() {
                    @Override
                    public void run() {
                        isExit = false;
                    }
                }, 2000);
            } else {
                this.finish();
            }
        }
        return false;
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        mNavigationController = null;
        builder = null;
        mMyFragmentPagerAdapter = null;
        updateModel = null;
        JAnalyticsInterface.onPageEnd(this, this.getClass().getCanonicalName());
    }
}

package com.cfcp.a01.ui.lottery;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.TextUtils;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.TimeHelper;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.LotteryListResult;
import com.cfcp.a01.ui.lottery.trendview.CQTrendChart;
import com.cfcp.a01.ui.lottery.trendview.IIX5TrendChart;
import com.cfcp.a01.ui.lottery.trendview.K3TrendChart;
import com.cfcp.a01.ui.lottery.trendview.PK10TrendChart;
import com.cfcp.a01.ui.lottery.trendview.LotteryTrendView;
import com.cfcp.a01.ui.lottery.trendview.TrendData;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.xmlpull.v1.XmlPullParser;
import org.xmlpull.v1.XmlPullParserException;
import org.xmlpull.v1.XmlPullParserFactory;

import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;

//开奖结果
public class LotteryResultFragment extends BaseFragment implements LotteryResultContract.View{

    @BindView(R.id.lotteryResultType)
    TextView lotteryResultType;
    @BindView(R.id.lotteryResultTab)
    TabLayout lotteryResultTab;
    @BindView(R.id.lotteryResultRView)
    RecyclerView lotteryResultRView;
    @BindView(R.id.lotteryResultPK10TrendView)
    LotteryTrendView mPK10TrendView;
    @BindView(R.id.lotteryResultCQTrendView)
    LotteryTrendView mCQTrendView;
    @BindView(R.id.lotteryResult11X5TrendView)
    LotteryTrendView m11X5TrendView;
    @BindView(R.id.lotteryResultK3TrendView)
    LotteryTrendView mK3TrendView;
    //PK10走势图的初始化
    private PK10TrendChart pk10TrendChart;
    //IIX5走势图的初始化
    private IIX5TrendChart iix5TrendChart;

    //K3走势图的初始化
    private K3TrendChart k3TrendChart;
    //时时彩走势图的初始化
    private CQTrendChart cqTrendChart;
    ArrayList mTrendList = new ArrayList();

    ArrayList mTrendListPK1 = new ArrayList();

    ArrayList mTrendListPK2 = new ArrayList();

    ArrayList mTrendListPK3 = new ArrayList();

    ArrayList mTrendListPK4 = new ArrayList();

    ArrayList mTrendListPK5 = new ArrayList();

    ArrayList mTrendListPK6 = new ArrayList();

    ArrayList mTrendListPK7 = new ArrayList();

    ArrayList mTrendListPK8 = new ArrayList();

    ArrayList mTrendListPK9 = new ArrayList();

    ArrayList mTrendListPK10 = new ArrayList();

    //type类型选择器
    OptionsPickerView typeOptionsPicker;
    //代表彩种ID
    private String  lotteryId = "1";
    //信用盘的列表
    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLottery  = new ArrayList<>();
    LotteryResultContract.Presenter presenter;
    /*static List<String> typeOptionsList  = new ArrayList<>();
    static List<String> typeOptionsLotreryIdList  = new ArrayList<>();
    static {
        typeOptionsList.add("幸运飞艇");
        typeOptionsList.add("重庆时时彩");
        typeOptionsList.add("广东11选5");
        typeOptionsList.add("北京PK10");
        typeOptionsList.add("官网分分彩");
        typeOptionsList.add("官网11选5");
        typeOptionsList.add("江苏快三");
        typeOptionsList.add("官网三分彩");
        typeOptionsList.add("官网快三分分彩");
        typeOptionsList.add("官网极速PK10");
        typeOptionsList.add("官网极速3D");
        typeOptionsList.add("官网五分彩");
        typeOptionsList.add("安徽快三");
        typeOptionsList.add("北京快乐8");
        typeOptionsList.add("11选5三分彩");

        typeOptionsLotreryIdList.add("50");
        typeOptionsLotreryIdList.add("1");
        typeOptionsLotreryIdList.add("9");
        typeOptionsLotreryIdList.add("10");
        typeOptionsLotreryIdList.add("13");
        typeOptionsLotreryIdList.add("14");
        typeOptionsLotreryIdList.add("15");
        typeOptionsLotreryIdList.add("16");
        typeOptionsLotreryIdList.add("17");
        typeOptionsLotreryIdList.add("19");
        typeOptionsLotreryIdList.add("20");
        typeOptionsLotreryIdList.add("28");
        typeOptionsLotreryIdList.add("30");
        typeOptionsLotreryIdList.add("37");
        typeOptionsLotreryIdList.add("44");

    }*/

    public static LotteryResultFragment newInstance() {
        LotteryResultFragment lotteryResultFragment = new LotteryResultFragment();
        Injections.inject(lotteryResultFragment, null);
        return lotteryResultFragment;
    }

    private void initPK10(){
        if(lotteryResultTab.getTabCount()>0){
            lotteryResultTab.removeAllTabs();
            lotteryResultTab.clearOnTabSelectedListeners();
        }
        lotteryResultRView.setVisibility(View.VISIBLE);
        mCQTrendView.setVisibility(View.GONE);
        m11X5TrendView.setVisibility(View.GONE);
        mK3TrendView.setVisibility(View.GONE);
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("开奖"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("冠军走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("亚军走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("季军走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第四走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第五走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第六走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第七走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第八走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第九走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("第十走势"));
        lotteryResultTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()){
                    case 0:
                        lotteryResultRView.setVisibility(View.VISIBLE);
                        break;
                    case 1:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK1);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 2:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK2);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 3:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK3);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 4:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK4);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 5:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK5);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 6:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK6);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 7:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK7);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 8:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK8);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 9:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK9);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 10:
                        LotteryResultFragment.this.pk10TrendChart.updateData("01", mTrendListPK10);
                        lotteryResultRView.setVisibility(View.GONE);
                        mPK10TrendView.setVisibility(View.VISIBLE);
                        break;
                }

            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {

            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });
    }


    private void init11X5(){
        if(lotteryResultTab.getTabCount()>0){
            lotteryResultTab.removeAllTabs();
            lotteryResultTab.clearOnTabSelectedListeners();
        }
        lotteryResultRView.setVisibility(View.VISIBLE);
        mCQTrendView.setVisibility(View.GONE);
        mPK10TrendView.setVisibility(View.GONE);
        mK3TrendView.setVisibility(View.GONE);
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("开奖"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("万位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("千位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("百位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("十位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("个位走势"));
        lotteryResultTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()){
                    case 0:
                        lotteryResultRView.setVisibility(View.VISIBLE);
                        break;
                    case 1:
                        LotteryResultFragment.this.iix5TrendChart.updateData("04", mTrendListPK1);
                        lotteryResultRView.setVisibility(View.GONE);
                        m11X5TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 2:
                        LotteryResultFragment.this.iix5TrendChart.updateData("04", mTrendListPK2);
                        lotteryResultRView.setVisibility(View.GONE);
                        m11X5TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 3:
                        LotteryResultFragment.this.iix5TrendChart.updateData("04", mTrendListPK3);
                        lotteryResultRView.setVisibility(View.GONE);
                        m11X5TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 4:
                        LotteryResultFragment.this.iix5TrendChart.updateData("04", mTrendListPK4);
                        lotteryResultRView.setVisibility(View.GONE);
                        m11X5TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 5:
                        LotteryResultFragment.this.iix5TrendChart.updateData("04", mTrendListPK5);
                        lotteryResultRView.setVisibility(View.GONE);
                        m11X5TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 6:
                        LotteryResultFragment.this.iix5TrendChart.updateData("04", mTrendListPK6);
                        lotteryResultRView.setVisibility(View.GONE);
                        m11X5TrendView.setVisibility(View.VISIBLE);
                        break;
                }

            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {

            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });
    }


    private void initCQSSC(){
        if(lotteryResultTab.getTabCount()>0){
            lotteryResultTab.removeAllTabs();
            lotteryResultTab.clearOnTabSelectedListeners();
        }
        mPK10TrendView.setVisibility(View.GONE);
        m11X5TrendView.setVisibility(View.GONE);
        mK3TrendView.setVisibility(View.GONE);
        lotteryResultRView.setVisibility(View.VISIBLE);
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("开奖"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("万位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("千位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("百位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("十位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("个位走势"));
        lotteryResultTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()){
                    case 0:
                        lotteryResultRView.setVisibility(View.VISIBLE);
                        break;
                    case 1:
                        LotteryResultFragment.this.cqTrendChart.updateData("02", mTrendListPK1);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                    case 2:
                        LotteryResultFragment.this.cqTrendChart.updateData("02", mTrendListPK2);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                    case 3:
                        LotteryResultFragment.this.cqTrendChart.updateData("02", mTrendListPK3);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                    case 4:
                        LotteryResultFragment.this.cqTrendChart.updateData("02", mTrendListPK4);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                    case 5:
                        LotteryResultFragment.this.cqTrendChart.updateData("02", mTrendListPK5);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                }

            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {

            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });
    }

    private void initJSK3(){
        if(lotteryResultTab.getTabCount()>0){
            lotteryResultTab.removeAllTabs();
            lotteryResultTab.clearOnTabSelectedListeners();
        }
        mPK10TrendView.setVisibility(View.GONE);
        m11X5TrendView.setVisibility(View.GONE);
        mCQTrendView.setVisibility(View.GONE);
        mK3TrendView.setVisibility(View.VISIBLE);
        lotteryResultRView.setVisibility(View.VISIBLE);
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("开奖"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("百位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("十位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("个位走势"));
        lotteryResultTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()){
                    case 0:
                        lotteryResultRView.setVisibility(View.VISIBLE);
                        mK3TrendView.setVisibility(View.GONE);
                        break;
                    case 1:
                        LotteryResultFragment.this.k3TrendChart.updateData("03", mTrendListPK1);
                        lotteryResultRView.setVisibility(View.GONE);
                        mK3TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 2:
                        LotteryResultFragment.this.k3TrendChart.updateData("03", mTrendListPK2);
                        lotteryResultRView.setVisibility(View.GONE);
                        mK3TrendView.setVisibility(View.VISIBLE);
                        break;
                    case 3:
                        LotteryResultFragment.this.k3TrendChart.updateData("03", mTrendListPK3);
                        lotteryResultRView.setVisibility(View.GONE);
                        mK3TrendView.setVisibility(View.VISIBLE);
                        break;
                }

            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {

            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });
    }

    private void init3D(){
        if(lotteryResultTab.getTabCount()>0){
            lotteryResultTab.removeAllTabs();
            lotteryResultTab.clearOnTabSelectedListeners();
        }
        mPK10TrendView.setVisibility(View.GONE);
        m11X5TrendView.setVisibility(View.GONE);
        mCQTrendView.setVisibility(View.GONE);
        mK3TrendView.setVisibility(View.VISIBLE);
        lotteryResultRView.setVisibility(View.VISIBLE);
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("开奖"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("百位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("十位走势"));
        lotteryResultTab.addTab(lotteryResultTab.newTab().setText("个位走势"));
        lotteryResultTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()){
                    case 0:
                        lotteryResultRView.setVisibility(View.VISIBLE);
                        mCQTrendView.setVisibility(View.GONE);
                        break;
                    case 1:
                        LotteryResultFragment.this.cqTrendChart.updateData("03", mTrendListPK1);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                    case 2:
                        LotteryResultFragment.this.cqTrendChart.updateData("03", mTrendListPK2);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                    case 3:
                        LotteryResultFragment.this.cqTrendChart.updateData("03", mTrendListPK3);
                        lotteryResultRView.setVisibility(View.GONE);
                        mCQTrendView.setVisibility(View.VISIBLE);
                        break;
                }

            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {

            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {

            }
        });
    }

    private void initZTTablayout(String lottery_id){
        if(Check.isNull(presenter)){
            presenter = Injections.inject(this, null);
        }
        presenter.getLotteryList("",lottery_id,"");
        lotteryResultTab.setVisibility(View.VISIBLE);
        switch (lottery_id){
            case "48"://幸运飞艇
            case "49"://幸运飞艇
            case "10"://北京PK拾
            case "52"://北京PK拾 5
            case "19"://Gwpk10
                initPK10();
                break;
            case "16"://Gw3fc
            case "28"://Gw5fc
            case "13"://Gwffc
            case "1"://欢乐生肖
            case "53"://重庆时时彩
                initCQSSC();
                break;
            case "9"://广东11选5
            case "14"://GW115
            case "44"://11选5三分彩
                init11X5();
                break;
            case "15"://江苏快三
            case "17"://Gwk3ffc
            case "30"://安徽快三
            case "50"://极速快三五分彩
            case "51"://
                initJSK3();
                break;
            case "20"://Gw3d
                init3D();
                break;
            case "37"://北京快乐8
                lotteryResultRView.setVisibility(View.VISIBLE);
                lotteryResultTab.setVisibility(View.GONE);
                mPK10TrendView.setVisibility(View.GONE);
                mCQTrendView.setVisibility(View.GONE);
                m11X5TrendView.setVisibility(View.GONE);
                mK3TrendView.setVisibility(View.GONE);
                break;

        }

        //lotteryResultTab.getChildAt(0).setSelected(true);



    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_lottery_result;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        //EventBus.getDefault().register(this);
        AvailableLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG), AllGamesResult.DataBean.LotteriesBean.class);
        if(!Check.isNull(AvailableLottery)) {
            lotteryResultType.setText(AvailableLottery.get(0).getName());
            initZTTablayout(AvailableLottery.get(0).getLottery_id() + "");
        }
        initPK10TrendView();
        initCQTrendView();
        init11X5TrendView();
        initK3TrendView();

        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        lotteryResultRView.setLayoutManager(linearLayoutManager);
        lotteryResultRView.setHasFixedSize(true);
        lotteryResultRView.setNestedScrollingEnabled(false);

        typeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text = AvailableLottery.get(options1).getName();
                lotteryResultType.setText(text);
                lotteryId = AvailableLottery.get(options1).getLottery_id()+"";
                initZTTablayout(lotteryId);
            }
        }).build();
        try {
            if (Check.isNull(AvailableLottery) || AvailableLottery.size() == 0) {
                AvailableLottery = JSON.parseArray(ACache.get(getContext()).getAsString(CFConstant.USERNAME_HOME_GUANWANG), AllGamesResult.DataBean.LotteriesBean.class);
            }
            typeOptionsPicker.setPicker(AvailableLottery);
        }catch (Exception e){
            e.printStackTrace();
        }
    }

    //初始化走势图视图
    private void initPK10TrendView() {
        this.pk10TrendChart = new PK10TrendChart(getContext(), this.mPK10TrendView);
        this.mPK10TrendView.setChart(this.pk10TrendChart);
        this.pk10TrendChart.setShowYilou(true);
        this.pk10TrendChart.setDrawLine(true);
    }

    //初始化走势图视图
    private void initCQTrendView() {
        this.cqTrendChart = new CQTrendChart(getContext(), this.mCQTrendView);
        this.mCQTrendView.setChart(this.cqTrendChart);
        this.cqTrendChart.setShowYilou(true);
        this.cqTrendChart.setDrawLine(true);
    }

    //初始化走势图视图
    private void init11X5TrendView() {
        iix5TrendChart = new IIX5TrendChart(getContext(), this.m11X5TrendView);
        this.m11X5TrendView.setChart(this.iix5TrendChart);
        this.iix5TrendChart.setShowYilou(true);
        this.iix5TrendChart.setDrawLine(true);
    }

    //初始化走势图视图
    private void initK3TrendView() {
        k3TrendChart = new K3TrendChart(getContext(), this.mK3TrendView);
        this.mK3TrendView.setChart(this.k3TrendChart);
        this.k3TrendChart.setShowYilou(true);
        this.k3TrendChart.setDrawLine(true);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        //EventBus.getDefault().unregister(this);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        //showMessage("开奖结果界面");
        //EventBus.getDefault().post(new MainEvent(0));
    }

    @OnClick(R.id.lotteryResultType)
    public void onViewClicked() {
        typeOptionsPicker.show();
    }

    @Override
    public void getLotteryListResult(List<LotteryListResult> lotteryListResult) {
        GameLog.log("获取开奖结果数据。。。");
        //展示开奖结果数据，以及计算遗漏值，平均值 等等，
        LotteryResultAdapter lotteryResultAdapter =   new LotteryResultAdapter(R.layout.item_lottery_result,lotteryListResult);
        if(lotteryListResult.size()==0){
            showMessage("暂无数据！");
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            lotteryResultAdapter.setEmptyView(view);
        }
        lotteryResultRView.setAdapter(lotteryResultAdapter);
        Collections.reverse(lotteryListResult);
        //走势图的计算方案
        onParserLotteryListResult(lotteryListResult);
        Collections.reverse(lotteryListResult);
    }


    private List<TrendData> onShowK3(List<LotteryListResult> lotteryListResult,int index){
        List<TrendData> mTrendListData = new ArrayList<TrendData>();
        List<String> mTrendListData2 = new ArrayList<String>();
        TrendData trendDataMmv = new TrendData();
        trendDataMmv.setType("mmv");

        TrendData trendDataMlv = new TrendData();
        trendDataMlv.setType("mlv");

        TrendData trendDataAvg = new TrendData();
        trendDataAvg.setType("avg");
        int size = lotteryListResult.size();
        //用于计算遗漏值
        int i1=0,i2=0,i3=0,i4=0,i5=0,i6=0;
        //用于计算出现次数
        int maxShow1=0,maxShow2=0,maxShow3=0,maxShow4=0,maxShow5=0,maxShow6=0;

        for(int k=0;k<size;++k){
            //奖期
            TrendData trendData = new TrendData();
            //期号
            trendData.setPid(lotteryListResult.get(k).getIssue());
            //
            //GameLog.log("出现的值："+lotteryListResult.get(k).getWn_number());
            String [] dataList = lotteryListResult.get(k).getWn_number().split(",");
            mTrendListData2.add(dataList[index]);
            //10个数值
            switch (dataList[index]){
                case "1":
                    ++maxShow1;
                    i1 = 0;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    break;
                case "2":
                    ++maxShow2;
                    ++i1;
                    i2 = 0;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    break;
                case "3":
                    ++maxShow3;
                    ++i1;
                    ++i2;
                    i3 = 0;
                    ++i4;
                    ++i5;
                    ++i6;
                    break;
                case "4":
                    ++maxShow4;
                    ++i1;
                    ++i2;
                    ++i3;
                    i4 = 0;
                    ++i5;
                    ++i6;
                    break;
                case "5":
                    ++maxShow5;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    i5 = 0;
                    ++i6;
                    break;
                case "6":
                    ++maxShow6;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    i6 = 0;
                    break;

            }
            //GameLog.log("遗漏的值："+""+i1+","+i2+","+i3+","+i4+","+i5+","+i6);
            trendData.setBlue(""+i1+","+i2+","+i3+","+i4+","+i5+","+i6);
            trendData.setType("row");
            mTrendListData.add(trendData);
        }
        TrendData trendDataDis = new TrendData();
        trendDataDis.setType("dis");
        trendDataDis.setBlue(""+maxShow1+","+maxShow2+","+maxShow3+","+maxShow4+","+maxShow5+","+maxShow6);
        mTrendListData.add(trendDataDis);

        //---------------------计算方案第2个 ------start---------------
        for (int i = 1; i < 7; i++) {
            int count = 0;//遗漏计数
            int ylTimes = 0;//遗漏次数、遗漏多少次
            int totalYL = 0;//遗漏的总数
            int biggestYL = 0;//最大的遗漏
            int showTime = 0;//出现的次数
            int lianchu = 0;//连出
            int biggestLianchu = 0;//最大连出
            int lastYL = 0;
            for (int j = 0; j < mTrendListData2.size(); j++) {
                String v = mTrendListData2.get(j);
                int value = Integer.parseInt(v);
                if (value == i) {
                    ++showTime;
                    totalYL += count;
                    ++lianchu;
                    biggestLianchu = ((biggestLianchu > lianchu) ? (biggestLianchu) : (lianchu));
                    count = 0;
                } else {
                    ++count;
                    if (count == 1) {
                        ++ylTimes;
                    }
                    if (j == (mTrendListData2.size() - 1)) {
                        totalYL += count;
                    }
                    lianchu = 0;
                }
                biggestYL = ((biggestYL > count) ? (biggestYL) : (count));
                if (j == (mTrendListData2.size() - 1)) {
                    lastYL = count;
                }
            }

            int averageYL = (int) (((float) totalYL) / ylTimes + 0.5);//平均遗漏,四舍五入
            trendDataMmv.setBlue(trendDataMmv.getBlue()+biggestYL+",");
            trendDataMlv.setBlue(trendDataMlv.getBlue()+biggestLianchu+",");
            trendDataAvg.setBlue(trendDataAvg.getBlue()+averageYL+",");
        }

        mTrendListData.add(trendDataAvg);
        mTrendListData.add(trendDataMmv);
        mTrendListData.add(trendDataMlv);
        return mTrendListData;
    }

    private List<TrendData> onShowPK10(List<LotteryListResult> lotteryListResult,int index){
        List<TrendData> mTrendListData = new ArrayList<TrendData>();

        List<String> mTrendListData2 = new ArrayList<String>();
        TrendData trendDataMmv = new TrendData();
        trendDataMmv.setType("mmv");

        TrendData trendDataMlv = new TrendData();
        trendDataMlv.setType("mlv");

        TrendData trendDataAvg = new TrendData();
        trendDataAvg.setType("avg");

        int size = lotteryListResult.size();
        //用于计算遗漏值
        int i1=0,i2=0,i3=0,i4=0,i5=0,i6=0,i7=0,i8=0,i9=0,i10=0;
        //用于计算出现次数
        int maxShow1=0,maxShow2=0,maxShow3=0,maxShow4=0,maxShow5=0,maxShow6=0,maxShow7=0,maxShow8=0,maxShow9=0,maxShow10=0;
        for(int k=0;k<size;++k){
            //奖期
            TrendData trendData = new TrendData();
            //期号
            trendData.setPid(lotteryListResult.get(k).getIssue());
            //
            //GameLog.log("出现的值："+lotteryListResult.get(k).getWn_number());
            String [] dataList = lotteryListResult.get(k).getWn_number().split(",");
            mTrendListData2.add(dataList[index]);
            //10个数值
                switch (dataList[index]){
                    case "01":
                        ++maxShow1;
                        i1 = 0;
                        ++i2;
                        ++i3;
                        ++i4;
                        ++i5;
                        ++i6;
                        ++i7;
                        ++i8;
                        ++i9;
                        ++i10;
                        break;
                    case "02":
                        ++maxShow2;
                        ++i1;
                        i2 = 0;
                        ++i3;
                        ++i4;
                        ++i5;
                        ++i6;
                        ++i7;
                        ++i8;
                        ++i9;
                        ++i10;
                        break;
                    case "03":
                        ++maxShow3;
                        ++i1;
                        ++i2;
                        i3 = 0;
                        ++i4;
                        ++i5;
                        ++i6;
                        ++i7;
                        ++i8;
                        ++i9;
                        ++i10;
                        break;
                    case "04":
                        ++maxShow4;
                        ++i1;
                        ++i2;
                        ++i3;
                        i4 = 0;
                        ++i5;
                        ++i6;
                        ++i7;
                        ++i8;
                        ++i9;
                        ++i10;
                        break;
                    case "05":
                        ++maxShow5;
                        ++i1;
                        ++i2;
                        ++i3;
                        ++i4;
                        i5 = 0;
                        ++i6;
                        ++i7;
                        ++i8;
                        ++i9;
                        ++i10;
                        break;
                    case "06":
                        ++maxShow6;
                        ++i1;
                        ++i2;
                        ++i3;
                        ++i4;
                        ++i5;
                        i6 = 0;
                        ++i7;
                        ++i8;
                        ++i9;
                        ++i10;
                        break;
                    case "07":
                        ++maxShow7;
                        ++i1;
                        ++i2;
                        ++i3;
                        ++i4;
                        ++i5;
                        ++i6;
                        i7 = 0;
                        ++i8;
                        ++i9;
                        ++i10;
                        break;
                    case "08":
                        ++maxShow8;
                        ++i1;
                        ++i2;
                        ++i3;
                        ++i4;
                        ++i5;
                        ++i6;
                        ++i7;
                        i8 = 0;
                        ++i9;
                        ++i10;
                        break;
                    case "09":
                        ++maxShow9;
                        ++i1;
                        ++i2;
                        ++i3;
                        ++i4;
                        ++i5;
                        ++i6;
                        ++i7;
                        ++i8;
                        i9 = 0;
                        ++i10;
                        break;
                    case "10":
                        ++maxShow10;
                        ++i1;
                        ++i2;
                        ++i3;
                        ++i4;
                        ++i5;
                        ++i6;
                        ++i7;
                        ++i8;
                        ++i9;
                        i10 = 0;
                        break;
            }
            //GameLog.log("遗漏的值："+""+i1+","+i2+","+i3+","+i4+","+i5+","+i6+","+i7+","+i8+","+i9+","+i10);
            trendData.setBlue(""+i1+","+i2+","+i3+","+i4+","+i5+","+i6+","+i7+","+i8+","+i9+","+i10);
            trendData.setType("row");
            mTrendListData.add(trendData);
        }

        TrendData trendDataDis = new TrendData();
        trendDataDis.setType("dis");
        trendDataDis.setBlue(""+maxShow1+","+maxShow2+","+maxShow3+","+maxShow4+","+maxShow5+","+maxShow6+","+maxShow7+","+maxShow8+","+maxShow9+","+maxShow10);
        mTrendListData.add(trendDataDis);

        //---------------------计算方案第2个 ------start---------------
        for (int i = 1; i < 11; i++) {
            int count = 0;//遗漏计数
            int ylTimes = 0;//遗漏次数、遗漏多少次
            int totalYL = 0;//遗漏的总数
            int biggestYL = 0;//最大的遗漏
            int showTime = 0;//出现的次数
            int lianchu = 0;//连出
            int biggestLianchu = 0;//最大连出
            int lastYL = 0;
            for (int j = 0; j < mTrendListData2.size(); j++) {
                String v = mTrendListData2.get(j);
                int value = Integer.parseInt(v);
                if (value == i) {
                    ++showTime;
                    totalYL += count;
                    ++lianchu;
                    biggestLianchu = ((biggestLianchu > lianchu) ? (biggestLianchu) : (lianchu));
                    count = 0;
                } else {
                    ++count;
                    if (count == 1) {
                        ++ylTimes;
                    }
                    if (j == (mTrendListData2.size() - 1)) {
                        totalYL += count;
                    }
                    lianchu = 0;
                }
                biggestYL = ((biggestYL > count) ? (biggestYL) : (count));
                if (j == (mTrendListData2.size() - 1)) {
                    lastYL = count;
                }
            }

            int averageYL = (int) (((float) totalYL) / ylTimes + 0.5);//平均遗漏,四舍五入
            trendDataMmv.setBlue(trendDataMmv.getBlue()+biggestYL+",");
            trendDataMlv.setBlue(trendDataMlv.getBlue()+biggestLianchu+",");
            trendDataAvg.setBlue(trendDataAvg.getBlue()+averageYL+",");
        }

        mTrendListData.add(trendDataAvg);
        mTrendListData.add(trendDataMmv);
        mTrendListData.add(trendDataMlv);

        return mTrendListData;
    }

    private List<TrendData> onShow11X5(List<LotteryListResult> lotteryListResult,int index){
        List<TrendData> mTrendListData = new ArrayList<TrendData>();
        List<String> mTrendListData2 = new ArrayList<String>();
        TrendData trendDataMmv = new TrendData();
        trendDataMmv.setType("mmv");

        TrendData trendDataMlv = new TrendData();
        trendDataMlv.setType("mlv");

        TrendData trendDataAvg = new TrendData();
        trendDataAvg.setType("avg");
        int size = lotteryListResult.size();
        //用于计算遗漏值
        int i1=0,i2=0,i3=0,i4=0,i5=0,i6=0,i7=0,i8=0,i9=0,i10=0,i11=0;
        //用于计算出现次数
        int maxShow1=0,maxShow2=0,maxShow3=0,maxShow4=0,maxShow5=0,maxShow6=0,maxShow7=0,maxShow8=0,maxShow9=0,maxShow10=0,maxShow11=0;

        for(int k=0;k<size;++k){
            //奖期
            TrendData trendData = new TrendData();
            //期号
            trendData.setPid(lotteryListResult.get(k).getIssue());
            //
            //GameLog.log("出现的值："+lotteryListResult.get(k).getWn_number());
            String [] dataList = lotteryListResult.get(k).getWn_number().split(",");
            mTrendListData2.add(dataList[index]);
            //10个数值
            switch (dataList[index]){
                case "01":
                    ++maxShow1;
                    i1 = 0;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "02":
                    ++maxShow2;
                    ++i1;
                    i2 = 0;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "03":
                    ++maxShow3;
                    ++i1;
                    ++i2;
                    i3 = 0;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "04":
                    ++maxShow4;
                    ++i1;
                    ++i2;
                    ++i3;
                    i4 = 0;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "05":
                    ++maxShow5;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    i5 = 0;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "06":
                    ++maxShow6;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    i6 = 0;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "07":
                    ++maxShow7;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    i7 = 0;
                    ++i8;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "08":
                    ++maxShow8;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    i8 = 0;
                    ++i9;
                    ++i10;
                    ++i11;
                    break;
                case "09":
                    ++maxShow9;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    i9 = 0;
                    ++i10;
                    ++i11;
                    break;
                case "10":
                    ++maxShow10;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    i10 = 0;
                    ++i11;
                    break;
                case "11":
                    ++maxShow11;
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    i11 = 0;
                    break;
            }
            //GameLog.log("遗漏的值："+""+i1+","+i2+","+i3+","+i4+","+i5+","+i6+","+i7+","+i8+","+i9+","+i10+","+i10);
            trendData.setBlue(""+i1+","+i2+","+i3+","+i4+","+i5+","+i6+","+i7+","+i8+","+i9+","+i10+","+i11);
            trendData.setType("row");
            mTrendListData.add(trendData);
        }
        TrendData trendDataDis = new TrendData();
        trendDataDis.setType("dis");
        trendDataDis.setBlue(""+maxShow1+","+maxShow2+","+maxShow3+","+maxShow4+","+maxShow5+","+maxShow6+","+maxShow7+","+maxShow8+","+maxShow9+","+maxShow10+","+maxShow11);
        mTrendListData.add(trendDataDis);

        //---------------------计算方案第2个 ------start---------------
        for (int i = 1; i < 12; i++) {
            int count = 0;//遗漏计数
            int ylTimes = 0;//遗漏次数、遗漏多少次
            int totalYL = 0;//遗漏的总数
            int biggestYL = 0;//最大的遗漏
            int showTime = 0;//出现的次数
            int lianchu = 0;//连出
            int biggestLianchu = 0;//最大连出
            int lastYL = 0;
            for (int j = 0; j < mTrendListData2.size(); j++) {
                String v = mTrendListData2.get(j);
                int value = Integer.parseInt(v);
                if (value == i) {
                    ++showTime;
                    totalYL += count;
                    ++lianchu;
                    biggestLianchu = ((biggestLianchu > lianchu) ? (biggestLianchu) : (lianchu));
                    count = 0;
                } else {
                    ++count;
                    if (count == 1) {
                        ++ylTimes;
                    }
                    if (j == (mTrendListData2.size() - 1)) {
                        totalYL += count;
                    }
                    lianchu = 0;
                }
                biggestYL = ((biggestYL > count) ? (biggestYL) : (count));
                if (j == (mTrendListData2.size() - 1)) {
                    lastYL = count;
                }
            }

            int averageYL = (int) (((float) totalYL) / ylTimes + 0.5);//平均遗漏,四舍五入
            trendDataMmv.setBlue(trendDataMmv.getBlue()+biggestYL+",");
            trendDataMlv.setBlue(trendDataMlv.getBlue()+biggestLianchu+",");
            trendDataAvg.setBlue(trendDataAvg.getBlue()+averageYL+",");
        }

        mTrendListData.add(trendDataAvg);
        mTrendListData.add(trendDataMmv);
        mTrendListData.add(trendDataMlv);

        return mTrendListData;
    }

    private List<TrendData> onShowCQSSC(List<LotteryListResult> lotteryListResult,int index){
        List<TrendData> mTrendListData = new ArrayList<TrendData>();
        List<String> mTrendListData2 = new ArrayList<String>();
        TrendData trendDataMmv = new TrendData();
        trendDataMmv.setType("mmv");

        TrendData trendDataMlv = new TrendData();
        trendDataMlv.setType("mlv");

        TrendData trendDataAvg = new TrendData();
        trendDataAvg.setType("avg");


        int size = lotteryListResult.size();
        //用于计算遗漏值
        int i1=0,i2=0,i3=0,i4=0,i5=0,i6=0,i7=0,i8=0,i9=0,i10=0;
        //用于计算出现次数
        int maxShow1=0,maxShow2=0,maxShow3=0,maxShow4=0,maxShow5=0,maxShow6=0,maxShow7=0,maxShow8=0,maxShow9=0,maxShow10=0;
        //用于计算最大连出
        int maxEvenShow1=0,maxEvenShow2=0,maxEvenShow3=0,maxEvenShow4=0,maxEvenShow5=0,maxEvenShow6=0,maxEvenShow7=0,maxEvenShow8=0,maxEvenShow9=0,maxEvenShow10=0;
        for(int k=0;k<size;++k){
            //奖期
            TrendData trendData = new TrendData();
            //期号
            trendData.setPid(lotteryListResult.get(k).getIssue());
            //
            //GameLog.log("出现的值："+lotteryListResult.get(k).getWn_number());
            String [] dataList = lotteryListResult.get(k).getWn_number().split(",");
            mTrendListData2.add(dataList[index]);
            String dataW = "";


            //---------------------计算方案第2个 ------end---------------


            //10个数值
            switch (dataList[index]){
                case "0":
                    ++maxShow1;
                    if(i1>maxEvenShow1){
                        maxEvenShow1 = i1;
                    }
                    i1 = 0;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    break;
                case "1":
                    ++maxShow2;
                    if(i2>maxEvenShow2){
                        maxEvenShow2 = i2;
                    }
                    ++i1;
                    i2 = 0;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    break;
                case "2":
                    ++maxShow3;
                    if(i3>maxEvenShow3){
                        maxEvenShow3 = i3;
                    }
                    ++i1;
                    ++i2;
                    i3 = 0;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    break;
                case "3":
                    ++maxShow4;
                    if(i4>maxEvenShow4){
                        maxEvenShow4 = i4;
                    }
                    ++i1;
                    ++i2;
                    ++i3;
                    i4 = 0;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    break;
                case "4":
                    ++maxShow5;
                    if(i5>maxEvenShow5){
                        maxEvenShow5 = i5;
                    }
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    i5 = 0;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    break;
                case "5":
                    ++maxShow6;
                    if(i6>maxEvenShow6){
                        maxEvenShow6 = i6;
                    }
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    i6 = 0;
                    ++i7;
                    ++i8;
                    ++i9;
                    ++i10;
                    break;
                case "6":
                    ++maxShow7;
                    if(i7>maxEvenShow7){
                        maxEvenShow7 = i7;
                    }
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    i7 = 0;
                    ++i8;
                    ++i9;
                    ++i10;
                    break;
                case "7":
                    ++maxShow8;
                    if(i8>maxEvenShow8){
                        maxEvenShow8 = i8;
                    }
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    i8 = 0;
                    ++i9;
                    ++i10;
                    break;
                case "8":
                    ++maxShow9;
                    if(i9>maxEvenShow9){
                        maxEvenShow9 = i9;
                    }
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    i9 = 0;
                    ++i10;
                    break;
                case "9":
                    ++maxShow10;
                    if(i10>maxEvenShow10){
                        maxEvenShow10 = i10;
                    }
                    ++i1;
                    ++i2;
                    ++i3;
                    ++i4;
                    ++i5;
                    ++i6;
                    ++i7;
                    ++i8;
                    ++i9;
                    i10 = 0;
                    break;
            }
            //GameLog.log("遗漏的值："+""+i1+","+i2+","+i3+","+i4+","+i5+","+i6+","+i7+","+i8+","+i9+","+i10);
            trendData.setBlue(""+i1+","+i2+","+i3+","+i4+","+i5+","+i6+","+i7+","+i8+","+i9+","+i10);
            trendData.setType("row");
            mTrendListData.add(trendData);
        }
        TrendData trendDataDis = new TrendData();
        trendDataDis.setType("dis");
        trendDataDis.setBlue(""+maxShow1+","+maxShow2+","+maxShow3+","+maxShow4+","+maxShow5+","+maxShow6+","+maxShow7+","+maxShow8+","+maxShow9+","+maxShow10);
        mTrendListData.add(trendDataDis);

        //---------------------计算方案第2个 ------start---------------
        for (int i = 0; i < 10; i++) {
            int count = 0;//遗漏计数
            int ylTimes = 0;//遗漏次数、遗漏多少次
            int totalYL = 0;//遗漏的总数
            int biggestYL = 0;//最大的遗漏
            int showTime = 0;//出现的次数
            int lianchu = 0;//连出
            int biggestLianchu = 0;//最大连出
            int lastYL = 0;
            for (int j = 0; j < mTrendListData2.size(); j++) {
                String v = mTrendListData2.get(j);
                int value = Integer.parseInt(v);
                if (value == i) {
                    ++showTime;
                    totalYL += count;
                    ++lianchu;
                    biggestLianchu = ((biggestLianchu > lianchu) ? (biggestLianchu) : (lianchu));
                    count = 0;
                } else {
                    ++count;
                    if (count == 1) {
                        ++ylTimes;
                    }
                    if (j == (mTrendListData2.size() - 1)) {
                        totalYL += count;
                    }
                    lianchu = 0;
                }
                biggestYL = ((biggestYL > count) ? (biggestYL) : (count));
                if (j == (mTrendListData2.size() - 1)) {
                    lastYL = count;
                }
            }

            int averageYL = (int) (((float) totalYL) / ylTimes + 0.5);//平均遗漏,四舍五入
            trendDataMmv.setBlue(trendDataMmv.getBlue()+biggestYL+",");
            trendDataMlv.setBlue(trendDataMlv.getBlue()+biggestLianchu+",");
            trendDataAvg.setBlue(trendDataAvg.getBlue()+averageYL+",");
        }

        mTrendListData.add(trendDataAvg);
        mTrendListData.add(trendDataMmv);
        mTrendListData.add(trendDataMlv);
        return mTrendListData;
    }


    //走势图的数据解析
    private void onParserLotteryListResult(List<LotteryListResult> lotteryListResult) {
        mTrendListPK1.clear();
        mTrendListPK2.clear();
        mTrendListPK3.clear();
        mTrendListPK4.clear();
        mTrendListPK5.clear();
        mTrendListPK6.clear();
        mTrendListPK7.clear();
        mTrendListPK8.clear();
        mTrendListPK9.clear();
        mTrendListPK10.clear();
        switch (lotteryId){
            case "9"://广东11选5
            case "14"://GW115
            case "44"://11选5三分彩
                mTrendListPK1.addAll(onShow11X5(lotteryListResult,0));
                mTrendListPK2.addAll(onShow11X5(lotteryListResult,1));
                mTrendListPK3.addAll(onShow11X5(lotteryListResult,2));
                mTrendListPK4.addAll(onShow11X5(lotteryListResult,3));
                mTrendListPK5.addAll(onShow11X5(lotteryListResult,4));
                break;
            case "15"://江苏快三
            case "17"://Gwk3ffc
            case "30"://安徽快三
            case "50"://极速快三五分彩
            case "51"://
                mTrendListPK1.addAll(onShowK3(lotteryListResult,0));
                mTrendListPK2.addAll(onShowK3(lotteryListResult,1));
                mTrendListPK3.addAll(onShowK3(lotteryListResult,2));
                break;
            case "20"://Gw3d
                mTrendListPK1.addAll(onShowCQSSC(lotteryListResult,0));
                mTrendListPK2.addAll(onShowCQSSC(lotteryListResult,1));
                mTrendListPK3.addAll(onShowCQSSC(lotteryListResult,2));
                break;
            case "48"://幸运飞艇
            case "49":
            case "10"://北京PK拾
            case "52"://北京PK拾 5分
            case "19"://Gwpk10
                mTrendListPK1.addAll(onShowPK10(lotteryListResult,0));
                mTrendListPK2.addAll(onShowPK10(lotteryListResult,1));
                mTrendListPK3.addAll(onShowPK10(lotteryListResult,2));
                mTrendListPK4.addAll(onShowPK10(lotteryListResult,3));
                mTrendListPK5.addAll(onShowPK10(lotteryListResult,4));
                mTrendListPK6.addAll(onShowPK10(lotteryListResult,5));
                mTrendListPK7.addAll(onShowPK10(lotteryListResult,6));
                mTrendListPK8.addAll(onShowPK10(lotteryListResult,7));
                mTrendListPK9.addAll(onShowPK10(lotteryListResult,8));
                mTrendListPK10.addAll(onShowPK10(lotteryListResult,9));
                break;
            case "16"://Gw3fc
            case "28"://Gw5fc
            case "13"://Gwffc
            case "1"://重庆时时彩
                mTrendListPK1.addAll(onShowCQSSC(lotteryListResult,0));
                mTrendListPK2.addAll(onShowCQSSC(lotteryListResult,1));
                mTrendListPK3.addAll(onShowCQSSC(lotteryListResult,2));
                mTrendListPK4.addAll(onShowCQSSC(lotteryListResult,3));
                mTrendListPK5.addAll(onShowCQSSC(lotteryListResult,4));
                break;
            case "37"://北京快乐8
                lotteryResultRView.setVisibility(View.VISIBLE);
                lotteryResultTab.setVisibility(View.GONE);
                mPK10TrendView.setVisibility(View.GONE);
                mCQTrendView.setVisibility(View.GONE);
                m11X5TrendView.setVisibility(View.GONE);
                mK3TrendView.setVisibility(View.GONE);
                break;
        }
        //GameLog.log("篮球的值"+mTrendList.size());


    }

    @Override
    public void setPresenter(LotteryResultContract.Presenter presenter) {
        this.presenter = presenter;
    }


    class LotteryResultAdapter extends BaseQuickAdapter<LotteryListResult, BaseViewHolder> {

        public LotteryResultAdapter(int layoutId, @Nullable List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final LotteryListResult data) {

            holder.setText(R.id.itemLotteryResultNumber,"第"+data.getIssue()+"期").
                    setText(R.id.itemLotteryResultTime, TimeHelper.milliseconds2String(Long.parseLong(data.getOffical_time())*1000L));
            String [] dataList = data.getWn_number().split(",");
            int dataListSize = dataList.length;
            int total= 0;
            List<String> cpLeftEventList1 = new ArrayList<String>();
            List<String> cpLeftEventList22 =null;
            if(dataListSize>=20){
                cpLeftEventList22 = new ArrayList<String>();
            }
            for(int i=0;i<dataListSize;++i){
                if(i<10){
                    cpLeftEventList1.add(dataList[i]);
                }else{
                    cpLeftEventList22.add(dataList[i]);
                }
                total += Integer.parseInt(dataList[i]);
            }

            RecyclerView cpOrderLotteryOpen1 = holder.getView(R.id.itemLotteryResultOpen1);
            RecyclerView cpOrderLotteryOpen2 = holder.getView(R.id.itemLotteryResultOpen2);
            LinearLayoutManager cpOrderLotteryOpen11 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
            cpOrderLotteryOpen1.setLayoutManager(cpOrderLotteryOpen11);
            cpOrderLotteryOpen1.setHasFixedSize(true);
            cpOrderLotteryOpen1.setNestedScrollingEnabled(false);
            LinearLayoutManager cpOrderLotteryOpen22 = new LinearLayoutManager(getContext(),LinearLayoutManager.HORIZONTAL, false);
            cpOrderLotteryOpen2.setLayoutManager(cpOrderLotteryOpen22);
            cpOrderLotteryOpen2.setHasFixedSize(true);
            cpOrderLotteryOpen2.setNestedScrollingEnabled(false);

            switch (lotteryId){
                case "16"://Gw3fc
                case "28"://Gw5fc
                case "13"://Gwffc
                case "1"://重庆时时彩
                    cpOrderLotteryOpen1.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));
                    cpOrderLotteryOpen2.setVisibility(View.GONE);
                    cpOrderLotteryOpen1.setAdapter(new OpenQIUGameAdapter(R.layout.item_lottery_result_open_1,cpLeftEventList1));
                    holder.setText(R.id.itemLotteryResultText,"总和："+total+" 大小："+((total >= 23)?"大":"小")+" 单双："+((total % 2 ==1)?"单":"双"));
                    break;
                case "48"://幸运飞艇
                case "49"://幸运飞艇
                case "10"://北京PK拾
                case "52"://北京PK拾 5分
                case "19"://Gwpk10
                    List<String> cpLeftEventList2 = new ArrayList<String>();
                    cpLeftEventList2.add(Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1])+"");
                    cpLeftEventList2.add(((Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1]))%2 ==1)?"单":"双");
                    cpLeftEventList2.add((Integer.parseInt(dataList[0])+Integer.parseInt(dataList[1]))>11?"大":"小");
                    cpLeftEventList2.add(Integer.parseInt(dataList[0])>Integer.parseInt(dataList[9])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[1])>Integer.parseInt(dataList[8])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[2])>Integer.parseInt(dataList[7])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[3])>Integer.parseInt(dataList[6])?"龙":"虎");
                    cpLeftEventList2.add(Integer.parseInt(dataList[4])>Integer.parseInt(dataList[5])?"龙":"虎");

                    cpOrderLotteryOpen1.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.WRAP_CONTENT, LinearLayout.LayoutParams.WRAP_CONTENT));
                    cpOrderLotteryOpen2.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.WRAP_CONTENT, LinearLayout.LayoutParams.WRAP_CONTENT));

                    cpOrderLotteryOpen2.setVisibility(View.VISIBLE);
                    cpOrderLotteryOpen1.setAdapter(new OpenPKGameAdapter(R.layout.item_lottery_result_open_1,cpLeftEventList1));
                    cpOrderLotteryOpen2.setAdapter(new OpenRectangleGameAdapter(R.layout.item_cp_order_open_2,cpLeftEventList2));
                    holder.setGone(R.id.itemLotteryResultText,false);
                    break;
                case "9"://广东11选5
                case "14"://GW115
                case "44"://11选5三分彩
                    cpOrderLotteryOpen1.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));

                    cpOrderLotteryOpen2.setVisibility(View.GONE);
                    cpOrderLotteryOpen1.setAdapter(new OpenQIUGameAdapter(R.layout.item_lottery_result_open_1,cpLeftEventList1));
                    holder.setGone(R.id.itemLotteryResultText,false);
                    break;
                case "15"://江苏快三
                case "17"://Gwk3ffc
                case "20"://Gw3d
                case "30"://安徽快三
                case "50"://极速快三五分彩
                case "51"://
                    cpOrderLotteryOpen1.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));

                    cpOrderLotteryOpen2.setVisibility(View.GONE);
                    cpOrderLotteryOpen1.setAdapter(new OpenQIUGameAdapter(R.layout.item_lottery_result_open_1,cpLeftEventList1));
                    holder.setGone(R.id.itemLotteryResultText,false);
                    break;
                case "37"://北京快乐8
                    cpOrderLotteryOpen1.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.WRAP_CONTENT, LinearLayout.LayoutParams.WRAP_CONTENT));
                    cpOrderLotteryOpen2.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.WRAP_CONTENT, LinearLayout.LayoutParams.WRAP_CONTENT));
                    cpOrderLotteryOpen1.setAdapter(new OpenQIUGameAdapter(R.layout.item_lottery_result_open_1,cpLeftEventList1));
                    cpOrderLotteryOpen2.setAdapter(new OpenQIUGameAdapter(R.layout.item_lottery_result_open_1,cpLeftEventList22));
                    holder.setGone(R.id.itemLotteryResultText,false);
                    break;

            }

            //onShowImage(data.getIdentifier(),holder);
            /*holder.setText(R.id.itemHomeIconName, data.getName()).
                    setText(R.id.itemHomeIconDescribe, data.getSub_title());*/
        }
    }

    /**
     * 设置文字PK10+背景
     */
    class OpenPKGameAdapter extends BaseQuickAdapter<String,BaseViewHolder> {

        public OpenPKGameAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, String data) {
            switch (data){
                case "01":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_1);
                    break;
                case "02":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_2);
                    break;
                case "03":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_3);
                    break;
                case "04":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_4);
                    break;
                case "05":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_5);
                    break;
                case "06":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_6);
                    break;
                case "07":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_7);
                    break;
                case "08":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_8);
                    break;
                case "09":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_9);
                    break;
                case "10":
                    holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_pk_10);
                    break;
            }
            holder.setText(R.id.itemOrderOpen2,data);
        }
    }


    /**
     * 设置文字+球
     */
    class OpenQIUGameAdapter extends BaseQuickAdapter<String,BaseViewHolder> {

        public OpenQIUGameAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, String data) {
            holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_order_red_circle);
            /*if(data.length()==2){
                if("0".equals(data.substring(0,1))){
                    holder.setText(R.id.itemOrderOpen2,data.substring(1,2));
                }else{
                    holder.setText(R.id.itemOrderOpen2,data);
                }
            }else{
                holder.setText(R.id.itemOrderOpen2,data);
            }*/
            holder.setText(R.id.itemOrderOpen2,data);
        }
    }


    /**
     * 设置文字+边框
     */
    class OpenRectangleGameAdapter extends BaseQuickAdapter<String,BaseViewHolder> {

        public OpenRectangleGameAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, String data) {
            holder.setBackgroundRes(R.id.itemOrderOpen2,R.drawable.bg_cp_order_tv);
            holder.setText(R.id.itemOrderOpen2,data);
            holder.setTextColor(R.id.itemOrderOpen2,getResources().getColor(R.color.text_lottery_result));
        }
    }


}

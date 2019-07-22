package com.qpweb.a01.ui.home.agency;

import android.content.Intent;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.RequestOptions;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.qpweb.a01.Injections;
import com.qpweb.a01.MainActivity;
import com.qpweb.a01.R;
import com.qpweb.a01.base.BaseDialogFragment;
import com.qpweb.a01.base.IPresenter;
import com.qpweb.a01.data.DetailListResult;
import com.qpweb.a01.data.DetailWeekListResult;
import com.qpweb.a01.data.MyAgencyResults;
import com.qpweb.a01.data.ProListResults;
import com.qpweb.a01.utils.ACache;
import com.qpweb.a01.utils.CLipHelper;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.DoubleClickHelper;
import com.qpweb.a01.utils.GameLog;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;


public class AgencyFragment extends BaseDialogFragment implements AgencyContract.View{

    @BindView(R.id.agencyTView1)
    TextView agencyTView1;
    @BindView(R.id.agencyTView2)
    TextView agencyTView2;
    @BindView(R.id.agencyTView3)
    TextView agencyTView3;
    @BindView(R.id.agencyTView4)
    TextView agencyTView4;
    @BindView(R.id.agencyTView5)
    TextView agencyTView5;

    @BindView(R.id.agencyGetPromotion)
    TextView agencyGetPromotion;
    @BindView(R.id.profitHistory)
    TextView profitHistory;
    @BindView(R.id.profitYesterday)
    TextView profitYesterday;
    @BindView(R.id.profitYesterdayDirectlyUser)
    TextView profitYesterdayDirectlyUser;
    @BindView(R.id.profitYesterdayOtherUser)
    TextView profitYesterdayOtherUser;
    @BindView(R.id.regDirectlyTotal)
    TextView regDirectlyTotal;
    @BindView(R.id.regOtherTotal)
    TextView regOtherTotal;
    @BindView(R.id.profitGet)
    TextView profitGet;
    @BindView(R.id.agencyLay2QC)
    ImageView agencyLay2QC;
    @BindView(R.id.agencyLay2Screen)
    TextView agencyLay2Screen;
    @BindView(R.id.agencyLay2Share)
    TextView agencyLay2Share;
    @BindView(R.id.agencyLay2GetRecord)
    TextView agencyLay2GetRecord;
    @BindView(R.id.agencyLay2Intro)
    TextView agencyLay2Intro;

    @BindView(R.id.agencyLay3RView)
    RecyclerView agencyLay3RView;//推广明细
    @BindView(R.id.agencyLay4RView)
    RecyclerView agencyLay4RView;//推广周榜

    @BindView(R.id.agencyLay5Service)
    TextView agencyLay5Service;

    @BindView(R.id.agencyScreenshot)
    TextView agencyScreenshot;
    @BindView(R.id.agencyShare)
    TextView agencyShare;
    @BindView(R.id.agencyLay1)
    FrameLayout agencyLay1;
    @BindView(R.id.agencyLay2)
    RelativeLayout agencyLay2;
    @BindView(R.id.agencyLay3)
    LinearLayout agencyLay3;
    @BindView(R.id.agencyLay4)
    LinearLayout agencyLay4;
    @BindView(R.id.agencyLay5)
    FrameLayout agencyLay5;
    @BindView(R.id.agencyContainer)
    FrameLayout agencyContainer;
    @BindView(R.id.agencyClose)
    ImageView agencyClose;

    AgencyContract.Presenter presenter;
    List<DetailListResult> detailListResult;

    private ArrayList<Fragment> mFragments = new ArrayList<>();

    public static AgencyFragment newInstance() {
        Bundle bundle = new Bundle();
        AgencyFragment loginFragment = new AgencyFragment();
        loginFragment.setArguments(bundle);
        Injections.inject(loginFragment,null);
        return loginFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.agency_fragment;
    }


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {

        }

    }

    @Override
    public void setEvents(View view, @Nullable Bundle savedInstanceState) {
        String urlQc = ACache.get(getContext()).getAsString("promotion_qrcode_link");
        GameLog.log("用户二维码的地址 "+urlQc);
        Glide.with(AgencyFragment.this).load(urlQc).apply(new RequestOptions().fitCenter()).into(agencyLay2QC);
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        agencyLay3RView.setLayoutManager(linearLayoutManager);
        LinearLayoutManager linearLayoutManager4 = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL, false);
        agencyLay4RView.setLayoutManager(linearLayoutManager4);
    }



    @OnClick({R.id.agencyTView1, R.id.agencyTView2, R.id.agencyTView3, R.id.agencyTView4, R.id.agencyTView5, R.id.agencyClose,
    R.id.agencyScreenshot,R.id.agencyShare,R.id.agencyGetPromotion,R.id.agencyLay5Service,
    R.id.agencyLay2Screen,R.id.agencyLay2Share,R.id.agencyLay2GetRecord,R.id.agencyLay2Intro

    })
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.agencyLay5Service://客服
                //showMessage("在线客服");
                String url;
                url= ACache.get(getContext()).getAsString("OnlineServer");
                if(Check.isEmpty(url)){
                    showMessage("在线客服");
                    return;
                }
                Intent intent = new Intent(getContext(), MainActivity.class);
                intent.putExtra("app_url",url);
                startActivity(intent);
                break;
            case R.id.agencyLay2Screen://截图分享
                AgencyScreenFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.agencyLay2Share://复制分享
            case R.id.agencyShare:
                //分享
                String shareUrl = ACache.get(getContext()).getAsString("promotion_link");
                showMessage(shareUrl);
                CLipHelper.copy(getContext(),shareUrl);
                break;
            case R.id.agencyLay2GetRecord://领取记录
                presenter.postGetMyPromotionRecord("","");
                //AgencyGetProListFragment.newInstance(null).show(getFragmentManager());
                break;
            case R.id.agencyLay2Intro://推广介绍
                AgencyIntorFragment.newInstance().show(getFragmentManager());
                break;
            case R.id.agencyGetPromotion:
                DoubleClickHelper.getNewInstance().disabledView(agencyGetPromotion);
                presenter.postGetMyPromotion("","");
                break;
            case R.id.agencyScreenshot:
                //截屏
                AgencyScreenFragment.newInstance().show(getFragmentManager());
                //showMessage("截屏");
                break;
            case R.id.agencyTView1:
                agencyTView1.setBackgroundResource(R.mipmap.agency_tab_clicked);
                agencyTView2.setBackgroundResource(0);
                agencyTView3.setBackgroundResource(0);
                agencyTView4.setBackgroundResource(0);
                agencyTView5.setBackgroundResource(0);

                agencyLay1.setVisibility(View.VISIBLE);
                agencyLay2.setVisibility(View.GONE);
                agencyLay3.setVisibility(View.GONE);
                agencyLay4.setVisibility(View.GONE);
                agencyLay5.setVisibility(View.GONE);
                break;
            case R.id.agencyTView2:
                presenter.postMyProList("","");
                agencyTView1.setBackgroundResource(0);
                agencyTView2.setBackgroundResource(R.mipmap.agency_tab_clicked);
                agencyTView3.setBackgroundResource(0);
                agencyTView4.setBackgroundResource(0);
                agencyTView5.setBackgroundResource(0);

                agencyLay1.setVisibility(View.GONE);
                agencyLay2.setVisibility(View.VISIBLE);
                agencyLay3.setVisibility(View.GONE);
                agencyLay4.setVisibility(View.GONE);
                agencyLay5.setVisibility(View.GONE);

                break;
            case R.id.agencyTView3:
                presenter.postProDetail("","");
                agencyTView1.setBackgroundResource(0);
                agencyTView2.setBackgroundResource(0);
                agencyTView3.setBackgroundResource(R.mipmap.agency_tab_clicked);
                agencyTView4.setBackgroundResource(0);
                agencyTView5.setBackgroundResource(0);

                agencyLay1.setVisibility(View.GONE);
                agencyLay2.setVisibility(View.GONE);
                agencyLay3.setVisibility(View.VISIBLE);
                agencyLay4.setVisibility(View.GONE);
                agencyLay5.setVisibility(View.GONE);
                break;
            case R.id.agencyTView4:
                presenter.postWeeksDetail("","");
                agencyTView1.setBackgroundResource(0);
                agencyTView2.setBackgroundResource(0);
                agencyTView3.setBackgroundResource(0);
                agencyTView4.setBackgroundResource(R.mipmap.agency_tab_clicked);
                agencyTView5.setBackgroundResource(0);

                agencyLay1.setVisibility(View.GONE);
                agencyLay2.setVisibility(View.GONE);
                agencyLay3.setVisibility(View.GONE);
                agencyLay4.setVisibility(View.VISIBLE);
                agencyLay5.setVisibility(View.GONE);
                break;
            case R.id.agencyTView5:
                agencyTView1.setBackgroundResource(0);
                agencyTView2.setBackgroundResource(0);
                agencyTView3.setBackgroundResource(0);
                agencyTView4.setBackgroundResource(0);
                agencyTView5.setBackgroundResource(R.mipmap.agency_tab_clicked);

                agencyLay1.setVisibility(View.GONE);
                agencyLay2.setVisibility(View.GONE);
                agencyLay3.setVisibility(View.GONE);
                agencyLay4.setVisibility(View.GONE);
                agencyLay5.setVisibility(View.VISIBLE);
                break;
            case R.id.agencyClose:
                hide();
                break;
        }
    }

    @Override
    public void postMyProListResult(MyAgencyResults myAgencyResults) {
        profitHistory.setText(myAgencyResults.getProfit_history());
        profitYesterday.setText(myAgencyResults.getProfit_yesterday());
        profitYesterdayDirectlyUser.setText(myAgencyResults.getProfit_yesterday_for_directly_user());
        profitYesterdayOtherUser.setText(myAgencyResults.getProfitHistoryTotalForDirectlyUser());
        regDirectlyTotal.setText(myAgencyResults.getRegDirectlyTotal());
        regOtherTotal.setText(myAgencyResults.getReg_count());
        profitGet.setText(myAgencyResults.getProfit_get());
    }


    class DetailReportAdapter extends BaseQuickAdapter<DetailListResult, BaseViewHolder> {

        public DetailReportAdapter(int layoutResId, @Nullable List<DetailListResult> data) {
            super(layoutResId, data);
        }

        @Override
        protected void convert(BaseViewHolder helper, DetailListResult item) {
            /*if(item.getDate().equals("总计")){
                helper.setBackgroundColor(R.id.itemPersonDate,Color.parseColor("#579718"));
                helper.setBackgroundColor(R.id.itemPersonLay,Color.parseColor("#7e7e7e"));
                helper.setTextColor(R.id.itemPersonTurnover,getResources().getColor(R.color.white));
                helper.setTextColor(R.id.itemPersonPrize,getResources().getColor(R.color.white));
                helper.setTextColor(R.id.itemPersonProfit,getResources().getColor(R.color.white));
            }*/
            helper.setText(R.id.itemADetailUserName, item.getUsername()).
                    setText(R.id.itemADetailLevel, item.getLevel()).
                    setText(R.id.itemADetailMoney, item.getMoney()).
                    setText(R.id.itemADetailTime, item.getLast_date());
        }
    }

    class WeeksDetailAdapter extends BaseQuickAdapter<DetailWeekListResult, BaseViewHolder> {

        public WeeksDetailAdapter(int layoutResId, @Nullable List<DetailWeekListResult> data) {
            super(layoutResId, data);
        }

        private void onDataBackgot(String level,BaseViewHolder helper){
            if(level.length()==1){
                switch (level){
                    case "1":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_1);
                        break;
                    case "2":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_2);
                        break;
                    case "3":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_3);
                        break;
                    case "4":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_04);
                        break;
                    case "5":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_05);
                        break;
                    case "6":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_06);
                        break;
                    case "7":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_07);
                        break;
                    case "8":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_08);
                        break;
                    case "9":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_09);
                        break;
                }
            }else{
                switch (level.substring(0,1)){
                    case "0":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_00);
                        break;
                    case "1":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_01);
                        break;
                    case "2":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_02);
                        break;
                    case "3":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_03);
                        break;
                    case "4":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_04);
                        break;
                    case "5":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_05);
                        break;
                    case "6":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_06);
                        break;
                    case "7":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_07);
                        break;
                    case "8":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_08);
                        break;
                    case "9":
                        helper.setBackgroundRes(R.id.itemAWeekLevel,R.mipmap.agency_week_09);
                        break;
                }
                switch (level.substring(1,2)){
                    case "0":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_00);
                        break;
                    case "1":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_01);
                        break;
                    case "2":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_02);
                        break;
                    case "3":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_03);
                        break;
                    case "4":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_04);
                        break;
                    case "5":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_05);
                        break;
                    case "6":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_06);
                        break;
                    case "7":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_07);
                        break;
                    case "8":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_08);
                        break;
                    case "9":
                        helper.setBackgroundRes(R.id.itemAWeekLevel2,R.mipmap.agency_week_09);
                        break;
                }
            }

        }

        @Override
        protected void convert(BaseViewHolder helper, DetailWeekListResult item) {
            /*if(item.getDate().equals("总计")){
                helper.setBackgroundColor(R.id.itemPersonDate,Color.parseColor("#579718"));
                helper.setBackgroundColor(R.id.itemPersonLay,Color.parseColor("#7e7e7e"));
                helper.setTextColor(R.id.itemPersonTurnover,getResources().getColor(R.color.white));
                helper.setTextColor(R.id.itemPersonPrize,getResources().getColor(R.color.white));
                helper.setTextColor(R.id.itemPersonProfit,getResources().getColor(R.color.white));
            }*/
            onDataBackgot(item.getProxy_rank(),helper);
            helper.setText(R.id.itemAWeekUserName, item.getUsername()).
                    setText(R.id.itemAWeekMoney, item.getReback_money_thisweek());
        }
    }

    @Override
    public void postDetailListResult(List<DetailListResult> detailListResult) {
        this.detailListResult = detailListResult;
        //我的推广明细
        GameLog.log("推广明细的 大小 "+detailListResult.size());
        DetailReportAdapter detailReportAdapter = new DetailReportAdapter(R.layout.item_agency_detail,detailListResult);
        if(detailListResult.size()==0){
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("暂时你没有记录，快去分享给好友吧！");
            //textView.setTextColor(Color.parseColor("#C52133"));
            detailReportAdapter.setEmptyView(view);
            //detailReportAdapter.addHeaderView(view);
        }
        agencyLay3RView.setAdapter(detailReportAdapter);
    }
    @Override
    public void postWeeksDetailResult(List<DetailWeekListResult> detailListResult) {
        //我的推广周榜
        GameLog.log("推广明细的 大小 "+detailListResult.size());
        WeeksDetailAdapter detailReportAdapter = new WeeksDetailAdapter(R.layout.item_agency_weeks,detailListResult);
        if(detailListResult.size()==0){
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            TextView textView = view.findViewById(R.id.itemNoDate);
            textView.setText("暂时你没有记录，快去分享给好友吧！");
            textView.setTextColor(Color.parseColor("#C52133"));
            detailReportAdapter.setEmptyView(view);
            //detailReportAdapter.addHeaderView(view);
        }
        agencyLay4RView.setAdapter(detailReportAdapter);
    }

    @Override
    public void postGetMyPromotionRecordResult(List<ProListResults> proListResults) {

        AgencyGetProListFragment.newInstance((ArrayList<ProListResults>) proListResults).show(getFragmentManager());
    }

    @Override
    public void setPresenter(AgencyContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }
}

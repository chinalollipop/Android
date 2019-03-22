package com.cfcp.a01.ui.home.cplist.bet.betrecords.betlistrecords;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.cfcp.a01.CPInjections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseActivity2;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.data.BetRecordsListItemResult;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CPBetListRecordsFragment extends BaseActivity2 implements CpBetListRecordsContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpBetRecordsTitle)
    TextView cpBetRecordsTitle;
    @BindView(R.id.cpBetRecordsTitleName)
    TextView cpBetRecordsTitleName;
    @BindView(R.id.cpBetNowName)
    TextView cpBetNowName;
    @BindView(R.id.cpBetRecordsList)
    RecyclerView cpBetRecordsList;
    @BindView(R.id.cpBetRecordsbackHome)
    ImageView cpBetRecordsbackHome;
    @BindView(R.id.cpBetRecordsNumber)
    TextView cpBetRecordsNumber;
    @BindView(R.id.cpBetRecordsMoney)
    TextView cpBetRecordsMoney;
    @BindView(R.id.cpBetRecordsListNodata)
    TextView cpBetRecordsListNodata;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CpBetListRecordsContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String gameTime = "",gameForm = "",gameId = "";
    private String dzTitileName = "";

    int page =1;
    int pageTotal =1;
    List<BetRecordsListItemResult.DataBean> dataBeans = new ArrayList<>();
    BetListRecordsItemGameAdapter cpOrederContentGameAdapter = null;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_bet_list_records;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        Intent intent = getIntent();
        gameForm = intent.getStringExtra("gameForm");
        gameTime = intent.getStringExtra("gameTime");
        GameLog.log("船只的时间是才 "+gameTime);
        if(gameForm.equals("today")){
            cpBetRecordsTitle.setText("今日已结");
            presenter.getCpBetRecords("page=1&rows=20","today");
        }else if(gameForm.equals("before")){
            presenter.getCpBetRecords(gameTime+"/1/20","before");
        }else{
            cpBetRecordsTitle.setText("即时注单");
            cpBetNowName.setText("可盈金额");
            gameId =  intent.getStringExtra("gameId");
            String name = "";
            switch (gameId){
                case "51":
                    name ="北京赛车";
                    break;
                case "2":
                    name ="重庆时时彩";
                    break;
                case "189":
                    name ="极速赛车";
                    break;
                case "222":
                    name ="极速飞艇";
                    break;
                case "207":
                    name ="分分彩";
                    break;
                case "407":
                    name ="三分彩";
                    break;
                case "507":
                    name ="五分彩";
                    break;
                case "607":
                    name ="腾讯二分彩";
                    break;
                case "304":
                    name ="PC蛋蛋";
                    break;
                case "159":
                    name ="江苏快3";
                    break;
                case "47":
                    name ="幸运农场";
                    break;
                case "3":
                    name ="快乐十分";
                    break;
                case "69":
                    name ="香港六合彩";
                    break;
                case "384":
                    name ="极速快三";
                    break;

            }
            cpBetRecordsTitleName.setVisibility(View.VISIBLE);
            cpBetRecordsTitleName.setText(name+">下注明细");
            presenter.getCpBetRecords(gameId,"now");
        }
        final GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        cpBetRecordsList.setLayoutManager(gridLayoutManager);
        cpBetRecordsList.setHasFixedSize(true);
        cpBetRecordsList.setNestedScrollingEnabled(false);
        /*cpBetRecordsList.setRefreshProgressStyle(ProgressStyle.BallSpinFadeLoader);
        cpBetRecordsList.setLoadingMoreProgressStyle(ProgressStyle.BallRotate);
        cpBetRecordsList.setLoadingListener(new XRecyclerView.LoadingListener() {
            @Override
            public void onRefresh() {
                page =1;
                onSearchRecordList();
            }

            @Override
            public void onLoadMore() {
                ++page;
                onSearchRecordList();
            }
        });*/
        /*cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.VERTICAL,5,getContext().getColor(R.color.textview_normal),8));
        cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.HORIZONTAL,5,getContext().getColor(R.color.textview_normal),8));*/

    }

    private void onSearchRecordList(){
        if(gameForm.equals("today")){
            presenter.getCpBetRecords("page="+page+"&rows=20","today");
        }else if(gameForm.equals("before")){
            presenter.getCpBetRecords(gameTime+"/"+page+"/20","before");
        }else{
            presenter.getCpBetRecords(gameId,"now");
        }
    }

    @OnClick({  R.id.cpBetRecordsbackHome})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cpBetRecordsbackHome:
                //刷新用户余额
                finish();
                break;
        }
    }



    class BetListRecordsItemGameAdapter extends BaseQuickAdapter<BetRecordsListItemResult.DataBean, BaseViewHolder> {

        public BetListRecordsItemGameAdapter(int layoutId, List datas) {
            super( layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, BetRecordsListItemResult.DataBean data) {
            /** 北京赛车    game_code 51
             *  重庆时时彩    game_code 2
             *  极速赛车    game_code 189
             *  极速飞艇    game_code 222
             *  分分彩    game_code 207
             *  三分彩    game_code 407
             *  五分彩    game_code 507
             *  腾讯二分彩    game_code 607
             *  PC蛋蛋    game_code 304
             *  江苏快3    game_code 159
             *  幸运农场    game_code 47
             *  快乐十分    game_code 3
             *  香港六合彩  game_code 69
             *  极速快三    game_code 384
             *
             */
            String name ="";
            switch (data.getGame_code()){
                case "51":
                    name ="北京赛车";
                    break;
                case "2":
                    name ="重庆时时彩";
                    break;
                case "189":
                    name ="极速赛车";
                    break;
                case "222":
                    name ="极速飞艇";
                    break;
                case "207":
                    name ="分分彩";
                    break;
                case "407":
                    name ="三分彩";
                    break;
                 case "507":
                     name ="五分彩";
                    break;
                case "607":
                    name ="腾讯二分彩";
                    break;
                case "304":
                    name ="PC蛋蛋";
                    break;
                case "159":
                    name ="江苏快3";
                    break;
                case "47":
                    name ="幸运农场";
                    break;
                case "3":
                    name ="快乐十分";
                    break;
                case "69":
                    name ="香港六合彩";
                    break;
                case "384":
                    name ="极速快三";
                    break;

            }
            holder.setText(R.id.cpBetRecord2time, name+"\n"+data.getRound());
            holder.setText(R.id.cpBetRecord2number, data.getBetInfo());
            holder.setText(R.id.cpBetRecord2money, GameShipHelper.formatMoney2(data.getDrop_money()));
            if(gameForm.equals("now")){
                holder.setText(R.id.cpBetRecord2time, data.getRound());
                holder.setText(R.id.cpBetRecord2win, GameShipHelper.formatMoney2(data.getMayWin()));
            }else{
                if("-".equals(data.getRebateResult().substring(0,1))){
                    holder.setTextColor(R.id.cpBetRecord2win,getResources().getColor(R.color.login_tv));
                }else{
                    holder.setTextColor(R.id.cpBetRecord2win,getResources().getColor(R.color.event_line));
                }
                holder.setText(R.id.cpBetRecord2win, GameShipHelper.formatMoney2(data.getRebateResult()));
            }
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CpBetListRecordsContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#ff0000'>" + sign+"</font>";
    }

    @Override
    public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult) {
        if(gameForm.equals("now")){
            pageTotal =1;
            cpBetRecordsMoney.setText(Html.fromHtml("总下注金额："+onMarkRed(betRecordsResult.getOtherData().getTotalBetMoney()+"")));
            cpBetRecordsNumber.setVisibility(View.GONE);
        }else{
            pageTotal = betRecordsResult.getTotalCount()/20;
            cpBetRecordsNumber.setText(Html.fromHtml("下注金额："+onMarkRed(betRecordsResult.getOtherData().getTotalBetMoney()+"")));
            cpBetRecordsMoney.setText(Html.fromHtml("输赢金额："+onMarkRed(betRecordsResult.getOtherData().getTotalResultMoney()+"")));
        }
        GameLog.log("当前是第 【"+page+" 】页  总共的页数是 【"+pageTotal+"】");


        //cpBetRecordsList.addItemDecoration(new GridRvItemDecoration2(getContext()));
        if(betRecordsResult.getData().size()>0){
            cpBetRecordsListNodata.setVisibility(View.GONE);
            cpBetRecordsList.setVisibility(View.VISIBLE);
            /*if(page == 1){
                dataBeans.clear();
                cpBetRecordsList.refreshComplete();
                if(gameForm.equals("now")){
                    cpBetRecordsList.setNoMore(true);
                    cpBetRecordsList.loadMoreComplete();
                }
            }else if(page>=pageTotal){
                    cpBetRecordsList.setNoMore(true);
            }else{
                cpBetRecordsList.loadMoreComplete();
            }*/
            dataBeans.addAll(betRecordsResult.getData());
            if(null == cpOrederContentGameAdapter){
                cpOrederContentGameAdapter = new BetListRecordsItemGameAdapter( R.layout.item_cp_records_3, dataBeans);
                cpBetRecordsList.setAdapter(cpOrederContentGameAdapter);
            }
            cpOrederContentGameAdapter.notifyDataSetChanged();
        }else{
            showMessage("暂无数据！");
            if(page==1){
                cpBetRecordsList.setVisibility(View.GONE);
                cpBetRecordsListNodata.setVisibility(View.VISIBLE);
            }
            //cpBetRecordsList.setNoMore(true);
        }

    }
}

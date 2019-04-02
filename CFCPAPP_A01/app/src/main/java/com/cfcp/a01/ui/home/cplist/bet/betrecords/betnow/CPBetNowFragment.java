package com.cfcp.a01.ui.home.cplist.bet.betrecords.betnow;

import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;


import com.cfcp.a01.CPInjections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseActivity2;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.data.CPBetNowResult;
import com.cfcp.a01.ui.home.cplist.bet.betrecords.betlistrecords.CPBetListRecordsFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CPBetNowFragment extends BaseActivity2 implements CpBetNowContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpBetRecordsList)
    RecyclerView cpBetRecordsList;
    @BindView(R.id.cpBetRecordsbackHome)
    ImageView cpBetRecordsbackHome;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CpBetNowContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String gameTime = "";
    private String dzTitileName = "";

    int page =1;
    int pageTotal =1;
    BetListRecordsItemGameAdapter cpOrederContentGameAdapter = null;
    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_bet_now;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        Intent intent = getIntent();
//        game_code = intent.getStringExtra("gameId");
        gameTime = intent.getStringExtra("gameTime");
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        cpBetRecordsList.setLayoutManager(gridLayoutManager);
        cpBetRecordsList.setHasFixedSize(true);
        cpBetRecordsList.setNestedScrollingEnabled(false);
        GameLog.log("船只的时间是才 "+gameTime);
        presenter.getCpBetRecords("");
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
        presenter.getCpBetRecords(gameTime+"/"+page+"/20");
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



    class BetListRecordsItemGameAdapter extends BaseQuickAdapter<BetNow, BaseViewHolder> {

        public BetListRecordsItemGameAdapter( int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final BetNow data) {
            String name ="";
            switch (data.id){
                case "50":
                    name ="北京PK拾";
                    break;
                case "1":
                    name ="重庆时时彩";
                    break;
                case "55":
                    name ="幸运飞艇";
                    break;
                case "70":
                    name ="香港六合彩";
                    break;
                case "72":
                    name ="极速六合彩";
                    break;
                case "66":
                    name ="PC蛋蛋";
                    break;
                 case "10":
                     name ="江苏骰宝(快3)";
                    break;
                case "73":
                    name ="极速快3五分彩";
                    break;
                case "74":
                    name ="极速快3三分彩";
                    break;
                case "75":
                    name ="极速快3分分彩";
                    break;
                case "51":
                    name ="极速赛车";
                    break;
                case "2":
                    name ="官方分分彩";
                    break;
                case "60":
                    name ="广东快乐十分";
                    break;
                case "61":
                    name ="重庆幸运农场";
                    break;
                case "65":
                    name ="北京快乐8";
                    break;
                case "21":
                    name ="广东11选5";
                    break;
                case "4":
                    name ="阿里二分彩";
                    break;
                case "5":
                    name ="腾讯三分彩";
                    break;
                case "6":
                    name ="百度五分彩";
                    break;

            }
            holder.setText(R.id.cpBetRecord2time, name);
            holder.setText(R.id.cpBetRecord2number, data.num);
            if(Integer.parseInt(data.num)>0){
                holder.setText(R.id.cpBetRecord2number, data.num);
                TextView textView= holder.getView(R.id.cpBetRecord2number);
                textView.getPaint().setFlags(Paint.UNDERLINE_TEXT_FLAG); //下划线
            }
            holder.setText(R.id.cpBetRecord2money, data.moeny);
            holder.setOnClickListener(R.id.cpBetRecordLay, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(Integer.parseInt(data.num)>0){
                        Intent intent  = new Intent(getContext(), CPBetListRecordsFragment.class);
                        intent.putExtra("gameForm","now");
                        intent.putExtra("gameTime",data.id);
                        intent.putExtra("gameId",data.id);
                        startActivity(intent);
                    }
                }
            });
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CpBetNowContract.Presenter presenter) {

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

    private BetNow getData(String gameId,CPBetNowResult betRecordsResult){
        int listSize = betRecordsResult.getList().size();
        for(CPBetNowResult.ListBean listBean:betRecordsResult.getList()){
            if(gameId.equals(listBean.getGameId())){
                BetNow betNow = new BetNow();
                betNow.id = listBean.getGameId()+"";
                betNow.moeny = listBean.getTotalMoney();
                betNow.num = listBean.getTotalNums();
                return betNow;
            }
        }
        return null;
    }

    @Override
    public void getBetRecordsResult(CPBetNowResult betRecordsResult) {
        List<CPBetNowResult.ListBean> listBeanList = betRecordsResult.getList();
        int listSize = listBeanList.size();
        List<String> betNowListID = new ArrayList<>();
        for(int k=0;k<listSize;++k){
            betNowListID.add(listBeanList.get(k).getGameId()+"");
        }
        List<BetNow> betNowList = new ArrayList<>();
        if(listSize>0){
            if(betNowListID.contains("50")){
                betNowList.add(getData("50",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "50";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(betNowListID.contains("1")){
                betNowList.add(getData("1",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "1";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(betNowListID.contains("55")){
                betNowList.add(getData("55",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "55";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("70")){
                betNowList.add(getData("70",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "70";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("72")){
                betNowList.add(getData("72",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "72";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("66")){
                betNowList.add(getData("66",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "66";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("10")){
                betNowList.add(getData("10",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "10";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(betNowListID.contains("73")){
                betNowList.add(getData("73",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "73";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(betNowListID.contains("74")){
                betNowList.add(getData("74",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "74";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(betNowListID.contains("75")){
                betNowList.add(getData("75",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "75";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("51")){
                betNowList.add(getData("51",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "51";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("2")){
                betNowList.add(getData("2",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "2";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("60")){
                betNowList.add(getData("60",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "60";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }


            if(betNowListID.contains("61")){
                betNowList.add(getData("61",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "61";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("65")){
                betNowList.add(getData("65",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "65";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("21")){
                betNowList.add(getData("21",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "21";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("4")){
                betNowList.add(getData("4",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "4";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(betNowListID.contains("5")){
                betNowList.add(getData("5",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "5";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(betNowListID.contains("6")){
                betNowList.add(getData("6",betRecordsResult));
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "6";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
        }

        /*cpBetRecordsList.refreshComplete();
        cpBetRecordsList.loadMoreComplete();*/
        if(null == cpOrederContentGameAdapter){
            cpOrederContentGameAdapter = new BetListRecordsItemGameAdapter( R.layout.item_cp_records_4, betNowList);
            cpBetRecordsList.setAdapter(cpOrederContentGameAdapter);
        }
        cpOrederContentGameAdapter.notifyDataSetChanged();

    }
}

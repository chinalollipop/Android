package com.hgapp.a6668.homepage.cplist.bet.betrecords.betnow;

import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.text.Html;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.a6668.CPInjections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.BaseActivity2;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.data.BetRecordsListItemResult;
import com.hgapp.a6668.data.CPBetNowResult;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.betlistrecords.CPBetListRecordsFragment;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.jcodecraeer.xrecyclerview.ProgressStyle;
import com.jcodecraeer.xrecyclerview.XRecyclerView;
import com.zhy.adapter.recyclerview.base.ViewHolder;

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
    XRecyclerView cpBetRecordsList;
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
        GameLog.log("船只的时间是才 "+gameTime);
        presenter.getCpBetRecords("");
        final GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        cpBetRecordsList.setLayoutManager(gridLayoutManager);
        cpBetRecordsList.setHasFixedSize(true);
        cpBetRecordsList.setNestedScrollingEnabled(false);
        cpBetRecordsList.setRefreshProgressStyle(ProgressStyle.BallSpinFadeLoader);
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
        });
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



    class BetListRecordsItemGameAdapter extends AutoSizeRVAdapter<BetNow> {
        private Context context;

        public BetListRecordsItemGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final BetNow data, final int position) {
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
            switch (data.id){
                case "51":
                    name ="北京赛车";
                    break;
                case "2":
                    name ="欢乐生肖";
                    break;
                case "189":
                    name ="极速赛车";
                    break;
                case "168":
                    name ="幸运飞艇";
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
                        Intent intent  = new Intent(getContext(),CPBetListRecordsFragment.class);
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

    @Override
    public void getBetRecordsResult(CPBetNowResult betRecordsResult) {

        List<BetNow> betNowList = new ArrayList<>();
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
        if(!Check.isNull(betRecordsResult)){
            if(!Check.isNull(betRecordsResult.getData51())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData51().getGameId();
                betNow.moeny = betRecordsResult.getData51().getTotalMoney();
                betNow.num = betRecordsResult.getData51().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "51";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(!Check.isNull(betRecordsResult.getData2())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData2().getGameId();
                betNow.moeny = betRecordsResult.getData2().getTotalMoney();
                betNow.num = betRecordsResult.getData2().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "2";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }
            if(!Check.isNull(betRecordsResult.getData189())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData189().getGameId();
                betNow.moeny = betRecordsResult.getData189().getTotalMoney();
                betNow.num = betRecordsResult.getData189().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "189";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData222())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData222().getGameId();
                betNow.moeny = betRecordsResult.getData222().getTotalMoney();
                betNow.num = betRecordsResult.getData222().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "222";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData207())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData207().getGameId();
                betNow.moeny = betRecordsResult.getData207().getTotalMoney();
                betNow.num = betRecordsResult.getData207().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "207";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData407())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData407().getGameId();
                betNow.moeny = betRecordsResult.getData407().getTotalMoney();
                betNow.num = betRecordsResult.getData407().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "407";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData507())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData507().getGameId();
                betNow.moeny = betRecordsResult.getData507().getTotalMoney();
                betNow.num = betRecordsResult.getData507().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "507";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData607())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData607().getGameId();
                betNow.moeny = betRecordsResult.getData607().getTotalMoney();
                betNow.num = betRecordsResult.getData607().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "607";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData304())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData304().getGameId();
                betNow.moeny = betRecordsResult.getData304().getTotalMoney();
                betNow.num = betRecordsResult.getData304().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "304";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData159())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData159().getGameId();
                betNow.moeny = betRecordsResult.getData159().getTotalMoney();
                betNow.num = betRecordsResult.getData159().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "159";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }


            if(!Check.isNull(betRecordsResult.getData47())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData47().getGameId();
                betNow.moeny = betRecordsResult.getData47().getTotalMoney();
                betNow.num = betRecordsResult.getData47().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "47";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData3())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData3().getGameId();
                betNow.moeny = betRecordsResult.getData3().getTotalMoney();
                betNow.num = betRecordsResult.getData3().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "3";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData69())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData69().getGameId();
                betNow.moeny = betRecordsResult.getData69().getTotalMoney();
                betNow.num = betRecordsResult.getData69().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "69";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData384())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData384().getGameId();
                betNow.moeny = betRecordsResult.getData384().getTotalMoney();
                betNow.num = betRecordsResult.getData384().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "384";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

            if(!Check.isNull(betRecordsResult.getData168())){
                BetNow betNow = new BetNow();
                betNow.id = betRecordsResult.getData168().getGameId();
                betNow.moeny = betRecordsResult.getData168().getTotalMoney();
                betNow.num = betRecordsResult.getData168().getTotalNums();
                betNowList.add(betNow);
            }else{
                BetNow betNow = new BetNow();
                betNow.id = "168";
                betNow.moeny = "0";
                betNow.num = "0";
                betNowList.add(betNow);
            }

        }

        cpBetRecordsList.refreshComplete();
        cpBetRecordsList.loadMoreComplete();
        if(null == cpOrederContentGameAdapter){
            cpOrederContentGameAdapter = new BetListRecordsItemGameAdapter(getContext(), R.layout.item_cp_records_4, betNowList);
            cpBetRecordsList.setAdapter(cpOrederContentGameAdapter);
        }
        cpOrederContentGameAdapter.notifyDataSetChanged();

    }
}

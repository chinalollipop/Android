package com.cfcp.a01.ui.home.cplist.bet.betrecords;

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
import com.cfcp.a01.common.utils.CalcHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.data.BetRecordsResult;
import com.cfcp.a01.ui.home.cplist.bet.betrecords.betlistrecords.CPBetListRecordsFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CPBetRecordsFragment extends BaseActivity2 implements CpBetRecordsContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpBetRecordsList)
    RecyclerView cpBetRecordsList;
    @BindView(R.id.cpBetRecordsbackHome)
    ImageView cpBetRecordsbackHome;
    @BindView(R.id.cpBetRecordsNumber)
    TextView cpBetRecordsNumber;
    @BindView(R.id.cpBetRecordsMoney)
    TextView cpBetRecordsMoney;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CpBetRecordsContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_bet_records;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        presenter.getCpBetRecords();
        /*cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.VERTICAL,5,getContext().getColor(R.color.textview_normal),8));
        cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.HORIZONTAL,5,getContext().getColor(R.color.textview_normal),8));*/

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


    class BetRecordsGameAdapter extends BaseQuickAdapter<BetRecordsList, BaseViewHolder> {

        public BetRecordsGameAdapter(Context context, int layoutId, List datas) {
            super( layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, BetRecordsList data) {
            holder.setText(R.id.cpRecordsItemName, data.recordsname);
            RecyclerView recyclerView = holder.getView(R.id.cpRecordsItemRV);
            GridLayoutManager gridLayoutManager= new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
            recyclerView.setLayoutManager(gridLayoutManager);
            recyclerView.setHasFixedSize(true);
            recyclerView.setNestedScrollingEnabled(false);
            BetRecordsItemGameAdapter cpOrederContentGameAdapter = null;
            cpOrederContentGameAdapter = new BetRecordsItemGameAdapter(getContext(), R.layout.item_cp_records_2, data.arrayListData);
            recyclerView.setAdapter(cpOrederContentGameAdapter);

            /*holder.setBackgroundRes(R.id.iv_item_game_icon, data.getIconId());
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    startActivity(new Intent(getContext(),CPOrderFragment.class));
                    //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("111", "222", "333"))));
                }
            });*/
        }
    }

    class BetRecordsItemGameAdapter extends BaseQuickAdapter<BetRecordsList.dataBean,BaseViewHolder> {

        public BetRecordsItemGameAdapter(Context context, int layoutId, List datas) {
            super( layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final BetRecordsList.dataBean data) {
            int position =0;
            if(position==7){
                holder.setText(R.id.cpBetRecord2time, "点击日期可查看下注详情");
                holder.setVisible(R.id.cpBetRecord2time,true);
                holder.setVisible(R.id.cpBetRecord2number,false);
                holder.setVisible(R.id.cpBetRecord2money,false);
                holder.setVisible(R.id.cpBetRecord2win,false);
                return;
            }else{
               /* holder.setVisible(R.id.cpBetRecord2time,true);
                holder.setVisible(R.id.cpBetRecord2number,true);
                holder.setVisible(R.id.cpBetRecord2money,true);
                holder.setVisible(R.id.cpBetRecord2win,true);*/
            }
            holder.setText(R.id.cpBetRecord2time, data.getBet_time());
            holder.setText(R.id.cpBetRecord2number, data.getAllnum());
            holder.setText(R.id.cpBetRecord2money, data.getAllMoney());
            holder.setText(R.id.cpBetRecord2win, data.getAllWin());
            holder.setOnClickListener(R.id.cpBetRecordLay, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    int position =0;
                    if(position==7){
                        showMessage("点击到钢板了");
                        return;
                    }
                    GameLog.log("时间是："+data.getTime()+" 次数是 "+data.getAllnum());
                    if(Integer.parseInt(data.getAllnum())>0){
                        Intent intent  = new Intent(getContext(), CPBetListRecordsFragment.class);
                        intent.putExtra("gameForm","before");
                        intent.putExtra("gameTime",data.getTime());
                        startActivity(intent);
                    }

                   // startActivity(new Intent(getContext(),CPOrderFragment.class));
                    //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("111", "222", "333"))));
                }
            });
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(CpBetRecordsContract.Presenter presenter) {

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
    public void getBetRecordsResult(BetRecordsResult betRecordsResult) {
        List<BetRecordsList> allList = new ArrayList<BetRecordsList>();
        BetRecordsResult.ThisWeekBean lastWeek = betRecordsResult.getLastWeek();
        BetRecordsResult.ThisWeekBean thisWeek = betRecordsResult.getThisWeek();
        List<BetRecordsResult.ThisWeekBean.RowBean> rowBeans = betRecordsResult.getRow();
        int rowBeansSize = rowBeans.size();
        List<BetRecordsList.dataBean>  lastweekList=  new ArrayList<>();
        List<BetRecordsList.dataBean>  thisweekList=  new ArrayList<>();
        BetRecordsList lastWeekBetRecordsData = new BetRecordsList();
        lastWeekBetRecordsData.recordsname = "上 周";
        BetRecordsList thisWeekBetRecordsData = new BetRecordsList();
        thisWeekBetRecordsData.recordsname ="本 周";

        for(int k=0;k<rowBeansSize;++k){
            if(rowBeans.get(k).getDate().equals(thisWeek.getdata1().getDateformat())){
                BetRecordsList.dataBean thisdataBean1 = new BetRecordsList.dataBean();
                thisdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                thisdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                thisdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                thisdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+thisWeek.getdata1().getWeek());
                thisdataBean1.setTime(rowBeans.get(k).getDate());
                thisweekList.add(thisdataBean1);
            }


            if(rowBeans.get(k).getDate().equals(lastWeek.getdata1().getDateformat())){
                BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
                lastdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                lastdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                lastdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                lastdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+lastWeek.getdata1().getWeek());
                lastdataBean1.setTime(rowBeans.get(k).getDate());
                lastweekList.add(lastdataBean1);
            }
        }

        if(lastweekList.size()==0){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(lastWeek.getdata1().getDateformat()+"\n"+lastWeek.getdata1().getWeek());
            lastweekList.add(lastdataBean1);
        }

        if(thisweekList.size()==0){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(thisWeek.getdata1().getDateformat()+"\n"+thisWeek.getdata1().getWeek());
            thisweekList.add(lastdataBean1);
        }

        List<String> win1  = new ArrayList<>();//负数
        List<String> win2  = new ArrayList<>();//正数
        int betNumber = 0;
        for(int k=0;k<rowBeansSize;++k){
           String windata =  GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin());
           if(windata.contains("-")){
               win1.add(windata.substring(1,windata.length()));
           }else{
               win2.add(windata.substring(0,windata.length()));
           }
            betNumber += Integer.parseInt(rowBeans.get(k).getAllnum());
            if(rowBeans.get(k).getDate().equals(lastWeek.getdata2().getDateformat())){
                BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
                lastdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                lastdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                lastdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                lastdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+lastWeek.getdata2().getWeek());
                lastdataBean1.setTime(rowBeans.get(k).getDate());
                lastweekList.add(lastdataBean1);
            }

            if(rowBeans.get(k).getDate().equals(thisWeek.getdata2().getDateformat())){
                BetRecordsList.dataBean thisdataBean1 = new BetRecordsList.dataBean();
                thisdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                thisdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                thisdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                thisdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+thisWeek.getdata2().getWeek());
                thisdataBean1.setTime(rowBeans.get(k).getDate());
                thisweekList.add(thisdataBean1);
            }

        }

        if(lastweekList.size()==1){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(lastWeek.getdata2().getDateformat()+"\n"+lastWeek.getdata2().getWeek());
            lastweekList.add(lastdataBean1);
        }


        if(thisweekList.size()==1){
            BetRecordsList.dataBean thisdataBean2 = new BetRecordsList.dataBean();
            thisdataBean2.setAllnum("0");
            thisdataBean2.setAllMoney("0.00");
            thisdataBean2.setAllWin("0.00");
            thisdataBean2.setBet_time(thisWeek.getdata2().getDateformat()+"\n"+thisWeek.getdata2().getWeek());
            thisweekList.add(thisdataBean2);
        }


        for(int k=0;k<rowBeansSize;++k){
            if(rowBeans.get(k).getDate().equals(lastWeek.getdata3().getDateformat())){
                BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
                lastdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                lastdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                lastdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                lastdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+lastWeek.getdata3().getWeek());
                lastdataBean1.setTime(rowBeans.get(k).getDate());
                lastweekList.add(lastdataBean1);
            }

            if(rowBeans.get(k).getDate().equals(thisWeek.getdata3().getDateformat())){
                BetRecordsList.dataBean thisdataBean1 = new BetRecordsList.dataBean();
                thisdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                thisdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                thisdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                thisdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+thisWeek.getdata3().getWeek());
                thisdataBean1.setTime(rowBeans.get(k).getDate());
                thisweekList.add(thisdataBean1);
            }
        }

        if(lastweekList.size()==2){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(lastWeek.getdata3().getDateformat()+"\n"+lastWeek.getdata3().getWeek());
            lastweekList.add(lastdataBean1);
        }

        if(thisweekList.size()==2){
            BetRecordsList.dataBean thisdataBean2 = new BetRecordsList.dataBean();
            thisdataBean2.setAllnum("0");
            thisdataBean2.setAllMoney("0.00");
            thisdataBean2.setAllWin("0.00");
            thisdataBean2.setBet_time(thisWeek.getdata3().getDateformat()+"\n"+thisWeek.getdata3().getWeek());
            thisweekList.add(thisdataBean2);
        }

        for(int k=0;k<rowBeansSize;++k){
            if(rowBeans.get(k).getDate().equals(lastWeek.getdata4().getDateformat())){
                BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
                lastdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                lastdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                lastdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                lastdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+lastWeek.getdata4().getWeek());
                lastdataBean1.setTime(rowBeans.get(k).getDate());
                lastweekList.add(lastdataBean1);
            }

            if(rowBeans.get(k).getDate().equals(thisWeek.getdata4().getDateformat())){
                BetRecordsList.dataBean thisdataBean1 = new BetRecordsList.dataBean();
                thisdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                thisdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                thisdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                thisdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+thisWeek.getdata4().getWeek());
                thisdataBean1.setTime(rowBeans.get(k).getDate());
                thisweekList.add(thisdataBean1);
            }
        }

        if(lastweekList.size()==3){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(lastWeek.getdata4().getDateformat()+"\n"+lastWeek.getdata4().getWeek());
            lastweekList.add(lastdataBean1);
        }

        if(thisweekList.size()==3){
            BetRecordsList.dataBean thisdataBean2 = new BetRecordsList.dataBean();
            thisdataBean2.setAllnum("0");
            thisdataBean2.setAllMoney("0.00");
            thisdataBean2.setAllWin("0.00");
            thisdataBean2.setBet_time(thisWeek.getdata4().getDateformat()+"\n"+thisWeek.getdata4().getWeek());
            thisweekList.add(thisdataBean2);
        }

        for(int k=0;k<rowBeansSize;++k){
            if(rowBeans.get(k).getDate().equals(lastWeek.getdata5().getDateformat())){
                BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
                lastdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                lastdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                lastdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                lastdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+lastWeek.getdata5().getWeek());
                lastdataBean1.setTime(rowBeans.get(k).getDate());
                lastweekList.add(lastdataBean1);
            }

            if(rowBeans.get(k).getDate().equals(thisWeek.getdata5().getDateformat())){
                BetRecordsList.dataBean thisdataBean1 = new BetRecordsList.dataBean();
                thisdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                thisdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                thisdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                thisdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+thisWeek.getdata5().getWeek());
                thisdataBean1.setTime(rowBeans.get(k).getDate());
                thisweekList.add(thisdataBean1);
            }
        }

        if(lastweekList.size()==4){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(lastWeek.getdata5().getDateformat()+"\n"+lastWeek.getdata5().getWeek());
            lastweekList.add(lastdataBean1);
        }

        if(thisweekList.size()==4){
            BetRecordsList.dataBean thisdataBean2 = new BetRecordsList.dataBean();
            thisdataBean2.setAllnum("0");
            thisdataBean2.setAllMoney("0.00");
            thisdataBean2.setAllWin("0.00");
            thisdataBean2.setBet_time(thisWeek.getdata5().getDateformat()+"\n"+thisWeek.getdata5().getWeek());
            thisweekList.add(thisdataBean2);
        }

        for(int k=0;k<rowBeansSize;++k){
            if(rowBeans.get(k).getDate().equals(lastWeek.getdata6().getDateformat())){
                BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
                lastdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                lastdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                lastdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                lastdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+lastWeek.getdata6().getWeek());
                lastdataBean1.setTime(rowBeans.get(k).getDate());
                lastweekList.add(lastdataBean1);
            }

            if(rowBeans.get(k).getDate().equals(thisWeek.getdata6().getDateformat())){
                BetRecordsList.dataBean thisdataBean1 = new BetRecordsList.dataBean();
                thisdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                thisdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                thisdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                thisdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+thisWeek.getdata6().getWeek());
                thisdataBean1.setTime(rowBeans.get(k).getDate());
                thisweekList.add(thisdataBean1);
            }
        }

        if(lastweekList.size()==5){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(lastWeek.getdata6().getDateformat()+"\n"+lastWeek.getdata6().getWeek());
            lastweekList.add(lastdataBean1);
        }

        if(thisweekList.size()==5){
            BetRecordsList.dataBean thisdataBean2 = new BetRecordsList.dataBean();
            thisdataBean2.setAllnum("0");
            thisdataBean2.setAllMoney("0.00");
            thisdataBean2.setAllWin("0.00");
            thisdataBean2.setBet_time(thisWeek.getdata6().getDateformat()+"\n"+thisWeek.getdata6().getWeek());
            thisweekList.add(thisdataBean2);
        }

        for(int k=0;k<rowBeansSize;++k){
            if(rowBeans.get(k).getDate().equals(lastWeek.getdata7().getDateformat())){
                BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
                lastdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                lastdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                lastdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                lastdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+lastWeek.getdata7().getWeek());
                lastdataBean1.setTime(rowBeans.get(k).getDate());
                lastweekList.add(lastdataBean1);
            }

            if(rowBeans.get(k).getDate().equals(thisWeek.getdata7().getDateformat())){
                BetRecordsList.dataBean thisdataBean1 = new BetRecordsList.dataBean();
                thisdataBean1.setAllnum(rowBeans.get(k).getAllnum());
                thisdataBean1.setAllMoney(GameShipHelper.formatMoney2(rowBeans.get(k).getAllMoney()));
                thisdataBean1.setAllWin(GameShipHelper.formatMoney2(rowBeans.get(k).getAllWin()));
                thisdataBean1.setBet_time(rowBeans.get(k).getDate()+"\n"+thisWeek.getdata7().getWeek());
                thisdataBean1.setTime(rowBeans.get(k).getDate());
                thisweekList.add(thisdataBean1);
            }
        }

        if(lastweekList.size()==6){
            BetRecordsList.dataBean lastdataBean1 = new BetRecordsList.dataBean();
            lastdataBean1.setAllnum("0");
            lastdataBean1.setAllMoney("0.00");
            lastdataBean1.setAllWin("0.00");
            lastdataBean1.setBet_time(lastWeek.getdata7().getDateformat()+"\n"+lastWeek.getdata7().getWeek());
            lastweekList.add(lastdataBean1);
        }

        if(thisweekList.size()==6){
            BetRecordsList.dataBean thisdataBean2 = new BetRecordsList.dataBean();
            thisdataBean2.setAllnum("0");
            thisdataBean2.setAllMoney("0.00");
            thisdataBean2.setAllWin("0.00");
            thisdataBean2.setBet_time(thisWeek.getdata7().getDateformat()+"\n"+thisWeek.getdata7().getWeek());
            thisweekList.add(thisdataBean2);
        }

        BetRecordsList.dataBean thisdataBean7 = new BetRecordsList.dataBean();
        thisdataBean7.setAllnum("0");
        thisdataBean7.setAllMoney("0.00");
        thisdataBean7.setAllWin("0.00");
        thisdataBean7.setBet_time(thisWeek.getdata7().getDateformat()+"\n"+thisWeek.getdata7().getWeek());
        thisweekList.add(thisdataBean7);

        lastWeekBetRecordsData.arrayListData = lastweekList;
        thisWeekBetRecordsData.arrayListData = thisweekList;

        allList.add(lastWeekBetRecordsData);
        allList.add(thisWeekBetRecordsData);


        GameLog.log("请求得到的数据："+betRecordsResult.getTodayWeek().toString());
        cpBetRecordsNumber.setText(Html.fromHtml("总笔数："+onMarkRed(betNumber+"")));
        Double win1d  = 0.0;
        Double win2d  = 0.0;
        for(int k=0;k<win1.size();++k){
            win1d += CalcHelper.add(win1.get(k),"0");
        }
        for(int k=0;k<win2.size();++k){
            win2d += CalcHelper.add(win2.get(k),"0");
        }

        Double win3d = CalcHelper.sub(""+win2d,""+win1d);

        cpBetRecordsMoney.setText(Html.fromHtml("总输赢："+onMarkRed(GameShipHelper.formatMoney2(win3d+""))));

        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        cpBetRecordsList.setLayoutManager(gridLayoutManager);
        cpBetRecordsList.setHasFixedSize(true);
        cpBetRecordsList.setNestedScrollingEnabled(false);
        //cpBetRecordsList.addItemDecoration(new GridRvItemDecoration2(getContext()));
        cpBetRecordsList.setAdapter(new BetRecordsGameAdapter(getContext(), R.layout.item_cp_records, allList));
    }
}

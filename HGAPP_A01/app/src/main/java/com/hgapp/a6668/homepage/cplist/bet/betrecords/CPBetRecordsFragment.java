package com.hgapp.a6668.homepage.cplist.bet.betrecords;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.a6668.CPInjections;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.BaseActivity2;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.CalcHelper;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration2;
import com.hgapp.a6668.common.widgets.RoundCornerImageView;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.BetRecordsResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.cplist.CPOrderFragment;
import com.hgapp.a6668.homepage.cplist.bet.CpBetApiContract;
import com.hgapp.common.util.GameLog;
import com.squareup.picasso.Picasso;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

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

                break;
        }
    }


    class BetRecordsGameAdapter extends AutoSizeRVAdapter<BetRecordsList> {
        private Context context;

        public BetRecordsGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, BetRecordsList data, final int position) {
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

    class BetRecordsItemGameAdapter extends AutoSizeRVAdapter<BetRecordsResult.ThisWeekBean.data1Bean> {
        private Context context;

        public BetRecordsItemGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, BetRecordsResult.ThisWeekBean.data1Bean data, final int position) {
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
            holder.setText(R.id.cpBetRecord2time, data.getDateformat()+"\n"+data.getWeek());
            holder.setText(R.id.cpBetRecord2number, data.getDateformat());
            holder.setText(R.id.cpBetRecord2money, data.getWeek());
//            holder.setText(R.id.cpBetRecord2win, data.recordsname);
            holder.setOnClickListener(R.id.cpBetRecordLay, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    if(position==7){
                        showMessage("点击到钢板了");
                        return;
                    }
                    showMessage("点击时间");
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
        return " <font color='#fdb22b'>" + sign+"</font>";
    }

    @Override
    public void getBetRecordsResult(BetRecordsResult betRecordsResult) {
        List<BetRecordsList> allList = new ArrayList<BetRecordsList>();
        BetRecordsResult.ThisWeekBean lastWeek = betRecordsResult.getLastWeek();
        BetRecordsResult.ThisWeekBean thisWeek = betRecordsResult.getThisWeek();
        List<BetRecordsResult.ThisWeekBean.data1Bean>  lastweekList=  new ArrayList<>();

        BetRecordsList lastWeekBetRecordsData = new BetRecordsList();
        lastWeekBetRecordsData.recordsname = "上 周";

        lastweekList.add(lastWeek.getdata1());
        lastweekList.add(lastWeek.getdata2());
        lastweekList.add(lastWeek.getdata3());
        lastweekList.add(lastWeek.getdata4());
        lastweekList.add(lastWeek.getdata5());
        lastweekList.add(lastWeek.getdata6());
        lastweekList.add(lastWeek.getdata7());
        lastWeekBetRecordsData.arrayListData = lastweekList;

        List<BetRecordsResult.ThisWeekBean.data1Bean>  thisweekList=  new ArrayList<>();
        BetRecordsList thisWeekBetRecordsData = new BetRecordsList();
        thisWeekBetRecordsData.recordsname ="本 周";

        thisweekList.add(thisWeek.getdata1());
        thisweekList.add(thisWeek.getdata2());
        thisweekList.add(thisWeek.getdata3());
        thisweekList.add(thisWeek.getdata4());
        thisweekList.add(thisWeek.getdata5());
        thisweekList.add(thisWeek.getdata6());
        thisweekList.add(thisWeek.getdata7());
        thisweekList.add(thisWeek.getdata7());
        thisWeekBetRecordsData.arrayListData = thisweekList;


        allList.add(lastWeekBetRecordsData);
        allList.add(thisWeekBetRecordsData);

        /*cpGameList.add(thisWeekBean);
        cpGameList.add(thisWeekBean2);*/
        GameLog.log("请求得到的数据："+betRecordsResult.getTodayWeek().toString());
        cpBetRecordsNumber.setText(Html.fromHtml("总笔数："+onMarkRed(String.valueOf(CalcHelper.add(betRecordsResult.getRow().get(0).getAllnum(),betRecordsResult.getRow().get(1).getAllnum())).replace(".0",""))));
        //cpBetRecordsMoney.setText(Html.fromHtml("总输赢："+onMarkRed(String.valueOf(CalcHelper.multiply(betRecordsResult.getRow().get(0).getAllnum(),betRecordsResult.getRow().get(1).getAllnum())))));
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 1, OrientationHelper.VERTICAL, false);
        cpBetRecordsList.setLayoutManager(gridLayoutManager);
        cpBetRecordsList.setHasFixedSize(true);
        cpBetRecordsList.setNestedScrollingEnabled(false);
        //cpBetRecordsList.addItemDecoration(new GridRvItemDecoration2(getContext()));
        cpBetRecordsList.setAdapter(new BetRecordsGameAdapter(getContext(), R.layout.item_cp_records, allList));
    }
}

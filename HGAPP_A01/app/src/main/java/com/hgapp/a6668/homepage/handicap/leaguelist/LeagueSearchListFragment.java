package com.hgapp.a6668.homepage.handicap.leaguelist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.NListView;
import com.hgapp.a6668.data.LeagueDetailSearchListResult;
import com.hgapp.a6668.data.LeagueSearchListResult;
import com.hgapp.a6668.data.LeagueSearchTimeResult;
import com.hgapp.a6668.data.MaintainResult;
import com.hgapp.a6668.homepage.handicap.BottombarViewManager;
import com.hgapp.a6668.homepage.handicap.HandicapFragment;
import com.hgapp.a6668.homepage.handicap.betnew.LeagueEvent;
import com.hgapp.a6668.homepage.handicap.leaguedetail.ComPassSearchEvent;
import com.hgapp.a6668.homepage.handicap.leaguedetail.LeagueDetailSearchEvent;
import com.hgapp.a6668.homepage.handicap.leaguedetail.zhbet.ZHBetViewManager;
import com.hgapp.a6668.homepage.handicap.leaguelist.championlist.ChampionDetailSearchEvent;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.abslistview.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class LeagueSearchListFragment extends HGBaseFragment implements LeagueSearchListContract.View{

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.llLeagueSearchType)
    LinearLayout llLeagueSearchType;
    @BindView(R.id.tvLeagueSearchTitle1)
    TextView tvLeagueSearchTitle1;
    @BindView(R.id.tvLeagueSearchTitle2)
    TextView tvLeagueSearchTitle2;
    @BindView(R.id.tvLeagueSearchTitle3)
    TextView tvLeagueSearchTitle3;
    @BindView(R.id.tvLeagueSearchDown1)
    TextView tvLeagueSearchDown1;
    @BindView(R.id.tvLeagueSearchDown2)
    TextView tvLeagueSearchDown2;
    @BindView(R.id.tvLeagueSearchDown3)
    TextView tvLeagueSearchDown3;
    @BindView(R.id.tvLeagueSearchName)
    TextView tvLeagueSearchName;
    @BindView(R.id.ivLeagueSearchRefresh)
    ImageView ivLeagueSearchRefresh;
    @BindView(R.id.tvLeagueSearchRefresh)
    TextView tvLeagueSearchRefresh;
    @BindView(R.id.llLeagueSearchTimeAll)
    LinearLayout llLeagueSearchTimeAll;
    @BindView(R.id.tvLeagueSearchTime1)
    TextView tvLeagueSearchTime1;
    @BindView(R.id.tvLeagueSearchTime2)
    TextView tvLeagueSearchTime2;
    @BindView(R.id.tvLeagueSearchRBTime)
    TextView tvLeagueSearchRBTime;
    @BindView(R.id.lvLeagueSearchList2)
    RecyclerView lvLeagueSearchList2;
    @BindView(R.id.lvLeagueSearchList)
    NListView lvLeagueSearchList;
    @BindView(R.id.tvLeagueSearchNoData)
    TextView lvLeagueSearchNoData;
    @BindView(R.id.btnLeagueSearch)
    TextView btnLeagueSearch;
    //让球/大小1 众合过关2 冠军3
    private String Ctype = "1";
    private String getArgParam1, getArgParam2,getArgParam3,getArgParam4;
    OptionsPickerView optionsPickerViewRBState;//联盟排序
    OptionsPickerView optionsPickerViewState2;//联盟排序
    OptionsPickerView optionsPickerViewTime;//无用的
    OptionsPickerView optionsPickerViewTime1;//时间排序
    LeagueSearchListContract.Presenter presenter;
    private ScheduledExecutorService executorService;
    onWaitingThread onWaitingThread = new onWaitingThread();
    private int sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
    List<String> time = new ArrayList<String>();
    String mdata ="";
    private int sorttype = 0;
    static List<String> stateList = new ArrayList<String>();
    static {
        stateList.add("联盟排序");
        stateList.add("时间排序");
    }

    public static LeagueSearchListFragment newInstance(List<String> param1) {
        LeagueSearchListFragment fragment = new LeagueSearchListFragment();
        Bundle args = new Bundle();
        /*args.putString(ARG_PARAM1, param1.get(0));
        args.putString(ARG_PARAM2, param1.get(1));*/
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            /*getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getString(ARG_PARAM2);*/
            getArgParam1 = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            getArgParam2 = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            getArgParam3 = getArguments().getStringArrayList(ARG_PARAM1).get(2);
            getArgParam4 = getArguments().getStringArrayList(ARG_PARAM1).get(3);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_league_search_list;
    }


    String gtype = "";
    String showtype = "";
    //RotateAnimation animation ;
    Animation animation ;
    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

        /*animation= new RotateAnimation(0,360f,Animation.RELATIVE_TO_SELF,0.5f,Animation.RELATIVE_TO_SELF,0.5f);
        animation.setDuration(1000);
        animation.setFillAfter(true);
        animation.setInterpolator(new LinearInterpolator());
        animation.setRepeatMode(Animation.RESTART);
        animation.setRepeatCount(Animation.INFINITE);*/
        animation = AnimationUtils.loadAnimation(getContext(),R.anim.rotate_clockwise);
        switch (getArgParam1){
            case "1":
                showtype = "RB";
                tvLeagueSearchRBTime.setVisibility(View.VISIBLE);
                llLeagueSearchType.setVisibility(View.GONE);
                llLeagueSearchTimeAll.setVisibility(View.GONE);

                optionsPickerViewRBState = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

                    @Override
                    public void onOptionsSelect(int options1, int options2, int options3, View v) {
                        tvLeagueSearchRBTime.setText(stateList.get(options1));
                        sorttype = options1;
                        postLeagueSearchList();
                    }
                }).build();
                optionsPickerViewRBState.setPicker(stateList);

                break;
            case "2":
                showtype = "FT";
                tvLeagueSearchRBTime.setVisibility(View.VISIBLE);
                llLeagueSearchType.setVisibility(View.VISIBLE);
                llLeagueSearchTimeAll.setVisibility(View.GONE);
                presenter.postLeagueSearchTime(null);

               optionsPickerViewRBState = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

                    @Override
                    public void onOptionsSelect(int options1, int options2, int options3, View v) {
                        tvLeagueSearchRBTime.setText(stateList.get(options1));
                        sorttype = options1;
                        postLeagueSearchList();
                    }
                }).build();
                optionsPickerViewRBState.setPicker(stateList);

                 optionsPickerViewState2 = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

                    @Override
                    public void onOptionsSelect(int options1, int options2, int options3, View v) {
                        tvLeagueSearchTime2.setText(stateList.get(options1));
                        sorttype = options1;
                        postLeagueSearchList();
                    }
                }).build();
                optionsPickerViewState2.setPicker(stateList);

                break;
            case "3":
                showtype = "FU";
                tvLeagueSearchRBTime.setVisibility(View.GONE);
                llLeagueSearchType.setVisibility(View.VISIBLE);
                llLeagueSearchTimeAll.setVisibility(View.VISIBLE);
                presenter.postLeagueSearchTime(null);

                optionsPickerViewState2 = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

                    @Override
                    public void onOptionsSelect(int options1, int options2, int options3, View v) {
                        tvLeagueSearchTime2.setText(stateList.get(options1));
                        sorttype = options1;
                        postLeagueSearchList();
                    }
                }).build();
                optionsPickerViewState2.setPicker(stateList);

                break;
        }

        switch (getArgParam2){
            case "足球":
                gtype = "FT";
                break;
            case "篮球 / 美式足球":
                gtype = "BK";
                break;
            case "3":
                gtype = "";
                break;
             default:
                gtype = "";
                break;
        }

        GameLog.log("getArgParam1 "+getArgParam1 + " getArgParam2 " + getArgParam2+" getArgParam3 "+getArgParam3 + " getArgParam4 " + getArgParam4);
        //
        tvLeagueSearchName.setText(getArgParam2);

        onSartTime();
        setCurrentShowPoastation();
    }


    private void setShowShopping(){
        if(gtype.equals("FT")){
            ZHBetViewManager.getSingleton().onShowView(getActivity(),this,gtype,"",getArgParam4.equals("s")?"1":"11");
        }else{
            ZHBetViewManager.getSingleton().onShowView(getActivity(),this,gtype,"",getArgParam4.equals("s")?"2":"22");
        }

    }


    private void postLeagueSearchList(){
        GameLog.log("-----------------------------Ctype的值是："+Ctype+" sorttype "+ sorttype);
        switch (getArgParam1){
            case "1":
                showtype = "RB";
                presenter.postLeagueSearchList(null,gtype,showtype,sorttype==0?"league":"time",mdata);
                break;
            case "2":
                showtype = "FT";
                if(Ctype.equals("1")){
                    presenter.postLeagueSearchList(null,gtype,showtype,sorttype==0?"league":"time",mdata);

                }else if(Ctype.equals("2")){
                    presenter.postLeaguePassSearchList(null,gtype,"",sorttype==0?"league":"time",mdata);
                }else{
                    presenter.postLeagueSearchChampionList("",showtype,gtype,"4");
                }
                 break;
            case "3":
                showtype = "FU";
                if(Ctype.equals("1")){
                    presenter.postLeagueSearchList(null,gtype,showtype,sorttype==0?"league":"time",mdata);

                }else if(Ctype.equals("2")){
                    presenter.postLeaguePassSearchList(null,gtype,"future",sorttype==0?"league":"time",mdata);
                }else{
                    presenter.postLeagueSearchChampionList("",showtype,gtype,"4");
                }
                break;
        }

    }

    //等待时长
    class onWaitingThread implements Runnable {
        @Override
        public void run() {
            if (sendAuthTime-- <= 0) {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        onSartTime();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if(tvLeagueSearchRefresh!=null){
                            tvLeagueSearchRefresh.setText(""+ sendAuthTime);
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }

    private void onSartTime(){
        presenter.postMaintain();
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        switch (getArgParam1){
            case "1":
                sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_R;
                break;
            case "2":
                sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
                break;
            case "3":
                sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
                break;
        }

        onSendAuthCode();
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(onWaitingThread, 0, 1000, TimeUnit.MILLISECONDS);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
    }


    @Override
    public void postLeagueSearchTimeResult(final LeagueSearchTimeResult leagueSearchTimeResult) {
        List<LeagueSearchTimeResult.DataBean>  dataBean = leagueSearchTimeResult.getData();
        time.add("全部日期");
        for(int k=0;k<dataBean.size();++k){
            time.add(dataBean.get(k).getDate_txt());
        }

           /* optionsPickerViewTime = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

                @Override
                public void onOptionsSelect(int options1, int options2, int options3, View v) {
                    tvLeagueSearchRBTime.setText(time.get(options1));
                    //sorttype = options1;
                }
            }).build();
            optionsPickerViewTime.setPicker(time);*/


            optionsPickerViewTime1 = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

                @Override
                public void onOptionsSelect(int options1, int options2, int options3, View v) {
                    tvLeagueSearchTime1.setText(time.get(options1));
                    //sorttype = options1;
                    if(options1==0){
                        mdata = "";
                    }/*else if(options1==1){
                        mdata = time.get(options1);
                        tvLeagueSearchTime1.setText("今日");
                    }*/else{
                        mdata = leagueSearchTimeResult.getData().get(options1-1).getDate();
                        //mdata = time.get(options1);
                    }
                    GameLog.log("当前的时间是 "+mdata);
                    postLeagueSearchList();
                }
            }).build();
            optionsPickerViewTime1.setPicker(time);
        }

    @Override
    public void postLeagueSearchListResult(LeagueSearchListResult leagueSearchListResult) {
        //GameLog.log("返回的列表是："+leagueSearchListResult.toString());
        ivLeagueSearchRefresh.clearAnimation();
        lvLeagueSearchList.setVisibility(View.VISIBLE);
        lvLeagueSearchList.setAdapter(new LeagueListAdapter(getContext(),R.layout.item_league_search, leagueSearchListResult.getData()));
        lvLeagueSearchNoData.setVisibility(View.GONE);
        /*lvLeagueSearchList2.setVisibility(View.VISIBLE);
        LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        lvLeagueSearchList2.setLayoutManager(gridLayoutManager);
        lvLeagueSearchList2.setHasFixedSize(true);
        lvLeagueSearchList2.setNestedScrollingEnabled(true);
        SaiGuoListAdapter2 saiGuoListAdapter =   new SaiGuoListAdapter2(getContext(), R.layout.item_league_search, leagueSearchListResult.getData());
        lvLeagueSearchList2.setAdapter(saiGuoListAdapter);*/

    }

    @Override
    public void postLeagueSearchListNoDataResult(String message) {
        //showMessage(message);
        ivLeagueSearchRefresh.clearAnimation();
        lvLeagueSearchList.setVisibility(View.GONE);
        lvLeagueSearchNoData.setVisibility(View.VISIBLE);
    }

    @Override
    public void postLeagueDetailSearchListResult(LeagueDetailSearchListResult leagueDetailSearchListResult) {
        GameLog.log("返回的列表是："+leagueDetailSearchListResult.toString());
        ivLeagueSearchRefresh.clearAnimation();
        EventBus.getDefault().post(leagueDetailSearchListResult);
        //EventBus.getDefault().post(new StartBrotherEvent(LeagueDetailListFragment.newInstance(leagueDetailSearchListResult)));
    }

    @Override
    public void postMaintainResult(List<MaintainResult> maintainResult) {

        switch (getArgParam1){
            case "1":
                for(MaintainResult maintainResult1:maintainResult){
                    if (maintainResult1.getType().equals("rb")){
                        if("1".equals(maintainResult1.getState())){
                            //滚球维护状态
                            lvLeagueSearchList.setVisibility(View.GONE);
                            tvLeagueSearchRBTime.setVisibility(View.GONE);
                            lvLeagueSearchNoData.setVisibility(View.VISIBLE);
                            lvLeagueSearchNoData.setText(maintainResult1.getContent());
                        }else{
                            postLeagueSearchList();
                        }
                    }
                }
                break;
            case "2":
                for(MaintainResult maintainResult1:maintainResult){
                    if (maintainResult1.getType().equals("today")){
                        if("1".equals(maintainResult1.getState())){
                            //今日赛事状态
                            lvLeagueSearchList.setVisibility(View.GONE);
                            tvLeagueSearchRBTime.setVisibility(View.GONE);
                            llLeagueSearchType.setVisibility(View.GONE);
                            llLeagueSearchTimeAll.setVisibility(View.GONE);
                            lvLeagueSearchNoData.setVisibility(View.VISIBLE);
                            lvLeagueSearchNoData.setText(maintainResult1.getContent());
                        }else{
                            postLeagueSearchList();
                        }
                    }
                }
                break;
            case "3":
                for(MaintainResult maintainResult1:maintainResult){
                    if (maintainResult1.getType().equals("future")){
                        if("1".equals(maintainResult1.getState())){
                            //早盘维护状态
                            lvLeagueSearchList.setVisibility(View.GONE);
                            tvLeagueSearchRBTime.setVisibility(View.GONE);
                            llLeagueSearchType.setVisibility(View.GONE);
                            llLeagueSearchTimeAll.setVisibility(View.GONE);
                            lvLeagueSearchNoData.setVisibility(View.VISIBLE);
                            lvLeagueSearchNoData.setText(maintainResult1.getContent());
                        }else{
                            postLeagueSearchList();
                        }
                    }
                }
                break;
        }

    }

    @Override
    public void setPresenter(LeagueSearchListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    public class SaiGuoListAdapter2 extends AutoSizeRVAdapter<LeagueSearchListResult.DataBean> {
        private Context context;

        public SaiGuoListAdapter2(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(com.zhy.adapter.recyclerview.base.ViewHolder holder, final LeagueSearchListResult.DataBean dataList, int position) {
            holder.setText(R.id.child_league_title,dataList.getM_League());
            holder.setText(R.id.child_league_number,dataList.getNum()+"");
            holder.setOnClickListener(R.id.child_league_title, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(Ctype.equals("1")){
                        EventBus.getDefault().post(new LeagueDetailSearchEvent(getArgParam3,getArgParam4,dataList.getGid(),getArgParam1));
                    }else if(Ctype.equals("2")){
                        //showtype 今日传空""/  早盘传future
                        EventBus.getDefault().post(new ComPassSearchEvent(getArgParam1,getArgParam3,getArgParam4,"",gtype,sorttype==0?"league":"time",mdata,"",dataList.getM_League()));
                        //EventBus.getDefault().post(new LeagueDetailSearchEvent(getArgParam3,getArgParam4,dataList.getGid(),getArgParam1));
                    }else if(Ctype.equals("3")){
                        EventBus.getDefault().post(new ChampionDetailSearchEvent(gtype,"4",getArgParam1,dataList.getM_League()));
                    }
                    //presenter.postLeagueDetailSearchList("",getArgParam3,getArgParam4,dataList.getGid());
                    // EventBus.getDefault().post(new StartBrotherEvent(BetFragment.newInstance(dataBean.getM_League(),dataBean.getType(),dataBean.getMID(),cate,active,type,userMoney), SupportFragment.SINGLETASK));
                }
            });
            //holder.setText(R.id.betRecordItemWin,GameShipHelper.formatNumber(rowsBean.getM_Result()));
        }

    }


    public class LeagueListAdapter extends com.hgapp.a6668.common.adapters.AutoSizeAdapter<LeagueSearchListResult.DataBean> {
        private Context context;

        public LeagueListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final LeagueSearchListResult.DataBean dataList, final int position) {
            holder.setText(R.id.child_league_title,dataList.getM_League());
            holder.setText(R.id.child_league_number,dataList.getNum()+"");
            holder.setOnClickListener(R.id.child_league_title, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(Ctype.equals("1")){
                        EventBus.getDefault().post(new LeagueDetailSearchEvent(getArgParam3,getArgParam4,dataList.getGid(),getArgParam1));
                    }else if(Ctype.equals("2")){
                        //showtype 今日传空""/  早盘传future
                        EventBus.getDefault().post(new ComPassSearchEvent(getArgParam1,getArgParam3,getArgParam4,"",gtype,sorttype==0?"league":"time",mdata,"",dataList.getM_League()));
                        //EventBus.getDefault().post(new LeagueDetailSearchEvent(getArgParam3,getArgParam4,dataList.getGid(),getArgParam1));
                    }else if(Ctype.equals("3")){
                        EventBus.getDefault().post(new ChampionDetailSearchEvent(gtype,"4",getArgParam1,dataList.getM_League()));
                    }
                    ACache.get(getContext()).put(HGConstant.USER_CURRENT_POSITION,Ctype);
                    //presenter.postLeagueDetailSearchList("",getArgParam3,getArgParam4,dataList.getGid());
                   // EventBus.getDefault().post(new StartBrotherEvent(BetFragment.newInstance(dataBean.getM_League(),dataBean.getType(),dataBean.getMID(),cate,active,type,userMoney), SupportFragment.SINGLETASK));
                }
            });

        }

    }

    //设置头部的展示
    private void setCurrentShowPoastation(){
        String position = ACache.get(getContext()).getAsString(HGConstant.USER_CURRENT_POSITION);
        switch(position) {
            case "1":
                Ctype = "1";
                //postLeagueSearchList();
                if(getArgParam1.equals("2")){       //今日
                    tvLeagueSearchRBTime.setVisibility(View.VISIBLE);
                    llLeagueSearchTimeAll.setVisibility(View.GONE);
                }else if(getArgParam1.equals("3")){ //早盘
                    tvLeagueSearchRBTime.setVisibility(View.GONE);
                    llLeagueSearchTimeAll.setVisibility(View.VISIBLE);
                }
                tvLeagueSearchTitle1.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchTitle2.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle3.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown1.setBackgroundColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchDown2.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown3.setBackgroundColor(getResources().getColor(R.color.bet_line));
                break;
            case "2":
                Ctype = "2";
                //postLeagueSearchList();
                setShowShopping();
                if(getArgParam1.equals("2")||getArgParam1.equals("3")){       //今日 +早盘
                    tvLeagueSearchRBTime.setVisibility(View.GONE);
                    llLeagueSearchTimeAll.setVisibility(View.VISIBLE);
                }
                llLeagueSearchTimeAll.setVisibility(View.VISIBLE);
                tvLeagueSearchTitle1.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle2.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchTitle3.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown1.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown2.setBackgroundColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchDown3.setBackgroundColor(getResources().getColor(R.color.bet_line));
                break;
            case "3":
                Ctype = "3";
                //postLeagueSearchList();
                llLeagueSearchTimeAll.setVisibility(View.GONE);
                tvLeagueSearchRBTime.setVisibility(View.GONE);
                tvLeagueSearchTitle1.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle2.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle3.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchDown1.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown2.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown3.setBackgroundColor(getResources().getColor(R.color.bet_title_tv_clicked));
                break;
        }
    }

    @OnClick({R.id.tvLeagueSearchTitle1,R.id.tvLeagueSearchTitle2,R.id.tvLeagueSearchTitle3,
            R.id.tvLeagueSearchTime1,R.id.tvLeagueSearchTime2,R.id.tvLeagueSearchRefresh,
            R.id.tvLeagueSearchRBTime,R.id.btnLeagueSearch,R.id.btnLeagueSearchBackHome})
    public void onClickView (View view){
        switch (view.getId()){
            case R.id.tvLeagueSearchRefresh:
                if (ivLeagueSearchRefresh != null) {
                    ivLeagueSearchRefresh.startAnimation(animation);
                }
                onSartTime();
                break;
            case R.id.tvLeagueSearchTime1://日期排序
                optionsPickerViewTime1.show();
                break;
            case R.id.tvLeagueSearchTime2://联盟 时间排序
                optionsPickerViewState2.show();
                break;
            case R.id.tvLeagueSearchRBTime://联盟 时间排序
                optionsPickerViewRBState.show();
                break;
            case R.id.tvLeagueSearchTitle1:
                Ctype = "1";
                postLeagueSearchList();
                if(getArgParam1.equals("2")){       //今日
                    tvLeagueSearchRBTime.setVisibility(View.VISIBLE);
                    llLeagueSearchTimeAll.setVisibility(View.GONE);
                }else if(getArgParam1.equals("3")){ //早盘
                    tvLeagueSearchRBTime.setVisibility(View.GONE);
                    llLeagueSearchTimeAll.setVisibility(View.VISIBLE);
                }
                tvLeagueSearchTitle1.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchTitle2.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle3.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown1.setBackgroundColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchDown2.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown3.setBackgroundColor(getResources().getColor(R.color.bet_line));
                break;
            case R.id.tvLeagueSearchTitle2:
                Ctype = "2";
                postLeagueSearchList();
                setShowShopping();
                if(getArgParam1.equals("2")||getArgParam1.equals("3")){       //今日 +早盘
                    tvLeagueSearchRBTime.setVisibility(View.GONE);
                    llLeagueSearchTimeAll.setVisibility(View.VISIBLE);
                }
                llLeagueSearchTimeAll.setVisibility(View.VISIBLE);
                tvLeagueSearchTitle1.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle2.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchTitle3.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown1.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown2.setBackgroundColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchDown3.setBackgroundColor(getResources().getColor(R.color.bet_line));
                break;
            case R.id.tvLeagueSearchTitle3:
                Ctype = "3";
                postLeagueSearchList();
                llLeagueSearchTimeAll.setVisibility(View.GONE);
                tvLeagueSearchRBTime.setVisibility(View.GONE);
                tvLeagueSearchTitle1.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle2.setTextColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchTitle3.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvLeagueSearchDown1.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown2.setBackgroundColor(getResources().getColor(R.color.bet_line));
                tvLeagueSearchDown3.setBackgroundColor(getResources().getColor(R.color.bet_title_tv_clicked));
                break;
            case R.id.btnLeagueSearch:
                GameLog.log("点击了所有球类 参数一是"+getArgParam1);
                EventBus.getDefault().post(new LeagueEvent(getArgParam1));
               // finish();
                break;
            case R.id.btnLeagueSearchBackHome:
                popTo(HandicapFragment.class,true);
                BottombarViewManager.getSingleton().onCloseView();
                break;

        }

    }

    @Override
    public boolean onBackPressedSupport() {
        return true;
    }

}

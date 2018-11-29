package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hgapp.a6668.CPInjections;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.BaseActivity2;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.TimeHelper;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration;
import com.hgapp.a6668.common.widgets.MarqueeTextView;
import com.hgapp.a6668.common.widgets.RoundCornerImageView;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CPHallResult;
import com.hgapp.a6668.data.CPLeftInfoResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.NoticeResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.cplist.hall.CPHallListContract;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.TimeUtils;
import com.squareup.picasso.Picasso;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class CPHallFragment extends BaseActivity2 implements CPHallListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpHallBackHome)
    ImageView cpHallBackHome;
    @BindView(R.id.cpPageBulletin)
    MarqueeTextView cpPageBulletin;
    @BindView(R.id.cpHallList)
    RecyclerView cpHallList;
    private static List<CPHallIcon> cpGameList = new ArrayList<CPHallIcon>();
    @BindView(R.id.cpHallMenu)
    ImageView cpHallMenu;
    @BindView(R.id.cpHallUserName)
    TextView cpHallUserName;
    @BindView(R.id.cpHallUserMoney)
    TextView cpHallUserMoney;
    Unbinder unbinder;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    CPHallListContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    private ScheduledExecutorService executorService;
    private ScheduledExecutorService executorService2;
    private HallPageGameAdapter hallPageGameAdapter;
    private long cpHallIcon0, cpHallIcon1, cpHallIcon2, cpHallIcon3, cpHallIcon4, cpHallIcon5, cpHallIcon6, cpHallIcon7,
            cpHallIcon8, cpHallIcon9, cpHallIcon10, cpHallIcon11, cpHallIcon12, cpHallIcon13;
    private int scpHallIcon0, scpHallIcon1, scpHallIcon2, scpHallIcon3, scpHallIcon4, scpHallIcon5, scpHallIcon6, scpHallIcon7,
            scpHallIcon8, scpHallIcon9, scpHallIcon10, scpHallIcon11, scpHallIcon12, scpHallIcon13;
    private int sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;

    static {
        cpGameList.add(new CPHallIcon("北京赛车", R.mipmap.cp_bjsc, 0,51));
        cpGameList.add(new CPHallIcon("重庆时时彩", R.mipmap.cp_cqssc, 0,2));
        cpGameList.add(new CPHallIcon("极速赛车", R.mipmap.cp_jsft, 0,189));
        cpGameList.add(new CPHallIcon("极速飞艇", R.mipmap.cp_jsfc, 0,222));
        cpGameList.add(new CPHallIcon("分分彩", R.mipmap.cp_ffc, 0,207));
        cpGameList.add(new CPHallIcon("三分彩", R.mipmap.cp_sfc, 0,407));
        cpGameList.add(new CPHallIcon("五分彩", R.mipmap.cp_wfc, 0,507));
        cpGameList.add(new CPHallIcon("腾讯二分彩", R.mipmap.cp_efc, 0,607));
        cpGameList.add(new CPHallIcon("PC蛋蛋", R.mipmap.cp_pcdd, 0,304));
        cpGameList.add(new CPHallIcon("江苏快3", R.mipmap.cp_js, 0,159));
        cpGameList.add(new CPHallIcon("幸运农场", R.mipmap.cp_xync, 0,47));
        cpGameList.add(new CPHallIcon("快乐十分", R.mipmap.cp_klsfc, 0,3));
        cpGameList.add(new CPHallIcon("香港六合彩", R.mipmap.cp_lhc, 0,47));
        cpGameList.add(new CPHallIcon("极速快三", R.mipmap.cp_js, 0,384));
    }

   /* public static CPHallFragment newInstance(List<String> param1) {
        CPHallFragment fragment = new CPHallFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        CPInjections.inject(fragment,null);
        fragment.setArguments(args);
        return fragment;
    }*/

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
        /*if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }*/
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_hall;
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        if (null != executorService) {
            GameLog.log("关闭计数任务1");
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }

        if (null != executorService2) {
            GameLog.log("关闭计数任务2");
            executorService2.shutdownNow();
            executorService2.shutdown();
            executorService2 = null;
        }
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService2 = Executors.newScheduledThreadPool(1);
        executorService2.scheduleAtFixedRate(new Runnable() {
            @Override
            public void run() {
                presenter.postCPHallList("");
            }
         }, 0, 10000, TimeUnit.MILLISECONDS);
        NoticeResult noticeResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_HOME_NOTICE), NoticeResult.class);
        if (!Check.isNull(noticeResult)) {
            List<String> stringList = new ArrayList<String>();
            int size = noticeResult.getData().size();
            for (int i = 0; i < size; ++i) {
                stringList.add(noticeResult.getData().get(i).getNotice());
            }
            cpPageBulletin.setContentList(stringList);
        }
        /*cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.VERTICAL,5,getContext().getColor(R.color.textview_normal),8));
        cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.HORIZONTAL,5,getContext().getColor(R.color.textview_normal),8));*/
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
        cpHallList.setLayoutManager(gridLayoutManager);
        cpHallList.setHasFixedSize(true);
        cpHallList.setNestedScrollingEnabled(false);
        cpHallList.addItemDecoration(new GridRvItemDecoration(getContext()));
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        executorService = Executors.newScheduledThreadPool(14);
        if(hallPageGameAdapter == null){
            hallPageGameAdapter = new HallPageGameAdapter(getContext(), R.layout.item_cp_hall, cpGameList);
        }
        cpHallList.setAdapter(hallPageGameAdapter);
        cpHallIcon0 = 0;
        cpHallIcon1 = 0;
        cpHallIcon2 = 0;
        cpHallIcon3 = 0;
        cpHallIcon4 = 0;
        cpHallIcon5 = 0;
        cpHallIcon6 = 0;
        cpHallIcon7 = 0;
        cpHallIcon8 = 0;
        cpHallIcon9 = 0;
        cpHallIcon10 = 0;
        cpHallIcon11 = 0;
        cpHallIcon12 = 0;
        cpHallIcon13 = 0;
        /*cpHallIcon0 = 11000;
        cpHallIcon1 = 90;
        cpHallIcon2 = 90;
        cpHallIcon3 = 15;
        cpHallIcon4 = 90;
        cpHallIcon5 = 30;
        cpHallIcon6 = 90;
        cpHallIcon7 = 7100;
        cpHallIcon8 = 90;
        cpHallIcon9 = 70;
        cpHallIcon10 = 990;
        cpHallIcon11 = 20;
        cpHallIcon12 = 90;
        cpHallIcon13 = 5000;*/
        /*hallPageGameAdapter = null;
        hallPageGameAdapter = new HallPageGameAdapter(getContext(), R.layout.item_cp_hall, cpGameList);
        cpHallList.setAdapter(hallPageGameAdapter);
        hallPageGameAdapter.notifyDataSetChanged();*/
    }

    private synchronized void onRequestData() {
//        presenter.postCPHallList("");
        /*if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        GameLog.log("=================================================");
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService = Executors.newScheduledThreadPool(14);
        cpHallIcon0 = 11000;
        cpHallIcon1 = 190;
        cpHallIcon2 = 90;
        cpHallIcon3 = 10;
        cpHallIcon4 = 90;
        cpHallIcon5 = 13000;
        cpHallIcon6 = 90;
        cpHallIcon7 = 150;
        cpHallIcon8 = 90;
        cpHallIcon9 = 70;
        cpHallIcon10 = 19000;
        cpHallIcon11 = 20;
        cpHallIcon12 = 190;
        cpHallIcon13 = 50;
        hallPageGameAdapter = null;
        hallPageGameAdapter = new HallPageGameAdapter(getContext(), R.layout.item_cp_hall, cpGameList);
        cpHallList.setAdapter(hallPageGameAdapter);
        hallPageGameAdapter.notifyDataSetChanged();*/
    }

    @OnClick({R.id.cpHallBackHome, R.id.cpHallMenu})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cpHallBackHome:
                finish();
                break;
            case R.id.cpHallMenu:
                break;
        }
    }

    @Override
    public void setPresenter(CPHallListContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void postCPHallListResult(CPHallResult cpHallResult) {

        GameLog.log("彩票大厅的数据 "+cpHallResult.toString());
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        executorService = Executors.newScheduledThreadPool(14);
        cpGameList.clear();
        cpGameList.add(new CPHallIcon("北京赛车", R.mipmap.cp_bjsc, 0,51));
        cpGameList.add(new CPHallIcon("重庆时时彩", R.mipmap.cp_cqssc, 0,2));
        cpGameList.add(new CPHallIcon("极速赛车", R.mipmap.cp_jsfc, 0,189));
        cpGameList.add(new CPHallIcon("极速飞艇", R.mipmap.cp_jsft, 0,222));
        cpGameList.add(new CPHallIcon("分分彩", R.mipmap.cp_ffc, 0,207));
        cpGameList.add(new CPHallIcon("三分彩", R.mipmap.cp_sfc, 0,407));
        cpGameList.add(new CPHallIcon("五分彩", R.mipmap.cp_wfc, 0,507));
        cpGameList.add(new CPHallIcon("腾讯二分彩", R.mipmap.cp_efc, 0,607));
        cpGameList.add(new CPHallIcon("PC蛋蛋", R.mipmap.cp_pcdd, 0,304));
        cpGameList.add(new CPHallIcon("江苏快3", R.mipmap.cp_js, 0,159));
        cpGameList.add(new CPHallIcon("幸运农场", R.mipmap.cp_xync, 0,47));
        cpGameList.add(new CPHallIcon("快乐十分", R.mipmap.cp_klsfc, 0,3));
        cpGameList.add(new CPHallIcon("香港六合彩", R.mipmap.cp_lhc, 0,69));
        cpGameList.add(new CPHallIcon("极速快三", R.mipmap.cp_jss, 0,384));
       /* CPHallResult._$51Bean _51Bean = cpHallResult.get_$51();
        cpGameList.add(new CPHallIcon("北京赛车", R.mipmap.cp_bjsc, 0,51));
        cpGameList.add(new CPHallIcon("重庆时时彩", R.mipmap.cp_jsft, 0,2));
        cpGameList.add(new CPHallIcon("极速赛车", R.mipmap.cp_cqssc, 0,189));
        cpGameList.add(new CPHallIcon("极速飞艇", R.mipmap.cp_jsfc, 0,222));
        cpGameList.add(new CPHallIcon("分分彩", R.mipmap.cp_ffc, 0,207));
        cpGameList.add(new CPHallIcon("三分彩", R.mipmap.cp_lhc, 0,407));
        cpGameList.add(new CPHallIcon("五分彩", R.mipmap.cp_lhc, 0,507));
        cpGameList.add(new CPHallIcon("腾讯二分彩", R.mipmap.cp_lhc, 0,607));
        cpGameList.add(new CPHallIcon("PC蛋蛋", R.mipmap.cp_pcdd, 0,304));
        cpGameList.add(new CPHallIcon("江苏快3", R.mipmap.cp_js, 0,159));
        cpGameList.add(new CPHallIcon("幸运农场", R.mipmap.cp_xync, 0,47));
        cpGameList.add(new CPHallIcon("快乐十分", R.mipmap.cp_klsfc, 0,3));
        cpGameList.add(new CPHallIcon("香港六合彩", R.mipmap.cp_js, 0,47));
        cpGameList.add(new CPHallIcon("极速快三", R.mipmap.cp_more, 0,384));
        CPHallIcon cpHallIcon = new CPHallIcon();
        cpHallIcon.setIsopen(_51Bean.getIsopen());
        cpHallIcon.setEndtime(_51Bean.getEndtime());
        cpHallIcon.setGameId(_51Bean.getGameId());
        cpHallIcon.setIconName(_51Bean.getEndtime());
        cpHallIcon.setEndtime(_51Bean.getEndtime());
        cpGameList.

        cpGameList.get(0).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(1).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(2).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(3).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(4).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(5).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(6).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(7).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(8).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(9).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(10).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(11).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(12).setIsopen(cpHallResult.get_$51().getIsopen());
        cpGameList.get(13).setIsopen(cpHallResult.get_$51().getIsopen());*/
       String systTime =TimeUtils.convertToDetailTime(System.currentTimeMillis());
       if(Check.isNumericNull(cpHallResult.get_$51().getEndtime())){
           cpHallIcon0 = 0;
           scpHallIcon0 = 1;
       }else{
           scpHallIcon0 = 0;
           cpHallIcon0 = TimeHelper.timeToSecond(cpHallResult.get_$51().getEndtime(),systTime)+20;
       }
        Date date = new Date(System.currentTimeMillis());
        GameLog.log("倒计时的时间 "+TimeUtils.string2Milliseconds(cpHallResult.get_$51().getEndtime())+" 系统的时间 "+systTime+" 秒的计算 "+date.getTime());
        if(Check.isNumericNull(cpHallResult.get_$2().getEndtime())){
            cpHallIcon1 = 0;
            scpHallIcon1 = 1;
        }else {
            scpHallIcon1 = 0;
            cpHallIcon1 = TimeHelper.timeToSecond(cpHallResult.get_$2().getEndtime(),systTime) +70;
        }
        if(Check.isNumericNull(cpHallResult.get_$189().getEndtime())){
            cpHallIcon2 = 0;
            scpHallIcon2 = 1;
        }else {
            scpHallIcon2 = 0;
            cpHallIcon2 =  TimeHelper.timeToSecond(cpHallResult.get_$189().getEndtime(),systTime)+100 ;
        }
        if(Check.isNumericNull(cpHallResult.get_$222().getEndtime())){
            cpHallIcon3 = 0;
            scpHallIcon3 = 1;
        }else {
            scpHallIcon3 = 0;
            cpHallIcon3 =  TimeHelper.timeToSecond(cpHallResult.get_$222().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$207().getEndtime())){
            cpHallIcon4 = 0;
            scpHallIcon4 = 1;
        }else {
            scpHallIcon4 = 0;
            cpHallIcon4 =  TimeHelper.timeToSecond(cpHallResult.get_$207().getEndtime(),systTime) +20;
        }
        if(Check.isNumericNull(cpHallResult.get_$407().getEndtime())){
            cpHallIcon5 = 0;
            scpHallIcon5 = 1;
        }else {
            scpHallIcon5 = 0;
            cpHallIcon5 = TimeHelper.timeToSecond(cpHallResult.get_$407().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$507().getEndtime())){
            cpHallIcon6 = 0;
            scpHallIcon6 = 1;
        }else {
            scpHallIcon6 = 0;
            cpHallIcon6 = TimeHelper.timeToSecond(cpHallResult.get_$507().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$607().getEndtime())){
            cpHallIcon7 = 0;
            scpHallIcon7 = 1;
        }else {
            scpHallIcon7 = 0;
            cpHallIcon7 = TimeHelper.timeToSecond(cpHallResult.get_$607().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$304().getEndtime())){
            cpHallIcon8 = 0;
            scpHallIcon8 = 1;
        }else {
            scpHallIcon8 = 8;
            cpHallIcon8 = TimeHelper.timeToSecond(cpHallResult.get_$304().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$159().getEndtime())){
            cpHallIcon9 = 0;
            scpHallIcon9 = 1;
        }else {
            scpHallIcon9 = 0;
            cpHallIcon9 = TimeHelper.timeToSecond(cpHallResult.get_$159().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$47().getEndtime())){
            cpHallIcon10 = 0;
            scpHallIcon10 = 1;
        }else {
            scpHallIcon10 = 0;
            cpHallIcon10 = TimeHelper.timeToSecond(cpHallResult.get_$47().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$3().getEndtime())){
            cpHallIcon11 = 0;
            scpHallIcon11 = 1;
        }else {
            scpHallIcon11 = 0;
            cpHallIcon11 = TimeHelper.timeToSecond(cpHallResult.get_$3().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$69().getEndtime())){
            cpHallIcon12 = 0;
            scpHallIcon12 = 1;
        }else {
            scpHallIcon12 = 0;
            cpHallIcon12 = TimeHelper.timeToSecond(cpHallResult.get_$69().getEndtime(),systTime)+20;
        }
        if(Check.isNumericNull(cpHallResult.get_$384().getEndtime())){
            cpHallIcon13 = 0;
            scpHallIcon13 = 1;
        }else {
            cpHallIcon13 = TimeHelper.timeToSecond(cpHallResult.get_$384().getEndtime(),systTime)+20 ;
            scpHallIcon13 = 0;
        }
        GameLog.log("最后的时间  "+cpHallIcon0+"|"+cpHallIcon1+"|"+cpHallIcon2+"|"+cpHallIcon3+"|"+cpHallIcon4+"|"+cpHallIcon5+"|"+cpHallIcon6+"|"+cpHallIcon7+"|"+cpHallIcon8+"|"+cpHallIcon9+"|"+cpHallIcon10+"|"+cpHallIcon11+"|"+cpHallIcon12+"|"+cpHallIcon13);
        hallPageGameAdapter.notifyDataSetChanged();
        cpHallList.scrollToPosition(0);
    }

    @Override
    public void postCPLeftInfoResult(CPLeftInfoResult cpLeftInfoResult) {
        GameLog.log("postCPLeftInfoResult "+cpLeftInfoResult.toString());
    }

    class HallPageGameAdapter extends AutoSizeRVAdapter<CPHallIcon> {
        private Context context;

        public HallPageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(final ViewHolder holder, final CPHallIcon data, final int position) {
            executorService.scheduleAtFixedRate(new Runnable() {
                @Override
                public void run() {
                    switch (position) {
                        case 0:
                            if (cpHallIcon0-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求0，，，，，，，，，，，，，，");
                                        onRequestData();
                                        if(scpHallIcon0==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon0));
                                    }
                                });
                            }
                            break;
                        case 1:
                            if (cpHallIcon1-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求1，，，，，，，，，，，，，，");
                                        onRequestData();
                                        if(scpHallIcon1==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon1) );
                                    }
                                });
                            }
                            break;
                        case 2:
                            if (cpHallIcon2-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求2，，，，，，，，，，，，，，");
                                        onRequestData();
                                        if(scpHallIcon2==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon2));
                                    }
                                });
                            }
                            break;
                        case 3:
                            if (cpHallIcon3-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon3==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求3，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon3 ));
                                    }
                                });
                            }
                            break;
                        case 4:
                            if (cpHallIcon4-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon4==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求4，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon4));
                                    }
                                });
                            }
                            break;
                        case 5:
                            if (cpHallIcon5-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon5==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求5，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon5));
                                    }
                                });
                            }
                            break;
                        case 6:
                            if (cpHallIcon6-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon6==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求6，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon6));
                                    }
                                });
                            }
                            break;
                        case 7:
                            if (cpHallIcon7-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon7==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求7，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon7 ));
                                    }
                                });
                            }
                            break;
                        case 8:
                            if (cpHallIcon8-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon0==8){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求8，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon8));
                                    }
                                });
                            }
                            break;
                        case 9:
                            if (cpHallIcon9-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon0==9){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求9，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon9));
                                    }
                                });
                            }
                            break;
                        case 10:
                            if (cpHallIcon10-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon10==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求10，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon10));
                                    }
                                });
                            }
                            break;
                        case 11:
                            if (cpHallIcon11-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon11==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求11，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon11));
                                    }
                                });
                            }
                            break;
                        case 12:
                            if (cpHallIcon12-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon12==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求12，，，，，，，，，，，，，，");
                                    }
                                });
                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon12));
                                    }
                                });
                            }
                            break;
                        case 13:
                            if (cpHallIcon13-- <= 0) {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        if(scpHallIcon13==1){
                                            holder.setText(R.id.cpHallItemTime, "未开盘");
                                        }else{
                                            holder.setText(R.id.cpHallItemTime, "开奖中");
                                        }
                                        GameLog.log("，，，，，，，，，，，，，重庆请求13，，，，，，，，，，，，，，");
                                    }
                                });

                            } else {
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime, TimeHelper.getTimeString(cpHallIcon13));
                                    }
                                });
                            }
                            break;
                    }
                }
            }, 0, 1000, TimeUnit.MILLISECONDS);
            holder.setText(R.id.cpHallItemName, data.getIconName());
            if (position == 2 || position == 3 || position == 6 || position == 7 || position == 10 || position == 11) {//(position & 1) != 0
                holder.setBackgroundRes(R.id.cpHallItemShow, R.color.cp_hall_cline);
            }else{
                holder.setBackgroundRes(R.id.cpHallItemShow, R.color.title_text);
            }
            holder.setBackgroundRes(R.id.cpHallItemIcon, data.getIconId());
            holder.setOnClickListener(R.id.cpHallItemShow, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    Intent intent  = new Intent(getContext(),CPOrderFragment.class);
                    intent.putExtra("gameId",data.getGameId());
                    intent.putExtra("gameName",data.getIconName());
                    startActivity(intent);
                    //EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList(data.getGameId()+"", data.getIconName(), "333"))));
                }
            });
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult) {
        GameLog.log("通过发送消息得的的数据" + personBalanceResult.getBalance_ag());
        agMoney = personBalanceResult.getBalance_ag();
        hgMoney = personBalanceResult.getBalance_hg();
    }

}

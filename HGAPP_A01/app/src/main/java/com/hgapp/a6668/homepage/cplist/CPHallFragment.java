package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.service.ServiceOnlineFragment;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.CPBottomBar;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration2;
import com.hgapp.a6668.common.widgets.MarqueeTextView;
import com.hgapp.a6668.common.widgets.RoundCornerImageView;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.NoticeResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.squareup.picasso.Picasso;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;
import me.yokeyword.sample.demo_wechat.ui.view.BottomBarTab;

public class CPHallFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpPageBulletin)
    MarqueeTextView cpPageBulletin;
    @BindView(R.id.cpHallList)
    RecyclerView cpHallList;
    private static List<CPHallIcon> cpGameList = new ArrayList<CPHallIcon>();
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    private ScheduledExecutorService executorService;
    private HallPageGameAdapter hallPageGameAdapter;
    private int cpHallIcon0, cpHallIcon1,cpHallIcon2,cpHallIcon3,cpHallIcon4,cpHallIcon5,cpHallIcon6,cpHallIcon7,cpHallIcon8,cpHallIcon9,cpHallIcon10,cpHallIcon11,cpHallIcon12,cpHallIcon13,cpHallIcon14;
    private int sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
    static {
        cpGameList.add(new CPHallIcon("北京赛车", R.mipmap.cp_bjsc,90));
        cpGameList.add(new CPHallIcon("极速飞艇", R.mipmap.cp_jsft,90));
        cpGameList.add(new CPHallIcon("重庆时时彩", R.mipmap.cp_cqssc,30));
        cpGameList.add(new CPHallIcon("极速赛车", R.mipmap.cp_jsfc,30));
        cpGameList.add(new CPHallIcon("六合彩", R.mipmap.cp_lhc,30));
        cpGameList.add(new CPHallIcon("分分彩", R.mipmap.cp_ffc,30));
        cpGameList.add(new CPHallIcon("PC蛋蛋", R.mipmap.cp_pcdd,30));
        cpGameList.add(new CPHallIcon("快乐十分", R.mipmap.cp_klsfc,30));
        cpGameList.add(new CPHallIcon("幸运农场", R.mipmap.cp_xync,30));
        cpGameList.add(new CPHallIcon("江苏快3", R.mipmap.cp_js,20));
        cpGameList.add(new CPHallIcon("更多", R.mipmap.cp_more,10));
        cpGameList.add(new CPHallIcon("幸运农场", R.mipmap.cp_xync,30));
        cpGameList.add(new CPHallIcon("江苏快3", R.mipmap.cp_js,20));
        cpGameList.add(new CPHallIcon("更多", R.mipmap.cp_more,10));
    }
    public static CPHallFragment newInstance(List<String> param1) {
        CPHallFragment fragment = new CPHallFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_hall;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
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
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),2, OrientationHelper.VERTICAL,false);
        cpHallList.setLayoutManager(gridLayoutManager);
        cpHallList.setHasFixedSize(true);
        cpHallList.setNestedScrollingEnabled(false);
        //cpHallList.addItemDecoration(new GridRvItemDecoration(getContext()));
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService = Executors.newScheduledThreadPool(14);
        cpHallIcon0 = 10;
        cpHallIcon1 = 90;
        cpHallIcon2 = 90;
        cpHallIcon3 = 10;
        cpHallIcon4 = 90;
        cpHallIcon5 = 30;
        cpHallIcon6 = 90;
        cpHallIcon7 = 50;
        cpHallIcon8 = 90;
        cpHallIcon9 = 70;
        cpHallIcon10 = 90;
        cpHallIcon11 = 20;
        cpHallIcon12 = 90;
        cpHallIcon13 = 50;
        cpHallIcon14 = 90;
        hallPageGameAdapter = null;
        hallPageGameAdapter = new HallPageGameAdapter(getContext(),R.layout.item_cp_hall,cpGameList);
        cpHallList.setAdapter(hallPageGameAdapter);
        hallPageGameAdapter.notifyDataSetChanged();
    }

    private synchronized void onRequestData(){
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        GameLog.log("=================================================");
        sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        executorService = Executors.newScheduledThreadPool(14);
        cpHallIcon0 = 10;
        cpHallIcon1 = 190;
        cpHallIcon2 = 90;
        cpHallIcon3 = 10;
        cpHallIcon4 = 90;
        cpHallIcon5 = 130;
        cpHallIcon6 = 90;
        cpHallIcon7 = 150;
        cpHallIcon8 = 90;
        cpHallIcon9 = 70;
        cpHallIcon10 = 190;
        cpHallIcon11 = 20;
        cpHallIcon12 = 190;
        cpHallIcon13 = 50;
        cpHallIcon14 = 90;
        hallPageGameAdapter = null;
        hallPageGameAdapter = new HallPageGameAdapter(getContext(),R.layout.item_cp_hall,cpGameList);
        cpHallList.setAdapter(hallPageGameAdapter);
        hallPageGameAdapter.notifyDataSetChanged();
    }

    class HallPageGameAdapter extends AutoSizeRVAdapter<CPHallIcon> {
        private Context context;

        public HallPageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(final ViewHolder holder,final CPHallIcon data, final int position) {
            executorService.scheduleAtFixedRate(new Runnable() {
                @Override
                public void run() {
                    switch (position){
                        case 0:
                            if(cpHallIcon0--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求0，，，，，，，，，，，，，，");
                                        onRequestData();
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon0+"");
                                    }
                                });
                            }
                            break;
                        case 1:
                            if(cpHallIcon1--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求1，，，，，，，，，，，，，，");
                                        onRequestData();
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon1+"");
                                    }
                                });
                            }
                            break;
                        case 2:
                            if(cpHallIcon2--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        GameLog.log("，，，，，，，，，，，，，重庆请求2，，，，，，，，，，，，，，");
                                        onRequestData();
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon2+"");
                                    }
                                });
                            }
                            break;
                        case 3:
                            if(cpHallIcon3--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求3，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon3+"");
                                    }
                                });
                            }
                            break;
                        case 4:
                            if(cpHallIcon4--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求4，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon4+"");
                                    }
                                });
                            }
                            break;
                        case 5:
                            if(cpHallIcon5--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求5，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon5+"");
                                    }
                                });
                            }
                            break;
                        case 6:
                            if(cpHallIcon6--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求6，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon6+"");
                                    }
                                });
                            }
                            break;
                        case 7:
                            if(cpHallIcon7--<= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求7，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon7+"");
                                    }
                                });
                            }
                            break;
                        case 8:
                            if(cpHallIcon8-- <= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求8，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon8+"");
                                    }
                                });
                            }
                            break;
                        case 9:
                            if(cpHallIcon9-- <= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求9，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon9+"");
                                    }
                                });
                            }
                            break;
                        case 10:
                            if(cpHallIcon10-- <= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求10，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon10+"");
                                    }
                                });
                            }
                            break;
                        case 11:
                            if(cpHallIcon11-- <= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求11，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon11+"");
                                    }
                                });
                            }
                            break;
                        case 12:
                            if(cpHallIcon12-- <= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求12，，，，，，，，，，，，，，");
                                    }
                                });
                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon12+"");
                                    }
                                });
                            }
                            break;
                        case 13:
                            if(cpHallIcon13-- <= 0){
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        onRequestData();
                                        GameLog.log("，，，，，，，，，，，，，重庆请求13，，，，，，，，，，，，，，");
                                    }
                                });

                            }else{
                                cpHallList.post(new Runnable() {
                                    @Override
                                    public void run() {
                                        holder.setText(R.id.cpHallItemTime,cpHallIcon13+"");
                                    }
                                });
                            }
                            break;
                    }
                }
            },0, 1000, TimeUnit.MILLISECONDS);
            holder.setText(R.id.cpHallItemName, data.getIconName());
            if(position==2||position==3||position==6||position==7||position==10||position==11){//(position & 1) != 0
                holder.setBackgroundRes(R.id.cpHallItemShow, R.color.cp_hall_line);
            }
            holder.setBackgroundRes(R.id.cpHallItemIcon, data.getIconId());
            holder.setOnClickListener(R.id.cpHallItemShow, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("111", "222", "333"))));
                }
            });
        }
    }


    @Override
    public void showMessage(String message) {
        super.showMessage(message);
    }

    @Override
    public void setPresenter(AGListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postGoPlayGameResult(AGGameLoginResult agGameLoginResult) {

    }

    @Override
    public void postCheckAgLiveAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCheckAgGameAccountResult(CheckAgLiveResult checkAgLiveResult) {
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("用户的真人账户：" + personBalance.getBalance_ag());
    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表：" + agLiveResult);
    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    class AGGameAdapter extends AutoSizeRVAdapter<AGLiveResult> {
        private Context context;

        public AGGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final AGLiveResult data, final int position) {
            holder.setText(R.id.tv_item_game_name, data.getName());
            RoundCornerImageView roundCornerImageView = (RoundCornerImageView) holder.getView(R.id.iv_item_game_icon);
            roundCornerImageView.onCornerAll(roundCornerImageView);
            String ur = Client.baseUrl().substring(0, Client.baseUrl().length() - 1) + data.getGameurl();
            //GameLog.log("图片地址："+ur);
            Picasso.with(context)
                    .load(ur)
                    .placeholder(null)
                    .into(roundCornerImageView);
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    dzTitileName = data.getName();
                    presenter.postGoPlayGame("", data.getGameid());
                }
            });
        }
    }

    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult) {
        GameLog.log("通过发送消息得的的数据" + personBalanceResult.getBalance_ag());
        agMoney = personBalanceResult.getBalance_ag();
        hgMoney = personBalanceResult.getBalance_hg();
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }
}

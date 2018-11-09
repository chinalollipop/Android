package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.RelativeLayout;
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
import com.hgapp.a6668.common.util.GameShipHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.CPBottomBar;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration2;
import com.hgapp.a6668.common.widgets.MarqueeTextView;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.common.widgets.RecyclerViewItemDecoration;
import com.hgapp.a6668.common.widgets.RoundCornerImageView;
import com.hgapp.a6668.common.widgets.SimpleDividerItemDecoration;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.NoticeResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.HomepageFragment;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.aglist.agchange.AGPlatformDialog;
import com.hgapp.a6668.homepage.aglist.playgame.XPlayGameActivity;
import com.hgapp.a6668.login.fastlogin.LoginFragment;
import com.hgapp.common.util.Check;
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
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;
import me.yokeyword.sample.demo_wechat.ui.view.BottomBarTab;

public class CPListFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpBottomBar)
    CPBottomBar cpBottomBar;
    @BindView(R.id.cpPageBulletin)
    MarqueeTextView cpPageBulletin;/*
    @BindView(R.id.cpGameList)
    RecyclerView cpList;
    private static List<HomePageIcon> cpGameList = new ArrayList<HomePageIcon>();*/
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private String agMoney,hgMoney;
    private String titleName = "";
    private String dzTitileName ="";
    /*static {
        cpGameList.add(new HomePageIcon("北京赛车", R.mipmap.cp_bjsc));
        cpGameList.add(new HomePageIcon("极速飞艇", R.mipmap.cp_jsft));
        cpGameList.add(new HomePageIcon("重庆时时彩", R.mipmap.cp_cqssc));
        cpGameList.add(new HomePageIcon("极速赛车", R.mipmap.cp_jsfc));
        cpGameList.add(new HomePageIcon("六合彩", R.mipmap.cp_lhc));
        cpGameList.add(new HomePageIcon("分分彩", R.mipmap.cp_ffc));
        cpGameList.add(new HomePageIcon("PC蛋蛋", R.mipmap.cp_pcdd));
        cpGameList.add(new HomePageIcon("快乐十分", R.mipmap.cp_klsfc));
        cpGameList.add(new HomePageIcon("幸运农场", R.mipmap.cp_xync));
        cpGameList.add(new HomePageIcon("江苏快3", R.mipmap.cp_js));
        cpGameList.add(new HomePageIcon("更多", R.mipmap.cp_more));
    }*/
    public static CPListFragment newInstance(List<String> param1) {
        CPListFragment fragment = new CPListFragment();
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
        return R.layout.fragment_cp_list;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        cpBottomBar
                .addItem(new BottomBarTab(_mActivity, R.drawable.cp_tab_home, getString(R.string.str_title_homepage)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.cp_tab_record, getString(R.string.cp_title_record)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.cp_tab_me, getString(R.string.str_title_person)))
                .addItem(new BottomBarTab(_mActivity, R.drawable.cp_tab_service, getString(R.string.str_title_withdraw)));
        //cpBottomBar.getItem(1).setUnreadCount(9);
        cpBottomBar.setOnTabSelectedListener(new CPBottomBar.OnTabSelectedListener() {
            @Override
            public void onTabSelected(int position, int prePosition) {
                switch (position){
                    case 0:
                        GameLog.log("当前选择的事");
                        pop();
                        break;
                    case 1:
                        break;
                    case 2:
                        break;
                    case 3:
                        EventBus.getDefault().post(new StartBrotherEvent(ServiceOnlineFragment.newInstance(), SupportFragment.SINGLETASK));
                        break;
                }
            }

            @Override
            public void onTabUnselected(int position) {
                GameLog.log("++++++++++++++++++++++++++ "+position);
            }

            @Override
            public void onTabReselected(int position) {
                if(position==0){
                    pop();
                }
                GameLog.log("----------------------------- "+position);
            }
        });
        NoticeResult noticeResult = JSON.parseObject(ACache.get(getContext()).getAsString(HGConstant.USERNAME_HOME_NOTICE), NoticeResult.class);
        if(!Check.isNull(noticeResult)){
            List<String> stringList = new ArrayList<String>();
            int size =noticeResult.getData().size();
            for(int i=0;i<size;++i){
                stringList.add(noticeResult.getData().get(i).getNotice());
            }
            cpPageBulletin.setContentList(stringList);
        }
        /*cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.VERTICAL,5,getContext().getColor(R.color.textview_normal),8));
        cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.HORIZONTAL,5,getContext().getColor(R.color.textview_normal),8));*/
       /* GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),3, OrientationHelper.VERTICAL,false);
        cpList.setLayoutManager(gridLayoutManager);
        cpList.setHasFixedSize(true);
        cpList.setNestedScrollingEnabled(false);
        cpList.addItemDecoration(new GridRvItemDecoration2(getContext()));
        cpList.setAdapter(new LotteryPageGameAdapter(getContext(),R.layout.item_cp_hall,cpGameList));*/
    }
    class LotteryPageGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;
        public LotteryPageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name,data.getIconName());
            holder.setBackgroundRes(R.id.iv_item_game_icon,data.getIconId());
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    EventBus.getDefault().post(new StartBrotherEvent(CPOrderFragment.newInstance(Arrays.asList("111","222","333"))));
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
        GameLog.log("用户的真人账户："+personBalance.getBalance_ag());
    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表："+agLiveResult);
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
            holder.setText(R.id.tv_item_game_name,data.getName());
            RoundCornerImageView roundCornerImageView =      (RoundCornerImageView) holder.getView(R.id.iv_item_game_icon);
            roundCornerImageView.onCornerAll(roundCornerImageView);
            String ur  = Client.baseUrl().substring(0,Client.baseUrl().length()-1)+data.getGameurl();
            //GameLog.log("图片地址："+ur);
            Picasso.with(context)
                    .load(ur)
                    .placeholder(null)
                    .into(roundCornerImageView);
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    dzTitileName = data.getName();
                    presenter.postGoPlayGame("",data.getGameid());
                }
            });
        }
    }
    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult){
        GameLog.log("通过发送消息得的的数据"+personBalanceResult.getBalance_ag());
        agMoney = personBalanceResult.getBalance_ag();
        hgMoney = personBalanceResult.getBalance_hg();
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }
}

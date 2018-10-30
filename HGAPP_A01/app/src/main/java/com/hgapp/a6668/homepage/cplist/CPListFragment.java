package com.hgapp.a6668.homepage.cplist;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.util.GameShipHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.common.widgets.RoundCornerImageView;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.HomepageFragment;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.aglist.agchange.AGPlatformDialog;
import com.hgapp.a6668.homepage.aglist.playgame.XPlayGameActivity;
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
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class CPListFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpTitleBack)
    NTitleBar cpTitleBack;
    @BindView(R.id.cpGameList)
    RecyclerView cpList;
    private static List<HomePageIcon> cpGameList = new ArrayList<HomePageIcon>();
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private String agMoney,hgMoney;
    private String titleName = "";
    private String dzTitileName ="";
    static {
        cpGameList.add(new HomePageIcon("北京赛车", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("幸运飞艇", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("重庆时时彩", R.mipmap.home_vrcp));
        cpGameList.add(new HomePageIcon("极速赛车", R.mipmap.home_qipai));
        cpGameList.add(new HomePageIcon("极速飞艇", R.mipmap.home_hgty));
        cpGameList.add(new HomePageIcon("PC蛋蛋", R.mipmap.home_ag));
        cpGameList.add(new HomePageIcon("分分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("三分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("五分彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("广东快乐十分", R.mipmap.home_vrcp));
        cpGameList.add(new HomePageIcon("幸运农场", R.mipmap.home_qipai));
        cpGameList.add(new HomePageIcon("极速时时彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("香港六合彩", R.mipmap.home_lhj));
        cpGameList.add(new HomePageIcon("江苏快三", R.mipmap.home_lhj));
    }
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
        cpTitleBack.setMoreText(userMoney);
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),3, OrientationHelper.VERTICAL,false);
        cpList.setLayoutManager(gridLayoutManager);
        cpList.setHasFixedSize(true);
        cpList.setNestedScrollingEnabled(false);
        cpList.setAdapter(new HomaPageGameAdapter(getContext(),R.layout.item_game_hall,cpGameList));
        cpList.scrollToPosition(0);
    }

    class HomaPageGameAdapter extends com.hgapp.a6668.common.adapters.AutoSizeRVAdapter<HomePageIcon> {
        private Context context;
        public HomaPageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name,data.getIconName());
            RoundCornerImageView roundCornerImageView =      (RoundCornerImageView) holder.getView(R.id.iv_item_game_icon);
            roundCornerImageView.onCornerAll(roundCornerImageView);
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

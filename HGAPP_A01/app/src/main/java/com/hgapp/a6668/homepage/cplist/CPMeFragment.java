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
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.util.ArrayListHelper;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration2;
import com.hgapp.a6668.common.widgets.RoundCornerImageView;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.homepage.HomePageIcon;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.common.util.GameLog;
import com.squareup.picasso.Picasso;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class CPMeFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.cpMeList)
    RecyclerView cpMeList;
    private static List<HomePageIcon> cpGameList = new ArrayList<HomePageIcon>();
    @BindView(R.id.backHome)
    ImageView backHome;
    @BindView(R.id.userLogout)
    TextView userLogout;
    @BindView(R.id.cpUserName)
    TextView cpUserName;
    @BindView(R.id.cpUserMoney)
    TextView cpUserMoney;
    @BindView(R.id.userMoneyRefresh)
    ImageView userMoneyRefresh;
    private String userName, userMoney, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    Animation animation ;
    static {
        cpGameList.add(new HomePageIcon("个人资料", R.mipmap.cp_profile));
        cpGameList.add(new HomePageIcon("登录密码", R.mipmap.cp_modifypwd));
        cpGameList.add(new HomePageIcon("信息中心", R.mipmap.cp_message));
        cpGameList.add(new HomePageIcon("资金管理", R.mipmap.cp_funds));
        cpGameList.add(new HomePageIcon("取款密码", R.mipmap.cp_transfer));
        cpGameList.add(new HomePageIcon("银行卡", R.mipmap.cp_bankaccount));
        cpGameList.add(new HomePageIcon("今日已结", R.mipmap.cp_record));
        cpGameList.add(new HomePageIcon("今日下注", R.mipmap.cp_record));
        cpGameList.add(new HomePageIcon("在线客服", R.mipmap.cp_service));
    }

    public static CPMeFragment newInstance(List<String> param1) {
        CPMeFragment fragment = new CPMeFragment();
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
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_me;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        animation = AnimationUtils.loadAnimation(getContext(),R.anim.rotate_clockwise);
        /*cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.VERTICAL,5,getContext().getColor(R.color.textview_normal),8));
        cpList.addItemDecoration(new RecyclerViewItemDecoration(LinearLayoutManager.HORIZONTAL,5,getContext().getColor(R.color.textview_normal),8));*/
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
        cpMeList.setLayoutManager(gridLayoutManager);
        cpMeList.setHasFixedSize(true);
        cpMeList.setNestedScrollingEnabled(false);
        cpMeList.addItemDecoration(new GridRvItemDecoration2(getContext()));
        cpMeList.setAdapter(new MePageGameAdapter(getContext(), R.layout.item_cp_me, cpGameList));
    }

    @OnClick({R.id.backHome, R.id.userLogout, R.id.userMoneyRefresh})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.backHome:
                pop();
                break;
            case R.id.userLogout:
                ///退出
                break;
            case R.id.userMoneyRefresh:
                userMoneyRefresh.startAnimation(animation);
                //刷新用户余额

                break;
        }
    }

    class MePageGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public MePageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setBackgroundRes(R.id.iv_item_game_icon, data.getIconId());
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    startActivity(new Intent(getContext(),CPOrderFragment.class));
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

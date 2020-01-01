package com.nhg.xhg.homepage.cplist.me;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.TextView;

import com.nhg.xhg.CPInjections;
import com.nhg.xhg.R;
import com.nhg.xhg.base.BaseActivity2;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.common.adapters.AutoSizeRVAdapter;
import com.nhg.xhg.common.util.ACache;
import com.nhg.xhg.common.util.DateHelper;
import com.nhg.xhg.common.util.GameShipHelper;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.common.widgets.GridRvItemDecoration2;
import com.nhg.xhg.data.CPHallResult;
import com.nhg.xhg.data.CPLeftInfoResult;
import com.nhg.xhg.homepage.HomePageIcon;
import com.nhg.xhg.homepage.cplist.bet.betrecords.CPBetRecordsFragment;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betlistrecords.CPBetListRecordsFragment;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betnow.CPBetNowFragment;
import com.nhg.xhg.homepage.cplist.hall.CPHallListContract;
import com.nhg.xhg.homepage.cplist.lottery.CPLotteryListFragment;
import com.nhg.xhg.homepage.cplist.role.CPServiceActivity;
import com.nhg.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.GregorianCalendar;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class CPMeFragment extends BaseActivity2 implements CPHallListContract.View {

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
    CPHallListContract.Presenter presenter;
    private String agMoney, hgMoney;
    private String titleName = "";
    private String dzTitileName = "";
    Animation animation ;
    static {
        cpGameList.add(new HomePageIcon("即时注单", R.mipmap.cp_profile,1));
        cpGameList.add(new HomePageIcon("今日已结", R.mipmap.cp_modifypwd,2));
        cpGameList.add(new HomePageIcon("下注记录", R.mipmap.cp_message,3));
        cpGameList.add(new HomePageIcon("开奖结果", R.mipmap.cp_funds,4));
        cpGameList.add(new HomePageIcon("今日输赢", R.mipmap.cp_transfer,5));
        cpGameList.add(new HomePageIcon("在线客服", R.mipmap.cp_service,6));
    }

   /* public static CPMeFragment newInstance(List<String> param1) {
        CPMeFragment fragment = new CPMeFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }*/

    @Override
    public void onCreate(Bundle savedInstanceState) {
        CPInjections.inject(this,null);
        super.onCreate(savedInstanceState);
       /* if (getArguments() != null) {
            userName = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);
        }*/
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_cp_me;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        presenter.postCPLeftInfo("");
        GregorianCalendar ca = new GregorianCalendar();
        int time =  ca.get(GregorianCalendar.AM_PM);
        GameLog.log("当前的时间是 "+(time==0?"上午":"下午"));
        cpUserName.setText((time==0?"上午好！":"下午好！")+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_USERNAME));
        animation = AnimationUtils.loadAnimation(getContext(),R.anim.rotate_clockwise);
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
                finish();
                break;
            case R.id.userLogout:
                ///退出
                break;
            case R.id.userMoneyRefresh:
                userMoneyRefresh.startAnimation(animation);
                //刷新用户余额
                presenter.postCPLeftInfo("");
                break;
        }
    }

    @Override
    public void postCPHallListResult(CPHallResult cpHallResult) {

    }

    @Override
    public void postCPLeftInfoResult(CPLeftInfoResult cpLeftInfoResult) {
        userMoneyRefresh.clearAnimation();
        cpUserMoney.setText("用户余额："+GameShipHelper.formatMoney(cpLeftInfoResult.getMoney()));
    }

    @Override
    public void setPresenter(CPHallListContract.Presenter presenter) {
        this.presenter = presenter;
    }

    class MePageGameAdapter extends AutoSizeRVAdapter<HomePageIcon> {
        private Context context;

        public MePageGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final HomePageIcon data, final int position) {
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setBackgroundRes(R.id.iv_item_game_icon, data.getIconId());
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //onHomeGameItemClick(position);
                    Intent intent = null;
                    switch (data.getId()){
                        case 1:
                            intent = new Intent(getContext(),CPBetNowFragment.class);
                            intent.putExtra("gameForm","now");
                            intent.putExtra("gameTime",DateHelper.getToday());
                            break;
                        case 2:
                        case 5:
                            intent = new Intent(getContext(),CPBetListRecordsFragment.class);
                            intent.putExtra("gameForm","today");
                            intent.putExtra("gameTime",DateHelper.getToday());
                            break;
                        case 3:
                            intent = new Intent(getContext(),CPBetRecordsFragment.class);
                            intent.putExtra("gameId","51");
                            intent.putExtra("gameName","北京赛车");
                            break;
                        case 4:
                            intent = new Intent(getContext(),CPLotteryListFragment.class);
                            intent.putExtra("gameId","51");
                            intent.putExtra("gameName","北京赛车");
                            break;
                        case 6:
                            intent = new Intent(getContext(),CPServiceActivity.class);
                            intent.putExtra("gameId","51");
                            intent.putExtra("gameName",titleName);
                            break;
                    }
                    startActivity(intent);
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
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }
}

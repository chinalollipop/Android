package com.hgapp.bet365.homepage.cplist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;

import com.hgapp.bet365.Injections;
import com.hgapp.bet365.R;
import com.hgapp.bet365.base.HGBaseFragment;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.common.adapters.AutoSizeRVAdapter;
import com.hgapp.bet365.common.util.ArrayListHelper;
import com.hgapp.bet365.data.AGGameLoginResult;
import com.hgapp.bet365.data.AGLiveResult;
import com.hgapp.bet365.data.CheckAgLiveResult;
import com.hgapp.bet365.data.PersonBalanceResult;
import com.hgapp.bet365.homepage.aglist.AGListContract;
import com.hgapp.bet365.homepage.cplist.events.CPIcon;
import com.hgapp.bet365.homepage.cplist.events.LeftMenuEvents;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;

public class CPLeftMenuFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.rvContentOrder)
    RecyclerView rvContentOrder;
    AGListContract.Presenter presenter;
    private String userName, userMoney, fshowtype;
    private static List<CPOrderAllResult> allResultList = new ArrayList<CPOrderAllResult>();
    private static List<CPIcon> cpGameList = new ArrayList<CPIcon>();
    static {
        //注意事项  每次投注成功之后都需要刷新一下用户的金额 ，且是全局的金额都需要变动  需要发送一下全部的 Money  message 去
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
        cpGameList.add(new CPIcon("返回大厅", R.mipmap.home_hgty,0));
        cpGameList.add(new CPIcon("北京赛车", R.mipmap.home_hgty,51));
        cpGameList.add(new CPIcon("欢乐生肖", R.mipmap.home_vrcp,2));
        cpGameList.add(new CPIcon("极速赛车", R.mipmap.home_vrcp,189));
        cpGameList.add(new CPIcon("极速飞艇", R.mipmap.home_hgty,222));
        cpGameList.add(new CPIcon("分分彩", R.mipmap.home_vrcp,207));
        cpGameList.add(new CPIcon("三分彩", R.mipmap.home_vrcp,407));
        cpGameList.add(new CPIcon("五分彩", R.mipmap.home_vrcp,507));
        cpGameList.add(new CPIcon("腾讯二分彩", R.mipmap.home_vrcp,607));
        cpGameList.add(new CPIcon("PC蛋蛋", R.mipmap.home_ag,304));
        cpGameList.add(new CPIcon("江苏快3", R.mipmap.home_ag,159));
        cpGameList.add(new CPIcon("幸运农场", R.mipmap.home_ag,47));
        cpGameList.add(new CPIcon("快乐十分", R.mipmap.home_vrcp,3));
        cpGameList.add(new CPIcon("香港六合彩", R.mipmap.home_vrcp,69));
        cpGameList.add(new CPIcon("极速快三", R.mipmap.home_vrcp,384));
        cpGameList.add(new CPIcon("幸运飞艇", R.mipmap.cp_xyft,168));
    }
    public static CPLeftMenuFragment newInstance(List<String> param1) {
        CPLeftMenuFragment fragment = new CPLeftMenuFragment();
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
        return R.layout.fragment_cp_left_menu;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        LinearLayoutManager linearLayoutManagerRight = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        rvContentOrder.setLayoutManager(linearLayoutManagerRight);
        rvContentOrder.setHasFixedSize(true);
        rvContentOrder.setNestedScrollingEnabled(false);
        CPLeftMenuGameAdapter cpOrederListRightGameAdapter = new CPLeftMenuGameAdapter(getContext(), R.layout.item_cp_order_list, cpGameList);
        rvContentOrder.setAdapter(cpOrederListRightGameAdapter);
    }


    class CPLeftMenuGameAdapter extends AutoSizeRVAdapter<CPIcon> {
        private Context context;

        public CPLeftMenuGameAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final CPIcon data, final int position) {
            /*if(position==0){
                TextView tv =  holder.getView(R.id.tv_item_game_name);
                tv.setGravity(Gravity.CENTER);
            }*/
            holder.setText(R.id.tv_item_game_name, data.getIconName());
            holder.setOnClickListener(R.id.tv_item_game_name, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(position==0){
                        finish();
                        return;
                    }else {
                        EventBus.getDefault().post(new LeftMenuEvents(data.getIconName(),data.getGameId()+""));
                        return;
                    }
                }
            });
        }
    }




    @Override
    public void onDestroyView() {
        super.onDestroyView();
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
    public void postMGPersonBalanceResult(PersonBalanceResult personBalance) {

    }

    @Override
    public void postCQPersonBalanceResult(PersonBalanceResult personBalance) {

    }

    @Override
    public void postMWPersonBalanceResult(PersonBalanceResult personBalance) {

    }

    @Override
    public void postFGPersonBalanceResult(PersonBalanceResult personBalance) {

    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表：" + agLiveResult);
    }

    @Override
    public void postsMessageGameResult(String message) {

    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }


    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult) {
        GameLog.log("通过发送消息得的的数据" + personBalanceResult.getBalance_ag());
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }

}

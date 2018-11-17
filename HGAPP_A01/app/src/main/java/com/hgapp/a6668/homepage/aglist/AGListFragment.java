package com.hgapp.a6668.homepage.aglist;

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
import com.hgapp.a6668.homepage.UserMoneyEvent;
import com.hgapp.a6668.homepage.aglist.agchange.AGPlatformDialog;
import com.hgapp.a6668.homepage.aglist.playgame.XPlayGameActivity;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.squareup.picasso.Picasso;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

public class AGListFragment extends HGBaseFragment implements AGListContract.View {

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.agTitleBack)
    NTitleBar agTitleBack;
    @BindView(R.id.agUserMoneyShow)
    RelativeLayout agUserMoneyShow;
    @BindView(R.id.agUserMoney)
    TextView agUserMoney;
    @BindView(R.id.agUserMoneyChange)
    TextView agUserMoneyChange;
    @BindView(R.id.agLiveList)
    RecyclerView agLiveList;
    /*@BindView(R.id.agGameList)
    RecyclerView agGameList;*/
    private String FStype, Mtype, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private String agMoney,hgMoney;
    private String titleName = "";
    private String dzTitileName ="";

    public static AGListFragment newInstance(List<String> param1) {
        AGListFragment fragment = new AGListFragment();
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
            FStype = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            Mtype = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_ag_list;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        agTitleBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                pop();
            }
        });
        EventBus.getDefault().register(this);
        String userState = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT));
        GameLog.log("用户登录的状态 ：["+userState+"]");
        if(!Check.isNull(userState)&&userState.equals("1")){
            presenter.postPersonBalance("","");
            //presenter.postCheckAgAccount("","","check_game_account");
            agUserMoneyShow.setVisibility(View.VISIBLE);
        }else{
            agUserMoneyShow.setVisibility(View.GONE);
        }

        if("live".equals(fshowtype)){
            //presenter.postCheckAgLiveAccount("");
            presenter.postAGGameList("","","gamelist_zhenren");
            titleName = "真人额度：";
            //onAgLiveTestData();
            agLiveList.setVisibility(View.VISIBLE);
            //agGameList.setVisibility(View.GONE);
        }else{
            //presenter.postCheckAgGameAccount("");
            presenter.postAGGameList("","","gamelist_dianzi");
            titleName = "电子额度：";
            //onAgGameTestData();
            agLiveList.setVisibility(View.VISIBLE);
            //agGameList.setVisibility(View.VISIBLE);
        }
       // presenter.postCreateAgAccount("","","cga");

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

        GameLog.log("游戏弟弟值："+agGameLoginResult.getUrl());

        //EventBus.getDefault().post(new StartBrotherEvent(XPlayGameFragment.newInstance(dzTitileName,agGameLoginResult.getUrl(),"1"), SupportFragment.SINGLETASK));
        Intent intent = new Intent(getContext(),XPlayGameActivity.class);
        intent.putExtra("url",agGameLoginResult.getUrl());
        intent.putExtra("gameCnName",dzTitileName);
        intent.putExtra("hidetitlebar",false);
        getActivity().startActivity(intent);
    }

    @Override
    public void postCheckAgLiveAccountResult(CheckAgLiveResult checkAgLiveResult) {

        if(!"1".equals(checkAgLiveResult.getIs_registered())){
            presenter.postCreateAgAccount("","","cga");
        }
    }

    @Override
    public void postCheckAgGameAccountResult(CheckAgLiveResult checkAgLiveResult) {
        if(!"1".equals(checkAgLiveResult.getIs_registered())){
            presenter.postCreateAgAccount("","","cga");
        }
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("用户的真人账户："+personBalance.getBalance_ag());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getBalance_ag()));
    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表："+agLiveResult);

        if("live".equals(fshowtype)){
            GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),2, OrientationHelper.VERTICAL,false);
            agLiveList.setLayoutManager(gridLayoutManager);
            agLiveList.setAdapter(new AGLiveAdapter(getContext(),R.layout.item_ag_live,agLiveResult));
        }else{
            GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),4, OrientationHelper.VERTICAL,false);
            agLiveList.setLayoutManager(gridLayoutManager);
            agLiveList.setAdapter(new AGGameAdapter(getContext(),R.layout.item_ag_game,agLiveResult));
        }
    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @OnClick({R.id.agUserMoneyChange})
    public void onViewClicked(View view ) {
        AGPlatformDialog.newInstance(agMoney,hgMoney).show(getFragmentManager());
    }

    class AGLiveAdapter extends AutoSizeRVAdapter<AGLiveResult> {
        private Context context;
        public AGLiveAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final AGLiveResult data, final int position) {
            //holder.setText(R.id.tv_item_game_name,data.getName());
            RoundCornerImageView roundCornerImageView =      (RoundCornerImageView) holder.getView(R.id.iv_item_game_icon);
            roundCornerImageView.onCornerAll(roundCornerImageView);
            switch (position){
                case 0:
                    roundCornerImageView.setBackgroundResource(R.drawable.game_one);
                    break;
                case 1:
                    roundCornerImageView.setBackgroundResource(R.drawable.game_four);
                    break;
                case 2:
                    roundCornerImageView.setBackgroundResource(R.drawable.game_two);
                    break;
                case 3:
                    roundCornerImageView.setBackgroundResource(R.drawable.game_three);
                    break;
            }
            holder.setOnClickListener(R.id.ll_home_main_show, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    dzTitileName = data.getName();
                    presenter.postGoPlayGame("",data.getGameid());
                }
            });
        }
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
        EventBus.getDefault().post(new UserMoneyEvent(GameShipHelper.formatMoney(hgMoney)));
        agUserMoney.setText(titleName+GameShipHelper.formatMoney(personBalanceResult.getBalance_ag()));
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }
}

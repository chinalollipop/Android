package com.sunapp.bloc.homepage.aglist;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.design.widget.TabLayout;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.sunapp.bloc.Injections;
import com.sunapp.bloc.R;
import com.sunapp.bloc.base.HGBaseFragment;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.common.adapters.AutoSizeRVAdapter;
import com.sunapp.bloc.common.http.Client;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.ArrayListHelper;
import com.sunapp.bloc.common.util.GameShipHelper;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.widgets.NTitleBar;
import com.sunapp.bloc.common.widgets.RoundCornerImageView;
import com.sunapp.bloc.data.AGGameLoginResult;
import com.sunapp.bloc.data.AGLiveResult;
import com.sunapp.bloc.data.CheckAgLiveResult;
import com.sunapp.bloc.data.PersonBalanceResult;
import com.sunapp.bloc.homepage.UserMoneyEvent;
import com.sunapp.bloc.homepage.aglist.agchange.AGPlatformDialog;
import com.sunapp.bloc.homepage.aglist.playgame.XPlayGameActivity;
import com.sunapp.common.util.Check;
import com.sunapp.common.util.GameLog;
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
    @BindView(R.id.agListAg)
    TextView agListAg;
    @BindView(R.id.agListMg)
    TextView agListMg;
    @BindView(R.id.agListCQ)
    TextView agListCQ;
    @BindView(R.id.agListMW)
    TextView agListMW;
    @BindView(R.id.agUserMoney)
    TextView agUserMoney;
    @BindView(R.id.mwDz)
    ImageView mwDz;
    @BindView(R.id.agUserMoneyChange)
    TextView agUserMoneyChange;
    @BindView(R.id.agLiveList)
    RecyclerView agLiveList;
    private String FStype, Mtype, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private String agMoney,hgMoney;
    private String titleName = "";
    private String dzTitileName ="";
    private String gameId;
    int positionl;
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
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);// 用以判断是电子还是真人 [live真人] 【game电子】
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
        agTitleBack.setMoreText(Mtype);
        EventBus.getDefault().register(this);
        String userState = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT));
        GameLog.log("用户登录的状态 ：["+userState+"]"+ fshowtype);
        if(!Check.isNull(userState)&&userState.equals("1")){
            presenter.postPersonBalance("","");
            //presenter.postCheckAgAccount("","","check_game_account");
            agUserMoneyShow.setVisibility(View.VISIBLE);
        }else{
            agUserMoneyShow.setVisibility(View.GONE);
        }
        if("cq".equals(fshowtype)){
            agListCQ.performClick();
            presenter.postCQGameList("","","");
            presenter.postCQPersonBalance("","");
            titleName = "电子额度：";
        }else if("mw".equals(fshowtype)){
            agListMW.performClick();
            presenter.postMWPersonBalance("","");
            titleName = "电子额度：";
        }else if("live".equals(fshowtype)){
            presenter.postAGGameList("","","gamelist_zhenren");
            titleName = "真人额度：";
        }else if("game".equals(fshowtype)){
            agListAg.performClick();
            fshowtype ="game";
            presenter.postAGGameList("","","gamelist_dianzi");
            titleName = "电子额度：";
        }else{
            agListMg.performClick();
            fshowtype ="mg";
            titleName = "电子额度：";
            presenter.postMGPersonBalance("","");
            presenter.postMGGameList("","","");
        }

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        agLiveList.setVisibility(View.GONE);
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
        if("mw".equals(fshowtype)){
            Intent intent = new Intent(Intent.ACTION_VIEW);
            intent.setData(Uri.parse(agGameLoginResult.getToUrl()));
            startActivity(intent);
            return;
        }
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
    public void postMGPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("MG用户的真人账户："+personBalance.getMg_balance());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getMg_balance()));
    }

    @Override
    public void postCQPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("postCQPersonBalanceResult："+personBalance.getCq_balance());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getCq_balance()));
    }

    @Override
    public void postMWPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("postMWPersonBalanceResult："+personBalance.getMw_balance());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getMw_balance()));
    }

    /*@OnClick({R.id.mwDz})
    public void onViewMWClicked(View view ) {
        //AGPlatformDialog.newInstance(agMoney,hgMoney,fshowtype).show(getFragmentManager());
        presenter.postMWGameList("","","");
    }*/

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表："+agLiveResult);

        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),4, OrientationHelper.VERTICAL,false);
        agLiveList.setLayoutManager(gridLayoutManager);
        agLiveList.setAdapter(new AGGameAdapter(getContext(),R.layout.item_ag_game,agLiveResult));
    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @OnClick({R.id.agUserMoneyChange,R.id.agListAg,R.id.agListMg,R.id.agListCQ,R.id.agListMW,R.id.mwDz})
    public void onViewClicked(View view ) {
        switch (view.getId()){
            case R.id.agUserMoneyChange:
                AGPlatformDialog.newInstance(agMoney,hgMoney,fshowtype).show(getFragmentManager());
                break;
            case R.id.agListAg:
                agLiveList.setVisibility(View.VISIBLE);
                mwDz.setVisibility(View.GONE);
                agListAg.setTextColor(getResources().getColor(R.color.register_left));
                agListAg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_click));
                agListMg.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListMg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListCQ.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListCQ.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListMW.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListMW.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                fshowtype ="game";
                presenter.postPersonBalance("","");
                presenter.postAGGameList("","","gamelist_dianzi");
                break;
            case R.id.agListMg:
                agLiveList.setVisibility(View.VISIBLE);
                mwDz.setVisibility(View.GONE);
                agListMg.setTextColor(getResources().getColor(R.color.register_left));
                agListMg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_click));
                agListAg.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListAg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListCQ.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListCQ.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListMW.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListMW.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                fshowtype ="mg";
                presenter.postMGPersonBalance("","");
                presenter.postMGGameList("","","");
                break;
            case R.id.agListCQ:
                agLiveList.setVisibility(View.VISIBLE);
                mwDz.setVisibility(View.GONE);
                agListAg.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListAg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListMg.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListMg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListCQ.setTextColor(getResources().getColor(R.color.register_left));
                agListCQ.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_click));
                agListMW.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListMW.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                fshowtype ="cq";
                presenter.postCQPersonBalance("","");
                presenter.postCQGameList("","","");
                break;
            case R.id.agListMW:
                agListAg.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListAg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListMg.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListMg.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListCQ.setTextColor(getResources().getColor(R.color.home_item_normal));
                agListCQ.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_nor));
                agListMW.setTextColor(getResources().getColor(R.color.register_left));
                agListMW.setBackground(getResources().getDrawable(R.drawable.btn_ag_item_click));
                fshowtype ="mw";
                presenter.postMWPersonBalance("","");
                agLiveList.setVisibility(View.GONE);
                mwDz.setVisibility(View.VISIBLE);
                break;
            case R.id.mwDz:
                presenter.postMWGameList("","","");
                break;
        }
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
                    if(fshowtype.equals("mg")){
                        presenter.postGoPlayGameMG("",data.getItem_id());
                    }if(fshowtype.equals("cq")){
                        presenter.postGoPlayGameCQ("",data.getGameid());
                    }else{
                        presenter.postGoPlayGame("",data.getGameid());
                    }
                }
            });
        }
    }
    @Subscribe
    public void onPersonBalanceResult(PersonBalanceResult personBalanceResult){
        GameLog.log("通过发送消息得的的数据"+personBalanceResult.getBalance_ag());
        /*if("mg".equals(fshowtype)){
            agMoney = personBalanceResult.getMg_balance();
            hgMoney = personBalanceResult.getHg_balance();
        }else{
            agMoney = personBalanceResult.getBalance_ag();
            hgMoney = personBalanceResult.getBalance_hg();
        }*/

        switch (fshowtype){
            case "mw":
                agMoney = personBalanceResult.getMw_balance();
                hgMoney = personBalanceResult.getHg_balance();
                break;
            case "cq":
                agMoney = personBalanceResult.getCq_balance();
                hgMoney = personBalanceResult.getHg_balance();
                break;
            case "mg":
                agMoney = personBalanceResult.getMg_balance();
                hgMoney = personBalanceResult.getHg_balance();
                break;
            default:
                agMoney = personBalanceResult.getBalance_ag();
                hgMoney = personBalanceResult.getBalance_hg();
                break;
        }
        agTitleBack.setMoreText(GameShipHelper.formatMoney(hgMoney));
        EventBus.getDefault().post(new UserMoneyEvent(GameShipHelper.formatMoney(hgMoney)));
        agUserMoney.setText(titleName+GameShipHelper.formatMoney(agMoney));
    }


    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
    }
}

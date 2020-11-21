package com.hgapp.a0086.homepage.aglist;

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
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a0086.common.http.Client;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.ArrayListHelper;
import com.hgapp.a0086.common.util.GameShipHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.GifView;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.common.widgets.RoundCornerImageView;
import com.hgapp.a0086.data.AGGameLoginResult;
import com.hgapp.a0086.data.AGLiveResult;
import com.hgapp.a0086.data.CheckAgLiveResult;
import com.hgapp.a0086.data.PersonBalanceResult;
import com.hgapp.a0086.homepage.UserMoneyEvent;
import com.hgapp.a0086.homepage.aglist.agchange.AGPlatformDialog;
import com.hgapp.a0086.homepage.aglist.playgame.XPlayGameActivity;
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
    @BindView(R.id.tabLay)
    LinearLayout tabLay;
    @BindView(R.id.gameTab)
    TabLayout gameTab;
    @BindView(R.id.agUserMoneyShow)
    RelativeLayout agUserMoneyShow;
    @BindView(R.id.agUserMoney)
    TextView agUserMoney;
    @BindView(R.id.mwDz)
    ImageView mwDz;
    @BindView(R.id.agUserMoneyChange)
    TextView agUserMoneyChange;
    @BindView(R.id.agLiveList)
    RecyclerView agLiveList;
    @BindView(R.id.agVideo)
    GifView agVideo;
    @BindView(R.id.agVideoLayout)
    FrameLayout agVideoLayout;
    private String FStype, Mtype, fshowtype, M_League, getArgParam4, fromType;
    AGListContract.Presenter presenter;
    private String agMoney,hgMoney;
    private String titleName = "";
    private String dzTitileName ="";
    private String gameId;

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
            //presenter.postPersonBalance("","");
            //presenter.postCheckAgAccount("","","check_game_account");
            agUserMoneyShow.setVisibility(View.VISIBLE);
        }else{
            agUserMoneyShow.setVisibility(View.GONE);
        }

        if("live".equals(fshowtype)){
            //presenter.postCheckAgLiveAccount("");
            presenter.postPersonBalance("","");
            presenter.postAGGameList("","","gamelist_zhenren");
            titleName = getString(R.string.games_ag_zr_bal);//"真人额度："
            //onAgLiveTestData();
            agLiveList.setVisibility(View.GONE);
            tabLay.setVisibility(View.GONE);
            agVideoLayout.setVisibility(View.VISIBLE);
            agVideo.setMovieResource(R.raw.cp_video);
            agVideo.setPaused(false);
        }else{
            //presenter.postCheckAgGameAccount("");
            initTabStyle();
            //presenter.postAGGameList("","","gamelist_dianzi");
            titleName = getString(R.string.games_ag_dz_bal);//"电子额度："
            //onAgGameTestData();
            agLiveList.setVisibility(View.VISIBLE);
            tabLay.setVisibility(View.VISIBLE);
            agVideoLayout.setVisibility(View.GONE);
        }
       // presenter.postCreateAgAccount("","","cga");

    }

    private void initTabStyle() {
        //presenter.getDepositSubmit(typeArgs2,"","","");
        gameTab.addTab(gameTab.newTab().setText(getString(R.string.plat_aggame)));
        gameTab.addTab(gameTab.newTab().setText(getString(R.string.plat_mg)));
        gameTab.addTab(gameTab.newTab().setText(getString(R.string.plat_cq)));
        gameTab.addTab(gameTab.newTab().setText(getString(R.string.plat_mw)));//大满贯
        gameTab.addTab(gameTab.newTab().setText(getString(R.string.plat_fg)));//大满贯
        gameTab.addOnTabSelectedListener(new TabLayout.OnTabSelectedListener() {
            @Override
            public void onTabSelected(TabLayout.Tab tab) {
                switch (tab.getPosition()) {
                    case 0:
                        fshowtype ="game";
                        presenter.postPersonBalance("","");
                        presenter.postAGGameList("","","gamelist_dianzi");
                        break;
                    case 1:
                        fshowtype ="mg";
                        presenter.postMGPersonBalance("","");
                        presenter.postMGGameList("","","");
                        break;
                    case 2:
                        fshowtype ="cq";
                        presenter.postCQPersonBalance("","");
                        presenter.postCQGameList("","","");
                        break;
                    case 3:
                        fshowtype ="mw";
                        presenter.postMWPersonBalance("","");
                        presenter.postMWGameList("","","");
                        break;
                    case 4:
                        fshowtype ="fg";
                        presenter.postFGPersonBalance("","");
                        presenter.postFGGameList("","","");
                        break;
                }
            }

            @Override
            public void onTabUnselected(TabLayout.Tab tab) {
            }

            @Override
            public void onTabReselected(TabLayout.Tab tab) {
            }
        });
        switch (fshowtype){
            case "game":
                presenter.postPersonBalance("","");
                presenter.postAGGameList("","","gamelist_dianzi");
                gameTab.getTabAt(0).select();
                break;
            case "mg":
                gameTab.getTabAt(1).select();
                break;
            case "cq":
                gameTab.getTabAt(2).select();
                break;
            case "mw":
                gameTab.getTabAt(3).select();
                break;
            case "fg":
                gameTab.getTabAt(4).select();
                break;
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

        GameLog.log("游戏弟弟值："+agGameLoginResult.getUrl());

        if(getString(R.string.plat_agvideo).equals(dzTitileName)){
            Intent intent = new Intent(Intent.ACTION_VIEW);
            intent.setData(Uri.parse(agGameLoginResult.getUrl()));
            startActivity(intent);
            return;
        }
        //EventBus.getDefault().post(new StartBrotherEvent(XPlayGameFragment.newInstance(dzTitileName,agGameLoginResult.getUrl(),"1"), SupportFragment.SINGLETASK));
        Intent intent = new Intent(getContext(),XPlayGameActivity.class);
        if("mw".equals(fshowtype)||"fg".equals(fshowtype)){
            intent.putExtra("url",agGameLoginResult.getToUrl());
        }else{
            intent.putExtra("url",agGameLoginResult.getUrl());
        }
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

    private void onSetMoney(String money){
        agTitleBack.setMoreText(GameShipHelper.formatMoney(money));
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("用户的真人账户："+personBalance.getBalance_ag());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getBalance_ag()));
        onSetMoney(personBalance.getBalance_hg());
    }

    @Override
    public void postMGPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("用户的真人账户："+personBalance.getMg_balance());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getMg_balance()));
        onSetMoney(personBalance.getHg_balance());
    }

    @Override
    public void postCQPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("postCQPersonBalanceResult："+personBalance.getCq_balance());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getCq_balance()));
        onSetMoney(personBalance.getHg_balance());
    }

    @OnClick({R.id.mwDz})
    public void onViewMWClicked(View view ) {
        //AGPlatformDialog.newInstance(agMoney,hgMoney,fshowtype).show(getFragmentManager());
        presenter.postMWGameList("","","");
    }

    @Override
    public void postMWPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("postMWPersonBalanceResult："+personBalance.getMw_balance());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getMw_balance()));
        onSetMoney(personBalance.getHg_balance());
    }

    @Override
    public void postFGPersonBalanceResult(PersonBalanceResult personBalance) {
        GameLog.log("postFGPersonBalanceResult："+personBalance.getFg_balance());
        agUserMoney.setText(titleName+ GameShipHelper.formatMoney(personBalance.getFg_balance()));
        onSetMoney(personBalance.getHg_balance());
    }

    @Override
    public void postAGGameResult(List<AGLiveResult> agLiveResult) {
        GameLog.log("游戏列表："+agLiveResult);
        if("live".equals(fshowtype)){
           /* GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),2, OrientationHelper.VERTICAL,false);
            agLiveList.setLayoutManager(gridLayoutManager);
            agLiveList.setAdapter(new AGLiveAdapter(getContext(),R.layout.item_ag_live,agLiveResult));*/
            gameId = agLiveResult.get(0).getGameid();
            dzTitileName = getString(R.string.plat_agvideo);//"真人视讯";
        }else{
            agLiveList.setVisibility(View.VISIBLE);
            GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),4, OrientationHelper.VERTICAL,false);
            agLiveList.setLayoutManager(gridLayoutManager);
            agLiveList.setAdapter(new AGGameAdapter(getContext(),R.layout.item_ag_game,agLiveResult));
        }
    }

    @Override
    public void postsMessageGameResult(String message) {
        agLiveList.setVisibility(View.GONE);
        showMessage(message);
    }

    @Override
    public void postCheckAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @Override
    public void postCreateAgAccountResult(CheckAgLiveResult checkAgLiveResult) {

    }

    @OnClick({R.id.agUserMoneyChange})
    public void onViewClicked(View view ) {
        AGPlatformDialog.newInstance(agMoney,hgMoney,fshowtype).show(getFragmentManager());
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
                    roundCornerImageView.setBackgroundResource(R.drawable.game_two);
                    break;
                case 2:
                    roundCornerImageView.setBackgroundResource(R.drawable.game_three);
                    break;
                case 3:
                    roundCornerImageView.setBackgroundResource(R.drawable.game_four);
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
                    if(fshowtype.equals("fg")){
                        presenter.postGoPlayGameFG("",data.getGameid());
                    }else if(fshowtype.equals("mg")){
                        presenter.postGoPlayGameMG("",data.getItem_id());
                    }else if(fshowtype.equals("cq")){
                        presenter.postGoPlayGameCQ("",data.getGameid());
                    }else if(fshowtype.equals("mw")){
                        presenter.postGoPlayGameMW("",data.getGameid());
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
        switch (fshowtype){
            case "fg":
                agMoney = personBalanceResult.getFg_balance();
                hgMoney = personBalanceResult.getHg_balance();
                break;
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
        /*if("mg".equals(fshowtype)){
            agMoney = personBalanceResult.getMg_balance();
            hgMoney = personBalanceResult.getHg_balance();
        }else{
            agMoney = personBalanceResult.getBalance_ag();
            hgMoney = personBalanceResult.getBalance_hg();
        }*/
        agTitleBack.setMoreText(GameShipHelper.formatMoney(hgMoney));
        EventBus.getDefault().post(new UserMoneyEvent(GameShipHelper.formatMoney(hgMoney)));
        agUserMoney.setText(titleName+GameShipHelper.formatMoney(agMoney));
    }


    @OnClick({R.id.agVideo})
    public void onAGVodie(){
        dzTitileName = getString(R.string.plat_agvideo);//"真人视讯";
        presenter.postGoPlayGame("",gameId);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        EventBus.getDefault().unregister(this);
        agVideo = null;
        GameLog.log("消失了");
    }
}

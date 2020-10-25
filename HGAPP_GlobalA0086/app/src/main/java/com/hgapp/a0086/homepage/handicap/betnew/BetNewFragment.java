package com.hgapp.a0086.homepage.handicap.betnew;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.FragmentTransaction;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.LinearInterpolator;
import android.view.animation.RotateAnimation;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.http.Client;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.ArrayListHelper;
import com.hgapp.a0086.common.util.GameShipHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.data.CPResult;
import com.hgapp.a0086.data.LoginResult;
import com.hgapp.a0086.data.PersonBalanceResult;
import com.hgapp.a0086.data.PersonInformResult;
import com.hgapp.a0086.data.QipaiResult;
import com.hgapp.a0086.homepage.UserMoneyEvent;
import com.hgapp.a0086.homepage.aglist.AGListFragment;
import com.hgapp.a0086.homepage.aglist.playgame.XPlayGameActivity;
import com.hgapp.a0086.homepage.handicap.BottombarViewManager;
import com.hgapp.a0086.homepage.handicap.HandicapFragment;
import com.hgapp.a0086.homepage.handicap.leaguedetail.CalosEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.ComPassSearchEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.LeagueDetailSearchEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.LeagueDetailSearchListFragment;
import com.hgapp.a0086.homepage.handicap.leaguedetail.PrepareBetFragment;
import com.hgapp.a0086.homepage.handicap.leaguedetail.PrepareGoEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet.PrepareBetZHFragment;
import com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet.PrepareGoZHEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet.ZHBetViewManager;
import com.hgapp.a0086.homepage.handicap.leaguelist.LeagueSearchListFragment;
import com.hgapp.a0086.homepage.handicap.leaguelist.championlist.ChampionDetailListFragment;
import com.hgapp.a0086.homepage.handicap.leaguelist.championlist.ChampionDetailSearchEvent;
import com.hgapp.a0086.homepage.handicap.saiguo.SaiGuoFragment;
import com.hgapp.a0086.personpage.PersonContract;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.tencent.smtt.export.external.interfaces.JsPromptResult;
import com.tencent.smtt.export.external.interfaces.JsResult;
import com.tencent.smtt.sdk.CookieManager;
import com.tencent.smtt.sdk.WebChromeClient;
import com.tencent.smtt.sdk.WebSettings;
import com.tencent.smtt.sdk.WebView;
import com.tencent.smtt.sdk.WebViewClient;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import butterknife.Unbinder;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class BetNewFragment extends HGBaseFragment implements PersonContract.View{

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";

    @BindView(R.id.tvBetNewUserName)
    TextView tvBetNewUserName;
    @BindView(R.id.tvBetNewUserMoney)
    TextView tvBetNewUserMoney;
    @BindView(R.id.ivBetNewBack)
    ImageView ivBetNewBack;
    @BindView(R.id.ivBetNewBowls)
    ImageView ivBetNewBowls;
    @BindView(R.id.tvBetNewBowls)
    TextView tvBetNewBowls;
    @BindView(R.id.tvBetNewToday)
    TextView tvBetNewToday;
    @BindView(R.id.tvBetNewMorning)
    TextView tvBetNewMorning;
    @BindView(R.id.ivBetNewMenu)
    ImageView ivBetNewMenu;
    @BindView(R.id.ivBetNewUserRefresh)
    ImageView ivBetNewUserRefresh;
    @BindView(R.id.rlBetNewUserInfor)
    RelativeLayout rlBetNewUserInfor;
    Unbinder unbinder;
    @BindView(R.id.tvBetNewFootball)
    TextView tvBetNewFootball;
    @BindView(R.id.tvBetNewBasketball)
    TextView tvBetNewBasketball;
    @BindView(R.id.tvBetNewSaiGuo)
    TextView tvBetNewSaiGuo;
    @BindView(R.id.tvBetNewTennis)
    TextView tvBetNewTennis;
    @BindView(R.id.tvBetNewBaseball)
    TextView tvBetNewBaseball;
    @BindView(R.id.tvBetNewVideoGame)
    TextView tvBetNewVideoGame;
    @BindView(R.id.tvBetNewSlotsGame)
    TextView tvBetNewSlotsGame;
    @BindView(R.id.tvBetNewLottery)
    TextView tvBetNewLottery;
    @BindView(R.id.tvBetNewQipai)
    TextView tvBetNewQipai;


    private boolean ivBetNewMenuShow = false;

    /**
     * cate    FT_RB  足球滚球、FT 足球今日赛事 足球早盘 、BK_RB 篮球滚球、BK 篮球今日赛事 篮球早盘
     * <p>
     * <p>
     * FT	足球今日赛事，滚球
     * FU	足球早盘
     * BK	篮球今日赛事，滚球
     * BU	篮球早盘
     */
    private String cate;
    /**
     * type H 主队独赢 C 客队独赢 N 和局  C 滚球大小-小  H 滚球大小-大 C 球队得分大小-主队 H 球队得分大小-客队 H 主队让球 C 客队让球
     */
    private String type;
    /**
     * active=1&       1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
     */
    private String active;

    /**
     * 单双玩法投注传参
     * ODD 单
     * EVEN 双
     */
    private String rtype;
    /**
     * （全场大小、半场大小、球队得分大小） OUH 大，OUC 小，ROUH 球队得分大小-大，ROUC 球队得分大小-小
     * 今日赛事
     * 大小单场 & 大小半场
     * 主队 大 OUH
     * 客队 小 OUC
     * * 滚球赛事
     * 大小单场 & 大小半场
     * 主队 大 ROUH
     * 客队 小 ROUC
     * 足球
     * 篮球
     * 网球
     * 排球
     * 羽毛球
     * 棒球
     * 其它
     */
    private String wtype;

    // 数据源
    private String[] groups = {"滚球赛事", "今日赛事", "早盘赛事"};
    private String[][] children = {
            {"足球", "篮球", "网球", "排球", "羽毛球", "棒球", "其它"},
            {"足球", "篮球", "网球", "排球", "羽毛球", "棒球", "其它"},
            {"足球", "篮球", "网球", "排球", "羽毛球", "棒球", "其它"}
    };


    /**
     * getArgParam1 用户名称 ,
     * getArgParam2 用户金额 ，
     * getArgParam3 赛事 滚球1 今日2 早盘3
     * getArgParam4 具体的赛事名字 、
     * getArgParam5 cate,
     * getArgParam6 active,
     * getArgParam7 type
     */
    private String getArgParam1, getArgParam2,getArgParam3,getArgParam4,getArgParam5,getArgParam6,getArgParam7;

    OptionsPickerView optionsPickerViewState;
    private PersonContract.Presenter presenter;
    private int resource = 1;
    static List<String> stateList = new ArrayList<String>();
    RotateAnimation animation ;
    static {
        stateList.add("香港盘");
        stateList.add("马来盘");
        stateList.add("印尼盘");
        stateList.add("欧洲盘");
    }


    public static BetNewFragment newInstance(List<String> param1) {
        BetNewFragment fragment = new BetNewFragment();
        Bundle args = new Bundle();
        //args.putString(ARG_PARAM1, param1.get(0));
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        //args.putString(ARG_PARAM2, param1.get(1));
        //args.putString(ARG_PARAM2, param1.get(2));
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
           /* getArgParam1 = getArguments().getString(ARG_PARAM1);
            getArgParam2 = getArguments().getString(ARG_PARAM2);*/
            getArgParam1 = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            getArgParam2 = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            getArgParam3 = getArguments().getStringArrayList(ARG_PARAM1).get(2);
            getArgParam4 = getArguments().getStringArrayList(ARG_PARAM1).get(3);
            getArgParam5 = getArguments().getStringArrayList(ARG_PARAM1).get(4);
            getArgParam6 = getArguments().getStringArrayList(ARG_PARAM1).get(5);
            getArgParam7 = getArguments().getStringArrayList(ARG_PARAM1).get(6);
        }
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_bet_new;
    }

    String stype = "";
    String more = "";
    DynamicReceiver dynamicReceiver;
    HGBaseFragment hgBaseFragment;

    class DynamicReceiver extends BroadcastReceiver {
        @Override
        public void onReceive(Context context, Intent intent) {
            //通过土司验证接收到广播
            String action = intent.getAction();
            GameLog.log("action "+action);
            if (Intent.ACTION_SCREEN_ON.equals(action)) {
                /**
           * 屏幕亮 
           */
                GameLog.log("action "+action);
                if("1".equals(ACache.get(getActivity()).getAsString(HGConstant.USER_ACTION_SCREEN_OFF))){
                    ZHBetViewManager.getSingleton().onShowView(getActivity(),hgBaseFragment,"","","");
                    EventBus.getDefault().post(new CalosEvent());
                }
                //ACache.get(getActivity()).put(HGConstant.USER_ACTION_SCREEN_OFF,"2");
            } else if (Intent.ACTION_SCREEN_OFF.equals(action)) {
                /**
            * 屏幕锁定
            */
                ACache.get(getActivity()).put(HGConstant.USER_ACTION_SCREEN_OFF,"1");
            } else if (Intent.ACTION_USER_PRESENT.equals(action)) {
                /** 
                 * 屏幕解锁了且可以使用 
                 */
                EventBus.getDefault().post(new CalosEvent());
                ZHBetViewManager.getSingleton().onShowView(getActivity(),hgBaseFragment,"","","");
            }

        }
    }

    private void initBoa(){
        IntentFilter filter = new IntentFilter();
        filter.addAction(Intent.ACTION_SCREEN_ON);
        filter.addAction(Intent.ACTION_SCREEN_OFF);
        filter.addAction(Intent.ACTION_USER_PRESENT);
        dynamicReceiver = new DynamicReceiver();
        //注册广播接收
        getActivity().registerReceiver(dynamicReceiver,filter);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        initBoa();
        hgBaseFragment = this;
        onEventType(getArgParam7);
        EventBus.getDefault().register(this);
        tvBetNewUserName.setText(getArgParam1);
        tvBetNewUserMoney.setText(getArgParam2);
        onSetTitileColor(getArgParam3);
        optionsPickerViewState = new OptionsPickerBuilder(getContext(), new OnOptionsSelectListener() {

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                resource = options1;
            }
        }).build();
        optionsPickerViewState.setPicker(stateList);

        showSearchFragmnet(getArgParam3);

    }

    private void onEventType(String getArgParam){
        switch (getArgParam){
            case "1":
                stype = "FT";
                more = "r";
                break;
            case "2":
                stype = "BK";
                more = "r";
                break;
            case "3":
                stype = "FT";
                more = "s";
                break;
            case "4":
                stype = "BK";
                more = "s";
                break;
            case "5":
                stype = "FU";
                more = "";
                break;
            case "6":
                stype = "BU";
                more = "";
                break;
        }
        animation= new RotateAnimation(0,360f,Animation.RELATIVE_TO_SELF,0.5f,Animation.RELATIVE_TO_SELF,0.5f);
        animation.setDuration(1000);
        animation.setFillAfter(true);
        animation.setInterpolator(new LinearInterpolator());
        animation.setRepeatMode(Animation.RESTART);
        animation.setRepeatCount(Animation.INFINITE);
    }

    private void  showFragmnet(String param){
        LeagueListFragment rightFragment = LeagueListFragment.newInstance(Arrays.asList(param,"2"));
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, rightFragment);
        ft.show(rightFragment);
        ft.commit();
    }

    private void  showSearchFragmnet(String param){
        leagueSearchListFragment = LeagueSearchListFragment.newInstance(Arrays.asList(param,getArgParam4,stype,more));
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, leagueSearchListFragment);
        ft.show(leagueSearchListFragment);
        ft.commit();
    }

    private void  showSearchChampionDetailFragmnet(ChampionDetailSearchEvent championDetailSearchEvent ){
        championDetailListFragment = ChampionDetailListFragment.newInstance(Arrays.asList(championDetailSearchEvent.getFStype(),championDetailSearchEvent.getMtype(),championDetailSearchEvent.getShowtype(),championDetailSearchEvent.getM_League(),getArgParam7,getArgParam2));
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, championDetailListFragment);
        ft.show(championDetailListFragment);
        ft.commit();
    }

    //让球& 大小
    private void  showSearchDetailFragmnet(LeagueDetailSearchEvent leagueDetailSearchEvent ){
        leagueDetailSearchListFragment = LeagueDetailSearchListFragment.newInstance(Arrays.asList(leagueDetailSearchEvent.getType(),leagueDetailSearchEvent.getMore(),leagueDetailSearchEvent.getGid(),leagueDetailSearchEvent.getShowtype(),getArgParam7,getArgParam2,""));
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, leagueDetailSearchListFragment);
        ft.show(leagueDetailSearchListFragment);
        ft.commit();
    }
    ChampionDetailListFragment championDetailListFragment;
    LeagueDetailSearchListFragment leagueDetailSearchListFragment;
    LeagueSearchListFragment leagueSearchListFragment;
    PrepareBetZHFragment prepareBetZHFragment;
    PrepareBetFragment prepareBetFragment;
    //综合过关
    private void  showSearchComPassFragmnet(ComPassSearchEvent comPassSearchEvent ){
        leagueDetailSearchListFragment = LeagueDetailSearchListFragment.newInstance(Arrays.asList(comPassSearchEvent.gtype,comPassSearchEvent.sorttype,comPassSearchEvent.mdate,comPassSearchEvent.getArgParam1,getArgParam7,getArgParam2,comPassSearchEvent.M_league));
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, leagueDetailSearchListFragment);
        ft.show(leagueDetailSearchListFragment);
        ft.commit();
    }

    private void  showBetFragmnet(PrepareGoEvent prepareGoEvent){
        prepareBetFragment = PrepareBetFragment.newInstance(prepareGoEvent.getmLeague(),prepareGoEvent.getmTeamH(),prepareGoEvent.getmTeamC(),prepareGoEvent.getGid(),prepareGoEvent.getGtype(),prepareGoEvent.getShowtype(),prepareGoEvent.getUserMoney(),prepareGoEvent.getFromType(),prepareGoEvent.getFromString());
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, prepareBetFragment);
        ft.show(prepareBetFragment);
        ft.commit();
    }

    private void  showBetZHFragmnet(PrepareGoZHEvent prepareGoZHEvent){
        prepareBetZHFragment = PrepareBetZHFragment.newInstance(prepareGoZHEvent);
        FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, prepareBetZHFragment);
        ft.show(prepareBetZHFragment);
        ft.commit();
    }



    private void onSetTitileColor(String param){

        switch (param){
            case "1":
                ivBetNewBowls.setVisibility(View.VISIBLE);
                if (ivBetNewBowls != null) {
                    ivBetNewBowls.startAnimation(animation);
                }
                /*Animation aniRotateClk = AnimationUtils.loadAnimation(getContext(),R.anim.rotate_clockwise);
                ivBetNewBowls.startAnimation(aniRotateClk);*/
                tvBetNewBowls.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvBetNewToday.setTextColor(getResources().getColor(R.color.bet_title_tv_normal));
                tvBetNewMorning.setTextColor(getResources().getColor(R.color.bet_title_tv_normal));
                break;
            case "2":
                ivBetNewBowls.clearAnimation();
                ivBetNewBowls.setVisibility(View.INVISIBLE);
                 tvBetNewBowls.setTextColor(getResources().getColor(R.color.bet_title_tv_normal));
                 tvBetNewToday.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                tvBetNewMorning.setTextColor(getResources().getColor(R.color.bet_title_tv_normal));
                 break;
            case "3":
                ivBetNewBowls.clearAnimation();
                ivBetNewBowls.setVisibility(View.INVISIBLE);
                tvBetNewBowls.setTextColor(getResources().getColor(R.color.bet_title_tv_normal));
                tvBetNewToday.setTextColor(getResources().getColor(R.color.bet_title_tv_normal));
                tvBetNewMorning.setTextColor(getResources().getColor(R.color.bet_title_tv_clicked));
                break;

        }
    }



    private void onHideDeatail(){
        ZHBetViewManager.getSingleton().onCloseView();
    }

    private void onHideLeagueDetailList(){
        GameLog.log("----------------------start--------------------");
        SupportFragment class2 = getTopFragment();
        if( class2.getClass().isInstance(championDetailListFragment)){
            GameLog.log("数据的名称："+class2+" championDetailListFragment  "+championDetailListFragment+" leagueSearchListFragment "+leagueSearchListFragment);
            FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, leagueSearchListFragment);
            ft.show(leagueSearchListFragment);
            ft.commit();
            GameLog.log("--------1-------------prepareBetZHFragment---------------------");
        }else if( class2.getClass().isInstance(prepareBetZHFragment)){
            GameLog.log("数据的名称："+class2+" prepareBetZHFragment  "+prepareBetZHFragment+" leagueDetailSearchListFragment "+leagueDetailSearchListFragment);
            FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, leagueDetailSearchListFragment);
            ft.show(leagueDetailSearchListFragment);
            ft.commit();
            GameLog.log("--------1-------------prepareBetZHFragment---------------------");
        }else if( class2.getClass().isInstance(prepareBetFragment)){
            GameLog.log("数据的名称："+class2+" prepareBetFragment  "+prepareBetFragment+" leagueDetailSearchListFragment "+leagueDetailSearchListFragment);
            FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, leagueDetailSearchListFragment);
            ft.show(leagueDetailSearchListFragment);
            ft.commit();
            GameLog.log("--------2-------------prepareBetFragment---------------------");
        }else if( class2.getClass().isInstance(leagueDetailSearchListFragment) ){
            GameLog.log("数据的名称："+class2+" leagueSearchListFragment  "+leagueSearchListFragment+" leagueDetailSearchListFragment "+leagueDetailSearchListFragment);
            /*GameLog.log("--------3--------------leagueDetailSearchListFragment--------------------");
            ZHBetViewManager.getSingleton().onCloseView();
            pop();
            EventBus.getDefault().post(new HideLeagueDetailEvent());*/
            FragmentTransaction ft = getFragmentManager().beginTransaction().replace(R.id.fgtContainer, leagueSearchListFragment);
            ft.show(leagueSearchListFragment);
            ft.commit();
            GameLog.log("--------3-------------leagueSearchListFragment---------------------");
        }else{
            GameLog.log("----------------------other--------------------"+class2);
            ZHBetViewManager.getSingleton().onCloseView();
            pop();
        }
        //showHideFragment(leagueDetailSearchListFragment,prepareBetZHFragment);
        //}
        //pop();
    }

    @Override
    public boolean onBackPressedSupport() {
        return true;
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
    }



    @OnClick({R.id.ivBetNewBack, R.id.ivBetNewMenu, R.id.ivBetNewUserRefresh,
            R.id.tvBetNewBowls,R.id.tvBetNewToday,R.id.tvBetNewMorning,
            R.id.tvBetNewFootball, R.id.tvBetNewBasketball,R.id.tvBetNewSaiGuo, R.id.tvBetNewTennis, R.id.tvBetNewBaseball, R.id.tvBetNewVideoGame, R.id.tvBetNewSlotsGame, R.id.tvBetNewLottery,R.id.tvBetNewQipai,R.id.tvBetNewHgQipai})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.tvBetNewBowls:
                getArgParam3 = "1";
                onSetTitileColor("1");
                showFragmnet("1");
                onHideDeatail();
                break;
            case R.id.tvBetNewToday:
                getArgParam3 = "2";
                onSetTitileColor("2");
                showFragmnet("2");
                onHideDeatail();
                break;
            case R.id.tvBetNewMorning:
                getArgParam3 = "3";
                onSetTitileColor("3");
                showFragmnet("3");
                onHideDeatail();
                break;
            case R.id.ivBetNewBack:
                onHideLeagueDetailList();
                break;
            case R.id.ivBetNewMenu:
                GameLog.log("是否展示个人相关信息：" + ivBetNewMenuShow);
                if (!ivBetNewMenuShow) {
                    ivBetNewMenuShow = true;
                    rlBetNewUserInfor.setVisibility(View.VISIBLE);
                    ivBetNewMenu.setBackgroundResource(R.mipmap.bet_nav_click);
                } else {
                    ivBetNewMenuShow = false;
                    rlBetNewUserInfor.setVisibility(View.GONE);
                    ivBetNewMenu.setBackgroundResource(R.mipmap.bet_nav_default);
                }
                String userMoney2 = ACache.get(getContext()).getAsString(HGConstant.USERNAME_REMAIN_MONEY);
                if (!Check.isEmpty(userMoney2) && userMoney2 != getArgParam2) {
                    getArgParam2 = userMoney2;
                }
                tvBetNewUserMoney.setText(getArgParam2);
                break;
            case R.id.ivBetNewUserRefresh:
                if(ivBetNewUserRefresh!=null){
                    ivBetNewUserRefresh.startAnimation(animation);
                }
                presenter.getPersonBalance("", "");
                break;
            case R.id.tvBetNewFootball:
                onHideDeatail();
                getArgParam4 = getString(R.string.plat_football);

                if(getArgParam3.equals("1")){
                    getArgParam7 = "1";
                }else if(getArgParam3.equals("2")){
                    getArgParam7 = "3";
                }else if(getArgParam3.equals("3")){
                    getArgParam7 = "5";
                }
                GameLog.log("getArgParam3 "+getArgParam3 + " getArgParam4 "+getArgParam4+ " getArgParam7 "+getArgParam7);
                onEventType(getArgParam7);
                showSearchFragmnet(getArgParam3);
                break;
            case R.id.tvBetNewBasketball:
                onHideDeatail();
                getArgParam4 = getString(R.string.games_basketball);
                if(getArgParam3.equals("1")){
                    getArgParam7 = "2";
                }else if(getArgParam3.equals("2")){
                    getArgParam7 = "4";
                }else if(getArgParam3.equals("3")){
                    getArgParam7 = "6";
                }
                GameLog.log("getArgParam3 "+getArgParam3 + " getArgParam4 "+getArgParam4+ " getArgParam7 "+getArgParam7);
                onEventType(getArgParam7);
                showSearchFragmnet(getArgParam3);
                break;
            case R.id.tvBetNewSaiGuo:
                BottombarViewManager.getSingleton().onCloseView();
                EventBus.getDefault().post(new StartBrotherEvent(SaiGuoFragment.newInstance(getArgParam2,getArgParam2 ), SupportFragment.SINGLETASK));
                break;
            case R.id.tvBetNewTennis:
                showMessage(getString(R.string.games_no_data));
                break;
            case R.id.tvBetNewBaseball:
                showMessage(getString(R.string.games_no_data));
                break;
            case R.id.tvBetNewVideoGame:
                onHideDeatail();
                //pop();
                popTo(HandicapFragment.class,true);
                BottombarViewManager.getSingleton().onCloseView();
                EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(getArgParam1,getArgParam2,"live")), SupportFragment.SINGLETASK));
                break;
            case R.id.tvBetNewSlotsGame:
                onHideDeatail();
                //pop();
                popTo(HandicapFragment.class,true);
                BottombarViewManager.getSingleton().onCloseView();
                EventBus.getDefault().post(new StartBrotherEvent(AGListFragment.newInstance(Arrays.asList(getArgParam1,getArgParam2,"game")), SupportFragment.SINGLETASK));
                break;
            case R.id.tvBetNewLottery:
                onHideDeatail();
                //presenter.postCP();
                postCPGo();
                //pop();
                //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(getArgParam2, Client.baseUrl().replace("m.","mc."))));
                break;
            case R.id.tvBetNewQipai:
                //onHideDeatail();
                //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(getArgParam2, Client.baseUrl()+"ky/ky_api.php?action=cm")));
                postQiPaiGo();
                //finish();
                break;
            case R.id.tvBetNewHgQipai:
                postHGQiPaiGo();
                break;
        }
    }


    private void postCPGo(){
        String cp_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_CP_URL);
        if(Check.isEmpty(cp_url)){
            presenter.postCP();
        }else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.APP_CP_COOKIE))){
            showMessage(getString(R.string.comm_loading1));
        }else{
            Intent intent = new Intent(getContext(),XPlayGameActivity.class);
            intent.putExtra("url",cp_url);
            intent.putExtra("gameCnName",getString(R.string.plat_cp));
            intent.putExtra("gameType","CP");
            intent.putExtra("hidetitlebar",false);
            getActivity().startActivity(intent);
        }
    }



    //FT_RB  足球滚球、FT 足球今日赛事 足球早盘 、BK_RB 篮球滚球、BK 篮球今日赛事 篮球早盘
    // 1 足球滚球、今日赛事, 11 足球早餐，2 篮球滚球、今日赛事, 22 篮球早餐
    /*@OnClick({R.id.ivHandBack,R.id.llHandicapName,R.id.tvHandicapRule})
    public void onViewClicked(View view) {
        String cate = "";
        String active = "";
        String type = "";
        switch (view.getId()) {
            case R.id.ivHandBack:
                finish();
                break;

        }
    }*/


    @Override
    public void onDestroyView() {
        super.onDestroyView();
        Client.cancelTag(this);
        EventBus.getDefault().unregister(this);
        getActivity().unregisterReceiver(dynamicReceiver);
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("登录成功跟这个一");
        //ZHBetViewManager.getSingleton().onShowView(getActivity(),this,"","","");
        BottombarViewManager.getSingleton().onShowView(getActivity(),this,"","","");
    }

    @Subscribe
    public void onEventMain(UserMoneyEvent userMoneyEvent){
        tvBetNewUserMoney.setText(userMoneyEvent.money);
    }

    @Subscribe
    public void onEventMain(CloseBottomEvent closeBottomEvent){
        String position = ACache.get(getContext()).getAsString(HGConstant.USER_CURRENT_POSITION);
        if(position.equals("2")){
            ZHBetViewManager.getSingleton().onShowView(getActivity(),this,"","","");
        }
        BottombarViewManager.getSingleton().onShowView(getActivity(),this,"","","");
    }

    @Subscribe
    public void onLeagueEvent(LeagueEvent leagueEvent){
        GameLog.log("LeagueEvent: LeagueNumber "+leagueEvent.getLeagueNumber());
        getArgParam3 = leagueEvent.getLeagueNumber();
        switch (leagueEvent.getLeagueNumber()){
            case "1":
                showFragmnet("1");
                break;
            case "2":
                showFragmnet("2");
                break;
            case "3":
                showFragmnet("3");
                break;
        }

    }

    @Subscribe
    public void omLeagueSearchEvent(LeagueSearchEvent leagueSearchEvent){
        GameLog.log("omLeagueSearchEvent: leagueSearchSub =="+leagueSearchEvent.getLeagueSearchSub()+" LeagusSearchName =="+leagueSearchEvent.getLeagueSearchName()+" LeagusSearchType =="+leagueSearchEvent.getLeagueSearchType());
        getArgParam4 = leagueSearchEvent.getLeagueSearchName();
        getArgParam3 =leagueSearchEvent.getLeagueSearchSub();
        getArgParam7 = leagueSearchEvent.getLeagueSearchType();
        onEventType(getArgParam7);
        showSearchFragmnet(leagueSearchEvent.getLeagueSearchSub());
    }

    @Subscribe
    public void onLeagueDetailSearchEvent(LeagueDetailSearchEvent leagueDetailSearchEvent){
        GameLog.log("LeagueDetailSearchEvent: 时间监听的到 Showtype "+leagueDetailSearchEvent.getShowtype());
        showSearchDetailFragmnet(leagueDetailSearchEvent);
    }
    @Subscribe
    public void onComPassSearchEvent(ComPassSearchEvent comPassSearchEvent){
        GameLog.log("comPassSearchEvent: 时间监听的到  "+comPassSearchEvent.toString());
        showSearchComPassFragmnet(comPassSearchEvent);
    }
    @Subscribe
    public void onPrepareGoZHEvent(PrepareGoZHEvent prepareGoZHEvent){

        GameLog.log("综合过关更多的信息："+prepareGoZHEvent.toString());
        showBetZHFragmnet(prepareGoZHEvent);
    }

    @Subscribe
    public void onChampionDetailSearchEvent(ChampionDetailSearchEvent championDetailSearchEvent){
        GameLog.log("championDetailSearchEvent: 时间监听的到 Showtype "+championDetailSearchEvent.getShowtype());
        showSearchChampionDetailFragmnet(championDetailSearchEvent);
    }

    @Subscribe
    public void onPrepareGoEvent(PrepareGoEvent prepareGoEvent){
        showBetFragmnet(prepareGoEvent);
    }

    @Override
    public void postPersonInformResult(PersonInformResult personInformResult) {

    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        ivBetNewUserRefresh.clearAnimation();
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        if(ivBetNewUserRefresh!=null){
            ivBetNewUserRefresh.clearAnimation();
        }
        getArgParam2 = GameShipHelper.formatMoney(personBalance.getBalance_hg());
        tvBetNewUserMoney.setText(getArgParam2);
        ACache.get(getContext()).put(HGConstant.USERNAME_REMAIN_MONEY,getArgParam2);
        GameLog.log("获取用户金额："+personBalance.getBalance_hg());
    }

    @Override
    public void postQipaiResult(QipaiResult qipaiResult) {
        GameLog.log("棋牌："+qipaiResult.getMessage());
        ACache.get(getContext()).put(HGConstant.USERNAME_QIPAI_URL,qipaiResult.getUrl());
    }

    @Override
    public void postHgQipaiResult(QipaiResult qipaiResult) {
        ACache.get(getContext()).put(HGConstant.USERNAME_HG_QIPAI_URL,qipaiResult.getUrl());
        GameLog.log("=============皇冠棋牌的地址=============");
    }

    private void postQiPaiGo(){
        String qipai_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_QIPAI_URL);
        if(Check.isEmpty(qipai_url)){
            showMessage(getString(R.string.comm_loading1));
            presenter.postQipai("","");
        }/*else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL))){
            showMessage("正在加载中，请稍后再试!");
        }*/else {
            Intent intent = new Intent(getContext(),XPlayGameActivity.class);
            intent.putExtra("url",qipai_url);
            intent.putExtra("gameCnName",getString(R.string.plat_ky));
            intent.putExtra("hidetitlebar",false);
            getActivity().startActivity(intent);
        }

    }

    private void postHGQiPaiGo(){
        String qipai_url = ACache.get(getContext()).getAsString(HGConstant.USERNAME_HG_QIPAI_URL);
        if(Check.isEmpty(qipai_url)){
            showMessage(getString(R.string.comm_loading1));
            presenter.postHgQipai("","");
        }/*else if(Check.isEmpty(ACache.get(getContext()).getAsString(HGConstant.USERNAME_GIFT_URL))){
            showMessage("正在加载中，请稍后再试!");
        }*/else {
            Intent intent = new Intent(getContext(),XPlayGameActivity.class);
            intent.putExtra("url",qipai_url);
            intent.putExtra("gameCnName",getString(R.string.plat_hg));
            intent.putExtra("hidetitlebar",false);
            getActivity().startActivity(intent);
        }

    }

    @Override
    public void postPersonLogoutResult(String message) {

    }

    private void initWebView(String url) {
        WebView mWebView = new WebView(getContext());
        mWebView.setWebViewClient(new WebViewClient() {

            @Override
            public void onLoadResource(WebView view, String url) {
                super.onLoadResource(view, url);
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView webview, String url) {
                webview.loadUrl(url);
                return true;
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {

                //rl_loading.setVisibility(View.VISIBLE); // 显示加载界面
            }

            @Override
            public void onPageFinished(WebView view, String url) {

                GameLog.log("请求的URL地址 "+url);
                String title = view.getTitle();
                CookieManager cookieManager = CookieManager.getInstance();
                String CookieStr = cookieManager.getCookie(url);
                ACache.get(getContext()).put(HGConstant.APP_CP_COOKIE,CookieStr);
                GameLog.log("cookie日志："+CookieStr);
                /*tv_topbar_title.setText(title);
                tv_topbar_title.setVisibility(View.VISIBLE);
                rl_loading.setVisibility(View.GONE); // 隐藏加载界面*/
            }

            @Override
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                //rl_loading.setVisibility(View.GONE); // 隐藏加载界面
            }
        });

        mWebView.setWebChromeClient(new WebChromeClient() {

            @Override
            public boolean onJsAlert(WebView view, String url, String message, JsResult result) {
                result.confirm();
                return true;
            }

            @Override
            public boolean onJsConfirm(WebView view, String url, String message, JsResult result) {
                return super.onJsConfirm(view, url, message, result);
            }

            @Override
            public boolean onJsPrompt(WebView view, String url, String message, String defaultValue, JsPromptResult result) {
                return super.onJsPrompt(view, url, message, defaultValue, result);
            }
        });

        mWebView.loadUrl(url);
        mWebView.getSettings().setJavaScriptEnabled(true);
        mWebView.getSettings().setRenderPriority(WebSettings.RenderPriority.HIGH);
        mWebView.getSettings().setCacheMode(WebSettings.LOAD_DEFAULT);  //设置 缓存模式
        // 开启 DOM storage API 功能
        mWebView.getSettings().setDomStorageEnabled(true);
        //开启 database storage API 功能
        mWebView.getSettings().setDatabaseEnabled(true);
//        String cacheDirPath = getContext().getFilesDir().getAbsolutePath()+APP_CACAHE_DIRNAME;
        String cacheDirPath = getContext().getCacheDir().getAbsolutePath() + HGConstant.APP_DB_DIRNAME;
        GameLog.log("cacheDirPath=" + cacheDirPath);
        //设置数据库缓存路径
        mWebView.getSettings().setDatabasePath(cacheDirPath);
        //设置  Application Caches 缓存目录
        mWebView.getSettings().setAppCachePath(cacheDirPath);
        //开启 Application Caches 功能
        mWebView.getSettings().setAppCacheEnabled(true);

    }

    @Override
    public void postCPResult(CPResult cpResult) {
        ACache.get(getContext()).put(HGConstant.USERNAME_CP_URL,cpResult.getCpUrl());
        initWebView(cpResult.getUrlLogin());
        /*MyHttpClient myHttpClient = new MyHttpClient();
        String domainUrl = cpResult.getUrlLogin();
        myHttpClient.executeGet(domainUrl, new Callback() {
            @Override
            public void onFailure(Call call, final IOException e) {
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                final String responseText =  response.body().string();
                GameLog.log("登录成功之后请求彩票地址："+responseText);
            }
        });
        pop();*/
    }

    @Override
    public void setPresenter(PersonContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onVisible() {
        super.onVisible();
        /**
         * 代码启动阶段获取设备屏幕初始状态
         */
       /* PowerManager manager = (PowerManager) getActivity().getSystemService(Context.POWER_SERVICE);
        if (manager.isScreenOn()) {
            ZHBetViewManager.getSingleton().onShowView(getActivity(),this,"","","");
        } else {

        }*/
    }
}

package com.hgapp.betnhg.homepage.handicap.leaguedetail;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.TextView;

import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.betnhg.Injections;
import com.hgapp.betnhg.R;
import com.hgapp.betnhg.base.HGBaseFragment;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.common.adapters.AutoSizeAdapter;
import com.hgapp.betnhg.common.util.ACache;
import com.hgapp.betnhg.common.util.ArrayListHelper;
import com.hgapp.betnhg.common.util.HGConstant;
import com.hgapp.betnhg.common.widgets.NListView;
import com.hgapp.betnhg.data.BetResult;
import com.hgapp.betnhg.data.ComPassSearchListResult;
import com.hgapp.betnhg.data.LeagueDetailSearchListResult;
import com.hgapp.betnhg.data.PrepareBetResult;
import com.hgapp.betnhg.homepage.handicap.BottombarViewManager;
import com.hgapp.betnhg.homepage.handicap.HandicapFragment;
import com.hgapp.betnhg.homepage.handicap.betapi.PrepareRequestParams;
import com.hgapp.betnhg.homepage.handicap.betnew.HideLeagueDetailEvent;
import com.hgapp.betnhg.homepage.handicap.betnew.LeagueEvent;
import com.hgapp.betnhg.homepage.handicap.leaguedetail.zhbet.PrepareGoZHEvent;
import com.hgapp.betnhg.homepage.handicap.leaguedetail.zhbet.ZHBetManager;
import com.hgapp.betnhg.homepage.handicap.leaguedetail.zhbet.ZHBetViewManager;
import com.hgapp.betnhg.homepage.sportslist.bet.BetOrderSubmitDialog;
import com.hgapp.betnhg.homepage.sportslist.bet.OrderNumber;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.abslistview.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashSet;
import java.util.LinkedList;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class LeagueDetailSearchListFragment extends HGBaseFragment implements LeagueDetailSearchListContract.View{

    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    @BindView(R.id.tvLeagueDetailSearchName)
    TextView tvLeagueDetailSearchName;
    @BindView(R.id.ivLeagueDetailRefresh)
    ImageView ivLeagueDetailRefresh;
    @BindView(R.id.tvLeagueDetailRefresh)
    TextView tvLeagueDetailRefresh;
    @BindView(R.id.tvLeagueSearchTime)
    TextView tvLeagueSearchTime;
    @BindView(R.id.lvLeagueSearchList)
    NListView lvLeagueSearchList;
    @BindView(R.id.tvLeagueSearchNoData)
    TextView tvLeagueSearchNoData;
    @BindView(R.id.btnLeagueSearch)
    TextView btnLeagueSearch;
    LeagueDetailSearchListResult leagueDetailSearchListResult;
    private String typeId, more,moreGid,getArgParam4,fromType;
    LinkedList<ComPassListData> linkedList = new LinkedList<>();
    Animation animation ;
    HashSet<String> hashSetGid  = new HashSet<>();
    private String method_type;
    ComPassListAdapter comPassListAdapter;
    List<ComPassSearchListResult.DataBean> dataBeanList;
    private String jointdata;//购买的名字
    private int checked;//选中的位置
    //购买的id
    private String gid;

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
     *  玩法列号
     1 全场-独赢  ,2 全场-让球  , 3 全场-今日赛事-得分大小 ,4 足球今日赛事-波胆 ,4 半场(篮球)-滚球-得分大小，5 全场-今日赛事单双  ，
     6 足球今日赛事-总入球 ,7 足球今日赛事-全场/半场 8 综合过关，9 全场-滚球-让球，10 全场-滚球-得分大小，11 半场(足球)-独赢 ,12 半场(足球)-今日赛事-让球，
     13 半场-足球得分大小，16 足球(篮球)今日赛事-冠军，19 半场(足球)-滚球-让球 20 半场(足球)-滚球-得分大小，21 全场-滚球-独赢，31 半场(足球)-滚球-独赢，33 半场-篮球得分大小
     */
    private String line_type;
    private String odd_f_type;
    private String gold;
    private String ioradio_r_h;
    /**
     * 单双玩法投注传参
     * ODD 单
     * EVEN 双
     *
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
     */
    private String wtype;

    private String mLeague ,mTeamH,mTeamC;
    //滚球的数值
    private String ratio;

    private String buyOrderInfor,buyOrderTitle,buyOrderText;

    /**
     * 选择玩法和赔率，准备投注接口
     * order/order_prepare_api.php
     *
     * @param  order_method
     * FT_rm 滚球独赢，FT_re 滚球让球，FT_rou 滚球大小，FT_rt 滚球单双，FT_hrm 滚球半场独赢，FT_hre 滚球半场让球，FT_hrou 滚球半场大小，
     * FT_m 独赢，FT_r 让球，FT_ou 大小，FT_t 单双，FT_hm 半场独赢，FT_hr 半场让球，FT_hou 半场大小，
     * BK_re 滚球让球，BK_rou 滚球大小，BK_m 独赢，BK_r 让球，BK_ou 大小，BK_t 单双，BK_ouhc 球队得分大小
     * @param  gid
     * @param  type  H 主队 C 客队  N 和
     * @param  wtype  M 独赢，R 让球，大小 OU，单双 EO，半场独赢 HM，半场让球 HR，半场大小 HOU
     * @param  rtype  ODD 单 EVEN 双
     * @param  odd_f_type  H
     * @param  error_flag
     * @param  order_type
     */

    private String  pappRefer, porder_method,  pgid,  ptype,  pwtype,  prtype, podd_f_type,  perror_flag,  porder_type;


    OptionsPickerView optionsPickerViewState;
    OptionsPickerView optionsPickerViewTime;
    LeagueDetailSearchListContract.Presenter presenter;


    private ScheduledExecutorService executorService;
    onWaitingThread onWaitingThread = new onWaitingThread();
    private int sendAuthTime = HGConstant.ACTION_SEND_AUTH_CODE;
    private int sorttype = 1;
    private String userMoney,M_League ;


    //请求所有玩法用
    String gtype = "";
    String showtype = "";

    static List<String> stateList = new ArrayList<String>();
    static {
        stateList.add("联盟排序");
        stateList.add("时间排序");
    }

    public static LeagueDetailSearchListFragment newInstance(List<String> param1) {
        LeagueDetailSearchListFragment fragment = new LeagueDetailSearchListFragment();
        Bundle args = new Bundle();
        args.putStringArrayList(ARG_PARAM1, ArrayListHelper.convertListToArrayList(param1));
        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            typeId = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            more = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            moreGid = getArguments().getStringArrayList(ARG_PARAM1).get(2);
            getArgParam4 = getArguments().getStringArrayList(ARG_PARAM1).get(3);//滚盘 今日 早盘
            fromType = getArguments().getStringArrayList(ARG_PARAM1).get(4);//来自哪里的数据 1 2 3 4 5 6 7
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(5);//用户金额
            M_League  = getArguments().getStringArrayList(ARG_PARAM1).get(6);//让球大小  综合过关
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_league_detail;
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        animation = AnimationUtils.loadAnimation(getContext(),R.anim.rotate_clockwise);
        EventBus.getDefault().register(this);
        onSartTime();
        onRepertData("","");
    }

    private void onRepertData(String data1,String data2) {
        GameLog.log("来自哪里的球赛 ： "+fromType);
        switch (fromType){
            case "1":       //滚球足球
                gtype = "FT";
                showtype = "RB";
                cate = "FT_RB";
                active = "1";
                break;
            case "2":       //滚球篮球
                gtype = "BK";
                showtype = "RB";
                cate = "BK_RB";
                active = "2";
                break;
            case "3":       //今日足球
                gtype = "FT";
                showtype = "FT";
                cate = "FT";
                active = "1";
                break;
            case "4":       //今日篮球
                gtype = "BK";
                showtype = "FT";
                cate = "BK";
                active = "2";
                break;
            case "5":       //早盘足球
                gtype = "FT";
                showtype = "FU";
                cate = "FT";
                active = "11";
                break;
            case "6":       //早盘篮球
                gtype = "BK";
                showtype = "FU";
                cate = "BK";
                active = "22";
                break;
        }
    }

    @Override
    public void showMessage(String message) {
        super.showMessage(message);
        ivLeagueDetailRefresh.clearAnimation();
        //tvLeagueDetailSearchName.setText("暂无赛事");
    }

    @Override
    public void postLeagueDetailSearchListResult(LeagueDetailSearchListResult leagueDetailSearchListResult) {
        GameLog.log("返回的列表是："+leagueDetailSearchListResult.toString());
        ivLeagueDetailRefresh.clearAnimation();
        if(!Check.isNull(leagueDetailSearchListResult.getData())&&leagueDetailSearchListResult.getData().size()>0){
            tvLeagueDetailSearchName.setText(leagueDetailSearchListResult.getData().get(0).getLeague());
        }else{
            tvLeagueDetailSearchName.setText("暂无赛事");
        }
        lvLeagueSearchList.setAdapter(new LeagueDetailListAdapter(getContext(),R.layout.item_league_detail, leagueDetailSearchListResult.getData()));
        lvLeagueSearchList.setVisibility(View.VISIBLE);
        tvLeagueSearchNoData.setVisibility(View.GONE);
    }

    @Override
    public void postComPassSearchListResult(ComPassSearchListResult comPassSearchListResult) {
        ivLeagueDetailRefresh.clearAnimation();
        //GameLog.log("返回的列表是："+comPassSearchListResult.toString());
        if(!Check.isNull(comPassSearchListResult.getData())&&comPassSearchListResult.getData().size()>0){
            lvLeagueSearchList.setVisibility(View.VISIBLE);
            tvLeagueSearchNoData.setVisibility(View.GONE);
            tvLeagueDetailSearchName.setText(comPassSearchListResult.getData().get(0).getLeague());
            dataBeanList = comPassSearchListResult.getData();
            ArrayList<ComPassListData>  comPassListData = ZHBetManager.getSingleton().onShowViewListData();
            int dataSize =dataBeanList.size();//动态数据
            int comSize =comPassListData.size();//本地数据
            for(int k=0;k<comSize;++k){
                ComPassListData comPassListData1 = comPassListData.get(k);
                String gid2= comPassListData1.gid;
                for(int kk=0;kk<dataSize;++kk){
                    String gid1 = dataBeanList.get(kk).getGid();
                    if(gid1.equals(gid2)){
                        dataBeanList.get(kk).setIsChecked(comPassListData1.checked);
                    }
                }
            }
        }else{
            tvLeagueDetailSearchName.setText("暂无赛事");
            lvLeagueSearchList.setVisibility(View.GONE);
            tvLeagueSearchNoData.setVisibility(View.VISIBLE);
        }
        /*if(comPassListAdapter==null){
            comPassListAdapter = new ComPassListAdapter(getContext(),R.layout.item_league_detail, dataBeanList);
            lvLeagueSearchList.setAdapter(comPassListAdapter);
        }*/
        comPassListAdapter = null;
        comPassListAdapter = new ComPassListAdapter(getContext(),R.layout.item_league_detail, dataBeanList);
        lvLeagueSearchList.setAdapter(comPassListAdapter);
        comPassListAdapter.notifyDataSetChanged();

    }

    @Override
    public void postLeagueDetailSearchListNoDataResult(String noDataString) {
        ivLeagueDetailRefresh.clearAnimation();
        tvLeagueSearchNoData.setVisibility(View.VISIBLE);
        lvLeagueSearchList.setVisibility(View.GONE);
    }

    @Override
    public void postPrepareBetApiResult(PrepareBetResult prepareBetResult) {
        OrderNumber orderNumber = new OrderNumber();//无用
        orderNumber.setAppRefer("");
        orderNumber.setCate(cate);
        orderNumber.setGid(gid);
        orderNumber.setType(type);
        orderNumber.setActive(active);
        orderNumber.setLine_type(line_type);
        orderNumber.setOdd_f_type(odd_f_type);
        orderNumber.setIoradio_r_h(ioradio_r_h);
        orderNumber.setRtype(rtype);
        orderNumber.setWtype(wtype);
        orderNumber.setGold("");
        String userMoney2 = ACache.get(getContext()).getAsString(HGConstant.USERNAME_REMAIN_MONEY);
        if(!Check.isEmpty(userMoney2)&&userMoney2!=userMoney){
            userMoney = userMoney2;
        }
        GameLog.log("准备下注的数据是："+prepareBetResult.toString());
        //无用
        PrepareBetEvent prepareBetEvent = new PrepareBetEvent(buyOrderTitle,mLeague, mTeamH, mTeamC, ioradio_r_h, ratio,buyOrderText);
        PrepareRequestParams prepareRequestParams  = new PrepareRequestParams(cate,active,porder_method,pgid,ptype,pwtype,prtype,podd_f_type,perror_flag,porder_type);
        if(fromType.equals("1")){
            prepareRequestParams.autoOdd = "FT_order_re";
        }else if(fromType.equals("2")){
            prepareRequestParams.autoOdd = "BK_order_re";
        }else if(fromType.equals("4")||fromType.equals("6")){
            prepareRequestParams.autoOdd = "BK_order";
        }
        BetOrderSubmitDialog.newInstance(userMoney,"open",orderNumber,prepareBetEvent,prepareRequestParams,prepareBetResult).show(getFragmentManager());
    }

    @Override
    public void postBetApiResult(BetResult betResult) {

    }

    @Override
    public void setPresenter(LeagueDetailSearchListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }


    public class LeagueDetailListAdapter extends AutoSizeAdapter<LeagueDetailSearchListResult.DataBean> {
        private Context context;

        public LeagueDetailListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(final ViewHolder holder, final LeagueDetailSearchListResult.DataBean dataList, final int position) {
            if("1".equals(dataList.getM_Type())){
                holder.setVisible(R.id.tv_M_Type,true);
            }else{
                if(!Check.isEmpty(dataList.getScore_h())) {
                    holder.setText(R.id.tv_M_Type, dataList.getScore_h() + "-" + dataList.getScore_c());
                }else{
                    holder.setText(R.id.tv_M_Type,"   ");
                }
            }
            holder.setText(R.id.tv_time,Check.isEmpty(dataList.getM_Date())?"":dataList.getM_Date()+" "+dataList.getM_Time());
            holder.setText(R.id.tv_showretime,dataList.getShowretime());
            holder.setText(R.id.tv_team_h,dataList.getTeam_h());
            if(fromType.equals("1")){
                if(dataList.getTeam_h().contains("角球数")){
                    holder.setVisible(R.id.ll_pay_all,false);
                }else{
                    holder.setVisible(R.id.ll_pay_all,true);
                }
            }

            holder.setText(R.id.tv_redcard_h,dataList.getRedcard_h());//红牌数
            holder.setText(R.id.tv_redcard_c,dataList.getRedcard_c());
            if(Check.isEmpty(dataList.getRedcard_h())){
                holder.setVisible(R.id.tv_redcard_h,false);
            }else{
                holder.setVisible(R.id.tv_redcard_h,true);
            }
            if(Check.isEmpty(dataList.getRedcard_c())){
                holder.setVisible(R.id.tv_redcard_c,false);
            }else{
                holder.setVisible(R.id.tv_redcard_c,true);
            }
            holder.setText(R.id.tv_ratio_mb_str,dataList.getRatio_mb_str());//主队让球数
            holder.setText(R.id.tv_ior_rh,dataList.getIor_RH());            //主队让球赔率

            holder.setText(R.id.tv_ratio_o_str,dataList.getRatio_o_str());//主队大小
            holder.setText(R.id.tv_ior_ouc,dataList.getIor_OUC());          //主队大小赔率
            holder.setText(R.id.tv_team_c,dataList.getTeam_c());
            holder.setText(R.id.tv_ratio_tg_str,dataList.getRatio_tg_str());//客队让球数
            holder.setText(R.id.tv_ior_rc,dataList.getIor_RC());            //客队让球赔率
            holder.setText(R.id.tv_ratio_u_str,dataList.getRatio_u_str());//客队大小
            holder.setText(R.id.tv_ior_ouh,dataList.getIor_OUH());          //客队大小赔率
            if("0".equals(dataList.getAll())||"".equals(dataList.getAll())){
                holder.setText(R.id.tv_pay_all,"更多玩法>");
            }else{
                holder.setVisible(R.id.ll_pay_all,true);
                holder.setText(R.id.tv_pay_all,dataList.getAll()+" 玩法>");
            }
            if(dataList.getStrong().equals("H")){       //主队让球
                holder.setText(R.id.tv_ratio_mb_str,dataList.getRatio());
                holder.setText(R.id.tv_ratio_tg_str,"");
                holder.setVisible(R.id.tv_ratio_mb_str,true);
                holder.setVisible(R.id.tv_ratio_tg_str,false);
            }else if(dataList.getStrong().equals("C")){//客队让球
                holder.setText(R.id.tv_ratio_tg_str,dataList.getRatio());
                holder.setText(R.id.tv_ratio_mb_str,"");
                holder.setVisible(R.id.tv_ratio_mb_str,false);
                holder.setVisible(R.id.tv_ratio_tg_str,true);
            }
            if(Check.isEmpty(dataList.getRatio_mb_str())&&Check.isEmpty(dataList.getIor_RH())){//主队让球
                holder.setText(R.id.tv_ratio_mb_str,"");
                holder.setBackgroundRes(R.id.ll_team_h_rang,R.mipmap.bet_lock);
            }else{
                holder.setBackgroundRes(R.id.ll_team_h_rang,R.drawable.wanfa_item_default);
            }

            if(Check.isEmpty(dataList.getRatio_tg_str())&&Check.isEmpty(dataList.getIor_RC())){//客队让球
                holder.setText(R.id.tv_ratio_tg_str,"");
                holder.setBackgroundRes(R.id.ll_team_c_rang,R.mipmap.bet_lock);
            }else{
                holder.setBackgroundRes(R.id.ll_team_c_rang,R.drawable.wanfa_item_default);
            }

            if(Check.isEmpty(dataList.getIor_OUC())){//主队大小 Check.isEmpty(dataList.getRatio_o_str())&&
                holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.mipmap.bet_lock);
                holder.setText(R.id.tv_ratio_o_str,"");
            }else{
                holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.drawable.wanfa_item_default);
            }

            if(Check.isEmpty(dataList.getIor_OUH())){//客队大小 Check.isEmpty(dataList.getRatio_u_str())&&
                holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.mipmap.bet_lock);
                holder.setText(R.id.tv_ratio_u_str,"");
            }else{
                holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.drawable.wanfa_item_default);
            }

            holder.setOnClickListener(R.id.ll_team_h_rang, new View.OnClickListener() {//主队让球
                @Override
                public void onClick(View view) {//让球 单场 主队
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    ioradio_r_h = dataList.getIor_RH();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = dataList.getRatio();
                    buyOrderText = mTeamH+" @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                    gid = dataList.getGid();
                    line_type = "2";
                    type = "H";
                    rtype = "H";
                    wtype = "";

                    porder_method = "FT_re";
                    pgid = dataList.getGid();
                    ptype = "H";
                    pwtype = "R";
                    prtype = "";

                    if(fromType.equals("1")){//滚球足球
                        line_type = "9";
                        buyOrderTitle = "足球（滚球） 让球";
                        porder_method = "FT_re";
                    }else if(fromType.equals("2")){//滚球篮球
                        buyOrderTitle = "篮球（滚球） 让球";
                        porder_method = "BK_re";
                    }else if(fromType.equals("3")){//今日足球
                        buyOrderTitle = "足球 让球";
                        porder_method = "FT_r";
                    }else if(fromType.equals("4")){//今日篮球
                        buyOrderTitle = "篮球 让球";
                        porder_method = "BK_r";
                    }else if(fromType.equals("5")){//早盘足球
                        buyOrderTitle = "足球 早盘 让球";
                        porder_method = "FT_r";
                    }else if(fromType.equals("6")){//早盘篮球
                        buyOrderTitle = "篮球 早盘 让球";
                        porder_method = "BK_r";
                    }
                    onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);

                }
            });

            holder.setOnClickListener(R.id.ll_team_h_daxiao, new View.OnClickListener() {//主队大小
                @Override
                public void onClick(View view) {// 大小 单场 主队
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    ioradio_r_h = dataList.getIor_OUC();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = "";
                    buyOrderText = dataList.getRatio_o_str().substring(0,1)+"<font color='#C9270B'>"+dataList.getRatio_o_str().substring(1)+"</font> @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                    gid = dataList.getGid();
                    line_type = "2";
                    type = "C";
                    type = "C";
                    rtype = "C";
                    wtype = "";

                    porder_method = "FT_rou";
                    pgid = dataList.getGid();
                    ptype = "C";
                    pwtype = "OU";
                    prtype = "";

                    if(fromType.equals("1")){//滚球足球
                        buyOrderTitle = "足球（滚球） 总分 大/小";
                        porder_method = "FT_rou";
                    }else if(fromType.equals("2")){//滚球篮球
                        buyOrderTitle = "篮球（滚球） 总分 大/小";
                        porder_method = "BK_rou";
                    }else if(fromType.equals("3")){//今日足球
                        buyOrderTitle = "足球 大/小";
                        porder_method = "FT_ou";
                    }else if(fromType.equals("4")){//今日篮球
                        buyOrderTitle = "篮球 大/小";
                        porder_method = "BK_ou";
                    }else if(fromType.equals("5")){//早盘足球
                        buyOrderTitle = "足球 早盘 大/小";
                        porder_method = "FT_ou";
                    }else if(fromType.equals("6")){//早盘篮球
                        buyOrderTitle = "篮球 早盘 大/小";
                        porder_method = "BK_ou";
                    }
                    if(fromType.equals("1")){
                        line_type = "10";
                    }else if(fromType.equals("3")){
                        line_type = "3";
                    }else if(fromType.equals("5")){
                        line_type = "3";
                    }else if(fromType.equals("6")){
                        line_type = "3";
                    }
                    if("1".equals(fromType)||"2".equals(fromType)){
                        wtype = "ROUH";
                    }else if("3".equals(fromType)||"4".equals(fromType)||"5".equals(fromType)||"6".equals(fromType)){
                        wtype = "OUH";
                    }
                    onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                }
            });

            holder.setOnClickListener(R.id.ll_team_c_rang, new View.OnClickListener() {//客队让球
                @Override
                public void onClick(View view) {//让球 单场 客队
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    ioradio_r_h = dataList.getIor_RC();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = dataList.getRatio();
                    buyOrderTitle = "单场让球";
                    buyOrderText = mTeamC+" @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                    gid = dataList.getGid();
                    line_type = "2";
                    type = "C";
                    rtype = "C";
                    wtype = "";

                    porder_method = "FT_re";
                    pgid = dataList.getGid();
                    ptype = "C";
                    pwtype = "R";
                    prtype = "";

                    if(fromType.equals("1")){//滚球足球
                        buyOrderTitle = "足球（滚球） 让球";
                        porder_method = "FT_re";
                    }else if(fromType.equals("2")){//滚球篮球
                        buyOrderTitle = "篮球（滚球） 让球";
                        porder_method = "BK_re";
                    }else if(fromType.equals("3")){//今日足球
                        buyOrderTitle = "足球 让球";
                        porder_method = "FT_r";
                    }else if(fromType.equals("4")){//今日篮球
                        buyOrderTitle = "篮球 让球";
                        porder_method = "BK_r";
                    }else if(fromType.equals("5")){//早盘足球
                        buyOrderTitle = "足球 早盘 让球";
                        porder_method = "FT_r";
                    }else if(fromType.equals("6")){//早盘篮球
                        buyOrderTitle = "篮球 早盘 让球";
                        porder_method = "BK_r";
                    }
                    if(fromType.equals("1")){
                        line_type = "9";
                    }
                    onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                }
            });

            holder.setOnClickListener(R.id.ll_team_c_daxiao, new View.OnClickListener() {//客队大小
                @Override
                public void onClick(View view) {//大小 单场 客队
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    ioradio_r_h = dataList.getIor_OUH();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = "";
                    buyOrderTitle = "单场大小";
                    buyOrderText = dataList.getRatio_u_str().substring(0,1)+"<font color='#C9270B'>"+dataList.getRatio_o_str().substring(1)+"</font> @ <font color='#C9270B'>"+ioradio_r_h+"</font>";

                    gid = dataList.getGid();
                    type = "H";
                    line_type = "2";
                    rtype = "H";
                    wtype = "";

                    porder_method = "FT_ou";
                    pgid = dataList.getGid();
                    ptype = "H";
                    pwtype = "OU";
                    prtype = "";

                    if(fromType.equals("1")){//滚球足球
                        buyOrderTitle = "足球（滚球） 总分 大/小";
                        porder_method = "FT_rou";
                    }else if(fromType.equals("2")){//滚球篮球
                        buyOrderTitle = "篮球（滚球） 总分 大/小";
                        porder_method = "BK_rou";
                    }else if(fromType.equals("3")){//今日足球
                        buyOrderTitle = "足球 大/小";
                        porder_method = "FT_ou";
                    }else if(fromType.equals("4")){//今日篮球
                        buyOrderTitle = "篮球 大/小";
                        porder_method = "BK_ou";
                    }else if(fromType.equals("5")){//早盘足球
                        porder_method = "FT_ou";
                        buyOrderTitle = "足球 早盘 大/小";
                    }else if(fromType.equals("6")){//早盘篮球
                        buyOrderTitle = "篮球 早盘 大/小";
                        porder_method = "BK_ou";
                    }
                    if(fromType.equals("1")){
                        line_type = "10";
                    }else if(fromType.equals("3")){
                        line_type = "3";
                    }else if(fromType.equals("5")){
                        line_type = "3";
                    }else if(fromType.equals("6")){
                        line_type = "3";
                    }
                    if("1".equals(fromType)||"2".equals(fromType)){
                        wtype = "ROUC";
                    }else if("3".equals(fromType)||"4".equals(fromType)||"5".equals(fromType)||"6".equals(fromType)){
                        wtype = "OUC";
                    }
                    onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                }
            });

            holder.setOnClickListener(R.id.ll_pay_all, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //showMessage("您点击了 更多玩法啊 "+dataList);
                    TextView tvType = holder.getView(R.id.tv_M_Type);
                    TextView tvTime = holder.getView(R.id.tv_time);
                    TextView tvShowTime = holder.getView(R.id.tv_showretime);
                    String fromString = tvType.getText().toString()+tvTime.getText().toString()+tvShowTime.getText().toString();
                    EventBus.getDefault().post(new PrepareGoEvent(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType,fromString));
                    //EventBus.getDefault().post(new StartBrotherEvent(PrepareBetFragment.newInstance(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType),SupportFragment.SINGLETASK));
                }
            });
        }

    }

    public class ComPassListAdapter extends AutoSizeAdapter<ComPassSearchListResult.DataBean> {
        private Context context;

        public ComPassListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        private void onResetChecked(){
            int size = dataBeanList.size();
            for(int k=0;k<size;++k){
                ComPassSearchListResult.DataBean dataBean = dataBeanList.get(k);
                dataBean.setIsChecked(0);
            }
        }

        @Override
        protected void convert(final ViewHolder holder, final ComPassSearchListResult.DataBean dataList, final int position) {
            /*if("1".equals(dataList.getM_Type())){
                holder.setVisible(R.id.tv_M_Type,true);
            }else{
                if(!Check.isEmpty(dataList.getScore_h())) {
                    holder.setText(R.id.tv_M_Type, dataList.getScore_h() + "-" + dataList.getScore_c());
                }
            }*/
            holder.setVisible(R.id.tv_M_Type,true);
            holder.setText(R.id.tv_time,"");
            holder.setText(R.id.tv_showretime,dataList.getDatetime());
            holder.setText(R.id.tv_team_h,dataList.getTeam_h());

            holder.setText(R.id.tv_ratio_mb_str,dataList.getRatio_mb_str());//主队让球数
            holder.setText(R.id.tv_ior_rh,dataList.getIor_PRH());            //主队让球赔率

            holder.setText(R.id.tv_ratio_o_str,dataList.getRatio_o_str());//主队大小
            holder.setText(R.id.tv_ior_ouc,dataList.getIor_POUC());          //主队大小赔率
            holder.setText(R.id.tv_team_c,dataList.getTeam_c());
            holder.setText(R.id.tv_ratio_tg_str,dataList.getRatio_tg_str());//客队让球数
            holder.setText(R.id.tv_ior_rc,dataList.getIor_PRC());            //客队让球赔率
            holder.setText(R.id.tv_ratio_u_str,dataList.getRatio_u_str());//客队大小
            holder.setText(R.id.tv_ior_ouh,dataList.getIor_POUH());          //客队大小赔率
            holder.setText(R.id.tv_pay_all,"所有玩法>");

            if(dataList.getStrong().equals("H")){       //主队让球
                holder.setText(R.id.tv_ratio_mb_str,dataList.getRatio());
                holder.setText(R.id.tv_ratio_tg_str,"");
                holder.setVisible(R.id.tv_ratio_mb_str,true);
                holder.setVisible(R.id.tv_ratio_tg_str,false);
            }else if(dataList.getStrong().equals("C")){//客队让球
                holder.setText(R.id.tv_ratio_tg_str,dataList.getRatio());
                holder.setText(R.id.tv_ratio_mb_str,"");
                holder.setVisible(R.id.tv_ratio_mb_str,false);
                holder.setVisible(R.id.tv_ratio_tg_str,true);
            }
            if(Check.isEmpty(dataList.getRatio_mb_str())&&Check.isEmpty(dataList.getIor_PRH())){//主队让球
                holder.setText(R.id.tv_ratio_mb_str,"");
                holder.setBackgroundRes(R.id.ll_team_h_rang,R.mipmap.bet_lock);
            }else{
                holder.setBackgroundRes(R.id.ll_team_h_rang,R.drawable.wanfa_item_default);
            }

            if(Check.isEmpty(dataList.getRatio_tg_str())&&Check.isEmpty(dataList.getIor_PRC())){//客队让球
                holder.setText(R.id.tv_ratio_tg_str,"");
                holder.setBackgroundRes(R.id.ll_team_c_rang,R.mipmap.bet_lock);
            }else{
                holder.setBackgroundRes(R.id.ll_team_c_rang,R.drawable.wanfa_item_default);
            }

            if(Check.isEmpty(dataList.getIor_POUC())){//主队大小 Check.isEmpty(dataList.getRatio_o_str())&&
                holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.mipmap.bet_lock);
                holder.setText(R.id.tv_ratio_o_str,"");
            }else{
                holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.drawable.wanfa_item_default);
            }

            if(Check.isEmpty(dataList.getIor_POUH())){//客队大小 Check.isEmpty(dataList.getRatio_u_str())&&
                holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.mipmap.bet_lock);
                holder.setText(R.id.tv_ratio_u_str,"");
            }else{
                holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.drawable.wanfa_item_default);
            }
            switch (dataList.getIsChecked()){
                case 0:
                    holder.setBackgroundRes(R.id.ll_team_h_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.drawable.wanfa_item_default);
                    break;
                case 1:
                    holder.setBackgroundRes(R.id.ll_team_h_rang,R.drawable.wanfa_item_checked);
                    holder.setBackgroundRes(R.id.ll_team_c_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.drawable.wanfa_item_default);
                    break;
                case 2:
                    holder.setBackgroundRes(R.id.ll_team_h_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_rang,R.drawable.wanfa_item_checked);
                    holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.drawable.wanfa_item_default);
                    break;
                case 3:
                    holder.setBackgroundRes(R.id.ll_team_h_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.drawable.wanfa_item_checked);
                    holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.drawable.wanfa_item_default);
                    break;
                case 4:
                    holder.setBackgroundRes(R.id.ll_team_h_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_rang,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_h_daxiao,R.drawable.wanfa_item_default);
                    holder.setBackgroundRes(R.id.ll_team_c_daxiao,R.drawable.wanfa_item_checked);
                    break;

            }

            holder.setOnClickListener(R.id.ll_team_h_rang, new View.OnClickListener() {//主队让球
                @Override
                public void onClick(View view) {//让球 单场 主队
                    //GameLog.log("当前点击的位置"+position);
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    jointdata = mLeague+mTeamH+mTeamC+dataList.getDatetime();
                    checked = 1;
                    if(dataList.getIsChecked()==1){
                        dataList.setIsChecked(0);
                    }else{
                        dataList.setIsChecked(1);
                        /*if(!onCheckHave()){
                            dataList.setIsChecked(1);
                        }else{
                            onResetChecked();
                            dataList.setIsChecked(0);
                        }*/
                    }
                    notifyDataSetInvalidated();
                    method_type = "PRH";

                    ioradio_r_h = dataList.getIor_PRH();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = dataList.getRatio();
                    gid = dataList.getGid();
                    line_type = "";
                    type = "";
                    rtype = "";
                    wtype = "P3";

                    porder_method = "PRH";
                    pgid = dataList.getGid();
                    ptype = "";
                    pwtype = "";
                    prtype = "";

                    onAddData();
                    //onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);

                }
            });

            holder.setOnClickListener(R.id.ll_team_h_daxiao, new View.OnClickListener() {//主队大小
                @Override
                public void onClick(View view) {// 大小 单场 主队
                    //GameLog.log("当前点击的位置"+position);
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    jointdata = mLeague+mTeamH+mTeamC+dataList.getDatetime();
                    checked = 3;
                    if(dataList.getIsChecked()==3){
                        dataList.setIsChecked(0);
                    }else{
                        dataList.setIsChecked(3);
                        /*if(!onCheckHave()){
                            dataList.setIsChecked(3);
                        }else{
                            onResetChecked();
                            dataList.setIsChecked(0);
                        }*/
                    }
                    notifyDataSetInvalidated();
                    method_type = "POUC";
                    ioradio_r_h = dataList.getIor_POUC();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = "";
                    gid = dataList.getGid();
                    line_type = "2";
                    type = "";
                    type = "";
                    rtype = "";
                    wtype = "P3";

                    porder_method = "POUC";
                    pgid = dataList.getGid();
                    ptype = "";
                    pwtype = "";
                    prtype = "P3";
                    onAddData();
                    //onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                }
            });

            holder.setOnClickListener(R.id.ll_team_c_rang, new View.OnClickListener() {//客队让球
                @Override
                public void onClick(View view) {//让球 单场 客队
                    //GameLog.log("当前点击的位置"+position);
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    jointdata = mLeague+mTeamH+mTeamC+dataList.getDatetime();
                    checked = 2;
                    if(dataList.getIsChecked()==2){
                        dataList.setIsChecked(0);
                    }else{
                        dataList.setIsChecked(2);
                       /* if(!onCheckHave()) {
                            dataList.setIsChecked(2);
                        }else{
                            onResetChecked();
                            dataList.setIsChecked(0);
                        }*/
                    }
                    notifyDataSetInvalidated();
                    method_type = "PRC";
                    ioradio_r_h = dataList.getIor_PRC();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = dataList.getRatio();
                    buyOrderTitle = "单场让球";
                    gid = dataList.getGid();
                    line_type = "2";
                    type = "";
                    rtype = "";
                    wtype = "P3";

                    porder_method = "PRC";
                    pgid = dataList.getGid();
                    ptype = "";
                    pwtype = "";
                    prtype = "P3";

                    onAddData();
                    //onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                }
            });

            holder.setOnClickListener(R.id.ll_team_c_daxiao, new View.OnClickListener() {//客队大小
                @Override
                public void onClick(View view) {//大小 单场 客队
                   // GameLog.log("当前点击的位置"+position);
                    mLeague = dataList.getLeague() ;
                    mTeamH = dataList.getTeam_h();
                    mTeamC = dataList.getTeam_c();
                    jointdata = mLeague+mTeamH+mTeamC+dataList.getDatetime();
                    checked = 4;
                    if(dataList.getIsChecked()==4){
                        dataList.setIsChecked(0);
                    }else {
                        dataList.setIsChecked(4);
                       /* if(!onCheckHave()) {
                            dataList.setIsChecked(4);
                        }else{
                            onResetChecked();
                            dataList.setIsChecked(0);
                        }*/
                    }
                    notifyDataSetInvalidated();
                    method_type = "POUH";
                    ioradio_r_h = dataList.getIor_POUH();
                    if(Check.isEmpty(ioradio_r_h)){
                        return;
                    }
                    ratio = "";
                    buyOrderTitle = "单场大小";

                    gid = dataList.getGid();
                    type = "";
                    line_type = "";
                    rtype = "";
                    wtype = "P3";

                    porder_method = "POUH";
                    pgid = dataList.getGid();
                    ptype = "";
                    pwtype = "";
                    prtype = "P3";
                    onAddData();
                    //onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                }
            });

            holder.setOnClickListener(R.id.ll_pay_all, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    gid = dataList.getGid();
                    TextView tvType = holder.getView(R.id.tv_M_Type);
                    TextView tvTime = holder.getView(R.id.tv_time);
                    TextView tvShowTime = holder.getView(R.id.tv_showretime);
                    String fromString = tvType.getText().toString()+tvTime.getText().toString()+tvShowTime.getText().toString();
                    GameLog.log("typeId = "+typeId+" more = "+more+" moreGid = "+moreGid+" M_League = "+M_League+" gid = "+gid+" userMoney = "+userMoney);
                    EventBus.getDefault().post(new PrepareGoZHEvent(typeId,more,moreGid,showtype,M_League,gid,userMoney,fromType,dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),fromString));
                    //EventBus.getDefault().post(new PrepareGoEvent(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType));
                    //showMessage("您点击了 更多玩法啊 "+dataList);
                    //EventBus.getDefault().post(new PrepareGoEvent(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType));
                    //EventBus.getDefault().post(new StartBrotherEvent(PrepareBetFragment.newInstance(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType),SupportFragment.SINGLETASK));
                }
            });
        }

    }

    @OnClick({R.id.tvLeagueDetailRefresh,R.id.tvLeagueSearchTime,R.id.btnLeagueSearch,R.id.btnLeagueSearchBackHome})
    public void onClickView (View view){
        switch (view.getId()){
            case R.id.tvLeagueDetailRefresh:
                if(null !=ivLeagueDetailRefresh){
                    ivLeagueDetailRefresh.startAnimation(animation);
                }
                onSartTime();
                break;
            case R.id.tvLeagueSearchTime:
                optionsPickerViewState.show();
                break;
            case R.id.btnLeagueSearch:
                GameLog.log("点击了所有球类 参数一是"+getArgParam4);
                EventBus.getDefault().post(new LeagueEvent(getArgParam4));
               // finish();
                break;
            case R.id.btnLeagueSearchBackHome:
                popTo(HandicapFragment.class,true);
                BottombarViewManager.getSingleton().onCloseView();
                break;

        }
    }

    private boolean onCheckHave(){
        boolean isHave=false;
        HashSet<String>  hashSet = ZHBetManager.getSingleton().onShowHash();
        if(hashSet.contains(jointdata)) {
            isHave = true;
        }
        return isHave;
    }

    private void onAddData(){
        if(ZHBetManager.getSingleton().onListSize()>=10){
            showMessage("不接受超过10串过关投注！");
            return;
        }
        ZHBetManager.getSingleton().onAddData(jointdata,gid,method_type,checked);
        GameLog.log("当前下单的数量："+ZHBetManager.getSingleton().onListSize());
        //floatNumber.setText(ZHBetManager.getSingleton().onListSize()+"");
        ZHBetViewManager.getSingleton().onShowNumber(ZHBetManager.getSingleton().onListSize()+"");
        onSartTime();
    }

    private void onCheckThirdMobilePay(String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype) {
       /* String thirdBankMoney = etDepositThirdBankMoney.getText().toString().trim();

        if(Check.isEmpty(thirdBankMoney)){
            showMessage("汇款金额必须是整数！");
            return;
        }*/
        GameLog.log("赔率："+ioradio_r_h+" 金额："+gold+" cate："+cate+" active："+active+" type："+type+" line_type："+line_type);
        buyOrderInfor = buyOrderTitle +"\n"+ mLeague + "\n" +mTeamH + " "+ratio + " v "+ mTeamC +"\n"+buyOrderText;
        GameLog.log("购买的消息是：\n"+buyOrderInfor);

        //PrepareBetEvent prepareBetEvent = new PrepareBetEvent(buyOrderTitle,mLeague, mTeamH, mTeamC, ioradio_r_h, ratio,buyOrderText);

        //PrepareRequestParams prepareRequestParams  = new PrepareRequestParams(pappRefer,porder_method,pgid,ptype,pwtype,prtype,podd_f_type,perror_flag,porder_type);
        presenter.postPrepareBetApi(pappRefer,porder_method,pgid,ptype,pwtype,prtype,podd_f_type,perror_flag,porder_type);

        /*OrderNumber orderNumber = new OrderNumber();
        orderNumber.setAppRefer("");
        orderNumber.setCate(cate);
        orderNumber.setGid(gid);
        orderNumber.setType(type);
        orderNumber.setActive(active);
        orderNumber.setLine_type(line_type);
        orderNumber.setOdd_f_type(odd_f_type);
        orderNumber.setIoradio_r_h(ioradio_r_h);
        orderNumber.setRtype(rtype);
        orderNumber.setWtype(wtype);
        orderNumber.setGold("");
        String userMoney2 = ACache.get(getContext()).getAsString(HGConstant.USERNAME_REMAIN_MONEY);
        if(!Check.isEmpty(userMoney2)&&userMoney2!=userMoney){
            userMoney = userMoney2;
        }*/

        //BetOrderDialog.newInstance(userMoney,buyOrderInfor,orderNumber).show(getFragmentManager());
        //BetOrderSubmitDialog.newInstance(userMoney,buyOrderInfor,orderNumber,prepareBetEvent).show(getFragmentManager());
       // presenter.postBetApi("",cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);

        //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(),thirdBankMoney,dataBean.getUserid(),dataBean.getId(),bankCode), SupportFragment.SINGLETASK));
    }


    //等待时长
    class onWaitingThread implements Runnable {
        @Override
        public void run() {
            if (sendAuthTime-- <= 0) {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                        onSartTime();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if(tvLeagueDetailRefresh!=null){
                            tvLeagueDetailRefresh.setText(""+ sendAuthTime);
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }

    private void onSartTime(){
        if(!Check.isEmpty(M_League)){
            //showtype 今日传空""/  早盘传future
            presenter.postComPassSearchList("",typeId,more,moreGid,getArgParam4.equals("2")?"":"future",M_League );
        }else{
            presenter.postLeagueDetailSearchList("",typeId,more,moreGid);
        }
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        switch (getArgParam4){
            case "1":
                sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_R;
                break;
            case "2":
                sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
                break;
            case "3":
                sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
                break;
        }
        onSendAuthCode();
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        executorService = Executors.newScheduledThreadPool(1);
        if(onWaitingThread ==null){
            onWaitingThread =  new onWaitingThread();
        }
        executorService.scheduleAtFixedRate(onWaitingThread, 0, 1000, TimeUnit.MILLISECONDS);
    }

    @Subscribe
    public void onMainEvent(CalosEvent calosEvent){
        onSartTime();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
            onWaitingThread = null;
        }
    }

    @Override
    public void onVisible() {
        super.onVisible();
        /*ScreenListener l = new ScreenListener(getContext());
        l.begin(new ScreenListener.ScreenStateListener() {

            @Override
            public void onUserPresent() {
                GameLog.log("onUserPresent onUserPresent");
            }

            @Override
            public void onScreenOn() {
                isScreenOff = false;
                GameLog.log("onScreenOn onScreenOn");
            }

            @Override
            public void onScreenOff() {
                isScreenOff = true;
                GameLog.log("onScreenOff onScreenOff");
            }
        });*/
    }
//    boolean isScreenOff ;

    /*@Override
    public void onSupportInvisible() {
        super.onSupportInvisible();
        content.postDelayed(new Runnable() {
            @Override
            public void run() {
                if(!isScreenOff){
                    onBackPressedSupport();
                }
            }
        },1500);

        GameLog.log("不可见的时候调用。。。。");
    }*/

    @Subscribe
    public void onHideLeagueDetail(HideLeagueDetailEvent hideLeagueDetailEvent){
        onBackPressedSupport();
    }

    @Override
    public boolean onBackPressedSupport() {
        //super.onBackPressedSupport();
        GameLog.log("===============================LeagueDetailSearchListFragment VVVVVVVVVVVVVVV ( onBackPressedSupport ) VVVVVVVVVVVVVVVVV。。。。");
        return true;
    }
}

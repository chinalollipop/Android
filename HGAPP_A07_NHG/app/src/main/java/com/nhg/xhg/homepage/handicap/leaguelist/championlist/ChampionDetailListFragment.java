package com.nhg.xhg.homepage.handicap.leaguelist.championlist;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.BaseExpandableListAdapter;
import android.widget.ExpandableListView;
import android.widget.ImageView;
import android.widget.TextView;

import com.nhg.common.util.Check;
import com.nhg.common.util.GameLog;
import com.nhg.xhg.Injections;
import com.nhg.xhg.R;
import com.nhg.xhg.base.HGBaseFragment;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.common.util.ACache;
import com.nhg.xhg.common.util.ArrayListHelper;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.common.widgets.NExpandableListView;
import com.nhg.xhg.data.ChampionDetailListResult;
import com.nhg.xhg.data.LeagueDetailSearchListResult;
import com.nhg.xhg.data.PrepareBetResult;
import com.nhg.xhg.homepage.handicap.BottombarViewManager;
import com.nhg.xhg.homepage.handicap.HandicapFragment;
import com.nhg.xhg.homepage.handicap.betapi.PrepareRequestParams;
import com.nhg.xhg.homepage.handicap.betnew.LeagueEvent;
import com.nhg.xhg.homepage.sportslist.bet.BetOrderSubmitDialog;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class ChampionDetailListFragment extends HGBaseFragment implements ChampionDetailListContract.View{

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
    @BindView(R.id.exChampionListView)
    NExpandableListView exChampionListView;
    @BindView(R.id.tvLeagueSearchNoData)
    TextView tvLeagueSearchNoData;
    @BindView(R.id.btnLeagueSearch)
    TextView btnLeagueSearch;
    LeagueDetailSearchListResult leagueDetailSearchListResult;
    MyExpandableAdapter myExAdapter;
    Animation animation;
    private String FStype, Mtype,fshowtype,M_League,getArgParam4,fromType;

    //购买的id
    private String gid;

    private String name;

    private String order_method;

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
    private String type ="";
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


    ChampionDetailListContract.Presenter presenter;


    private ScheduledExecutorService executorService;
    private int sendAuthTime = HGConstant.ACTION_SEND_AUTH_CODE;

    private int sorttype = 1;
    private String userMoney;


    //请求所有玩法用
    String gtype = "";
    String showtype = "";


    public static ChampionDetailListFragment newInstance(List<String> param1) {
        ChampionDetailListFragment fragment = new ChampionDetailListFragment();
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
            FStype = getArguments().getStringArrayList(ARG_PARAM1).get(0);
            Mtype = getArguments().getStringArrayList(ARG_PARAM1).get(1);
            fshowtype = getArguments().getStringArrayList(ARG_PARAM1).get(2);//滚盘 今日 早盘
            M_League = getArguments().getStringArrayList(ARG_PARAM1).get(3);//联赛名字
            fromType = getArguments().getStringArrayList(ARG_PARAM1).get(4);//来自哪里的数据 1 2 3 4 5 6 7
            userMoney = getArguments().getStringArrayList(ARG_PARAM1).get(5);//用户金额
        }
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_champion_detail;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        animation = AnimationUtils.loadAnimation(getContext(),R.anim.rotate_clockwise);
        tvLeagueDetailSearchName.setText(M_League);
        onSartTime();
        //onRepertData("","");
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
    public void postLeagueSearchChampionListResult(final ChampionDetailListResult championDetailListResult) {
        ivLeagueDetailRefresh.clearAnimation();
        GameLog.log("返回的列表是："+championDetailListResult.toString());
        if(!Check.isNull(championDetailListResult.getData())&&championDetailListResult.getData().size()>0){
           // tvLeagueDetailSearchName.setText(leagueDetailSearchListResult.getData().get(0).getLeague());
            if(Check.isNull(myExAdapter)){
                myExAdapter = new MyExpandableAdapter(getContext(),championDetailListResult);
                exChampionListView.setAdapter(myExAdapter);
                myExAdapter.expandGroup();
                exChampionListView.setOnChildClickListener(new ExpandableListView.OnChildClickListener() {
                    @Override
                    public boolean onChildClick(ExpandableListView parent, View v,int groupPosition, int childPosition, long id) {
                        gid = championDetailListResult.getData().get(groupPosition).getGid();
                        name = championDetailListResult.getData().get(groupPosition).getTeamsname();
                        rtype = championDetailListResult.getData().get(groupPosition).getItem().get(childPosition).getRtype();
                        wtype =  "FS";
                        order_method = "FT_nfs";
                        GameLog.log(" gid： "+championDetailListResult.getData().get(groupPosition).getGid()+
                                " Team_name_fs"+championDetailListResult.getData().get(groupPosition).getItem().get(childPosition).getTeam_name_fs()+
                                " Ratio "+championDetailListResult.getData().get(groupPosition).getItem().get(childPosition).getRatio()+
                                " Rtype "+championDetailListResult.getData().get(groupPosition).getItem().get(childPosition).getRtype());
                        onPrepareBetClick();
                        return true;
                    }
                });
            }else{
                myExAdapter.dataChanged(championDetailListResult);
            }
        }else{
            tvLeagueDetailSearchName.setText("暂无赛事");
        }
        exChampionListView.setVisibility(View.VISIBLE);
        tvLeagueSearchNoData.setVisibility(View.GONE);
    }

    @Override
    public void postLeagueSearchChampionListNoDataResult(String noDataString) {
        ivLeagueDetailRefresh.clearAnimation();
        tvLeagueSearchNoData.setVisibility(View.VISIBLE);
        exChampionListView.setVisibility(View.GONE);
    }

    //下注之前的准备动作  你懂的 神来之笔 哈哈哈
    private void onPrepareBetClick() {
        GameLog.log(" 购买之前的下注准备数据：\norder_method [" + order_method + "] gid [" + gid + "] type [" + type + "] wtype [" + wtype + "] rtype [" + rtype + "]");
        presenter.postPrepareBetApi("", order_method, gid, type, wtype, rtype, "", "", "");
    }

    @Override
    public void postPrepareBetApiResult(PrepareBetResult prepareBetResult) {
        //准备下注之前的动作  神来之笔呀   倚天长笑  哈哈哈哈哈
        String userMoney2 = ACache.get(getContext()).getAsString(HGConstant.USERNAME_REMAIN_MONEY);
        if (!Check.isEmpty(userMoney2) && userMoney2 != userMoney) {
            userMoney = userMoney2;
        }
        order_method = "FT_nfs";

        String cate = "", active = "";
        switch (fromType) {
            case "1":       //滚球足球
                /*gtype = "FT";
                showtype = "RB";*/
                cate = "FT_RB";
                active = "1";
                break;
            case "2":       //滚球篮球
                /*gtype = "BK";
                showtype = "RB";*/
                cate = "BK_RB";
                active = "2";
                break;
            case "3":       //今日足球
                /*gtype = "FT";
                showtype = "FT";*/
                cate = "FT";
                active = "1";
                break;
            case "4":       //今日篮球
                /*gtype = "BK";
                showtype = "FT";*/
                cate = "BK";
                active = "2";
                break;
            case "5":       //早盘足球
                /*gtype = "FT";
                showtype = "FU";*/
                cate = "FT";
                active = "11";
                break;
            case "6":       //早盘篮球
                /*gtype = "BK";
                showtype = "FU";*/
                cate = "BK";
                active = "22";
                break;
        }

        PrepareRequestParams prepareRequestParams = new PrepareRequestParams(cate, active, order_method, gid, type, wtype, rtype, "", "", name);
        prepareRequestParams.autoOdd = "FT_order_nfs";
        BetOrderSubmitDialog.newInstance(userMoney, "", null, null, prepareRequestParams, prepareBetResult).show(getFragmentManager());
    }

    @Override
    public void setPresenter(ChampionDetailListContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }



    @OnClick({R.id.tvLeagueDetailRefresh,R.id.tvLeagueSearchTime,R.id.btnLeagueSearch,R.id.btnLeagueSearchBackHome})
    public void onClickView (View view){
        switch (view.getId()){
            case R.id.tvLeagueDetailRefresh:
                ivLeagueDetailRefresh.startAnimation(animation);
                onSartTime();
                break;
            case R.id.tvLeagueSearchTime:
                break;
            case R.id.btnLeagueSearch:
                GameLog.log("点击了所有球类 参数一是"+getArgParam4+" fshowtype "+fshowtype);
                EventBus.getDefault().post(new LeagueEvent(fshowtype));
               // finish();
                break;
            case R.id.btnLeagueSearchBackHome:
                popTo(HandicapFragment.class,true);
                BottombarViewManager.getSingleton().onCloseView();
                break;

        }
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
        presenter.postLeagueSearchChampionList("","future",FStype,Mtype,M_League);
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        if(fshowtype.equals("1")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_R;
        }else if(fshowtype.equals("2")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
        }else{
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        }
        /*if(fromType.equals("1")||fromType.equals("2")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_R;
        }else if(fromType.equals("3")||fromType.equals("4")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
        }else{
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        }
        sendAuthTime = HGConstant.ACTION_SEND_AUTH_CODE;*/
        onSendAuthCode();
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(new onWaitingThread(), 0, 1000, TimeUnit.MILLISECONDS);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
    }

    class MyExpandableAdapter extends BaseExpandableListAdapter {

        private Context mContext;
        private ChampionDetailListResult groups;
        private ChampionDetailListResult.DataBean children;

        public MyExpandableAdapter(Context context, ChampionDetailListResult groups) {
            this.mContext = context;
            this.groups = groups;
        }

        public void dataChanged(ChampionDetailListResult groups){
            this.groups = groups;
            super.notifyDataSetChanged();
            //expandGroup();
        }

        public void expandGroup(){
            for (int i=0; i<groups.getData().size(); i++) {
                exChampionListView.expandGroup(i);
            }
        }

        // 组的个数
        @Override
        public int getGroupCount() {

            return groups.getData().size();
        }

        @Override
        public long getGroupId(int groupPosition) {

            return groupPosition;
        }

        // 根据组的位置，组的成员个数
        @Override
        public int getChildrenCount(int groupPosition) {
            // 根据groupPosition获取某一个组的长度
            //return children.getItem().size();
            return groups.getData().get(groupPosition).getItem().size();
        }

        @Override
        public Object getGroup(int groupPosition) {

            return groups.getData().get(groupPosition);
        }

        @Override
        public Object getChild(int groupPosition, int childPosition) {

            return groups.getData().get(groupPosition).getItem().get(childPosition);
            //return children.getItem().get(childPosition);
        }

        @Override
        public long getChildId(int groupPosition, int childPosition) {

            return childPosition;
        }

        @Override
        public boolean hasStableIds() {

            return false;
        }

        @Override
        public View getGroupView(int groupPosition, boolean isExpanded,
                                 View convertView, ViewGroup parent) {
            GpViewHolder gpViewHolder = null;
            if (convertView == null) {
                convertView = View.inflate(mContext, R.layout.item_champion, null);
                gpViewHolder = new GpViewHolder();
                gpViewHolder.img = (ImageView) convertView.findViewById(R.id.img);
                gpViewHolder.title = (TextView) convertView.findViewById(R.id.title);
                convertView.setTag(gpViewHolder);
            } else {
                gpViewHolder = (GpViewHolder) convertView.getTag();
            }
            if(isExpanded){
                gpViewHolder.img.setImageResource(R.mipmap.icon_ex_down);
            }else{
                gpViewHolder.img.setImageResource(R.mipmap.deposit_right);
            }
            gpViewHolder.title.setText(groups.getData().get(groupPosition).getTeamsname());
            return convertView;
        }

        @Override
        public View getChildView(int groupPosition, int childPosition,
                                 boolean isLastChild, View convertView, ViewGroup parent) {
            GpViewHolder gpViewHolder = null;
            if (convertView == null) {
                convertView = View.inflate(mContext, R.layout.item_champion_child, null);
                gpViewHolder = new GpViewHolder();
                gpViewHolder.title = (TextView) convertView.findViewById(R.id.champion_child_name);
                gpViewHolder.ratio = (TextView) convertView.findViewById(R.id.champion_child_ratio);
                convertView.setTag(gpViewHolder);
            } else {
                gpViewHolder = (GpViewHolder) convertView.getTag();
            }
            gpViewHolder.title.setText(groups.getData().get(groupPosition).getItem().get(childPosition).getTeam_name_fs());
            gpViewHolder.ratio.setText(groups.getData().get(groupPosition).getItem().get(childPosition).getRatio());
            //gpViewHolder.img.setImageResource(R.drawable.qq_kong);
            //gpViewHolder.title.setText(children.getItem().get(childPosition).getTeam_name_fs());
            //gpViewHolder.ratio.setText(children.getItem().get(childPosition).getRatio());
            return convertView;
        }



        @Override
        public boolean isChildSelectable(int groupPosition, int childPosition) {
            return true;
        }

         class GpViewHolder {
            public ImageView img;
            public TextView title;
            public TextView ratio;
        }

    }


}

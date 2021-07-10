package com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseExpandableListAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.GameShipHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.NExpandableListView;
import com.hgapp.a0086.data.GameAllPlayZHResult;
import com.hgapp.a0086.data.PrepareBetResult;
import com.hgapp.a0086.data.SwPDMD2TG;
import com.hgapp.a0086.homepage.handicap.betapi.PrepareRequestParams;
import com.hgapp.a0086.homepage.handicap.leaguedetail.CalosEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.ComPassListData;
import com.hgapp.a0086.homepage.sportslist.bet.BetOrderSubmitDialog;
import com.hgapp.a0086.homepage.sportslist.bet.SportMethodResult;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class PrepareBetZHFragment extends HGBaseFragment implements PrepareBetZHApiContract.View {

    private static final int VIEW_TYPE_1 = 1;//让球
    private static final int VIEW_TYPE_2 = 2;//大小
    private static final int VIEW_TYPE_3 = 3;//独赢
    private static final int VIEW_TYPE_4 = 4;//单双
    private static final String ARG_PARAM1 = "mLeague";
    private static final String ARG_PARAM2 = "mTeamH";
    private static final String ARG_PARAM3 = "mTeamC";
    private static final String ARG_PARAM4 = "gid";
    private static final String ARG_PARAM5 = "gtype";
    private static final String ARG_PARAM6 = "showtype";
    private static final String ARG_PARAM7 = "userMoney";
    private static final String ARG_PARAM8 = "fromType";
    //    @BindView(R.id.ivBetEventBack)
//    ImageView ivBetEventBack;
    @BindView(R.id.exZHListView)
    NExpandableListView exListView;
    @BindView(R.id.teamRFT)
    LinearLayout teamRFT;
    @BindView(R.id.teamLiveTime)
    TextView teamLiveTime;
    @BindView(R.id.tvBetEventName)
    TextView tvBetEventName;
    @BindView(R.id.ivBetEventRefresh)
    TextView ivBetEventRefresh;
    @BindView(R.id.MB_Team)
    TextView MBTeam;
    @BindView(R.id.TG_Team)
    TextView TGTeam;
    @BindView(R.id.MB_Team_number)
    TextView MBTeamNumber;
    @BindView(R.id.TG_Team_number)
    TextView TGTeamNumber;
    @BindView(R.id.teamVs)
    TextView teamVs;

    private PrepareBetZHApiContract.Presenter presenter;

    private String mLeague, mTeamH, mTeamC, gid, gtype, showtype, userMoney, fromType;

    //准备下注的数据
    private String order_method, rtype, type, wtype;
    //可盈金额的计算
    private String win_radio_r_h = "";
    private String orderType = " FT_order";
    //数据格式的转换
    private static List<String> MID = new ArrayList<>();
    private List<SportMethodResult.RqDanListBean> rq_dan_list = new ArrayList<>();
    private List<SportMethodResult.RqBanListBean> rq_ban_list = new ArrayList<>();
    private List<SportMethodResult.DxDanListBean> dx_dan_list = new ArrayList<>();
    private List<SportMethodResult.DxBanListBean> dx_ban_list = new ArrayList<>();
    private List<SportMethodResult.DsListBean> ds_list = new ArrayList<>();

    private ScheduledExecutorService executorService;
    private int sendAuthTime = HGConstant.ACTION_SEND_AUTH_CODE;
    // 数据源
    //private String[] groups = { "独赢", "让球", "大小" , "单/双" , "独赢-上半场", "让球-上半场", "大小-上半场" };
    MyExpandableAdapter myExpandableAdapter;
    ArrayList<PrepareBetDataAll> arrayListDataAll = new ArrayList<PrepareBetDataAll>();
    private String jointdata,pgid,pmothed_type,pchecked;
    int chedkedNumber =0;
    private PrepareGoZHEvent prepareGoZHEvent;
    public static PrepareBetZHFragment newInstance(PrepareGoZHEvent prepareGoZHEvent) {
        PrepareBetZHFragment fragment = new PrepareBetZHFragment();
        Bundle args = new Bundle();
        args.putParcelable(ARG_PARAM1, prepareGoZHEvent);
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    public static PrepareBetZHFragment newInstance(String mLeague, String mTeamH, String mTeamC, String gid, String gtype, String showtype, String userMoney, String fromType) {
        PrepareBetZHFragment fragment = new PrepareBetZHFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, mLeague);
        args.putString(ARG_PARAM2, mTeamH);
        args.putString(ARG_PARAM3, mTeamC);
        args.putString(ARG_PARAM4, gid);
        args.putString(ARG_PARAM5, gtype);
        args.putString(ARG_PARAM6, showtype);
        args.putString(ARG_PARAM7, userMoney);
        args.putString(ARG_PARAM8, fromType);
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if(getArguments() != null){
            prepareGoZHEvent = getArguments().getParcelable(ARG_PARAM1);
        }
        /*if (getArguments() != null) {
            mLeague = getArguments().getString(ARG_PARAM1);
            mTeamH = getArguments().getString(ARG_PARAM2);
            mTeamC = getArguments().getString(ARG_PARAM3);
            gid = getArguments().getString(ARG_PARAM4);
            gtype = getArguments().getString(ARG_PARAM5);
            showtype = getArguments().getString(ARG_PARAM6);
            userMoney = getArguments().getString(ARG_PARAM7);
            fromType = getArguments().getString(ARG_PARAM8);
            //sportsPlayMethodResult.getData().get(0).;
            GameLog.log("所有玩法时的数据展示是 mLeague：" + mLeague + " mTeamH：" + mTeamH + " mTeamC：" + mTeamC + " gid ：" + gid + " gtype：" + gtype + " showtype ：" + showtype + " userMoney ：" + userMoney + " fromType : " + fromType);
        }*/
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_prepare_bet_zh;
    }

    public void onPostGameData() {
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        if(prepareGoZHEvent.getFromType().equals("1")||prepareGoZHEvent.getFromType().equals("2")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_R;
        }else if(prepareGoZHEvent.getFromType().equals("3")||prepareGoZHEvent.getFromType().equals("4")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
        }else{
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        }
        onSendAuthCode();
        if(prepareGoZHEvent.getFromType().equals("4")||prepareGoZHEvent.getFromType().equals("6")){
            teamVs.setText("|");
            teamRFT.setBackground(getActivity().getDrawable(R.drawable.bg_bet_bk));
        }
        presenter.postGameAllBetsZH("",prepareGoZHEvent.getGtype(),prepareGoZHEvent.sorttype,prepareGoZHEvent.getMdata(),prepareGoZHEvent.getShowtype(),prepareGoZHEvent.getM_League(),prepareGoZHEvent.getGid());
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        tvBetEventName.setText(prepareGoZHEvent.getM_League());
        MBTeam.setText(prepareGoZHEvent.getmTeamH());
        TGTeam.setText(prepareGoZHEvent.getmTeamC());
        teamLiveTime.setText(prepareGoZHEvent.getFromString());
        onPostGameData();
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(new onWaitingThread(), 0, 1000, TimeUnit.MILLISECONDS);
    }

    @Override
    public void postGameAllBetsZH(GameAllPlayZHResult gameAllPlayZHResult) {
        //GameLog.log(""+gameAllPlayZHResult.toString());
        jointdata = gameAllPlayZHResult.getLeague()+ gameAllPlayZHResult.getTeam_h()+gameAllPlayZHResult.getTeam_c()+gameAllPlayZHResult.getDatetime();
        arrayListDataAll.clear();
        pgid = gameAllPlayZHResult.getGid();
        if(!Check.isEmpty(gameAllPlayZHResult.getIor_PRH())&&!Check.isEmpty(gameAllPlayZHResult.getIor_PRC())){
            PrepareBetDataAll prepareBetDataAllRang = new PrepareBetDataAll();//让球
            ArrayList<PrepareBetData> arrayListDataRang = new ArrayList<PrepareBetData>();
            PrepareBetData prepareBetDataRang = new PrepareBetData();
            prepareBetDataRang.type = VIEW_TYPE_1;
            prepareBetDataRang.strong = gameAllPlayZHResult.getStrong();
            prepareBetDataRang.ratioH = gameAllPlayZHResult.getIor_PRH();
            prepareBetDataRang.ratioC = gameAllPlayZHResult.getIor_PRC();
            prepareBetDataRang.ratioUp = gameAllPlayZHResult.getTeam_h();
            prepareBetDataRang.ratioDown = gameAllPlayZHResult.getTeam_c();
            prepareBetDataRang.ratioN = gameAllPlayZHResult.getRatio();//把主客队的比例放在N中
            prepareBetDataRang.ratioHMethod = "PRH";
            prepareBetDataRang.ratioCMethod = "PRC";
            arrayListDataRang.add(prepareBetDataRang);
            prepareBetDataAllRang.name = "让球";
            prepareBetDataAllRang.prepareBetData = arrayListDataRang;
            arrayListDataAll.add(prepareBetDataAllRang);
        }

        if(!Check.isEmpty(gameAllPlayZHResult.getIor_HPRH())&&!Check.isEmpty(gameAllPlayZHResult.getIor_HPRC())){
            PrepareBetDataAll prepareBetDataAllRangH = new PrepareBetDataAll();//让球  上半场
            ArrayList<PrepareBetData> arrayListDataRangH = new ArrayList<PrepareBetData>();
            PrepareBetData prepareBetDataRangH = new PrepareBetData();
            prepareBetDataRangH.type = VIEW_TYPE_1;
            prepareBetDataRangH.strong = gameAllPlayZHResult.getHstrong();
            prepareBetDataRangH.ratioH = gameAllPlayZHResult.getIor_HPRH();
            prepareBetDataRangH.ratioC = gameAllPlayZHResult.getIor_HPRC();
            prepareBetDataRangH.ratioUp = gameAllPlayZHResult.getTeam_h();
            prepareBetDataRangH.ratioDown = gameAllPlayZHResult.getTeam_c();
            prepareBetDataRangH.ratioN = gameAllPlayZHResult.getHratio();
            prepareBetDataRangH.ratioHMethod = "HPRH";
            prepareBetDataRangH.ratioCMethod = "HPRC";
            arrayListDataRangH.add(prepareBetDataRangH);

            prepareBetDataAllRangH.name = "让球-上半场";
            prepareBetDataAllRangH.prepareBetData = arrayListDataRangH;
            arrayListDataAll.add(prepareBetDataAllRangH);
        }
        if(!Check.isEmpty(gameAllPlayZHResult.getIor_POUC())&&!Check.isEmpty(gameAllPlayZHResult.getIor_POUH())){
            PrepareBetDataAll prepareBetDataAllDaxiao = new PrepareBetDataAll();//大小
            ArrayList<PrepareBetData> arrayListDataDaxiao = new ArrayList<PrepareBetData>();
            PrepareBetData prepareBetDataDaxiao = new PrepareBetData();
            prepareBetDataDaxiao.type = VIEW_TYPE_2;
            prepareBetDataDaxiao.ratioH = gameAllPlayZHResult.getIor_POUH();
            prepareBetDataDaxiao.ratioUp = gameAllPlayZHResult.getRatio_o_str();
            prepareBetDataDaxiao.ratioC = gameAllPlayZHResult.getIor_POUC();
            prepareBetDataDaxiao.ratioDown = gameAllPlayZHResult.getRatio_u_str();
            prepareBetDataDaxiao.ratioHMethod = "POUC";
            prepareBetDataDaxiao.ratioCMethod = "POUH";
            arrayListDataDaxiao.add(prepareBetDataDaxiao);

            prepareBetDataAllDaxiao.name = "大小";
            prepareBetDataAllDaxiao.prepareBetData =arrayListDataDaxiao;
            arrayListDataAll.add(prepareBetDataAllDaxiao);
        }

        if(!Check.isEmpty(gameAllPlayZHResult.getIor_HPOUC())&&!Check.isEmpty(gameAllPlayZHResult.getIor_HPOUH())){
            PrepareBetDataAll prepareBetDataAllDaxiaoH = new PrepareBetDataAll();//大小 上半场
            ArrayList<PrepareBetData> arrayListDataDaxiaoH = new ArrayList<PrepareBetData>();
            PrepareBetData prepareBetDataDaxiaoH = new PrepareBetData();
            prepareBetDataDaxiaoH.type = VIEW_TYPE_2;
            prepareBetDataDaxiaoH.ratioH = gameAllPlayZHResult.getIor_HPOUH();
            prepareBetDataDaxiaoH.ratioUp = gameAllPlayZHResult.getHratio_o_str();
            prepareBetDataDaxiaoH.ratioC = gameAllPlayZHResult.getIor_HPOUC();
            prepareBetDataDaxiaoH.ratioDown = gameAllPlayZHResult.getHratio_u_str();
            prepareBetDataDaxiaoH.ratioHMethod = "HPOUC";
            prepareBetDataDaxiaoH.ratioCMethod = "HPOUH";
            arrayListDataDaxiaoH.add(prepareBetDataDaxiaoH);

            prepareBetDataAllDaxiaoH.name = "大小-上半场";
            prepareBetDataAllDaxiaoH.prepareBetData =arrayListDataDaxiaoH;
            arrayListDataAll.add(prepareBetDataAllDaxiaoH);
        }

        if(!Check.isEmpty(gameAllPlayZHResult.getIor_MH())&&!Check.isEmpty(gameAllPlayZHResult.getIor_MC())&&!Check.isEmpty(gameAllPlayZHResult.getIor_MN())) {

            PrepareBetDataAll prepareBetDataAllDuYin = new PrepareBetDataAll();//独赢
            ArrayList<PrepareBetData> arrayListDataDuYin = new ArrayList<PrepareBetData>();
            PrepareBetData prepareBetDataDuYin = new PrepareBetData();
            prepareBetDataDuYin.type = VIEW_TYPE_3;
            prepareBetDataDuYin.ratioH = gameAllPlayZHResult.getIor_MH();
            prepareBetDataDuYin.ratioC = gameAllPlayZHResult.getIor_MC();
            prepareBetDataDuYin.ratioN = gameAllPlayZHResult.getIor_MN();
            prepareBetDataDuYin.ratioHName = gameAllPlayZHResult.getTeam_h();
            prepareBetDataDuYin.ratioCName = gameAllPlayZHResult.getTeam_c();
            prepareBetDataDuYin.ratioHMethod = "PMH";
            prepareBetDataDuYin.ratioCMethod = "PMC";
            prepareBetDataDuYin.ratioNMethod = "PMN";
            arrayListDataDuYin.add(prepareBetDataDuYin);

            prepareBetDataAllDuYin.name = "独赢";
            prepareBetDataAllDuYin.prepareBetData = arrayListDataDuYin;
            arrayListDataAll.add(prepareBetDataAllDuYin);
        }

        if(!Check.isEmpty(gameAllPlayZHResult.getIor_HPMH())&&!Check.isEmpty(gameAllPlayZHResult.getIor_HPMC())&&!Check.isEmpty(gameAllPlayZHResult.getIor_HPMN())) {
            PrepareBetDataAll prepareBetDataAllDuYinH = new PrepareBetDataAll();//独赢 上半场
            ArrayList<PrepareBetData> arrayListDataDuYinH = new ArrayList<PrepareBetData>();
            PrepareBetData prepareBetDataDuyinH = new PrepareBetData();
            prepareBetDataDuyinH.type = VIEW_TYPE_3;
            prepareBetDataDuyinH.ratioH = gameAllPlayZHResult.getIor_HPMH();
            prepareBetDataDuyinH.ratioC = gameAllPlayZHResult.getIor_HPMC();
            prepareBetDataDuyinH.ratioN = gameAllPlayZHResult.getIor_HPMN();
            prepareBetDataDuyinH.ratioHName = gameAllPlayZHResult.getTeam_h();
            prepareBetDataDuyinH.ratioCName = gameAllPlayZHResult.getTeam_c();
            prepareBetDataDuyinH.ratioHMethod = "HPMH";
            prepareBetDataDuyinH.ratioCMethod = "HPMC";
            prepareBetDataDuyinH.ratioNMethod = "HPMN";
            arrayListDataDuYinH.add(prepareBetDataDuyinH);

            prepareBetDataAllDuYinH.name = "独赢-上半场";
            prepareBetDataAllDuYinH.prepareBetData = arrayListDataDuYinH;
            arrayListDataAll.add(prepareBetDataAllDuYinH);
        }

        if(!Check.isEmpty(gameAllPlayZHResult.getIor_PO())&&!Check.isEmpty(gameAllPlayZHResult.getIor_PE())) {
            PrepareBetDataAll prepareBetDataAllDanshuang = new PrepareBetDataAll();//单双
            ArrayList<PrepareBetData> arrayListDataDanshuang = new ArrayList<PrepareBetData>();
            PrepareBetData prepareBetDataDanshuang = new PrepareBetData();
            prepareBetDataDanshuang.type = VIEW_TYPE_4;
            prepareBetDataDanshuang.ratioH = gameAllPlayZHResult.getIor_PO();
            prepareBetDataDanshuang.ratioC = gameAllPlayZHResult.getIor_PE();
            prepareBetDataDanshuang.ratioHMethod = "PODD";
            prepareBetDataDanshuang.ratioCMethod = "PEVEN";
            arrayListDataDanshuang.add(prepareBetDataDanshuang);

            prepareBetDataAllDanshuang.name = "单双";
            prepareBetDataAllDanshuang.prepareBetData = arrayListDataDanshuang;
            arrayListDataAll.add(prepareBetDataAllDanshuang);
        }


        GameLog.log(""+arrayListDataAll.toString());

        ArrayList<ComPassListData>  comPassListData = ZHBetManager.getSingleton().onShowViewListData();
        int dataSize =arrayListDataAll.size();//动态数据
        int comSize =comPassListData.size();//本地数据
        for(int k=0;k<comSize;++k){
            ComPassListData comPassListData1 = comPassListData.get(k);
            String method_type= comPassListData1.method_type;
            String gid= comPassListData1.gid;
            GameLog.log("玩法："+method_type);
            for(int kk=0;kk<dataSize;++kk){
                int listSize = arrayListDataAll.get(kk).prepareBetData.size();
                for(int jj=0;jj<listSize;++jj){
                    if(gid.equals(pgid)){
                        if(method_type.equals(arrayListDataAll.get(kk).prepareBetData.get(jj).ratioHMethod)){
                            switch (method_type){
                                case "PRH":
                                case "POUC":
                                    chedkedNumber = 1;
                                    arrayListDataAll.get(kk).prepareBetData.get(jj).position = 1;
                                    break;
                                case "PRC":
                                case "POUH":
                                    chedkedNumber = 2;
                                    arrayListDataAll.get(kk).prepareBetData.get(jj).position = 2;
                                    break;
                            }
                            arrayListDataAll.get(kk).prepareBetData.get(jj).isChecked = 1;
                        }else if(method_type.equals(arrayListDataAll.get(kk).prepareBetData.get(jj).ratioCMethod)){
                            arrayListDataAll.get(kk).prepareBetData.get(jj).isChecked = 2;
                        }
                    }

                }

            }
        }

        myExpandableAdapter = new MyExpandableAdapter(getContext());
        exListView.setAdapter(myExpandableAdapter);
//        exListView.collapseGroup(0);
        int size = exListView.getCount();
        for (int i = 0; i < size; i++) {
            exListView.expandGroup(i);
        }
    }

    public class MyExpandableAdapter extends BaseExpandableListAdapter {
        View_Type_1 view_type_1 = null;
        View_Type_2 view_type_2 = null;
        View_Type_3 view_type_3 = null;
        View_Type_4 view_type_4 = null;
        private Context mContext;
        private LayoutInflater mLayoutInflater =null;
        public MyExpandableAdapter(Context context) {
            this.mContext = context;
            mLayoutInflater = LayoutInflater.from(mContext);
        }

        // 组的个数
        @Override
        public int getGroupCount() {

            //return groups.length;
           return arrayListDataAll.size();
        }

        @Override
        public long getGroupId(int groupPosition) {

            return groupPosition;
        }

        // 根据组的位置，组的成员个数
        @Override
        public int getChildrenCount(int groupPosition) {
            // 根据groupPosition获取某一个组的长度
           // return children[groupPosition].length;
            return arrayListDataAll.get(groupPosition).prepareBetData.size();
        }

        @Override
        public Object getGroup(int groupPosition) {

            //return groups[groupPosition];
            return arrayListDataAll.get(groupPosition);
        }

        @Override
        public Object getChild(int groupPosition, int childPosition) {

            //return children[groupPosition][childPosition].length();
            return arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition);
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
                convertView = View.inflate(mContext, R.layout.item_zh_handicap, null);
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
            gpViewHolder.title.setText(arrayListDataAll.get(groupPosition).name);
            return convertView;
        }

        /*public int getItemViewType(int groupPosition) {
            int p = groupPosition;
            if (p == 0) {
                return TYPE_1;
            } else if (p == 1) {
                return TYPE_2;
            } else if (p == 2) {
                return TYPE_3;
            } else {
                return TYPE_1;
            }
        }*/

        @Override
        public boolean isEmpty() {
            //groupArray为相应的父数据
            if(arrayListDataAll!=null && arrayListDataAll.size()>0){
                return false;
            }else {
                return true;
            }
        }

        public void onResetChecked(){
            int size = arrayListDataAll.size();
            for(int i=0;i<size;++i){
                PrepareBetDataAll prepareBetDataAll = arrayListDataAll.get(i);
                int csize = prepareBetDataAll.prepareBetData.size();
                for(int k=0;k<csize;++k){
                    PrepareBetData p =  prepareBetDataAll.prepareBetData.get(k);
                    p.isChecked = 0;
                }
            }
        }

        private void showLogData(){
            //onPostGameData();
            //GameLog.log("jointdata "+jointdata+" pgid "+pgid+" pmothed_type "+pmothed_type);
            if(ZHBetManager.getSingleton().onListSize()>=10){
                showMessage("不接受超过10串过关投注！");
                return;
            }
            ZHBetManager.getSingleton().onAddData(jointdata,pgid,pmothed_type,chedkedNumber);
            ZHBetViewManager.getSingleton().onShowNumber(ZHBetManager.getSingleton().onListSize()+"");
        }

        @Override
        public View getChildView(final int groupPosition, final int childPosition,
                                 boolean isLastChild, View convertView, ViewGroup parent) {
            //getItemViewType();
            final PrepareBetData prepareBetData =  arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition);
            switch (prepareBetData.type){
                case VIEW_TYPE_1:
//                    if(convertView == null){
                        convertView = mLayoutInflater.inflate(R.layout.item_view_type_1, null);
                        view_type_1 = new View_Type_1(convertView);
                        convertView.setTag(view_type_1);
                    /*}else{
                        view_type_1 = (View_Type_1) convertView.getTag();
                    }*/
                    view_type_1.tvTeamHRangName.setText(prepareBetData.ratioUp);
                    view_type_1.tvTeamCRangName.setText(prepareBetData.ratioDown);
                    if(!Check.isNull(prepareBetData.strong)&&prepareBetData.strong.equals("C")){
                        view_type_1.tv_ior_RH_ratio.setVisibility(View.GONE);
                        view_type_1.tv_ior_RC_ratio.setVisibility(View.VISIBLE);
                        view_type_1.tv_ior_RC_ratio.setText(prepareBetData.ratioN);
                    }else{
                        view_type_1.tv_ior_RH_ratio.setVisibility(View.VISIBLE);
                        view_type_1.tv_ior_RC_ratio.setVisibility(View.GONE);
                        view_type_1.tv_ior_RH_ratio.setText(prepareBetData.ratioN);
                    }
                    if(prepareBetData.isChecked==0){
                        view_type_1.ior_RH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else if(prepareBetData.isChecked==1){
                        view_type_1.ior_RH_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                        view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else {
                        view_type_1.ior_RH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                    }
                    view_type_1.tv_ior_RH.setText(prepareBetData.ratioH);
                    view_type_1.tv_ior_RC.setText(prepareBetData.ratioC);
                    view_type_1.rlTeamHRang.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            pmothed_type = prepareBetData.ratioHMethod;
                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            //showMessage("让球-主队");
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position ==1){
                                //view_type_1.ior_RH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                chedkedNumber = 0;
                                showLogData();
                                notifyDataSetInvalidated();
                                return;
                            }
                            if("PRH".equals(pmothed_type)){
                                chedkedNumber = 1;
                            }else{
                                chedkedNumber = 5;
                            }

                            showLogData();
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 1;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 1;
                            notifyDataSetInvalidated();
                        }
                    });
                    view_type_1.rlTeamCRang.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            pmothed_type = prepareBetData.ratioCMethod;

                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position == 2){
                                //view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                chedkedNumber = 0;
                                showLogData();
                                notifyDataSetInvalidated();
                                return;
                            }
                            if("PRC".equals(pmothed_type)){
                                chedkedNumber = 2;
                            }else{
                                chedkedNumber = 5;
                            }
                            showLogData();
                            //showMessage("让球-客队");
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 2;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 2;
                            notifyDataSetInvalidated();
                        }
                    });
                    break;
                case VIEW_TYPE_2:
//                    if(convertView==null){
                        convertView = mLayoutInflater.inflate(R.layout.item_view_type_2, null);
                        view_type_2 = new View_Type_2(convertView);
                        convertView.setTag(view_type_2);
                    /*}else{
                        view_type_2 = (View_Type_2) convertView.getTag();
                    }*/
                    view_type_2.ior_OUH.setText(prepareBetData.ratioH);
                    view_type_2.ior_OUC.setText(prepareBetData.ratioC);
                    view_type_2.ratio_o.setText(prepareBetData.ratioUp);
                    view_type_2.ratio_u.setText(prepareBetData.ratioDown);

                    if(prepareBetData.isChecked==0){
                        view_type_2.ior_OUH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_2.ior_OUC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else if(prepareBetData.isChecked==1){
                        view_type_2.ior_OUH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_2.ior_OUC_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                    }else {
                        view_type_2.ior_OUH_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                        view_type_2.ior_OUC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }

                    view_type_2.ior_OUC_Click.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            pmothed_type = prepareBetData.ratioHMethod;

                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            //showMessage("让球-主队");
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position ==1){
                                //view_type_1.ior_RH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                chedkedNumber = 0;
                                showLogData();
                                notifyDataSetInvalidated();
                                return;
                            }
                            if("POUC".equals(pmothed_type)){
                                chedkedNumber = 3;
                            }else{
                                chedkedNumber = 5;
                            }
                            showLogData();
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 1;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 1;
                            notifyDataSetInvalidated();
                            /*pmothed_type = prepareBetData.ratioHMethod;
                            showLogData();
                            //showMessage("大小-大");
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 1;
                            notifyDataSetInvalidated();*/
                        }
                    });
                    view_type_2.ior_OUH_Click.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            pmothed_type = prepareBetData.ratioCMethod;
                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position == 2){
                                //view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                chedkedNumber = 0;
                                showLogData();
                                notifyDataSetInvalidated();
                                return;
                            }
                            if("POUH".equals(pmothed_type)){
                                chedkedNumber = 4;
                            }else{
                                chedkedNumber = 5;
                            }
                            showLogData();
                            //showMessage("让球-客队");
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 2;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 2;
                            notifyDataSetInvalidated();
                           /* pmothed_type = prepareBetData.ratioCMethod;
                            showLogData();
                            //showMessage("大小-小");
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 2;
                            notifyDataSetInvalidated();*/
                        }
                    });
                    break;
                case VIEW_TYPE_3:
//                    if(convertView==null){
                        convertView = mLayoutInflater.inflate(R.layout.item_view_type_3, null);
                        view_type_3 = new View_Type_3(convertView);
                        convertView.setTag(view_type_3);
                    /*}else{
                        view_type_3 = (View_Type_3) convertView.getTag();
                    }*/
                    view_type_3.ior_MH.setText(prepareBetData.ratioH);
                    view_type_3.ior_MC.setText(prepareBetData.ratioC);
                    view_type_3.ior_MN.setText(prepareBetData.ratioN);
                    view_type_3.ior_MH_Name.setText(prepareBetData.ratioHName);
                    view_type_3.ior_MC_Name.setText(prepareBetData.ratioCName);
                    GameLog.log("当前的选中状态 "+prepareBetData.isChecked);
                    if(prepareBetData.isChecked == 0){
                        view_type_3.ior_MH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_3.ior_MC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_3.ior_MN_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else if(prepareBetData.isChecked ==1 ||prepareBetData.isChecked == 4){
                        view_type_3.ior_MH_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                        view_type_3.ior_MC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_3.ior_MN_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else if(prepareBetData.isChecked ==2 ||prepareBetData.isChecked == 5){
                        view_type_3.ior_MH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_3.ior_MC_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                        view_type_3.ior_MN_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else if(prepareBetData.isChecked ==3 ||prepareBetData.isChecked == 6){

                        view_type_3.ior_MH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_3.ior_MC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_3.ior_MN_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                    }

                    view_type_3.ior_MH_click.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            pmothed_type = prepareBetData.ratioHMethod;
                            showLogData();
                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            //showMessage("让球-主队");
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position ==1){
                                //view_type_1.ior_RH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                chedkedNumber = 0;
                                notifyDataSetInvalidated();
                                return;
                            }
                           
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 1;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 1;
                            notifyDataSetInvalidated();
                            /*pmothed_type = prepareBetData.ratioHMethod;
                            //showMessage("独赢-主队");
                            showLogData();
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 1;
                            notifyDataSetInvalidated();*/
                        }
                    });
                    view_type_3.ior_MC_click.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            //showMessage("独赢-客队");
                            pmothed_type = prepareBetData.ratioCMethod;
                            showLogData();
                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position == 2){
                                //view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                
                                notifyDataSetInvalidated();
                                return;
                            }
                            
                            //showMessage("让球-客队");
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 2;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 2;
                            notifyDataSetInvalidated();
                            /*pmothed_type = prepareBetData.ratioCMethod;
                            showLogData();
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 2;
                            notifyDataSetInvalidated();*/
                        }
                    });
                    view_type_3.ior_MN_click.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            pmothed_type = prepareBetData.ratioNMethod;
                            showLogData();
                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position == 3){
                                //view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                chedkedNumber = 0;
                                GameLog.log("当前的娃娃 0"+pmothed_type);
                                notifyDataSetInvalidated();
                                return;
                            }
                            //showMessage("让球-客队");
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 3;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 3;
                            notifyDataSetInvalidated();
                            //showMessage("独赢-和局");
                            /*pmothed_type = prepareBetData.ratioNMethod;
                            showLogData();
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 3;
                            notifyDataSetInvalidated();*/
                        }
                    });
                    break;
                case VIEW_TYPE_4:
//                    if(convertView == null){
                        convertView = mLayoutInflater.inflate(R.layout.item_view_type_4, null);
                        view_type_4 = new View_Type_4(convertView);
                        convertView.setTag(view_type_4);
//                    }else{
//                        view_type_4 = (View_Type_4) convertView.getTag();
//                    }
                    view_type_4.ior_EOO.setText(prepareBetData.ratioH);
                    view_type_4.ior_EOE.setText(prepareBetData.ratioC);

                    if(prepareBetData.isChecked==0){
                        view_type_4.ior_EOO_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_4.ior_EOE_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else if(prepareBetData.isChecked==1){
                        view_type_4.ior_EOO_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                        view_type_4.ior_EOE_H.setBackgroundResource(R.drawable.wanfa_item_default);
                    }else {
                        view_type_4.ior_EOO_H.setBackgroundResource(R.drawable.wanfa_item_default);
                        view_type_4.ior_EOE_H.setBackgroundResource(R.drawable.wanfa_item_checked);
                    }

                    view_type_4.ior_EOO_Click.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            pmothed_type = prepareBetData.ratioHMethod;
                            showLogData();
                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            //showMessage("让球-主队");
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position ==1){
                                //view_type_1.ior_RH_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                notifyDataSetInvalidated();
                                return;
                            }
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 1;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 1;
                            notifyDataSetInvalidated();
                            //showMessage("单双-单");
                            /*pmothed_type = prepareBetData.ratioHMethod;
                            showLogData();
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 1;
                            notifyDataSetInvalidated();*/
                        }
                    });
                    view_type_4.ior_EOE_Click.setOnClickListener(new View.OnClickListener() {
                        @Override
                        public void onClick(View view) {
                            //showMessage("单双-双");
                            /*pmothed_type = prepareBetData.ratioCMethod;
                            showLogData();
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 2;
                            notifyDataSetInvalidated();*/
                            pmothed_type = prepareBetData.ratioCMethod;
                            showLogData();
                            GameLog.log("当前的位置是"+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position);
                            if(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position == 2){
                                //view_type_1.ior_RC_H.setBackgroundResource(R.drawable.wanfa_item_default);
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 0;
                                arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 0;
                                notifyDataSetInvalidated();
                                return;
                            }
                            //showMessage("让球-客队");
                            onResetChecked();
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).position = 2;
                            arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).isChecked = 2;
                            notifyDataSetInvalidated();
                        }
                    });
                    break;
            }
            //gpViewHolder.img.setImageResource(R.drawable.qq_kong);
            //GameLog.log(" getChildView "+arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).type);
            //gpViewHolder.title.setText(arrayListDataAll.get(groupPosition).prepareBetData.get(childPosition).type);
            return convertView;
        }

        @Override
        public boolean isChildSelectable(int groupPosition, int childPosition) {

            return true;
        }

         class GpViewHolder {
            public ImageView img;
            TextView title;
        }
        class View_Type_1{
            public RelativeLayout rlTeamHRang;
            public RelativeLayout rlTeamCRang;
            public TextView tvTeamHRangName;
            public TextView tvTeamCRangName;
            public LinearLayout ior_RH_H;
            public LinearLayout ior_RC_H;
            public TextView tv_ior_RH_ratio;
            public TextView tv_ior_RC_ratio;
            public TextView tv_ior_RH;
            public TextView tv_ior_RC;
            public View_Type_1(View convertView){
                rlTeamHRang = (RelativeLayout) convertView.findViewById(R.id.rlTeamHRang);
                rlTeamCRang = (RelativeLayout) convertView.findViewById(R.id.rlTeamCRang);
                tvTeamHRangName = (TextView) convertView.findViewById(R.id.tvTeamHRangName);
                tvTeamCRangName = (TextView) convertView.findViewById(R.id.tvTeamCRangName);
                tv_ior_RH_ratio = (TextView) convertView.findViewById(R.id.tv_ior_RH_ratio);
                tv_ior_RC_ratio = (TextView) convertView.findViewById(R.id.tv_ior_RC_ratio);
                ior_RC_H = (LinearLayout) convertView.findViewById(R.id.ior_RC_H);
                ior_RH_H = (LinearLayout) convertView.findViewById(R.id.ior_RH_H);
                tv_ior_RH = (TextView) convertView.findViewById(R.id.tv_ior_RH);
                tv_ior_RC = (TextView) convertView.findViewById(R.id.tv_ior_RC);
            }
        }

        class View_Type_2{
            RelativeLayout ior_OUC_Click;
            RelativeLayout ior_OUH_Click;
            LinearLayout ior_OUC_H;
            LinearLayout ior_OUH_H;
            TextView ratio_o;
            TextView ratio_u;
            TextView ior_OUH;
            TextView ior_OUC;
            public View_Type_2(View convertView){
                ior_OUC_Click = (RelativeLayout) convertView.findViewById(R.id.ior_OUC_Click);
                ior_OUH_Click = (RelativeLayout) convertView.findViewById(R.id.ior_OUH_Click);
                ior_OUC_H = (LinearLayout) convertView.findViewById(R.id.ior_OUC_H);
                ior_OUH_H = (LinearLayout) convertView.findViewById(R.id.ior_OUH_H);
                ratio_o = (TextView) convertView.findViewById(R.id.ratio_o);
                ratio_u = (TextView) convertView.findViewById(R.id.ratio_u);
                ior_OUH = (TextView) convertView.findViewById(R.id.ior_OUH);
                ior_OUC = (TextView) convertView.findViewById(R.id.ior_OUC);
            }
        }

        class View_Type_3{
            RelativeLayout ior_MH_click;
            RelativeLayout ior_MC_click;
            RelativeLayout ior_MN_click;
            LinearLayout ior_MH_H;
            LinearLayout ior_MC_H;
            LinearLayout ior_MN_H;
            TextView ior_MH_Name;
            TextView ior_MC_Name;
            TextView ior_MH;
            TextView ior_MC;
            TextView ior_MN;
            public View_Type_3(View convertView){
                ior_MH_click = (RelativeLayout) convertView.findViewById(R.id.ior_MH_click);
                ior_MC_click = (RelativeLayout) convertView.findViewById(R.id.ior_MC_click);
                ior_MN_click = (RelativeLayout) convertView.findViewById(R.id.ior_MN_click);
                ior_MH_H = (LinearLayout) convertView.findViewById(R.id.ior_MH_H);
                ior_MC_H = (LinearLayout) convertView.findViewById(R.id.ior_MC_H);
                ior_MN_H = (LinearLayout) convertView.findViewById(R.id.ior_MN_H);
                ior_MH_Name = (TextView) convertView.findViewById(R.id.ior_MH_Name);
                ior_MC_Name = (TextView) convertView.findViewById(R.id.ior_MC_Name);
                ior_MH = (TextView) convertView.findViewById(R.id.ior_MH);
                ior_MC = (TextView) convertView.findViewById(R.id.ior_MC);
                ior_MN = (TextView) convertView.findViewById(R.id.ior_MN);
            }
        }

        class View_Type_4{
            RelativeLayout ior_EOO_Click;
            RelativeLayout ior_EOE_Click;
            LinearLayout ior_EOO_H;
            LinearLayout ior_EOE_H;
            TextView ior_EOO;
            TextView ior_EOE;
            public View_Type_4(View convertView){
                ior_EOO_Click = (RelativeLayout) convertView.findViewById(R.id.ior_EOO_Click);
                ior_EOE_Click = (RelativeLayout) convertView.findViewById(R.id.ior_EOE_Click);
                ior_EOO_H = (LinearLayout) convertView.findViewById(R.id.ior_EOO_H);
                ior_EOE_H = (LinearLayout) convertView.findViewById(R.id.ior_EOE_H);
                ior_EOO = (TextView) convertView.findViewById(R.id.ior_EOO);
                ior_EOE = (TextView) convertView.findViewById(R.id.ior_EOE);
            }
        }
    }


    @Override
    public void postGameAllBetsZHFailResult(String message) {
        showMessage(message);
    }

    @Override
    public void setPresenter(PrepareBetZHApiContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public boolean onBackPressedSupport() {
        return true;
    }


    //波胆
    class SwPDlistAdapter extends AutoSizeRVAdapter<SwPDMD2TG> {
        private Context context;

        public SwPDlistAdapter(Context context, int layoutId, List<SwPDMD2TG> datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final SwPDMD2TG swPDMD2TG, final int position) {
            GameLog.log("当前位置是：" + position + " 名字是 " + swPDMD2TG.ior_H_down);
            holder.setText(R.id.sw_pd_up, swPDMD2TG.ior_H_up);
            holder.setText(R.id.sw_pd_down, GameShipHelper.formatNumber(swPDMD2TG.ior_H_down));
            if (Check.isNumericNull(swPDMD2TG.ior_H_down)) {
                holder.setVisible(R.id.sw_pd_up, false);
                holder.setVisible(R.id.sw_pd_down, false);
                holder.setBackgroundRes(R.id.sw_pd_click, R.mipmap.bet_lock);
            } else {
                holder.setBackgroundRes(R.id.sw_pd_click, R.drawable.wanfa_item_default);
            }

            holder.setOnClickListener(R.id.sw_pd_click_all, new View.OnClickListener() {

                @Override
                public void onClick(View view) {
                    if (Check.isNumericNull(swPDMD2TG.ior_H_down)) {
                        //showMessage("数据为空");
                        // GameLog.log(""+swDua.ior_DUA_Name);
                        return;
                    } else {
                        GameLog.log("ior_H_up " + swPDMD2TG.ior_H_up + " ior_H_down " + swPDMD2TG.ior_H_down + " order_method:" + swPDMD2TG.order_method + " rtype:" + swPDMD2TG.rtype + " wtype:" + swPDMD2TG.wtype);
                    }
                    if(fromType.equals("1")){
                        if("FT_hrpd".equals(swPDMD2TG.order_method)){
                            //半场
                            orderType = "FT_order_hre";
                        }else{
                            //全场
                            orderType = "FT_order_re";
                        }

                    }
                    order_method = swPDMD2TG.order_method;
                    rtype = swPDMD2TG.rtype;
                    wtype = swPDMD2TG.wtype;
                    type = "";
                }
            });
        }
    }


    public void postPrepareBetApiResult(PrepareBetResult prepareBetResult) {

        //准备下注之前的动作  神来之笔呀   倚天长笑  哈哈哈哈哈
        String userMoney2 = ACache.get(getContext()).getAsString(HGConstant.USERNAME_REMAIN_MONEY);
        if (!Check.isEmpty(userMoney2) && userMoney2 != userMoney) {
            userMoney = userMoney2;
        }

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

        PrepareRequestParams prepareRequestParams = new PrepareRequestParams(cate, active, order_method, gid, type, wtype, rtype, "", "", "");
        prepareRequestParams.autoOdd = orderType;
        BetOrderSubmitDialog.newInstance(userMoney, win_radio_r_h, null, null, prepareRequestParams, prepareBetResult).show(getFragmentManager());
    }


    //等待时长
    class onWaitingThread implements Runnable {
        @Override
        public void run() {
            if (sendAuthTime-- <= 0) {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        onPostGameData();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if (ivBetEventRefresh != null) {
                            ivBetEventRefresh.setText("" + sendAuthTime);
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }

    @OnClick(R.id.ivBetEventRefresh)
    public void onClickedView(){
        onPostGameData();
    }

    @Subscribe
    public void onMainEvent(CalosEvent calosEvent){
        onPostGameData();
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
        if (null != executorService) {
            executorService.shutdown();
            executorService.shutdownNow();
        }

    }

    private void onClearListData() {
        MID.clear();
        rq_dan_list.clear();
        rq_ban_list.clear();
        dx_dan_list.clear();
        dx_ban_list.clear();
        ds_list.clear();
    }

}

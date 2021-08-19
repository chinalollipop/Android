package com.hgapp.a0086.homepage.handicap.leaguedetail;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.bigkoo.pickerview.view.OptionsPickerView;
import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.adapters.AutoSizeAdapter;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.ArrayListHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.NListView;
import com.hgapp.a0086.data.BetResult;
import com.hgapp.a0086.data.ComPassSearchListResult;
import com.hgapp.a0086.data.LeagueDatailNewData;
import com.hgapp.a0086.data.LeagueDetailListDataResults;
import com.hgapp.a0086.data.LeagueDetailSearchListResult;
import com.hgapp.a0086.data.PrepareBetResult;
import com.hgapp.a0086.homepage.handicap.BottombarViewManager;
import com.hgapp.a0086.homepage.handicap.HandicapFragment;
import com.hgapp.a0086.homepage.handicap.betapi.PrepareRequestParams;
import com.hgapp.a0086.homepage.handicap.betnew.HideLeagueDetailEvent;
import com.hgapp.a0086.homepage.handicap.betnew.LeagueEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet.PrepareGoZHEvent;
import com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet.ZHBetManager;
import com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet.ZHBetViewManager;
import com.hgapp.a0086.homepage.sportslist.bet.BetOrderSubmitDialog;
import com.hgapp.a0086.homepage.sportslist.bet.OrderNumber;
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

    private String mLeague ,mTeamH,mTeamC,isMaster;
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

    ArrayList<LeagueDatailNewData> arrayListNewData = new ArrayList<LeagueDatailNewData>();
    LeagueDetailNewListAdapter leagueDetailNewListAdapter;
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

        //数据转换
        List<LeagueDetailSearchListResult.DataBean> listData = leagueDetailSearchListResult.getData();

        arrayListNewData.clear();
        //让球 cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype
        for(int x=0;x<listData.size();++x){
            LeagueDatailNewData leagueDatailNewData  = new LeagueDatailNewData();
            leagueDatailNewData.setGid(listData.get(x).getGid());
            leagueDatailNewData.setAll(listData.get(x).getAll());
            leagueDatailNewData.setScore_h(listData.get(x).getScore_h());
            leagueDatailNewData.setScore_c(listData.get(x).getScore_c());
            leagueDatailNewData.setM_Type(listData.get(x).getM_Type());
            leagueDatailNewData.setM_Time(listData.get(x).getM_Time());
            leagueDatailNewData.setM_Date(listData.get(x).getM_Date());
            leagueDatailNewData.setShowretime(listData.get(x).getShowretime());
            leagueDatailNewData.setLeague(listData.get(x).getLeague());
            leagueDatailNewData.setTeam_h(listData.get(x).getTeam_h());
            leagueDatailNewData.setTeam_c(listData.get(x).getTeam_c());
            leagueDatailNewData.setOrder_method("");

            List<LeagueDatailNewData.DataBean> listDataLast = new ArrayList<LeagueDatailNewData.DataBean>();
            LeagueDatailNewData.DataBean dataBean = new LeagueDatailNewData.DataBean();
            dataBean.setLeague("让球");
            dataBean.setPwtype("R");
            //让球主队
            if(fromType.equals("1")){//滚球足球
                line_type = "9";
                dataBean.setOrder_method("FT_re");
                dataBean.setBuyOrderTitle("足球（滚球） 让球");
            }else if(fromType.equals("2")){//滚球篮球
                dataBean.setOrder_method("BK_re");
                dataBean.setBuyOrderTitle("篮球（滚球） 让球");
            }else if(fromType.equals("3")){//今日足球
                dataBean.setOrder_method("FT_r");
                dataBean.setBuyOrderTitle("足球 让球");
            }else if(fromType.equals("4")){//今日篮球
                dataBean.setOrder_method("BK_r");
                dataBean.setBuyOrderTitle("篮球 让球");
            }else if(fromType.equals("5")){//早盘足球
                dataBean.setOrder_method("FT_r");
                dataBean.setBuyOrderTitle("足球 早盘 让球");
            }else if(fromType.equals("6")){//早盘篮球
                dataBean.setOrder_method("BK_r");
                dataBean.setBuyOrderTitle("篮球 早盘 让球");
            }

            dataBean.setGid(listData.get(x).getGid());

            dataBean.setRtype("H");
            dataBean.setBtype_h("H");

            dataBean.setTextUp(listData.get(x).getRatio_mb_str());
            dataBean.setTextUpStr(listData.get(x).getIor_RH());

            dataBean.setWtype("C");
            dataBean.setBtype_c("C");
            dataBean.setTextDown(listData.get(x).getRatio_tg_str());
            dataBean.setTextDownStr(listData.get(x).getIor_RC());


            listDataLast.add(dataBean);


            LeagueDatailNewData.DataBean dataBean2 = new LeagueDatailNewData.DataBean();
            dataBean2.setLeague("大小");
            dataBean2.setPwtype("OU");
            if(fromType.equals("1")){//滚球足球
                dataBean2.setOrder_method("FT_rou");
                dataBean2.setBuyOrderTitle("足球（滚球） 总分 大/小");
            }else if(fromType.equals("2")){//滚球篮球
                dataBean2.setOrder_method("BK_rou");
                dataBean2.setBuyOrderTitle("篮球（滚球） 总分 大/小");
            }else if(fromType.equals("3")){//今日足球
                dataBean2.setBuyOrderTitle("足球 大/小");
                dataBean2.setOrder_method("FT_ou");
            }else if(fromType.equals("4")){//今日篮球
                dataBean2.setOrder_method("BK_ou");
                dataBean2.setBuyOrderTitle("篮球 大/小");
            }else if(fromType.equals("5")){//早盘足球
                dataBean2.setOrder_method("FT_ou");
                dataBean2.setBuyOrderTitle("足球 早盘 大/小");
            }else if(fromType.equals("6")){//早盘篮球
                dataBean2.setOrder_method("BK_ou");
                dataBean2.setBuyOrderTitle("篮球 早盘 大/小");
            }

            dataBean2.setGid(listData.get(x).getGid());

            dataBean2.setRtype("O");
            dataBean2.setBtype_h("O");
            dataBean2.setTextUp(listData.get(x).getRatio_o_str());
            dataBean2.setTextUpStr(listData.get(x).getIor_OUC());

            dataBean2.setWtype("U");
            dataBean2.setBtype_c("U");
            dataBean2.setTextDown(listData.get(x).getRatio_u_str());
            dataBean2.setTextDownStr(listData.get(x).getIor_OUH());
            listDataLast.add(dataBean2);

            //独赢 全场
            String  BuyOrderTitle1="足球（滚球） 全场 独赢",
                    BuyOrderTitle2="篮球（滚球） 全场 独赢",
                    BuyOrderTitle3="足球 全场 独赢",
                    BuyOrderTitle4="篮球 全场 独赢",
                    BuyOrderTitle5="足球 早盘 全场 独赢",
                    BuyOrderTitle6="篮球 早盘 全场 独赢";

            LeagueDatailNewData.DataBean dataBean3 = new LeagueDatailNewData.DataBean();
            dataBean3.setLeague("独赢");
            dataBean3.setPwtype("M");
            if(fromType.equals("1")){//滚球足球
                dataBean3.setOrder_method("FT_rm");
                dataBean3.setBuyOrderTitle(BuyOrderTitle1);
            }else if(fromType.equals("2")){//滚球篮球
                dataBean3.setOrder_method("BK_rm");
                dataBean3.setBuyOrderTitle(BuyOrderTitle2);
            }else if(fromType.equals("3")){//今日足球
                dataBean3.setBuyOrderTitle(BuyOrderTitle3);
                dataBean3.setOrder_method("FT_m");
            }else if(fromType.equals("4")){//今日篮球
                dataBean3.setOrder_method("BK_m");
                dataBean3.setBuyOrderTitle(BuyOrderTitle4);
            }else if(fromType.equals("5")){//早盘足球
                dataBean3.setOrder_method("FT_m");
                dataBean3.setBuyOrderTitle(BuyOrderTitle5);
            }else if(fromType.equals("6")){//早盘篮球
                dataBean3.setOrder_method("BK_m");
                dataBean3.setBuyOrderTitle(BuyOrderTitle6);
            }

            dataBean3.setGid(listData.get(x).getGid());

            dataBean3.setRtype("H");
            dataBean3.setBtype_h("H");
            dataBean3.setTextUp("");
            dataBean3.setTextUpStr(listData.get(x).getIor_MH());

            dataBean3.setWtype("C");
            dataBean3.setBtype_c("C");
            dataBean3.setTextDown("");
            dataBean3.setTextDownStr(listData.get(x).getIor_MC());
            dataBean3.setTextM("和");
            dataBean3.setTextMStr(listData.get(x).getIor_MN());

            listDataLast.add(dataBean3);



            //独赢上半场
            String  BuyOrderTitle41="足球（滚球） 上半场 独赢",
                    BuyOrderTitle42="篮球（滚球） 上半场 独赢",
                    BuyOrderTitle43="足球 上半场 独赢",
                    BuyOrderTitle44="篮球 上半场 独赢",
                    BuyOrderTitle45="足球 早盘 上半场 独赢",
                    BuyOrderTitle46="篮球 早盘 上半场 独赢";

            LeagueDatailNewData.DataBean dataBean4 = new LeagueDatailNewData.DataBean();
            dataBean4.setLeague("独赢上半场");
            dataBean4.setPwtype("HM");
            if(fromType.equals("1")){//滚球足球
                dataBean4.setOrder_method("FT_hrm");
                dataBean4.setBuyOrderTitle(BuyOrderTitle41);
            }else if(fromType.equals("2")){//滚球篮球
                dataBean4.setOrder_method("BK_hrm");
                dataBean4.setBuyOrderTitle(BuyOrderTitle42);
            }else if(fromType.equals("3")){//今日足球
                dataBean4.setBuyOrderTitle(BuyOrderTitle43);
                dataBean4.setOrder_method("FT_hm");
            }else if(fromType.equals("4")){//今日篮球
                dataBean4.setOrder_method("BK_hm");
                dataBean4.setBuyOrderTitle(BuyOrderTitle44);
            }else if(fromType.equals("5")){//早盘足球
                dataBean4.setOrder_method("FT_hm");
                dataBean4.setBuyOrderTitle(BuyOrderTitle45);
            }else if(fromType.equals("6")){//早盘篮球
                dataBean4.setOrder_method("BK_hm");
                dataBean4.setBuyOrderTitle(BuyOrderTitle46);
            }

            dataBean4.setGid(listData.get(x).getGid());

            dataBean4.setRtype("H");
            dataBean4.setBtype_h("H");
            dataBean4.setTextUp("");
            dataBean4.setTextUpStr(listData.get(x).getIor_HMH());

            dataBean4.setWtype("C");
            dataBean4.setBtype_c("C");
            dataBean4.setTextDown("");
            dataBean4.setTextDownStr(listData.get(x).getIor_HMC());

            dataBean4.setTextM("和");
            dataBean4.setTextMStr(listData.get(x).getIor_HMN());
            listDataLast.add(dataBean4);


            //让球上半场
            String  BuyOrderTitle51="足球（滚球） 上半场 让球",
                    BuyOrderTitle52="篮球（滚球） 上半场 让球",
                    BuyOrderTitle53="足球 上半场 让球",
                    BuyOrderTitle54="篮球 上半场 让球",
                    BuyOrderTitle55="足球 早盘 上半场 让球",
                    BuyOrderTitle56="篮球 早盘 上半场 让球";

            LeagueDatailNewData.DataBean dataBean5 = new LeagueDatailNewData.DataBean();
            dataBean5.setLeague("让球上半场");
            dataBean5.setPwtype("HR");
            if(fromType.equals("1")){//滚球足球
                dataBean5.setOrder_method("FT_hre");
                dataBean5.setBuyOrderTitle(BuyOrderTitle51);
            }else if(fromType.equals("2")){//滚球篮球
                dataBean5.setOrder_method("BK_hre");
                dataBean5.setBuyOrderTitle(BuyOrderTitle52);
            }else if(fromType.equals("3")){//今日足球
                dataBean5.setBuyOrderTitle(BuyOrderTitle53);
                dataBean5.setOrder_method("FT_hr");
            }else if(fromType.equals("4")){//今日篮球
                dataBean5.setOrder_method("BK_hr");
                dataBean5.setBuyOrderTitle(BuyOrderTitle54);
            }else if(fromType.equals("5")){//早盘足球
                dataBean5.setOrder_method("FT_hr");
                dataBean5.setBuyOrderTitle(BuyOrderTitle55);
            }else if(fromType.equals("6")){//早盘篮球
                dataBean5.setOrder_method("BK_hr");
                dataBean5.setBuyOrderTitle(BuyOrderTitle56);
            }

            dataBean5.setGid(listData.get(x).getGid());

            dataBean5.setRtype("H");
            dataBean5.setBtype_h("H");
            dataBean5.setTextUp(listData.get(x).getHratio_mb_str());
            dataBean5.setTextUpStr(listData.get(x).getIor_HRH());

            dataBean5.setWtype("C");
            dataBean5.setBtype_c("C");
            dataBean5.setTextDown(listData.get(x).getHratio_tg_str());
            dataBean5.setTextDownStr(listData.get(x).getIor_HRC());
            listDataLast.add(dataBean5);


            //得分大小上半场
            String  BuyOrderTitle61="足球（滚球） 上半场 大小",
                    BuyOrderTitle62="篮球（滚球） 上半场 大小",
                    BuyOrderTitle63="足球 上半场 大小",
                    BuyOrderTitle64="篮球 上半场 大小",
                    BuyOrderTitle65="足球 早盘 上半场 大小",
                    BuyOrderTitle66="篮球 早盘 上半场 大小";

            LeagueDatailNewData.DataBean dataBean6 = new LeagueDatailNewData.DataBean();
            dataBean6.setLeague("得分大小上半场");
            dataBean6.setPwtype("HOU");
            if(fromType.equals("1")){//滚球足球
                dataBean6.setOrder_method("FT_hrou");
                dataBean6.setBuyOrderTitle(BuyOrderTitle61);
            }else if(fromType.equals("2")){//滚球篮球
                dataBean6.setOrder_method("BK_hrou");
                dataBean6.setBuyOrderTitle(BuyOrderTitle62);
            }else if(fromType.equals("3")){//今日足球
                dataBean6.setBuyOrderTitle(BuyOrderTitle63);
                dataBean6.setOrder_method("FT_hou");
            }else if(fromType.equals("4")){//今日篮球
                dataBean6.setOrder_method("BK_hou");
                dataBean6.setBuyOrderTitle(BuyOrderTitle64);
            }else if(fromType.equals("5")){//早盘足球
                dataBean6.setOrder_method("FT_hou");
                dataBean6.setBuyOrderTitle(BuyOrderTitle65);
            }else if(fromType.equals("6")){//早盘篮球
                dataBean6.setOrder_method("BK_hou");
                dataBean6.setBuyOrderTitle(BuyOrderTitle66);
            }

            dataBean6.setGid(listData.get(x).getGid());

            dataBean6.setRtype("H");
            dataBean6.setBtype_h("H");
            dataBean6.setTextUp(listData.get(x).getHratio_o_str());
            dataBean6.setTextUpStr(listData.get(x).getIor_HOUC());

            dataBean6.setWtype("C");
            dataBean6.setBtype_c("C");
            dataBean6.setTextDown(listData.get(x).getHratio_u_str());
            dataBean6.setTextDownStr(listData.get(x).getIor_HOUH());
            listDataLast.add(dataBean6);



            //进球单双
            String  BuyOrderTitle71="足球（滚球） 进球 单双",
                    BuyOrderTitle72="篮球（滚球） 进球 单双",
                    BuyOrderTitle73="足球 进球 单双",
                    BuyOrderTitle74="篮球 进球 单双",
                    BuyOrderTitle75="足球 早盘 进球单双",
                    BuyOrderTitle76="篮球 早盘 进球单双";

            LeagueDatailNewData.DataBean dataBean7 = new LeagueDatailNewData.DataBean();
            dataBean7.setLeague("进球单双");
            dataBean7.setPwtype("EO");
            if(fromType.equals("1")){//滚球足球
                dataBean7.setOrder_method("FT_rt");
                dataBean7.setBuyOrderTitle(BuyOrderTitle71);
            }else if(fromType.equals("2")){//滚球篮球
                dataBean7.setOrder_method("BK_rt");
                dataBean7.setBuyOrderTitle(BuyOrderTitle72);
            }else if(fromType.equals("3")){//今日足球
                dataBean7.setBuyOrderTitle(BuyOrderTitle73);
                dataBean7.setOrder_method("FT_t");
            }else if(fromType.equals("4")){//今日篮球
                dataBean7.setOrder_method("BK_t");
                dataBean7.setBuyOrderTitle(BuyOrderTitle74);
            }else if(fromType.equals("5")){//早盘足球
                dataBean7.setOrder_method("FT_t");
                dataBean7.setBuyOrderTitle(BuyOrderTitle75);
            }else if(fromType.equals("6")){//早盘篮球
                dataBean7.setOrder_method("BK_t");
                dataBean7.setBuyOrderTitle(BuyOrderTitle76);
            }

            dataBean7.setGid(listData.get(x).getGid());

            dataBean7.setRtype("H");
            dataBean7.setBtype_h("H");
            dataBean7.setTextUp("单");
            dataBean7.setTextUpStr(listData.get(x).getIor_EOO());

            dataBean7.setWtype("C");
            dataBean7.setBtype_c("C");
            dataBean7.setTextDown("双");
            dataBean7.setTextDownStr(listData.get(x).getIor_EOE());
            listDataLast.add(dataBean7);



            leagueDatailNewData.setData(listDataLast);
            arrayListNewData.add(leagueDatailNewData);

        }
        leagueDetailNewListAdapter = new LeagueDetailNewListAdapter(getContext(),R.layout.item_league_detail_new, arrayListNewData);

        lvLeagueSearchList.setAdapter(leagueDetailNewListAdapter);
        lvLeagueSearchList.setVisibility(View.VISIBLE);
        tvLeagueSearchNoData.setVisibility(View.GONE);
    }


    @Override
    public void postGameAllBetsResult(List<LeagueDetailListDataResults.DataBean> leagueDetailListDataResults,final String postion,String action) {
        GameLog.log("当前位置 【 "+postion+"】 \n数据结构 "+leagueDetailListDataResults);
        int size = arrayListNewData.size();
        if(Check.isNull(arrayListNewData)){
           return;
        }

        for(int k=0;k<size;k++){
            LeagueDatailNewData arrayListNewDatas =   arrayListNewData.get(k);
            if(postion.equals(k+"")){
                arrayListNewDatas.setGameData(leagueDetailListDataResults);
                arrayListNewDatas.setAction(action);

            }
        }
        leagueDetailNewListAdapter.notifyDataSetChanged();
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



    public class LeagueDetailNewListAdapter extends AutoSizeAdapter<LeagueDatailNewData> {
        private Context context;

        public LeagueDetailNewListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(final ViewHolder holder, final LeagueDatailNewData dataLists, final int position) {
            if ("1".equals(dataLists.getM_Type())) {
                holder.setVisible(R.id.tv_M_Type, true);
            } else {
                if (!Check.isEmpty(dataLists.getScore_h())) {
                    holder.setText(R.id.tv_M_Type, dataLists.getScore_h() + "-" + dataLists.getScore_c());
                } else {
                    holder.setText(R.id.tv_M_Type, "   ");
                }
            }
            holder.setText(R.id.tv_time, Check.isEmpty(dataLists.getM_Date()) ? "" : dataLists.getM_Date() + " " + dataLists.getM_Time());
            holder.setText(R.id.tv_showretime, dataLists.getShowretime());
            holder.setText(R.id.tv_team_h, dataLists.getTeam_h());
            holder.setText(R.id.tv_team_c, dataLists.getTeam_c());

            /*if(fromType.equals("1")){
                if(dataList.getTeam_h().contains("角球数")){
                    holder.setVisible(R.id.ll_pay_all,false);
                }else{
                    holder.setVisible(R.id.ll_pay_all,true);
                }
            }*/

            /*holder.setText(R.id.tv_redcard_h, dataLists.getRedcard_h());//红牌数
            holder.setText(R.id.tv_redcard_c, dataLists.getRedcard_c());
            if (Check.isEmpty(dataLists.getRedcard_h())) {
                holder.setVisible(R.id.tv_redcard_h, false);
            } else {
                holder.setVisible(R.id.tv_redcard_h, true);
            }
            if (Check.isEmpty(dataLists.getRedcard_c())) {
                holder.setVisible(R.id.tv_redcard_c, false);
            } else {
                holder.setVisible(R.id.tv_redcard_c, true);
            }*/


            /*holder.setText(R.id.tv_ratio_mb_str,dataList.getRatio_mb_str());//主队让球数
            holder.setText(R.id.tv_ior_rh,dataList.getIor_RH());            //主队让球赔率

            holder.setText(R.id.tv_ratio_o_str,dataList.getRatio_o_str());//主队大小
            holder.setText(R.id.tv_ior_ouc,dataList.getIor_OUC());          //主队大小赔率
            holder.setText(R.id.tv_team_c,dataList.getTeam_c());
            holder.setText(R.id.tv_ratio_tg_str,dataList.getRatio_tg_str());//客队让球数
            holder.setText(R.id.tv_ior_rc,dataList.getIor_RC());            //客队让球赔率
            holder.setText(R.id.tv_ratio_u_str,dataList.getRatio_u_str());//客队大小
            holder.setText(R.id.tv_ior_ouh,dataList.getIor_OUH());          //客队大小赔率*/


            LinearLayout mLinearLayout = (LinearLayout) holder.getView(R.id.linear);
            mLinearLayout.removeAllViews();
            //开始添加数据
            List<LeagueDatailNewData.DataBean> dataList = dataLists.getData();
            for (int x = 0; x < dataList.size(); x++) {
                final LeagueDatailNewData.DataBean dataBean= dataList.get(x);
                //寻找行布局，第一个参数为行布局ID，第二个参数为这个行布局需要放到那个容器上
                View view = LayoutInflater.from(getContext()).inflate(R.layout.item_league_detail_new1, mLinearLayout, false);
                TextView item_new_team_title = view.findViewById(R.id.item_new_team_title);

                LinearLayout item_new_h_team_up = view.findViewById(R.id.item_new_h_team_up);
                TextView item_new_h_tv_ratio_up = view.findViewById(R.id.item_new_h_tv_ratio_up);
                final TextView item_new_h_tv_ratio_down = view.findViewById(R.id.item_new_h_tv_ratio_down);
                item_new_h_team_up.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {

                        isMaster = dataLists.getAll();
                        isMaster = isMaster.equals("0")?"N":"Y";
                        ACache.get(getContext()).put("isMaster",isMaster);
                        mLeague = dataBean.getLeague() ;
                        mTeamH = dataLists.getTeam_h();
                        mTeamC = dataLists.getTeam_c();
                        ioradio_r_h = item_new_h_tv_ratio_down.getText().toString();
                        GameLog.log("当前选中的配料是："+ioradio_r_h);
                        if(Check.isEmpty(ioradio_r_h)){
                            return;
                        }
                        ratio = dataBean.getRatio();
                        gid = dataLists.getGid();
                        line_type = "2";
                        type = "H";
                        rtype = "H";
                        wtype = "";


                        porder_method = dataBean.getOrder_method();
                        buyOrderTitle = dataBean.getBuyOrderTitle();
                        pgid = dataLists.getGid();
                        ptype = "H";
                        pwtype = dataBean.getPwtype();
                        prtype = "";


                        switch (dataBean.getPwtype()){
                            case "R"://让球
                                buyOrderText = mTeamH+" @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                                break;
                            case "OU"://大小
                                ptype = "C";
                                buyOrderText = "大"+"<font color='#C9270B'>"+
                                        dataBean.getTextUpStr().substring(0)+"</font> @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                                break;
                            case "M"://独赢
                                prtype ="MH";
                                break;
                            case "HM":
                                prtype ="HMH";
                                break;
                            case "HR":
                                prtype ="HRH";
                                break;
                            case "HOU":
                                ptype = "C";
                                prtype ="HOUC";
                                break;
                            case "EO":
                                if("FT_rt".equals(porder_method)||"BK_rt".equals(porder_method)){
                                    prtype = "RODD";
                                }else{
                                    prtype = "ODD";
                                }

                                wtype = "EO";
                                break;
                        }
                        if("OU".equals(dataBean.getPwtype())){

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

                        }

                        onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);

                    }
                });

                LinearLayout item_new_c_team_down = view.findViewById(R.id.item_new_c_team_down);
                TextView item_new_c_tv_ratio_up = view.findViewById(R.id.item_new_c_tv_ratio_up);
                final TextView item_new_c_tv_ratio_down = view.findViewById(R.id.item_new_c_tv_ratio_down);
                item_new_c_team_down.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        isMaster = dataLists.getAll();
                        isMaster = isMaster.equals("0")?"N":"Y";
                        ACache.get(getContext()).put("isMaster",isMaster);
                        mLeague = dataBean.getLeague() ;
                        mTeamH = dataLists.getTeam_h();
                        mTeamC = dataLists.getTeam_c();
                        ioradio_r_h = item_new_c_tv_ratio_down.getText().toString();
                        GameLog.log("当前选中的配料是："+ioradio_r_h);
                        if(Check.isEmpty(ioradio_r_h)){
                            return;
                        }
                        ratio = "";
                        gid = dataLists.getGid();
                        line_type = "2";
                        type = "C";
                        rtype = "C";
                        wtype = "";

                        porder_method = dataBean.getOrder_method();
                        pgid = dataLists.getGid();
                        ptype = "C";
                        pwtype = dataBean.getPwtype();
                        prtype = "";

                        switch (dataBean.getPwtype()){
                            case "R"://让球
                                buyOrderText = mTeamC+" @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                                break;
                            case "OU"://大小
                                ptype = "H";
                                buyOrderText = "小"+"<font color='#C9270B'>"+
                                        dataBean.getTextDownStr().substring(0)+"</font> @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                                break;
                            case "M"://独赢
                                prtype ="MC";
                                break;
                            case "HM":
                                prtype ="HMC";
                                break;
                            case "HR":
                                prtype ="HRC";
                                break;
                            case "HOU":
                                ptype = "H";
                                prtype ="HOUH";
                                break;

                            case "EO":
                                if("FT_rt".equals(porder_method)||"BK_rt".equals(porder_method)){
                                    prtype = "REVEN";
                                }else{
                                    prtype = "EVEN";
                                }
                                wtype = "EO";
                                break;
                        }
                        if("OU".equals(dataBean.getPwtype())){

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

                        }

                        onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                    }
                });

                LinearLayout item_new_m_team_down = view.findViewById(R.id.item_new_m_team_down);
                if("M".equals(dataBean.getPwtype())||"HM".equals(dataBean.getPwtype())){
                    item_new_m_team_down.setVisibility(View.VISIBLE);
                }else{
                    item_new_m_team_down.setVisibility(View.GONE);

                }
                TextView item_new_m_tv_ratio_up = view.findViewById(R.id.item_new_m_tv_ratio_up);
                final TextView item_new_m_tv_ratio_down = view.findViewById(R.id.item_new_m_tv_ratio_down);
                item_new_m_team_down.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        isMaster = dataLists.getAll();
                        isMaster = isMaster.equals("0")?"N":"Y";
                        ACache.get(getContext()).put("isMaster",isMaster);
                        mLeague = dataBean.getLeague() ;
                        mTeamH = dataLists.getTeam_h();
                        mTeamC = dataLists.getTeam_c();
                        ioradio_r_h = item_new_m_tv_ratio_down.getText().toString();
                        GameLog.log("当前选中的配料是："+ioradio_r_h);
                        if(Check.isEmpty(ioradio_r_h)){
                            return;
                        }
                        ratio = "";
                        gid = dataLists.getGid();
                        line_type = "2";
                        type = "N";
                        rtype = "N";
                        wtype = "";

                        porder_method = dataBean.getOrder_method();
                        pgid = dataLists.getGid();
                        ptype = "N";
                        pwtype = dataBean.getPwtype();
                        prtype = "";

                        switch (dataBean.getPwtype()){
                            case "M"://独赢
                                buyOrderText = mTeamC+" @ <font color='#C9270B'>"+ioradio_r_h+"</font>";
                                /*if("FT_rm".equals(porder_method)||"BK_rm".equals(porder_method)){
                                    prtype = "MN";
                                }else{
                                    prtype = "MN";
                                }*/
                                prtype = "MN";
                                wtype = "M";
                                break;

                            case "HM":
                                /*if("FT_hrm".equals(porder_method)||"BK_hrm".equals(porder_method)){
                                    prtype = "HMN";
                                }else{
                                    prtype = "HMN";
                                }*/
                                prtype = "HMN";
                                wtype = "HM";
                                break;

                        }


                        onCheckThirdMobilePay(cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);
                    }
                });

                //和
                /*TextView item_new_m_tv_ratio_up =  view.findViewById(R.id.item_new_m_tv_ratio_up);
                TextView item_new_m_tv_ratio_down =  view.findViewById(R.id.item_new_m_tv_ratio_down);*/
                item_new_team_title.setText(dataBean.getLeague());
                item_new_h_tv_ratio_up.setText(dataBean.getTextUp());
                item_new_h_tv_ratio_down.setText(dataBean.getTextUpStr());
                /*if(!Check.isNull(dataBean.getTextUpStr())){
                    item_new_h_tv_ratio_up.setVisibility(View.VISIBLE);
                }else{
                    item_new_h_tv_ratio_up.setVisibility(View.GONE);
                }*/
                item_new_c_tv_ratio_up.setText(dataBean.getTextDown());
                item_new_c_tv_ratio_down.setText(dataBean.getTextDownStr());

                item_new_m_tv_ratio_up.setText(dataBean.getTextM());
                item_new_m_tv_ratio_down.setText(dataBean.getTextMStr());
                if(Check.isEmpty(dataBean.getTextUpStr())){//
                    item_new_h_tv_ratio_up.setText("");
                    item_new_h_team_up.setBackgroundResource(R.mipmap.bet_lock);
                }else{
                    if(Check.isEmpty(dataBean.getTextUp())) {//
                        item_new_h_tv_ratio_up.setVisibility(View.GONE);
                    }else{
                        item_new_h_tv_ratio_up.setVisibility(View.VISIBLE);
                    }

                    item_new_h_team_up.setBackgroundResource(R.drawable.wanfa_item_default);
                }
                if(Check.isEmpty(dataBean.getTextDownStr())){//
                    item_new_c_tv_ratio_up.setText("");
                    item_new_c_team_down.setBackgroundResource(R.mipmap.bet_lock);
                }else{
                    if(Check.isEmpty(dataBean.getTextDown())) {//
                        item_new_c_tv_ratio_up.setVisibility(View.GONE);
                    }else{
                        item_new_c_tv_ratio_up.setVisibility(View.VISIBLE);
                    }
                    item_new_c_team_down.setBackgroundResource(R.drawable.wanfa_item_default);
                }
                if(Check.isEmpty(dataBean.getTextMStr())){//
                    item_new_m_tv_ratio_up.setText("");
                    item_new_m_team_down.setBackgroundResource(R.mipmap.bet_lock);
                }else{
                    if(Check.isEmpty(dataBean.getTextM())) {//
                        item_new_m_tv_ratio_up.setVisibility(View.GONE);
                    }else{
                        item_new_m_tv_ratio_up.setVisibility(View.VISIBLE);
                    }
                    item_new_m_team_down.setBackgroundResource(R.drawable.wanfa_item_default);
                }

                //把行布局放到linear里
                mLinearLayout.addView(view);
            }

            holder.setOnClickListener(R.id.ll_pay_all, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //showMessage("您点击了 更多玩法啊 "+dataList);
                    isMaster = dataLists.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
                    TextView tvType = holder.getView(R.id.tv_M_Type);
                    TextView tvTime = holder.getView(R.id.tv_time);
                    TextView tvShowTime = holder.getView(R.id.tv_showretime);
                    String fromString = tvType.getText().toString()+tvTime.getText().toString()+tvShowTime.getText().toString();
                    //EventBus.getDefault().post(new PrepareGoEvent(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType,fromString));
                    //EventBus.getDefault().post(new StartBrotherEvent(PrepareBetFragment.newInstance(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType),SupportFragment.SINGLETASK));

                    presenter.postGameAllBets("",dataLists.getGid(),gtype,showtype,position+"","aa");
                }
            });
            holder.setOnClickListener(R.id.item_aa, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //showMessage("您点击了 更多玩法啊 "+dataList);
                    isMaster = dataLists.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
                    TextView tvType = holder.getView(R.id.tv_M_Type);
                    TextView tvTime = holder.getView(R.id.tv_time);
                    TextView tvShowTime = holder.getView(R.id.tv_showretime);
                    String fromString = tvType.getText().toString()+tvTime.getText().toString()+tvShowTime.getText().toString();
                    //EventBus.getDefault().post(new PrepareGoEvent(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType,fromString));
                    //EventBus.getDefault().post(new StartBrotherEvent(PrepareBetFragment.newInstance(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType),SupportFragment.SINGLETASK));

                    presenter.postGameAllBets("",dataLists.getGid(),gtype,showtype,position+"","aa");
                }
            });
            holder.setOnClickListener(R.id.item_bb, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //showMessage("您点击了 更多玩法啊 "+dataList);
                    isMaster = dataLists.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
                    TextView tvType = holder.getView(R.id.tv_M_Type);
                    TextView tvTime = holder.getView(R.id.tv_time);
                    TextView tvShowTime = holder.getView(R.id.tv_showretime);
                    String fromString = tvType.getText().toString()+tvTime.getText().toString()+tvShowTime.getText().toString();
                    //EventBus.getDefault().post(new PrepareGoEvent(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType,fromString));
                    //EventBus.getDefault().post(new StartBrotherEvent(PrepareBetFragment.newInstance(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType),SupportFragment.SINGLETASK));

                    presenter.postGameAllBets("",dataLists.getGid(),gtype,showtype,position+"","bb");
                }
            });

            //添加附属盘口
            List<LeagueDetailListDataResults.DataBean> gameDataList  = dataLists.getGameData();
            if(Check.isNull(gameDataList)){

                holder.setVisible(R.id.item_bottom, false);
                return;
            }
            holder.setVisible(R.id.item_bottom, true);


            GameLog.log("当前数据的大小 "+gameDataList.size());
            for(int k=0;k<gameDataList.size();k++){

                    LinearLayout tab_linear1 = (LinearLayout) holder.getView(R.id.tab_linear1);
                    tab_linear1.removeAllViews();
                    LinearLayout tab_linear2 = (LinearLayout) holder.getView(R.id.tab_linear2);
                    tab_linear2.removeAllViews();

                    View view = LayoutInflater.from(getContext()).inflate(R.layout.item_league_detail_new2, mLinearLayout, false);


                    LinearLayout item1_title = view.findViewById(R.id.item1_title);
                    LinearLayout item2_title = view.findViewById(R.id.item2_title);
                    TextView item1_ratio_up = view.findViewById(R.id.item1_ratio_up);
                    TextView item1_ratio_down = view.findViewById(R.id.item1_ratio_down);
                    TextView item2_ratio_up = view.findViewById(R.id.item2_ratio_up);
                    TextView item2_ratio_down = view.findViewById(R.id.item2_ratio_down);



                    View view2 = LayoutInflater.from(getContext()).inflate(R.layout.item_league_detail_new2, mLinearLayout, false);


                    LinearLayout item1_title2 = view2.findViewById(R.id.item1_title);
                    LinearLayout item2_title2 = view2.findViewById(R.id.item2_title);
                    TextView item1_ratio_up2 = view2.findViewById(R.id.item1_ratio_up);
                    TextView item1_ratio_down2 = view2.findViewById(R.id.item1_ratio_down);
                    TextView item2_ratio_up2 = view2.findViewById(R.id.item2_ratio_up);
                    TextView item2_ratio_down2 = view2.findViewById(R.id.item2_ratio_down);

                    switch (dataLists.getAction()){
                        case "aa":
                            item1_ratio_up.setText(gameDataList.get(k).getTeam_h());
                            item1_ratio_down.setText(gameDataList.get(k).getIor_RH());

                            item2_ratio_up.setText(gameDataList.get(k).getTeam_c());
                            item2_ratio_down.setText(gameDataList.get(k).getIor_RC());

                            item1_ratio_up2.setText(gameDataList.get(k).getTeam_h());
                            item1_ratio_down2.setText(gameDataList.get(k).getIor_HRH());

                            item2_ratio_up2.setText(gameDataList.get(k).getTeam_c());
                            item2_ratio_down2.setText(gameDataList.get(k).getIor_HRC());
                            break;
                        case "bb":
                            item1_ratio_up.setText("大"+gameDataList.get(k).getRatio_o());
                            item1_ratio_down.setText(gameDataList.get(k).getIor_OUC());

                            item2_ratio_up.setText("大"+gameDataList.get(k).getRatio_ho());
                            item2_ratio_down.setText(gameDataList.get(k).getIor_HOUC());

                            item1_ratio_up2.setText("小"+gameDataList.get(k).getRatio_u());
                            item1_ratio_down2.setText(gameDataList.get(k).getIor_OUH());

                            item2_ratio_up2.setText("小"+gameDataList.get(k).getRatio_hu());
                            item2_ratio_down2.setText(gameDataList.get(k).getIor_HOUH());
                            break;
                    }


                    tab_linear1.addView(view);
                    tab_linear2.addView(view2);

            }



        }

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
            /*if(fromType.equals("1")){
                if(dataList.getTeam_h().contains("角球数")){
                    holder.setVisible(R.id.ll_pay_all,false);
                }else{
                    holder.setVisible(R.id.ll_pay_all,true);
                }
            }*/

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
                holder.setText(R.id.tv_pay_all,"所有玩法>");
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
                    isMaster = dataList.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
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
                    isMaster = dataList.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
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
                    isMaster = dataList.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
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
                    isMaster = dataList.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
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
                    isMaster = dataList.getAll();
                    isMaster = isMaster.equals("0")?"N":"Y";
                    ACache.get(getContext()).put("isMaster",isMaster);
                    TextView tvType = holder.getView(R.id.tv_M_Type);
                    TextView tvTime = holder.getView(R.id.tv_time);
                    TextView tvShowTime = holder.getView(R.id.tv_showretime);
                    String fromString = tvType.getText().toString()+tvTime.getText().toString()+tvShowTime.getText().toString();
                    //EventBus.getDefault().post(new PrepareGoEvent(dataList.getLeague(),dataList.getTeam_h(),dataList.getTeam_c(),dataList.getGid(),gtype,showtype,userMoney,fromType,fromString));
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
        GameLog.log("rtype是：\n"+rtype);
        GameLog.log("赔率："+ioradio_r_h+" 金额："+gold+" cate："+cate+" active："+active+" type："+type+" line_type："+line_type+" porder_method："+porder_method);
        buyOrderInfor = buyOrderTitle +"\n"+ mLeague + "\n" +mTeamH + " "+ratio + " v "+ mTeamC +"\n"+buyOrderText;
        GameLog.log("购买的消息是：\n"+buyOrderInfor);

        //PrepareBetEvent prepareBetEvent = new PrepareBetEvent(buyOrderTitle,mLeague, mTeamH, mTeamC, ioradio_r_h, ratio,buyOrderText);

        //PrepareRequestParams prepareRequestParams  = new PrepareRequestParams(pappRefer,porder_method,pgid,ptype,pwtype,prtype,podd_f_type,perror_flag,porder_type);
        presenter.postPrepareBetApi(pappRefer,porder_method,pgid,ptype,pwtype,prtype,podd_f_type,perror_flag,porder_type,isMaster);

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
        if(Check.isNull(presenter)){
            presenter =   Injections.inject(null,this);
        }
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

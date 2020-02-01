package com.nhg.xhg.homepage.sportslist.bet;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.nhg.common.util.Check;
import com.nhg.common.util.GameLog;
import com.nhg.xhg.Injections;
import com.nhg.xhg.R;
import com.nhg.xhg.base.HGBaseFragment;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.common.util.ACache;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.data.BetResult;
import com.nhg.xhg.data.SportsPlayMethodRBResult;
import com.nhg.xhg.data.SportsPlayMethodResult;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class BetFragment extends HGBaseFragment implements BetContract.View{

    private static final String ARG_PARAMLEAGUE = "league";
    private static final String ARG_PARAMTYPE = "type";
    private static final String ARG_PARAMMID = "mid";
    private static final String ARG_PARAM0 = "param0";
    private static final String ARG_PARAM1 = "param1";
    private static final String ARG_PARAM2 = "param2";
    private static final String ARG_PARAM3 = "param3";
    private static final String ARG_PARAM4 = "param4";
    @BindView(R.id.ivBetEventBack)
    ImageView ivBetEventBack;
    @BindView(R.id.tvBetEventName)
    TextView tvBetEventName;
    @BindView(R.id.ivBetEventRefresh)
    TextView ivBetEventRefresh;
    @BindView(R.id.MB_Team)
    TextView MBTeam;
    @BindView(R.id.TG_Team)
    TextView TGTeam;


    @BindView(R.id.ll_bet_all_show)
    LinearLayout ll_bet_all_show;

    //独赢-单场
    @BindView(R.id.llMB_Win_Rate_ALL)
    LinearLayout llMB_Win_Rate_ALL;
    @BindView(R.id.lllMB_Win_Rate)
    LinearLayout lllMB_Win_Rate;

    @BindView(R.id.rlMB_Win_Rate_ALL)
    RelativeLayout rlMB_Win_Rate_ALL;
    @BindView(R.id.rlMB_Win_Rate_ALL_right)
    ImageView rlMB_Win_Rate_ALL_right;
    @BindView(R.id.rlMB_Win_Rate)
    RelativeLayout rlMBWinRate;
    @BindView(R.id.MB_Win_Rate_tv)
    TextView MBWinRateTv;
    @BindView(R.id.MB_Win_Rate)
    TextView MBWinRate;
    @BindView(R.id.TG_Win_Rate_tv)
    TextView TGWinRateTv;
    @BindView(R.id.TG_Win_Rate)
    TextView TGWinRate;
    @BindView(R.id.rlTG_Win_Rate)
    RelativeLayout rlTGWinRate;
    @BindView(R.id.M_Flat_Rate)
    TextView MFlatRate;
    @BindView(R.id.rlM_Flat_Rate)
    RelativeLayout rlMFlatRate;


    //独盈-半场
    @BindView(R.id.llMB_Win_Rate_H_ALL)
    LinearLayout llMB_Win_Rate_H_ALL;
    @BindView(R.id.lllMB_Win_Rate_H_ALL)
    LinearLayout lllMB_Win_Rate_H_ALL;

    @BindView(R.id.rlMB_Win_Rate_H_ALL)
    RelativeLayout rlMB_Win_Rate_H_ALL;
    @BindView(R.id.rlMB_Win_Rate_H_ALL_right)
    ImageView rlMB_Win_Rate_H_ALL_right;
    @BindView(R.id.rlMB_Win_Rate_H)
    RelativeLayout rlMBWinRateH;
    @BindView(R.id.MB_Win_Rate_H_tv)
    TextView MBWinRateHTv;
    @BindView(R.id.MB_Win_Rate_H)
    TextView MBWinRateH;
    @BindView(R.id.TG_Win_Rate_H_tv)
    TextView TGWinRateHTv;
    @BindView(R.id.TG_Win_Rate_H)
    TextView TGWinRateH;
    @BindView(R.id.rlTG_Win_Rate_H)
    RelativeLayout rlTGWinRateH;
    @BindView(R.id.M_Flat_Rate_H)
    TextView MFlatRateH;
    @BindView(R.id.rlM_Flat_Rate_H)
    RelativeLayout rlMFlatRateH;

    //单场让球
    @BindView(R.id.ll_bet_rq_dan_all)
    LinearLayout ll_bet_rq_dan_all;
    @BindView(R.id.rl_bet_rq_dan_line)
    RelativeLayout rl_bet_rq_dan_line;
    @BindView(R.id.rl_bet_rq_dan_line_right)
    ImageView rl_bet_rq_dan_line_right;
    @BindView(R.id.ll_bet_rq_dan_list_1)
    LinearLayout ll_bet_rq_dan_list_1;
    @BindView(R.id.ll_bet_rq_dan_list_2)
    LinearLayout ll_bet_rq_dan_list_2;
    @BindView(R.id.ll_bet_rq_dan_list_3)
    LinearLayout ll_bet_rq_dan_list_3;
    @BindView(R.id.ll_bet_rq_dan_list_4)
    LinearLayout ll_bet_rq_dan_list_4;
    @BindView(R.id.MB_LetB_Rate_tv_1)
    TextView MBLetBRateTv_1;
    @BindView(R.id.M_LetB_1)
    TextView M_LetB_1;
    @BindView(R.id.MB_LetB_Rate_1)
    TextView MBLetBRate_1;
    @BindView(R.id.rlMB_LetB_Rate_1)
    RelativeLayout rlMBLetBRate_1;
    @BindView(R.id.TG_LetB_Rate_tv_1)
    TextView TGLetBRateTv_1;
    @BindView(R.id.TG_LetB_Rate_1)
    TextView TGLetBRate_1;
    @BindView(R.id.TG_LetB_1)
    TextView TG_LetB_1;
    @BindView(R.id.rlTG_LetB_Rate_1)
    RelativeLayout rlTGLetBRate_1;
    @BindView(R.id.MB_LetB_Rate_tv_2)
    TextView MBLetBRateTv_2;
    @BindView(R.id.M_LetB_2)
    TextView M_LetB_2;
    @BindView(R.id.MB_LetB_Rate_2)
    TextView MBLetBRate_2;
    @BindView(R.id.rlMB_LetB_Rate_2)
    RelativeLayout rlMBLetBRate_2;
    @BindView(R.id.TG_LetB_Rate_tv_2)
    TextView TGLetBRateTv_2;
    @BindView(R.id.TG_LetB_2)
    TextView TG_LetB_2;
    @BindView(R.id.TG_LetB_Rate_2)
    TextView TGLetBRate_2;
    @BindView(R.id.rlTG_LetB_Rate_2)
    RelativeLayout rlTGLetBRate_2;
    @BindView(R.id.MB_LetB_Rate_tv_3)
    TextView MBLetBRateTv_3;
    @BindView(R.id.M_LetB_3)
    TextView M_LetB_3;
    @BindView(R.id.MB_LetB_Rate_3)
    TextView MBLetBRate_3;
    @BindView(R.id.rlMB_LetB_Rate_3)
    RelativeLayout rlMBLetBRate_3;
    @BindView(R.id.TG_LetB_Rate_tv_3)
    TextView TGLetBRateTv_3;
    @BindView(R.id.TG_LetB_Rate_3)
    TextView TGLetBRate_3;
    @BindView(R.id.TG_LetB_3)
    TextView TG_LetB_3;
    @BindView(R.id.rlTG_LetB_Rate_3)
    RelativeLayout rlTGLetBRate_3;
    @BindView(R.id.MB_LetB_Rate_tv_4)
    TextView MBLetBRateTv_4;
    @BindView(R.id.M_LetB_4)
    TextView M_LetB_4;
    @BindView(R.id.MB_LetB_Rate_4)
    TextView MBLetBRate_4;
    @BindView(R.id.rlMB_LetB_Rate_4)
    RelativeLayout rlMBLetBRate_4;
    @BindView(R.id.TG_LetB_Rate_tv_4)
    TextView TGLetBRateTv_4;
    @BindView(R.id.TG_LetB_Rate_4)
    TextView TGLetBRate_4;
    @BindView(R.id.TG_LetB_4)
    TextView TG_LetB_4;
    @BindView(R.id.rlTG_LetB_Rate_4)
    RelativeLayout rlTGLetBRate_4;

    //半场让球
    @BindView(R.id.ll_bet_rq_ban_all)
    LinearLayout ll_bet_rq_ban_all;
    @BindView(R.id.rl_bet_rq_ban_line)
    RelativeLayout rl_bet_rq_ban_line;
    @BindView(R.id.rl_bet_rq_ban_line_right)
    ImageView rl_bet_rq_ban_line_right;
    @BindView(R.id.ll_bet_rq_ban_list_1)
    LinearLayout ll_bet_rq_ban_list_1;
    @BindView(R.id.ll_bet_rq_ban_list_2)
    LinearLayout ll_bet_rq_ban_list_2;
    @BindView(R.id.ll_bet_rq_ban_list_3)
    LinearLayout ll_bet_rq_ban_list_3;
    @BindView(R.id.MB_LetB_Rate_H_tv_1)
    TextView MBLetBRateHTv_1;
    @BindView(R.id.M_LetB_H_1)
    TextView MLetBH_1;
    @BindView(R.id.MB_LetB_Rate_H_1)
    TextView MBLetBRateH_1;
    @BindView(R.id.rlMB_LetB_Rate_H_1)
    RelativeLayout rlMBLetBRateH_1;
    @BindView(R.id.TG_LetB_Rate_H_tv_1)
    TextView TGLetBRateHTv_1;
    @BindView(R.id.TG_LetB_H1)
    TextView TG_LetB_H1;
    @BindView(R.id.TG_LetB_Rate_H_1)
    TextView TGLetBRateH_1;
    @BindView(R.id.rlTG_LetB_Rate_H_1)
    RelativeLayout rlTG_LetB_Rate_H_1;

    @BindView(R.id.MB_LetB_Rate_H_tv_2)
    TextView MBLetBRateHTv_2;
    @BindView(R.id.M_LetB_H_2)
    TextView MLetBH_2;
    @BindView(R.id.MB_LetB_Rate_H_2)
    TextView MBLetBRateH_2;
    @BindView(R.id.rlMB_LetB_Rate_H_2)
    RelativeLayout rlMBLetBRateH_2;
    @BindView(R.id.TG_LetB_Rate_H_tv_2)
    TextView TGLetBRateHTv_2;
    @BindView(R.id.TG_LetB_H2)
    TextView TG_LetB_H2;
    @BindView(R.id.TG_LetB_Rate_H_2)
    TextView TGLetBRateH_2;
    @BindView(R.id.rlTG_LetB_Rate_H_2)
    RelativeLayout rlTG_LetB_Rate_H_2;

    @BindView(R.id.MB_LetB_Rate_H_tv_3)
    TextView MBLetBRateHTv_3;
    @BindView(R.id.M_LetB_H_3)
    TextView MLetBH_3;
    @BindView(R.id.MB_LetB_Rate_H_3)
    TextView MBLetBRateH_3;
    @BindView(R.id.rlMB_LetB_Rate_H_3)
    RelativeLayout rlMBLetBRateH_3;
    @BindView(R.id.TG_LetB_Rate_H_tv_3)
    TextView TGLetBRateHTv_3;
    @BindView(R.id.TG_LetB_H3)
    TextView TG_LetB_H3;
    @BindView(R.id.TG_LetB_Rate_H_3)
    TextView TGLetBRateH_3;
    @BindView(R.id.rlTG_LetB_Rate_H_3)
    RelativeLayout rlTG_LetB_Rate_H_3;



    //大小单场
    @BindView(R.id.ll_bet_dx_dan_all)
    LinearLayout ll_bet_dx_dan_all;
    @BindView(R.id.rl_bet_dx_dan_line)
    RelativeLayout rl_bet_dx_dan_line;
    @BindView(R.id.rl_bet_dx_dan_line_right)
    ImageView rl_bet_dx_dan_line_right;
    @BindView(R.id.ll_bet_dx_dan_list_1)
    LinearLayout ll_bet_dx_dan_list_1;
    @BindView(R.id.ll_bet_dx_dan_list_2)
    LinearLayout ll_bet_dx_dan_list_2;
    @BindView(R.id.ll_bet_dx_dan_list_3)
    LinearLayout ll_bet_dx_dan_list_3;
    @BindView(R.id.ll_bet_dx_dan_list_4)
    LinearLayout ll_bet_dx_dan_list_4;
    @BindView(R.id.tvMB_Dime_1)
    TextView tvMBDime_1;
    @BindView(R.id.MB_Dime_1)
    TextView MBDime_1;
    @BindView(R.id.MB_Dime_Rate_1)
    TextView MBDimeRate_1;
    @BindView(R.id.llMB_Dime_Rate_1)
    RelativeLayout llMBDimeRate_1;
    @BindView(R.id.tvTG_Dime_1)
    TextView tvTGDime_1;
    @BindView(R.id.TG_Dime_1)
    TextView TGDime_1;
    @BindView(R.id.TG_Dime_Rate_1)
    TextView TGDimeRate_1;
    @BindView(R.id.llTG_Dime_Rate_1)
    RelativeLayout llTGDimeRate_1;

    @BindView(R.id.tvMB_Dime_2)
    TextView tvMBDime_2;
    @BindView(R.id.MB_Dime_2)
    TextView MBDime_2;
    @BindView(R.id.MB_Dime_Rate_2)
    TextView MBDimeRate_2;
    @BindView(R.id.llMB_Dime_Rate_2)
    RelativeLayout llMBDimeRate_2;
    @BindView(R.id.tvTG_Dime_2)
    TextView tvTGDime_2;
    @BindView(R.id.TG_Dime_2)
    TextView TGDime_2;
    @BindView(R.id.TG_Dime_Rate_2)
    TextView TGDimeRate_2;
    @BindView(R.id.llTG_Dime_Rate_2)
    RelativeLayout llTGDimeRate_2;

    @BindView(R.id.tvMB_Dime_3)
    TextView tvMBDime_3;
    @BindView(R.id.MB_Dime_3)
    TextView MBDime_3;
    @BindView(R.id.MB_Dime_Rate_3)
    TextView MBDimeRate_3;
    @BindView(R.id.llMB_Dime_Rate_3)
    RelativeLayout llMBDimeRate_3;
    @BindView(R.id.tvTG_Dime_3)
    TextView tvTGDime_3;
    @BindView(R.id.TG_Dime_3)
    TextView TGDime_3;
    @BindView(R.id.TG_Dime_Rate_3)
    TextView TGDimeRate_3;
    @BindView(R.id.llTG_Dime_Rate_3)
    RelativeLayout llTGDimeRate_3;

    @BindView(R.id.tvMB_Dime_4)
    TextView tvMBDime_4;
    @BindView(R.id.MB_Dime_4)
    TextView MBDime_4;
    @BindView(R.id.MB_Dime_Rate_4)
    TextView MBDimeRate_4;
    @BindView(R.id.llMB_Dime_Rate_4)
    RelativeLayout llMBDimeRate_4;
    @BindView(R.id.tvTG_Dime_4)
    TextView tvTGDime_4;
    @BindView(R.id.TG_Dime_4)
    TextView TGDime_4;
    @BindView(R.id.TG_Dime_Rate_4)
    TextView TGDimeRate_4;
    @BindView(R.id.llTG_Dime_Rate_4)
    RelativeLayout llTGDimeRate_4;


    //大小-半场
    @BindView(R.id.ll_bet_dx_ban_all)
    LinearLayout ll_bet_dx_ban_all;
    @BindView(R.id.rl_bet_dx_ban_line)
    RelativeLayout rl_bet_dx_ban_line;
    @BindView(R.id.rl_bet_dx_ban_line_right)
    ImageView rl_bet_dx_ban_line_right;
    @BindView(R.id.ll_bet_dx_ban_list_1)
    LinearLayout ll_bet_dx_ban_list_1;
    @BindView(R.id.ll_bet_dx_ban_list_2)
    LinearLayout ll_bet_dx_ban_list_2;
    @BindView(R.id.ll_bet_dx_ban_list_3)
    LinearLayout ll_bet_dx_ban_list_3;
    @BindView(R.id.tvMB_Dime_H_1)
    TextView tvMBDimeH_1;
    @BindView(R.id.MB_Dime_H_1)
    TextView MBDimeH_1;
    @BindView(R.id.MB_Dime_Rate_H_1)
    TextView MBDimeRateH_1;
    @BindView(R.id.llMB_Dime_Rate_H_1)
    RelativeLayout llMBDimeRateH_1;
    @BindView(R.id.tvTG_Dime_H_1)
    TextView tvTGDimeH_1;
    @BindView(R.id.TG_Dime_H_1)
    TextView TGDimeH_1;
    @BindView(R.id.TG_Dime_Rate_H_1)
    TextView TGDimeRateH_1;
    @BindView(R.id.llTG_Dime_Rate_H_1)
    RelativeLayout llTGDimeRateH_1;

    @BindView(R.id.tvMB_Dime_H_2)
    TextView tvMBDimeH_2;
    @BindView(R.id.MB_Dime_H_2)
    TextView MBDimeH_2;
    @BindView(R.id.MB_Dime_Rate_H_2)
    TextView MBDimeRateH_2;
    @BindView(R.id.llMB_Dime_Rate_H_2)
    RelativeLayout llMBDimeRateH_2;
    @BindView(R.id.tvTG_Dime_H_2)
    TextView tvTGDimeH_2;
    @BindView(R.id.TG_Dime_H_2)
    TextView TGDimeH_2;
    @BindView(R.id.TG_Dime_Rate_H_2)
    TextView TGDimeRateH_2;
    @BindView(R.id.llTG_Dime_Rate_H_2)
    RelativeLayout llTGDimeRateH_2;

    @BindView(R.id.tvMB_Dime_H_3)
    TextView tvMBDimeH_3;
    @BindView(R.id.MB_Dime_H_3)
    TextView MBDimeH_3;
    @BindView(R.id.MB_Dime_Rate_H_3)
    TextView MBDimeRateH_3;
    @BindView(R.id.llMB_Dime_Rate_H_3)
    RelativeLayout llMBDimeRateH_3;
    @BindView(R.id.tvTG_Dime_H_3)
    TextView tvTGDimeH_3;
    @BindView(R.id.TG_Dime_H_3)
    TextView TGDimeH_3;
    @BindView(R.id.TG_Dime_Rate_H_3)
    TextView TGDimeRateH_3;
    @BindView(R.id.llTG_Dime_Rate_H_3)
    RelativeLayout llTGDimeRateH_3;



    //单双的逻辑
    @BindView(R.id.llS_S_D_RateALl)
    LinearLayout llS_S_D_RateALl;
    @BindView(R.id.rlS_S_D_RateALl)
    RelativeLayout rlS_S_D_RateALl;
    @BindView(R.id.rlS_S_D_RateALl_right)
    ImageView rlS_S_D_RateALl_right;
    @BindView(R.id.ll_D_S_Rate_1)
    LinearLayout ll_D_S_Rate_1;
    @BindView(R.id.ll_D_S_Rate_2)
    LinearLayout ll_D_S_Rate_2;
    @BindView(R.id.ll_D_S_Rate_3)
    LinearLayout ll_D_S_Rate_3;
    @BindView(R.id.llS_Single_Rate_1)
    RelativeLayout llSSingleRate_1;
    @BindView(R.id.llS_Double_Rate_1)
    RelativeLayout llSDoubleRate_1;
    @BindView(R.id.S_Single_Rate_1)
    TextView SSingleRate_1;
    @BindView(R.id.S_Double_Rate_1)
    TextView SDoubleRate_1;

    @BindView(R.id.llS_Single_Rate_2)
    RelativeLayout llSSingleRate_2;
    @BindView(R.id.llS_Double_Rate_2)
    RelativeLayout llSDoubleRate_2;
    @BindView(R.id.S_Single_Rate_2)
    TextView SSingleRate_2;
    @BindView(R.id.S_Double_Rate_2)
    TextView SDoubleRate_2;

    @BindView(R.id.llS_Single_Rate_3)
    RelativeLayout llSSingleRate_3;
    @BindView(R.id.llS_Double_Rate_3)
    RelativeLayout llSDoubleRate_3;
    @BindView(R.id.S_Single_Rate_3)
    TextView SSingleRate_3;
    @BindView(R.id.S_Double_Rate_3)
    TextView SDoubleRate_3;

    private BetContract.Presenter presenter;

    SportsPlayMethodRBResult sportsPlayMethodResult;

    private String mLeague;
    private String mType;
    private String mid;

    //是否是让球
    private boolean buyOrderIsS =false;
    private String buyOrderIsSData;
    private String buyOrderInfor,buyOrderTitle,buyOrderText;
    private String gid;
    /**
     * cate    FT_RB  足球滚球、FT 足球今日赛事 足球早盘 、BK_RB 篮球滚球、BK 篮球今日赛事 篮球早盘
     *
     *
     FT	足球今日赛事，滚球
     FU	足球早盘
     BK	篮球今日赛事，滚球
     BU	篮球早盘
     *
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

    //顺势的数额
    private String typeFrom;

    private String userMoney;
    //数据格式的转换

    private static List<String> MID = new ArrayList<>();
    private List<SportMethodResult.RqDanListBean> rq_dan_list = new ArrayList<>();
    private List<SportMethodResult.RqBanListBean> rq_ban_list = new ArrayList<>();
    private List<SportMethodResult.DxDanListBean> dx_dan_list = new ArrayList<>();
    private List<SportMethodResult.DxBanListBean> dx_ban_list = new ArrayList<>();
    private List<SportMethodResult.DsListBean> ds_list = new ArrayList<>();

    private ScheduledExecutorService executorService;
    private int sendAuthTime = HGConstant.ACTION_SEND_AUTH_CODE;

    public static BetFragment newInstance(String mLeague, String mType, String mid, String cate, String active, String type, String userMoney) {
        BetFragment fragment = new BetFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAMLEAGUE, mLeague);
        args.putString(ARG_PARAMTYPE, mType);
        args.putString(ARG_PARAMMID, mid);
        args.putString(ARG_PARAM1, cate);
        args.putString(ARG_PARAM2, active);
        args.putString(ARG_PARAM3, type);
        args.putString(ARG_PARAM4, userMoney);
        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mLeague = getArguments().getString(ARG_PARAMLEAGUE);
            mType = getArguments().getString(ARG_PARAMTYPE);
            mid = getArguments().getString(ARG_PARAMMID);
            cate = getArguments().getString(ARG_PARAM1);
            active =  getArguments().getString(ARG_PARAM2);
            typeFrom =  getArguments().getString(ARG_PARAM3);
            userMoney =  getArguments().getString(ARG_PARAM4);
            //sportsPlayMethodResult.getData().get(0).;
            GameLog.log("下注时的数据展示是 cate："+cate+" active："+active+"Stype："+typeFrom+"userMoney："+userMoney);
        }
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_bet;
    }

    @Override
    public void onVisible() {
        super.onVisible();
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        sendAuthTime = HGConstant.ACTION_SEND_AUTH_CODE;
        onSendAuthCode();
        presenter.postSportsPlayMethod("",mType,"s",mid);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        ll_bet_all_show.setVisibility(View.GONE);
        tvBetEventName.setText(mLeague);
        /*tvDepositThirdBankChannel.setText(dataBean.getTitle());
        tvDepositThirdBankCode.setText(dataBean.getBankList().get(0).getBankname());
        bankCode = dataBean.getBankList().get(0).getBankcode();*/

       /* switch (Stype){
            case "1":
                cate = "FT";
                break;
            case "2":
                cate = "BK";
                break;
            case "3":
                cate = "FT";
                break;
            case "4":
                cate = "BK";
                break;
            case "5":
                cate = "FU";
                break;
            case "6":
                cate = "BU";
                break;
        }*/



    }

    private void onCheckThirdMobilePaySubmit(){
        String thirdBankMoney = "";//etBetGold.getText().toString().trim();

        if(Check.isEmpty(thirdBankMoney)){
            showMessage("购买金额必须是整数！");
            return;
        }
        if(thirdBankMoney.compareTo("20")>=1){
            showMessage("购买金额必须大于20元！");
            return;
        }
        GameLog.log("赔率："+ioradio_r_h+" 金额："+gold+" cate："+cate+" active："+active+" type："+type+" line_type："+line_type);
        onCheckThirdMobilePay(cate, gid, type, active, line_type, odd_f_type,  gold, ioradio_r_h, rtype, wtype);
    }

    private void onCheckThirdMobilePay(TextView textView){

        //赔率
        ioradio_r_h = textView.getText().toString();
        //金额
        //String gold = edittext.getText().toString();

        GameLog.log("赔率："+ioradio_r_h+" 金额："+gold+" cate："+cate+" active："+active+" type："+type+" line_type："+line_type);
        /*if(gold.compareTo("20")>=0){

        }else{
            showMessage("下注金额最小20元");
            return;
        }*/
        String vs = buyOrderIsS?("("+buyOrderIsSData+")"):" VS ";
        buyOrderInfor = buyOrderTitle +"\n\n"+ mLeague + "\n\n" +MBTeam.getText().toString() + vs+ TGTeam.getText().toString() +"\n\n"+buyOrderText +" @ " + ioradio_r_h;
        //GameLog.log("最终购买的数据信息是：\n\n"+buyOrderInfor);
        OrderNumber orderNumber = new OrderNumber();
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

        BetOrderDialog.newInstance(userMoney,buyOrderInfor,orderNumber).show(getFragmentManager());
    }

    private void onCheckThirdMobilePay(String cate, String gid, String type, String active, String line_type, String odd_f_type, String gold, String ioradio_r_h, String rtype, String wtype) {
       /* String thirdBankMoney = etDepositThirdBankMoney.getText().toString().trim();

        if(Check.isEmpty(thirdBankMoney)){
            showMessage("汇款金额必须是整数！");
            return;
        }*/
        presenter.postBet("",cate,gid,type,active,line_type,odd_f_type,gold,ioradio_r_h,rtype,wtype);

        //EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(dataBean.getUrl(),thirdBankMoney,dataBean.getUserid(),dataBean.getId(),bankCode), SupportFragment.SINGLETASK));
    }

    private boolean isShowMB_Win_Rate_ALL = false;

    @OnClick({ R.id.ivBetEventBack,R.id.ivBetEventRefresh,R.id.rlMB_Win_Rate, R.id.rlTG_Win_Rate, R.id.rlM_Flat_Rate, R.id.rlMB_Win_Rate_H, R.id.rlTG_Win_Rate_H, R.id.rlM_Flat_Rate_H,
            R.id.rlMB_LetB_Rate_1,R.id.rlTG_LetB_Rate_1,R.id.rlMB_LetB_Rate_2,R.id.rlTG_LetB_Rate_2,R.id.rlMB_LetB_Rate_3,R.id.rlTG_LetB_Rate_3,
            R.id.rlMB_LetB_Rate_H_1, R.id.rlTG_LetB_Rate_H_1,R.id.rlMB_LetB_Rate_H_2, R.id.rlTG_LetB_Rate_H_2,R.id.rlMB_LetB_Rate_H_3, R.id.rlTG_LetB_Rate_H_3,
            R.id.llMB_Dime_Rate_1, R.id.llTG_Dime_Rate_1,R.id.llMB_Dime_Rate_2, R.id.llTG_Dime_Rate_2,R.id.llMB_Dime_Rate_3, R.id.llTG_Dime_Rate_3,
            R.id.llMB_Dime_Rate_H_1, R.id.llTG_Dime_Rate_H_1,R.id.llMB_Dime_Rate_H_2, R.id.llTG_Dime_Rate_H_2,R.id.llMB_Dime_Rate_H_3, R.id.llTG_Dime_Rate_H_3,
            R.id.llS_Single_Rate_1,R.id.llS_Double_Rate_1,R.id.llS_Single_Rate_2,R.id.llS_Double_Rate_2,R.id.llS_Single_Rate_3,R.id.llS_Double_Rate_3,
            R.id.rlMB_Win_Rate_ALL,R.id.rl_bet_rq_dan_line,R.id.rlMB_Win_Rate_H_ALL,R.id.rl_bet_rq_ban_line,R.id.rl_bet_dx_dan_line,R.id.rl_bet_dx_ban_line,R.id.rlS_S_D_RateALl
    })
    public void onViewClicked(View view) {
        buyOrderIsS = false;
        switch (view.getId()) {
            case R.id.rlMB_Win_Rate_ALL:
                if(lllMB_Win_Rate.isShown()){
                    rlMB_Win_Rate_ALL_right.setBackgroundResource(R.mipmap.deposit_right);
                    lllMB_Win_Rate.setVisibility(View.GONE);
                }else{
                    rlMB_Win_Rate_ALL_right.setBackgroundResource(R.mipmap.icon_ex_down);
                    lllMB_Win_Rate.setVisibility(View.VISIBLE);
                }
                break;
            case R.id.rlMB_Win_Rate_H_ALL:
                if(lllMB_Win_Rate_H_ALL.isShown()){
                    rlMB_Win_Rate_H_ALL_right.setBackgroundResource(R.mipmap.deposit_right);
                    lllMB_Win_Rate_H_ALL.setVisibility(View.GONE);
                }else{
                    rlMB_Win_Rate_H_ALL_right.setBackgroundResource(R.mipmap.icon_ex_down);
                    lllMB_Win_Rate_H_ALL.setVisibility(View.VISIBLE);
                }
                break;
            case R.id.rl_bet_rq_dan_line:

                if(rq_dan_list.size()==1){
                    if(ll_bet_rq_dan_list_1.isShown()){
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_rq_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
                    }
                }else if(rq_dan_list.size()==2){
                    if(ll_bet_rq_dan_list_1.isShown()){
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_rq_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_dan_list_2.isShown()){
                        ll_bet_rq_dan_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_dan_list_2.setVisibility(View.VISIBLE);
                    }
                }else if(rq_dan_list.size()==3){
                    if(ll_bet_rq_dan_list_1.isShown()){
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_rq_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_dan_list_2.isShown()){
                        ll_bet_rq_dan_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_dan_list_2.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_dan_list_3.isShown()){
                        ll_bet_rq_dan_list_3.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_dan_list_3.setVisibility(View.VISIBLE);
                    }
                }else if(rq_dan_list.size()==4){
                    if(ll_bet_rq_dan_list_1.isShown()){
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_rq_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_dan_list_2.isShown()){
                        ll_bet_rq_dan_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_dan_list_2.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_dan_list_3.isShown()){
                        ll_bet_rq_dan_list_3.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_dan_list_3.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_dan_list_4.isShown()){
                        ll_bet_rq_dan_list_4.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_dan_list_4.setVisibility(View.VISIBLE);
                    }
                }

                break;

            case R.id.rl_bet_rq_ban_line:

                if(rq_ban_list.size()==1){
                    if(ll_bet_rq_ban_list_1.isShown()){
                        rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_rq_ban_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_rq_ban_list_1.setVisibility(View.VISIBLE);
                    }
                }else if(rq_ban_list.size()==2){
                    if(ll_bet_rq_ban_list_1.isShown()){
                        rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_rq_ban_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_rq_ban_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_ban_list_2.isShown()){
                        ll_bet_rq_ban_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_ban_list_2.setVisibility(View.VISIBLE);
                    }
                }else if(rq_ban_list.size()==3){
                    if(ll_bet_rq_ban_list_1.isShown()){
                        rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_rq_ban_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_rq_ban_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_ban_list_2.isShown()){
                        ll_bet_rq_ban_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_ban_list_2.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_rq_ban_list_3.isShown()){
                        ll_bet_rq_ban_list_3.setVisibility(View.GONE);
                    }else{
                        ll_bet_rq_ban_list_3.setVisibility(View.VISIBLE);
                    }
                }
                break;
            case R.id.rl_bet_dx_dan_line:

                if(dx_dan_list.size()==1){
                    if(ll_bet_dx_dan_list_1.isShown()){
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_dx_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
                    }
                }else if(dx_dan_list.size()==2){
                    if(ll_bet_dx_dan_list_1.isShown()){
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_dx_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_dan_list_2.isShown()){
                        ll_bet_dx_dan_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_dan_list_2.setVisibility(View.VISIBLE);
                    }
                }else if(dx_dan_list.size()==3){
                    if(ll_bet_dx_dan_list_1.isShown()){
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_dx_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_dan_list_2.isShown()){
                        ll_bet_dx_dan_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_dan_list_2.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_dan_list_3.isShown()){
                        ll_bet_dx_dan_list_3.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_dan_list_3.setVisibility(View.VISIBLE);
                    }
                }else if(dx_dan_list.size()==4){
                    if(ll_bet_dx_dan_list_1.isShown()){
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_dx_dan_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_dan_list_2.isShown()){
                        ll_bet_dx_dan_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_dan_list_2.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_dan_list_3.isShown()){
                        ll_bet_dx_dan_list_3.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_dan_list_3.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_dan_list_4.isShown()){
                        ll_bet_dx_dan_list_4.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_dan_list_4.setVisibility(View.VISIBLE);
                    }
                }

                break;

            case R.id.rl_bet_dx_ban_line:
                if(dx_ban_list.size()==1){
                    if(ll_bet_dx_ban_list_1.isShown()){
                        rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_dx_ban_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_dx_ban_list_1.setVisibility(View.VISIBLE);
                    }
                }else if(dx_ban_list.size()==2){
                    if(ll_bet_dx_ban_list_1.isShown()){
                        rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_dx_ban_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_dx_ban_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_ban_list_2.isShown()){
                        ll_bet_dx_ban_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_ban_list_2.setVisibility(View.VISIBLE);
                    }
                }else if(dx_ban_list.size()==3){
                    if(ll_bet_dx_ban_list_1.isShown()){
                        rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_bet_dx_ban_list_1.setVisibility(View.GONE);
                    }else{
                        rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_bet_dx_ban_list_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_ban_list_2.isShown()){
                        ll_bet_dx_ban_list_2.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_ban_list_2.setVisibility(View.VISIBLE);
                    }
                    if(ll_bet_dx_ban_list_3.isShown()){
                        ll_bet_dx_ban_list_3.setVisibility(View.GONE);
                    }else{
                        ll_bet_dx_ban_list_3.setVisibility(View.VISIBLE);
                    }
                }
                break;
            case R.id.rlS_S_D_RateALl://dx_ban_list


                if(ds_list.size()==1){
                    if(ll_D_S_Rate_1.isShown()){
                        rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_D_S_Rate_1.setVisibility(View.GONE);
                    }else{
                        rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_D_S_Rate_1.setVisibility(View.VISIBLE);
                    }
                }else if(ds_list.size()==2){
                    if(ll_D_S_Rate_1.isShown()){
                        rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_D_S_Rate_1.setVisibility(View.GONE);
                    }else{
                        rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_D_S_Rate_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_D_S_Rate_2.isShown()){
                        ll_D_S_Rate_2.setVisibility(View.GONE);
                    }else{
                        ll_D_S_Rate_2.setVisibility(View.VISIBLE);
                    }
                }else if(ds_list.size()==3){
                    if(ll_D_S_Rate_1.isShown()){
                        rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.deposit_right);
                        ll_D_S_Rate_1.setVisibility(View.GONE);
                    }else{
                        rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.icon_ex_down);
                        ll_D_S_Rate_1.setVisibility(View.VISIBLE);
                    }
                    if(ll_D_S_Rate_2.isShown()){
                        ll_D_S_Rate_2.setVisibility(View.GONE);
                    }else{
                        ll_D_S_Rate_2.setVisibility(View.VISIBLE);
                    }
                    if(ll_D_S_Rate_3.isShown()){
                        ll_D_S_Rate_3.setVisibility(View.GONE);
                    }else{
                        ll_D_S_Rate_3.setVisibility(View.VISIBLE);
                    }
                }
                break;

            case R.id.ivBetEventBack:
                finish();
            case R.id.ivBetEventRefresh:
                onVisible();
                break;
            case R.id.rlMB_Win_Rate://独赢 主队
                buyOrderTitle = "单场独赢";
                buyOrderText = MBWinRateTv.getText().toString();
                line_type = "1";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "21";
                }
                onCheckThirdMobilePay(MBWinRate);
                break;
            case R.id.rlTG_Win_Rate://独赢 客队
                buyOrderTitle = "单场独赢";
                buyOrderText = TGWinRateTv.getText().toString();
                line_type = "1";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "21";
                }
                onCheckThirdMobilePay(TGWinRate);
                break;
            case R.id.rlM_Flat_Rate://独赢 和
                buyOrderTitle = "单场独赢";
                buyOrderText = "和局";
                line_type = "1";
                type = "N";
                rtype = "N";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "21";
                }
                onCheckThirdMobilePay(MFlatRate);
                break;
            case R.id.rlMB_Win_Rate_H://独赢 半场 主队
                buyOrderTitle = "半场独赢";
                buyOrderText = MBWinRateHTv.getText().toString();
                line_type = "11";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "31";
                }
                onCheckThirdMobilePay(MBWinRateH);
                break;
            case R.id.rlTG_Win_Rate_H://独赢 半场 客队
                buyOrderTitle = "半场独赢";
                buyOrderText = TGWinRateHTv.getText().toString();
                line_type = "11";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "31";
                }
                onCheckThirdMobilePay(TGWinRateH);
                break;
            case R.id.rlM_Flat_Rate_H://独赢 半场 和
                buyOrderTitle = "半场独赢";
                buyOrderText = "和局";
                line_type = "11";
                type = "N";
                rtype = "N";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "31";
                }
                onCheckThirdMobilePay(MFlatRateH);
                break;
            case R.id.rlMB_LetB_Rate_1://让球 单场 主队
                gid=MID.get(0);
                buyOrderIsS = true;
                buyOrderIsSData = M_LetB_1.isShown()?M_LetB_1.getText().toString():TG_LetB_1.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = MBLetBRateTv_1.getText().toString();
                line_type = "2";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(MBLetBRate_1);
                break;
            case R.id.rlMB_LetB_Rate_2:
                gid=MID.get(1);
                buyOrderIsS = true;
                buyOrderIsSData = M_LetB_2.isShown()?M_LetB_2.getText().toString():TG_LetB_2.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = MBLetBRateTv_2.getText().toString();
                line_type = "2";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(MBLetBRate_2);
                break;
            case R.id.rlMB_LetB_Rate_3:
                gid=MID.get(2);
                buyOrderIsS = true;
                buyOrderIsSData = M_LetB_3.isShown()?M_LetB_3.getText().toString():TG_LetB_3.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = MBLetBRateTv_3.getText().toString();
                line_type = "2";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(MBLetBRate_3);
                break;
            case R.id.rlMB_LetB_Rate_4:
                gid=MID.get(3);
                buyOrderIsS = true;
                buyOrderIsSData = M_LetB_4.isShown()?M_LetB_4.getText().toString():TG_LetB_4.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = MBLetBRateTv_4.getText().toString();
                line_type = "2";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(MBLetBRate_4);
                break;
            case R.id.rlTG_LetB_Rate_1://让球 单场 客队
                gid=MID.get(0);
                buyOrderIsS = true;
                buyOrderIsSData = TG_LetB_1.isShown()?TG_LetB_1.getText().toString():M_LetB_1.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = TGLetBRateTv_1.getText().toString();
                line_type = "2";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(TGLetBRate_1);
                break;
            case R.id.rlTG_LetB_Rate_2:
                gid=MID.get(1);
                buyOrderIsS = true;
                buyOrderIsSData = TG_LetB_2.isShown()?TG_LetB_2.getText().toString():M_LetB_2.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = TGLetBRateTv_2.getText().toString();
                line_type = "2";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(TGLetBRate_2);
                break;
            case R.id.rlTG_LetB_Rate_3:
                gid=MID.get(2);
                buyOrderIsS = true;
                buyOrderIsSData = TG_LetB_3.isShown()?TG_LetB_3.getText().toString():M_LetB_3.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = TGLetBRateTv_3.getText().toString();
                line_type = "2";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(TGLetBRate_3);
                break;
            case R.id.rlTG_LetB_Rate_4:
                gid=MID.get(3);
                buyOrderIsS = true;
                buyOrderIsSData = TG_LetB_4.isShown()?TG_LetB_4.getText().toString():M_LetB_4.getText().toString();
                buyOrderTitle = "单场让球";
                buyOrderText = TGLetBRateTv_4.getText().toString();
                line_type = "2";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "9";
                }
                onCheckThirdMobilePay(TGLetBRate_4);
                break;
            case R.id.rlMB_LetB_Rate_H_1://让球 半场 主队
                gid=MID.get(0);
                buyOrderIsS = true;
                buyOrderIsSData = MLetBH_1.isShown()?MLetBH_1.getText().toString():TG_LetB_H1.getText().toString();
                buyOrderTitle = "半场让球";
                buyOrderText = MBLetBRateHTv_1.getText().toString();
                line_type = "12";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "19";
                }else if(typeFrom.equals("3")){
                    line_type = "12";
                }else if(typeFrom.equals("5")){
                    line_type = "12";
                }
                onCheckThirdMobilePay(MBLetBRateH_1);
                break;
            case R.id.rlMB_LetB_Rate_H_2:
                gid=MID.get(1);
                buyOrderIsS = true;
                buyOrderIsSData = MLetBH_2.isShown()?MLetBH_2.getText().toString():TG_LetB_H2.getText().toString();
                buyOrderTitle = "半场让球";
                buyOrderText = MBLetBRateHTv_2.getText().toString();
                line_type = "12";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "19";
                }else if(typeFrom.equals("3")){
                    line_type = "12";
                }else if(typeFrom.equals("5")){
                    line_type = "12";
                }
                onCheckThirdMobilePay(MBLetBRateH_2);
                break;
            case R.id.rlMB_LetB_Rate_H_3:
                gid=MID.get(2);
                buyOrderIsS = true;
                buyOrderIsSData = MLetBH_3.isShown()?MLetBH_3.getText().toString():TG_LetB_H3.getText().toString();
                buyOrderTitle = "半场让球";
                buyOrderText = MBLetBRateHTv_3.getText().toString();
                line_type = "12";
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "19";
                }else if(typeFrom.equals("3")){
                    line_type = "12";
                }else if(typeFrom.equals("5")){
                    line_type = "12";
                }
                onCheckThirdMobilePay(MBLetBRateH_3);
                break;
            case R.id.rlTG_LetB_Rate_H_1://让球 半场 客队
                gid=MID.get(0);
                buyOrderIsS = true;
                buyOrderIsSData = TG_LetB_H1.isShown()?TG_LetB_H1.getText().toString():MLetBH_1.getText().toString();
                buyOrderTitle = "半场让球";
                buyOrderText = TGLetBRateHTv_1.getText().toString();
                line_type = "12";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "19";
                }else if(typeFrom.equals("3")){
                    line_type = "12";
                }else if(typeFrom.equals("5")){
                    line_type = "12";
                }
                onCheckThirdMobilePay(TGLetBRateH_1);
                break;
            case R.id.rlTG_LetB_Rate_H_2:
                gid=MID.get(1);
                buyOrderIsS = true;
                buyOrderIsSData = TG_LetB_H2.isShown()?TG_LetB_H2.getText().toString():MLetBH_2.getText().toString();
                buyOrderTitle = "半场让球";
                buyOrderText = TGLetBRateHTv_2.getText().toString();
                line_type = "12";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "19";
                }else if(typeFrom.equals("3")){
                    line_type = "12";
                }else if(typeFrom.equals("5")){
                    line_type = "12";
                }
                onCheckThirdMobilePay(TGLetBRateH_2);
                break;
            case R.id.rlTG_LetB_Rate_H_3:
                gid=MID.get(2);
                buyOrderIsS = true;
                buyOrderIsSData = TG_LetB_H3.isShown()?TG_LetB_H3.getText().toString():MLetBH_3.getText().toString();
                buyOrderTitle = "半场让球";
                buyOrderText = TGLetBRateHTv_3.getText().toString();
                line_type = "12";
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "19";
                }else if(typeFrom.equals("3")){
                    line_type = "12";
                }else if(typeFrom.equals("5")){
                    line_type = "12";
                }
                onCheckThirdMobilePay(TGLetBRateH_3);
                break;
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
            case R.id.llMB_Dime_Rate_1://大小 单场 主队
                gid=MID.get(0);
                buyOrderTitle = "单场大小";
                buyOrderText = tvMBDime_1.getText().toString()+MBDime_1.getText().toString();
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUH";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUH";
                }
                onCheckThirdMobilePay(MBDimeRate_1);
                break;
            case R.id.llMB_Dime_Rate_2:
                gid=MID.get(1);
                buyOrderTitle = "单场大小";
                buyOrderText = tvMBDime_2.getText().toString()+MBDime_2.getText().toString();
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUH";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUH";
                }
                onCheckThirdMobilePay(MBDimeRate_2);
                break;
            case R.id.llMB_Dime_Rate_3:
                gid=MID.get(2);
                buyOrderTitle = "单场大小";
                buyOrderText = tvMBDime_3.getText().toString()+MBDime_3.getText().toString();
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUH";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUH";
                }
                onCheckThirdMobilePay(MBDimeRate_3);
                break;
            case R.id.llMB_Dime_Rate_4:
                gid=MID.get(3);
                buyOrderTitle = "单场大小";
                buyOrderText = tvMBDime_4.getText().toString()+MBDime_4.getText().toString();
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUH";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUH";
                }
                onCheckThirdMobilePay(MBDimeRate_4);
                break;
            case R.id.llTG_Dime_Rate_1://大小 单场 客队
                gid=MID.get(0);
                buyOrderTitle = "单场大小";
                buyOrderText = tvTGDime_1.getText().toString()+TGDime_1.getText().toString();
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUC";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUC";
                }
                onCheckThirdMobilePay(TGDimeRate_1);
                break;
            case R.id.llTG_Dime_Rate_2:
                gid=MID.get(1);
                buyOrderTitle = "单场大小";
                buyOrderText = tvTGDime_2.getText().toString()+TGDime_2.getText().toString();
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUC";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUC";
                }
                onCheckThirdMobilePay(TGDimeRate_2);
                break;
            case R.id.llTG_Dime_Rate_3:
                gid=MID.get(2);
                buyOrderTitle = "单场大小";
                buyOrderText = tvTGDime_3.getText().toString()+TGDime_3.getText().toString();
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUC";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUC";
                }
                onCheckThirdMobilePay(TGDimeRate_3);
                break;
            case R.id.llTG_Dime_Rate_4:
                gid=MID.get(3);
                buyOrderTitle = "单场大小";
                buyOrderText = tvTGDime_4.getText().toString()+TGDime_4.getText().toString();
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "3";
                }else if(typeFrom.equals("5")){
                    line_type = "3";
                }else if(typeFrom.equals("6")){
                    line_type = "3";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUC";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)||"6".equals(typeFrom)){
                    wtype = "OUC";
                }
                onCheckThirdMobilePay(TGDimeRate_4);
                break;
            case R.id.llMB_Dime_Rate_H_1://大小 半场 主队
                gid=MID.get(0);
                buyOrderTitle = "半场大小";
                buyOrderText = tvMBDimeH_1.getText().toString()+MBDimeH_1.getText().toString();
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "13";
                }else if(typeFrom.equals("5")){
                    line_type = "13";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUH";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)){
                    wtype = "OUH";
                }
                onCheckThirdMobilePay(MBDimeRateH_1);
                break;
            case R.id.llMB_Dime_Rate_H_2:
                gid=MID.get(1);
                buyOrderTitle = "半场大小";
                buyOrderText = tvMBDimeH_2.getText().toString()+MBDimeH_2.getText().toString();
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "13";
                }else if(typeFrom.equals("5")){
                    line_type = "13";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUH";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)){
                    wtype = "OUH";
                }
                onCheckThirdMobilePay(MBDimeRateH_2);
                break;
            case R.id.llMB_Dime_Rate_H_3:
                gid=MID.get(2);
                buyOrderTitle = "半场大小";
                buyOrderText = tvMBDimeH_3.getText().toString()+MBDimeH_3.getText().toString();
                type = "C";
                rtype = "C";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "13";
                }else if(typeFrom.equals("5")){
                    line_type = "13";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUH";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)){
                    wtype = "OUH";
                }
                onCheckThirdMobilePay(MBDimeRateH_3);
                break;
            case R.id.llTG_Dime_Rate_H_1://大小 半场 客队
                gid=MID.get(0);
                buyOrderTitle = "半场大小";
                buyOrderText = tvTGDimeH_1.getText().toString()+TGDimeH_1.getText().toString();
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "13";
                }else if(typeFrom.equals("5")){
                    line_type = "13";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUC";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)){
                    wtype = "OUC";
                }
                onCheckThirdMobilePay(TGDimeRateH_1);
                break;
            case R.id.llTG_Dime_Rate_H_2:
                gid=MID.get(1);
                buyOrderTitle = "半场大小";
                buyOrderText = tvTGDimeH_2.getText().toString()+TGDimeH_2.getText().toString();
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "13";
                }else if(typeFrom.equals("5")){
                    line_type = "13";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUC";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)){
                    wtype = "OUC";
                }
                onCheckThirdMobilePay(TGDimeRateH_2);
                break;
            case R.id.llTG_Dime_Rate_H_3:
                gid=MID.get(2);
                buyOrderTitle = "半场大小";
                buyOrderText = tvTGDimeH_3.getText().toString()+TGDimeH_3.getText().toString();
                type = "H";
                rtype = "H";
                wtype = "";
                if(typeFrom.equals("1")){
                    line_type = "10";
                }else if(typeFrom.equals("3")){
                    line_type = "13";
                }else if(typeFrom.equals("5")){
                    line_type = "13";
                }
                if("1".equals(typeFrom)||"2".equals(typeFrom)){
                    wtype = "ROUC";
                }else if("3".equals(typeFrom)||"4".equals(typeFrom)||"5".equals(typeFrom)){
                    wtype = "OUC";
                }
                onCheckThirdMobilePay(TGDimeRateH_3);
                break;
            case R.id.llS_Single_Rate_1://单双 主队
                gid=MID.get(0);
                buyOrderTitle = "单双";
                buyOrderText = "单";
                line_type = "5";
                type = "ODD";
                rtype = "H";
                wtype = "";
                rtype = "ODD";
                onCheckThirdMobilePay(SSingleRate_1);
                break;
            case R.id.llS_Single_Rate_2:
                gid=MID.get(1);
                buyOrderTitle = "单双";
                buyOrderText = "单";
                line_type = "5";
                type = "ODD";
                rtype = "H";
                wtype = "";
                rtype = "ODD";
                onCheckThirdMobilePay(SSingleRate_2);
                break;
            case R.id.llS_Single_Rate_3:
                gid=MID.get(2);
                buyOrderTitle = "单双";
                buyOrderText = "单";
                line_type = "5";
                type = "ODD";
                rtype = "H";
                wtype = "";
                rtype = "ODD";
                onCheckThirdMobilePay(SSingleRate_3);
                break;
            case R.id.llS_Double_Rate_1://单双 客队
                gid=MID.get(0);
                buyOrderTitle = "单双";
                buyOrderText = "双";
                line_type = "5";
                type = "EVEN";
                rtype = "H";
                wtype = "";
                rtype = "EVEN";
                onCheckThirdMobilePay(SDoubleRate_1);
                break;
            case R.id.llS_Double_Rate_2:
                gid=MID.get(1);
                buyOrderTitle = "单双";
                buyOrderText = "双";
                line_type = "5";
                type = "EVEN";
                rtype = "H";
                wtype = "";
                rtype = "EVEN";
                onCheckThirdMobilePay(SDoubleRate_2);
                break;
            case R.id.llS_Double_Rate_3:
                gid=MID.get(2);
                buyOrderTitle = "单双";
                buyOrderText = "双";
                line_type = "5";
                type = "EVEN";
                rtype = "H";
                wtype = "";
                rtype = "EVEN";
                onCheckThirdMobilePay(SDoubleRate_3);
                break;
        }
    }

    @Override
    public void postSportsPlayMethodResult(SportsPlayMethodResult sportsPlayMethodResult) {

        ll_bet_all_show.setVisibility(View.VISIBLE);
        //服务器的数据 不能成为我要的数据，这里转换成我需要的格式
        /*BetRBResult betNewResult = new BetRBResult();
        int resultSize = sportsPlayMethodResult.getData().size();
        for(int i=0;i<resultSize;++i){
            SportsPlayMethodResult.DataBean dataBean = sportsPlayMethodResult.getData().get(i);

            betNewResult.setM_Time(dataBean.getM_Time());
            betNewResult.setMB_Team(dataBean.getMB_Team());
            betNewResult.setTG_Team(dataBean.getTG_Team());
            betNewResult.setM_League(dataBean.getM_League());
            betNewResult.setShowTypeRB(dataBean.getShowTypeRB());
            betNewResult.setShowTypeHRB(dataBean.getShowTypeHRB());
            betNewResult.setMB_Dime_RB_S_H(dataBean.getMB_Dime_RB_S_H());
            betNewResult.setTG_Dime_RB_S_H(dataBean.getTG_Dime_RB_S_H());
            betNewResult.setMB_Dime_Rate_RB_S_H(dataBean.getMB_Dime_Rate_RB_S_H());
            betNewResult.setTG_Dime_Rate_RB_S_H(dataBean.getTG_Dime_Rate_RB_S_H());
            betNewResult.setMB_Ball(dataBean.getMB_Ball());
            betNewResult.setTG_Ball(dataBean.getTG_Ball());
            betNewResult.setMB_Inball_HR(dataBean.getMB_Inball_HR());
            betNewResult.setTG_Inball_HR(dataBean.getTG_Inball_HR());
            betNewResult.setNowSession(dataBean.getNowSession());
            if(Check.isEmpty(dataBean.getMID())){
                MID.add(dataBean.getMID());
            }
        }
        betNewResult.setMID(MID);
        */



        //先清空列表里的数据
        onClearListData();

        //服务器的数据 不能成为我要的数据，这里转换成我需要的格式
        SportMethodResult betNewResult = new SportMethodResult();
        int resultSize = sportsPlayMethodResult.getData().size();
        for(int i=0;i<resultSize;++i){
            SportsPlayMethodResult.DataBean dataBean = sportsPlayMethodResult.getData().get(i);

            betNewResult.setM_Time(dataBean.getM_Time());
            betNewResult.setMB_Team(dataBean.getMB_Team());
            betNewResult.setTG_Team(dataBean.getTG_Team());
            betNewResult.setM_League(dataBean.getM_League());
            betNewResult.setShowTypeR(dataBean.getShowTypeR());
            betNewResult.setShowTypeHR(dataBean.getShowTypeHR());

            //独赢-单场
            betNewResult.setMB_Win_Rate(dataBean.getMB_Win_Rate());
            betNewResult.setTG_Win_Rate(dataBean.getTG_Win_Rate());
            betNewResult.setM_Flat_Rate(dataBean.getM_Flat_Rate());
            //独赢-半场
            betNewResult.setMB_Win_Rate_H(dataBean.getMB_Win_Rate_H());
            betNewResult.setTG_Win_Rate_H(dataBean.getTG_Win_Rate_H());
            betNewResult.setM_Flat_Rate_H(dataBean.getM_Flat_Rate_H());

            //其他辅助的东西  可有可无  实际没有什么卵用
            betNewResult.setPD_Show(dataBean.getPD_Show());
            betNewResult.setHPD_Show(dataBean.getHPD_Show());
            betNewResult.setT_Show(dataBean.getT_Show());
            betNewResult.setF_Show(dataBean.getF_Show());
            betNewResult.setEventid(dataBean.getEventid());
            betNewResult.setHot(dataBean.getHot());
            betNewResult.setPlay(dataBean.getPlay());

            if(!Check.isEmpty(dataBean.getMID())){
                MID.add(dataBean.getMID());
            }
            //重新排列数据
            SportMethodResult.RqDanListBean rqDanListBean = new SportMethodResult.RqDanListBean();
            SportMethodResult.RqBanListBean rqBanListBean = new SportMethodResult.RqBanListBean();
            SportMethodResult.DxDanListBean dxDanListBean = new SportMethodResult.DxDanListBean();
            SportMethodResult.DxBanListBean dxBanListBean = new SportMethodResult.DxBanListBean();
            SportMethodResult.DsListBean dsListBean  = new SportMethodResult.DsListBean();

            //让球-单场
            if(!Check.isEmpty(dataBean.getM_LetB())){
                rqDanListBean.setM_LetB(dataBean.getM_LetB());
                rqDanListBean.setShowTypeR(dataBean.getShowTypeR());
                rqDanListBean.setMB_LetB_Rate(dataBean.getMB_LetB_Rate());
                rqDanListBean.setTG_LetB_Rate(dataBean.getTG_LetB_Rate());
                rq_dan_list.add(rqDanListBean);
            }

            //让球-半场
            if(!Check.isEmpty(dataBean.getM_LetB_H())){
                rqBanListBean.setM_LetB_H(dataBean.getM_LetB_H());
                rqBanListBean.setShowTypeHR(dataBean.getShowTypeHR());
                rqBanListBean.setMB_LetB_Rate_H(dataBean.getMB_LetB_Rate_H());
                rqBanListBean.setTG_LetB_Rate_H(dataBean.getTG_LetB_Rate_H());
                rq_ban_list.add(rqBanListBean);
            }

            //大小-单场
            if(!Check.isEmpty(dataBean.getMB_Dime())){
                dxDanListBean.setMB_Dime(dataBean.getMB_Dime());
                dxDanListBean.setTG_Dime(dataBean.getTG_Dime());
                dxDanListBean.setMB_Dime_Rate(dataBean.getMB_Dime_Rate());
                dxDanListBean.setTG_Dime_Rate(dataBean.getTG_Dime_Rate());
                dx_dan_list.add(dxDanListBean);
            }

            //大小-半场
            if(!Check.isEmpty(dataBean.getMB_Dime_H())){
                dxBanListBean.setMB_Dime_H(dataBean.getMB_Dime_H());
                dxBanListBean.setTG_Dime_H(dataBean.getTG_Dime_H());
                dxBanListBean.setMB_Dime_Rate_H(dataBean.getMB_Dime_Rate_H());
                dxBanListBean.setTG_Dime_Rate_H(dataBean.getTG_Dime_Rate_H());
                dx_ban_list.add(dxBanListBean);
            }

            //当双
            if(!Check.isEmpty(dataBean.getS_Single_Rate())){
                dsListBean.setS_Single_Rate(dataBean.getS_Single_Rate());
                dsListBean.setS_Double_Rate(dataBean.getS_Double_Rate());
                ds_list.add(dsListBean);
            }

        }
        betNewResult.setMID(MID);
        betNewResult.setRq_dan_list(rq_dan_list);
        betNewResult.setRq_ban_list(rq_ban_list);
        betNewResult.setDx_dan_list(dx_dan_list);
        betNewResult.setDx_ban_list(dx_ban_list);
        betNewResult.setDs_list(ds_list);



















        //只有单一一个数据的时候 如下的请求方式，
        SportsPlayMethodResult.DataBean dataBean = sportsPlayMethodResult.getData().get(0);

        gid = dataBean.getMID();
        //cate = dataBean.getType();
        MBTeam.setText(dataBean.getMB_Team());
        TGTeam.setText(dataBean.getTG_Team());


        //独赢-单场
        if(!Check.isEmpty(dataBean.getMB_Win_Rate())){
            llMB_Win_Rate_ALL.setVisibility(View.VISIBLE);
            MBWinRateTv.setText(dataBean.getMB_Team());
            TGWinRateTv.setText(dataBean.getTG_Team());
            MBWinRate.setText(dataBean.getMB_Win_Rate());
            TGWinRate.setText(dataBean.getTG_Win_Rate());
			if(Check.isEmpty(dataBean.getM_Flat_Rate())){
                rlMFlatRate.setVisibility(View.GONE);
            }else{
                rlMFlatRate.setVisibility(View.VISIBLE);
                MFlatRate.setText(dataBean.getM_Flat_Rate());
            }

        }else{
            llMB_Win_Rate_ALL.setVisibility(View.GONE);
        }

        //独赢-半场
        if(!Check.isEmpty(dataBean.getMB_Win_Rate_H())){
            llMB_Win_Rate_H_ALL.setVisibility(View.VISIBLE);
            MBWinRateHTv.setText(dataBean.getMB_Team());
            TGWinRateHTv.setText(dataBean.getTG_Team());
            MBWinRateH.setText(dataBean.getMB_Win_Rate_H());
            TGWinRateH.setText(dataBean.getTG_Win_Rate_H());
            MFlatRateH.setText(dataBean.getM_Flat_Rate_H());
        }else{
            llMB_Win_Rate_H_ALL.setVisibility(View.GONE);
        }

        //让球-单场
        /*MBLetBRateTv_1.setText(dataBean.getMB_Team());
        M_LetB_1.setText(dataBean.getM_LetB());
        MBLetBRate_1.setText(dataBean.getMB_LetB_Rate());
        TGLetBRateTv_1.setText(dataBean.getTG_Team());
        TGLetBRate_1.setText(dataBean.getTG_LetB_Rate());*/

        if(rq_dan_list.size()==0){//不显示
            ll_bet_rq_dan_all.setVisibility(View.GONE);
        }else if(rq_dan_list.size()==1){
            ll_bet_rq_dan_all.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
            rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_rq_dan_list_2.setVisibility(View.GONE);
            ll_bet_rq_dan_list_3.setVisibility(View.GONE);
            ll_bet_rq_dan_list_4.setVisibility(View.GONE);
            MBLetBRateTv_1.setText(dataBean.getMB_Team());
            TGLetBRateTv_1.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(0).getShowTypeR())){
                M_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setVisibility(View.GONE);
                M_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }else{
                M_LetB_1.setVisibility(View.GONE);
                TG_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }

            MBLetBRate_1.setText(rq_dan_list.get(0).getMB_LetB_Rate());
            TGLetBRate_1.setText(rq_dan_list.get(0).getTG_LetB_Rate());

        }else if(rq_dan_list.size()==2){
            ll_bet_rq_dan_all.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_2.setVisibility(View.VISIBLE);
            rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_rq_dan_list_3.setVisibility(View.GONE);
            ll_bet_rq_dan_list_4.setVisibility(View.GONE);
            MBLetBRateTv_1.setText(dataBean.getMB_Team());
            TGLetBRateTv_1.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(0).getShowTypeR())){
                M_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setVisibility(View.GONE);
                M_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }else{
                M_LetB_1.setVisibility(View.GONE);
                TG_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }

            //M_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            MBLetBRate_1.setText(rq_dan_list.get(0).getMB_LetB_Rate());
            TGLetBRate_1.setText(rq_dan_list.get(0).getTG_LetB_Rate());

            MBLetBRateTv_2.setText(dataBean.getMB_Team());
            TGLetBRateTv_2.setText(dataBean.getTG_Team());


            if("H".equals(rq_dan_list.get(1).getShowTypeR())){
                M_LetB_2.setVisibility(View.VISIBLE);
                TG_LetB_2.setVisibility(View.GONE);
                M_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            }else{
                M_LetB_2.setVisibility(View.GONE);
                TG_LetB_2.setVisibility(View.VISIBLE);
                TG_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            }

            //M_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            MBLetBRate_2.setText(rq_dan_list.get(1).getMB_LetB_Rate());
            TGLetBRate_2.setText(rq_dan_list.get(1).getTG_LetB_Rate());
        }else if(rq_dan_list.size()==3){
            ll_bet_rq_dan_all.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_2.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_3.setVisibility(View.VISIBLE);
            rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_rq_dan_list_4.setVisibility(View.GONE);
            MBLetBRateTv_1.setText(dataBean.getMB_Team());
            TGLetBRateTv_1.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(0).getShowTypeR())){
                M_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setVisibility(View.GONE);
                M_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }else{
                M_LetB_1.setVisibility(View.GONE);
                TG_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }
            //M_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            MBLetBRate_1.setText(rq_dan_list.get(0).getMB_LetB_Rate());
            TGLetBRate_1.setText(rq_dan_list.get(0).getTG_LetB_Rate());

            MBLetBRateTv_2.setText(dataBean.getMB_Team());
            TGLetBRateTv_2.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(1).getShowTypeR())){
                M_LetB_2.setVisibility(View.VISIBLE);
                TG_LetB_2.setVisibility(View.GONE);
                M_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            }else{
                M_LetB_2.setVisibility(View.GONE);
                TG_LetB_2.setVisibility(View.VISIBLE);
                TG_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            }
            //M_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            MBLetBRate_2.setText(rq_dan_list.get(1).getMB_LetB_Rate());
            TGLetBRate_2.setText(rq_dan_list.get(1).getTG_LetB_Rate());

            MBLetBRateTv_3.setText(dataBean.getMB_Team());
            TGLetBRateTv_3.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(2).getShowTypeR())){
                M_LetB_3.setVisibility(View.VISIBLE);
                TG_LetB_3.setVisibility(View.GONE);
                M_LetB_3.setText(rq_dan_list.get(2).getM_LetB());
            }else{
                M_LetB_3.setVisibility(View.GONE);
                TG_LetB_3.setVisibility(View.VISIBLE);
                TG_LetB_3.setText(rq_dan_list.get(2).getM_LetB());
            }
            //M_LetB_3.setText(rq_dan_list.get(2).getM_LetB());
            MBLetBRate_3.setText(rq_dan_list.get(2).getMB_LetB_Rate());
            TGLetBRate_3.setText(rq_dan_list.get(2).getTG_LetB_Rate());
        }else if(rq_dan_list.size()==4){
            ll_bet_rq_dan_all.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_1.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_2.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_3.setVisibility(View.VISIBLE);
            ll_bet_rq_dan_list_4.setVisibility(View.VISIBLE);
            rl_bet_rq_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            MBLetBRateTv_1.setText(dataBean.getMB_Team());
            TGLetBRateTv_1.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(0).getShowTypeR())){
                M_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setVisibility(View.GONE);
                M_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }else{
                M_LetB_1.setVisibility(View.GONE);
                TG_LetB_1.setVisibility(View.VISIBLE);
                TG_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            }
            //M_LetB_1.setText(rq_dan_list.get(0).getM_LetB());
            MBLetBRate_1.setText(rq_dan_list.get(0).getMB_LetB_Rate());
            TGLetBRate_1.setText(rq_dan_list.get(0).getTG_LetB_Rate());

            MBLetBRateTv_2.setText(dataBean.getMB_Team());
            TGLetBRateTv_2.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(1).getShowTypeR())){
                M_LetB_2.setVisibility(View.VISIBLE);
                TG_LetB_2.setVisibility(View.GONE);
                M_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            }else{
                M_LetB_2.setVisibility(View.GONE);
                TG_LetB_2.setVisibility(View.VISIBLE);
                TG_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            }
            //M_LetB_2.setText(rq_dan_list.get(1).getM_LetB());
            MBLetBRate_2.setText(rq_dan_list.get(1).getMB_LetB_Rate());
            TGLetBRate_2.setText(rq_dan_list.get(1).getTG_LetB_Rate());

            MBLetBRateTv_3.setText(dataBean.getMB_Team());
            TGLetBRateTv_3.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(2).getShowTypeR())){
                M_LetB_3.setVisibility(View.VISIBLE);
                TG_LetB_3.setVisibility(View.GONE);
                M_LetB_3.setText(rq_dan_list.get(2).getM_LetB());
            }else{
                M_LetB_3.setVisibility(View.GONE);
                TG_LetB_3.setVisibility(View.VISIBLE);
                TG_LetB_3.setText(rq_dan_list.get(2).getM_LetB());
            }
            MBLetBRate_3.setText(rq_dan_list.get(2).getMB_LetB_Rate());
            TGLetBRate_3.setText(rq_dan_list.get(2).getTG_LetB_Rate());

            MBLetBRateTv_4.setText(dataBean.getMB_Team());
            TGLetBRateTv_4.setText(dataBean.getTG_Team());

            if("H".equals(rq_dan_list.get(3).getShowTypeR())){
                M_LetB_4.setVisibility(View.VISIBLE);
                TG_LetB_4.setVisibility(View.GONE);
                M_LetB_4.setText(rq_dan_list.get(3).getM_LetB());
            }else{
                M_LetB_4.setVisibility(View.GONE);
                TG_LetB_4.setVisibility(View.VISIBLE);
                TG_LetB_4.setText(rq_dan_list.get(3).getM_LetB());
            }
            MBLetBRate_4.setText(rq_dan_list.get(3).getMB_LetB_Rate());
            TGLetBRate_4.setText(rq_dan_list.get(3).getTG_LetB_Rate());
        }


        //让球-半场
        /*MBLetBRateHTv_1.setText(dataBean.getMB_Team());
        MLetBH_1.setText(dataBean.getM_LetB_H());
        MBLetBRateH_1.setText(dataBean.getMB_LetB_Rate_H());
        TGLetBRateHTv_1.setText(dataBean.getTG_Team());
        TGLetBRateH_1.setText(dataBean.getTG_LetB_Rate_H());*/

        if(rq_ban_list.size()==0){//不显示
            ll_bet_rq_ban_all.setVisibility(View.GONE);
        }else if(rq_ban_list.size()==1){
            ll_bet_rq_ban_all.setVisibility(View.VISIBLE);
            ll_bet_rq_ban_list_1.setVisibility(View.VISIBLE);
            rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_rq_ban_list_2.setVisibility(View.GONE);
            ll_bet_rq_ban_list_3.setVisibility(View.GONE);
            MBLetBRateHTv_1.setText(dataBean.getMB_Team());
            TGLetBRateHTv_1.setText(dataBean.getTG_Team());

            if("H".equals(rq_ban_list.get(0).getShowTypeHR())){
                MLetBH_1.setVisibility(View.VISIBLE);
                TG_LetB_H1.setVisibility(View.GONE);
                MLetBH_1.setText(rq_ban_list.get(0).getM_LetB_H());
            }else{
                MLetBH_1.setVisibility(View.GONE);
                TG_LetB_H1.setVisibility(View.VISIBLE);
                TG_LetB_H1.setText(rq_ban_list.get(0).getM_LetB_H());
            }

            //MLetBH_1.setText(rq_ban_list.get(0).getM_LetB_H());
            MBLetBRateH_1.setText(rq_ban_list.get(0).getMB_LetB_Rate_H());
            TGLetBRateH_1.setText(rq_ban_list.get(0).getTG_LetB_Rate_H());
        }else if(rq_ban_list.size()==2){
            ll_bet_rq_ban_all.setVisibility(View.VISIBLE);
            ll_bet_rq_ban_list_1.setVisibility(View.VISIBLE);
            ll_bet_rq_ban_list_2.setVisibility(View.VISIBLE);
            rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_rq_ban_list_3.setVisibility(View.GONE);
            MBLetBRateHTv_1.setText(dataBean.getMB_Team());
            TGLetBRateHTv_1.setText(dataBean.getTG_Team());
            if("H".equals(rq_ban_list.get(0).getShowTypeHR())){
                MLetBH_1.setVisibility(View.VISIBLE);
                TG_LetB_H1.setVisibility(View.GONE);
                MLetBH_1.setText(rq_ban_list.get(0).getM_LetB_H());
            }else{
                MLetBH_1.setVisibility(View.GONE);
                TG_LetB_H1.setVisibility(View.VISIBLE);
                TG_LetB_H1.setText(rq_ban_list.get(0).getM_LetB_H());
            }
            //MLetBH_1.setText(rq_ban_list.get(0).getM_LetB_H());
            MBLetBRateH_1.setText(rq_ban_list.get(0).getMB_LetB_Rate_H());
            TGLetBRateH_1.setText(rq_ban_list.get(0).getTG_LetB_Rate_H());

            MBLetBRateHTv_2.setText(dataBean.getMB_Team());
            TGLetBRateHTv_2.setText(dataBean.getTG_Team());

            if("H".equals(rq_ban_list.get(1).getShowTypeHR())){
                MLetBH_2.setVisibility(View.VISIBLE);
                TG_LetB_H2.setVisibility(View.GONE);
                MLetBH_2.setText(rq_ban_list.get(1).getM_LetB_H());
            }else{
                MLetBH_2.setVisibility(View.GONE);
                TG_LetB_H2.setVisibility(View.VISIBLE);
                TG_LetB_H2.setText(rq_ban_list.get(1).getM_LetB_H());
            }
            //MLetBH_2.setText(rq_ban_list.get(1).getM_LetB_H());
            MBLetBRateH_2.setText(rq_ban_list.get(1).getMB_LetB_Rate_H());
            TGLetBRateH_2.setText(rq_ban_list.get(1).getTG_LetB_Rate_H());
        }else if(rq_ban_list.size()==3){
            ll_bet_rq_ban_all.setVisibility(View.VISIBLE);
            ll_bet_rq_ban_list_1.setVisibility(View.VISIBLE);
            ll_bet_rq_ban_list_2.setVisibility(View.VISIBLE);
            ll_bet_rq_ban_list_3.setVisibility(View.VISIBLE);
            rl_bet_rq_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            MBLetBRateHTv_1.setText(dataBean.getMB_Team());
            TGLetBRateHTv_1.setText(dataBean.getTG_Team());
            if("H".equals(rq_ban_list.get(0).getShowTypeHR())){
                MLetBH_1.setVisibility(View.VISIBLE);
                TG_LetB_H1.setVisibility(View.GONE);
                MLetBH_1.setText(rq_ban_list.get(0).getM_LetB_H());
            }else{
                MLetBH_1.setVisibility(View.GONE);
                TG_LetB_H1.setVisibility(View.VISIBLE);
                TG_LetB_H1.setText(rq_ban_list.get(0).getM_LetB_H());
            }
            //MLetBH_1.setText(rq_ban_list.get(0).getM_LetB_H());
            MBLetBRateH_1.setText(rq_ban_list.get(0).getMB_LetB_Rate_H());
            TGLetBRateH_1.setText(rq_ban_list.get(0).getTG_LetB_Rate_H());

            MBLetBRateHTv_2.setText(dataBean.getMB_Team());
            TGLetBRateHTv_2.setText(dataBean.getTG_Team());
            if("H".equals(rq_ban_list.get(1).getShowTypeHR())){
                MLetBH_2.setVisibility(View.VISIBLE);
                TG_LetB_H2.setVisibility(View.GONE);
                MLetBH_2.setText(rq_ban_list.get(1).getM_LetB_H());
            }else{
                MLetBH_2.setVisibility(View.GONE);
                TG_LetB_H2.setVisibility(View.VISIBLE);
                TG_LetB_H2.setText(rq_ban_list.get(1).getM_LetB_H());
            }
            //MLetBH_2.setText(rq_ban_list.get(1).getM_LetB_H());
            MBLetBRateH_2.setText(rq_ban_list.get(1).getMB_LetB_Rate_H());
            TGLetBRateH_2.setText(rq_ban_list.get(1).getTG_LetB_Rate_H());

            MBLetBRateHTv_3.setText(dataBean.getMB_Team());
            TGLetBRateHTv_3.setText(dataBean.getTG_Team());
            if("H".equals(rq_ban_list.get(2).getShowTypeHR())){
                MLetBH_3.setVisibility(View.VISIBLE);
                TG_LetB_H3.setVisibility(View.GONE);
                MLetBH_3.setText(rq_ban_list.get(2).getM_LetB_H());
            }else{
                MLetBH_3.setVisibility(View.GONE);
                TG_LetB_H3.setVisibility(View.VISIBLE);
                TG_LetB_H3.setText(rq_ban_list.get(2).getM_LetB_H());
            }
            //MLetBH_3.setText(rq_ban_list.get(2).getM_LetB_H());
            MBLetBRateH_3.setText(rq_ban_list.get(2).getMB_LetB_Rate_H());
            TGLetBRateH_3.setText(rq_ban_list.get(2).getTG_LetB_Rate_H());

        }

        //MBDime.setText(dataBean.getMB_Dime());

        //大小单场
        /*String mb_Dime  = dataBean.getMB_Dime();
        if("U".equals(mb_Dime.substring(0,1))){
            tvMBDime_1.setText("小");
        }else if("O".equals(mb_Dime.substring(0,1))){
            tvMBDime_1.setText("大");
        }
        MBDime_1.setText(mb_Dime.substring(1));
        MBDimeRate_1.setText(dataBean.getMB_Dime_Rate());

        //TGDime.setText(dataBean.getTG_Dime());
        String tg_Dime  = dataBean.getTG_Dime();
        if("U".equals(tg_Dime.substring(0,1))){
            tvTGDime_1.setText("小");
        }else if("O".equals(tg_Dime.substring(0,1))){
            tvTGDime_1.setText("大");
        }
        TGDime_1.setText(tg_Dime.substring(1));
        TGDimeRate_1.setText(dataBean.getTG_Dime_Rate());
*/

        if(dx_dan_list.size()==0){
            ll_bet_dx_dan_all.setVisibility(View.GONE);
        }else if(dx_dan_list.size()==1){
            ll_bet_dx_dan_all.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
            rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_dx_dan_list_2.setVisibility(View.GONE);
            ll_bet_dx_dan_list_3.setVisibility(View.GONE);
            ll_bet_dx_dan_list_4.setVisibility(View.GONE);
            String mb_Dime1  = dx_dan_list.get(0).getMB_Dime();
            if("U".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("小");
            }else if("O".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("大");
            }
            MBDime_1.setText(mb_Dime1.substring(1));
            MBDimeRate_1.setText(dx_dan_list.get(0).getMB_Dime_Rate());

            String tg_Dime1  = dx_dan_list.get(0).getTG_Dime();
            if("U".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("小");
            }else if("O".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("大");
            }
            TGDime_1.setText(tg_Dime1.substring(1));
            TGDimeRate_1.setText(dx_dan_list.get(0).getTG_Dime_Rate());
        }else if(dx_dan_list.size()==2){
            ll_bet_dx_dan_all.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_2.setVisibility(View.VISIBLE);
            rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_dx_dan_list_3.setVisibility(View.GONE);
            ll_bet_dx_dan_list_4.setVisibility(View.GONE);
            String mb_Dime1  = dx_dan_list.get(0).getMB_Dime();
            if("U".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("小");
            }else if("O".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("大");
            }
            MBDime_1.setText(mb_Dime1.substring(1));
            MBDimeRate_1.setText(dx_dan_list.get(0).getMB_Dime_Rate());
            String tg_Dime1  = dx_dan_list.get(0).getTG_Dime();
            if("U".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("小");
            }else if("O".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("大");
            }
            TGDime_1.setText(tg_Dime1.substring(1));
            TGDimeRate_1.setText(dx_dan_list.get(0).getTG_Dime_Rate());

            String mb_Dime2  = dx_dan_list.get(1).getMB_Dime();
            if("U".equals(mb_Dime2.substring(0,1))){
                tvMBDime_2.setText("小");
            }else if("O".equals(mb_Dime2.substring(0,1))){
                tvMBDime_2.setText("大");
            }
            MBDime_2.setText(mb_Dime2.substring(1));
            MBDimeRate_2.setText(dx_dan_list.get(1).getMB_Dime_Rate());
            String tg_Dime2  = dx_dan_list.get(1).getTG_Dime();
            if("U".equals(tg_Dime2.substring(0,1))){
                tvTGDime_2.setText("小");
            }else if("O".equals(tg_Dime2.substring(0,1))){
                tvTGDime_2.setText("大");
            }
            TGDime_2.setText(tg_Dime2.substring(1));
            TGDimeRate_2.setText(dx_dan_list.get(1).getTG_Dime_Rate());
        }else if(dx_dan_list.size()==3){
            ll_bet_dx_dan_all.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_2.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_3.setVisibility(View.VISIBLE);
            rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_dx_dan_list_4.setVisibility(View.GONE);
            String mb_Dime1  = dx_dan_list.get(0).getMB_Dime();
            if("U".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("小");
            }else if("O".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("大");
            }
            MBDime_1.setText(mb_Dime1.substring(1));
            MBDimeRate_1.setText(dx_dan_list.get(0).getMB_Dime_Rate());
            String tg_Dime1  = dx_dan_list.get(0).getTG_Dime();
            if("U".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("小");
            }else if("O".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("大");
            }
            TGDime_1.setText(tg_Dime1.substring(1));
            TGDimeRate_1.setText(dx_dan_list.get(0).getTG_Dime_Rate());

            String mb_Dime2  = dx_dan_list.get(1).getMB_Dime();
            if("U".equals(mb_Dime2.substring(0,1))){
                tvMBDime_2.setText("小");
            }else if("O".equals(mb_Dime2.substring(0,1))){
                tvMBDime_2.setText("大");
            }
            MBDime_2.setText(mb_Dime2.substring(1));
            MBDimeRate_2.setText(dx_dan_list.get(1).getMB_Dime_Rate());
            String tg_Dime2  = dx_dan_list.get(1).getTG_Dime();
            if("U".equals(tg_Dime2.substring(0,1))){
                tvTGDime_2.setText("小");
            }else if("O".equals(tg_Dime2.substring(0,1))){
                tvTGDime_2.setText("大");
            }
            TGDime_2.setText(tg_Dime2.substring(1));
            TGDimeRate_2.setText(dx_dan_list.get(1).getTG_Dime_Rate());

            String mb_Dime3  = dx_dan_list.get(2).getMB_Dime();
            if("U".equals(mb_Dime3.substring(0,1))){
                tvMBDime_3.setText("小");
            }else if("O".equals(mb_Dime3.substring(0,1))){
                tvMBDime_3.setText("大");
            }
            MBDime_3.setText(mb_Dime3.substring(1));
            MBDimeRate_3.setText(dx_dan_list.get(2).getMB_Dime_Rate());
            String tg_Dime3  = dx_dan_list.get(2).getTG_Dime();
            if("U".equals(tg_Dime3.substring(0,1))){
                tvTGDime_3.setText("小");
            }else if("O".equals(tg_Dime3.substring(0,1))){
                tvTGDime_3.setText("大");
            }
            TGDime_3.setText(tg_Dime3.substring(1));
            TGDimeRate_3.setText(dx_dan_list.get(2).getTG_Dime_Rate());
        }else if(dx_dan_list.size()==4){
            ll_bet_dx_dan_all.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_1.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_2.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_3.setVisibility(View.VISIBLE);
            ll_bet_dx_dan_list_4.setVisibility(View.VISIBLE);
            rl_bet_dx_dan_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            String mb_Dime1  = dx_dan_list.get(0).getMB_Dime();
            if("U".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("小");
            }else if("O".equals(mb_Dime1.substring(0,1))){
                tvMBDime_1.setText("大");
            }
            MBDime_1.setText(mb_Dime1.substring(1));
            MBDimeRate_1.setText(dx_dan_list.get(0).getMB_Dime_Rate());
            String tg_Dime1  = dx_dan_list.get(0).getTG_Dime();
            if("U".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("小");
            }else if("O".equals(tg_Dime1.substring(0,1))){
                tvTGDime_1.setText("大");
            }
            TGDime_1.setText(tg_Dime1.substring(1));
            TGDimeRate_1.setText(dx_dan_list.get(0).getTG_Dime_Rate());

            String mb_Dime2  = dx_dan_list.get(1).getMB_Dime();
            if("U".equals(mb_Dime2.substring(0,1))){
                tvMBDime_2.setText("小");
            }else if("O".equals(mb_Dime2.substring(0,1))){
                tvMBDime_2.setText("大");
            }
            MBDime_2.setText(mb_Dime2.substring(1));
            MBDimeRate_2.setText(dx_dan_list.get(1).getMB_Dime_Rate());
            String tg_Dime2  = dx_dan_list.get(1).getTG_Dime();
            if("U".equals(tg_Dime2.substring(0,1))){
                tvTGDime_2.setText("小");
            }else if("O".equals(tg_Dime2.substring(0,1))){
                tvTGDime_2.setText("大");
            }
            TGDime_2.setText(tg_Dime2.substring(1));
            TGDimeRate_2.setText(dx_dan_list.get(1).getTG_Dime_Rate());

            String mb_Dime3  = dx_dan_list.get(2).getMB_Dime();
            if("U".equals(mb_Dime3.substring(0,1))){
                tvMBDime_3.setText("小");
            }else if("O".equals(mb_Dime3.substring(0,1))){
                tvMBDime_3.setText("大");
            }
            MBDime_3.setText(mb_Dime3.substring(1));
            MBDimeRate_3.setText(dx_dan_list.get(2).getMB_Dime_Rate());
            String tg_Dime3  = dx_dan_list.get(2).getTG_Dime();
            if("U".equals(tg_Dime3.substring(0,1))){
                tvTGDime_3.setText("小");
            }else if("O".equals(tg_Dime3.substring(0,1))){
                tvTGDime_3.setText("大");
            }
            TGDime_3.setText(tg_Dime3.substring(1));
            TGDimeRate_3.setText(dx_dan_list.get(2).getTG_Dime_Rate());

            String mb_Dime4  = dx_dan_list.get(3).getMB_Dime();
            if("U".equals(mb_Dime4.substring(0,1))){
                tvMBDime_3.setText("小");
            }else if("O".equals(mb_Dime4.substring(0,1))){
                tvMBDime_3.setText("大");
            }
            MBDime_4.setText(mb_Dime4.substring(1));
            MBDimeRate_4.setText(dx_dan_list.get(3).getMB_Dime_Rate());
            String tg_Dime4  = dx_dan_list.get(3).getTG_Dime();
            if("U".equals(tg_Dime4.substring(0,1))){
                tvTGDime_4.setText("小");
            }else if("O".equals(tg_Dime4.substring(0,1))){
                tvTGDime_4.setText("大");
            }
            TGDime_4.setText(tg_Dime4.substring(1));
            TGDimeRate_4.setText(dx_dan_list.get(3).getTG_Dime_Rate());
        }


        //大小半场
        /*String mb_Dime_H  = dataBean.getMB_Dime_H();
        if(!Check.isEmpty(mb_Dime_H)&&"U".equals(mb_Dime_H.substring(0,1))){
            tvMBDimeH_1.setText("小");
        }else if(!Check.isEmpty(mb_Dime_H)&&"O".equals(mb_Dime_H.substring(0,1))){
            tvTGDimeH_1.setText("大");
        }
        if(mb_Dime_H.length()>=1){
            MBDimeH_1.setText(mb_Dime_H.substring(1));
        }else{
            MBDimeH_1.setText("");
        }
        MBDimeRateH_1.setText(dataBean.getMB_Dime_Rate_H());
        String tg_Dime_H  = dataBean.getTG_Dime_H();
        if(!Check.isEmpty(tg_Dime_H)&&"U".equals(tg_Dime_H.substring(0,1))){
            tvTGDimeH_1.setText("小");
        }else if(!Check.isEmpty(tg_Dime_H)&&"0".equals(tg_Dime_H.substring(0,1))){
            tvTGDimeH_1.setText("大");
        }
        if(TGDimeH_1.length()>=1){
            TGDimeH_1.setText(tg_Dime_H.substring(1));
        }else{
            TGDimeH_1.setText("");
        }
        TGDimeRateH_1.setText(dataBean.getTG_Dime_Rate_H());
*/
        if(dx_ban_list.size()==0){
            ll_bet_dx_ban_all.setVisibility(View.GONE);
        }else if(dx_ban_list.size()==1){
            ll_bet_dx_ban_all.setVisibility(View.VISIBLE);
            ll_bet_dx_ban_list_1.setVisibility(View.VISIBLE);
            rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_dx_ban_list_2.setVisibility(View.GONE);
            ll_bet_dx_ban_list_3.setVisibility(View.GONE);
            String mb_Dime_H1  = dx_ban_list.get(0).getMB_Dime_H();
            if(!Check.isEmpty(mb_Dime_H1)&&"U".equals(mb_Dime_H1.substring(0,1))){
                tvMBDimeH_1.setText("小");
            }else if(!Check.isEmpty(mb_Dime_H1)&&"O".equals(mb_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("大");
            }
            if(mb_Dime_H1.length()>=1){
                MBDimeH_1.setText(mb_Dime_H1.substring(1));
            }else{
                MBDimeH_1.setText("");
            }
            MBDimeRateH_1.setText(dx_ban_list.get(0).getMB_Dime_Rate_H());
            String tg_Dime_H1  = dx_ban_list.get(0).getTG_Dime_H();
            if(!Check.isEmpty(tg_Dime_H1)&&"U".equals(tg_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("小");
            }else if(!Check.isEmpty(tg_Dime_H1)&&"O".equals(tg_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("大");
            }
            if(TGDimeH_1.length()>=1){
                TGDimeH_1.setText(tg_Dime_H1.substring(1));
            }else{
                TGDimeH_1.setText("");
            }
            TGDimeRateH_1.setText(dx_ban_list.get(0).getTG_Dime_Rate_H());


        }else if(dx_ban_list.size()==2){
            ll_bet_dx_ban_all.setVisibility(View.VISIBLE);
            ll_bet_dx_ban_list_1.setVisibility(View.VISIBLE);
            ll_bet_dx_ban_list_2.setVisibility(View.VISIBLE);
            rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_bet_dx_ban_list_3.setVisibility(View.GONE);

            String mb_Dime_H1  = dx_ban_list.get(0).getMB_Dime_H();
            if(!Check.isEmpty(mb_Dime_H1)&&"U".equals(mb_Dime_H1.substring(0,1))){
                tvMBDimeH_1.setText("小");
            }else if(!Check.isEmpty(mb_Dime_H1)&&"O".equals(mb_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("大");
            }
            if(mb_Dime_H1.length()>=1){
                MBDimeH_1.setText(mb_Dime_H1.substring(1));
            }else{
                MBDimeH_1.setText("");
            }
            MBDimeRateH_1.setText(dx_ban_list.get(0).getMB_Dime_Rate_H());
            String tg_Dime_H1  = dx_ban_list.get(0).getTG_Dime_H();
            if(!Check.isEmpty(tg_Dime_H1)&&"U".equals(tg_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("小");
            }else if(!Check.isEmpty(tg_Dime_H1)&&"O".equals(tg_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("大");
            }
            if(TGDimeH_1.length()>=1){
                TGDimeH_1.setText(tg_Dime_H1.substring(1));
            }else{
                TGDimeH_1.setText("");
            }
            TGDimeRateH_1.setText(dx_ban_list.get(0).getTG_Dime_Rate_H());

            String mb_Dime_H2  = dx_ban_list.get(1).getMB_Dime_H();
            if(!Check.isEmpty(mb_Dime_H2)&&"U".equals(mb_Dime_H2.substring(0,1))){
                tvMBDimeH_2.setText("小");
            }else if(!Check.isEmpty(mb_Dime_H2)&&"O".equals(mb_Dime_H2.substring(0,1))){
                tvTGDimeH_2.setText("大");
            }
            if(mb_Dime_H2.length()>=1){
                MBDimeH_2.setText(mb_Dime_H2.substring(1));
            }else{
                MBDimeH_2.setText("");
            }
            MBDimeRateH_2.setText(dx_ban_list.get(1).getMB_Dime_Rate_H());
            String tg_Dime_H2  = dx_ban_list.get(1).getTG_Dime_H();
            if(!Check.isEmpty(tg_Dime_H2)&&"U".equals(tg_Dime_H2.substring(0,1))){
                tvTGDimeH_2.setText("小");
            }else if(!Check.isEmpty(tg_Dime_H2)&&"O".equals(tg_Dime_H2.substring(0,1))){
                tvTGDimeH_2.setText("大");
            }
            if(TGDimeH_2.length()>=1){
                TGDimeH_2.setText(tg_Dime_H2.substring(1));
            }else{
                TGDimeH_2.setText("");
            }
            TGDimeRateH_2.setText(dx_ban_list.get(1).getTG_Dime_Rate_H());

        }else if(dx_ban_list.size()==3){
            ll_bet_dx_ban_all.setVisibility(View.VISIBLE);
            ll_bet_dx_ban_list_1.setVisibility(View.VISIBLE);
            ll_bet_dx_ban_list_2.setVisibility(View.VISIBLE);
            ll_bet_dx_ban_list_3.setVisibility(View.VISIBLE);
            rl_bet_dx_ban_line_right.setBackgroundResource(R.mipmap.icon_ex_down);
            String mb_Dime_H1  = dx_ban_list.get(0).getMB_Dime_H();
            if(!Check.isEmpty(mb_Dime_H1)&&"U".equals(mb_Dime_H1.substring(0,1))){
                tvMBDimeH_1.setText("小");
            }else if(!Check.isEmpty(mb_Dime_H1)&&"O".equals(mb_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("大");
            }
            if(mb_Dime_H1.length()>=1){
                MBDimeH_1.setText(mb_Dime_H1.substring(1));
            }else{
                MBDimeH_1.setText("");
            }
            MBDimeRateH_1.setText(dx_ban_list.get(0).getMB_Dime_Rate_H());
            String tg_Dime_H1  = dx_ban_list.get(0).getTG_Dime_H();
            if(!Check.isEmpty(tg_Dime_H1)&&"U".equals(tg_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("小");
            }else if(!Check.isEmpty(tg_Dime_H1)&&"O".equals(tg_Dime_H1.substring(0,1))){
                tvTGDimeH_1.setText("大");
            }
            if(TGDimeH_1.length()>=1){
                TGDimeH_1.setText(tg_Dime_H1.substring(1));
            }else{
                TGDimeH_1.setText("");
            }
            TGDimeRateH_1.setText(dx_ban_list.get(0).getTG_Dime_Rate_H());

            String mb_Dime_H2  = dx_ban_list.get(1).getMB_Dime_H();
            if(!Check.isEmpty(mb_Dime_H2)&&"U".equals(mb_Dime_H2.substring(0,1))){
                tvMBDimeH_2.setText("小");
            }else if(!Check.isEmpty(mb_Dime_H2)&&"O".equals(mb_Dime_H2.substring(0,1))){
                tvTGDimeH_2.setText("大");
            }
            if(mb_Dime_H2.length()>=1){
                MBDimeH_2.setText(mb_Dime_H2.substring(1));
            }else{
                MBDimeH_2.setText("");
            }
            MBDimeRateH_2.setText(dx_ban_list.get(1).getMB_Dime_Rate_H());
            String tg_Dime_H2  = dx_ban_list.get(1).getTG_Dime_H();
            if(!Check.isEmpty(tg_Dime_H2)&&"U".equals(tg_Dime_H2.substring(0,1))){
                tvTGDimeH_2.setText("小");
            }else if(!Check.isEmpty(tg_Dime_H2)&&"O".equals(tg_Dime_H2.substring(0,1))){
                tvTGDimeH_2.setText("大");
            }
            if(TGDimeH_2.length()>=1){
                TGDimeH_2.setText(tg_Dime_H2.substring(1));
            }else{
                TGDimeH_2.setText("");
            }
            TGDimeRateH_2.setText(dx_ban_list.get(1).getTG_Dime_Rate_H());

            String mb_Dime_H3  = dx_ban_list.get(2).getMB_Dime_H();
            if(!Check.isEmpty(mb_Dime_H3)&&"U".equals(mb_Dime_H3.substring(0,1))){
                tvMBDimeH_3.setText("小");
            }else if(!Check.isEmpty(mb_Dime_H3)&&"O".equals(mb_Dime_H3.substring(0,1))){
                tvTGDimeH_3.setText("大");
            }
            if(mb_Dime_H3.length()>=1){
                MBDimeH_3.setText(mb_Dime_H3.substring(1));
            }else{
                MBDimeH_3.setText("");
            }
            MBDimeRateH_3.setText(dx_ban_list.get(2).getMB_Dime_Rate_H());
            String tg_Dime_H3  = dx_ban_list.get(2).getTG_Dime_H();
            if(!Check.isEmpty(tg_Dime_H3)&&"U".equals(tg_Dime_H3.substring(0,1))){
                tvTGDimeH_3.setText("小");
            }else if(!Check.isEmpty(tg_Dime_H3)&&"O".equals(tg_Dime_H3.substring(0,1))){
                tvTGDimeH_3.setText("大");
            }
            if(TGDimeH_3.length()>=1){
                TGDimeH_3.setText(tg_Dime_H3.substring(1));
            }else{
                TGDimeH_3.setText("");
            }
            TGDimeRateH_3.setText(dx_ban_list.get(2).getTG_Dime_Rate_H());


        }

        //单双逻辑
        /*if(!Check.isEmpty(dataBean.getS_Single_Rate())){
            llS_S_D_RateALl.setVisibility(View.VISIBLE);
            SSingleRate_1.setText(dataBean.getS_Single_Rate());
            SDoubleRate_1.setText(dataBean.getS_Double_Rate());
        }else{
            llS_S_D_RateALl.setVisibility(View.GONE);
        }*/

        if(ds_list.size()==0){
            llS_S_D_RateALl.setVisibility(View.GONE);
        }else if(ds_list.size()==1){
            llS_S_D_RateALl.setVisibility(View.VISIBLE);
            ll_D_S_Rate_1.setVisibility(View.VISIBLE);
            rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_D_S_Rate_2.setVisibility(View.GONE);
            ll_D_S_Rate_3.setVisibility(View.GONE);
            SSingleRate_1.setText(ds_list.get(0).getS_Single_Rate());
            SDoubleRate_1.setText(ds_list.get(0).getS_Double_Rate());
        }else if(ds_list.size()==2){
            llS_S_D_RateALl.setVisibility(View.VISIBLE);
            ll_D_S_Rate_1.setVisibility(View.VISIBLE);
            ll_D_S_Rate_2.setVisibility(View.VISIBLE);
            rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_D_S_Rate_3.setVisibility(View.GONE);
            SSingleRate_1.setText(ds_list.get(0).getS_Single_Rate());
            SDoubleRate_1.setText(ds_list.get(0).getS_Double_Rate());
            SSingleRate_2.setText(ds_list.get(1).getS_Single_Rate());
            SDoubleRate_2.setText(ds_list.get(1).getS_Double_Rate());
        }else if(ds_list.size()==3){
            llS_S_D_RateALl.setVisibility(View.VISIBLE);
            ll_D_S_Rate_1.setVisibility(View.VISIBLE);
            ll_D_S_Rate_2.setVisibility(View.VISIBLE);
            rlS_S_D_RateALl_right.setBackgroundResource(R.mipmap.icon_ex_down);
            ll_D_S_Rate_3.setVisibility(View.VISIBLE);
            SSingleRate_1.setText(ds_list.get(0).getS_Single_Rate());
            SDoubleRate_1.setText(ds_list.get(0).getS_Double_Rate());
            SSingleRate_2.setText(ds_list.get(1).getS_Single_Rate());
            SDoubleRate_2.setText(ds_list.get(1).getS_Double_Rate());
            SSingleRate_3.setText(ds_list.get(2).getS_Single_Rate());
            SDoubleRate_3.setText(ds_list.get(2).getS_Double_Rate());
        }

    }

    @Override
    public void postBetResult(BetResult betResult) {

        GameLog.log("下注的实际情况："+betResult.toString());
    }

    @Override
    public void setPresenter(BetContract.Presenter presenter) {
        this.presenter = presenter;
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(new onWaitingThread(), 0, 1000, TimeUnit.MILLISECONDS);
    }

    //等待时长
    class onWaitingThread implements Runnable {
        @Override
        public void run() {
            if (sendAuthTime-- <= 0) {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {

                       onVisible();
                    }
                });
            } else {
                getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if(ivBetEventRefresh!=null){
                            ivBetEventRefresh.setText(""+ sendAuthTime);
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });
            }
        }
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if(null!=executorService){
            executorService.shutdown();
            executorService.shutdownNow();
        }

    }



    private void onClearListData(){
        MID.clear();
        rq_dan_list.clear();
        rq_ban_list.clear();
        dx_dan_list.clear();
        dx_ban_list.clear();
        ds_list.clear();
    }

}

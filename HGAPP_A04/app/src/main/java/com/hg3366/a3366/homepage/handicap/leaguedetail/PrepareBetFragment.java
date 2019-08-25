package com.hg3366.a3366.homepage.handicap.leaguedetail;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.hg3366.a3366.Injections;
import com.hg3366.a3366.R;
import com.hg3366.a3366.base.HGBaseFragment;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.common.adapters.AutoSizeRVAdapter;
import com.hg3366.a3366.common.util.ACache;
import com.hg3366.a3366.common.util.GameShipHelper;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.data.BetResult;
import com.hg3366.a3366.data.GameAllPlayBKResult;
import com.hg3366.a3366.data.GameAllPlayFTResult;
import com.hg3366.a3366.data.GameAllPlayRBKResult;
import com.hg3366.a3366.data.GameAllPlayRFTResult;
import com.hg3366.a3366.data.PrepareBetResult;
import com.hg3366.a3366.data.SwDua;
import com.hg3366.a3366.data.SwPDMD2TG;
import com.hg3366.a3366.homepage.handicap.betapi.PrepareBetApiContract;
import com.hg3366.a3366.homepage.handicap.betapi.PrepareRequestParams;
import com.hg3366.a3366.homepage.sportslist.bet.BetOrderSubmitDialog;
import com.hg3366.a3366.homepage.sportslist.bet.SportMethodResult;
import com.hg3366.common.util.Check;
import com.hg3366.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class PrepareBetFragment extends HGBaseFragment implements PrepareBetApiContract.View {

    private static final String ARG_PARAM1 = "mLeague";
    private static final String ARG_PARAM2 = "mTeamH";
    private static final String ARG_PARAM3 = "mTeamC";
    private static final String ARG_PARAM4 = "gid";
    private static final String ARG_PARAM5 = "gtype";
    private static final String ARG_PARAM6 = "showtype";
    private static final String ARG_PARAM7 = "userMoney";
    private static final String ARG_PARAM8 = "fromType";
    private static final String ARG_PARAM9 = "fromString";
    //    @BindView(R.id.ivBetEventBack)
//    ImageView ivBetEventBack;
    @BindView(R.id.prepareBetTop)
    ScrollView prepareBetTop;
    @BindView(R.id.imprepareBetTop)
    ImageView imprepareBetTop;
    @BindView(R.id.tvBetEventName)
    TextView tvBetEventName;
    @BindView(R.id.ivBetEventRefresh)
    ImageView ivBetEventRefresh;
    @BindView(R.id.tvBetEventRefresh)
    TextView tvBetEventRefresh;
    @BindView(R.id.teamRFT)
    LinearLayout teamRFT;
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
    @BindView(R.id.teamLiveTime)
    TextView teamLiveTime;
    @BindView(R.id.rbkStateKK)
    TextView rbkStateKK;
    @BindView(R.id.tvPrepareBetNoData)
    TextView tvPrepareBetNoData;
    @BindView(R.id.ivRangMark)
    ImageView ivRangMark;
    @BindView(R.id.rlRang)
    RelativeLayout rlRang;
    @BindView(R.id.tvTeamHRangName)
    TextView tvTeamHRangName;
    @BindView(R.id.tv_ior_RH_ratio)
    TextView tvIorRHRatio;
    @BindView(R.id.tv_ior_RH)
    TextView tvIorRH;
    @BindView(R.id.rlTeamHRang)
    RelativeLayout rlTeamHRang;
    @BindView(R.id.tvTeamCRangName)
    TextView tvTeamCRangName;
    @BindView(R.id.tv_ior_RC_ratio)
    TextView tvIorRCRatio;
    @BindView(R.id.tv_ior_RC)
    TextView tvIorRC;
    @BindView(R.id.sw_ALL)
    LinearLayout swALL;
    @BindView(R.id.rlTeamCRang)
    RelativeLayout rlTeamCRang;
    @BindView(R.id.sw_R_show)
    LinearLayout sw_R_show;
    @BindView(R.id.sw_R)
    LinearLayout sw_R;
    Unbinder unbinder;
    @BindView(R.id.sw_M_Mark)
    ImageView swMMark;
    @BindView(R.id.sw_M_click)
    RelativeLayout swMClick;
    @BindView(R.id.ior_MH_Name)
    TextView iorMHName;
    @BindView(R.id.ior_MH)
    TextView iorMH;
    @BindView(R.id.ior_MH_click)
    RelativeLayout iorMHClick;
    @BindView(R.id.ior_MC_Name)
    TextView iorMCName;
    @BindView(R.id.ior_MC)
    TextView iorMC;
    @BindView(R.id.ior_MC_click)
    RelativeLayout iorMCClick;
    @BindView(R.id.ior_MN)
    TextView iorMN;
    @BindView(R.id.ior_MN_click)
    RelativeLayout iorMNClick;
    @BindView(R.id.sw_M_show)
    LinearLayout swMShow;
    @BindView(R.id.sw_M_All)
    LinearLayout swMAll;
    @BindView(R.id.sw_HM_Mark)
    ImageView swHMMark;
    @BindView(R.id.sw_HM_click)
    RelativeLayout swHMClick;
    @BindView(R.id.ior_HMH_Name)
    TextView iorHMHName;
    @BindView(R.id.ior_HMH)
    TextView iorHMH;
    @BindView(R.id.ior_HMH_click)
    RelativeLayout iorHMHClick;
    @BindView(R.id.ior_HMC_Name)
    TextView iorHMCName;
    @BindView(R.id.ior_HMC)
    TextView iorHMC;
    @BindView(R.id.ior_HMC_click)
    RelativeLayout iorHMCClick;
    @BindView(R.id.ior_HMN)
    TextView iorHMN;
    @BindView(R.id.ior_HMN_click)
    RelativeLayout iorHMNClick;
    @BindView(R.id.sw_HM_show)
    LinearLayout swHMShow;
    @BindView(R.id.sw_HM_All)
    LinearLayout swHMAll;
    @BindView(R.id.sw_WE_Mark)
    ImageView swWEMark;
    @BindView(R.id.sw_WE_Click)
    RelativeLayout swWEClick;
    @BindView(R.id.ior_WEH_Name)
    TextView iorWEHName;
    @BindView(R.id.ior_WEH)
    TextView iorWEH;
    @BindView(R.id.ior_WEH_Click)
    RelativeLayout iorWEHClick;
    @BindView(R.id.ior_WEC_Name)
    TextView iorWECName;
    @BindView(R.id.ior_WEC)
    TextView iorWEC;
    @BindView(R.id.ior_WEC_Click)
    RelativeLayout iorWECClick;
    @BindView(R.id.sw_WE_show)
    LinearLayout swWEShow;
    @BindView(R.id.sw_WE_All)
    LinearLayout swWEAll;
    @BindView(R.id.sw_WB_Mark)
    ImageView swWBMark;
    @BindView(R.id.sw_WB_Click)
    RelativeLayout swWBClick;
    @BindView(R.id.ior_WBH_Name)
    TextView iorWBHName;
    @BindView(R.id.ior_WBH)
    TextView iorWBH;
    @BindView(R.id.ior_WBH_Click)
    RelativeLayout iorWBHClick;
    @BindView(R.id.ior_WBC_Name)
    TextView iorWBCName;
    @BindView(R.id.ior_WBC)
    TextView iorWBC;
    @BindView(R.id.ior_WBC_Click)
    RelativeLayout iorWBCClick;
    @BindView(R.id.sw_WB_show)
    LinearLayout swWBShow;
    @BindView(R.id.sw_WB_All)
    LinearLayout swWBAll;
    @BindView(R.id.sw_T_Mark)
    ImageView swTMark;
    @BindView(R.id.sw_T_Click)
    RelativeLayout swTClick;
    @BindView(R.id.ior_T01)
    TextView iorT01;
    @BindView(R.id.ior_T01_Click)
    RelativeLayout iorT01Click;
    @BindView(R.id.ior_T23)
    TextView iorT23;
    @BindView(R.id.ior_T23_Click)
    RelativeLayout iorT23Click;
    @BindView(R.id.ior_T46)
    TextView iorT46;
    @BindView(R.id.ior_T46_Click)
    RelativeLayout iorT46Click;
    @BindView(R.id.ior_OVER)
    TextView iorOVER;
    @BindView(R.id.ior_OVER_Click)
    RelativeLayout iorOVERClick;
    @BindView(R.id.sw_T_show)
    LinearLayout swTShow;
    @BindView(R.id.sw_T_All)
    LinearLayout swTAll;
    @BindView(R.id.sw_HT_Mark)
    ImageView swHTMark;
    @BindView(R.id.sw_HT_Click)
    RelativeLayout swHTClick;
    @BindView(R.id.ior_HT0)
    TextView iorHT0;
    @BindView(R.id.ior_HT01_Click)
    RelativeLayout iorHT01Click;
    @BindView(R.id.ior_HT1)
    TextView iorHT1;
    @BindView(R.id.ior_HT1_Click)
    RelativeLayout iorHT1Click;
    @BindView(R.id.ior_HT2)
    TextView iorHT2;
    @BindView(R.id.ior_HT2_Click)
    RelativeLayout iorHT2Click;
    @BindView(R.id.ior_HTOV)
    TextView iorHTOV;
    @BindView(R.id.ior_HTOV_Click)
    RelativeLayout iorHTOVClick;
    @BindView(R.id.sw_HT_show)
    LinearLayout swHTShow;
    @BindView(R.id.sw_HT_All)
    LinearLayout swHTAll;
    @BindView(R.id.sw_TS_Mark)
    ImageView swTSMark;
    @BindView(R.id.sw_TS_Click)
    RelativeLayout swTSClick;
    @BindView(R.id.ior_TSY)
    TextView iorTSY;
    @BindView(R.id.ior_TSY_Click)
    RelativeLayout iorTSYClick;
    @BindView(R.id.ior_TSN)
    TextView iorTSN;
    @BindView(R.id.ior_TSN_Click)
    RelativeLayout iorTSNClick;
    @BindView(R.id.sw_TS_show)
    LinearLayout swTSShow;
    @BindView(R.id.sw_TS_All)
    LinearLayout swTSAll;
    @BindView(R.id.sw_HTS_Mark)
    ImageView swHTSMark;
    @BindView(R.id.sw_HTS_Click)
    RelativeLayout swHTSClick;
    @BindView(R.id.ior_HTSY)
    TextView iorHTSY;
    @BindView(R.id.ior_HTSY_Click)
    RelativeLayout iorHTSYClick;
    @BindView(R.id.ior_HTSN)
    TextView iorHTSN;
    @BindView(R.id.ior_HTSN_Click)
    RelativeLayout iorHTSNClick;
    @BindView(R.id.sw_HTS_show)
    LinearLayout swHTSShow;
    @BindView(R.id.sw_HTS_All)
    LinearLayout swHTSAll;
    @BindView(R.id.sw_EO_Mark)
    ImageView swEOMark;
    @BindView(R.id.sw_EO_Click)
    RelativeLayout swEOClick;
    @BindView(R.id.sw_EO_Name)
    TextView swEOName;
    @BindView(R.id.ior_EOO)
    TextView iorEOO;
    @BindView(R.id.ior_EOO_Click)
    RelativeLayout iorEOOClick;
    @BindView(R.id.ior_EOE)
    TextView iorEOE;
    @BindView(R.id.ior_EOE_Click)
    RelativeLayout iorEOEClick;
    @BindView(R.id.sw_EO_show)
    LinearLayout swEOShow;
    @BindView(R.id.sw_EO_All)
    LinearLayout swEOAll;
    @BindView(R.id.sw_HEO_Mark)
    ImageView swHEOMark;
    @BindView(R.id.sw_HEO_Click)
    RelativeLayout swHEOClick;
    @BindView(R.id.ior_HEOE)
    TextView iorHEOE;
    @BindView(R.id.ior_HEOE_Click)
    RelativeLayout iorHEOEClick;
    @BindView(R.id.ior_HEOO)
    TextView iorHEOO;
    @BindView(R.id.ior_HEOO_Click)
    RelativeLayout iorHEOOClick;
    @BindView(R.id.sw_HEO_show)
    LinearLayout swHEOShow;
    @BindView(R.id.sw_HEO_All)
    LinearLayout swHEOAll;
    @BindView(R.id.sw_CS_Mark)
    ImageView swCSMark;
    @BindView(R.id.sw_CS_Click)
    RelativeLayout swCSClick;
    @BindView(R.id.ior_CSH_Name)
    TextView iorCSHName;
    @BindView(R.id.ior_CSH)
    TextView iorCSH;
    @BindView(R.id.ior_CSH_Click)
    RelativeLayout iorCSHClick;
    @BindView(R.id.ior_CSC_Name)
    TextView iorCSCName;
    @BindView(R.id.ior_CSC)
    TextView iorCSC;
    @BindView(R.id.ior_CSC_Click)
    RelativeLayout iorCSCClick;
    @BindView(R.id.sw_CS_show)
    LinearLayout swCSShow;
    @BindView(R.id.sw_CS_All)
    LinearLayout swCSAll;
    @BindView(R.id.sw_WN_Mark)
    ImageView swWNMark;
    @BindView(R.id.sw_WN_Click)
    RelativeLayout swWNClick;
    @BindView(R.id.ior_WNH_Name)
    TextView iorWNHName;
    @BindView(R.id.ior_WNH)
    TextView iorWNH;
    @BindView(R.id.ior_WNH_Click)
    RelativeLayout iorWNHClick;
    @BindView(R.id.ior_WNC_Name)
    TextView iorWNCName;
    @BindView(R.id.ior_WNC)
    TextView iorWNC;
    @BindView(R.id.ior_WNC_Click)
    RelativeLayout iorWNCClick;
    @BindView(R.id.sw_WN_show)
    LinearLayout swWNShow;
    @BindView(R.id.sw_WN_All)
    LinearLayout swWNAll;
    @BindView(R.id.sw_HG_Mark)
    ImageView swHGMark;
    @BindView(R.id.sw_HG_Click)
    RelativeLayout swHGClick;
    @BindView(R.id.ior_HGH)
    TextView iorHGH;
    @BindView(R.id.ior_HGH_Click)
    RelativeLayout iorHGHClick;
    @BindView(R.id.ior_HGC_Name)
    TextView iorHGCName;
    @BindView(R.id.ior_HGC)
    TextView iorHGC;
    @BindView(R.id.ior_HGC_Click)
    RelativeLayout iorHGCClick;
    @BindView(R.id.sw_HG_show)
    LinearLayout swHGShow;
    @BindView(R.id.sw_HG_All)
    LinearLayout swHGAll;
    @BindView(R.id.sw_MG_Mark)
    ImageView swMGMark;
    @BindView(R.id.sw_MG_Click)
    RelativeLayout swMGClick;
    @BindView(R.id.ior_MGH)
    TextView iorMGH;
    @BindView(R.id.ior_MGH_Click)
    RelativeLayout iorMGHClick;
    @BindView(R.id.ior_MGC)
    TextView iorMGC;
    @BindView(R.id.ior_MGC_Click)
    RelativeLayout iorMGCClick;
    @BindView(R.id.ior_MGN)
    TextView iorMGN;
    @BindView(R.id.ior_MGN_Click)
    RelativeLayout iorMGNClick;
    @BindView(R.id.sw_MG_show)
    LinearLayout swMGShow;
    @BindView(R.id.sw_MG_All)
    LinearLayout swMGAll;
    @BindView(R.id.sw_SB_Mark)
    ImageView swSBMark;
    @BindView(R.id.sw_SB_Click)
    RelativeLayout swSBClick;
    @BindView(R.id.ior_SBH_Name)
    TextView iorSBHName;
    @BindView(R.id.ior_SBH)
    TextView iorSBH;
    @BindView(R.id.ior_SBH_Click)
    RelativeLayout iorSBHClick;
    @BindView(R.id.ior_SBC_Name)
    TextView iorSBCName;
    @BindView(R.id.ior_SBC)
    TextView iorSBC;
    @BindView(R.id.ior_SBC_Click)
    RelativeLayout iorSBCClick;
    @BindView(R.id.sw_SB_show)
    LinearLayout swSBShow;
    @BindView(R.id.sw_SB_All)
    LinearLayout swSBAll;
    @BindView(R.id.sw_F_Mark)
    ImageView swFMark;
    @BindView(R.id.sw_F_Click)
    RelativeLayout swFClick;
    @BindView(R.id.ior_FHH_Name)
    TextView iorFHHName;
    @BindView(R.id.ior_FHH)
    TextView iorFHH;
    @BindView(R.id.ior_FHH_Click)
    RelativeLayout iorFHHClick;
    @BindView(R.id.ior_FHN_Name)
    TextView iorFHNName;
    @BindView(R.id.ior_FHN)
    TextView iorFHN;
    @BindView(R.id.ior_FHN_Click)
    RelativeLayout iorFHNClick;
    @BindView(R.id.ior_FHC_Name)
    TextView iorFHCName;
    @BindView(R.id.ior_FHC)
    TextView iorFHC;
    @BindView(R.id.ior_FHC_Click)
    RelativeLayout iorFHCClick;
    @BindView(R.id.ior_FNH_Name)
    TextView iorFNHName;
    @BindView(R.id.ior_FNH)
    TextView iorFNH;
    @BindView(R.id.ior_FNH_Click)
    RelativeLayout iorFNHClick;
    @BindView(R.id.ior_FNN_Name)
    TextView iorFNNName;
    @BindView(R.id.ior_FNN)
    TextView iorFNN;
    @BindView(R.id.ior_FNN_Click)
    RelativeLayout iorFNNClick;
    @BindView(R.id.ior_FNC_Name)
    TextView iorFNCName;
    @BindView(R.id.ior_FNC)
    TextView iorFNC;
    @BindView(R.id.ior_FNC_Click)
    RelativeLayout iorFNCClick;
    @BindView(R.id.ior_FCH_Name)
    TextView iorFCHName;
    @BindView(R.id.ior_FCH)
    TextView iorFCH;
    @BindView(R.id.ior_FCH_Click)
    RelativeLayout iorFCHClick;
    @BindView(R.id.ior_FCN_Name)
    TextView iorFCNName;
    @BindView(R.id.ior_FCN)
    TextView iorFCN;
    @BindView(R.id.ior_FCN_Click)
    RelativeLayout iorFCNClick;
    @BindView(R.id.ior_FCC_Name)
    TextView iorFCCName;
    @BindView(R.id.ior_FCC)
    TextView iorFCC;
    @BindView(R.id.ior_FCC_Click)
    RelativeLayout iorFCCClick;
    @BindView(R.id.sw_F_show)
    LinearLayout swFShow;
    @BindView(R.id.sw_F_All)
    LinearLayout swFAll;
    @BindView(R.id.sw_WM_Mark)
    ImageView swWMMark;
    @BindView(R.id.sw_WM_Click)
    RelativeLayout swWMClick;
    @BindView(R.id.ior_WMH_Name)
    TextView iorWMHName;
    @BindView(R.id.ior_WMH1)
    TextView iorWMH1;
    @BindView(R.id.ior_WMH1_Click)
    RelativeLayout iorWMH1Click;
    @BindView(R.id.ior_WMH2)
    TextView iorWMH2;
    @BindView(R.id.ior_WMH2_Click)
    RelativeLayout iorWMH2Click;
    @BindView(R.id.ior_WMH3)
    TextView iorWMH3;
    @BindView(R.id.ior_WMH3_Click)
    RelativeLayout iorWMH3Click;
    @BindView(R.id.ior_WMHOV)
    TextView iorWMHOV;
    @BindView(R.id.ior_WMHOV_Click)
    RelativeLayout iorWMHOVClick;
    @BindView(R.id.ior_WMC_Name)
    TextView iorWMCName;
    @BindView(R.id.ior_WMC1)
    TextView iorWMC1;
    @BindView(R.id.ior_WMC1_Click)
    RelativeLayout iorWMC1Click;
    @BindView(R.id.ior_WMC2)
    TextView iorWMC2;
    @BindView(R.id.ior_WMC2_Click)
    RelativeLayout iorWMC2Click;
    @BindView(R.id.ior_WMC3)
    TextView iorWMC3;
    @BindView(R.id.ior_WMC3_Click)
    RelativeLayout iorWMC3Click;
    @BindView(R.id.ior_WMCOV)
    TextView iorWMCOV;
    @BindView(R.id.ior_WMCOV_Click)
    RelativeLayout iorWMCOVClick;
    @BindView(R.id.ior_WM0)
    TextView iorWM0;
    @BindView(R.id.ior_WM0_Click)
    RelativeLayout iorWM0Click;
    @BindView(R.id.ior_WMN)
    TextView iorWMN;
    @BindView(R.id.ior_WMN_Click)
    RelativeLayout iorWMNClick;
    @BindView(R.id.sw_WM_show)
    LinearLayout swWMShow;
    @BindView(R.id.sw_WM_All)
    LinearLayout swWMAll;
    @BindView(R.id.sw_DC_Mark)
    ImageView swDCMark;
    @BindView(R.id.sw_DC_click)
    RelativeLayout swDCClick;
    @BindView(R.id.ior_DCHN_Name)
    TextView iorDCHNName;
    @BindView(R.id.ior_DCHN)
    TextView iorDCHN;
    @BindView(R.id.ior_DCHN_click)
    RelativeLayout iorDCHNClick;
    @BindView(R.id.ior_DCCN_Name)
    TextView iorDCCNName;
    @BindView(R.id.ior_DCCN)
    TextView iorDCCN;
    @BindView(R.id.ior_DCCN_click)
    RelativeLayout iorDCCNClick;
    @BindView(R.id.ior_DCHC_Name)
    TextView iorDCHCName;
    @BindView(R.id.ior_DCHC)
    TextView iorDCHC;
    @BindView(R.id.ior_DCHC_click)
    RelativeLayout iorDCHCClick;
    @BindView(R.id.sw_DC_show)
    LinearLayout swDCShow;
    @BindView(R.id.sw_DC_All)
    LinearLayout swDCAll;
    @BindView(R.id.sw_MTS_Mark)
    ImageView swMTSMark;
    @BindView(R.id.sw_MTS_Click)
    RelativeLayout swMTSClick;
    @BindView(R.id.ior_MTSHY_Name)
    TextView iorMTSHYName;
    @BindView(R.id.ior_MTSHY)
    TextView iorMTSHY;
    @BindView(R.id.ior_MTSHY_Click)
    RelativeLayout iorMTSHYClick;
    @BindView(R.id.ior_MTSHN_Name)
    TextView iorMTSHNName;
    @BindView(R.id.ior_MTSHN)
    TextView iorMTSHN;
    @BindView(R.id.ior_MTSHN_Click)
    RelativeLayout iorMTSHNClick;
    @BindView(R.id.ior_MTSNY)
    TextView iorMTSNY;
    @BindView(R.id.ior_MTSNY_Click)
    RelativeLayout iorMTSNYClick;
    @BindView(R.id.ior_MTSNN)
    TextView iorMTSNN;
    @BindView(R.id.ior_MTSNN_Click)
    RelativeLayout iorMTSNNClick;
    @BindView(R.id.ior_MTSCY_Name)
    TextView iorMTSCYName;
    @BindView(R.id.ior_MTSCY)
    TextView iorMTSCY;
    @BindView(R.id.ior_MTSCY_Click)
    RelativeLayout iorMTSCYClick;
    @BindView(R.id.ior_MTSCN_Name)
    TextView iorMTSCNName;
    @BindView(R.id.ior_MTSCN)
    TextView iorMTSCN;
    @BindView(R.id.ior_MTSCN_Click)
    RelativeLayout iorMTSCNClick;
    @BindView(R.id.sw_MTS_show)
    LinearLayout swMTSShow;
    @BindView(R.id.sw_MTS_All)
    LinearLayout swMTSAll;
    @BindView(R.id.sw_DS_Mark)
    ImageView swDSMark;
    @BindView(R.id.sw_DS_Click)
    RelativeLayout swDSClick;
    @BindView(R.id.ior_DSHY_Name)
    TextView iorDSHYName;
    @BindView(R.id.ior_DSHY)
    TextView iorDSHY;
    @BindView(R.id.ior_DSHY_Click)
    RelativeLayout iorDSHYClick;
    @BindView(R.id.ior_DSHN_Name)
    TextView iorDSHNName;
    @BindView(R.id.ior_DSHN)
    TextView iorDSHN;
    @BindView(R.id.ior_DSHN_Click)
    RelativeLayout iorDSHNClick;
    @BindView(R.id.ior_DSCY_Name)
    TextView iorDSCYName;
    @BindView(R.id.ior_DSCY)
    TextView iorDSCY;
    @BindView(R.id.ior_DSCY_Click)
    RelativeLayout iorDSCYClick;
    @BindView(R.id.ior_DSCN_Name)
    TextView iorDSCNName;
    @BindView(R.id.ior_DSCN)
    TextView iorDSCN;
    @BindView(R.id.ior_DSCN_Click)
    RelativeLayout iorDSCNClick;
    @BindView(R.id.ior_DSSY_Name)
    TextView iorDSSYName;
    @BindView(R.id.ior_DSSY)
    TextView iorDSSY;
    @BindView(R.id.ior_DSSY_Click)
    RelativeLayout iorDSSYClick;
    @BindView(R.id.ior_DSSN_Name)
    TextView iorDSSNName;
    @BindView(R.id.ior_DSSN)
    TextView iorDSSN;
    @BindView(R.id.ior_DSSN_Click)
    RelativeLayout iorDSSNClick;
    @BindView(R.id.sw_DS_show)
    LinearLayout swDSShow;
    @BindView(R.id.sw_DS_All)
    LinearLayout swDSAll;
    @BindView(R.id.sw_DUA_Mark)
    ImageView swDUAMark;
    @BindView(R.id.sw_DUA_Click)
    RelativeLayout swDUAClick;
    @BindView(R.id.sw_DUA_show)
    RecyclerView swDUAShow;
    @BindView(R.id.sw_DUA_All)
    LinearLayout swDUAAll;
    @BindView(R.id.sw_W3_Mark)
    ImageView swW3Mark;
    @BindView(R.id.sw_W3_click)
    RelativeLayout swW3Click;
    @BindView(R.id.ior_W3H_Name)
    TextView iorW3HName;
    @BindView(R.id.ior_W3H_ratio)
    TextView iorW3HRatio;
    @BindView(R.id.ior_W3H)
    TextView iorW3H;
    @BindView(R.id.ior_W3H_click)
    RelativeLayout iorW3HClick;
    @BindView(R.id.ior_W3C_Name)
    TextView iorW3CName;
    @BindView(R.id.ior_W3C_ratio)
    TextView iorW3CRatio;
    @BindView(R.id.ior_W3C)
    TextView iorW3C;
    @BindView(R.id.ior_W3C_click)
    RelativeLayout iorW3CClick;
    @BindView(R.id.ior_W3N_ratio)
    TextView iorW3NRatio;
    @BindView(R.id.ior_W3N)
    TextView iorW3N;
    @BindView(R.id.ior_W3N_click)
    RelativeLayout iorW3NClick;
    @BindView(R.id.sw_W3_show)
    LinearLayout swW3Show;
    @BindView(R.id.sw_W3_All)
    LinearLayout swW3All;
    @BindView(R.id.sw_PD_Mark)
    ImageView swPDMark;
    @BindView(R.id.sw_PD_show)
    LinearLayout swPDShow;
    @BindView(R.id.sw_PD_Click)
    RelativeLayout swPDClick;
    @BindView(R.id.sw_PD_MD_TG_show)
    RecyclerView swPDMD2TGShow;
    @BindView(R.id.sw_PD_HE_show)
    RecyclerView swPDHEShow;
    @BindView(R.id.sw_PD_TG_MD_show)
    RecyclerView swPDTG2MDShow;
    @BindView(R.id.ior_OVH_Click)
    RelativeLayout iorOVHClick;
    @BindView(R.id.ior_OVH)
    TextView iorOVH;


    @BindView(R.id.sw_PD_All)
    LinearLayout swPDAll;
    @BindView(R.id.sw_HPD_Mark)
    ImageView swHPDMark;
    @BindView(R.id.sw_HPD_Click)
    RelativeLayout swHPDClick;
    @BindView(R.id.sw_HPD_MD_TG_show)
    RecyclerView swHPDMD2TGShow;
    @BindView(R.id.sw_HPD_HE_show)
    RecyclerView swHPDHEShow;
    @BindView(R.id.sw_HPD_TG_MD_show)
    RecyclerView swHPDTG2MDShow;
    @BindView(R.id.ior_HOVH)
    TextView iorHOVH;
    @BindView(R.id.ior_HOVH_Click)
    RelativeLayout iorHOVHClick;
    @BindView(R.id.sw_HPD_show)
    LinearLayout swHPDShow;
    @BindView(R.id.sw_HPD_All)
    LinearLayout swHPDAll;
    @BindView(R.id.swHRMark)
    ImageView swHRMark;
    @BindView(R.id.sw_HR_Click)
    RelativeLayout swHRClick;
    @BindView(R.id.ior_HRH_Name)
    TextView iorHRHName;
    @BindView(R.id.ior_HRH_ratio)
    TextView iorHRHRatio;
    @BindView(R.id.ior_HRH)
    TextView iorHRH;
    @BindView(R.id.ior_HRH_Click)
    RelativeLayout iorHRHClick;
    @BindView(R.id.ior_HRC_Name)
    TextView iorHRCName;
    @BindView(R.id.ior_HRC_ratio)
    TextView iorHRCRatio;
    @BindView(R.id.ior_HRC)
    TextView iorHRC;
    @BindView(R.id.ior_HRC_Click)
    RelativeLayout iorHRCClick;
    @BindView(R.id.sw_HR_show)
    LinearLayout swHRShow;
    @BindView(R.id.sw_HR)
    LinearLayout swHR;
    @BindView(R.id.sw_OU_Mark)
    ImageView swOUMark;
    @BindView(R.id.sw_OU_Click)
    RelativeLayout swOUClick;
    @BindView(R.id.sw_OU_Name)
    TextView swOUName;
    @BindView(R.id.ratio_o)
    TextView ratioO;
    @BindView(R.id.ior_OUC)
    TextView iorOUC;
    @BindView(R.id.ior_OUC_Click)
    RelativeLayout iorOUCClick;
    @BindView(R.id.ratio_u)
    TextView ratioU;
    @BindView(R.id.ior_OUH)
    TextView iorOUH;
    @BindView(R.id.ior_OUH_Click)
    RelativeLayout iorOUHClick;
    @BindView(R.id.sw_OU_show)
    LinearLayout swOUShow;
    @BindView(R.id.sw_OU_All)
    LinearLayout swOUAll;
    @BindView(R.id.sw_HOU_Mark)
    ImageView swHOUMark;
    @BindView(R.id.sw_HOU_Click)
    RelativeLayout swHOUClick;
    @BindView(R.id.ratio_ho)
    TextView ratioHo;
    @BindView(R.id.ior_HOUC)
    TextView iorHOUC;
    @BindView(R.id.ior_HOUC_Click)
    RelativeLayout iorHOUCClick;
    @BindView(R.id.ratio_hu)
    TextView ratioHu;
    @BindView(R.id.ior_HOUH)
    TextView iorHOUH;
    @BindView(R.id.ior_HOUH_Click)
    RelativeLayout iorHOUHClick;
    @BindView(R.id.sw_HOU_show)
    LinearLayout swHOUShow;
    @BindView(R.id.sw_HOU_All)
    LinearLayout swHOUAll;
    @BindView(R.id.sw_OUH_name)
    TextView swOUHName;
    @BindView(R.id.sw_OUH_Mark)
    ImageView swOUHMark;
    @BindView(R.id.sw_OUH_Click)
    RelativeLayout swOUHClick;
    @BindView(R.id.ratio_ouho)
    TextView ratioOuho;
    @BindView(R.id.ior_OUHO)
    TextView iorOUHO;
    @BindView(R.id.ior_OUHO_Click)
    RelativeLayout iorOUHOClick;
    @BindView(R.id.ratio_ouhu)
    TextView ratioOuhu;
    @BindView(R.id.ior_OUHU)
    TextView iorOUHU;
    @BindView(R.id.ior_OUHU_Click)
    RelativeLayout iorOUHUClick;
    @BindView(R.id.sw_OUH_show)
    LinearLayout swOUHShow;
    @BindView(R.id.sw_OUH_All)
    LinearLayout swOUHAll;
    @BindView(R.id.sw_OUC_name)
    TextView swOUCName;
    @BindView(R.id.sw_OUC_Mark)
    ImageView swOUCMark;
    @BindView(R.id.sw_OUC_Click)
    RelativeLayout swOUCClick;
    @BindView(R.id.ratio_ouco)
    TextView ratioOuco;
    @BindView(R.id.ior_OUCO)
    TextView iorOUCO;
    @BindView(R.id.ior_OUCO_Click)
    RelativeLayout iorOUCOClick;
    @BindView(R.id.ratio_oucu)
    TextView ratioOucu;
    @BindView(R.id.ior_OUCU)
    TextView iorOUCU;
    @BindView(R.id.ior_OUCU_Click)
    RelativeLayout iorOUCUClick;
    @BindView(R.id.sw_OUC_show)
    LinearLayout swOUCShow;
    @BindView(R.id.sw_OUC_All)
    LinearLayout swOUCAll;
    @BindView(R.id.sw_HOUH_name)
    TextView swHOUHName;
    @BindView(R.id.sw_HOUH_Mark)
    ImageView swHOUHMark;
    @BindView(R.id.sw_HOUH_Click)
    RelativeLayout swHOUHClick;
    @BindView(R.id.ratio_houho)
    TextView ratioHouho;
    @BindView(R.id.ior_HOUHO)
    TextView iorHOUHO;
    @BindView(R.id.ior_HOUHO_Click)
    RelativeLayout iorHOUHOClick;
    @BindView(R.id.ratio_houhu)
    TextView ratioHouhu;
    @BindView(R.id.ior_HOUHU)
    TextView iorHOUHU;
    @BindView(R.id.ior_HOUHU_Click)
    RelativeLayout iorHOUHUClick;
    @BindView(R.id.sw_HOUH_show)
    LinearLayout swHOUHShow;
    @BindView(R.id.sw_HOUH_All)
    LinearLayout swHOUHAll;
    @BindView(R.id.sw_HOUC_name)
    TextView swHOUCName;
    @BindView(R.id.sw_HOUC_Mark)
    ImageView swHOUCMark;
    @BindView(R.id.sw_HOUC_Click)
    RelativeLayout swHOUCClick;
    @BindView(R.id.ratio_houco)
    TextView ratioHouco;
    @BindView(R.id.ior_HOUCO)
    TextView iorHOUCO;
    @BindView(R.id.ior_HOUCO_Click)
    RelativeLayout iorHOUCOClick;
    @BindView(R.id.ratio_houcu)
    TextView ratioHoucu;
    @BindView(R.id.ior_HOUCU)
    TextView iorHOUCU;
    @BindView(R.id.ior_HOUCU_Click)
    RelativeLayout iorHOUCUClick;
    @BindView(R.id.sw_HOUC_show)
    LinearLayout swHOUCShow;
    @BindView(R.id.sw_HOUC_All)
    LinearLayout swHOUCAll;
    @BindView(R.id.sw_RMU_Mark)
    ImageView swRMUMark;
    @BindView(R.id.sw_RMU_Click)
    RelativeLayout swRMUClick;
    @BindView(R.id.sw_RMU_show)
    RecyclerView swRMUShow;
    @BindView(R.id.sw_RMU_All)
    LinearLayout swRMUAll;
    @BindView(R.id.sw_RUT_Mark)
    ImageView swRUTMark;
    @BindView(R.id.sw_RUT_Click)
    RelativeLayout swRUTClick;
    @BindView(R.id.sw_RUT_show)
    RecyclerView swRUTShow;
    @BindView(R.id.sw_RUT_All)
    LinearLayout swRUTAll;
    @BindView(R.id.sw_RUE_Mark)
    ImageView swRUEMark;
    @BindView(R.id.sw_RUE_Click)
    RelativeLayout swRUEClick;
    @BindView(R.id.sw_RUE_show)
    RecyclerView swRUEShow;
    @BindView(R.id.sw_RUE_All)
    LinearLayout swRUEAll;
    @BindView(R.id.sw_BK_PD_H_Name)
    TextView swBKPDHName;
    @BindView(R.id.sw_BK_PD_H_Mark)
    ImageView swBKPDHMark;
    @BindView(R.id.sw_BK_PD_H_Click)
    RelativeLayout swBKPDHClick;
    @BindView(R.id.sw_BK_PD_H)
    RecyclerView swBKPDH;
    @BindView(R.id.sw_BK_PD_H_show)
    LinearLayout swBKPDHShow;
    @BindView(R.id.sw_BK_PD_H_All)
    LinearLayout swBKPDHAll;
    @BindView(R.id.sw_BK_PD_C_Name)
    TextView swBKPDCName;
    @BindView(R.id.sw_BK_PD_C_Mark)
    ImageView swBKPDCMark;
    @BindView(R.id.sw_BK_PD_C_Click)
    RelativeLayout swBKPDCClick;
    @BindView(R.id.sw_BK_PD_C)
    RecyclerView swBKPDC;
    @BindView(R.id.sw_BK_PD_C_show)
    RelativeLayout swBKPDCShow;
    @BindView(R.id.sw_BK_PD_C_All)
    LinearLayout swBKPDCAll;
    @BindView(R.id.ior_RH_H)
    LinearLayout iorRHH;
    @BindView(R.id.ior_RC_H)
    LinearLayout iorRCH;
    @BindView(R.id.ior_HRH_H)
    LinearLayout iorHRHH;
    @BindView(R.id.ior_HRC_H)
    LinearLayout iorHRCH;
    @BindView(R.id.ior_OUC_H)
    LinearLayout iorOUCH;
    @BindView(R.id.ior_OUH_H)
    LinearLayout iorOUHH;
    @BindView(R.id.ior_HOUC_H)
    LinearLayout iorHOUCH;
    @BindView(R.id.ior_HOUH_H)
    LinearLayout iorHOUHH;
    @BindView(R.id.ior_MH_H)
    LinearLayout iorMHH;
    @BindView(R.id.ior_MC_H)
    LinearLayout iorMCH;
    @BindView(R.id.ior_MN_H)
    LinearLayout iorMNH;
    @BindView(R.id.ior_HMH_H)
    LinearLayout iorHMHH;
    @BindView(R.id.ior_HMC_H)
    LinearLayout iorHMCH;
    @BindView(R.id.ior_HMN_H)
    LinearLayout iorHMNH;
    @BindView(R.id.ior_OVH_H)
    LinearLayout iorOVHH;
    @BindView(R.id.ior_HOVH_H)
    LinearLayout iorHOVHH;
    @BindView(R.id.ior_T01_H)
    LinearLayout iorT01H;
    @BindView(R.id.ior_T23_H)
    LinearLayout iorT23H;
    @BindView(R.id.ior_T46_H)
    LinearLayout iorT46H;
    @BindView(R.id.ior_OVER_H)
    LinearLayout iorOVERH;
    @BindView(R.id.ior_HT0_H)
    LinearLayout iorHT0H;
    @BindView(R.id.ior_HT1_H)
    LinearLayout iorHT1H;
    @BindView(R.id.ior_HT2_H)
    LinearLayout iorHT2H;
    @BindView(R.id.ior_HTOV_H)
    LinearLayout iorHTOVH;
    @BindView(R.id.ior_TSY_H)
    LinearLayout iorTSYH;
    @BindView(R.id.ior_TSN_H)
    LinearLayout iorTSNH;
    @BindView(R.id.ior_HTSY_H)
    LinearLayout iorHTSYH;
    @BindView(R.id.ior_HTSN_H)
    LinearLayout iorHTSNH;
    @BindView(R.id.ior_OUHO_H)
    LinearLayout iorOUHOH;
    @BindView(R.id.ior_OUHU_H)
    LinearLayout iorOUHUH;
    @BindView(R.id.ior_OUCO_H)
    LinearLayout iorOUCOH;
    @BindView(R.id.ior_OUCU_H)
    LinearLayout iorOUCUH;
    @BindView(R.id.ior_HOUHO_H)
    LinearLayout iorHOUHOH;
    @BindView(R.id.ior_HOUHU_H)
    LinearLayout iorHOUHUH;
    @BindView(R.id.ior_HOUCO_H)
    LinearLayout iorHOUCOH;
    @BindView(R.id.ior_HOUCU_H)
    LinearLayout iorHOUCUH;
    @BindView(R.id.ior_EOO_H)
    LinearLayout iorEOOH;
    @BindView(R.id.ior_EOE_H)
    LinearLayout iorEOEH;
    @BindView(R.id.ior_HEOE_H)
    LinearLayout iorHEOEH;
    @BindView(R.id.ior_HEOO_H)
    LinearLayout iorHEOOH;
    @BindView(R.id.ior_FHH_H)
    LinearLayout iorFHHH;
    @BindView(R.id.ior_FHN_H)
    LinearLayout iorFHNH;
    @BindView(R.id.ior_FHC_H)
    LinearLayout iorFHCH;
    @BindView(R.id.ior_FNH_H)
    LinearLayout iorFNHH;
    @BindView(R.id.ior_FNN_H)
    LinearLayout iorFNNH;
    @BindView(R.id.ior_FNC_H)
    LinearLayout iorFNCH;
    @BindView(R.id.ior_FCH_H)
    LinearLayout iorFCHH;
    @BindView(R.id.ior_FCN_H)
    LinearLayout iorFCNH;
    @BindView(R.id.ior_FCC_H)
    LinearLayout iorFCCH;
    @BindView(R.id.ior_WMH1_H)
    LinearLayout iorWMH1H;
    @BindView(R.id.ior_WMH2_H)
    LinearLayout iorWMH2H;
    @BindView(R.id.ior_WMH3_H)
    LinearLayout iorWMH3H;
    @BindView(R.id.ior_WMHOV_H)
    LinearLayout iorWMHOVH;
    @BindView(R.id.ior_WMC1_H)
    LinearLayout iorWMC1H;
    @BindView(R.id.ior_WMC2_H)
    LinearLayout iorWMC2H;
    @BindView(R.id.ior_WMC3_H)
    LinearLayout iorWMC3H;
    @BindView(R.id.ior_WMCOV_H)
    LinearLayout iorWMCOVH;
    @BindView(R.id.ior_WM0_H)
    LinearLayout iorWM0H;
    @BindView(R.id.ior_WMN_H)
    LinearLayout iorWMNH;
    @BindView(R.id.ior_DCHN_H)
    LinearLayout iorDCHNH;
    @BindView(R.id.ior_DCCN_H)
    LinearLayout iorDCCNH;
    @BindView(R.id.ior_DCHC_H)
    LinearLayout iorDCHCH;
    @BindView(R.id.ior_CSH_H)
    LinearLayout iorCSHH;
    @BindView(R.id.ior_CSC_H)
    LinearLayout iorCSCH;
    @BindView(R.id.ior_WNH_H)
    LinearLayout iorWNHH;
    @BindView(R.id.ior_WNC_H)
    LinearLayout iorWNCH;
    @BindView(R.id.ior_MTSHY_H)
    LinearLayout iorMTSHYH;
    @BindView(R.id.ior_MTSHN_H)
    LinearLayout iorMTSHNH;
    @BindView(R.id.ior_MTSNY_H)
    LinearLayout iorMTSNYH;
    @BindView(R.id.ior_MTSNN_H)
    LinearLayout iorMTSNNH;
    @BindView(R.id.ior_MTSCY_H)
    LinearLayout iorMTSCYH;
    @BindView(R.id.ior_MTSCN_H)
    LinearLayout iorMTSCNH;
    @BindView(R.id.ior_HGH_H)
    LinearLayout iorHGHH;
    @BindView(R.id.ior_HGC_H)
    LinearLayout iorHGCH;
    @BindView(R.id.ior_MGH_H)
    LinearLayout iorMGHH;
    @BindView(R.id.ior_MGC_H)
    LinearLayout iorMGCH;
    @BindView(R.id.ior_MGN_H)
    LinearLayout iorMGNH;
    @BindView(R.id.ior_SBH_H)
    LinearLayout iorSBHH;
    @BindView(R.id.ior_SBC_H)
    LinearLayout iorSBCH;
    @BindView(R.id.ior_DSHY_H)
    LinearLayout iorDSHYH;
    @BindView(R.id.ior_DSHN_H)
    LinearLayout iorDSHNH;
    @BindView(R.id.ior_DSCY_H)
    LinearLayout iorDSCYH;
    @BindView(R.id.ior_DSCN_H)
    LinearLayout iorDSCNH;
    @BindView(R.id.ior_DSSY_H)
    LinearLayout iorDSSYH;
    @BindView(R.id.ior_DSSN_H)
    LinearLayout iorDSSNH;
    @BindView(R.id.ior_W3H_H)
    LinearLayout iorW3HH;
    @BindView(R.id.ior_W3C_H)
    LinearLayout iorW3CH;
    @BindView(R.id.ior_W3N_H)
    LinearLayout iorW3NH;
    @BindView(R.id.ior_WEH_H)
    LinearLayout iorWEHH;
    @BindView(R.id.ior_WEC_H)
    LinearLayout iorWECH;
    @BindView(R.id.ior_WBH_H)
    LinearLayout iorWBHH;
    @BindView(R.id.ior_WBC_H)
    LinearLayout iorWBCH;
    private PrepareBetApiContract.Presenter presenter;

    private String mLeague, mTeamH, mTeamC, gid, gtype, showtype, userMoney, fromType,fromString;

    //准备下注的数据
    private String order_method, rtype, type, wtype;
    //可盈金额的计算
    private String win_radio_r_h = "";
    private String orderType = " FT_order";
    Animation animation ;
    //数据格式的转换
    private static List<String> MID = new ArrayList<>();
    private List<SportMethodResult.RqDanListBean> rq_dan_list = new ArrayList<>();
    private List<SportMethodResult.RqBanListBean> rq_ban_list = new ArrayList<>();
    private List<SportMethodResult.DxDanListBean> dx_dan_list = new ArrayList<>();
    private List<SportMethodResult.DxBanListBean> dx_ban_list = new ArrayList<>();
    private List<SportMethodResult.DsListBean> ds_list = new ArrayList<>();

    private ScheduledExecutorService executorService;
    private int sendAuthTime = HGConstant.ACTION_SEND_AUTH_CODE;
    //独赢 & 进球 大/小    开关             滚球足球独有
    boolean b_sw_RMU = true;
    //进球 大/小 双方球队进球    开关       滚球足球独有
    boolean b_sw_RUT = true;
    //进球 大/小 & 进球 单/双   开关       滚球足球独有
    boolean b_sw_RUE = true;
    //让球    开关
    boolean b_sw_R = true;
    //让球-上半场    开关
    boolean b_sw_HR = true;
    //大/小    开关
    boolean b_sw_OU = true;
    //大/小-上半场    开关
    boolean b_sw_HOU = true;
    //独赢    开关
    boolean b_sw_M = true;
    //独赢- 上半场   开关
    boolean b_sw_HM = true;
    //波胆   开关
    boolean b_sw_PD = true;
    //球队得分：主队 -最后一位数 篮球 独有   开关
    boolean b_sw_PD_H = true;
    //球队得分：客队 -最后一位数 篮球 独有   开关
    boolean b_sw_PD_C = true;
    //波胆   上半场  开关
    boolean b_sw_HPD = true;
    //赢得任一半场    开关
    boolean b_sw_WE = true;
    //赢得所有半场 开关
    boolean b_sw_WB = true;
    //总进球数    开关
    boolean b_sw_T = true;
    //总进球数  上半场 开关
    boolean b_sw_HT = true;
    //双方球队进球    开关
    boolean b_sw_TS = true;
    //双方球队进球  上半场 开关
    boolean b_sw_HTS = true;
    //球队进球数 主队 大/小    开关
    boolean b_sw_OUH = true;
    //球队进球数 客队 大/小  开关
    boolean b_sw_OUC = true;
    //球队进球数 主队 大/小  上半场  开关
    boolean b_sw_HOUH = true;
    //球队进球数 客队 大/小  上半场 开关
    boolean b_sw_HOUC = true;
    //单/双    开关
    boolean b_sw_EO = true;
    //单/双  上半场 开关
    boolean b_sw_HEO = true;
    //零失球    开关
    boolean b_sw_CS = true;
    //零失球获胜   开关
    boolean b_sw_WN = true;
    //最多进球的半场   开关
    boolean b_sw_HG = true;
    //最多进球的半场-独赢    开关
    boolean b_sw_MG = true;
    //双半场进球   开关
    boolean b_sw_SB = true;
    //半场/全场   开关
    boolean b_sw_F = true;
    //净胜球数   开关
    boolean b_sw_WM = true;
    //双重机会   开关
    boolean b_sw_DC = true;
    //独赢&双方球队进球   开关
    boolean b_sw_MTS = true;
    //双重机会&双方球队进球   开关
    boolean b_sw_DS = true;
    //双重机会&进球 大/小   开关
    boolean b_sw_DUA = true;
    //三项让球投注   开关
    boolean b_sw_W3 = true;

    public static PrepareBetFragment newInstance(String mLeague, String mTeamH, String mTeamC, String gid, String gtype, String showtype, String userMoney, String fromType, String fromString) {
        PrepareBetFragment fragment = new PrepareBetFragment();
        Bundle args = new Bundle();
        args.putString(ARG_PARAM1, mLeague);
        args.putString(ARG_PARAM2, mTeamH);
        args.putString(ARG_PARAM3, mTeamC);
        args.putString(ARG_PARAM4, gid);
        args.putString(ARG_PARAM5, gtype);
        args.putString(ARG_PARAM6, showtype);
        args.putString(ARG_PARAM7, userMoney);
        args.putString(ARG_PARAM8, fromType);
        args.putString(ARG_PARAM9, fromString);
        Injections.inject(null, fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
            mLeague = getArguments().getString(ARG_PARAM1);
            mTeamH = getArguments().getString(ARG_PARAM2);
            mTeamC = getArguments().getString(ARG_PARAM3);
            gid = getArguments().getString(ARG_PARAM4);
            gtype = getArguments().getString(ARG_PARAM5);
            showtype = getArguments().getString(ARG_PARAM6);
            userMoney = getArguments().getString(ARG_PARAM7);
            fromType = getArguments().getString(ARG_PARAM8);
            fromString = getArguments().getString(ARG_PARAM9);
            //sportsPlayMethodResult.getData().get(0).;
            GameLog.log("所有玩法时的数据展示是 mLeague：" + mLeague + " mTeamH：" + mTeamH + " mTeamC：" + mTeamC + " gid ：" + gid + " gtype：" + gtype + " showtype ：" + showtype + " userMoney ：" + userMoney + " fromType : " + fromType);
        }
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_prepare_bet;
    }

    public void onPostGameData() {
        if (null != executorService) {
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        if(fromType.equals("1")||fromType.equals("2")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_R;
        }else if(fromType.equals("3")||fromType.equals("4")){
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_T;
        }else{
            sendAuthTime = HGConstant.ACTION_SEND_LEAGUE_TIME_M;
        }
        if(!Check.isEmpty(fromString)){
            teamLiveTime.setText(fromString);
        }
        GameLog.log("fromString : "+fromString);
        onSendAuthCode();
        switch (fromType) {
            case "1":
                orderType = "FT_order_re";
                presenter.postGameAllBetsRFT("", gid, gtype, showtype);
                break;
            case "3":
            case "5":
                orderType = "FT_order";
                MBTeamNumber.setVisibility(View.GONE);
                TGTeamNumber.setVisibility(View.GONE);
                presenter.postGameAllBetsFT("", gid, gtype, showtype);
                break;
            case "2":
                teamVs.setText("|");
                rbkStateKK.setVisibility(View.VISIBLE);
                teamRFT.setBackground(getResources().getDrawable(R.drawable.bg_bet_bk));
                orderType = "BK_order_re";
                presenter.postGameAllBetsRBK("", gid, gtype, showtype);
                break;
            case "4":
            case "6":
                teamVs.setText("|");
                MBTeamNumber.setVisibility(View.GONE);
                TGTeamNumber.setVisibility(View.GONE);
                teamRFT.setBackground(getResources().getDrawable(R.drawable.bg_bet_bk));
                orderType = "BK_order";
                presenter.postGameAllBetsBK("", gid, gtype, showtype);
                break;

        }

    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        animation = AnimationUtils.loadAnimation(getContext(),R.anim.rotate_clockwise);
        onPostGameData();
        tvBetEventName.setText(mLeague);
        MBTeam.setText(mTeamH);
        TGTeam.setText(mTeamC);

        //假数据 上线去除
        /*setHPAAAAAAAA();
        setHPDDDDD();*/
        //setBKPDTest();
    }

    private void setBKPDTest() {
        ArrayList<SwPDMD2TG> myListSwPDh = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDc = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 5; ++i) {
            SwPDMD2TG swPDMD2TGH = new SwPDMD2TG();
            SwPDMD2TG swPDMD2TGC = new SwPDMD2TG();
            swPDMD2TGH.order_method = "FT_pd";
            swPDMD2TGH.wtype = "PD";
            swPDMD2TGC.order_method = "FT_pd";
            swPDMD2TGC.wtype = "PD";
            switch (i) {
                case 0:
                    swPDMD2TGH.rtype = "H1C0";
                    swPDMD2TGH.ior_H_up = "0或5";
                    swPDMD2TGH.ior_H_down = "1.02";

                    swPDMD2TGC.rtype = "H0C1";
                    swPDMD2TGC.ior_H_up = "0或5";
                    swPDMD2TGC.ior_H_down = "1.02";
                    break;
                case 1:
                    swPDMD2TGH.rtype = "H2C0";
                    swPDMD2TGH.ior_H_up = "1或6";
                    swPDMD2TGH.ior_H_down = "1.02";

                    swPDMD2TGC.rtype = "H0C2";
                    swPDMD2TGC.ior_H_up = "1或6";
                    swPDMD2TGC.ior_H_down = "1.02";
                    break;
                case 2:
                    swPDMD2TGH.rtype = "H2C1";
                    swPDMD2TGH.ior_H_up = "2或7";
                    swPDMD2TGH.ior_H_down = "";

                    swPDMD2TGC.rtype = "H1C2";
                    swPDMD2TGC.ior_H_up = "2或7";
                    swPDMD2TGC.ior_H_down = "1.02";
                    break;
                case 3:
                    swPDMD2TGH.rtype = "H3C0";
                    swPDMD2TGH.ior_H_up = "3或8";
                    swPDMD2TGH.ior_H_down = "1.02";

                    swPDMD2TGC.rtype = "H0C3";
                    swPDMD2TGC.ior_H_up = "3或8";
                    swPDMD2TGC.ior_H_down = "1.02";
                    break;
                case 4:
                    swPDMD2TGH.rtype = "H3C1";
                    swPDMD2TGH.ior_H_up = "4或9";
                    swPDMD2TGH.ior_H_down = "";

                    swPDMD2TGH.rtype = "H1C3";
                    swPDMD2TGC.ior_H_up = "4或9";
                    swPDMD2TGC.ior_H_down = "";
                    break;
            }
            myListSwPDh.add(swPDMD2TGH);
            myListSwPDc.add(swPDMD2TGC);
        }

        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swBKPDH.setLayoutManager(mLayoutManager1);
        swBKPDH.setHasFixedSize(true);
        swBKPDH.setNestedScrollingEnabled(false);
        swBKPDH.setAdapter(new SwBKPDlistAdapter(getContext(), R.layout.item_bk_pd, myListSwPDh));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swBKPDC.setLayoutManager(mLayoutManager3);
        swBKPDC.setHasFixedSize(true);
        swBKPDC.setNestedScrollingEnabled(false);
        //swBKPDC.setAdapter(new SwBKPDlistAdapter(getContext(), R.layout.item_bk_pd, myListSwPDc));
    }

    private void setHPAAAAAAAA() {
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
        swDUAShow.setLayoutManager(gridLayoutManager);
        ArrayList<SwDua> myList = new ArrayList<SwDua>();

        for (int i = 0; i < 12; ++i) {
            SwDua sw_dua = new SwDua();
            sw_dua.ior_DUA_Name = "主队";
            switch (i) {
                case 0:
                case 1:
                case 2:
                    sw_dua.ior_DUA_da_Name = "大1.5";
                    sw_dua.ior_DUA_xiao_Name = "小1.5";
                    break;
                case 3:
                case 4:
                case 5:
                    sw_dua.ior_DUA_da_Name = "大2.5";
                    sw_dua.ior_DUA_xiao_Name = "小2.5";
                    break;
                case 6:
                case 7:
                case 8:
                    sw_dua.ior_DUA_da_Name = "大3.5";
                    sw_dua.ior_DUA_xiao_Name = "小3.5";
                    break;
                case 9:
                case 10:
                case 11:
                    sw_dua.ior_DUA_da_Name = "大4.5";
                    sw_dua.ior_DUA_xiao_Name = "小4.5";
                    break;
            }
            sw_dua.ior_DUA_da = "1.22";
            if (i == 4 || i == 8) {
                sw_dua.ior_DUA_da = "";
            }
            sw_dua.ior_DUA_xiao = "5.4";
            myList.add(sw_dua);

        }
        swDUAShow.setHasFixedSize(true);
        swDUAShow.setNestedScrollingEnabled(false);
        //swDUAShow.addItemDecoration(new GridRvItemDecoration(getContext()));
        swDUAShow.setAdapter(new SwDualistAdapter(getContext(), R.layout.item_sw_dua, myList));


        ArrayList<SwPDMD2TG> myListSwPD = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 10; ++i) {
            SwPDMD2TG swPDMD2TG = new SwPDMD2TG();
            switch (i) {
                case 0:
                    swPDMD2TG.ior_H_up = "1 - 0";
                    swPDMD2TG.ior_H_down = "7.2";
                    break;
                case 1:
                    swPDMD2TG.ior_H_up = "2 - 0";
                    swPDMD2TG.ior_H_down = "13";
                    break;
                case 2:
                    swPDMD2TG.ior_H_up = "2 - 1";
                    swPDMD2TG.ior_H_down = "20";
                    break;
                case 3:
                    swPDMD2TG.ior_H_up = "3 - 0";
                    swPDMD2TG.ior_H_down = "33";
                    break;
                case 4:
                    swPDMD2TG.ior_H_up = "3 - 1";
                    swPDMD2TG.ior_H_down = "60";
                    break;
                case 5:
                    swPDMD2TG.ior_H_up = "3 - 2";
                    swPDMD2TG.ior_H_down = "71";
                    break;
                case 6:
                    swPDMD2TG.ior_H_up = "4 - 0";
                    swPDMD2TG.ior_H_down = "84";
                    break;
                case 7:
                    swPDMD2TG.ior_H_up = "4 - 1";
                    swPDMD2TG.ior_H_down = "100";
                    break;
                case 8:
                    swPDMD2TG.ior_H_up = "4 - 2";
                    swPDMD2TG.ior_H_down = "120";
                    break;
                case 9:
                    swPDMD2TG.ior_H_up = "4 - 3";
                    swPDMD2TG.ior_H_down = "131";
                    break;
            }
            myListSwPD.add(swPDMD2TG);
        }

        ArrayList<SwPDMD2TG> myListSwPDHE = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 5; ++i) {
            SwPDMD2TG swPDMD2TG = new SwPDMD2TG();
            switch (i) {
                case 0:
                    swPDMD2TG.ior_H_up = "1 - 0";
                    swPDMD2TG.ior_H_down = "17.2";
                    break;
                case 1:
                    swPDMD2TG.ior_H_up = "2 - 0";
                    swPDMD2TG.ior_H_down = "23";
                    break;
                case 2:
                    swPDMD2TG.ior_H_up = "2 - 1";
                    swPDMD2TG.ior_H_down = "28";
                    break;
                case 3:
                    swPDMD2TG.ior_H_up = "3 - 0";
                    swPDMD2TG.ior_H_down = "43";
                    break;
                case 4:
                    swPDMD2TG.ior_H_up = "3 - 1";
                    swPDMD2TG.ior_H_down = "90";
                    break;
            }
            myListSwPDHE.add(swPDMD2TG);
        }

        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swPDMD2TGShow.setLayoutManager(mLayoutManager1);
        swPDMD2TGShow.setHasFixedSize(true);
        swPDMD2TGShow.setNestedScrollingEnabled(false);
        swPDMD2TGShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPD));

        LinearLayoutManager mLayoutManager2 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swPDHEShow.setLayoutManager(mLayoutManager2);
        swPDHEShow.setHasFixedSize(true);
        swPDHEShow.setNestedScrollingEnabled(false);
        swPDHEShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDHE));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swPDTG2MDShow.setLayoutManager(mLayoutManager3);
        swPDTG2MDShow.setHasFixedSize(true);
        swPDTG2MDShow.setNestedScrollingEnabled(false);
        swPDTG2MDShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPD));

    }

    private void setHPDDDDD() {
        ArrayList<SwPDMD2TG> myListSwPD = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 10; ++i) {
            SwPDMD2TG swPDMD2TG = new SwPDMD2TG();
            switch (i) {
                case 0:
                    swPDMD2TG.ior_H_up = "1 - 0";
                    swPDMD2TG.ior_H_down = "7.2";
                    break;
                case 1:
                    swPDMD2TG.ior_H_up = "2 - 0";
                    swPDMD2TG.ior_H_down = "13";
                    break;
                case 2:
                    swPDMD2TG.ior_H_up = "2 - 1";
                    swPDMD2TG.ior_H_down = "20";
                    break;
                case 3:
                    swPDMD2TG.ior_H_up = "3 - 0";
                    swPDMD2TG.ior_H_down = "33";
                    break;
                case 4:
                    swPDMD2TG.ior_H_up = "3 - 1";
                    swPDMD2TG.ior_H_down = "60";
                    break;
                case 5:
                    swPDMD2TG.ior_H_up = "3 - 2";
                    swPDMD2TG.ior_H_down = "71";
                    break;
                case 6:
                    swPDMD2TG.ior_H_up = "4 - 0";
                    swPDMD2TG.ior_H_down = "84";
                    break;
                case 7:
                    swPDMD2TG.ior_H_up = "4 - 1";
                    swPDMD2TG.ior_H_down = "100";
                    break;
                case 8:
                    swPDMD2TG.ior_H_up = "4 - 2";
                    swPDMD2TG.ior_H_down = "120";
                    break;
                case 9:
                    swPDMD2TG.ior_H_up = "4 - 3";
                    swPDMD2TG.ior_H_down = "131";
                    break;
            }
            myListSwPD.add(swPDMD2TG);
        }

        ArrayList<SwPDMD2TG> myListSwPDHE = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 5; ++i) {
            SwPDMD2TG swPDMD2TG = new SwPDMD2TG();
            switch (i) {
                case 0:
                    swPDMD2TG.ior_H_up = "1 - 0";
                    swPDMD2TG.ior_H_down = "17.2";
                    break;
                case 1:
                    swPDMD2TG.ior_H_up = "2 - 0";
                    swPDMD2TG.ior_H_down = "23";
                    break;
                case 2:
                    swPDMD2TG.ior_H_up = "2 - 1";
                    swPDMD2TG.ior_H_down = "28";
                    break;
                case 3:
                    swPDMD2TG.ior_H_up = "3 - 0";
                    swPDMD2TG.ior_H_down = "43";
                    break;
                case 4:
                    swPDMD2TG.ior_H_up = "3 - 1";
                    swPDMD2TG.ior_H_down = "90";
                    break;
            }
            myListSwPDHE.add(swPDMD2TG);
        }

        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swHPDMD2TGShow.setLayoutManager(mLayoutManager1);
        swHPDMD2TGShow.setHasFixedSize(true);
        swHPDMD2TGShow.setNestedScrollingEnabled(false);
        swHPDMD2TGShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPD));

        LinearLayoutManager mLayoutManager2 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swHPDHEShow.setLayoutManager(mLayoutManager2);
        swHPDHEShow.setHasFixedSize(true);
        swHPDHEShow.setNestedScrollingEnabled(false);
        swHPDHEShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDHE));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swHPDTG2MDShow.setLayoutManager(mLayoutManager3);
        swHPDTG2MDShow.setHasFixedSize(true);
        swHPDTG2MDShow.setNestedScrollingEnabled(false);
        swHPDTG2MDShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPD));

    }

    private void onCheckThirdMobilePaySubmit() {
        String thirdBankMoney = "";//etBetGold.getText().toString().trim();

        if (Check.isEmpty(thirdBankMoney)) {
            showMessage("购买金额必须是整数！");
            return;
        }
        if (thirdBankMoney.compareTo("20") >= 1) {
            showMessage("购买金额必须大于20元！");
            return;
        }
    }

    @Override
    public void setPresenter(PrepareBetApiContract.Presenter presenter) {
        this.presenter = presenter;
    }

    //计数器，用于倒计时使用
    private void onSendAuthCode() {
        GameLog.log("-----开始-----");
        executorService = Executors.newScheduledThreadPool(1);
        executorService.scheduleAtFixedRate(new onWaitingThread(), 0, 1000, TimeUnit.MILLISECONDS);
    }

    @Override
    public void postGameAllBetsFTFailResult(String message) {
        showMessage(message);
        ivBetEventRefresh.clearAnimation();
        swALL.setVisibility(View.GONE);
        imprepareBetTop.setVisibility(View.GONE);
        tvPrepareBetNoData.setVisibility(View.VISIBLE);
        //swALL.setVisibility(View.GONE);
    }

    @Override
    public void postGameAllBetsBKResult(GameAllPlayBKResult gameAllPlayBKResult) {
        ivBetEventRefresh.clearAnimation();
        tvPrepareBetNoData.setVisibility(View.GONE);
        imprepareBetTop.setVisibility(View.VISIBLE);
        swALL.setVisibility(View.VISIBLE);
        GameLog.log("所有篮球玩法的接口：" + gameAllPlayBKResult.toString());
        //让球的数据展示
        sw_BKR(gameAllPlayBKResult);
        //大/小的数据展示
        sw_BKOU(gameAllPlayBKResult);
        //单/双
        sw_BKEO(gameAllPlayBKResult);
        //独赢的数据展示
        sw_BKM(gameAllPlayBKResult);
        //球队进球数 主队 大/小的数据展示
        sw_BKOUH(gameAllPlayBKResult);

        //球队进球数 客队大/小的数据展示
        sw_BKOUC(gameAllPlayBKResult);

        //波胆 球队得分：主队/客队 -最后一位
        sw_BKPD(gameAllPlayBKResult);

    }

    @Override
    public void postGameAllBetsRBKResult(GameAllPlayRBKResult gameAllPlayRBKResult) {
        ivBetEventRefresh.clearAnimation();
        tvPrepareBetNoData.setVisibility(View.GONE);
        swALL.setVisibility(View.VISIBLE);
        imprepareBetTop.setVisibility(View.VISIBLE);
        MBTeamNumber.setText(gameAllPlayRBKResult.getSc_FT_H());
        TGTeamNumber.setText(gameAllPlayRBKResult.getSc_FT_A());
        //teamLiveTime.setText(gameAllPlayRBKResult.getRe_time());
        StringBuilder stringBuilder = new StringBuilder();
        String sc_now = gameAllPlayRBKResult.getSe_now();
        if(Check.isEmpty(sc_now)){
            sc_now = "Q5";
        }
        switch (sc_now){
            case "Q1":
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_H())){
                    gameAllPlayRBKResult.setSc_Q1_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_A())){
                    gameAllPlayRBKResult.setSc_Q1_A("0");
                }
                stringBuilder.append("Q1(").append(gameAllPlayRBKResult.getSc_Q1_H()).append("-").append(gameAllPlayRBKResult.getSc_Q1_A()).append(")");
                break;
            case "Q2":
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_H())){
                    gameAllPlayRBKResult.setSc_Q1_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_A())){
                    gameAllPlayRBKResult.setSc_Q1_A("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q2_H())){
                    gameAllPlayRBKResult.setSc_Q2_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q2_A())){
                    gameAllPlayRBKResult.setSc_Q2_A("0");
                }
                stringBuilder.append("Q1(").append(gameAllPlayRBKResult.getSc_Q1_H()).append("-").append(gameAllPlayRBKResult.getSc_Q1_A()).append(")");
                stringBuilder.append("Q2(").append(gameAllPlayRBKResult.getSc_Q2_H()).append("-").append(gameAllPlayRBKResult.getSc_Q2_A()).append(")");
                break;
            case "Q3":
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_H())){
                    gameAllPlayRBKResult.setSc_Q1_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_A())){
                    gameAllPlayRBKResult.setSc_Q1_A("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q2_H())){
                    gameAllPlayRBKResult.setSc_Q2_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q2_A())){
                    gameAllPlayRBKResult.setSc_Q2_A("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q3_H())){
                    gameAllPlayRBKResult.setSc_Q3_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q3_A())){
                    gameAllPlayRBKResult.setSc_Q3_A("0");
                }
                stringBuilder.append("Q1(").append(gameAllPlayRBKResult.getSc_Q1_H()).append("-").append(gameAllPlayRBKResult.getSc_Q1_A()).append(")");
                stringBuilder.append("Q2(").append(gameAllPlayRBKResult.getSc_Q2_H()).append("-").append(gameAllPlayRBKResult.getSc_Q2_A()).append(")");
                stringBuilder.append("Q3(").append(gameAllPlayRBKResult.getSc_Q3_H()).append("-").append(gameAllPlayRBKResult.getSc_Q3_A()).append(")");
                break;
            case "Q4":
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_H())){
                    gameAllPlayRBKResult.setSc_Q1_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q1_A())){
                    gameAllPlayRBKResult.setSc_Q1_A("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q2_H())){
                    gameAllPlayRBKResult.setSc_Q2_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q2_A())){
                    gameAllPlayRBKResult.setSc_Q2_A("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q3_H())){
                    gameAllPlayRBKResult.setSc_Q3_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q3_A())){
                    gameAllPlayRBKResult.setSc_Q3_A("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q4_H())){
                    gameAllPlayRBKResult.setSc_Q4_H("0");
                }
                if(Check.isEmpty(gameAllPlayRBKResult.getSc_Q4_A())){
                    gameAllPlayRBKResult.setSc_Q4_A("0");
                }
                stringBuilder.append("Q1(").append(gameAllPlayRBKResult.getSc_Q1_H()).append("-").append(gameAllPlayRBKResult.getSc_Q1_A()).append(")");
                stringBuilder.append("Q2(").append(gameAllPlayRBKResult.getSc_Q2_H()).append("-").append(gameAllPlayRBKResult.getSc_Q2_A()).append(")");
                stringBuilder.append("Q3(").append(gameAllPlayRBKResult.getSc_Q3_H()).append("-").append(gameAllPlayRBKResult.getSc_Q3_A()).append(")");
                stringBuilder.append("Q4(").append(gameAllPlayRBKResult.getSc_Q4_H()).append("-").append(gameAllPlayRBKResult.getSc_Q4_A()).append(")");
                break;
            default:
        }

        if(!Check.isEmpty(gameAllPlayRBKResult.getSc_H1_H())||!Check.isEmpty(gameAllPlayRBKResult.getSc_H1_A())){
            stringBuilder.append("上半场(").append(gameAllPlayRBKResult.getSc_H1_H()).append("-").append(gameAllPlayRBKResult.getSc_H1_A()).append(")");
        }
        if(!Check.isEmpty(gameAllPlayRBKResult.getSc_H2_H())||!Check.isEmpty(gameAllPlayRBKResult.getSc_H2_A())){
            stringBuilder.append("下半场(").append(gameAllPlayRBKResult.getSc_H2_H()).append("-").append(gameAllPlayRBKResult.getSc_H2_A()).append(")");
        }
        if(!Check.isEmpty(gameAllPlayRBKResult.getSc_OT_H())||!Check.isEmpty(gameAllPlayRBKResult.getSc_OT_A())){
            stringBuilder.append("加时(").append(gameAllPlayRBKResult.getSc_OT_H()).append("-").append(gameAllPlayRBKResult.getSc_OT_A()).append(")");
        }
        if(Check.isEmpty(stringBuilder.toString())){
            rbkStateKK.setVisibility(View.GONE);
        }else{
            rbkStateKK.setVisibility(View.VISIBLE);
            rbkStateKK.setText(stringBuilder.toString());
        }

        if(gameAllPlayRBKResult.getSe_now().equals("Q4")){
            tvPrepareBetNoData.setVisibility(View.VISIBLE);
            swALL.setVisibility(View.GONE);
            imprepareBetTop.setVisibility(View.GONE);
            return;
        }

        GameLog.log("滚球篮球玩法的接口：" + gameAllPlayRBKResult.toString());
        //让球的数据展示
        sw_BKRE(gameAllPlayRBKResult);
        //大/小的数据展示
        sw_RBKOU(gameAllPlayRBKResult);
        //单/双
        sw_RBKEO(gameAllPlayRBKResult);
        //独赢的数据展示
        sw_BKRM(gameAllPlayRBKResult);
        //球队进球数 主队 大/小的数据展示
        sw_BKROUH(gameAllPlayRBKResult);

        //球队进球数 客队大/小的数据展示
        sw_BKROUC(gameAllPlayRBKResult);

        //球队得分：主队/客队 -最后一位
        sw_BKRPD(gameAllPlayRBKResult);
    }

    @Override
    public void postGameAllBetsFTResult(GameAllPlayFTResult gameAllPlayFTResult) {
        ivBetEventRefresh.clearAnimation();
        tvPrepareBetNoData.setVisibility(View.GONE);
        swALL.setVisibility(View.VISIBLE);
        imprepareBetTop.setVisibility(View.VISIBLE);
        GameLog.log("所有足球玩法的接口：" + gameAllPlayFTResult.toString());

        //让球的数据展示
        sw_R(gameAllPlayFTResult);

        //让球-上半场的数据展示
        sw_HR(gameAllPlayFTResult);

        //大/小的数据展示
        sw_OU(gameAllPlayFTResult);

        //大/小-上半场的数据展示
        sw_HOU(gameAllPlayFTResult);

        //球队进球数 主队 大/小的数据展示
        sw_OUH(gameAllPlayFTResult);

        //球队进球数 客队大/小的数据展示
        sw_OUC(gameAllPlayFTResult);

        //球队进球数 主队 大/小 上半场的数据展示
        sw_HOUH(gameAllPlayFTResult);

        //球队进球数 客队 大/小 上半场的数据展示
        sw_HOUC(gameAllPlayFTResult);

        //独赢的数据展示
        sw_M(gameAllPlayFTResult);

        //独赢-上半场的数据展示
        sw_HM(gameAllPlayFTResult);

        //赢得任一半场
        sw_WE(gameAllPlayFTResult);

        //赢得所有半场
        sw_WB(gameAllPlayFTResult);

        //总进球数
        sw_T(gameAllPlayFTResult);

        //总进球数  上半场
        sw_HT(gameAllPlayFTResult);

        //双方球队进球
        sw_TS(gameAllPlayFTResult);

        //双方球队进球  上半场
        sw_HTS(gameAllPlayFTResult);

        //单/双
        sw_EO(gameAllPlayFTResult);

        //单/双  上半场
        sw_HEO(gameAllPlayFTResult);


        //零失球
        sw_CS(gameAllPlayFTResult);

        //零失球获胜
        sw_WN(gameAllPlayFTResult);

        //最多进球的半场
        sw_HG(gameAllPlayFTResult);

        //最多进球的半场-独赢
        sw_MG(gameAllPlayFTResult);

        //双半场进球
        sw_SB(gameAllPlayFTResult);

        //半场/全场  开关
        sw_F(gameAllPlayFTResult);


        //净胜球数
        sw_WM(gameAllPlayFTResult);

        //双重机会
        sw_DC(gameAllPlayFTResult);

        //独赢&双方球队进球
        sw_MTS(gameAllPlayFTResult);


        //双重机会&双方球队进球
        sw_DS(gameAllPlayFTResult);


        //双重机会&进球 大/小
        sw_DUA(gameAllPlayFTResult);

        //三项让球投注
        sw_W3(gameAllPlayFTResult);

        //波胆
        sw_PD(gameAllPlayFTResult);

        //波胆上半场
        sw_HPD(gameAllPlayFTResult);

    }

    @Override
    public void postGameAllBetsRFTResult(GameAllPlayRFTResult gameAllPlayRFTResult) {
        ivBetEventRefresh.clearAnimation();
        tvPrepareBetNoData.setVisibility(View.GONE);
        swALL.setVisibility(View.VISIBLE);
        imprepareBetTop.setVisibility(View.VISIBLE);
        if(Check.isEmpty(gameAllPlayRFTResult.getScore_c())){
            gameAllPlayRFTResult.setScore_c("0");
        }
        if(Check.isEmpty(gameAllPlayRFTResult.getScore_h())){
            gameAllPlayRFTResult.setScore_h("0");
        }
        MBTeamNumber.setText(gameAllPlayRFTResult.getScore_h());
        TGTeamNumber.setText(gameAllPlayRFTResult.getScore_c());
        GameLog.log("滚球足球玩法的接口：" + gameAllPlayRFTResult.toString());
        //让球的数据展示   滚球
        sw_RE(gameAllPlayRFTResult);
        //让球-上半场的数据展示   滚球
        sw_HRE(gameAllPlayRFTResult);
        //大/小的数据展示
        sw_ROU(gameAllPlayRFTResult);
        //大/小-上半场的数据展示
        sw_HOU(gameAllPlayRFTResult);
        //球队进球数 主队 大/小的数据展示
        sw_ROUH(gameAllPlayRFTResult);
        //球队进球数 客队大/小的数据展示
        sw_ROUC(gameAllPlayRFTResult);
        //球队进球数 主队 大/小 上半场的数据展示
        //sw_HRUH(gameAllPlayRFTResult);

        //球队进球数 客队 大/小 上半场的数据展示
        //sw_HRUC(gameAllPlayRFTResult);

        //独赢的数据展示
        sw_RM(gameAllPlayRFTResult);

        //独赢-上半场的数据展示
        sw_HRM(gameAllPlayRFTResult);

        //赢得任一半场
        sw_RWE(gameAllPlayRFTResult);

        //赢得所有半场
        sw_RWB(gameAllPlayRFTResult);

        //总进球数
        //sw_RT(gameAllPlayRFTResult);

        //总进球数  上半场
        sw_HRT(gameAllPlayRFTResult);

        //双方球队进球
        sw_RTS(gameAllPlayRFTResult);

        //单/双
        sw_REO(gameAllPlayRFTResult);

        //单/双  上半场
        sw_HREO(gameAllPlayRFTResult);

        //零失球
        sw_RCS(gameAllPlayRFTResult);

        //零失球获胜
        sw_RWN(gameAllPlayRFTResult);

        //最多进球的半场
        sw_RHG(gameAllPlayRFTResult);

        //最多进球的半场-独赢
        sw_RMG(gameAllPlayRFTResult);

        //双半场进球
        sw_RSB(gameAllPlayRFTResult);

        //半场/全场  开关
        sw_RF(gameAllPlayRFTResult);


        //净胜球数
        sw_RWM(gameAllPlayRFTResult);

        //双重机会
        sw_RDC(gameAllPlayRFTResult);

        //独赢&双方球队进球
        sw_RMTS(gameAllPlayRFTResult);


        //双重机会&双方球队进球
        sw_RDS(gameAllPlayRFTResult);

        //双重机会&进球 大/小
        sw_RDUA(gameAllPlayRFTResult);

        //波胆
        sw_RPD(gameAllPlayRFTResult);

        //波胆上半场
        sw_HRPD(gameAllPlayRFTResult);

        //独赢 & 进球 大/小
        sw_RMU(gameAllPlayRFTResult);

        //进球 大/小 & 双方球队进球
        sw_RUT(gameAllPlayRFTResult);

        //进球 大/小 & 进球 单/双
        sw_RUE(gameAllPlayRFTResult);
    }

    private void sw_HPD(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HPD().equals("Y")) {
            swHPDAll.setVisibility(View.VISIBLE);
            if (b_sw_HPD) {
                swHPDShow.setVisibility(View.VISIBLE);
            } else {
                swHPDShow.setVisibility(View.GONE);
            }
        } else {
            swHPDAll.setVisibility(View.GONE);
        }
        ArrayList<SwPDMD2TG> myListSwPDh = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDc = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDhc = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 6; ++i) {
            SwPDMD2TG swPDMD2TGH = new SwPDMD2TG();
            SwPDMD2TG swPDMD2TGC = new SwPDMD2TG();
            SwPDMD2TG swPDHE = null;
            if (i < 4) {
                swPDHE = new SwPDMD2TG();
                swPDHE.order_method = "FT_hpd";
                swPDHE.wtype = "HPD";
            }
            swPDMD2TGH.order_method = "FT_hpd";
            swPDMD2TGH.wtype = "HPD";

            swPDMD2TGC.order_method = "FT_hpd";
            swPDMD2TGC.wtype = "HPD";
            switch (i) {
                case 0:
                    swPDMD2TGH.rtype = "H1C0";
                    swPDMD2TGH.ior_H_up = "1 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH1C0();

                    swPDMD2TGC.rtype = "H0C1";
                    swPDMD2TGC.ior_H_up = "0 - 1";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH0C1();

                    swPDHE.rtype = "H0C0";
                    swPDHE.ior_H_up = "0 - 0";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_HH0C0();
                    break;
                case 1:
                    swPDMD2TGH.rtype = "H2C0";
                    swPDMD2TGH.ior_H_up = "2 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH2C0();

                    swPDMD2TGC.rtype = "H0C2";
                    swPDMD2TGC.ior_H_up = "0 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH0C2();

                    swPDHE.rtype = "H1C1";
                    swPDHE.ior_H_up = "1 - 1";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_HH1C1();
                    break;
                case 2:
                    swPDMD2TGH.rtype = "H2C1";
                    swPDMD2TGH.ior_H_up = "2 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH2C1();

                    swPDMD2TGC.rtype = "H1C2";
                    swPDMD2TGC.ior_H_up = "1 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH1C2();

                    swPDHE.rtype = "H2C2";
                    swPDHE.ior_H_up = "2 - 2";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_HH2C2();
                    break;
                case 3:
                    swPDMD2TGH.rtype = "H3C0";
                    swPDMD2TGH.ior_H_up = "3 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH3C0();

                    swPDMD2TGC.rtype = "H0C3";
                    swPDMD2TGC.ior_H_up = "0 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH0C3();

                    swPDHE.rtype = "H3C3";
                    swPDHE.ior_H_up = "3 - 3";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_HH3C3();
                    break;
                case 4:
                    swPDMD2TGH.rtype = "H3C1";
                    swPDMD2TGH.ior_H_up = "3 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH3C1();

                    swPDMD2TGC.rtype = "H1C3";
                    swPDMD2TGC.ior_H_up = "1 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH1C3();

                    /*swPDHE.rtype = "H4C4";
                    swPDHE.ior_H_up = "4 - 4";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_HH4C4();*/
                    break;
                case 5:
                    swPDMD2TGH.rtype = "H3C2";
                    swPDMD2TGH.ior_H_up = "3 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH3C2();

                    swPDMD2TGC.rtype = "H2C3";
                    swPDMD2TGC.ior_H_up = "2 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH2C3();
                    break;
                case 6:
                    swPDMD2TGH.rtype = "H4C0";
                    swPDMD2TGH.ior_H_up = "4 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH4C0();

                    swPDMD2TGH.rtype = "H0C4";
                    swPDMD2TGC.ior_H_up = "0 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH0C4();
                    break;
                case 7:
                    swPDMD2TGH.rtype = "H4C1";
                    swPDMD2TGH.ior_H_up = "4 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH4C1();

                    swPDMD2TGH.rtype = "H1C4";
                    swPDMD2TGC.ior_H_up = "1 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH1C4();
                    break;
                case 8:
                    swPDMD2TGH.rtype = "H4C2";
                    swPDMD2TGH.ior_H_up = "4 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH4C2();

                    swPDMD2TGH.rtype = "H2C4";
                    swPDMD2TGC.ior_H_up = "2 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH2C4();
                    break;
                case 9:
                    swPDMD2TGH.rtype = "H4C3";
                    swPDMD2TGH.ior_H_up = "4 - 3";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_HH4C3();

                    swPDMD2TGH.rtype = "H3C4";
                    swPDMD2TGC.ior_H_up = "3 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_HH3C4();
                    break;
            }
            myListSwPDh.add(swPDMD2TGH);
            myListSwPDc.add(swPDMD2TGC);
            if (i < 4) {
                myListSwPDhc.add(swPDHE);
            }
        }
        iorHOVH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HOVH()));

        /*ArrayList<SwPDMD2TG> myListSwPDHE = new ArrayList<SwPDMD2TG>();
        for(int i = 0; i < 5; ++i){
            SwPDMD2TG swPDMD2TG = new SwPDMD2TG();
            switch (i){
                case 0:
                    swPDMD2TG.ior_H_up = "1 - 0";
                    swPDMD2TG.ior_H_down = "17.2";
                    break;
                case 1:
                    swPDMD2TG.ior_H_up = "2 - 0";
                    swPDMD2TG.ior_H_down = "23";
                    break;
                case 2:
                    swPDMD2TG.ior_H_up = "2 - 1";
                    swPDMD2TG.ior_H_down = "28";
                    break;
                case 3:
                    swPDMD2TG.ior_H_up = "3 - 0";
                    swPDMD2TG.ior_H_down = "43";
                    break;
                case 4:
                    swPDMD2TG.ior_H_up = "3 - 1";
                    swPDMD2TG.ior_H_down = "90";
                    break;
            }
            myListSwPDHE.add(swPDMD2TG);
        }*/

        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swHPDMD2TGShow.setLayoutManager(mLayoutManager1);
        swHPDMD2TGShow.setHasFixedSize(true);
        swHPDMD2TGShow.setNestedScrollingEnabled(false);
        swHPDMD2TGShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDh));

        LinearLayoutManager mLayoutManager2 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swHPDHEShow.setLayoutManager(mLayoutManager2);
        swHPDHEShow.setHasFixedSize(true);
        swHPDHEShow.setNestedScrollingEnabled(false);
        swHPDHEShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDhc));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swHPDTG2MDShow.setLayoutManager(mLayoutManager3);
        swHPDTG2MDShow.setHasFixedSize(true);
        swHPDTG2MDShow.setNestedScrollingEnabled(false);
        swHPDTG2MDShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDc));

    }

    private void sw_HRPD(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HRPD().equals("Y")) {
            swHPDAll.setVisibility(View.VISIBLE);
            if (b_sw_HPD) {
                swHPDShow.setVisibility(View.VISIBLE);
            } else {
                swHPDShow.setVisibility(View.GONE);
            }
        } else {
            swHPDAll.setVisibility(View.GONE);
        }
        ArrayList<SwPDMD2TG> myListSwPDh = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDc = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDhc = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 6; ++i) {
            SwPDMD2TG swPDMD2TGH = new SwPDMD2TG();
            SwPDMD2TG swPDMD2TGC = new SwPDMD2TG();
            SwPDMD2TG swPDHE = null;
            if (i < 4) {
                swPDHE = new SwPDMD2TG();
                swPDHE.order_method = "FT_hrpd";
                swPDHE.wtype = "HRPD";
            }
            swPDMD2TGH.order_method = "FT_hrpd";
            swPDMD2TGH.wtype = "HRPD";

            swPDMD2TGC.order_method = "FT_hrpd";
            swPDMD2TGC.wtype = "HRPD";
            switch (i) {
                case 0:
                    swPDMD2TGH.rtype = "RH1C0";
                    swPDMD2TGH.ior_H_up = "1 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH1C0();

                    swPDMD2TGC.rtype = "RH0C1";
                    swPDMD2TGC.ior_H_up = "0 - 1";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH0C1();

                    swPDHE.rtype = "RH0C0";
                    swPDHE.ior_H_up = "0 - 0";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_HRH0C0();
                    break;
                case 1:
                    swPDMD2TGH.rtype = "RH2C0";
                    swPDMD2TGH.ior_H_up = "2 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH2C0();

                    swPDMD2TGC.rtype = "RH0C2";
                    swPDMD2TGC.ior_H_up = "0 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH0C2();

                    swPDHE.rtype = "RH1C1";
                    swPDHE.ior_H_up = "1 - 1";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_HRH1C1();
                    break;
                case 2:
                    swPDMD2TGH.rtype = "RH2C1";
                    swPDMD2TGH.ior_H_up = "2 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH2C1();

                    swPDMD2TGC.rtype = "RH1C2";
                    swPDMD2TGC.ior_H_up = "1 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH1C2();

                    swPDHE.rtype = "RH2C2";
                    swPDHE.ior_H_up = "2 - 2";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_HRH2C2();
                    break;
                case 3:
                    swPDMD2TGH.rtype = "RH3C0";
                    swPDMD2TGH.ior_H_up = "3 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH3C0();

                    swPDMD2TGC.rtype = "RH0C3";
                    swPDMD2TGC.ior_H_up = "0 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH0C3();

                    swPDHE.rtype = "RH3C3";
                    swPDHE.ior_H_up = "3 - 3";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_HRH3C3();
                    break;
                case 4:
                    swPDMD2TGH.rtype = "RH3C1";
                    swPDMD2TGH.ior_H_up = "3 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH3C1();

                    swPDMD2TGC.rtype = "RH1C3";
                    swPDMD2TGC.ior_H_up = "1 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH1C3();

                    /*swPDHE.rtype = "RH4C4";
                    swPDHE.ior_H_up = "4 - 4";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_HRH4C4();*/
                    break;
                case 5:
                    swPDMD2TGH.rtype = "RH3C2";
                    swPDMD2TGH.ior_H_up = "3 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH3C2();

                    swPDMD2TGC.rtype = "RH2C3";
                    swPDMD2TGC.ior_H_up = "2 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH2C3();
                    break;
                case 6:
                    swPDMD2TGH.rtype = "RH4C0";
                    swPDMD2TGH.ior_H_up = "4 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH4C0();

                    swPDMD2TGC.rtype = "RH0C4";
                    swPDMD2TGC.ior_H_up = "0 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH0C4();
                    break;
                case 7:
                    swPDMD2TGH.rtype = "RH4C1";
                    swPDMD2TGH.ior_H_up = "4 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH4C1();

                    swPDMD2TGC.rtype = "RH1C4";
                    swPDMD2TGC.ior_H_up = "1 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH1C4();
                    break;
                case 8:
                    swPDMD2TGH.rtype = "RH4C2";
                    swPDMD2TGH.ior_H_up = "4 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH4C2();

                    swPDMD2TGC.rtype = "RH2C4";
                    swPDMD2TGC.ior_H_up = "2 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH2C4();
                    break;
                case 9:
                    swPDMD2TGH.rtype = "RH4C3";
                    swPDMD2TGH.ior_H_up = "4 - 3";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_HRH4C3();

                    swPDMD2TGC.rtype = "RH3C4";
                    swPDMD2TGC.ior_H_up = "3 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_HRH3C4();
                    break;
            }
            myListSwPDh.add(swPDMD2TGH);
            myListSwPDc.add(swPDMD2TGC);
            if (i < 4) {
                myListSwPDhc.add(swPDHE);
            }
        }
        iorHOVH.setText(gameAllPlayRFTResult.getIor_HROVH());

        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swHPDMD2TGShow.setLayoutManager(mLayoutManager1);
        swHPDMD2TGShow.setHasFixedSize(true);
        swHPDMD2TGShow.setNestedScrollingEnabled(false);
        swHPDMD2TGShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDh));

        LinearLayoutManager mLayoutManager2 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swHPDHEShow.setLayoutManager(mLayoutManager2);
        swHPDHEShow.setHasFixedSize(true);
        swHPDHEShow.setNestedScrollingEnabled(false);
        swHPDHEShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDhc));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swHPDTG2MDShow.setLayoutManager(mLayoutManager3);
        swHPDTG2MDShow.setHasFixedSize(true);
        swHPDTG2MDShow.setNestedScrollingEnabled(false);
        swHPDTG2MDShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDc));

    }

    private void sw_PD(GameAllPlayFTResult gameAllPlayFTResult) {
        GameLog.log("---------------波胆数据展示---------------");
        if (gameAllPlayFTResult.getSw_PD().equals("Y")) {
            swPDAll.setVisibility(View.VISIBLE);
            if (b_sw_PD) {
                swPDShow.setVisibility(View.VISIBLE);
            } else {
                swPDShow.setVisibility(View.GONE);
            }
        } else {
            swPDAll.setVisibility(View.GONE);
        }
        ArrayList<SwPDMD2TG> myListSwPDh = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDc = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDhc = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 10; ++i) {
            SwPDMD2TG swPDMD2TGH = new SwPDMD2TG();
            SwPDMD2TG swPDMD2TGC = new SwPDMD2TG();
            SwPDMD2TG swPDHE = null;
            if (i < 5) {
                swPDHE = new SwPDMD2TG();
                swPDHE.order_method = "FT_pd";
                swPDHE.wtype = "PD";
            }
            swPDMD2TGH.order_method = "FT_pd";
            swPDMD2TGH.wtype = "PD";

            swPDMD2TGC.order_method = "FT_pd";
            swPDMD2TGC.wtype = "PD";
            switch (i) {
                case 0:
                    swPDMD2TGH.rtype = "H1C0";
                    swPDMD2TGH.ior_H_up = "1 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H1C0();

                    swPDMD2TGC.rtype = "H0C1";
                    swPDMD2TGC.ior_H_up = "0 - 1";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H0C1();

                    swPDHE.rtype = "H0C0";
                    swPDHE.ior_H_up = "0 - 0";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_H0C0();
                    break;
                case 1:
                    swPDMD2TGH.rtype = "H2C0";
                    swPDMD2TGH.ior_H_up = "2 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H2C0();

                    swPDMD2TGC.rtype = "H0C2";
                    swPDMD2TGC.ior_H_up = "0 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H0C2();

                    swPDHE.rtype = "H1C1";
                    swPDHE.ior_H_up = "1 - 1";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_H1C1();
                    break;
                case 2:
                    swPDMD2TGH.rtype = "H2C1";
                    swPDMD2TGH.ior_H_up = "2 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H2C1();

                    swPDMD2TGC.rtype = "H1C2";
                    swPDMD2TGC.ior_H_up = "1 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H1C2();

                    swPDHE.rtype = "H2C2";
                    swPDHE.ior_H_up = "2 - 2";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_H2C2();
                    break;
                case 3:
                    swPDMD2TGH.rtype = "H3C0";
                    swPDMD2TGH.ior_H_up = "3 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H3C0();

                    swPDMD2TGC.rtype = "H0C3";
                    swPDMD2TGC.ior_H_up = "0 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H0C3();

                    swPDHE.rtype = "H3C3";
                    swPDHE.ior_H_up = "3 - 3";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_H3C3();
                    break;
                case 4:
                    swPDMD2TGH.rtype = "H3C1";
                    swPDMD2TGH.ior_H_up = "3 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H3C1();

                    swPDMD2TGC.rtype = "H1C3";
                    swPDMD2TGC.ior_H_up = "1 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H1C3();

                    swPDHE.rtype = "H4C4";
                    swPDHE.ior_H_up = "4 - 4";
                    swPDHE.ior_H_down = gameAllPlayFTResult.getIor_H4C4();
                    break;
                case 5:
                    swPDMD2TGH.rtype = "H3C2";
                    swPDMD2TGH.ior_H_up = "3 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H3C2();

                    swPDMD2TGC.rtype = "H2C3";
                    swPDMD2TGC.ior_H_up = "2 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H2C3();
                    break;
                case 6:
                    swPDMD2TGH.rtype = "H4C0";
                    swPDMD2TGH.ior_H_up = "4 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H4C0();

                    swPDMD2TGC.rtype = "H0C4";
                    swPDMD2TGC.ior_H_up = "0 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H0C4();
                    break;
                case 7:
                    swPDMD2TGH.rtype = "H4C1";
                    swPDMD2TGH.ior_H_up = "4 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H4C1();

                    swPDMD2TGC.rtype = "H1C4";
                    swPDMD2TGC.ior_H_up = "1 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H1C4();
                    break;
                case 8:
                    swPDMD2TGH.rtype = "H4C2";
                    swPDMD2TGH.ior_H_up = "4 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H4C2();

                    swPDMD2TGC.rtype = "H2C4";
                    swPDMD2TGC.ior_H_up = "2 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H2C4();
                    break;
                case 9:
                    swPDMD2TGH.rtype = "H4C3";
                    swPDMD2TGH.ior_H_up = "4 - 3";
                    swPDMD2TGH.ior_H_down = gameAllPlayFTResult.getIor_H4C3();

                    swPDMD2TGC.rtype = "H3C4";
                    swPDMD2TGC.ior_H_up = "3 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayFTResult.getIor_H3C4();
                    break;
            }
            myListSwPDh.add(swPDMD2TGH);
            myListSwPDc.add(swPDMD2TGC);
            if (i < 5) {
                myListSwPDhc.add(swPDHE);
            }
        }
        iorOVH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OVH()));

        /*ArrayList<SwPDMD2TG> myListSwPDHE = new ArrayList<SwPDMD2TG>();
        for(int i = 0; i < 5; ++i){
            SwPDMD2TG swPDMD2TG = new SwPDMD2TG();
            switch (i){
                case 0:
                    swPDMD2TG.ior_H_up = "1 - 0";
                    swPDMD2TG.ior_H_down = "17.2";
                    break;
                case 1:
                    swPDMD2TG.ior_H_up = "2 - 0";
                    swPDMD2TG.ior_H_down = "23";
                    break;
                case 2:
                    swPDMD2TG.ior_H_up = "2 - 1";
                    swPDMD2TG.ior_H_down = "28";
                    break;
                case 3:
                    swPDMD2TG.ior_H_up = "3 - 0";
                    swPDMD2TG.ior_H_down = "43";
                    break;
                case 4:
                    swPDMD2TG.ior_H_up = "3 - 1";
                    swPDMD2TG.ior_H_down = "90";
                    break;
            }
            myListSwPDHE.add(swPDMD2TG);
        }*/

        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swPDMD2TGShow.setLayoutManager(mLayoutManager1);
        swPDMD2TGShow.setHasFixedSize(true);
        swPDMD2TGShow.setNestedScrollingEnabled(false);
        swPDMD2TGShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDh));

        LinearLayoutManager mLayoutManager2 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swPDHEShow.setLayoutManager(mLayoutManager2);
        swPDHEShow.setHasFixedSize(true);
        swPDHEShow.setNestedScrollingEnabled(false);
        swPDHEShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDhc));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swPDTG2MDShow.setLayoutManager(mLayoutManager3);
        swPDTG2MDShow.setHasFixedSize(true);
        swPDTG2MDShow.setNestedScrollingEnabled(false);
        swPDTG2MDShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDc));
    }

    private void sw_BKPD(GameAllPlayBKResult gameAllPlayBKResult) {
        GameLog.log("---------------篮球波胆数据展示---------------"+orderType);

        if (gameAllPlayBKResult.getSw_PD().equals("Y")) {
            swBKPDHAll.setVisibility(View.VISIBLE);
            swBKPDCAll.setVisibility(View.VISIBLE);
        } else {
            swBKPDHAll.setVisibility(View.GONE);
            swBKPDCAll.setVisibility(View.GONE);
        }
        swBKPDHName.setText("球队得分：" + gameAllPlayBKResult.getTeam_h() + " -最后一位数");
        swBKPDCName.setText("球队得分：" + gameAllPlayBKResult.getTeam_c() + " -最后一位数");
        ArrayList<SwPDMD2TG> myListSwPDh = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDc = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 5; ++i) {
            SwPDMD2TG swPDMD2TGH = new SwPDMD2TG();
            SwPDMD2TG swPDMD2TGC = new SwPDMD2TG();
            swPDMD2TGH.order_method = "BK_pd";
            swPDMD2TGH.wtype = "PD";
            swPDMD2TGC.order_method = "BK_pd";
            swPDMD2TGC.wtype = "PD";
            switch (i) {
                case 0:
                    swPDMD2TGH.rtype = "PDH0";
                    swPDMD2TGH.ior_H_up = "0或5";
                    swPDMD2TGH.ior_H_down = gameAllPlayBKResult.getIor_PDH0();

                    swPDMD2TGC.rtype = "PDC0";
                    swPDMD2TGC.ior_H_up = "0或5";
                    swPDMD2TGC.ior_H_down = gameAllPlayBKResult.getIor_PDC0();
                    break;
                case 1:
                    swPDMD2TGH.rtype = "PDH1";
                    swPDMD2TGH.ior_H_up = "1或6";
                    swPDMD2TGH.ior_H_down = gameAllPlayBKResult.getIor_PDH1();

                    swPDMD2TGC.rtype = "PDC1";
                    swPDMD2TGC.ior_H_up = "1或6";
                    swPDMD2TGC.ior_H_down = gameAllPlayBKResult.getIor_PDC1();
                    break;
                case 2:
                    swPDMD2TGH.rtype = "PDH2";
                    swPDMD2TGH.ior_H_up = "2或7";
                    swPDMD2TGH.ior_H_down = gameAllPlayBKResult.getIor_PDH2();

                    swPDMD2TGC.rtype = "PDC2";
                    swPDMD2TGC.ior_H_up = "2或7";
                    swPDMD2TGC.ior_H_down = gameAllPlayBKResult.getIor_PDC2();
                    break;
                case 3:
                    swPDMD2TGH.rtype = "PDH3";
                    swPDMD2TGH.ior_H_up = "3或8";
                    swPDMD2TGH.ior_H_down = gameAllPlayBKResult.getIor_PDH3();

                    swPDMD2TGC.rtype = "PDC3";
                    swPDMD2TGC.ior_H_up = "3或8";
                    swPDMD2TGC.ior_H_down = gameAllPlayBKResult.getIor_PDC3();
                    break;
                case 4:
                    swPDMD2TGH.rtype = "PDH4";
                    swPDMD2TGH.ior_H_up = "4或9";
                    swPDMD2TGH.ior_H_down = gameAllPlayBKResult.getIor_PDH4();

                    swPDMD2TGC.rtype = "PDC4";
                    swPDMD2TGC.ior_H_up = "4或9";
                    swPDMD2TGC.ior_H_down = gameAllPlayBKResult.getIor_PDC4();
                    break;
            }
            myListSwPDh.add(swPDMD2TGH);
            myListSwPDc.add(swPDMD2TGC);
        }


        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swBKPDH.setLayoutManager(mLayoutManager1);
        swBKPDH.setHasFixedSize(true);
        swBKPDH.setNestedScrollingEnabled(false);
        swBKPDH.setAdapter(new SwBKPDlistAdapter(getContext(), R.layout.item_bk_pd, myListSwPDh));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swBKPDC.setLayoutManager(mLayoutManager3);
        swBKPDC.setHasFixedSize(true);
        swBKPDC.setNestedScrollingEnabled(false);
        swBKPDC.setAdapter(new SwBKPDlistAdapter(getContext(), R.layout.item_bk_pd, myListSwPDc));
    }

    private void sw_BKRPD(GameAllPlayRBKResult gameAllPlayRBKResult) {
        GameLog.log("---------------篮球波胆数据展示---------------");
        if (gameAllPlayRBKResult.getSw_RPD().equals("Y")) {
            swBKPDHAll.setVisibility(View.VISIBLE);
            swBKPDCAll.setVisibility(View.VISIBLE);
        } else {
            swBKPDHAll.setVisibility(View.GONE);
            swBKPDCAll.setVisibility(View.GONE);
        }
        swBKPDHName.setText("球队得分：" + gameAllPlayRBKResult.getTeam_h() + " -最后一位数");
        swBKPDCName.setText("球队得分：" + gameAllPlayRBKResult.getTeam_c() + " -最后一位数");
        ArrayList<SwPDMD2TG> myListSwPDh = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDc = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 5; ++i) {
            SwPDMD2TG swPDMD2TGH = new SwPDMD2TG();
            SwPDMD2TG swPDMD2TGC = new SwPDMD2TG();
            swPDMD2TGH.order_method = "BK_rpd";
            swPDMD2TGH.wtype = "RPD";
            swPDMD2TGC.order_method = "BK_rpd";
            swPDMD2TGC.wtype = "RPD";
            switch (i) {
                case 0:
                    swPDMD2TGH.rtype = "RPDH0";
                    swPDMD2TGH.ior_H_up = "0或5";
                    swPDMD2TGH.ior_H_down = gameAllPlayRBKResult.getIor_RPDH0();

                    swPDMD2TGC.rtype = "RPDC0";
                    swPDMD2TGC.ior_H_up = "0或5";
                    swPDMD2TGC.ior_H_down = gameAllPlayRBKResult.getIor_RPDC0();
                    break;
                case 1:
                    swPDMD2TGH.rtype = "RPDH1";
                    swPDMD2TGH.ior_H_up = "1或6";
                    swPDMD2TGH.ior_H_down = gameAllPlayRBKResult.getIor_RPDH1();

                    swPDMD2TGC.rtype = "RPDC1";
                    swPDMD2TGC.ior_H_up = "1或6";
                    swPDMD2TGC.ior_H_down = gameAllPlayRBKResult.getIor_RPDC1();
                    break;
                case 2:
                    swPDMD2TGH.rtype = "RPDH2";
                    swPDMD2TGH.ior_H_up = "2或7";
                    swPDMD2TGH.ior_H_down = gameAllPlayRBKResult.getIor_RPDH2();

                    swPDMD2TGC.rtype = "RPDC2";
                    swPDMD2TGC.ior_H_up = "2或7";
                    swPDMD2TGC.ior_H_down = gameAllPlayRBKResult.getIor_RPDC2();
                    break;
                case 3:
                    swPDMD2TGH.rtype = "RPDH3";
                    swPDMD2TGH.ior_H_up = "3或8";
                    swPDMD2TGH.ior_H_down = gameAllPlayRBKResult.getIor_RPDH3();

                    swPDMD2TGC.rtype = "RPDC3";
                    swPDMD2TGC.ior_H_up = "3或8";
                    swPDMD2TGC.ior_H_down = gameAllPlayRBKResult.getIor_RPDC3();
                    break;
                case 4:
                    swPDMD2TGH.rtype = "RPDH4";
                    swPDMD2TGH.ior_H_up = "4或9";
                    swPDMD2TGH.ior_H_down = gameAllPlayRBKResult.getIor_RPDH4();

                    swPDMD2TGC.rtype = "RPDC4";
                    swPDMD2TGC.ior_H_up = "4或9";
                    swPDMD2TGC.ior_H_down = gameAllPlayRBKResult.getIor_RPDC4();
                    break;
            }
            myListSwPDh.add(swPDMD2TGH);
            myListSwPDc.add(swPDMD2TGC);
        }


        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swBKPDH.setLayoutManager(mLayoutManager1);
        swBKPDH.setHasFixedSize(true);
        swBKPDH.setNestedScrollingEnabled(false);
        swBKPDH.setAdapter(new SwBKPDlistAdapter(getContext(), R.layout.item_bk_pd, myListSwPDh));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swBKPDC.setLayoutManager(mLayoutManager3);
        swBKPDC.setHasFixedSize(true);
        swBKPDC.setNestedScrollingEnabled(false);
        swBKPDC.setAdapter(new SwBKPDlistAdapter(getContext(), R.layout.item_bk_pd, myListSwPDc));
    }

    private void sw_RPD(GameAllPlayRFTResult gameAllPlayRFTResult) {
        GameLog.log("---------------滚球 波胆数据展示---------------");
        if (gameAllPlayRFTResult.getSw_RPD().equals("Y")) {
            swPDAll.setVisibility(View.VISIBLE);
            if (b_sw_PD) {
                swPDShow.setVisibility(View.VISIBLE);
            } else {
                swPDShow.setVisibility(View.GONE);
            }
        } else {
            swPDAll.setVisibility(View.GONE);
        }
        ArrayList<SwPDMD2TG> myListSwPDh = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDc = new ArrayList<SwPDMD2TG>();
        ArrayList<SwPDMD2TG> myListSwPDhc = new ArrayList<SwPDMD2TG>();
        for (int i = 0; i < 10; ++i) {
            SwPDMD2TG swPDMD2TGH = new SwPDMD2TG();
            SwPDMD2TG swPDMD2TGC = new SwPDMD2TG();
            SwPDMD2TG swPDHE = null;
            if (i < 5) {
                swPDHE = new SwPDMD2TG();
                swPDHE.order_method = "FT_rpd";
                swPDHE.wtype = "RPD";
            }
            swPDMD2TGH.order_method = "FT_rpd";
            swPDMD2TGH.wtype = "RPD";

            swPDMD2TGC.order_method = "FT_rpd";
            swPDMD2TGC.wtype = "RPD";
            switch (i) {
                case 0:
                    swPDMD2TGH.rtype = "RH1C0";
                    swPDMD2TGH.ior_H_up = "1 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH1C0();

                    swPDMD2TGC.rtype = "RH0C1";
                    swPDMD2TGC.ior_H_up = "0 - 1";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH0C1();

                    swPDHE.rtype = "RH0C0";
                    swPDHE.ior_H_up = "0 - 0";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_RH0C0();
                    break;
                case 1:
                    swPDMD2TGH.rtype = "RH2C0";
                    swPDMD2TGH.ior_H_up = "2 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH2C0();

                    swPDMD2TGC.rtype = "RH0C2";
                    swPDMD2TGC.ior_H_up = "0 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH0C2();

                    swPDHE.rtype = "RH1C1";
                    swPDHE.ior_H_up = "1 - 1";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_RH1C1();
                    break;
                case 2:
                    swPDMD2TGH.rtype = "RH2C1";
                    swPDMD2TGH.ior_H_up = "2 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH2C1();

                    swPDMD2TGC.rtype = "RH1C2";
                    swPDMD2TGC.ior_H_up = "1 - 2";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH1C2();

                    swPDHE.rtype = "RH2C2";
                    swPDHE.ior_H_up = "2 - 2";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_RH2C2();
                    break;
                case 3:
                    swPDMD2TGH.rtype = "RH3C0";
                    swPDMD2TGH.ior_H_up = "3 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH3C0();

                    swPDMD2TGC.rtype = "RH0C3";
                    swPDMD2TGC.ior_H_up = "0 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH0C3();

                    swPDHE.rtype = "RH3C3";
                    swPDHE.ior_H_up = "3 - 3";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_RH3C3();
                    break;
                case 4:
                    swPDMD2TGH.rtype = "RH3C1";
                    swPDMD2TGH.ior_H_up = "3 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH3C1();

                    swPDMD2TGC.rtype = "RH1C3";
                    swPDMD2TGC.ior_H_up = "1 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH1C3();

                    swPDHE.rtype = "RH4C4";
                    swPDHE.ior_H_up = "4 - 4";
                    swPDHE.ior_H_down = gameAllPlayRFTResult.getIor_RH4C4();
                    break;
                case 5:
                    swPDMD2TGH.rtype = "RH3C2";
                    swPDMD2TGH.ior_H_up = "3 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH3C2();

                    swPDMD2TGC.rtype = "RH2C3";
                    swPDMD2TGC.ior_H_up = "2 - 3";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH2C3();
                    break;
                case 6:
                    swPDMD2TGH.rtype = "RH4C0";
                    swPDMD2TGH.ior_H_up = "4 - 0";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH4C0();

                    swPDMD2TGC.rtype = "RH0C4";
                    swPDMD2TGC.ior_H_up = "0 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH0C4();
                    break;
                case 7:
                    swPDMD2TGH.rtype = "RH4C1";
                    swPDMD2TGH.ior_H_up = "4 - 1";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH4C1();

                    swPDMD2TGC.rtype = "RH1C4";
                    swPDMD2TGC.ior_H_up = "1 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH1C4();
                    break;
                case 8:
                    swPDMD2TGH.rtype = "RH4C2";
                    swPDMD2TGH.ior_H_up = "4 - 2";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH4C2();

                    swPDMD2TGC.rtype = "RH2C4";
                    swPDMD2TGC.ior_H_up = "2 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH2C4();
                    break;
                case 9:
                    swPDMD2TGH.rtype = "RH4C3";
                    swPDMD2TGH.ior_H_up = "4 - 3";
                    swPDMD2TGH.ior_H_down = gameAllPlayRFTResult.getIor_RH4C3();

                    swPDMD2TGC.rtype = "RH3C4";
                    swPDMD2TGC.ior_H_up = "3 - 4";
                    swPDMD2TGC.ior_H_down = gameAllPlayRFTResult.getIor_RH3C4();
                    break;
            }
            myListSwPDh.add(swPDMD2TGH);
            myListSwPDc.add(swPDMD2TGC);
            if (i < 5) {
                myListSwPDhc.add(swPDHE);
            }
        }
        iorOVH.setText(GameShipHelper.formatMoney(gameAllPlayRFTResult.getIor_ROVH()));

        LinearLayoutManager mLayoutManager1 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swPDMD2TGShow.setLayoutManager(mLayoutManager1);
        swPDMD2TGShow.setHasFixedSize(true);
        swPDMD2TGShow.setNestedScrollingEnabled(false);
        swPDMD2TGShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDh));

        LinearLayoutManager mLayoutManager2 = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        swPDHEShow.setLayoutManager(mLayoutManager2);
        swPDHEShow.setHasFixedSize(true);
        swPDHEShow.setNestedScrollingEnabled(false);
        swPDHEShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDhc));

        LinearLayoutManager mLayoutManager3 = new LinearLayoutManager(getContext());
        mLayoutManager3.setOrientation(LinearLayoutManager.VERTICAL);
        swPDTG2MDShow.setLayoutManager(mLayoutManager3);
        swPDTG2MDShow.setHasFixedSize(true);
        swPDTG2MDShow.setNestedScrollingEnabled(false);
        swPDTG2MDShow.setAdapter(new SwPDlistAdapter(getContext(), R.layout.item_sw_pd, myListSwPDc));
    }

    private void sw_DS(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_DS().equals("Y")) {
            swDSAll.setVisibility(View.VISIBLE);
        } else {
            swDSAll.setVisibility(View.GONE);
        }
        iorDSHY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DSHY()));
        iorDSHN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DSHN()));
        iorDSCY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DSCY()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DSHY())){
            iorDSHY.setVisibility(View.GONE);
            iorDSHYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DSHN())){
            iorDSHN.setVisibility(View.GONE);
            iorDSHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DSCY())){
            iorDSCY.setVisibility(View.GONE);
            iorDSCYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        iorDSCN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DSCN()));
        iorDSSY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DSSY()));
        iorDSSN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DSSN()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DSCN())){
            iorDSCN.setVisibility(View.GONE);
            iorDSCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DSSY())){
            iorDSSY.setVisibility(View.GONE);
            iorDSSYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DSSN())){
            iorDSSN.setVisibility(View.GONE);
            iorDSSNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        iorDSHYName.setText(gameAllPlayFTResult.getTeam_h() + " / 和局 & 是");
        iorDSHNName.setText(gameAllPlayFTResult.getTeam_h() + " / 和局 & 不是");
        iorDSCYName.setText(gameAllPlayFTResult.getTeam_c() + " / 和局  & 是");
        iorDSCNName.setText(gameAllPlayFTResult.getTeam_c() + " / 和局 & 不是");
        iorDSSYName.setText(gameAllPlayFTResult.getTeam_h() + " / " + gameAllPlayFTResult.getTeam_c() + "  & 是");
        iorDSSNName.setText(gameAllPlayFTResult.getTeam_h() + " / " + gameAllPlayFTResult.getTeam_c() + " & 不是");

    }

    private void sw_RDS(GameAllPlayRFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_RDS().equals("Y")) {
            swDSAll.setVisibility(View.VISIBLE);
        } else {
            swDSAll.setVisibility(View.GONE);
        }
        iorDSHY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RDSHY()));
        iorDSHN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RDSHN()));
        iorDSCY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RDSCY()));
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RDSHY())){
            iorDSHY.setVisibility(View.GONE);
            iorDSHYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RDSHN())){
            iorDSHN.setVisibility(View.GONE);
            iorDSHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RDSCY())){
            iorDSCY.setVisibility(View.GONE);
            iorDSCYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorDSCN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RDSCN()));
        iorDSSY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RDSSY()));
        iorDSSN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RDSSN()));
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RDSCN())){
            iorDSCN.setVisibility(View.GONE);
            iorDSCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RDSSY())){
            iorDSSY.setVisibility(View.GONE);
            iorDSSYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RDSSN())){
            iorDSSN.setVisibility(View.GONE);
            iorDSSNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorDSHYName.setText(gameAllPlayFTResult.getTeam_h() + " / 和局 & 是");
        iorDSHNName.setText(gameAllPlayFTResult.getTeam_h() + " / 和局 & 不是");
        iorDSCYName.setText(gameAllPlayFTResult.getTeam_c() + " / 和局  & 是");
        iorDSCNName.setText(gameAllPlayFTResult.getTeam_c() + " / 和局 & 不是");
        iorDSSYName.setText(gameAllPlayFTResult.getTeam_h() + " / " + gameAllPlayFTResult.getTeam_c() + "  & 是");
        iorDSSNName.setText(gameAllPlayFTResult.getTeam_h() + " / " + gameAllPlayFTResult.getTeam_c() + " & 不是");

    }

    private void sw_MTS(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_MTS().equals("Y")) {
            swMTSAll.setVisibility(View.VISIBLE);
        } else {
            swMTSAll.setVisibility(View.GONE);
        }
        iorMTSHY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MTSHY()));
        iorMTSHN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MTSHN()));
        iorMTSNY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MTSNY()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MTSHY())){
            iorMTSHY.setVisibility(View.GONE);
            iorMTSHYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MTSHN())){
            iorMTSHN.setVisibility(View.GONE);
            iorMTSHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MTSNY())){
            iorMTSNY.setVisibility(View.GONE);
            iorMTSNYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        iorMTSNN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MTSNN()));
        iorMTSCY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MTSCY()));
        iorMTSCN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MTSCN()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MTSNN())){
            iorMTSNN.setVisibility(View.GONE);
            iorMTSNNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MTSCY())){
            iorMTSCY.setVisibility(View.GONE);
            iorMTSCYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MTSCN())){
            iorMTSCN.setVisibility(View.GONE);
            iorMTSCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorMTSHYName.setText(gameAllPlayFTResult.getTeam_h() + " & 是");
        iorMTSHNName.setText(gameAllPlayFTResult.getTeam_h() + " & 不是");
        iorMTSCYName.setText(gameAllPlayFTResult.getTeam_c() + "  & 是");
        iorMTSCNName.setText(gameAllPlayFTResult.getTeam_c() + " & 不是");

    }

    private void sw_RMTS(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RMTS().equals("Y")) {
            swMTSAll.setVisibility(View.VISIBLE);
        } else {
            swMTSAll.setVisibility(View.GONE);
        }
        iorMTSHY.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMTSHY()));
        iorMTSHN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMTSHN()));
        iorMTSNY.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMTSNY()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMTSHY())){
            iorMTSHY.setVisibility(View.GONE);
            iorMTSHYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMTSHN())){
            iorMTSHN.setVisibility(View.GONE);
            iorMTSHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMTSNY())){
            iorMTSNY.setVisibility(View.GONE);
            iorMTSNYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorMTSNN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMTSNN()));
        iorMTSCY.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMTSCY()));
        iorMTSCN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMTSCN()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMTSNN())){
            iorMTSNN.setVisibility(View.GONE);
            iorMTSNNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMTSCY())){
            iorMTSCY.setVisibility(View.GONE);
            iorMTSCYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMTSCN())){
            iorMTSCN.setVisibility(View.GONE);
            iorMTSCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorMTSHYName.setText(gameAllPlayRFTResult.getTeam_h() + " & 是");
        iorMTSHNName.setText(gameAllPlayRFTResult.getTeam_h() + " & 不是");
        iorMTSCYName.setText(gameAllPlayRFTResult.getTeam_c() + "  & 是");
        iorMTSCNName.setText(gameAllPlayRFTResult.getTeam_c() + " & 不是");

    }

    private void sw_DC(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_DC().equals("Y")) {
            swDCAll.setVisibility(View.VISIBLE);
        } else {
            swDCAll.setVisibility(View.GONE);
        }
        iorDCHN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DCHN()));
        iorDCCN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DCCN()));
        iorDCHC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_DCHC()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DCHN())){
            iorDCHN.setVisibility(View.GONE);
            iorDCHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DCCN())){
            iorDCCN.setVisibility(View.GONE);
            iorDCCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_DCHC())){
            iorDCHC.setVisibility(View.GONE);
            iorDCHCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorDCHNName.setText(gameAllPlayFTResult.getTeam_h() + " / 和局");
        iorDCCNName.setText(gameAllPlayFTResult.getTeam_c() + " / 和局");
        iorDCHCName.setText(gameAllPlayFTResult.getTeam_h() + " / " + gameAllPlayFTResult.getTeam_c());

    }

    private void sw_RDC(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RDC().equals("Y")) {
            swDCAll.setVisibility(View.VISIBLE);
        } else {
            swDCAll.setVisibility(View.GONE);
        }
        iorDCHN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RDCHN()));
        iorDCCN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RDCCN()));
        iorDCHC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RDCHC()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RDCHN())){
            iorDCHN.setVisibility(View.GONE);
            iorDCHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RDCCN())){
            iorDCCN.setVisibility(View.GONE);
            iorDCCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RDCHC())){
            iorDCHC.setVisibility(View.GONE);
            iorDCHCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorDCHNName.setText(gameAllPlayRFTResult.getTeam_h() + " / 和局");
        iorDCCNName.setText(gameAllPlayRFTResult.getTeam_c() + " / 和局");
        iorDCHCName.setText(gameAllPlayRFTResult.getTeam_h() + " / " + gameAllPlayRFTResult.getTeam_c());

    }

    private void sw_WM(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_WM().equals("Y")) {
            swWMAll.setVisibility(View.VISIBLE);
        } else {
            swWMAll.setVisibility(View.GONE);
        }

        iorWMHName.setText(gameAllPlayFTResult.getTeam_h());
        iorWMCName.setText(gameAllPlayFTResult.getTeam_c());

        iorWMH1.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMH1()));
        iorWMH2.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMH2()));
        iorWMH3.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMH3()));
        iorWMHOV.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMHOV()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMH1())){
            iorWMH1.setVisibility(View.GONE);
            iorWMH1H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMH2())){
            iorWMH2.setVisibility(View.GONE);
            iorWMH2H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMH3())){
            iorWMH3.setVisibility(View.GONE);
            iorWMH3H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMHOV())){
            iorWMHOV.setVisibility(View.GONE);
            iorWMHOVH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        iorWMC1.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMC1()));
        iorWMC2.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMC2()));
        iorWMC3.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMC3()));
        iorWMCOV.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMCOV()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMC1())){
            iorWMC1.setVisibility(View.GONE);
            iorWMC1H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMC2())){
            iorWMC2.setVisibility(View.GONE);
            iorWMC2H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMC3())){
            iorWMC3.setVisibility(View.GONE);
            iorWMC3H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMCOV())){
            iorWMCOV.setVisibility(View.GONE);
            iorWMCOVH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        iorWM0.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WM0()));
        iorWMN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WMN()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WM0())){
            iorWM0.setVisibility(View.GONE);
            iorWM0H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WMN())){
            iorWMN.setVisibility(View.GONE);
            iorWMNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RWM(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RWM().equals("Y")) {
            swWMAll.setVisibility(View.VISIBLE);
        } else {
            swWMAll.setVisibility(View.GONE);
        }

        iorWMHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorWMCName.setText(gameAllPlayRFTResult.getTeam_c());
        iorWMH1.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMH1()));
        iorWMH2.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMH2()));
        iorWMH3.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMH3()));
        iorWMHOV.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMHOV()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMH1())){
            iorWMH1.setVisibility(View.GONE);
            iorWMH1H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMH2())){
            iorWMH2.setVisibility(View.GONE);
            iorWMH2H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMH3())){
            iorWMH3.setVisibility(View.GONE);
            iorWMH3H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMHOV())){
            iorWMHOV.setVisibility(View.GONE);
            iorWMHOVH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorWMC1.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMC1()));
        iorWMC2.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMC2()));
        iorWMC3.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMC3()));
        iorWMCOV.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMCOV()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMC1())){
            iorWMC1.setVisibility(View.GONE);
            iorWMC1H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMC2())){
            iorWMC2.setVisibility(View.GONE);
            iorWMC2H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMC3())){
            iorWMC3.setVisibility(View.GONE);
            iorWMC3H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMCOV())){
            iorWMCOV.setVisibility(View.GONE);
            iorWMCOVH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        iorWM0.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWM0()));
        iorWMN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWMN()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWM0())){
            iorWM0.setVisibility(View.GONE);
            iorWM0H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWMN())){
            iorWMN.setVisibility(View.GONE);
            iorWMNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_F(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_F().equals("Y")) {
            swFAll.setVisibility(View.VISIBLE);
        } else {
            swFAll.setVisibility(View.GONE);
        }
        iorFHH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FHH()));
        iorFHN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FHN()));
        iorFHC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FHC()));
        iorFNH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FNH()));
        iorFNN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FNN()));
        iorFNC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FNC()));
        iorFCH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FCH()));
        iorFCN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FCN()));
        iorFCC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_FCC()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FHH())){
            iorFHH.setVisibility(View.GONE);
            iorFHHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FHN())){
            iorFHN.setVisibility(View.GONE);
            iorFHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FHC())){
            iorFHC.setVisibility(View.GONE);
            iorFHCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FNH())){
            iorFNH.setVisibility(View.GONE);
            iorFNHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FNN())){
            iorFNN.setVisibility(View.GONE);
            iorFNNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FNC())){
            iorFNC.setVisibility(View.GONE);
            iorFNCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FCH())){
            iorFCH.setVisibility(View.GONE);
            iorFCHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FCN())){
            iorFCN.setVisibility(View.GONE);
            iorFCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_FCC())){
            iorFCC.setVisibility(View.GONE);
            iorFCCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }


        iorFHHName.setText(gameAllPlayFTResult.getTeam_h() + "/" + gameAllPlayFTResult.getTeam_h());
        iorFHNName.setText(gameAllPlayFTResult.getTeam_h() + "/和局");
        iorFHCName.setText(gameAllPlayFTResult.getTeam_h() + "/" + gameAllPlayFTResult.getTeam_c());

        iorFNHName.setText("和局/" + gameAllPlayFTResult.getTeam_h());
        iorFNNName.setText("和局/和局");
        iorFNCName.setText("和局/" + gameAllPlayFTResult.getTeam_c());

        iorFCHName.setText(gameAllPlayFTResult.getTeam_c() + "/" + gameAllPlayFTResult.getTeam_h());
        iorFCNName.setText(gameAllPlayFTResult.getTeam_c() + "/和局");
        iorFCCName.setText(gameAllPlayFTResult.getTeam_c() + "/" + gameAllPlayFTResult.getTeam_c());

    }


    private void sw_RF(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RF().equals("Y")) {
            swFAll.setVisibility(View.VISIBLE);
        } else {
            swFAll.setVisibility(View.GONE);
        }
        iorFHH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFHH()));
        iorFHN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFHN()));
        iorFHC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFHC()));
        iorFNH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFNH()));
        iorFNN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFNN()));
        iorFNC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFNC()));
        iorFCH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFCH()));
        iorFCN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFCN()));
        iorFCC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RFCC()));

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFHH())){
            iorFHH.setVisibility(View.GONE);
            iorFHHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFHN())){
            iorFHN.setVisibility(View.GONE);
            iorFHNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFHC())){
            iorFHC.setVisibility(View.GONE);
            iorFHCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFNH())){
            iorFNH.setVisibility(View.GONE);
            iorFNHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFNN())){
            iorFNN.setVisibility(View.GONE);
            iorFNNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFNC())){
            iorFNC.setVisibility(View.GONE);
            iorFNCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFCH())){
            iorFCH.setVisibility(View.GONE);
            iorFCHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFCN())){
            iorFCN.setVisibility(View.GONE);
            iorFCNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RFCC())){
            iorFCC.setVisibility(View.GONE);
            iorFCCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        iorFHHName.setText(gameAllPlayRFTResult.getTeam_h() + "/" + gameAllPlayRFTResult.getTeam_h());
        iorFHNName.setText(gameAllPlayRFTResult.getTeam_h() + "/和局");
        iorFHCName.setText(gameAllPlayRFTResult.getTeam_h() + "/" + gameAllPlayRFTResult.getTeam_c());

        iorFNHName.setText("和局/" + gameAllPlayRFTResult.getTeam_h());
        iorFNNName.setText("和局/和局");
        iorFNCName.setText("和局/" + gameAllPlayRFTResult.getTeam_c());

        iorFCHName.setText(gameAllPlayRFTResult.getTeam_c() + "/" + gameAllPlayRFTResult.getTeam_h());
        iorFCNName.setText(gameAllPlayRFTResult.getTeam_c() + "/和局");
        iorFCCName.setText(gameAllPlayRFTResult.getTeam_c() + "/" + gameAllPlayRFTResult.getTeam_c());

    }

    private void sw_SB(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_SB().equals("Y")) {
            swSBAll.setVisibility(View.VISIBLE);
        } else {
            swSBAll.setVisibility(View.GONE);
        }
        iorSBH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_SBH()));
        iorSBC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_SBC()));
        iorSBHName.setText(gameAllPlayFTResult.getTeam_h());
        iorSBCName.setText(gameAllPlayFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_SBH())){
            iorSBH.setVisibility(View.GONE);
            iorSBHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_SBC())){
            iorSBC.setVisibility(View.GONE);
            iorSBCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RSB(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RSB().equals("Y")) {
            swSBAll.setVisibility(View.VISIBLE);
        } else {
            swSBAll.setVisibility(View.GONE);
        }
        iorSBH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RSBH()));
        iorSBC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RSBC()));
        iorSBHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorSBCName.setText(gameAllPlayRFTResult.getTeam_c());
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RSBH())){
            iorSBH.setVisibility(View.GONE);
            iorSBHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RSBC())){
            iorSBC.setVisibility(View.GONE);
            iorSBCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_MG(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_MG().equals("Y")) {
            swMGAll.setVisibility(View.VISIBLE);
        } else {
            swMGAll.setVisibility(View.GONE);
        }
        iorMGH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MGH()));
        iorMGC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MGC()));
        iorMGN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MGN()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MGH())){
            iorMGH.setVisibility(View.GONE);
            iorMGHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MGC())){
            iorMGC.setVisibility(View.GONE);
            iorMGCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MGN())){
            iorMGN.setVisibility(View.GONE);
            iorMGNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RMG(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RMG().equals("Y")) {
            swMGAll.setVisibility(View.VISIBLE);
        } else {
            swMGAll.setVisibility(View.GONE);
        }
        iorMGH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMGH()));
        iorMGC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMGC()));
        iorMGN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMGN()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMGH())){
            iorMGH.setVisibility(View.GONE);
            iorMGHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMGC())){
            iorMGC.setVisibility(View.GONE);
            iorMGCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMGN())){
            iorMGN.setVisibility(View.GONE);
            iorMGNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_HG(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HG().equals("Y")) {
            swHGAll.setVisibility(View.VISIBLE);
        } else {
            swHGAll.setVisibility(View.GONE);
        }
        iorHGH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HGH()));
        iorHGC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HGC()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HGH())){
            iorHGH.setVisibility(View.GONE);
            iorHGHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HGC())){
            iorHGC.setVisibility(View.GONE);
            iorHGCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RHG(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RHG().equals("Y")) {
            swHGAll.setVisibility(View.VISIBLE);
        } else {
            swHGAll.setVisibility(View.GONE);
        }
        iorHGH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RHGH()));
        iorHGC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RHGC()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RHGH())){
            iorHGH.setVisibility(View.GONE);
            iorHGHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RHGC())){
            iorHGC.setVisibility(View.GONE);
            iorHGCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_WN(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_WN().equals("Y")) {
            swWNAll.setVisibility(View.VISIBLE);
        } else {
            swWNAll.setVisibility(View.GONE);
        }
        iorWNH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WNH()));
        iorWNC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WNC()));
        iorWNHName.setText(gameAllPlayFTResult.getTeam_h());
        iorWNCName.setText(gameAllPlayFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WNH())){
            iorWNH.setVisibility(View.GONE);
            iorWNHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WNC())){
            iorWNC.setVisibility(View.GONE);
            iorWNCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RWN(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RWN().equals("Y")) {
            swWNAll.setVisibility(View.VISIBLE);
        } else {
            swWNAll.setVisibility(View.GONE);
        }
        iorWNH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWNH()));
        iorWNC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWNC()));
        iorWNHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorWNCName.setText(gameAllPlayRFTResult.getTeam_c());
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWNH())){
            iorWNH.setVisibility(View.GONE);
            iorWNHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWNC())){
            iorWNC.setVisibility(View.GONE);
            iorWNCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_CS(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_CS().equals("Y")) {
            swCSAll.setVisibility(View.VISIBLE);
        } else {
            swCSAll.setVisibility(View.GONE);
        }
        iorCSH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_CSH()));
        iorCSC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_CSC()));
        iorCSHName.setText(gameAllPlayFTResult.getTeam_h());
        iorCSCName.setText(gameAllPlayFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_CSH())){
            iorCSH.setVisibility(View.GONE);
            iorCSHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_CSC())){
            iorCSC.setVisibility(View.GONE);
            iorCSCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RCS(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RCS().equals("Y")) {
            swCSAll.setVisibility(View.VISIBLE);
        } else {
            swCSAll.setVisibility(View.GONE);
        }
        iorCSH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RCSH()));
        iorCSC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RCSC()));
        iorCSHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorCSCName.setText(gameAllPlayRFTResult.getTeam_c());
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RCSH())){
            iorCSH.setVisibility(View.GONE);
            iorCSHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RCSC())){
            iorCSC.setVisibility(View.GONE);
            iorCSCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }


    private void sw_HEO(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HEO().equals("Y")) {
            swHEOAll.setVisibility(View.VISIBLE);
        } else {
            swHEOAll.setVisibility(View.GONE);
        }
        iorHEOO.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HEOE()));
        iorHEOE.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HEOO()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HEOO())){
            iorHEOO.setVisibility(View.GONE);
            iorHEOOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HEOE())){
            iorHEOE.setVisibility(View.GONE);
            iorHEOEH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_HREO(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HREO().equals("Y")) {
            swHEOAll.setVisibility(View.VISIBLE);
        } else {
            swHEOAll.setVisibility(View.GONE);
        }
        iorHEOO.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HREOE()));
        iorHEOE.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HREOO()));

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HREOO())){
            iorHEOO.setVisibility(View.GONE);
            iorHEOOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HREOE())){
            iorHEOE.setVisibility(View.GONE);
            iorHEOEH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_EO(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_EO().equals("Y")) {
            swEOAll.setVisibility(View.VISIBLE);
        } else {
            swEOAll.setVisibility(View.GONE);
        }
        iorEOO.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_EOO()));
        iorEOE.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_EOE()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_EOO())){
            iorEOO.setVisibility(View.GONE);
            iorEOOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_EOE())){
            iorEOE.setVisibility(View.GONE);
            iorEOEH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_BKEO(GameAllPlayBKResult gameAllPlayBKResult) {
        if (gameAllPlayBKResult.getSw_EO().equals("Y")) {
            swEOAll.setVisibility(View.VISIBLE);
        } else {
            swEOAll.setVisibility(View.GONE);
        }
        swEOName.setText("总分：单/双");
        iorEOO.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_EOO()));
        iorEOE.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_EOE()));

        if(Check.isNumericNull(gameAllPlayBKResult.getIor_EOO())){
            iorEOO.setVisibility(View.GONE);
            iorEOOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_EOE())){
            iorEOE.setVisibility(View.GONE);
            iorEOEH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_RBKEO(GameAllPlayRBKResult gameAllPlayRBKResult) {
        if (gameAllPlayRBKResult.getSw_REO().equals("Y")) {
            swEOAll.setVisibility(View.VISIBLE);
        } else {
            swEOAll.setVisibility(View.GONE);
        }
        swEOName.setText("总分：单/双");
        iorEOO.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_REOO()));
        iorEOE.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_REOE()));
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_REOO())){
            iorEOO.setVisibility(View.GONE);
            iorEOOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_REOE())){
            iorEOE.setVisibility(View.GONE);
            iorEOEH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_REO(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_REO().equals("Y")) {
            swEOAll.setVisibility(View.VISIBLE);
        } else {
            swEOAll.setVisibility(View.GONE);
        }
        iorEOO.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_REOO()));
        iorEOE.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_REOE()));

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_REOO())){
            iorEOO.setVisibility(View.GONE);
            iorEOOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_REOE())){
            iorEOE.setVisibility(View.GONE);
            iorEOEH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_HTS(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HTS().equals("Y")) {
            swHTSAll.setVisibility(View.VISIBLE);
        } else {
            swHTSAll.setVisibility(View.GONE);
        }
        iorHTSY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HTSY()));
        iorHTSN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HTSN()));


        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HTSY())){
            iorHTSY.setVisibility(View.GONE);
            iorHTSYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HTSN())){
            iorHTSN.setVisibility(View.GONE);
            iorHTSNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_TS(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_TS().equals("Y")) {
            swTSAll.setVisibility(View.VISIBLE);
        } else {
            swTSAll.setVisibility(View.GONE);
        }
        iorTSY.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_TSY()));
        iorTSN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_TSN()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_TSY())){
            iorTSY.setVisibility(View.GONE);
            iorTSYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_TSN())){
            iorTSN.setVisibility(View.GONE);
            iorTSNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RTS(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RTS().equals("Y")) {
            swTSAll.setVisibility(View.VISIBLE);
        } else {
            swTSAll.setVisibility(View.GONE);
        }
        iorTSY.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RTSY()));
        iorTSN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RTSN()));
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RTSY())){
            iorTSY.setVisibility(View.GONE);
            iorTSYH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RTSN())){
            iorTSN.setVisibility(View.GONE);
            iorTSNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }


    private void sw_HT(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HT().equals("Y")) {
            swHTAll.setVisibility(View.VISIBLE);
        } else {
            swHTAll.setVisibility(View.GONE);
        }

        iorHT0.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HT0()));
        iorHT1.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HT1()));
        iorHT2.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HT2()));
        iorHTOV.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HTOV()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HT0())){
            iorHT0.setVisibility(View.GONE);
            iorHT0H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HT1())){
            iorHT1.setVisibility(View.GONE);
            iorHT1H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HT2())){
            iorHT2.setVisibility(View.GONE);
            iorHT2H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HTOV())){
            iorHTOV.setVisibility(View.GONE);
            iorHTOVH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_HRT(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HRT().equals("Y")) {
            swHTAll.setVisibility(View.VISIBLE);
        } else {
            swHTAll.setVisibility(View.GONE);
        }

        iorHT0.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRT0()));
        iorHT1.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRT1()));
        iorHT2.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRT2()));
        iorHTOV.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRTOV()));

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRT0())){
            iorHT0.setVisibility(View.GONE);
            iorHT0H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRT1())){
            iorHT1.setVisibility(View.GONE);
            iorHT1H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRT2())){
            iorHT2.setVisibility(View.GONE);
            iorHT2H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRTOV())){
            iorHTOV.setVisibility(View.GONE);
            iorHTOVH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_T(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_T().equals("Y")) {
            swTAll.setVisibility(View.VISIBLE);
        } else {
            swTAll.setVisibility(View.GONE);
        }

        iorT01.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_T01()));
        iorT23.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_T23()));
        iorT46.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_T46()));
        iorOVER.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OVER()));


        if(Check.isNumericNull(gameAllPlayFTResult.getIor_T01())){
            iorT01.setVisibility(View.GONE);
            iorT01H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_T23())){
            iorT23.setVisibility(View.GONE);
            iorT23H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_T46())){
            iorT46.setVisibility(View.GONE);
            iorT46H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_OVER())){
            iorOVER.setVisibility(View.GONE);
            iorOVERH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RT(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RT().equals("Y")) {
            swTAll.setVisibility(View.VISIBLE);
        } else {
            swTAll.setVisibility(View.GONE);
        }

        iorT01.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RT01()));
        iorT23.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RT23()));
        iorT46.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RT46()));
        iorOVER.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_ROVER()));

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RT01())){
            iorT01.setVisibility(View.GONE);
            iorT01H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RT23())){
            iorT23.setVisibility(View.GONE);
            iorT23H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RT46())){
            iorT46.setVisibility(View.GONE);
            iorT46H.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_ROVER())){
            iorOVER.setVisibility(View.GONE);
            iorOVERH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_WB(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_WB().equals("Y")) {
            swWBAll.setVisibility(View.VISIBLE);
        } else {
            swWBAll.setVisibility(View.GONE);
        }
        iorWBH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WBH()));
        iorWBC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WBC()));
        iorWBHName.setText(gameAllPlayFTResult.getTeam_h());
        iorWBCName.setText(gameAllPlayFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WBH())){
            iorWBH.setVisibility(View.GONE);
            iorWBHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WBC())){
            iorWBC.setVisibility(View.GONE);
            iorWBCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RWB(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RWB().equals("Y")) {
            swWBAll.setVisibility(View.VISIBLE);
        } else {
            swWBAll.setVisibility(View.GONE);
        }
        iorWBH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWBH()));
        iorWBC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWBC()));
        iorWBHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorWBCName.setText(gameAllPlayRFTResult.getTeam_c());
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWBH())){
            iorWBH.setVisibility(View.GONE);
            iorWBHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWBC())){
            iorWBC.setVisibility(View.GONE);
            iorWBCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_WE(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_WE().equals("Y")) {
            swWEAll.setVisibility(View.VISIBLE);
        } else {
            swWEAll.setVisibility(View.GONE);
        }
        iorWEH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WEH()));
        iorWEC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_WEC()));
        iorWEHName.setText(gameAllPlayFTResult.getTeam_h());
        iorWECName.setText(gameAllPlayFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WEH())){
            iorWEH.setVisibility(View.GONE);
            iorWEHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_WEC())){
            iorWEC.setVisibility(View.GONE);
            iorWECH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RWE(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RWE().equals("Y")) {
            swWEAll.setVisibility(View.VISIBLE);
        } else {
            swWEAll.setVisibility(View.GONE);
        }
        iorWEH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWEH()));
        iorWEC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RWEC()));
        iorWEHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorWECName.setText(gameAllPlayRFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWEH())){
            iorWEH.setVisibility(View.GONE);
            iorWEHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RWEC())){
            iorWEC.setVisibility(View.GONE);
            iorWECH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_HM(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HM().equals("Y")) {
            swHMAll.setVisibility(View.VISIBLE);
        } else {
            swHMAll.setVisibility(View.GONE);
        }
        iorHMH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HMH()));
        iorHMC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HMC()));
        iorHMN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HMN()));
        iorHMHName.setText(gameAllPlayFTResult.getTeam_h());
        iorHMCName.setText(gameAllPlayFTResult.getTeam_c());


        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HMH())){
            iorHMH.setVisibility(View.GONE);
            iorHMHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HMC())){
            iorHMC.setVisibility(View.GONE);
            iorHMCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HMN())){
            iorHMN.setVisibility(View.GONE);
            iorHMNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_HRM(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HRM().equals("Y")) {
            swHMAll.setVisibility(View.VISIBLE);
        } else {
            swHMAll.setVisibility(View.GONE);
        }
        iorHMH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRMH()));
        iorHMC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRMC()));
        iorHMN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRMN()));
        iorHMHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorHMCName.setText(gameAllPlayRFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRMH())){
            iorHMH.setVisibility(View.GONE);
            iorHMHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRMC())){
            iorHMC.setVisibility(View.GONE);
            iorHMCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRMN())){
            iorHMN.setVisibility(View.GONE);
            iorHMNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_M(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_M().equals("Y")) {
            swMAll.setVisibility(View.VISIBLE);
        } else {
            swMAll.setVisibility(View.GONE);
        }
        iorMH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MH()));
        iorMC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MC()));
        iorMN.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_MN()));
        iorMHName.setText(gameAllPlayFTResult.getTeam_h());
        iorMCName.setText(gameAllPlayFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MH())){
            iorMH.setVisibility(View.GONE);
            iorMHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MC())){
            iorMC.setVisibility(View.GONE);
            iorMCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_MN())){
            iorMN.setVisibility(View.GONE);
            iorMNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_BKM(GameAllPlayBKResult gameAllPlayBKResult) {
        if (gameAllPlayBKResult.getSw_M().equals("Y")) {
            swMAll.setVisibility(View.VISIBLE);
        } else {
            swMAll.setVisibility(View.GONE);
        }
        iorMH.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_MH()));
        iorMC.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_MC()));
        //iorMN.setText(gameAllPlayBKResult.getIor_MN());
        iorMNClick.setVisibility(View.GONE);
        iorMHName.setText(gameAllPlayBKResult.getTeam_h());
        iorMCName.setText(gameAllPlayBKResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayBKResult.getIor_MH())){
            iorMH.setVisibility(View.GONE);
            iorMHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_MC())){
            iorMC.setVisibility(View.GONE);
            iorMCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_BKRM(GameAllPlayRBKResult gameAllPlayRBKResult) {
        if (gameAllPlayRBKResult.getSw_RM().equals("Y")) {
            swMAll.setVisibility(View.VISIBLE);
        } else {
            swMAll.setVisibility(View.GONE);
        }
        iorMH.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_RMH()));
        iorMC.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_RMC()));
        //iorMN.setText(gameAllPlayBKResult.getIor_MN());
        iorMNClick.setVisibility(View.GONE);
        iorMHName.setText(gameAllPlayRBKResult.getTeam_h());
        iorMCName.setText(gameAllPlayRBKResult.getTeam_c());
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_RMH())){
            iorMH.setVisibility(View.GONE);
            iorMHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_RMC())){
            iorMC.setVisibility(View.GONE);
            iorMCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_RM(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RM().equals("Y")) {
            swMAll.setVisibility(View.VISIBLE);
        } else {
            swMAll.setVisibility(View.GONE);
        }
        iorMH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMH()));
        iorMC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMC()));
        iorMN.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_RMN()));
        iorMHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorMCName.setText(gameAllPlayRFTResult.getTeam_c());

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMH())){
            iorMH.setVisibility(View.GONE);
            iorMHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMC())){
            iorMC.setVisibility(View.GONE);
            iorMCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_RMN())){
            iorMN.setVisibility(View.GONE);
            iorMNH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_HOUC(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HOUC().equals("Y")) {
            swHOUCAll.setVisibility(View.VISIBLE);
        } else {
            swHOUCAll.setVisibility(View.GONE);
        }
        iorHOUCO.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HOUCO()));
        iorHOUCU.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HOUCU()));
        ratioHouco.setText(gameAllPlayFTResult.getRatio_houco());
        ratioHoucu.setText(gameAllPlayFTResult.getRatio_houcu());
        swHOUCName.setText("球队进球数 - " + gameAllPlayFTResult.getTeam_c() + " 大/小 上半场");

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HOUCO())){
            iorHOUCO.setVisibility(View.GONE);
            ratioHouco.setVisibility(View.GONE);
            iorHOUCOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HOUCU())){
            iorHOUCU.setVisibility(View.GONE);
            ratioHoucu.setVisibility(View.GONE);
            iorHOUCUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_HRUC(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HRUC().equals("Y")) {
            swHOUCAll.setVisibility(View.VISIBLE);
        } else {
            swHOUCAll.setVisibility(View.GONE);
        }
        iorHOUCO.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRUCO()));
        iorHOUCU.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRUCU()));
        ratioHouco.setText(gameAllPlayRFTResult.getRatio_hruco());
        ratioHoucu.setText(gameAllPlayRFTResult.getRatio_hrucu());
        swHOUCName.setText("球队进球数 - " + gameAllPlayRFTResult.getTeam_c() + " 大/小 上半场");
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRUCO())){
            iorHOUCO.setVisibility(View.GONE);
            ratioHouco.setVisibility(View.GONE);
            iorHOUCOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRUCU())){
            iorHOUCU.setVisibility(View.GONE);
            ratioHoucu.setVisibility(View.GONE);
            iorHOUCUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_HOUH(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HOUH().equals("Y")) {
            swHOUHAll.setVisibility(View.VISIBLE);
        } else {
            swHOUHAll.setVisibility(View.GONE);
        }
        iorHOUHO.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HOUHO()));
        iorHOUHU.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HOUHU()));
        ratioHouho.setText(gameAllPlayFTResult.getRatio_houho());
        ratioHouhu.setText(gameAllPlayFTResult.getRatio_houhu());
        swHOUHName.setText("球队进球数 - " + gameAllPlayFTResult.getTeam_h() + " 大/小 上半场");

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HOUHO())){
            iorHOUHO.setVisibility(View.GONE);
            ratioHouho.setVisibility(View.GONE);
            iorHOUHOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HOUHU())){
            iorHOUHU.setVisibility(View.GONE);
            ratioHouhu.setVisibility(View.GONE);
            iorHOUHUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_HRUH(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HRUH().equals("Y")) {
            swHOUHAll.setVisibility(View.VISIBLE);
        } else {
            swHOUHAll.setVisibility(View.GONE);
        }
        iorHOUHO.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRUHO()));
        iorHOUHU.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HRUHU()));
        ratioHouho.setText(gameAllPlayRFTResult.getRatio_hruho());
        ratioHouhu.setText(gameAllPlayRFTResult.getRatio_hruhu());
        swHOUHName.setText("球队进球数 - " + gameAllPlayRFTResult.getTeam_h() + " 大/小 上半场");

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRUHO())){
            iorHOUHO.setVisibility(View.GONE);
            ratioHouho.setVisibility(View.GONE);
            iorHOUHOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HRUHU())){
            iorHOUHU.setVisibility(View.GONE);
            ratioHouhu.setVisibility(View.GONE);
            iorHOUHUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_OUC(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_OUC().equals("Y")) {
            swOUCAll.setVisibility(View.VISIBLE);
        } else {
            swOUCAll.setVisibility(View.GONE);
        }
        iorOUCO.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OUCO()));
        iorOUCU.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OUCU()));
        ratioOuco.setText(gameAllPlayFTResult.getRatio_ouco());
        ratioOucu.setText(gameAllPlayFTResult.getRatio_oucu());
        swOUCName.setText("球队进球数 - " + gameAllPlayFTResult.getTeam_c() + " 大/小");

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_OUCO())){
            iorOUCO.setVisibility(View.GONE);
            ratioOuco.setVisibility(View.GONE);
            iorOUCOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_OUCU())){
            iorOUCU.setVisibility(View.GONE);
            ratioOucu.setVisibility(View.GONE);
            iorOUCUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_BKOUC(GameAllPlayBKResult gameAllPlayBKResult) {
        if (gameAllPlayBKResult.getSw_OUC().equals("Y")) {
            swOUCAll.setVisibility(View.VISIBLE);
        } else {
            swOUCAll.setVisibility(View.GONE);
        }
        iorOUCO.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_OUCO()));
        iorOUCU.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_OUCU()));
        ratioOuco.setText(gameAllPlayBKResult.getRatio_ouco());
        ratioOucu.setText(gameAllPlayBKResult.getRatio_oucu());
        swOUCName.setText("球队得分 - " + gameAllPlayBKResult.getTeam_c() + " 大/小");
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_OUCO())){
            iorOUCO.setVisibility(View.GONE);
            ratioOuco.setVisibility(View.GONE);
            iorOUCOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_OUCU())){
            iorOUCU.setVisibility(View.GONE);
            ratioOucu.setVisibility(View.GONE);
            iorOUCUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_BKROUC(GameAllPlayRBKResult gameAllPlayRBKResult) {
        if (gameAllPlayRBKResult.getSw_ROUC().equals("Y")) {
            swOUCAll.setVisibility(View.VISIBLE);
        } else {
            swOUCAll.setVisibility(View.GONE);
        }
        iorOUCO.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_ROUCO()));
        iorOUCU.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_ROUCU()));
        ratioOuco.setText(gameAllPlayRBKResult.getRatio_rouco());
        ratioOucu.setText(gameAllPlayRBKResult.getRatio_roucu());
        swOUCName.setText("球队得分 - " + gameAllPlayRBKResult.getTeam_c() + " 大/小");
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_ROUCO())){
            iorOUCO.setVisibility(View.GONE);
            ratioOuco.setVisibility(View.GONE);
            iorOUCOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_ROUCU())){
            iorOUCU.setVisibility(View.GONE);
            ratioOucu.setVisibility(View.GONE);
            iorOUCUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_ROUC(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_ROUC().equals("Y")) {
            swOUCAll.setVisibility(View.VISIBLE);
        } else {
            swOUCAll.setVisibility(View.GONE);
        }
        iorOUCO.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_ROUCO()));
        iorOUCU.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_ROUCU()));
        ratioOuco.setText(gameAllPlayRFTResult.getRatio_rouco());
        ratioOucu.setText(gameAllPlayRFTResult.getRatio_roucu());
        swOUCName.setText("球队进球数 - " + gameAllPlayRFTResult.getTeam_c() + " 大/小");

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_ROUCO())){
            iorOUCO.setVisibility(View.GONE);
            ratioOuco.setVisibility(View.GONE);
            iorOUCOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_ROUCU())){
            iorOUCU.setVisibility(View.GONE);
            ratioOucu.setVisibility(View.GONE);
            iorOUCUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_OUH(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_OUH().equals("Y")) {
            swOUHAll.setVisibility(View.VISIBLE);
        } else {
            swOUHAll.setVisibility(View.GONE);
        }
        iorOUHO.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OUHO()));
        iorOUHU.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OUHU()));
        ratioOuho.setText(gameAllPlayFTResult.getRatio_ouho());
        ratioOuhu.setText(gameAllPlayFTResult.getRatio_ouhu());
        swOUHName.setText("球队进球数 - " + gameAllPlayFTResult.getTeam_h() + " 大/小");


        if(Check.isNumericNull(gameAllPlayFTResult.getIor_OUHO())){
            iorOUHO.setVisibility(View.GONE);
            ratioOuho.setVisibility(View.GONE);
            iorOUHOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_OUHU())){
            iorOUHU.setVisibility(View.GONE);
            ratioOuhu.setVisibility(View.GONE);
            iorOUHUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }


    }

    private void sw_BKOUH(GameAllPlayBKResult gameAllPlayBKResult) {
        if (gameAllPlayBKResult.getSw_OUH().equals("Y")) {
            swOUHAll.setVisibility(View.VISIBLE);
        } else {
            swOUHAll.setVisibility(View.GONE);
        }
        iorOUHO.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_OUHO()));
        iorOUHU.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_OUHU()));
        ratioOuho.setText(gameAllPlayBKResult.getRatio_ouho());
        ratioOuhu.setText(gameAllPlayBKResult.getRatio_ouhu());
        swOUHName.setText("球队得分 - " + gameAllPlayBKResult.getTeam_h() + " 大/小");
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_OUHO())){
            iorOUHO.setVisibility(View.GONE);
            ratioOuho.setVisibility(View.GONE);
            iorOUHOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_OUHU())){
            iorOUHU.setVisibility(View.GONE);
            ratioOuhu.setVisibility(View.GONE);
            iorOUHUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_BKROUH(GameAllPlayRBKResult gameAllPlayRBKResult) {
        if (gameAllPlayRBKResult.getSw_ROUH().equals("Y")) {
            swOUHAll.setVisibility(View.VISIBLE);
        } else {
            swOUHAll.setVisibility(View.GONE);
        }
        iorOUHO.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_ROUHO()));
        iorOUHU.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_ROUHU()));
        ratioOuho.setText(gameAllPlayRBKResult.getRatio_rouho());
        ratioOuhu.setText(gameAllPlayRBKResult.getRatio_rouhu());
        swOUHName.setText("球队得分 - " + gameAllPlayRBKResult.getTeam_h() + " 大/小");
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_ROUHO())){
            iorOUHO.setVisibility(View.GONE);
            ratioOuho.setVisibility(View.GONE);
            iorOUHOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_ROUHU())){
            iorOUHU.setVisibility(View.GONE);
            ratioOuhu.setVisibility(View.GONE);
            iorOUHUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_ROUH(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_ROUH().equals("Y")) {
            swOUHAll.setVisibility(View.VISIBLE);
        } else {
            swOUHAll.setVisibility(View.GONE);
        }
        iorOUHO.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_ROUHO()));
        iorOUHU.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_ROUHU()));
        ratioOuho.setText(gameAllPlayRFTResult.getRatio_rouho());
        ratioOuhu.setText(gameAllPlayRFTResult.getRatio_rouhu());
        swOUHName.setText("球队进球数 - " + gameAllPlayRFTResult.getTeam_h() + " 大/小");

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_ROUHO())){
            iorOUHO.setVisibility(View.GONE);
            ratioOuho.setVisibility(View.GONE);
            iorOUHOH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_ROUHU())){
            iorOUHU.setVisibility(View.GONE);
            ratioOuhu.setVisibility(View.GONE);
            iorOUHUH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_HOU(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HOU().equals("Y")) {
            swHOUAll.setVisibility(View.VISIBLE);
        } else {
            swHOUAll.setVisibility(View.GONE);
        }
        iorHOUH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HOUH()));
        iorHOUC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HOUC()));
        ratioHo.setText(gameAllPlayFTResult.getRatio_ho());
        ratioHu.setText(gameAllPlayFTResult.getRatio_hu());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HOUH())){
            iorHOUH.setVisibility(View.GONE);
            ratioHu.setVisibility(View.GONE);
            iorHOUHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HOUC())){
            iorHOUC.setVisibility(View.GONE);
            ratioHo.setVisibility(View.GONE);
            iorHOUCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_HOU(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HROU().equals("Y")) {
            swHOUAll.setVisibility(View.VISIBLE);
        } else {
            swHOUAll.setVisibility(View.GONE);
        }
        iorHOUH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HROUH()));
        iorHOUC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HROUC()));
        ratioHo.setText(gameAllPlayRFTResult.getRatio_hrouo());
        ratioHu.setText(gameAllPlayRFTResult.getRatio_hrouu());

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HROUH())){
            iorHOUH.setVisibility(View.GONE);
            ratioHu.setVisibility(View.GONE);
            iorHOUHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HROUC())){
            iorHOUC.setVisibility(View.GONE);
            ratioHo.setVisibility(View.GONE);
            iorHOUCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_OU(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_OU().equals("Y")) {
            swOUAll.setVisibility(View.VISIBLE);
        } else {
            swOUAll.setVisibility(View.GONE);
        }

        iorOUH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OUH()));
        iorOUC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_OUC()));
        ratioO.setText(gameAllPlayFTResult.getRatio_o());
        ratioU.setText(gameAllPlayFTResult.getRatio_u());

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_OUH())){
            iorOUH.setVisibility(View.GONE);
            ratioU.setVisibility(View.GONE);
            iorOUHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_OUC())){
            iorOUC.setVisibility(View.GONE);
            ratioO.setVisibility(View.GONE);
            iorOUCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_BKOU(GameAllPlayBKResult gameAllPlayBKResult) {
        if (gameAllPlayBKResult.getSw_OU().equals("Y")) {
            swOUAll.setVisibility(View.VISIBLE);
        } else {
            swOUAll.setVisibility(View.GONE);
        }
        swOUName.setText("总分：大/小");

        iorOUH.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_OUH()));
        iorOUC.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_OUC()));
        ratioO.setText(gameAllPlayBKResult.getRatio_o());
        ratioU.setText(gameAllPlayBKResult.getRatio_u());
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_OUH())){
            iorOUH.setVisibility(View.GONE);
            ratioU.setVisibility(View.GONE);
            iorOUHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_OUC())){
            iorOUC.setVisibility(View.GONE);
            ratioO.setVisibility(View.GONE);
            iorOUCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_RBKOU(GameAllPlayRBKResult gameAllPlayRBKResult) {
        if (gameAllPlayRBKResult.getSw_ROU().equals("Y")) {
            swOUAll.setVisibility(View.VISIBLE);
        } else {
            swOUAll.setVisibility(View.GONE);
        }
        swOUName.setText("总分：大/小");

        iorOUH.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_ROUH()));
        iorOUC.setText(GameShipHelper.formatNumber(gameAllPlayRBKResult.getIor_ROUC()));
        ratioO.setText(gameAllPlayRBKResult.getRatio_rouo());
        ratioU.setText(gameAllPlayRBKResult.getRatio_rouu());
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_ROUH())){
            iorOUH.setVisibility(View.GONE);
            ratioU.setVisibility(View.GONE);
            iorOUHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRBKResult.getIor_ROUC())){
            iorOUC.setVisibility(View.GONE);
            ratioO.setVisibility(View.GONE);
            iorOUCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_ROU(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_ROU().equals("Y")) {
            swOUAll.setVisibility(View.VISIBLE);
        } else {
            swOUAll.setVisibility(View.GONE);
        }

        iorOUH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_ROUH()));
        iorOUC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_ROUC()));
        ratioO.setText(gameAllPlayRFTResult.getRatio_rouo());
        ratioU.setText(gameAllPlayRFTResult.getRatio_rouu());

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_ROUH())){
            iorOUH.setVisibility(View.GONE);
            ratioU.setVisibility(View.GONE);
            iorOUHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_ROUC())){
            iorOUC.setVisibility(View.GONE);
            ratioO.setVisibility(View.GONE);
            iorOUCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }


    }


    private void sw_HRE(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_HRE().equals("Y")) {
            swHR.setVisibility(View.VISIBLE);
        } else {
            swHR.setVisibility(View.GONE);
        }
        iorHRHName.setText(gameAllPlayRFTResult.getTeam_h());
        iorHRCName.setText(gameAllPlayRFTResult.getTeam_c());

        if (gameAllPlayRFTResult.getHstrong().equals("H")) {
            iorHRHRatio.setText(gameAllPlayRFTResult.getRatio_hre());
            iorHRCRatio.setText("");
            iorHRCRatio.setVisibility(View.GONE);
        } else {
            iorHRCRatio.setText(gameAllPlayRFTResult.getRatio_hre());
            iorHRHRatio.setText("");
            iorHRHRatio.setVisibility(View.GONE);
        }
        iorHRH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HREH()));
        iorHRC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_HREC()));

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HREH())){
            iorHRH.setVisibility(View.GONE);
            iorHRHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_HREC())){
            iorHRC.setVisibility(View.GONE);
            iorHRCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_HR(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_HR().equals("Y") && b_sw_HR) {
            swHR.setVisibility(View.VISIBLE);
        } else {
            swHR.setVisibility(View.GONE);
        }
        iorHRHName.setText(gameAllPlayFTResult.getTeam_h());
        iorHRCName.setText(gameAllPlayFTResult.getTeam_c());

        if (gameAllPlayFTResult.getHstrong().equals("H")) {
            iorHRHRatio.setText(gameAllPlayFTResult.getHratio());
            iorHRCRatio.setText("");
            iorHRCRatio.setVisibility(View.GONE);
        } else {
            iorHRCRatio.setText(gameAllPlayFTResult.getHratio());
            iorHRHRatio.setText("");
            iorHRHRatio.setVisibility(View.GONE);
        }
        iorHRH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HRH()));
        iorHRC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_HRC()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HRH())){
            iorHRH.setVisibility(View.GONE);
            iorHRHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_HRC())){
            iorHRC.setVisibility(View.GONE);
            iorHRCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

    }

    private void sw_RE(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RE().equals("Y") && b_sw_R) {
            sw_R.setVisibility(View.VISIBLE);
        } else {
            sw_R.setVisibility(View.GONE);
        }
        tvTeamHRangName.setText(gameAllPlayRFTResult.getTeam_h());
        tvTeamCRangName.setText(gameAllPlayRFTResult.getTeam_c());

        if (gameAllPlayRFTResult.getStrong().equals("H")) {
            tvIorRHRatio.setText(gameAllPlayRFTResult.getRatio_re());
            tvIorRCRatio.setText("");
            tvIorRCRatio.setVisibility(View.GONE);
        } else {
            tvIorRCRatio.setText(gameAllPlayRFTResult.getRatio_re());
            tvIorRHRatio.setText("");
            tvIorRHRatio.setVisibility(View.GONE);
        }
        tvIorRH.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_REH()));
        tvIorRC.setText(GameShipHelper.formatNumber(gameAllPlayRFTResult.getIor_REC()));

        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_REH())){
            tvIorRH.setVisibility(View.GONE);
            iorRHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayRFTResult.getIor_REC())){
            tvIorRC.setVisibility(View.GONE);
            iorRCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_R(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_R().equals("Y") && b_sw_R) {
            sw_R.setVisibility(View.VISIBLE);
        } else {
            sw_R.setVisibility(View.GONE);
        }
        tvTeamHRangName.setText(gameAllPlayFTResult.getTeam_h());
        tvTeamCRangName.setText(gameAllPlayFTResult.getTeam_c());

        if (gameAllPlayFTResult.getStrong().equals("H")) {
            tvIorRHRatio.setText(gameAllPlayFTResult.getRatio());
            tvIorRCRatio.setText("");
            tvIorRCRatio.setVisibility(View.GONE);
        } else {
            tvIorRCRatio.setText(gameAllPlayFTResult.getRatio());
            tvIorRHRatio.setText("");
            tvIorRHRatio.setVisibility(View.GONE);
        }
        tvIorRH.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RH()));
        tvIorRC.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_RC()));
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RH())){
            tvIorRH.setVisibility(View.GONE);
            iorRHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_RC())){
            tvIorRC.setVisibility(View.GONE);
            iorRCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }


    }

    private void sw_BKR(GameAllPlayBKResult gameAllPlayBKResult) {
        if (gameAllPlayBKResult.getSw_R().equals("Y") && b_sw_R) {
            sw_R.setVisibility(View.VISIBLE);
        } else {
            sw_R.setVisibility(View.GONE);
        }
        tvTeamHRangName.setText(gameAllPlayBKResult.getTeam_h());
        tvTeamCRangName.setText(gameAllPlayBKResult.getTeam_c());

        if (gameAllPlayBKResult.getStrong().equals("H")) {
            tvIorRHRatio.setText(gameAllPlayBKResult.getRatio());
            tvIorRCRatio.setText("");
            tvIorRCRatio.setVisibility(View.GONE);
        } else {
            tvIorRCRatio.setText(gameAllPlayBKResult.getRatio());
            tvIorRHRatio.setText("");
            tvIorRHRatio.setVisibility(View.GONE);
        }
        tvIorRH.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_RH()));
        tvIorRC.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_RC()));
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_RH())){
            tvIorRH.setVisibility(View.GONE);
            iorRHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_RC())){
            tvIorRC.setVisibility(View.GONE);
            iorRCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_BKRE(GameAllPlayRBKResult gameAllPlayBKResult) {
        if (gameAllPlayBKResult.getSw_RE().equals("Y") && b_sw_R) {
            sw_R.setVisibility(View.VISIBLE);
        } else {
            sw_R.setVisibility(View.GONE);
        }
        tvTeamHRangName.setText(gameAllPlayBKResult.getTeam_h());
        tvTeamCRangName.setText(gameAllPlayBKResult.getTeam_c());

        if (gameAllPlayBKResult.getStrong().equals("H")) {
            tvIorRHRatio.setText(gameAllPlayBKResult.getRatio_re());
            tvIorRCRatio.setText("");
            tvIorRCRatio.setVisibility(View.GONE);
        } else {
            tvIorRCRatio.setText(gameAllPlayBKResult.getRatio_re());
            tvIorRHRatio.setText("");
            tvIorRHRatio.setVisibility(View.GONE);
        }
        tvIorRH.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_REH()));
        tvIorRC.setText(GameShipHelper.formatNumber(gameAllPlayBKResult.getIor_REC()));
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_REH())){
            tvIorRH.setVisibility(View.GONE);
            iorRHH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayBKResult.getIor_REC())){
            tvIorRC.setVisibility(View.GONE);
            iorRCH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
    }

    private void sw_W3(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_W3().equals("Y")) {
            swW3All.setVisibility(View.VISIBLE);
        } else {
            swW3All.setVisibility(View.GONE);
        }
        iorW3H.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_W3H()));
        iorW3C.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_W3C()));
        iorW3N.setText(GameShipHelper.formatNumber(gameAllPlayFTResult.getIor_W3N()));

        if(Check.isNumericNull(gameAllPlayFTResult.getIor_W3H())){
            iorW3H.setVisibility(View.GONE);
            iorW3HH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_W3C())){
            iorW3C.setVisibility(View.GONE);
            iorW3CH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }
        if(Check.isNumericNull(gameAllPlayFTResult.getIor_W3N())){
            iorW3N.setVisibility(View.GONE);
            iorW3NH.setBackground(getResources().getDrawable(R.mipmap.bet_lock));
        }

        iorW3HRatio.setText(gameAllPlayFTResult.getRatio_w3h());
        iorW3CRatio.setText(gameAllPlayFTResult.getRatio_w3c());
        iorW3NRatio.setText(gameAllPlayFTResult.getRatio_w3n());
        iorW3HName.setText(gameAllPlayFTResult.getTeam_h());
        iorW3CName.setText(gameAllPlayFTResult.getTeam_c());
    }

    private void sw_DUA(GameAllPlayFTResult gameAllPlayFTResult) {
        if (gameAllPlayFTResult.getSw_DUA().equals("Y")||gameAllPlayFTResult.getSw_DUB().equals("Y")||gameAllPlayFTResult.getSw_DUC().equals("Y")||gameAllPlayFTResult.getSw_DUD().equals("Y")) {
            swDUAAll.setVisibility(View.VISIBLE);
        } else {
            swDUAAll.setVisibility(View.GONE);
        }
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
        swDUAShow.setLayoutManager(gridLayoutManager);
        ArrayList<SwDua> myListSWDUA = new ArrayList<SwDua>();

        if (gameAllPlayFTResult.getSw_DUA().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大1.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayFTResult.getIor_DUAHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUAHU();
            sw_duaAll1.order_method = "FT_single";
            sw_duaAll1.rtype_o = "DUAHO";
            sw_duaAll1.rtype_u = "DUAHU";
            sw_duaAll1.wtype = "DUA";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大1.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayFTResult.getIor_DUACO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUACU();
            sw_duaAll2.order_method = "FT_single";
            sw_duaAll2.rtype_o = "DUACO";
            sw_duaAll2.rtype_u = "DUACU";
            sw_duaAll2.wtype = "DUA";
            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/" + gameAllPlayFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大1.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayFTResult.getIor_DUASO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUASU();
            sw_duaAll3.order_method = "FT_single";
            sw_duaAll3.rtype_o = "DUASO";
            sw_duaAll3.rtype_u = "DUASU";
            sw_duaAll3.wtype = "DUA";
            myListSWDUA.add(sw_duaAll3);
        }

        if (gameAllPlayFTResult.getSw_DUB().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大2.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayFTResult.getIor_DUBHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUBHU();
            sw_duaAll1.order_method = "FT_single";
            sw_duaAll1.rtype_o = "DUBHO";
            sw_duaAll1.rtype_u = "DUBHU";
            sw_duaAll1.wtype = "DUB";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大2.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayFTResult.getIor_DUBCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUBCU();
            sw_duaAll2.order_method = "FT_single";
            sw_duaAll2.rtype_o = "DUBCO";
            sw_duaAll2.rtype_u = "DUBCU";
            sw_duaAll2.wtype = "DUB";
            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/" + gameAllPlayFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大2.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayFTResult.getIor_DUBSO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUBSU();
            sw_duaAll3.order_method = "FT_single";
            sw_duaAll3.rtype_o = "DUBSO";
            sw_duaAll3.rtype_u = "DUBSU";
            sw_duaAll3.wtype = "DUB";
            myListSWDUA.add(sw_duaAll3);
        }

        if (gameAllPlayFTResult.getSw_DUC().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大3.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayFTResult.getIor_DUCHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUCHU();
            sw_duaAll1.order_method = "FT_single";
            sw_duaAll1.rtype_o = "DUCHO";
            sw_duaAll1.rtype_u = "DUCHU";
            sw_duaAll1.wtype = "DUC";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大3.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayFTResult.getIor_DUCCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUCCU();
            sw_duaAll2.order_method = "FT_single";
            sw_duaAll2.rtype_o = "DUCCO";
            sw_duaAll2.rtype_u = "DUCCU";
            sw_duaAll2.wtype = "DUC";
            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/" + gameAllPlayFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大3.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayFTResult.getIor_DUCSO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUCSU();
            sw_duaAll3.order_method = "FT_single";
            sw_duaAll3.rtype_o = "DUCSO";
            sw_duaAll3.rtype_u = "DUCSU";
            sw_duaAll3.wtype = "DUC";
            myListSWDUA.add(sw_duaAll3);
        }

        if (gameAllPlayFTResult.getSw_DUD().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大4.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayFTResult.getIor_DUDHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUDHU();
            sw_duaAll1.order_method = "FT_single";
            sw_duaAll1.rtype_o = "DUDHO";
            sw_duaAll1.rtype_u = "DUDHU";
            sw_duaAll1.wtype = "DUD";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大4.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayFTResult.getIor_DUDCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUDCU();
            sw_duaAll2.order_method = "FT_single";
            sw_duaAll2.rtype_o = "DUDCO";
            sw_duaAll2.rtype_u = "DUDCU";
            sw_duaAll2.wtype = "DUD";
            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayFTResult.getTeam_h() + "\n/" + gameAllPlayFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大4.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayFTResult.getIor_DUDSO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayFTResult.getIor_DUDSU();
            sw_duaAll3.order_method = "FT_single";
            sw_duaAll3.rtype_o = "DUDSO";
            sw_duaAll3.rtype_u = "DUDSU";
            sw_duaAll3.wtype = "DUD";
            myListSWDUA.add(sw_duaAll3);
        }
        swDUAShow.setHasFixedSize(true);
        swDUAShow.setNestedScrollingEnabled(false);
        //swDUAShow.addItemDecoration(new GridRvItemDecoration(getContext()));
        swDUAShow.setAdapter(new SwDualistAdapter(getContext(), R.layout.item_sw_dua, myListSWDUA));

    }


    private void sw_RDUA(GameAllPlayRFTResult gameAllPlayRFTResult) {
        GameLog.log("sw_RDUA开关 "+gameAllPlayRFTResult.getSw_RDUA());
        if (gameAllPlayRFTResult.getSw_RDUA().equals("Y")||gameAllPlayRFTResult.getSw_RDUB().equals("Y")||gameAllPlayRFTResult.getSw_RDUC().equals("Y")||gameAllPlayRFTResult.getSw_RDUD().equals("Y")) {
            swDUAAll.setVisibility(View.VISIBLE);
        } else {
            swDUAAll.setVisibility(View.GONE);
        }
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
        swDUAShow.setLayoutManager(gridLayoutManager);
        ArrayList<SwDua> myListSWDUA = new ArrayList<SwDua>();

        if (gameAllPlayRFTResult.getSw_RDUA().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大1.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUAHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUAHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RDUAHO";
            sw_duaAll1.rtype_u = "RDUAHU";
            sw_duaAll1.wtype = "RDUA";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大1.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUACO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUACU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RDUACO";
            sw_duaAll2.rtype_u = "RDUACU";
            sw_duaAll2.wtype = "RDUA";
            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/" + gameAllPlayRFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大1.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUASO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUASU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RDUASO";
            sw_duaAll3.rtype_u = "RDUASU";
            sw_duaAll3.wtype = "RDUA";
            myListSWDUA.add(sw_duaAll3);
        }

        if (gameAllPlayRFTResult.getSw_RDUB().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大2.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUBHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUBHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RDUBHO";
            sw_duaAll1.rtype_u = "RDUBHO";
            sw_duaAll1.wtype = "RDUB";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大2.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUBCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUBCU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RDUBCO";
            sw_duaAll2.rtype_u = "RDUBCU";
            sw_duaAll2.wtype = "RDUB";

            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/" + gameAllPlayRFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大2.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUBSO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUBSU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RDUBSO";
            sw_duaAll3.rtype_u = "RDUBSU";
            sw_duaAll3.wtype = "RDUB";
            myListSWDUA.add(sw_duaAll3);
        }

        if (gameAllPlayRFTResult.getSw_RDUC().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大3.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUCHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUCHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RDUCHO";
            sw_duaAll1.rtype_u = "RDUCHU";
            sw_duaAll1.wtype = "RDUC";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大3.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUCCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUCCU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RDUCCO";
            sw_duaAll2.rtype_u = "RDUCCU";
            sw_duaAll2.wtype = "RDUC";
            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/" + gameAllPlayRFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大3.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUCSO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUCSU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RDUCSO";
            sw_duaAll3.rtype_u = "RDUCSU";
            sw_duaAll3.wtype = "RDUC";
            myListSWDUA.add(sw_duaAll3);
        }

        if (gameAllPlayRFTResult.getSw_RDUD().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/和局";
            sw_duaAll1.ior_DUA_da_Name = "大4.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUDHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUDHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RDUDHO";
            sw_duaAll1.rtype_u = "RDUDHU";
            sw_duaAll1.wtype = "RDUD";
            myListSWDUA.add(sw_duaAll1);
            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c() + "\n/和局";
            sw_duaAll2.ior_DUA_da_Name = "大4.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUDCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUDCU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RDUDCO";
            sw_duaAll2.rtype_u = "RDUDCU";
            sw_duaAll2.wtype = "RDUD";
            myListSWDUA.add(sw_duaAll2);
            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h() + "\n/" + gameAllPlayRFTResult.getTeam_c();
            sw_duaAll3.ior_DUA_da_Name = "大4.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RDUDSO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RDUDSU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RDUDSO";
            sw_duaAll3.rtype_u = "RDUDSU";
            sw_duaAll3.wtype = "RDUD";
            myListSWDUA.add(sw_duaAll3);
        }
        swDUAShow.setHasFixedSize(true);
        swDUAShow.setNestedScrollingEnabled(false);
        //swDUAShow.addItemDecoration(new GridRvItemDecoration(getContext()));
        swDUAShow.setAdapter(new SwDualistAdapter(getContext(), R.layout.item_sw_dua, myListSWDUA));

    }

    private void sw_RMU(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RMUA().equals("Y")||gameAllPlayRFTResult.getSw_RMUB().equals("Y")||gameAllPlayRFTResult.getSw_RMUC().equals("Y")||gameAllPlayRFTResult.getSw_RMUD().equals("Y")) {
            swRMUAll.setVisibility(View.VISIBLE);
        } else {
            swRMUAll.setVisibility(View.GONE);
        }
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 3, OrientationHelper.VERTICAL, false);
        swRMUShow.setLayoutManager(gridLayoutManager);
        ArrayList<SwDua> myListSWDUA = new ArrayList<SwDua>();

        if (gameAllPlayRFTResult.getSw_RMUA().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h();
            sw_duaAll1.ior_DUA_da_Name = "大1.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUAHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUAHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RMUAHO";
            sw_duaAll1.rtype_u = "RMUAHU";
            sw_duaAll1.wtype = "RMUA";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = "和局";
            sw_duaAll3.ior_DUA_da_Name = "大1.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUANO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUANU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RMUANO";
            sw_duaAll3.rtype_u = "RMUANU";
            sw_duaAll3.wtype = "RMUA";
            myListSWDUA.add(sw_duaAll3);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c();
            sw_duaAll2.ior_DUA_da_Name = "大1.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUACO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUACU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RMUACO";
            sw_duaAll2.rtype_u = "RMUACU";
            sw_duaAll2.wtype = "RMUA";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RMUB().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h();
            sw_duaAll1.ior_DUA_da_Name = "大2.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUBHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUBHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RMUBHO";
            sw_duaAll1.rtype_u = "RMUBHU";
            sw_duaAll1.wtype = "RMUB";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = "和局";
            sw_duaAll3.ior_DUA_da_Name = "大2.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUBNO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUBNU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RMUBNO";
            sw_duaAll3.rtype_u = "RMUBNU";
            sw_duaAll3.wtype = "RMUB";
            myListSWDUA.add(sw_duaAll3);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c();
            sw_duaAll2.ior_DUA_da_Name = "大2.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUBCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUBCU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RMUBCO";
            sw_duaAll2.rtype_u = "RMUBCU";
            sw_duaAll2.wtype = "RMUB";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RMUC().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h();
            sw_duaAll1.ior_DUA_da_Name = "大3.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUCHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUCHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RMUCHO";
            sw_duaAll1.rtype_u = "RMUCHU";
            sw_duaAll1.wtype = "RMUC";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = "和局";
            sw_duaAll3.ior_DUA_da_Name = "大3.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUCNO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUCNU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RMUCNO";
            sw_duaAll3.rtype_u = "RMUCNU";
            sw_duaAll3.wtype = "RMUC";
            myListSWDUA.add(sw_duaAll3);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c();
            sw_duaAll2.ior_DUA_da_Name = "大3.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUCCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUCCU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RMUCCO";
            sw_duaAll2.rtype_u = "RMUCCU";
            sw_duaAll2.wtype = "RMUC";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RMUD().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = gameAllPlayRFTResult.getTeam_h();
            sw_duaAll1.ior_DUA_da_Name = "大4.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUDHO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUDHU();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RMUDHO";
            sw_duaAll1.rtype_u = "RMUDHU";
            sw_duaAll1.wtype = "RMUD";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll3 = new SwDua();
            sw_duaAll3.ior_DUA_Name = "和局";
            sw_duaAll3.ior_DUA_da_Name = "大4.5";
            sw_duaAll3.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll3.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUDNO();
            sw_duaAll3.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUDNU();
            sw_duaAll3.order_method = "FT_rsingle";
            sw_duaAll3.rtype_o = "RMUDNO";
            sw_duaAll3.rtype_u = "RMUDNU";
            sw_duaAll3.wtype = "RMUD";
            myListSWDUA.add(sw_duaAll3);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = gameAllPlayRFTResult.getTeam_c();
            sw_duaAll2.ior_DUA_da_Name = "大4.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RMUDCO();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RMUDCU();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RMUDCO";
            sw_duaAll2.rtype_u = "RMUDCU";
            sw_duaAll2.wtype = "RMUD";
            myListSWDUA.add(sw_duaAll2);

        }
        swRMUShow.setHasFixedSize(true);
        swRMUShow.setNestedScrollingEnabled(false);
        //swDUAShow.addItemDecoration(new GridRvItemDecoration(getContext()));
        swRMUShow.setAdapter(new SwDualistAdapter(getContext(), R.layout.item_sw_dua, myListSWDUA));

    }

    private void sw_RUT(GameAllPlayRFTResult gameAllPlayRFTResult) {
        if (gameAllPlayRFTResult.getSw_RUTA().equals("Y")||gameAllPlayRFTResult.getSw_RUTB().equals("Y")||gameAllPlayRFTResult.getSw_RUTC().equals("Y")||gameAllPlayRFTResult.getSw_RUTD().equals("Y")) {
            swRUTAll.setVisibility(View.VISIBLE);
        } else {
            swRUTAll.setVisibility(View.GONE);
        }
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
        swRUTShow.setLayoutManager(gridLayoutManager);
        ArrayList<SwDua> myListSWDUA = new ArrayList<SwDua>();

        if (gameAllPlayRFTResult.getSw_RUTA().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "是";
            sw_duaAll1.ior_DUA_da_Name = "大1.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTAOY();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTAUY();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUTAOY";
            sw_duaAll1.rtype_u = "RUTAUY";
            sw_duaAll1.wtype = "RUTA";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "不是";
            sw_duaAll2.ior_DUA_da_Name = "大1.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTAON();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTAUN();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUTAON";
            sw_duaAll2.rtype_u = "RUTAUN";
            sw_duaAll2.wtype = "RUTA";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RUTB().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "是";
            sw_duaAll1.ior_DUA_da_Name = "大2.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTBOY();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTBUY();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUTBOY";
            sw_duaAll1.rtype_u = "RUTBUY";
            sw_duaAll1.wtype = "RUTB";
            myListSWDUA.add(sw_duaAll1);


            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "不是";
            sw_duaAll2.ior_DUA_da_Name = "大2.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTBON();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTBUN();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUTBON";
            sw_duaAll2.rtype_u = "RUTBUN";
            sw_duaAll2.wtype = "RUTB";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RUTC().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "是";
            sw_duaAll1.ior_DUA_da_Name = "大3.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTCOY();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTCUY();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUTCOY";
            sw_duaAll1.rtype_u = "RUTCUY";
            sw_duaAll1.wtype = "RUTC";
            myListSWDUA.add(sw_duaAll1);


            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "不是";
            sw_duaAll2.ior_DUA_da_Name = "大3.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTCON();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTCUN();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUTCON";
            sw_duaAll2.rtype_u = "RUTCUN";
            sw_duaAll2.wtype = "RUTC";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RUTD().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "是";
            sw_duaAll1.ior_DUA_da_Name = "大4.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTDOY();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTDUY();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUTDOY";
            sw_duaAll1.rtype_u = "RUTDUY";
            sw_duaAll1.wtype = "RUTD";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "不是";
            sw_duaAll2.ior_DUA_da_Name = "大4.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUTDON();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUTDUN();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUTDON";
            sw_duaAll2.rtype_u = "RUTDUN";
            sw_duaAll2.wtype = "RUTD";
            myListSWDUA.add(sw_duaAll2);

        }
        swRUTShow.setHasFixedSize(true);
        swRUTShow.setNestedScrollingEnabled(false);
        //swDUAShow.addItemDecoration(new GridRvItemDecoration(getContext()));
        swRUTShow.setAdapter(new SwDualistAdapter(getContext(), R.layout.item_sw_dua, myListSWDUA));

    }

    private void sw_RUE(GameAllPlayRFTResult gameAllPlayRFTResult) {
        GameLog.log("开关 sw_RUE "+gameAllPlayRFTResult.getSw_RUEA());
        if (gameAllPlayRFTResult.getSw_RUEA().equals("Y")||gameAllPlayRFTResult.getSw_RUEB().equals("Y")||gameAllPlayRFTResult.getSw_RUEC().equals("Y")||gameAllPlayRFTResult.getSw_RUED().equals("Y")) {
            swRUEAll.setVisibility(View.VISIBLE);
        } else {
            swRUEAll.setVisibility(View.GONE);
        }
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(), 2, OrientationHelper.VERTICAL, false);
        swRUEShow.setLayoutManager(gridLayoutManager);
        ArrayList<SwDua> myListSWDUA = new ArrayList<SwDua>();

        if (gameAllPlayRFTResult.getSw_RUEA().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "单";
            sw_duaAll1.ior_DUA_da_Name = "大1.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUEAOO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUEAUO();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUEAOO";
            sw_duaAll1.rtype_u = "RUEAUO";
            sw_duaAll1.wtype = "RUEA";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "双";
            sw_duaAll2.ior_DUA_da_Name = "大1.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小1.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUEAOE();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUEAUE();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUEAOE";
            sw_duaAll2.rtype_u = "RUEAUE";
            sw_duaAll2.wtype = "RUEA";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RUEB().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "单";
            sw_duaAll1.ior_DUA_da_Name = "大2.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUEBOO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUEBUO();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUEBOO";
            sw_duaAll1.rtype_u = "RUEBUO";
            sw_duaAll1.wtype = "RUEB";
            myListSWDUA.add(sw_duaAll1);


            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "双";
            sw_duaAll2.ior_DUA_da_Name = "大2.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小2.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUEBOE();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUEBUE();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUEBOE";
            sw_duaAll2.rtype_u = "RUEBUE";
            sw_duaAll2.wtype = "RUEB";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RUEC().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "单";
            sw_duaAll1.ior_DUA_da_Name = "大3.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUECOO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUECUO();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUECOO";
            sw_duaAll1.rtype_u = "RUECUO";
            sw_duaAll1.wtype = "RUEC";
            myListSWDUA.add(sw_duaAll1);


            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "双";
            sw_duaAll2.ior_DUA_da_Name = "大3.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小3.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUECOE();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUECUE();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUECOE";
            sw_duaAll2.rtype_u = "RUECUE";
            sw_duaAll2.wtype = "RUEC";
            myListSWDUA.add(sw_duaAll2);

        }

        if (gameAllPlayRFTResult.getSw_RUED().equals("Y")) {
            SwDua sw_duaAll1 = new SwDua();
            sw_duaAll1.ior_DUA_Name = "单";
            sw_duaAll1.ior_DUA_da_Name = "大4.5";
            sw_duaAll1.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll1.ior_DUA_da = gameAllPlayRFTResult.getIor_RUEDOO();
            sw_duaAll1.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUEDUO();
            sw_duaAll1.order_method = "FT_rsingle";
            sw_duaAll1.rtype_o = "RUEDOO";
            sw_duaAll1.rtype_u = "RUEDUO";
            sw_duaAll1.wtype = "RUED";
            myListSWDUA.add(sw_duaAll1);

            SwDua sw_duaAll2 = new SwDua();
            sw_duaAll2.ior_DUA_Name = "双";
            sw_duaAll2.ior_DUA_da_Name = "大4.5";
            sw_duaAll2.ior_DUA_xiao_Name = "小4.5";
            sw_duaAll2.ior_DUA_da = gameAllPlayRFTResult.getIor_RUEDOE();
            sw_duaAll2.ior_DUA_xiao = gameAllPlayRFTResult.getIor_RUEDUE();
            sw_duaAll2.order_method = "FT_rsingle";
            sw_duaAll2.rtype_o = "RUEDOE";
            sw_duaAll2.rtype_u = "RUEDUE";
            sw_duaAll2.wtype = "RUED";
            myListSWDUA.add(sw_duaAll2);

        }
        swRUEShow.setHasFixedSize(true);
        swRUEShow.setNestedScrollingEnabled(false);
        //swDUAShow.addItemDecoration(new GridRvItemDecoration(getContext()));
        swRUEShow.setAdapter(new SwDualistAdapter(getContext(), R.layout.item_sw_dua, myListSWDUA));

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
            //GameLog.log("当前位置是：" + position + " 名字是 " + swPDMD2TG.ior_H_down);
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
                        GameLog.log("orderType " + orderType +"ior_H_up " + swPDMD2TG.ior_H_up + " ior_H_down " + swPDMD2TG.ior_H_down + " order_method:" + swPDMD2TG.order_method + " rtype:" + swPDMD2TG.rtype + " wtype:" + swPDMD2TG.wtype);
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
                    onPrepareBetClick();
                }
            });
        }
    }

    //球队得分：主队/客队 -最后一位
    class SwBKPDlistAdapter extends AutoSizeRVAdapter<SwPDMD2TG> {
        private Context context;

        public SwBKPDlistAdapter(Context context, int layoutId, List<SwPDMD2TG> datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final SwPDMD2TG swPDMD2TG, final int position) {
            GameLog.log("当前位置是：" + position + " 名字是 " + swPDMD2TG.ior_H_up);
            holder.setText(R.id.ior_PD_Name, swPDMD2TG.ior_H_up);
            holder.setText(R.id.ior_PD_ratio, GameShipHelper.formatNumber(swPDMD2TG.ior_H_down));
            if (Check.isNumericNull(swPDMD2TG.ior_H_down)) {
                holder.setVisible(R.id.ior_PD_ratio, false);
                holder.setBackgroundRes(R.id.ior_PD_bg, R.mipmap.bet_lock);
            } else {
                holder.setBackgroundRes(R.id.ior_PD_bg, R.drawable.wanfa_item_default);
            }

            holder.setOnClickListener(R.id.ior_PD_click, new View.OnClickListener() {

                @Override
                public void onClick(View view) {
                    if (Check.isNumericNull(swPDMD2TG.ior_H_down)) {
                        //showMessage("数据为空");
                        // GameLog.log(""+swDua.ior_DUA_Name);
                        return;
                    } else {
                        GameLog.log("ior_H_up " + swPDMD2TG.ior_H_up + " ior_H_down " + swPDMD2TG.ior_H_down + " order_method:" + swPDMD2TG.order_method + " rtype:" + swPDMD2TG.rtype + " wtype:" + swPDMD2TG.wtype);
                    }
                    order_method = swPDMD2TG.order_method;
                    rtype = swPDMD2TG.rtype;
                    wtype = swPDMD2TG.wtype;
                    type = "";
                    onPrepareBetClick();
                }
            });
        }
    }

    //双重机会&进球 大/小
    class SwDualistAdapter extends AutoSizeRVAdapter<SwDua> {
        private Context context;

        public SwDualistAdapter(Context context, int layoutId, List<SwDua> datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final SwDua swDua, final int position) {
            GameLog.log("当前位置是：" + position + " 名字是 " + swDua.ior_DUA_Name);
            holder.setText(R.id.sw_dua_name, swDua.ior_DUA_Name);
            holder.setText(R.id.sw_dua_da_up, swDua.ior_DUA_da_Name);
            holder.setText(R.id.sw_dua_da_down, GameShipHelper.formatNumber(swDua.ior_DUA_da));
            holder.setText(R.id.sw_dua_xiao_up, swDua.ior_DUA_xiao_Name);
            holder.setText(R.id.sw_dua_xiao_down, GameShipHelper.formatNumber(swDua.ior_DUA_xiao));
            if (Check.isNumericNull(swDua.ior_DUA_da)) {
                holder.setVisible(R.id.sw_dua_da_up, false);
                holder.setVisible(R.id.sw_dua_da_down, false);
                holder.setBackgroundRes(R.id.sw_dua_da_click, R.mipmap.bet_lock);
            } else {
                holder.setBackgroundRes(R.id.sw_dua_da_click, R.drawable.wanfa_item_default);
            }
            if (Check.isNumericNull(swDua.ior_DUA_xiao)) {
                holder.setVisible(R.id.sw_dua_xiao_up, false);
                holder.setVisible(R.id.sw_dua_xiao_down, false);
                holder.setBackgroundRes(R.id.sw_dua_xiao_click, R.mipmap.bet_lock);
            } else {
                holder.setBackgroundRes(R.id.sw_dua_xiao_click, R.drawable.wanfa_item_default);
            }
            holder.setOnClickListener(R.id.sw_dua_da_click_all, new View.OnClickListener() {

                @Override
                public void onClick(View view) {
                    if (Check.isNumericNull(swDua.ior_DUA_da)) {
                        //showMessage("数据为空");
                        // GameLog.log(""+swDua.ior_DUA_Name);
                        return;
                    } else {
                        GameLog.log("order_method [" + swDua.order_method + "] rtype_o [" + swDua.rtype_o + "] wtype " + swDua.wtype + "] " + swDua.ior_DUA_da_Name + " " + swDua.ior_DUA_da);
                    }
                    if(fromType.equals("1")){
                        //全场
                        orderType = "FT_order_re";
                    }

                    order_method = swDua.order_method;
                    rtype = swDua.rtype_o;
                    wtype = swDua.wtype;
                    type = "";
                    onPrepareBetClick();
                }
            });
            holder.setOnClickListener(R.id.sw_dua_xiao_click_all, new View.OnClickListener() {

                @Override
                public void onClick(View view) {
                    if (Check.isNumericNull(swDua.ior_DUA_xiao)) {
                        //showMessage("数据为空");
                        // GameLog.log(""+swDua.ior_DUA_Name);
                        return;
                    } else {
                        GameLog.log("order_method [" + swDua.order_method + "] rtype_u [" + swDua.rtype_u + "] wtype " + swDua.wtype + "] " + swDua.ior_DUA_xiao_Name + " " + swDua.ior_DUA_xiao);
                    }
                    if(fromType.equals("1")){
                        //全场
                        orderType = "FT_order_re";
                    }
                    order_method = swDua.order_method;
                    rtype = swDua.rtype_u;
                    wtype = swDua.wtype;
                    type = "";
                    onPrepareBetClick();
                }
            });
        }
    }

    @Override
    public void postGameAllBetsResult(GameAllPlayRBKResult gameAllBetsResult) {

        GameLog.log("所有篮球玩法的接口：" + gameAllBetsResult.toString());
    }

    @Override
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

    @Override
    public void postBetApiResult(BetResult betResult) {

    }

    @Override
    public void postBetApiFailResult(String message) {

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        // TODO: inflate a fragment view
        View rootView = super.onCreateView(inflater, container, savedInstanceState);
        unbinder = ButterKnife.bind(this, rootView);
        return rootView;
    }


    //用来展示视图是否显示
    private void onShowDown(boolean isShow, LinearLayout showView, ImageView markRight) {
        if (!isShow) {
            isShow = true;
            showView.setVisibility(View.VISIBLE);
            markRight.setBackgroundResource(R.mipmap.icon_ex_down);
        } else {
            isShow = false;
            showView.setVisibility(View.GONE);
            markRight.setBackgroundResource(R.mipmap.deposit_right);
        }
    }


    //下注之前的准备动作  你懂的 神来之笔 哈哈哈
    private void onPrepareBetClick() {
        GameLog.log(" 购买之前的下注准备数据：\norder_method [" + order_method + "] gid [" + gid + "] type [" + type + "] wtype [" + wtype + "] rtype [" + rtype + "]");
        presenter.postPrepareBetApi("", order_method, gid, type, wtype, rtype, "", "", "");
    }


    @OnClick({R.id.rlRang, R.id.rlTeamHRang, R.id.sw_HR_Click, R.id.ior_HRH_Click, R.id.ior_HRC_Click,
            R.id.sw_OU_Click, R.id.ior_OUC_Click, R.id.ior_OUH_Click, R.id.sw_HOU_Click, R.id.ior_HOUC_Click, R.id.ior_HOUH_Click,
            R.id.sw_OUH_Click, R.id.ior_OUHO_Click, R.id.ior_OUHU_Click, R.id.sw_OUC_Click, R.id.ior_OUCO_Click, R.id.ior_OUCU_Click,
            R.id.sw_HOUH_Click, R.id.ior_HOUHO_Click, R.id.ior_HOUHU_Click, R.id.sw_HOUC_Click, R.id.ior_HOUCO_Click, R.id.ior_HOUCU_Click,
            R.id.rlTeamCRang, R.id.sw_M_click, R.id.ior_MH_click, R.id.ior_MC_click, R.id.ior_MN_click, R.id.sw_HM_click, R.id.ior_HMH_click, R.id.ior_HMC_click, R.id.ior_HMN_click,
            R.id.sw_WE_Click, R.id.ior_WEH_Click, R.id.ior_WEC_Click, R.id.sw_WB_Click, R.id.ior_WBH_Click, R.id.ior_WBC_Click,
            R.id.sw_T_Click, R.id.ior_T01_Click, R.id.ior_T23_Click, R.id.ior_T46_Click, R.id.ior_OVER_Click, R.id.sw_HT_Click, R.id.ior_HT01_Click, R.id.ior_HT1_Click, R.id.ior_HT2_Click, R.id.ior_HTOV_Click,
            R.id.sw_TS_Click, R.id.ior_TSY_Click, R.id.ior_TSN_Click, R.id.sw_HTS_Click, R.id.ior_HTSY_Click, R.id.ior_HTSN_Click,
            R.id.sw_EO_Click, R.id.ior_EOO_Click, R.id.ior_EOE_Click, R.id.sw_HEO_Click, R.id.ior_HEOE_Click, R.id.ior_HEOO_Click,
            R.id.sw_CS_Click, R.id.ior_CSH_Click, R.id.ior_CSC_Click, R.id.sw_WN_Click, R.id.ior_WNH_Click, R.id.ior_WNC_Click,
            R.id.sw_HG_Click, R.id.ior_HGH_Click, R.id.ior_HGC_Click, R.id.sw_MG_Click, R.id.ior_MGH_Click, R.id.ior_MGC_Click, R.id.ior_MGN_Click,
            R.id.sw_SB_Click, R.id.ior_SBH_Click, R.id.ior_SBC_Click,
            R.id.sw_F_Click, R.id.ior_FHH_Click, R.id.ior_FHN_Click, R.id.ior_FHC_Click, R.id.ior_FNH_Click, R.id.ior_FNN_Click, R.id.ior_FNC_Click, R.id.ior_FCH_Click, R.id.ior_FCN_Click, R.id.ior_FCC_Click,
            R.id.sw_WM_Click, R.id.ior_WMH1_Click, R.id.ior_WMH2_Click, R.id.ior_WMH3_Click, R.id.ior_WMHOV_Click, R.id.ior_WMC1_Click, R.id.ior_WMC2_Click, R.id.ior_WMC3_Click, R.id.ior_WMCOV_Click, R.id.ior_WM0_Click, R.id.ior_WMN_Click,
            R.id.sw_DC_click, R.id.ior_DCHN_click, R.id.ior_DCCN_click, R.id.ior_DCHC_click,
            R.id.sw_MTS_Click, R.id.ior_MTSHY_Click, R.id.ior_MTSHN_Click, R.id.ior_MTSNY_Click, R.id.ior_MTSNN_Click, R.id.ior_MTSCY_Click, R.id.ior_MTSCN_Click,
            R.id.sw_DS_Click, R.id.ior_DSHY_Click, R.id.ior_DSHN_Click, R.id.ior_DSCY_Click, R.id.ior_DSCN_Click, R.id.ior_DSSY_Click, R.id.ior_DSSN_Click,
            R.id.sw_DUA_Click,
            R.id.sw_W3_click, R.id.ior_W3H_click, R.id.ior_W3C_click, R.id.ior_W3N_click,
            R.id.sw_PD_Click, R.id.ior_OVH_Click,
            R.id.sw_HPD_Click, R.id.ior_HOVH_Click,
            R.id.sw_RMU_Click, R.id.sw_RUT_Click, R.id.sw_RUE_Click,
            R.id.sw_BK_PD_H_Click, R.id.sw_BK_PD_C_Click,
            R.id.tvBetEventRefresh,R.id.imprepareBetTop,
    })
    public void onViewClicked(View view) {
        win_radio_r_h = "close";
        switch (view.getId()) {
            case R.id.imprepareBetTop:
                prepareBetTop.post(new Runnable() {
                    @Override
                    public void run() {
                        //prepareBetTop.computeScroll();
                        GameLog.log("滑动的位置是"+swALL.getBottom());
                        //prepareBetTop.scrollTo(swALL.getBottom(),0 );
                        prepareBetTop.smoothScrollTo(0, 0);
                        //EventBus.getDefault().post(new LoadAgainEvent());
                        //prepareBetTop.onNestedScroll(swALL,0,swALL.getBottom(),0,0 );
                    }
                });

                break;
            case R.id.tvBetEventRefresh:
                ivBetEventRefresh.startAnimation(animation);
                onPostGameData();
                break;
            case R.id.sw_BK_PD_H_Click://球队得分：主队 -最后一位数
                if (!b_sw_PD_H) {
                    b_sw_PD_H = true;
                    swBKPDHShow.setVisibility(View.VISIBLE);
                    swBKPDHMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_PD_H = false;
                    swBKPDHShow.setVisibility(View.GONE);
                    swBKPDHMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.sw_BK_PD_C_Click:
                if (!b_sw_PD_C) {
                    b_sw_PD_C = true;
                    swBKPDCShow.setVisibility(View.VISIBLE);
                    swBKPDCMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_PD_C = false;
                    swBKPDCShow.setVisibility(View.GONE);
                    swBKPDCMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.sw_RUE_Click://进球 大/小 & 进球 单/双
                if (!b_sw_RUE) {
                    b_sw_RUE = true;
                    swRUEShow.setVisibility(View.VISIBLE);
                    swRUEMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_RUE = false;
                    swRUEShow.setVisibility(View.GONE);
                    swRUEMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.sw_RMU_Click://独赢 & 进球 大/小
                if (!b_sw_RMU) {
                    b_sw_RMU = true;
                    swRMUShow.setVisibility(View.VISIBLE);
                    swRMUMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_RMU = false;
                    swRMUShow.setVisibility(View.GONE);
                    swRMUMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.sw_RUT_Click://进球 大/小 双方球队进球
                if (!b_sw_RUT) {
                    b_sw_RUT = true;
                    swRUTShow.setVisibility(View.VISIBLE);
                    swRUTMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_RUT = false;
                    swRUTShow.setVisibility(View.GONE);
                    swRUTMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.rlRang:
                if (!b_sw_R) {          //让球
                    b_sw_R = true;
                    sw_R_show.setVisibility(View.VISIBLE);
                    ivRangMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_R = false;
                    sw_R_show.setVisibility(View.GONE);
                    ivRangMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                //onShowDown(b_sw_R,sw_R_show,ivRangMark);
                GameLog.log("让球是否展示 " + b_sw_R);
                break;
            case R.id.rlTeamHRang:                  //让球
                win_radio_r_h = "open";
                order_method = "FT_r";
                rtype = "RH";
                wtype = "R";
                type = "H";

                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_re";
                    rtype = "REH";
                    wtype = "RE";
                    type = "H";
                } else if (fromType.equals("2")) {
                    order_method = "BK_re";
                    rtype = "REH";
                    wtype = "RE";
                    type = "H";
                } else if (fromType.equals("4") || fromType.equals("6")) {
                    order_method = "BK_r";
                    rtype = "RH";
                    wtype = "R";
                    type = "H";
                }

                onPrepareBetClick();
                break;
            case R.id.rlTeamCRang:
                win_radio_r_h = "open";
                order_method = "FT_r";
                rtype = "RC";
                wtype = "R";
                type = "C";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_re";
                    rtype = "REC";
                    wtype = "RE";
                    type = "C";
                } else if (fromType.equals("2")) {
                    order_method = "BK_re";
                    rtype = "REC";
                    wtype = "RE";
                    type = "C";
                } else if (fromType.equals("4") || fromType.equals("6")) {
                    order_method = "BK_r";
                    rtype = "RC";
                    wtype = "R";
                    type = "C";
                }

                onPrepareBetClick();
                break;
            case R.id.sw_HR_Click:            //让球-上半场
                if (!b_sw_HR) {
                    b_sw_HR = true;
                    swHRShow.setVisibility(View.VISIBLE);
                    swHRMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HR = false;
                    swHRShow.setVisibility(View.GONE);
                    swHRMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HRH_Click:
                win_radio_r_h = "open";
                order_method = "FT_hr";
                rtype = "HRH";
                wtype = "HR";
                type = "H";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hre";
                    rtype = "HREH";
                    wtype = "HRE";
                    type = "H";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HRC_Click:
                win_radio_r_h = "open";
                order_method = "FT_hr";
                rtype = "HRC";
                wtype = "HR";
                type = "C";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hre";
                    rtype = "HREC";
                    wtype = "HRE";
                    type = "C";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_OU_Click:                //大小
                if (!b_sw_OU) {
                    b_sw_OU = true;
                    swOUShow.setVisibility(View.VISIBLE);
                    swOUMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_OU = false;
                    swOUShow.setVisibility(View.GONE);
                    swOUMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_OUC_Click:
                win_radio_r_h = "open";
                order_method = "FT_ou";
                rtype = "OUC";
                wtype = "OU";
                type = "C";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rou";
                    rtype = "ROUC";
                    wtype = "ROU";
                    type = "C";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rou";
                    rtype = "ROUC";
                    wtype = "ROU";
                    type = "C";
                } else if (fromType.equals("4") || fromType.equals("6")) {
                    order_method = "BK_ou";
                    rtype = "OUC";
                    wtype = "OU";
                    type = "C";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_OUH_Click:
                win_radio_r_h = "open";
                order_method = "FT_ou";
                rtype = "OUH";
                wtype = "OU";
                type = "H";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rou";
                    rtype = "ROUH";
                    wtype = "ROU";
                    type = "H";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rou";
                    rtype = "ROUH";
                    wtype = "ROU";
                    type = "H";
                } else if (fromType.equals("4") || fromType.equals("6")) {
                    order_method = "BK_ou";
                    rtype = "OUH";
                    wtype = "OU";
                    type = "H";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HOU_Click:               //大小-上半场
                if (!b_sw_HOU) {
                    b_sw_HOU = true;
                    swHOUShow.setVisibility(View.VISIBLE);
                    swHOUMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HOU = false;
                    swHOUShow.setVisibility(View.GONE);
                    swHOUMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HOUC_Click:
                win_radio_r_h = "open";
                order_method = "FT_hou";
                rtype = "HOUC";
                wtype = "HOU";
                type = "C";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hrou";
                    rtype = "HROUC";
                    wtype = "HROU";
                    type = "C";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HOUH_Click:
                win_radio_r_h = "open";
                order_method = "FT_hou";
                rtype = "HOUH";
                wtype = "HOU";
                type = "H";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hrou";
                    rtype = "HROUH";
                    wtype = "HROU";
                    type = "H";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_OUH_Click:               //球队进球数-主队 大/小
                if (!b_sw_OUH) {
                    b_sw_OUH = true;
                    swOUHShow.setVisibility(View.VISIBLE);
                    swOUHMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_OUH = false;
                    swOUHShow.setVisibility(View.GONE);
                    swOUHMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_OUHO_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "OUHO";
                wtype = "OUH";
                type = "O";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "ROUHO";
                    wtype = "ROUH";
                    type = "O";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rouhc";
                    rtype = "ROUHO";
                    wtype = "ROUH";
                    type = "O";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_single";
                    rtype = "OUHO";
                    wtype = "OUH";
                    type = "O";
                } else if (fromType.equals("4")||fromType.equals("6")) {
                    order_method = "BK_ouhc";
                    rtype = "OUHO";
                    wtype = "OUH";
                    type = "O";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_OUHU_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "OUHU";
                wtype = "OUH";
                type = "U";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "ROUHU";
                    wtype = "ROUH";
                    type = "U";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rouhc";
                    rtype = "ROUHU";
                    wtype = "ROUH";
                    type = "U";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_single";
                    rtype = "OUHU";
                    wtype = "OUH";
                    type = "U";
                } else if (fromType.equals("4")||fromType.equals("6")) {
                    order_method = "BK_ouhc";
                    rtype = "OUHU";
                    wtype = "OUH";
                    type = "U";
                } 
                onPrepareBetClick();
                break;
            case R.id.sw_OUC_Click:               //球队进球数-客队 大/小
                if (!b_sw_OUC) {
                    b_sw_OUC = true;
                    swOUCShow.setVisibility(View.VISIBLE);
                    swOUCMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_OUC = false;
                    swOUCShow.setVisibility(View.GONE);
                    swOUCMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_OUCO_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "OUCO";
                wtype = "OUC";
                type = "O";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "ROUCO";
                    wtype = "ROUC";
                    type = "O";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rouhc";
                    rtype = "ROUCO";
                    wtype = "ROUC";
                    type = "O";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_single";
                    rtype = "OUCO";
                    wtype = "OUC";
                    type = "O";
                }else if (fromType.equals("4")||fromType.equals("6")) {
                    order_method = "BK_ouhc";
                    rtype = "OUCO";
                    wtype = "OUC";
                    type = "O";
                } 
                onPrepareBetClick();
                break;
            case R.id.ior_OUCU_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "OUCU";
                wtype = "OUC";
                type = "U";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "ROUCU";
                    wtype = "ROUC";
                    type = "U";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rouhc";
                    rtype = "ROUCU";
                    wtype = "ROUC";
                    type = "U";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_single";
                    rtype = "OUCU";
                    wtype = "OUC";
                    type = "U";
                } else if (fromType.equals("4")||fromType.equals("6")) {
                    order_method = "BK_ouhc";
                    rtype = "OUCU";
                    wtype = "OUC";
                    type = "U";
                } 
                onPrepareBetClick();
                break;
            case R.id.sw_HOUH_Click:               //球队进球数-主队 大/小 上半场
                if (!b_sw_HOUH) {
                    b_sw_HOUH = true;
                    swHOUHShow.setVisibility(View.VISIBLE);
                    swHOUHMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HOUH = false;
                    swHOUHShow.setVisibility(View.GONE);
                    swHOUHMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HOUHO_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "HOUHO";
                wtype = "HOUH";
                type = "O";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rsingle";
                    rtype = "HRUHO";
                    wtype = "HRUH";
                    type = "O";
                }else if (fromType.equals("3")||fromType.equals("5")) {
                //半场
                orderType = "H";
                order_method = "FT_single";
                rtype = "HOUHO";
                wtype = "HOUH";
                type = "O";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HOUHU_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "HOUHU";
                wtype = "HOUH";
                type = "U";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rsingle";
                    rtype = "HRUHU";
                    wtype = "HRUH";
                    type = "U";
                }else if (fromType.equals("3")||fromType.equals("5")) {
                    //半场
                    orderType = "H";
                    order_method = "FT_single";
                    rtype = "HOUHU";
                    wtype = "HOUH";
                    type = " U";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HOUC_Click:               //球队进球数-客队 大/小 上半场
                if (!b_sw_HOUC) {
                    b_sw_HOUC = true;
                    swHOUCShow.setVisibility(View.VISIBLE);
                    swHOUCMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HOUC = false;
                    swHOUCShow.setVisibility(View.GONE);
                    swHOUCMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HOUCO_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "HOUCO";
                wtype = "HOUC";
                type = "O";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rsingle";
                    rtype = "HRUCO";
                    wtype = "HRUC";
                    type = "O";
                }else if (fromType.equals("3")||fromType.equals("5")) {
                    //半场
                    orderType = "H";
                    order_method = "FT_single";
                    rtype = "HOUCO";
                    wtype = "HOUC";
                    type = " O";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HOUCU_Click:
                win_radio_r_h = "open";
                order_method = "FT_single";
                rtype = "HOUCO";
                wtype = "HOUC";
                type = "U";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rsingle";
                    rtype = "HRUCO";
                    wtype = "HRUC";
                    type = "U";
                }else if (fromType.equals("3")||fromType.equals("5")) {
                    //半场
                    orderType = "H";
                    order_method = "FT_single";
                    rtype = "HOUCU";
                    wtype = "HOUC";
                    type = "U";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_M_click:                 //独赢
                if (!b_sw_M) {
                    b_sw_M = true;
                    swMShow.setVisibility(View.VISIBLE);
                    swMMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_M = false;
                    swMShow.setVisibility(View.GONE);
                    swMMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                //onShowDown(b_sw_M,swMShow,swMMark);
                break;
            case R.id.ior_MH_click:
                order_method = "FT_m";
                rtype = "MH";
                wtype = "M";
                type = "H";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rm";
                    rtype = "RMH";
                    wtype = "RM";
                    type = "H";
                } else if (fromType.equals("2")) {
                    orderType = "BK_order_re";
                    order_method = "BK_rm";
                    rtype = "RMH";
                    wtype = "RM";
                    type = "H";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_m";
                    rtype = "MH";
                    wtype = "M";
                    type = "H";
                }else  if (fromType.equals("4") || fromType.equals("6")){
                    order_method = "BK_m";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MC_click:
                order_method = "FT_m";
                rtype = "MC";
                wtype = "M";
                type = "C";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rm";
                    rtype = "RMC";
                    wtype = "RM";
                    type = "C";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rm";
                    rtype = "RMH";
                    wtype = "RM";
                    type = "H";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_m";
                    rtype = "MC";
                    wtype = "M";
                    type = "C";
                }else  if (fromType.equals("4") || fromType.equals("6")){
                    order_method = "BK_m";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MN_click:
                order_method = "FT_m";
                rtype = "MN";
                wtype = "M";
                type = "N";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rm";
                    rtype = "RMN";
                    wtype = "RM";
                    type = "N";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rm";
                    rtype = "RMN";
                    wtype = "RM";
                    type = "N";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_m";
                    rtype = "MN";
                    wtype = "M";
                    type = "N";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HM_click:                 //独赢-上半场
                if (!b_sw_HM) {
                    b_sw_HM = true;
                    swHMShow.setVisibility(View.VISIBLE);
                    swHMMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HM = false;
                    swHMShow.setVisibility(View.GONE);
                    swHMMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                //onShowDown(b_sw_HM,swHMShow,swHMMark);
                break;
            case R.id.ior_HMH_click:
                order_method = "FT_m";
                rtype = "HMH";
                wtype = "HM";
                type = "H";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hrm";
                    rtype = "HRMH";
                    wtype = "HRM";
                    type = "H";
                }else if (fromType.equals("2")) {
                    order_method = "BK_hrm";
                    rtype = "HRMH";
                    wtype = "HRM";
                    type = "H";
                }else  if (fromType.equals("3")||fromType.equals("5")) {
                    //全场
                    order_method = "FT_hm";
                    rtype = "HMH";
                    wtype = "HM";
                    type = "H";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HMC_click:
                order_method = "FT_m";
                rtype = "HMC";
                wtype = "HM";
                type = "C";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hrm";
                    rtype = "HRMC";
                    wtype = "HRM";
                    type = "C";
                }else if (fromType.equals("2")) {
                    order_method = "BK_Hrm";
                    rtype = "HRMC";
                    wtype = "HRM";
                    type = "C";
                }else  if (fromType.equals("3")||fromType.equals("5")) {
                    //全场
                    order_method = "FT_hm";
                    rtype = "HMC";
                    wtype = "HM";
                    type = "C";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HMN_click:
                order_method = "FT_m";
                rtype = "HMN";
                wtype = "HM";
                type = "N";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hrm";
                    rtype = "HRMN";
                    wtype = "HRM";
                    type = "N";
                }else if (fromType.equals("2")) {
                    order_method = "BK_rm";
                    rtype = "HRMH";
                    wtype = "HRM";
                    type = "N";
                }else  if (fromType.equals("3")||fromType.equals("5")) {
                    //全场
                    order_method = "FT_hm";
                    rtype = "HMN";
                    wtype = "HM";
                    type = "N";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_PD_Click://波胆
                if (!b_sw_PD) {
                    b_sw_PD = true;
                    swPDShow.setVisibility(View.VISIBLE);
                    swPDMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_PD = false;
                    swPDShow.setVisibility(View.GONE);
                    swPDMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_OVH_Click:
                order_method = "FT_pd";
                rtype = "OVH";
                wtype = "PD";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rpd";
                    rtype = "ROVH";
                    wtype = "RPD";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HPD_Click://波胆上半场
                if (!b_sw_HPD) {
                    b_sw_HPD = true;
                    swHPDShow.setVisibility(View.VISIBLE);
                    swHPDMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HPD = false;
                    swHPDShow.setVisibility(View.GONE);
                    swHPDMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HOVH_Click:
                order_method = "FT_hpd";
                rtype = "OVH";
                wtype = "HPD";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_hrpd";
                    rtype = "ROVH";
                    wtype = "HRPD";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_WE_Click:                  //赢得任一半场
                if (!b_sw_WE) {
                    b_sw_WE = true;
                    swWEShow.setVisibility(View.VISIBLE);
                    swWEMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_WE = false;
                    swWEShow.setVisibility(View.GONE);
                    swWEMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                //onShowDown(b_sw_WE,swWEShow,swWEMark);
                break;
            case R.id.ior_WEH_Click:
                order_method = "FT_single";
                rtype = "WEH";
                wtype = "WE";
                type = "H";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWEH";
                    wtype = "RWE";
                    type = "C";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WEC_Click:
                order_method = "FT_single";
                rtype = "WEC";
                wtype = "WE";
                type = "C";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWEC";
                    wtype = "RWE";
                    type = "C";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_WB_Click:                  //赢得所有半场
                if (!b_sw_WB) {
                    b_sw_WB = true;
                    swWBShow.setVisibility(View.VISIBLE);
                    swWBMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_WB = false;
                    swWBShow.setVisibility(View.GONE);
                    swWBMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                //onShowDown(b_sw_WB,swWBShow,swWBMark);
                break;
            case R.id.ior_WBH_Click:
                order_method = "FT_single";
                rtype = "WBH";
                wtype = "WB";
                type = "H";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWBH";
                    wtype = "RWB";
                    type = "H";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WBC_Click:
                order_method = "FT_single";
                rtype = "WBC";
                wtype = "WB";
                type = "C";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWBC";
                    wtype = "RWB";
                    type = "C";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_T_Click:                   //总进球数
                if (!b_sw_T) {
                    b_sw_T = true;
                    swTShow.setVisibility(View.VISIBLE);
                    swTMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_T = false;
                    swTShow.setVisibility(View.GONE);
                    swTMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                //onShowDown(b_sw_T,swTShow,swTMark);
                break;
            case R.id.ior_T01_Click:
                order_method = "FT_t";
                rtype = "0~1";
                wtype = "T";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rt";
                    rtype = "R0~1";
                    wtype = "RT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_T23_Click:
                order_method = "FT_t";
                rtype = "2~3";
                wtype = "T";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rt";
                    rtype = "R2~3";
                    wtype = "RT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_T46_Click:
                order_method = "FT_t";
                rtype = "4~6";
                wtype = "T";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rt";
                    rtype = "R4~6";
                    wtype = "RT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_OVER_Click:
                order_method = "FT_t";
                rtype = "OVER";
                wtype = "T";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rt";
                    rtype = "ROVER";
                    wtype = "RT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HT_Click:                   //总进球数-上半场
                if (!b_sw_HT) {
                    b_sw_HT = true;
                    swHTShow.setVisibility(View.VISIBLE);
                    swHTMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HT = false;
                    swHTShow.setVisibility(View.GONE);
                    swHTMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                //onShowDown(b_sw_HT,swHTShow,swHTMark);
                break;
            case R.id.ior_HT01_Click:
                order_method = "FT_t";
                rtype = "HT0";
                wtype = "HT";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rt";
                    rtype = "HRT0";
                    wtype = "HRT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HT1_Click:
                order_method = "FT_t";
                rtype = "HT1";
                wtype = "HT";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rt";
                    rtype = "HRT1";
                    wtype = "HRT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HT2_Click:
                order_method = "FT_t";
                rtype = "HT2";
                wtype = "HT";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rt";
                    rtype = "HRT2";
                    wtype = "HRT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HTOV_Click:
                order_method = "FT_t";
                rtype = "HTOV";
                wtype = "HT";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rt";
                    rtype = "HRTOV";
                    wtype = "HRT";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_TS_Click:                  //双方球队进球
                if (!b_sw_TS) {
                    b_sw_TS = true;
                    swTSShow.setVisibility(View.VISIBLE);
                    swTSMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_TS = false;
                    swTSShow.setVisibility(View.GONE);
                    swTSMark.setBackgroundResource(R.mipmap.deposit_right);
                }

                break;
            case R.id.ior_TSY_Click:
                order_method = "FT_single";
                rtype = "TSY";
                wtype = "TS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RTSY";
                    wtype = "RTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_TSN_Click:
                order_method = "FT_single";
                rtype = "TSN";
                wtype = "TS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RTSN";
                    wtype = "RTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HTS_Click:                  //双方球队进球-上半场
                if (!b_sw_HTS) {
                    b_sw_HTS = true;
                    swHTSShow.setVisibility(View.VISIBLE);
                    swHTSMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HTS = false;
                    swHTSShow.setVisibility(View.GONE);
                    swHTSMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HTSY_Click:
                order_method = "FT_single";
                rtype = "HTSY";
                wtype = "HTS";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rsingle";
                    rtype = "HRTSY";
                    wtype = "HRTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HTSN_Click:
                order_method = "FT_single";
                rtype = "HTSN";
                wtype = "HTS";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rsingle";
                    rtype = "HRTSN";
                    wtype = "HRTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_EO_Click:                      //单/双
                if (!b_sw_EO) {
                    b_sw_EO = true;
                    swEOShow.setVisibility(View.VISIBLE);
                    swEOMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_EO = false;
                    swEOShow.setVisibility(View.GONE);
                    swEOMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_EOO_Click:
                order_method = "FT_t";
                rtype = "ODD";
                wtype = "EO";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rt";
                    rtype = "RODD";
                    wtype = "REO";
                    type = "";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rt";
                    rtype = "ODD";
                    wtype = "REO";
                    type = "";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_t";
                    rtype = "ODD";
                    wtype = "EO";
                    type = "";
                } else if (fromType.equals("4") || fromType.equals("6")) {
                    order_method = "BK_t";
                    rtype = "ODD";
                    wtype = "EO";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_EOE_Click:
                order_method = "FT_t";
                rtype = "EVEN";
                wtype = "EO";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rt";
                    rtype = "REVEN";
                    wtype = "REO";
                    type = "";
                } else if (fromType.equals("2")) {
                    order_method = "BK_rt";
                    rtype = "EVEN";
                    wtype = "REO";
                    type = "";
                } else if (fromType.equals("3") || fromType.equals("5")) {
                    order_method = "FT_t";
                    rtype = "EVEN";
                    wtype = "EO";
                    type = "";
                } else if (fromType.equals("4") || fromType.equals("6")) {
                    order_method = "BK_t";
                    rtype = "EVEN";
                    wtype = "EO";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HEO_Click:                      //单/双-上半场
                if (!b_sw_HEO) {
                    b_sw_HEO = true;
                    swHEOShow.setVisibility(View.VISIBLE);
                    swHEOMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HEO = false;
                    swHEOShow.setVisibility(View.GONE);
                    swHEOMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HEOE_Click:
                order_method = "FT_t";
                rtype = "HODD";
                wtype = "HEO";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rt";
                    rtype = "HRODD";
                    wtype = "HREO";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HEOO_Click:
                order_method = "FT_t";
                rtype = "HEVEN";
                wtype = "HEO";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_hre";
                    order_method = "FT_rt";
                    rtype = "HREVEN";
                    wtype = "HREO";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_CS_Click:                      //零失球
                if (!b_sw_CS) {
                    b_sw_CS = true;
                    swCSShow.setVisibility(View.VISIBLE);
                    swCSMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_CS = false;
                    swCSShow.setVisibility(View.GONE);
                    swCSMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_CSH_Click:
                order_method = "FT_single";
                rtype = "CSH";
                wtype = "CS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RCSH";
                    wtype = "RCS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_CSC_Click:
                order_method = "FT_single";
                rtype = "CSC";
                wtype = "CS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RCSC";
                    wtype = "RCS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_WN_Click:                      //零失球获胜
                if (!b_sw_WN) {
                    b_sw_WN = true;
                    swWNShow.setVisibility(View.VISIBLE);
                    swWNMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_WN = false;
                    swWNShow.setVisibility(View.GONE);
                    swWNMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_WNH_Click:
                order_method = "FT_single";
                rtype = "WNH";
                wtype = "WN";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWNH";
                    wtype = "RWN";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WNC_Click:
                order_method = "FT_single";
                rtype = "WNC";
                wtype = "WN";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWNC";
                    wtype = "RWN";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_HG_Click:                      //最多进球的半场
                if (!b_sw_HG) {
                    b_sw_HG = true;
                    swHGShow.setVisibility(View.VISIBLE);
                    swHGMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_HG = false;
                    swHGShow.setVisibility(View.GONE);
                    swHGMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_HGH_Click:
                order_method = "FT_single";
                rtype = "HGH";
                wtype = "HG";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RHGH";
                    wtype = "RHG";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_HGC_Click:
                order_method = "FT_single";
                rtype = "HGC";
                wtype = "HG";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RHGC";
                    wtype = "RHG";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_MG_Click:                      //最多进球的半场-独赢
                if (!b_sw_MG) {
                    b_sw_MG = true;
                    swMGShow.setVisibility(View.VISIBLE);
                    swMGMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_MG = false;
                    swMGShow.setVisibility(View.GONE);
                    swMGMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_MGH_Click:
                order_method = "FT_single";
                rtype = "MGH";
                wtype = "MG";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMGH";
                    wtype = "RMG";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MGC_Click:
                order_method = "FT_single";
                rtype = "MGC";
                wtype = "MG";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMGC";
                    wtype = "RMG";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MGN_Click:
                order_method = "FT_single";
                rtype = "MGN";
                wtype = "MG";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMGN";
                    wtype = "RMG";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_SB_Click:                      //双半场进球
                if (!b_sw_SB) {
                    b_sw_SB = true;
                    swSBShow.setVisibility(View.VISIBLE);
                    swSBMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_SB = false;
                    swSBShow.setVisibility(View.GONE);
                    swSBMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_SBH_Click:
                order_method = "FT_single";
                rtype = "SBH";
                wtype = "SB";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RSBH";
                    wtype = "RSB";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_SBC_Click:
                order_method = "FT_single";
                rtype = "SBC";
                wtype = "SB";
                type = "";
                if (fromType.equals("1")) {
                    //半场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RSBC";
                    wtype = "RSB";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_F_Click:                      //半场/全场
                if (!b_sw_F) {
                    b_sw_F = true;
                    swFShow.setVisibility(View.VISIBLE);
                    swFMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_F = false;
                    swFShow.setVisibility(View.GONE);
                    swFMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_FHH_Click:
                order_method = "FT_f";
                rtype = "FHH";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFHH";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FHN_Click:
                order_method = "FT_f";
                rtype = "FHN";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFHN";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FHC_Click:
                order_method = "FT_f";
                rtype = "FHC";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFHC";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FNH_Click:
                order_method = "FT_f";
                rtype = "FNH";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFNH";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FNN_Click:
                order_method = "FT_f";
                rtype = "FNN";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFNN";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FNC_Click:
                order_method = "FT_f";
                rtype = "FNC";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFNC";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FCH_Click:
                order_method = "FT_f";
                rtype = "FCH";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFCH";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FCN_Click:
                order_method = "FT_f";
                rtype = "FCN";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFCN";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_FCC_Click:
                order_method = "FT_f";
                rtype = "FCC";
                wtype = "F";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rf";
                    rtype = "RFCC";
                    wtype = "RF";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_WM_Click:                      //净胜球数
                if (!b_sw_WM) {
                    b_sw_WM = true;
                    swWMShow.setVisibility(View.VISIBLE);
                    swWMMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_WM = false;
                    swWMShow.setVisibility(View.GONE);
                    swWMMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_WMH1_Click:
                order_method = "FT_single";
                rtype = "WMH1";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMH1";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMH2_Click:
                order_method = "FT_single";
                rtype = "WMH2";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMH2";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMH3_Click:
                order_method = "FT_single";
                rtype = "WMH3";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMH3";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMHOV_Click:
                order_method = "FT_single";
                rtype = "WMHOV";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMHOV";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMC1_Click:
                order_method = "FT_single";
                rtype = "WMC1";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMC1";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMC2_Click:
                order_method = "FT_single";
                rtype = "WMC2";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMC2";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMC3_Click:
                order_method = "FT_single";
                rtype = "WMC3";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMC3";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMCOV_Click:
                order_method = "FT_single";
                rtype = "WMCOV";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMCOV";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WM0_Click:
                order_method = "FT_single";
                rtype = "WM0";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWM0";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_WMN_Click:
                order_method = "FT_single";
                rtype = "WMN";
                wtype = "WM";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RWMN";
                    wtype = "RWM";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_DC_click:                      //双重机会
                if (!b_sw_DC) {
                    b_sw_DC = true;
                    swDCShow.setVisibility(View.VISIBLE);
                    swDCMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_DC = false;
                    swDCShow.setVisibility(View.GONE);
                    swDCMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_DCHN_click:
                order_method = "FT_single";
                rtype = "DCHN";
                wtype = "DC";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDCHN";
                    wtype = "RDC";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_DCCN_click:
                order_method = "FT_single";
                rtype = "DCCN";
                wtype = "DC";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDCCN";
                    wtype = "RDC";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_DCHC_click:
                order_method = "FT_single";
                rtype = "DCHC";
                wtype = "DC";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDCHC";
                    wtype = "RDC";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_MTS_Click:                      //独赢 & 双方球队进球
                if (!b_sw_MTS) {
                    b_sw_MTS = true;
                    swMTSShow.setVisibility(View.VISIBLE);
                    swMTSMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_MTS = false;
                    swMTSShow.setVisibility(View.GONE);
                    swMTSMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_MTSHY_Click:
                order_method = "FT_single";
                rtype = "MTSHY";
                wtype = "MTS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMTSHY";
                    wtype = "RMTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MTSHN_Click:
                order_method = "FT_single";
                rtype = "MTSHN";
                wtype = "MTS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMTSHN";
                    wtype = "RMTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MTSNY_Click:
                order_method = "FT_single";
                rtype = "MTSNY";
                wtype = "MTS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMTSNY";
                    wtype = "RMTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MTSNN_Click:
                order_method = "FT_single";
                rtype = "MTSNN";
                wtype = "MTS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMTSNN";
                    wtype = "RMTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MTSCY_Click:
                order_method = "FT_single";
                rtype = "MTSCY";
                wtype = "MTS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMTSCY";
                    wtype = "RMTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_MTSCN_Click:
                order_method = "FT_single";
                rtype = "MTSCN";
                wtype = "MTS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RMTSCN";
                    wtype = "RMTS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_DS_Click:                      //双重机会&双方球队进球

                if (!b_sw_DS) {
                    b_sw_DS = true;
                    swDSShow.setVisibility(View.VISIBLE);
                    swDSMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_DS = false;
                    swDSShow.setVisibility(View.GONE);
                    swDSMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_DSHY_Click:
                order_method = "FT_single";
                rtype = "DSHY";
                wtype = "DS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDSHY";
                    wtype = "RDS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_DSHN_Click:
                order_method = "FT_single";
                rtype = "DSHN";
                wtype = "DS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDSHN";
                    wtype = "RDS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_DSCY_Click:
                order_method = "FT_single";
                rtype = "DSCY";
                wtype = "DS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDSCY";
                    wtype = "RDS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_DSCN_Click:
                order_method = "FT_single";
                rtype = "DSCN";
                wtype = "DS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDSCN";
                    wtype = "RDS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_DSSY_Click:
                order_method = "FT_single";
                rtype = "DSSY";
                wtype = "DS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDSSY";
                    wtype = "RDS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_DSSN_Click:
                order_method = "FT_single";
                rtype = "DSSN";
                wtype = "DS";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RDSSN";
                    wtype = "RDS";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.sw_DUA_Click:                     //独赢 & 双方球队进球
                if (!b_sw_DUA) {
                    b_sw_DUA = true;
                    swDUAShow.setVisibility(View.VISIBLE);
                    swDUAMark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_DUA = false;
                    swDUAShow.setVisibility(View.GONE);
                    swDUAMark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.sw_W3_click:                     //三项让球投注
                if (!b_sw_W3) {
                    b_sw_W3 = true;
                    swW3Show.setVisibility(View.VISIBLE);
                    swW3Mark.setBackgroundResource(R.mipmap.icon_ex_down);
                } else {
                    b_sw_W3 = false;
                    swW3Show.setVisibility(View.GONE);
                    swW3Mark.setBackgroundResource(R.mipmap.deposit_right);
                }
                break;
            case R.id.ior_W3H_click:
                order_method = "FT_single";
                rtype = "W3H";
                wtype = "W3";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RW3H";
                    wtype = "RW3";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_W3C_click:
                order_method = "FT_single";
                rtype = "W3C";
                wtype = "W3";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RW3C";
                    wtype = "RW3";
                    type = "";
                }
                onPrepareBetClick();
                break;
            case R.id.ior_W3N_click:
                order_method = "FT_single";
                rtype = "W3N";
                wtype = "W3";
                type = "";
                if (fromType.equals("1")) {
                    //全场
                    orderType = "FT_order_re";
                    order_method = "FT_rsingle";
                    rtype = "RW3N";
                    wtype = "RW3";
                    type = "";
                }
                onPrepareBetClick();
                break;

        }
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
                        if (tvBetEventRefresh != null) {
                            tvBetEventRefresh.setText("" + sendAuthTime);
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
        if (null != executorService) {
            executorService.shutdown();
            executorService.shutdownNow();
        }
        unbinder.unbind();

    }

    @Override
    public boolean onBackPressedSupport() {
        return true;
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

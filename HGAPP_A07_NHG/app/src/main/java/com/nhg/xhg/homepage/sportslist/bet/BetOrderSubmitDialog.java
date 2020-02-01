package com.nhg.xhg.homepage.sportslist.bet;

import android.os.Bundle;
import android.text.Editable;
import android.text.Html;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.nhg.common.util.Check;
import com.nhg.common.util.GameLog;
import com.nhg.xhg.Injections;
import com.nhg.xhg.R;
import com.nhg.xhg.base.HGBaseDialogFragment;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.common.util.ACache;
import com.nhg.xhg.common.util.CalcHelper;
import com.nhg.xhg.common.util.DoubleClickHelper;
import com.nhg.xhg.common.util.GameShipHelper;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.data.BetResult;
import com.nhg.xhg.data.GameAllPlayBKResult;
import com.nhg.xhg.data.GameAllPlayFTResult;
import com.nhg.xhg.data.GameAllPlayRBKResult;
import com.nhg.xhg.data.GameAllPlayRFTResult;
import com.nhg.xhg.data.PrepareBetResult;
import com.nhg.xhg.homepage.handicap.betapi.PrepareBetApiContract;
import com.nhg.xhg.homepage.handicap.betapi.PrepareRequestParams;
import com.nhg.xhg.homepage.handicap.leaguedetail.PrepareBetEvent;

import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class BetOrderSubmitDialog extends HGBaseDialogFragment implements PrepareBetApiContract.View{

    public static final String PARAM0 = "param0";
    public static final String PARAM1 = "param1";
    public static final String PARAM2 = "param2";
    public static final String PARAM3 = "param3";
    public static final String PARAM4 = "param4";
    public static final String PARAM5 = "param5";
    @BindView(R.id.tvBetOrderMM)
    TextView tvBetOrderMM;
    @BindView(R.id.etBetSubmitsInfor)
    TextView etBetSubmitInfor;
    @BindView(R.id.betAutoOdd)
    CheckBox betAutoOdd;

    @BindView(R.id.tvBetSubmitTitle)
    TextView tvBetSubmitTitle;
    @BindView(R.id.tvBetSubmitmLeague)
    TextView tvBetSubmitmLeague;
    @BindView(R.id.tvBetSubmitmLeagueName)
    TextView tvBetSubmitmLeagueName;
    @BindView(R.id.tvBetSubmitmLeagueRadio)
    TextView tvBetSubmitmLeagueRadio;


    @BindView(R.id.etBetSubmitGod)
    EditText etBetSubmitGod;
    @BindView(R.id.ivBetSubmitGodClear)
    ImageView ivBetSubmitGodClear;
    @BindView(R.id.tvBetSubmitGodMin)
    TextView tvBetSubmitGodMin;
    @BindView(R.id.tvBetSubmitGodMax)
    TextView tvBetSubmitGodMax;
    @BindView(R.id.tvetBetSubmitWinGod)
    TextView tvetBetSubmitWinGod;
    @BindView(R.id.btnBetSubmitCancel)
    Button btnBetSubmitCancel;
    @BindView(R.id.btnBetSubmitSuccess)
    Button btnBetSubmitSuccess;

    OrderNumber orderNumber;
    PrepareBetEvent prepareBetEvent;
    PrepareRequestParams prepareRequestParams;
    PrepareBetResult prepareBetResult;
    private ScheduledExecutorService executorService;
    private int sendAuthTime = HGConstant.ACTION_SEND_PREPARE_BET_TIME;

    private String getParam0,getParam1;
    private String championStr = "";
    private String autoOdd = "";
    private PrepareBetApiContract.Presenter presenter;
    public static BetOrderSubmitDialog newInstance(String param0, String param1, OrderNumber param2, PrepareBetEvent prepareBetEvent, PrepareRequestParams prepareRequestParams,PrepareBetResult prepareBetResult) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM0, param0);
        bundle.putString(PARAM1, param1);
        bundle.putParcelable(PARAM2, param2);
        bundle.putParcelable(PARAM3, prepareBetEvent);
        bundle.putParcelable(PARAM4, prepareRequestParams);
        bundle.putParcelable(PARAM5, prepareBetResult);
        BetOrderSubmitDialog dialog = new BetOrderSubmitDialog();
        Injections.inject(null,dialog);
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.dialog_bet_order_submit;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        //EventBus.getDefault().register(this);
        getParam0 =  getArguments().getString(PARAM0);
        getParam1 =  getArguments().getString(PARAM1);
        orderNumber =  getArguments().getParcelable(PARAM2);
        prepareBetEvent =  getArguments().getParcelable(PARAM3);
        prepareRequestParams =  getArguments().getParcelable(PARAM4);
        prepareBetResult =  getArguments().getParcelable(PARAM5);
        String aauto = ACache.get(getContext()).getAsString(HGConstant.USERNAME_AUTO_ADD);
        if(Check.isEmpty(aauto)||aauto.equals("Y")){
            GameLog.log("当前是Y");
            autoOdd = "Y";
            betAutoOdd.setChecked(true);
            ACache.get(getContext()).put(HGConstant.USERNAME_AUTO_ADD,"Y");
        }else if(aauto.equals("N")){
            GameLog.log("当前是N");
            betAutoOdd.setChecked(false);
            autoOdd = "";
            ACache.get(getContext()).put(HGConstant.USERNAME_AUTO_ADD,"N");
        }
        betAutoOdd.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean b) {
                if(!b){
                    GameLog.log("当前是N");
                    autoOdd = "";
                    ACache.get(getContext()).put(HGConstant.USERNAME_AUTO_ADD,"N");
                }else{
                    GameLog.log("当前是Y");
                    autoOdd = "Y";
                    ACache.get(getContext()).put(HGConstant.USERNAME_AUTO_ADD,"Y");
                }
            }
        });
        onpostPrepareBetApiResult(prepareBetResult);
        onSartTime();

        tvBetOrderMM.setText(getParam0);
        //tvBetSubmitTitle.setText(prepareBetEvent.getmLeagueTitle());
        /*etBetSubmitInfor.setText(getParam1);
        etBetSubmitInfor.setVisibility(View.GONE);
        tvBetSubmitTitle.setText(prepareBetEvent.getmLeagueTitle());
        tvBetSubmitmLeague.setText(prepareBetEvent.getmLeagueName());
        if(Check.isEmpty(prepareBetEvent.getRatio())){
            tvBetSubmitmLeagueName.setText(prepareBetEvent.getmTeamH()+" v "+prepareBetEvent.getmTeamC());
        }else {
            tvBetSubmitmLeagueName.setText(Html.fromHtml(prepareBetEvent.getmTeamH()+" <font color='#C9270B'>"+prepareBetEvent.getRatio()+"</font> v "+prepareBetEvent.getmTeamC()));
        }
        tvBetSubmitmLeagueRadio.setText(Html.fromHtml(prepareBetEvent.getBuyOrderText()));

        tvBetSubmitGodMin.setText(ACache.get(getContext()).getAsString(HGConstant.USERNAME_BUY_MIN));
        tvBetSubmitGodMax.setText(ACache.get(getContext()).getAsString(HGConstant.USERNAME_BUY_MAX));*/
        etBetSubmitGod.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }
            /**
             * 可赢金额=交易金额*赔率。
             除了后面几种玩法例外，独赢、单双、波胆、总进球、双方球队进球、半场/全场、双重机会
             可赢金额=交易金额*（赔率-1）
             备注
             除了让球和大小 判断赔率是否大于1 如果大于1就减1
             */
            @Override
            public void afterTextChanged(Editable editable) {
                if (editable.length() == 0) {
                    tvetBetSubmitWinGod.setText("0.00");
                    ivBetSubmitGodClear.setVisibility(View.GONE);
                    return;
                } else {
                    ivBetSubmitGodClear.setVisibility(View.VISIBLE);
                }
                String edit  = editable.toString();
                GameLog.log("输入的金额 [ "+edit+" ]"+" 准备再次请求的数据 "+prepareBetResult.getIoradio_r_h());
                if(Check.isEmpty(edit)||Check.isNull(prepareBetResult)){
                    return;
                }
                if(getParam1.equals("open")){
                    GameLog.log("让球和大小的赔率计算");
                    tvetBetSubmitWinGod.setText(GameShipHelper.formatMoney( CalcHelper.multiply(prepareBetResult.getIoradio_r_h(),edit)+""));
                    return;
                }
                if(prepareBetResult.getIoradio_r_h().compareTo("1")>0){
                    GameLog.log("赔率大于1的计算");
                    tvetBetSubmitWinGod.setText(GameShipHelper.formatMoney( CalcHelper.multiply(CalcHelper.sub(prepareBetResult.getIoradio_r_h(),"1")+"",edit)+""));
                }else{
                    GameLog.log("赔率小于1的计算");
                    tvetBetSubmitWinGod.setText(GameShipHelper.formatMoney( CalcHelper.multiply(prepareBetResult.getIoradio_r_h(),edit)+""));
                }
            }
        });
    }

    @OnClick({R.id.ivBetSubmitGodClear,R.id.btnBetSubmitCancel, R.id.btnBetSubmitSuccess})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.ivBetSubmitGodClear:
                etBetSubmitGod.setText("");
                tvetBetSubmitWinGod.setText("0.00");
                break;
            case R.id.btnBetSubmitCancel:
                this.dismiss();
                break;
            case R.id.btnBetSubmitSuccess:
               String god =  etBetSubmitGod.getText().toString().trim();
                String godWin = tvetBetSubmitWinGod.getText().toString().trim();
               if(Check.isEmpty(god)){
                   showMessage("请输入投注额！");
                   return;
               }
               GameLog.log(" 购买金额："+god);

                if(Double.valueOf(god) < Double.valueOf(prepareBetResult.getMinBet())){
                    showMessage("下注金额需大于"+prepareBetResult.getMinBet()+"元！");
                    return;
                }
                if(Double.valueOf(godWin.replace(",","")) > Double.valueOf("1000000")){
                    showMessage("单注最高派彩额是RMB 1,000,000");
                    return;
                }
                GameLog.log("下注的请求参数是："+prepareRequestParams.autoOdd);
                DoubleClickHelper.getNewInstance().disabledView(btnBetSubmitSuccess);
                if("FT_order_nfs".equals(prepareRequestParams.autoOdd)){
                    presenter.postBetChampionApi("",prepareRequestParams.getCate(),prepareRequestParams.getGid(),prepareBetResult.getType(),prepareRequestParams.getAppRefer(),
                            prepareBetResult.getLine_type(),prepareBetResult.getOdd_f_type(),god,prepareBetResult.getIoradio_r_h(),prepareBetResult.getRtype(),prepareBetResult.getWtype(),autoOdd);

                }else if("BK_order_re".equals(prepareRequestParams.autoOdd)){
                    presenter.postBetBKreApi("",prepareRequestParams.getCate(),prepareRequestParams.getGid(),prepareBetResult.getType(),prepareRequestParams.getAppRefer(),
                            prepareBetResult.getLine_type(),prepareBetResult.getOdd_f_type(),god,prepareBetResult.getIoradio_r_h(),prepareBetResult.getRtype(),prepareBetResult.getWtype(),autoOdd);

                }else if("BK_order".equals(prepareRequestParams.autoOdd)){
                presenter.postBetBKApi("",prepareRequestParams.getCate(),prepareRequestParams.getGid(),prepareBetResult.getType(),prepareRequestParams.getAppRefer(),
                        prepareBetResult.getLine_type(),prepareBetResult.getOdd_f_type(),god,prepareBetResult.getIoradio_r_h(),prepareBetResult.getRtype(),prepareBetResult.getWtype(),autoOdd);

                }else if("FT_order_re".equals(prepareRequestParams.autoOdd)){
                    presenter.postBetFTreApi("",prepareRequestParams.getCate(),prepareRequestParams.getGid(),prepareBetResult.getType(),prepareRequestParams.getAppRefer(),
                            prepareBetResult.getLine_type(),prepareBetResult.getOdd_f_type(),god,prepareBetResult.getIoradio_r_h(),prepareBetResult.getRtype(),prepareBetResult.getWtype(),autoOdd);

                }else if("FT_order_hre".equals(prepareRequestParams.autoOdd)){
                    presenter.postBetFThreApi("",prepareRequestParams.getCate(),prepareRequestParams.getGid(),prepareBetResult.getType(),prepareRequestParams.getAppRefer(),
                            prepareBetResult.getLine_type(),prepareBetResult.getOdd_f_type(),god,prepareBetResult.getIoradio_r_h(),prepareBetResult.getRtype(),prepareBetResult.getWtype(),autoOdd);

                }else{
                    presenter.postBetFTApi("",prepareRequestParams.getCate(),prepareRequestParams.getGid(),prepareBetResult.getType(),prepareRequestParams.getAppRefer(),
                            prepareBetResult.getLine_type(),prepareBetResult.getOdd_f_type(),god,prepareBetResult.getIoradio_r_h(),prepareBetResult.getRtype(),prepareBetResult.getWtype(),autoOdd);
                }
                break;
        }
    }


    @Override
    public void setStart(int action) {

    }

    @Override
    public void setError(int action, int errcode) {

    }

    @Override
    public void setError(int action, String errString) {

    }

    @Override
    public void setComplete(int action) {

    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postGameAllBetsBKResult(GameAllPlayBKResult gameAllPlayBKResult) {

    }

    @Override
    public void postGameAllBetsRBKResult(GameAllPlayRBKResult gameAllPlayRBKResult) {

    }

    @Override
    public void postGameAllBetsFTResult(GameAllPlayFTResult gameAllBetsFBResult) {

    }

    @Override
    public void postGameAllBetsRFTResult(GameAllPlayRFTResult gameAllPlayRFTResult) {

    }

    @Override
    public void postGameAllBetsFTFailResult(String message) {

    }

    @Override
    public void postGameAllBetsResult(GameAllPlayRBKResult gameAllBetsResult) {

    }

    @Override
    public void postPrepareBetApiResult(PrepareBetResult prepareBetResult) {

        onpostPrepareBetApiResult(prepareBetResult);
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#C9270B'>" + sign+"</font>";
    }

    private void onpostPrepareBetApiResult(PrepareBetResult prepareBetResult) {
        this.prepareBetResult = prepareBetResult;
        if("Y".equals(prepareRequestParams.autoOdd)){
            championStr = prepareBetResult.getMB_Team();
            tvBetSubmitTitle.setText(prepareBetResult.getLeag()+"-"+prepareBetResult.getMB_Team());
            tvBetSubmitmLeague.setVisibility(View.GONE);//联赛名称
        }else{
            tvBetSubmitTitle.setText(Html.fromHtml(prepareBetResult.getGametype()+onMarkRed(prepareBetResult.getInball())));
            if(!Check.isEmpty(prepareRequestParams.getOrder_type())){
                tvBetSubmitmLeague.setText(Html.fromHtml(prepareBetResult.getLeag()+"<br>"+prepareRequestParams.getOrder_type()));//联赛名称
            }else{
                tvBetSubmitmLeague.setText(prepareBetResult.getLeag());//联赛名称
            }
        }

        tvBetSubmitGodMin.setText(prepareBetResult.getMinBet());//最小下注
        tvBetSubmitGodMax.setText(prepareBetResult.getMaxBet());//最大下注

        /*String textHead = "";//大小的下注展示

        if(prepareBetResult.getM_Place().contains("小")){
            textHead += "小 " +onMarkRed(prepareBetResult.getM_Place().substring(1));
        }else if(prepareBetResult.getM_Place().contains("大") ){
            textHead += "大 " +onMarkRed(prepareBetResult.getM_Place().substring(1));
        }else{
            textHead +=  prepareBetResult.getM_Place();
        }
        textHead += " @ " + onMarkRed(prepareBetResult.getIoradio_r_h());
        tvBetSubmitmLeagueRadio.setText(Html.fromHtml(textHead));*/

        String showTypeR =  prepareBetResult.getMB_Team();
        String textHead = "";
        //主队 v 客队 的展示
        switch (prepareBetResult.getWtype()){
            case "R"://让球
            case "HR":
               /* if(prepareBetResult.getShowTypeR().equals("H")){
                    showTypeR += onMarkRed(prepareBetResult.getSign())+"  "+prepareBetResult.getTG_Team();
                }else if(prepareBetResult.getShowTypeR().equals("C")){
                    showTypeR += "  "+prepareBetResult.getTG_Team()+onMarkRed(prepareBetResult.getSign());
                }else if(prepareBetResult.getShowTypeRB().equals("H")){
                    showTypeR += onMarkRed(prepareBetResult.getSign())+"  "+prepareBetResult.getTG_Team();
                }else if(prepareBetResult.getShowTypeRB().equals("C")){
                    showTypeR += "  "+prepareBetResult.getTG_Team()+onMarkRed(prepareBetResult.getSign());
                }else{
                    showTypeR += "  "+prepareBetResult.getTG_Team()+onMarkRed(prepareBetResult.getSign());
                }*/
                showTypeR += onMarkRed(prepareBetResult.getSign())+"  "+prepareBetResult.getTG_Team();
                textHead += onMarkRed(prepareBetResult.getM_Place());
                tvBetSubmitmLeagueName.setText(Html.fromHtml(showTypeR));
                break;
            /*case "OU"://大小
            case "HOU":
                textHead += prepareBetResult.getM_Place().substring(0,1)+onMarkRed(prepareBetResult.getM_Place().substring(1));
                tvBetSubmitmLeagueName.setText(prepareBetResult.getMB_Team()+" V "+prepareBetResult.getTG_Team());
                break;
            case "PD":
            case "HPD":
                textHead += onMarkRed(prepareBetResult.getM_Place());
                tvBetSubmitmLeagueName.setText(prepareBetResult.getMB_Team()+" V "+prepareBetResult.getTG_Team());
                break;*/
            case "FS":
                tvBetSubmitmLeagueName.setVisibility(View.GONE);
                textHead += onMarkRed(prepareBetResult.getM_Place());
                break;
            default:
                    textHead += onMarkRed(prepareBetResult.getM_Place());
                    tvBetSubmitmLeagueName.setText(prepareBetResult.getMB_Team()+" VS "+prepareBetResult.getTG_Team());
                    break;
        }

        /*if(prepareBetResult.getWtype().equals("R")){
            if(prepareBetResult.getShowTypeR().equals("H")){
                showTypeR += onMarkRed(prepareBetResult.getSign())+" V "+prepareBetResult.getTG_Team();
            }else if(prepareBetResult.getShowTypeR().equals("C")){
                showTypeR += " V "+prepareBetResult.getTG_Team()+onMarkRed(prepareBetResult.getSign());
            }else if(prepareBetResult.getShowTypeRB().equals("H")){
                showTypeR += onMarkRed(prepareBetResult.getSign())+" V "+prepareBetResult.getTG_Team();
            }else if(prepareBetResult.getShowTypeRB().equals("C")){
                showTypeR += " V "+prepareBetResult.getTG_Team()+onMarkRed(prepareBetResult.getSign());
            }else{
                showTypeR += " V "+prepareBetResult.getTG_Team()+onMarkRed(prepareBetResult.getSign());
            }
            textHead += prepareBetResult.getM_Place();
            tvBetSubmitmLeagueName.setText(Html.fromHtml(showTypeR));
        }else if(prepareBetResult.getWtype().equals("OU")){
            textHead += prepareBetResult.getM_Place().substring(0,1)+onMarkRed(prepareBetResult.getM_Place().substring(1));
            tvBetSubmitmLeagueName.setText(prepareBetResult.getMB_Team()+" V "+prepareBetResult.getTG_Team());
        }else if(prepareBetResult.getWtype().equals("FS")){
            tvBetSubmitmLeagueName.setVisibility(View.GONE);
            textHead += onMarkRed(prepareBetResult.getM_Place());
        }*/
        textHead += " @ " + onMarkRed(prepareBetResult.getIoradio_r_h());
        tvBetSubmitmLeagueRadio.setText(Html.fromHtml(textHead));
    }

    @Override
    public void postBetApiResult(BetResult betResult) {
        GameLog.log("交易的订单是："+betResult);
        hide();
        BetOrderSubmitSuccessDialog.newInstance( betResult.getData().get(0),championStr).show(getFragmentManager());
    }

    @Override
    public void postBetApiFailResult(String message) {
        showMessage(message);
        hide();
    }

    @Override
    public void setPresenter(PrepareBetApiContract.Presenter presenter) {
        this.presenter = presenter;
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
               /* getActivity().runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if(tvLeagueDetailRefresh!=null){
                            tvLeagueDetailRefresh.setText(""+ sendAuthTime);
                            //GameLog.log(getString(R.string.n_register_phone_waiting) + sendAuthTime + "s");
                        }
                    }
                });*/
            }
        }
    }

    private void onSartTime(){
        //pappRefer,porder_method,pgid,ptype,pwtype,prtype,podd_f_type,perror_flag,porder_type
        presenter.postPrepareBetApi("",prepareRequestParams.order_method,prepareRequestParams.gid,prepareRequestParams.type,prepareRequestParams.wtype,prepareRequestParams.rtype,prepareRequestParams.odd_f_type,prepareRequestParams.error_flag,prepareRequestParams.order_type);
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
        sendAuthTime = HGConstant.ACTION_SEND_PREPARE_BET_TIME;
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
        //EventBus.getDefault().unregister(this);
        super.onDestroyView();
        if(null!=executorService){
            executorService.shutdownNow();
            executorService.shutdown();
            executorService = null;
        }
    }

    /*@Subscribe
    public void onPrepareBetEvent(PrepareBetEvent prepareBetEvent){
        orderNumber.setIoradio_r_h(prepareBetEvent.getIoradio_r_h());
        GameLog.log("------------------PrepareBetEvent-----------------------");
    }*/

}

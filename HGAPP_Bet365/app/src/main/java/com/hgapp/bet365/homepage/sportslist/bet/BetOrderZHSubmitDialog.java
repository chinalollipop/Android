package com.hgapp.bet365.homepage.sportslist.bet;

import android.content.Context;
import android.os.Bundle;
import android.support.v7.widget.DividerItemDecoration;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Editable;
import android.text.Html;
import android.text.TextWatcher;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.hgapp.bet365.Injections;
import com.hgapp.bet365.R;
import com.hgapp.bet365.base.HGBaseDialogFragment;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.common.adapters.AutoSizeRVAdapter;
import com.hgapp.bet365.common.util.ACache;
import com.hgapp.bet365.common.util.CalcHelper;
import com.hgapp.bet365.common.util.DoubleClickHelper;
import com.hgapp.bet365.common.util.GameShipHelper;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.data.BetZHResult;
import com.hgapp.bet365.data.GameAllZHBetsBKResult;
import com.hgapp.bet365.data.PersonInformResult;
import com.hgapp.bet365.data.PrepareBetResult;
import com.hgapp.bet365.homepage.handicap.betapi.PrepareRequestParams;
import com.hgapp.bet365.homepage.handicap.betapi.zhbetapi.PrepareZHBetApiContract;
import com.hgapp.bet365.homepage.handicap.leaguedetail.CalosEvent;
import com.hgapp.bet365.homepage.handicap.leaguedetail.ComPassListData;
import com.hgapp.bet365.homepage.handicap.leaguedetail.PrepareBetEvent;
import com.hgapp.bet365.homepage.handicap.leaguedetail.zhbet.ZHBetManager;
import com.hgapp.bet365.homepage.handicap.leaguedetail.zhbet.ZHBetViewManager;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import butterknife.BindView;
import butterknife.OnClick;

public class BetOrderZHSubmitDialog extends HGBaseDialogFragment implements PrepareZHBetApiContract.View {

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
    @BindView(R.id.betSubmitZHRv)
    RecyclerView betSubmitZHRv;

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
    ArrayList<ComPassListData> dataList;
    String gType,active;//bk ft
    String game, gameid,gid_fs;
    private ScheduledExecutorService executorService;
    private int sendAuthTime = HGConstant.ACTION_SEND_PREPARE_BET_TIME;
    private List<GameAllZHBetsBKResult.BetItemBean> betItem;
    private String getParam0,getParamMin,getParamMax;
    private String championStr = "";
    private PrepareZHBetApiContract.Presenter presenter;
    public static BetOrderZHSubmitDialog newInstance(String param0, String param1, OrderNumber param2, PrepareBetEvent prepareBetEvent, PrepareRequestParams prepareRequestParams, PrepareBetResult prepareBetResult) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM0, param0);
        bundle.putString(PARAM1, param1);
        bundle.putParcelable(PARAM2, param2);
        bundle.putParcelable(PARAM3, prepareBetEvent);
        bundle.putParcelable(PARAM4, prepareRequestParams);
        bundle.putParcelable(PARAM5, prepareBetResult);
        BetOrderZHSubmitDialog dialog = new BetOrderZHSubmitDialog();
        Injections.inject(null,dialog);
        dialog.setArguments(bundle);
        return dialog;
    }

    public static BetOrderZHSubmitDialog newInstance(String param1,String param2,String param3,ArrayList<ComPassListData> dataList) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM1, param1);
        bundle.putString(PARAM2, param2);
        bundle.putString(PARAM3, param3);
        bundle.putParcelableArrayList(PARAM0, dataList);
        BetOrderZHSubmitDialog dialog = new BetOrderZHSubmitDialog();
        Injections.inject(null,dialog);
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.dialog_bet_order_zh_submit;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        //EventBus.getDefault().register(this);
       /* getParam0 =  getArguments().getString(PARAM0);
        getParam1 =  getArguments().getString(PARAM1);
        orderNumber =  getArguments().getParcelable(PARAM2);
        prepareBetEvent =  getArguments().getParcelable(PARAM3);
        prepareRequestParams =  getArguments().getParcelable(PARAM4);
        prepareBetResult =  getArguments().getParcelable(PARAM5);*/
       presenter.getPersonInform("");
        gType = getArguments().getString(PARAM1);
        getParam0 =  getArguments().getString(PARAM2);
        active =  getArguments().getString(PARAM3);
        dataList = getArguments().getParcelableArrayList(PARAM0);
        onpostPrepareBetApiResult();
        onSartTime();

        tvBetOrderMM.setText(ACache.get(getContext()).getAsString(HGConstant.USERNAME_REMAIN_MONEY));
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

                if(Check.isEmpty(edit)){//prepareBetResult
                    return;
                }

                if(Check.isNull(betItem)){
                    return;
                }
                int size = betItem.size();
                Double money =1.0d;
                for(int k=0;k<size;++k){
                    money = CalcHelper.multiply(betItem.get(k).getM_rate(),money+"");
                }
                money =  CalcHelper.multiply(edit,money+"");
                GameLog.log("输入的金额 [ "+edit+" ]"+" 准备再次请求的数据 "+money);
                tvetBetSubmitWinGod.setText(GameShipHelper.formatMoney(CalcHelper.sub(money+"",edit)+""));

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

                if(Double.valueOf(god) < Double.valueOf(getParamMin)){
                    //showMessage("下注金额需大于20元！");
                    showMessage("下注金额需大于"+getParamMin+"元！");
                    return;
                }
                if(Double.valueOf(godWin.replace(",","")) > Double.valueOf("1000000")){
                    showMessage("单注最高派彩额是RMB 1,000,000");
                    return;
                }
                if(betItem.size()<3){
                    showMessage("下注单数最少3注");
                    return;
                }
                DoubleClickHelper.getNewInstance().disabledView(btnBetSubmitSuccess);
                onPostRquestZHBet(god);
               // GameLog.log("下注的请求参数是："+prepareRequestParams.autoOdd);
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


    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#C9270B'>" + sign+"</font>";
    }

    private void onpostPrepareBetApiResult() {
        int size = dataList.size();
        game = "";
        gameid = "";
        gid_fs = "";
        for(int k=0;k<size;++k){
            game += dataList.get(k).gid+",";
            gameid += dataList.get(k).method_type+",";
            gid_fs += dataList.get(k).gid_fs+",";
        }
        if(Check.isEmpty(game)||Check.isEmpty(gameid)){
            return;
        }
        game = game.substring(0,game.length()-1);
        gameid = gameid.substring(0,gameid.length()-1);
        gid_fs = gid_fs.substring(0,gid_fs.length()-1);
        GameLog.log("game "+game +" gameid "+gameid+" gid_fs "+gid_fs);
        if("BK".equals(gType)){
            presenter.postGameAllZHBetsBK("",gameid,game,gid_fs);
        }else{
            presenter.postGameAllZHBetsFT("",gameid,game,gid_fs);
        }
       /* tvBetSubmitGodMin.setText(prepareBetResult.getMinBet());//最小下注
        tvBetSubmitGodMax.setText(prepareBetResult.getMaxBet());//最大下注*/
    }

    private void onPostRquestZHBet(String gold){
        String wagerDatas = "";
        int size = betItem.size();
        for(int k=0;k<size;++k){
            wagerDatas += betItem.get(k).getM_gid()+","+betItem.get(k).getType()+","+betItem.get(k).getM_rate()+"|";
        }
        if("BK".equals(gType)){
            presenter.postZHBetBK("",active,betItem.size()+"",gold,wagerDatas);
        }else{
            presenter.postZHBetFT("",active,betItem.size()+"",gold,wagerDatas);
        }
    }


    @Override
    public void postGameAllZHBetsBKResult(GameAllZHBetsBKResult gameAllZHBetsBKResult) {
        LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        betSubmitZHRv.setLayoutManager(gridLayoutManager);
        betSubmitZHRv.setHasFixedSize(true);
        betSubmitZHRv.setNestedScrollingEnabled(false);
        betSubmitZHRv.addItemDecoration(new DividerItemDecoration(getContext(),DividerItemDecoration.VERTICAL));
        betItem = gameAllZHBetsBKResult.getBetItem();
        betSubmitZHRv.setAdapter(new ZHRecordListAdapter(getContext(), R.layout.item_order_zh,betItem));
        getParamMin = gameAllZHBetsBKResult.getMinBet();
        getParamMax = gameAllZHBetsBKResult.getMaxBet();
        tvBetSubmitGodMin.setText(getParamMin);//最小下注
        tvBetSubmitGodMax.setText(getParamMax);//最大下注
    }

    @Override
    public void postGameAllZHBetsFTResult(GameAllZHBetsBKResult gameAllZHBetsBKResult) {

        LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        betSubmitZHRv.setLayoutManager(gridLayoutManager);
        betSubmitZHRv.setHasFixedSize(true);
        betSubmitZHRv.setNestedScrollingEnabled(false);
        betSubmitZHRv.addItemDecoration(new DividerItemDecoration(getContext(),DividerItemDecoration.VERTICAL));
        betItem = gameAllZHBetsBKResult.getBetItem();
        betSubmitZHRv.setAdapter(new ZHRecordListAdapter(getContext(), R.layout.item_order_zh,betItem));
        getParamMin = gameAllZHBetsBKResult.getMinBet();
        getParamMax = gameAllZHBetsBKResult.getMaxBet();
        tvBetSubmitGodMin.setText(getParamMin);//最小下注
        tvBetSubmitGodMax.setText(getParamMax);//最大下注
    }

    @Override
    public void postZHBetFTResult(BetZHResult betZHResult) {
        GameLog.log("购买成功数据："+betZHResult.toString());
        hide();
        ZHBetManager.getSingleton().onClearData();
        ZHBetViewManager.getSingleton().onShowNumber("0");
        EventBus.getDefault().post(new CalosEvent());
        BetOrderZHSubmitSuccessDialog.newInstance(betZHResult.getData().get(0),"").show(getFragmentManager());
    }

    public class ZHRecordListAdapter extends AutoSizeRVAdapter<GameAllZHBetsBKResult.BetItemBean> {
        private Context context;

        public ZHRecordListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }
        @Override
        protected void convert(ViewHolder holder, final GameAllZHBetsBKResult.BetItemBean rowsBean, int position) {
            GameLog.log("数据展示 "+rowsBean.getType());
            holder.setText(R.id.itemZH1,  rowsBean.getLeag());
            holder.setText(R.id.itemZH2,  rowsBean.getGametype());
            TextView textView3 =  holder.getView(R.id.itemZH3);
            TextView textView4 =  holder.getView(R.id.itemZH4);
            textView3.setText(Html.fromHtml(rowsBean.getMb_team()+onMarkRed(rowsBean.getSign())+rowsBean.getTg_team()));
            textView4.setText(Html.fromHtml(onMarkRed(rowsBean.getPlace())+"@"+onMarkRed(rowsBean.getM_rate())));
            holder.setOnClickListener(R.id.itemZHClear, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    betItem.remove(rowsBean);
                    ZHBetManager.getSingleton().onRemoveItem(rowsBean.getM_gid());
                    ZHBetViewManager.getSingleton().onShowNumber(ZHBetManager.getSingleton().onListSize()+"");
                    if(betItem.size()==0){
                        hide();
                    }
                    notifyDataSetChanged();
                }
            });
           /* if (rowsBean.getType().equals("S")) {//交易金额
                holder.setText(R.id.tvRecordName, rowsBean.getBank());
                holder.setText(R.id.tvRecordOrderStatus, rowsBean.getChecked());
                holder.setText(R.id.tvRecordOrderCode, rowsBean.getOrder_code().substring(rowsBean.getOrder_code().length()-7));
                holder.setText(R.id.tvRecordTime, rowsBean.getDate());
                holder.setText(R.id.tvRecordMoney,  rowsBean.getGold());
            } else {//转账记录
                holder.setText(R.id.tvRecordName, "转出到" + rowsBean.getBank_Address());
                holder.setText(R.id.tvRecordOrderCode, rowsBean.getName());
                holder.setText(R.id.tvRecordTime, rowsBean.getDate());
                holder.setText(R.id.tvRecordMoney, "-" + rowsBean.getGold());
            }*/
        }
    }

    @Override
    public void postBetApiFailResult(String message) {
        showMessage(message);
        hide();
    }

    @Override
    public void postPersonInformResult(PersonInformResult personInformResult) {
        String personMoney = GameShipHelper.formatMoney(personInformResult.getBalance_hg());
        GameLog.log("ZH个人的金额："+personMoney);
        tvBetOrderMM.setText(personMoney);
    }


    @Override
    public void setPresenter(PrepareZHBetApiContract.Presenter presenter) {
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
        onpostPrepareBetApiResult();
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

}

package com.hgapp.a0086.homepage.sportslist.bet;

import android.content.Context;
import android.os.Bundle;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.text.Html;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseDialogFragment;
import com.hgapp.a0086.common.adapters.AutoSizeRVAdapter;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.GameShipHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.data.BetZHResult;
import com.hgapp.a0086.homepage.UserMoneyEvent;
import com.hgapp.a0086.homepage.handicap.BottombarViewManager;
import com.hgapp.a0086.homepage.handicap.leaguedetail.CalosEvent;
import com.hgapp.a0086.personpage.betrecord.BetRecordFragment;
import com.hgapp.common.util.GameLog;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class BetOrderZHSubmitSuccessDialog extends HGBaseDialogFragment{

    public static final String PARAM1 = "champion";
    public static final String PARAM2 = "betResult";
    @BindView(R.id.betSuccessTitle)
    TextView betSuccessTitle;
    @BindView(R.id.tvSuccessUserMoney)
    TextView tvSuccessUserMoney;
    @BindView(R.id.etBetSuccessInforList)
    RecyclerView etBetSuccessInforList;
    @BindView(R.id.etBetSuccessInfor1)
    TextView etBetSubmitInfor1;
    @BindView(R.id.etBetSuccessInfor2)
    TextView etBetSubmitInfor2;
    @BindView(R.id.etBetSuccessInfor)
    TextView etBetSubmitInfor;
    @BindView(R.id.tvBetSuccessZHLimit)
    TextView tvBetSuccessZHLimit;
    @BindView(R.id.btnBetSuccessCancel)
    Button btnBetSuccessCancel;

    BetZHResult.DataBean betResult;
    String userMoney;
    private String getParam1,getParam2;
    public static BetOrderZHSubmitSuccessDialog newInstance(BetZHResult.DataBean betResult, String champion) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM1, champion);
        bundle.putParcelable(PARAM2, betResult);
        GameLog.log("champion : "+champion + " betResult "+betResult.toString());
        BetOrderZHSubmitSuccessDialog dialog = new BetOrderZHSubmitSuccessDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.dialog_bet_order_zh_submit_success;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        getParam1 =  getArguments().getString(PARAM1);
        betResult =  getArguments().getParcelable(PARAM2);
        betSuccessTitle.setText(betResult.getCaption()+"\n"+getString(R.string.games_prepare_bet_bill_zh_success));
        userMoney = GameShipHelper.formatMoney(betResult.getHavemoney());
        ACache.get(getContext()).put(HGConstant.USERNAME_REMAIN_MONEY,userMoney );
        EventBus.getDefault().post(new UserMoneyEvent(userMoney));
        tvSuccessUserMoney.setText(userMoney);
        etBetSubmitInfor1.setText(getString(R.string.games_prepare_bet_bill_number)+betResult.getOrder());
        etBetSubmitInfor2.setText(getString(R.string.games_prepare_bet_bill_money)+betResult.getGold()+"\n"+getString(R.string.games_prepare_bet_win_amount)+"："+GameShipHelper.formatMoney(betResult.getOrder_bet_amount()+""));
       /* StringBuilder stringBuilder = new StringBuilder();
        stringBuilder.append("编号：")
                .append(betResult.getOrder()).append("\n\n");*/
        String [] s_league = betResult.getS_league().split(",");
        String [] s_mb_team = betResult.getS_mb_team().split(",");
        String [] sign = betResult.getSign().split(",");
        String [] s_tg_team = betResult.getS_tg_team().split(",");
        String [] s_m_place = betResult.getS_m_place().split(",");
        String [] w_m_rate = betResult.getW_m_rate().split(",");
        /*if(betResult.getBtype().equals(",,,")||",,".equals(betResult.getBtype())){
            betResult.setBtype(" , , ,");
        }*/
        String [] btype = betResult.getBtype().replace(","," , ").split(",");
        BetZHData betZHData = new BetZHData();
        List<BetZHData.BetResultItemBean> betItem = new ArrayList<>();
        for(int k=0;k<s_league.length;++k){
            BetZHData.BetResultItemBean betResultItemBean = new BetZHData.BetResultItemBean();
            betResultItemBean.s_league = s_league[k];
            betResultItemBean.s_mb_team = s_mb_team[k];
            betResultItemBean.sign = sign[k];
            betResultItemBean.s_tg_team = s_tg_team[k];
            if(btype.length==0){
                betResultItemBean.s_m_place = s_m_place[k];
            }else{
                betResultItemBean.s_m_place = s_m_place[k]+btype[k];
            }
            betResultItemBean.w_m_rate = w_m_rate[k];
            betItem.add(betResultItemBean);
            /*stringBuilder.append(s_league[k]).append("\n\n")
                    .append(s_mb_team[k]).append(onMarkRed(sign[k])).append(s_tg_team[k]).append("\n\n")
                    .append(onMarkRed(s_m_place[k])).append("@").append(onMarkRed(w_m_rate[k])).append("\n\n").append("\n\n");*/
        }
        betZHData.setBetItem(betItem);

        /*stringBuilder.append("\n\n").append(betResult.getGold()).append("元\n\n可盈").append(betResult.getOrder_bet_amount()).append("\n\n");
        etBetSubmitInfor.setText(Html.fromHtml(stringBuilder.toString()));*/

        LinearLayoutManager gridLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        etBetSuccessInforList.setLayoutManager(gridLayoutManager);
        etBetSuccessInforList.setHasFixedSize(true);
        etBetSuccessInforList.setNestedScrollingEnabled(false);
        etBetSuccessInforList.setAdapter(new ZHBetListAdapter(getContext(), R.layout.item_order_zh,betZHData.getBetItem()));
        //tvBetSubmitLimit.setText("单注最低："+ ACache.get(getContext()).getAsString(HGConstant.USERNAME_BUY_MIN)+"元\n\n"+"单注最高："+ ACache.get(getContext()).getAsString(HGConstant.USERNAME_BUY_MAX)+"元");
    }

    public class ZHBetListAdapter extends AutoSizeRVAdapter<BetZHData.BetResultItemBean> {
        private Context context;

        public ZHBetListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }
        @Override
        protected void convert(ViewHolder holder, final BetZHData.BetResultItemBean rowsBean, int position) {
            GameLog.log("数据展示 "+rowsBean.s_league);
            holder.setText(R.id.itemZH1,  rowsBean.s_league);
            TextView textView2 =  holder.getView(R.id.itemZH2);
            TextView textView3 =  holder.getView(R.id.itemZH3);
            textView2.setText(Html.fromHtml(rowsBean.s_mb_team+onMarkRed(rowsBean.sign)+rowsBean.s_tg_team));
            textView3.setText(Html.fromHtml(onMarkRed(rowsBean.s_m_place)+"@"+onMarkRed(rowsBean.w_m_rate)));
            holder.setText(R.id.itemZH4, "" );
            holder.setVisible(R.id.itemZHClear,false);
        }
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#C9270B'>" + sign+"</font>";
    }

    @OnClick({R.id.btnBetSuccessCancel,R.id.tvBetSuccessZHLimit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btnBetSuccessCancel:
                hide();
                break;
            case R.id.tvBetSuccessZHLimit:
                BottombarViewManager.getSingleton().onCloseView();
                EventBus.getDefault().post(new CalosEvent());
                EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("today",userMoney), SupportFragment.SINGLETASK));
                hide();
                break;
        }
    }

}

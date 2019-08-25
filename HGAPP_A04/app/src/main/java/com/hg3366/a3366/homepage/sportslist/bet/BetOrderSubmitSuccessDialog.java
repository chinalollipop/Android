package com.hg3366.a3366.homepage.sportslist.bet;

import android.os.Bundle;
import android.text.Html;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.hg3366.a3366.R;
import com.hg3366.a3366.base.HGBaseDialogFragment;
import com.hg3366.a3366.common.util.ACache;
import com.hg3366.a3366.common.util.GameShipHelper;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.data.BetResult;
import com.hg3366.a3366.homepage.UserMoneyEvent;
import com.hg3366.a3366.homepage.handicap.BottombarViewManager;
import com.hg3366.a3366.personpage.betrecord.BetRecordFragment;
import com.hg3366.common.util.Check;
import com.hg3366.common.util.GameLog;

import org.greenrobot.eventbus.EventBus;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class BetOrderSubmitSuccessDialog extends HGBaseDialogFragment{

    public static final String PARAM1 = "champion";
    public static final String PARAM2 = "betResult";
    @BindView(R.id.betSuccessTitle)
    TextView betSuccessTitle;
    @BindView(R.id.tvSuccessUserMoney)
    TextView tvSuccessUserMoney;
    @BindView(R.id.etBetSuccessInfor)
    TextView etBetSubmitInfor;
    @BindView(R.id.tvBetSuccessLimit)
    TextView tvBetSubmitLimit;
    @BindView(R.id.btnBetSuccessCancel)
    Button btnBetSuccessCancel;

    BetResult.DataBean betResult;
    String userMoney;
    private String getParam1,getParam2;
    public static BetOrderSubmitSuccessDialog newInstance(BetResult.DataBean betResult,String champion) {
        Bundle bundle = new Bundle();
        bundle.putString(PARAM1, champion);
        bundle.putParcelable(PARAM2, betResult);
        GameLog.log("champion : "+champion + " betResult "+betResult.toString());
        BetOrderSubmitSuccessDialog dialog = new BetOrderSubmitSuccessDialog();
        dialog.setArguments(bundle);
        return dialog;
    }

    @Override
    protected int getLayoutResId() {
        return R.layout.dialog_bet_order_submit_success;
    }

    @Override
    protected void initView(View view, Bundle bundle) {
        getParam1 =  getArguments().getString(PARAM1);
        betResult =  getArguments().getParcelable(PARAM2);
        betSuccessTitle.setText(betResult.getCaption()+"\n交易成功");
        userMoney = GameShipHelper.formatMoney(betResult.getHavemoney());
        ACache.get(getContext()).put(HGConstant.USERNAME_REMAIN_MONEY, userMoney);
        EventBus.getDefault().post(new UserMoneyEvent(userMoney));
        tvSuccessUserMoney.setText(userMoney);
        StringBuilder stringBuilder = new StringBuilder();
        stringBuilder.append("编号：")
                .append(betResult.getOrder()).append("<br>").append(betResult.getS_sleague());
        if(Check.isEmpty(getParam1)){
            stringBuilder.append("<br>").append(onMarkRed(betResult.getInball())).append(betResult.getS_mb_team());
                    if(Check.isEmpty(betResult.getSign())){
                        if(!Check.isEmpty(betResult.getS_tg_team())){
                            stringBuilder.append(betResult.getS_tg_team()).append("<br>");
                        }
                    }else{
                        stringBuilder.append(onMarkRed(betResult.getSign())).append(betResult.getS_tg_team()).append("<br>");
                    }
        }else{
            stringBuilder.append("<br>").append(getParam1);
        }
        stringBuilder.append(onMarkRed(betResult.getS_m_place()+" @"+betResult.getW_m_rate()))
                .append("<br>").append(betResult.getGold()).append("元<br>可赢").append(GameShipHelper.formatMoney(betResult.getOrder_bet_amount()+""));
        etBetSubmitInfor.setText(Html.fromHtml(stringBuilder.toString()));
        //tvBetSubmitLimit.setText("单注最低："+ ACache.get(getContext()).getAsString(HGConstant.USERNAME_BUY_MIN)+"元<br>"+"单注最高："+ ACache.get(getContext()).getAsString(HGConstant.USERNAME_BUY_MAX)+"元");
    }

    //标记为红色
    private String onMarkRed(String sign){
        return " <font color='#C9270B'>" + sign+"</font>";
    }

    @OnClick({R.id.btnBetSuccessCancel,R.id.tvBetSuccessLimit})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.btnBetSuccessCancel:
                hide();
                break;
            case R.id.tvBetSuccessLimit:
                BottombarViewManager.getSingleton().onCloseView();
                EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("today",userMoney), SupportFragment.SINGLETASK));
                hide();
                break;
        }
    }

}

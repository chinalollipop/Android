package com.cfcp.a01.ui.home.withdraw;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.TextView;

import com.bigkoo.pickerview.builder.OptionsPickerBuilder;
import com.bigkoo.pickerview.listener.OnOptionsSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.WithDrawNextResult;
import com.cfcp.a01.data.WithDrawResult;
import com.cfcp.a01.ui.home.withdraw.submit.WithDrawSubmitFragment;
import com.cfcp.a01.ui.me.bankcard.CardFragment;
import com.cfcp.a01.ui.me.bankcard.ModifyFragment;

import org.greenrobot.eventbus.EventBus;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;
import butterknife.OnClick;
import butterknife.Unbinder;

public class WithDrawFragment extends BaseFragment implements WithDrawContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    @BindView(R.id.infoBack)
    NTitleBar infoBack;
    @BindView(R.id.withDrawMoney)
    TextView withDrawMoney;
    @BindView(R.id.withDrawDaMa)
    TextView withDrawDaMa;
    @BindView(R.id.withDrawDaMaData)
    TextView withDrawDaMaData;
    @BindView(R.id.withDrawBankCardList)
    TextView withDrawBankCardList;
    @BindView(R.id.infoAccount)
    EditText infoAccount;
    @BindView(R.id.infoAccountText)
    TextView infoAccountText;
    @BindView(R.id.withDrawNext)
    TextView withDrawNext;
    @BindView(R.id.withDrawAddCard)
    TextView withDrawAddCard;
    OptionsPickerView typeOptionsPicker;
    private String typeArgs2, typeArgs3;
    WithDrawContract.Presenter presenter;
    List<WithDrawResult.ABankCardsBean> aBankCardsBeanList;

    String id;
    String min,max;

    public static WithDrawFragment newInstance(String deposit_mode, String money) {
        WithDrawFragment betFragment = new WithDrawFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_withdraw;
    }

    //标记为红色
    private String onMarkRed(String sign) {
        return " <font color='#e13f51'>" + sign + "</font>";
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (null != getArguments()) {
            typeArgs2 = getArguments().getString(TYPE2);
            typeArgs3 = getArguments().getString(TYPE3);
        }
    }


    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        infoBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        withDrawMoney.setText(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BALANCE));
        presenter.getWithDraw();
    }


    //请求数据接口
    private void onRequsetData() {
        String money = infoAccount.getText().toString().trim();
        if (Check.isEmpty(money)) {
            showMessage("请输入取款金额");
            return;
        }
        if(money.compareTo(min)>=0&&money.compareTo(max)<0){
            presenter.getWithDrawNext(id,money);
        }else{
            showMessage("取款金额必须在"+min+"~"+max+"之间");
        }
    }


    @Override
    public void getWithDrawResult(WithDrawResult withDrawResult) {
        //转账前渠道确认

        List<WithDrawResult.AUserDepositGameBean.ADepositGameBean> aDepositGameBean = withDrawResult.getAUserDepositGame().getADepositGame();
        int size  = aDepositGameBean.size();
        GameLog.log("获取取款信息 成功 "+size);
        String damaString ="";
        for(int k=0;k<size;++k){
            damaString += aDepositGameBean.get(k).getDepositTime()+"充值/额外打码 "+aDepositGameBean.get(k).getDepositAmount()+" 元,之后 打码量"+
                    aDepositGameBean.get(k).getGameMoney()+
                    "官方盘"+aDepositGameBean.get(k).getProjectAmount()+
                    "信用盘"+aDepositGameBean.get(k).getBetMoney()+
                    "棋牌量"+aDepositGameBean.get(k).getKaiyuanGameCellScore()+","+
                    (aDepositGameBean.get(k).getIsEnough().equals("0")?"未达到":"已达到")+"\n";
        }
        if(!withDrawResult.getAUserDepositGame().isBEnough()){
            damaString += "还需要 "+withDrawResult.getAUserDepositGame().getFNeedGameAmount()+"打码量，才能出款"+"\n";
        }
        if(size==0){
            damaString += "尊敬的 "+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NAME)+",从上次" +
                    withDrawResult.getAUserDepositGame().getLastWithdrawalFinishTime()+"提现，截止到目前";
        }
        withDrawDaMaData.setText(damaString);
        aBankCardsBeanList = withDrawResult.getABankCards();
        /*if(Integer.parseInt(withDrawResult.getIBindedCardsNum())>aBankCardsBeanList.size()){
            withDrawAddCard.setVisibility(View.VISIBLE);
        }*/
        withDrawBankCardList.setText(aBankCardsBeanList.get(0).getPickerViewText());
        id = aBankCardsBeanList.get(0).getId();
        min = withDrawResult.getIMinWithdrawAmount();
        max = withDrawResult.getIMaxWithdrawAmount();
        typeOptionsPicker = new OptionsPickerBuilder(getContext(),new OnOptionsSelectListener(){

            @Override
            public void onOptionsSelect(int options1, int options2, int options3, View v) {
                //1、tab做相应的切换
                // 2、下面做查询数据的请求和展示
                String text =  aBankCardsBeanList.get(options1).getPickerViewText();
                withDrawBankCardList.setText(text);
                id = aBankCardsBeanList.get(options1).getId();
            }
        }).build();
        typeOptionsPicker.setPicker(aBankCardsBeanList);
        //ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_NAME,infoAccount.getText().toString().trim());
    }

    @Override
    public void getWithDrawNextResult(WithDrawNextResult withDrawNextResult) {
        GameLog.log("取款输入金额点击下一步  成功");
        EventBus.getDefault().post(new StartBrotherEvent(WithDrawSubmitFragment.newInstance(withDrawNextResult,"")));
    }

    @Override
    public void setPresenter(WithDrawContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
    }


    @OnClick({R.id.withDrawDaMa, R.id.withDrawBankCardList, R.id.withDrawNext, R.id.withDrawAddCard})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.withDrawDaMa:
                if(withDrawDaMaData.isShown()){
                    withDrawDaMaData.setVisibility(View.GONE);
                }else{
                    withDrawDaMaData.setVisibility(View.VISIBLE);
                }
                break;
            case R.id.withDrawBankCardList:
                typeOptionsPicker.show();
                break;
            case R.id.withDrawNext:
                onRequsetData();
                break;
            case R.id.withDrawAddCard:
                //EventBus.getDefault().post(new StartBrotherEvent(ModifyFragment.newInstance(null,"3",null)));
                EventBus.getDefault().post(new StartBrotherEvent(CardFragment.newInstance("","")));
                break;
        }
    }
}

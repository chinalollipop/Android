package com.cfcp.a01.ui.me.bankcard;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.alibaba.fastjson.JSON;
import com.bigkoo.pickerview.builder.TimePickerBuilder;
import com.bigkoo.pickerview.listener.OnTimeSelectListener;
import com.bigkoo.pickerview.view.OptionsPickerView;
import com.bigkoo.pickerview.view.TimePickerView;
import com.bumptech.glide.Glide;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.http.Client;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.widget.NTitleBar;
import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BankCardListResult;
import com.cfcp.a01.data.CouponResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.ui.event.EventFragment;
import com.cfcp.a01.ui.me.info.InfoFragment;
import com.cfcp.a01.ui.me.pwd.PwdFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;
import com.kongzue.dialog.v2.WaitDialog;

import org.greenrobot.eventbus.EventBus;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;

import static com.cfcp.a01.common.utils.ACache.get;

public class CardFragment extends BaseFragment implements CardContract.View {

    private static final String TYPE1 = "type1";
    private static final String TYPE2 = "type2";
    private static final String TYPE3 = "type3";
    private String typeArgs2,typeArgs3;
    CardContract.Presenter presenter;
    @BindView(R.id.cardBack)
    NTitleBar cardBack;
    @BindView(R.id.cardRView)
    RecyclerView cardRView;
    @BindView(R.id.cardAddBank)
    TextView cardAddBank;
    @BindView(R.id.cardAddAlready)
    TextView cardAddAlready;
    //代表彩种ID
    private String  lotteryId = "1";
    String startTime,endTime;
    List<BankCardListResult.ABankCardsBean> aBankCardsBeans;
    BankCardListResult.ABankCardsBean aBankCardsBean;
    //官方盘的列表
    private List<AllGamesResult.DataBean.LotteriesBean> AvailableLottery  = new ArrayList<>();

    public static CardFragment newInstance(String deposit_mode, String money) {
        CardFragment betFragment = new CardFragment();
        Bundle args = new Bundle();
        args.putString(TYPE2, deposit_mode);
        args.putString(TYPE3, money);
        betFragment.setArguments(args);
        Injections.inject(betFragment, null);
        return betFragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_card;
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



    public static String getTime(Date date) {
        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
        return format.format(date);
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext(), OrientationHelper.VERTICAL,false);
        cardRView.setLayoutManager(linearLayoutManager);
        cardBack.setBackListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        WaitDialog.show(getActivity(),"加载中...");
    }

    //请求数据接口
    private void onRequsetData(){
        presenter.getBankCardList();
    }

    @Override
    public void getBankCardListResult(BankCardListResult bankCardListResult) {
        GameLog.log("个人区间报表 成功");
        WaitDialog.dismiss();
        aBankCardsBeans = bankCardListResult.getABankCards();
        BankCardListAdapter bankCardListAdapter =   new BankCardListAdapter(R.layout.item_card,aBankCardsBeans);
        if(aBankCardsBeans.size()==0){
            //无数据的展示 空布局
           // cardRView.setAdapter();
            View view = LayoutInflater.from(getContext()).inflate(R.layout.item_card_nodata, null);
            bankCardListAdapter.setEmptyView(view);
            cardAddBank.setVisibility(View.VISIBLE);
            cardAddAlready.setVisibility(View.GONE);
        }else{
            if(aBankCardsBeans.size()>=bankCardListResult.getILimitCardsNum()){
                cardAddBank.setVisibility(View.GONE);
            }else{
                cardAddBank.setVisibility(View.VISIBLE);
            }
            cardAddAlready.setText("一个游戏账户最多绑定 "+bankCardListResult.getILimitCardsNum()+" 张银行卡， 您目前绑定了"+aBankCardsBeans.size()+
                    " 张卡，还可以绑定 "+(bankCardListResult.getILimitCardsNum()-aBankCardsBeans.size())+" 张。\n 银行卡信息锁定后，不能增加新卡绑定，已绑定的银行卡信息不能进行修改和删除。");
            cardAddAlready.setVisibility(View.VISIBLE);
            //有数据的展示
            bankCardListAdapter.setOnItemChildClickListener(new BaseQuickAdapter.OnItemChildClickListener() {
                @Override
                public void onItemChildClick(BaseQuickAdapter adapter, View view, int position) {
                    switch (view.getId()){
                        case R.id.cardDetail:
                            if(aBankCardsBeans.get(position).isChecked()){
                                aBankCardsBeans.get(position).setChecked(false);
                            }else{
                                aBankCardsBeans.get(position).setChecked(true);
                            }
                            adapter.notifyDataSetChanged();
                            break;
                        case R.id.cardModify:
                            EventBus.getDefault().post(new StartBrotherEvent(ModifyFragment.newInstance(aBankCardsBeans.get(position),"1",aBankCardsBeans)));
                            break;
                        case R.id.cardDelete:
                            aBankCardsBean = aBankCardsBeans.get(position);
                            presenter.getDeleteCard(aBankCardsBeans.get(position).getId()+"");
                            break;
                    }
                }
            });
        }
        cardRView.setAdapter(bankCardListAdapter);
    }

    @Override
    public void getDeleteCardResult() {
        EventBus.getDefault().post(new StartBrotherEvent(ModifyFragment.newInstance(aBankCardsBean,"2",aBankCardsBeans)));
    }

    class BankCardListAdapter extends BaseQuickAdapter<BankCardListResult.ABankCardsBean, BaseViewHolder> {

        public BankCardListAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder, final BankCardListResult.ABankCardsBean data) {
            if(!data.isChecked()){
                holder.setGone(R.id.cardDetailLay,false);
            }else{
                holder.setVisible(R.id.cardDetailLay,true);
            }

            holder.setText(R.id.cardUserName,data.getAccount_name()).
                    setText(R.id.cardBankName,data.getBank()).
                    setText(R.id.cardState,(data.getStatus()==1)?"使用中":"锁定").
                    setText(R.id.cardAccount,"**** **** **** "+data.getAccount().substring(data.getAccount().length()-4)).
                    setText(R.id.itemCardTime,data.getCreated_at()).
                    addOnClickListener(R.id.cardDetail).
                    addOnClickListener(R.id.cardModify).
                    addOnClickListener(R.id.cardDelete);
        }
    }


    @Override
    public void setPresenter(CardContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        onRequsetData();
    }

    @OnClick({R.id.cardAddBank})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.cardAddBank:
                //先判断是否有资金密码然后看有名字
                String pwd = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_FUND_PWD);
                GameLog.log("资金密码的状态 "+pwd);
                if(!Check.isEmpty(pwd)&&pwd.equals("0")){
                    EventBus.getDefault().post(new StartBrotherEvent(PwdFragment.newInstance("2","")));
                }else if(Check.isEmpty(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NAME))){
                    EventBus.getDefault().post(new StartBrotherEvent(InfoFragment.newInstance("","")));
                }else{
                    /**
                     * PS：
                     * 1、如果不是第一次绑卡，则需要验证卡信息。
                     * 2、如果是修改卡信息，则需要验证旧卡信息。
                     */
                    if(!Check.isNull(aBankCardsBeans)&&aBankCardsBeans.size()>0){
                        EventBus.getDefault().post(new StartBrotherEvent(ModifyFragment.newInstance(aBankCardsBeans.get(0),"3",aBankCardsBeans)));
                    }else{
                        EventBus.getDefault().post(new StartBrotherEvent(AddCardFragment.newInstance("1","")));
                    }
                }
                break;
        }
    }
}

package com.sands.corp.depositpage;

import android.content.Context;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.View;
import android.widget.ListView;

import com.sands.corp.Injections;
import com.sands.corp.R;
import com.sands.corp.base.HGBaseFragment;
import com.sands.corp.base.IPresenter;
import com.sands.corp.common.util.ACache;
import com.sands.corp.common.util.GameShipHelper;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.widgets.NTitleBar;
import com.sands.corp.data.DepositAliPayQCCodeResult;
import com.sands.corp.data.DepositBankCordListResult;
import com.sands.corp.data.DepositListResult;
import com.sands.corp.data.DepositThirdBankCardResult;
import com.sands.corp.data.DepositThirdQQPayResult;
import com.sands.corp.data.LoginResult;
import com.sands.corp.depositpage.aliqcpay.AliQCPayFragment;
import com.sands.corp.depositpage.companypay.CompanyPayFragment;
import com.sands.corp.depositpage.thirdbankcardpay.ThirdbankCardFragment;
import com.sands.corp.depositpage.thirdmobilepay.OnlinePlayFragment;
import com.sands.corp.depositpage.thirdmobilepay.ThirdMobilePayFragment;
import com.sands.corp.homepage.UserMoneyEvent;
import com.sands.corp.personpage.realname.RealNameFragment;
import com.sands.common.util.Check;
import com.sands.common.util.GameLog;
import com.zhy.adapter.abslistview.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class DepositFragment extends HGBaseFragment implements DepositeContract.View{

    @BindView(R.id.tvDepositUserMoney)
    NTitleBar tvDepositUserMoney;
    @BindView(R.id.lvDeposit)
    ListView lvDeposit;
    private String userMoney;
    private int payId;
    private DepositeContract.Presenter presenter;

    public static DepositFragment newInstance() {
        DepositFragment fragment = new DepositFragment();
        Bundle args = new Bundle();
        Injections.inject(null,fragment);
        fragment.setArguments(args);
        return fragment;
    }

    @Override
    public void onAttach(Context context) {
        super.onAttach(context);
        EventBus.getDefault().register(this);
    }

    @Override
    public void onDetach() {
        super.onDetach();
        EventBus.getDefault().unregister(this);
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {

        GameLog.log("存款获取的用户余额："+loginResult.getMoney());
        if(!Check.isEmpty(loginResult.getMoney())){
            userMoney = GameShipHelper.formatMoney(loginResult.getMoney());
            tvDepositUserMoney.setMoreText(userMoney);
        }
    }

    @Subscribe
    public void onEventMain(UserMoneyEvent userMoneyEvent){
        userMoney = userMoneyEvent.money;
        tvDepositUserMoney.setMoreText(userMoney);
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_deposit;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {

    }

    @Override
    public void postDepositListResult(DepositListResult message) {
        GameLog.log("存款方式列表 的数据大小"+message.getData().size());
        lvDeposit.setAdapter(new DepositFragment.DepositListAdapter(getContext(),R.layout.item_deposit,message.getData()));

    }

    @Override
    public void postDepositBankCordListResult(DepositBankCordListResult message) {
        GameLog.log("公司入款："+message.getData().size());
        EventBus.getDefault().post(new StartBrotherEvent(CompanyPayFragment.newInstance(message,userMoney), SupportFragment.SINGLETASK));

    }

    @Override
    public void postDepositAliPayQCCodeResult(DepositAliPayQCCodeResult message) {
        GameLog.log("支付宝二维码大小："+message.getData().size()+"金额："+userMoney);
        EventBus.getDefault().post(new StartBrotherEvent(AliQCPayFragment.newInstance(message,userMoney,payId), SupportFragment.SINGLETASK));
    }

    @Override
    public void postDepositThirdBankCardResult(DepositThirdBankCardResult message) {
        GameLog.log("第三方银行卡线上："+message.getData().size());
        EventBus.getDefault().post(new StartBrotherEvent(ThirdbankCardFragment.newInstance(message.getData().get(0),userMoney), SupportFragment.SINGLETASK));

    }

    @Override
    public void postDepositThirdWXPayResult(DepositThirdQQPayResult message) {
        GameLog.log("第三方微信支付大小："+message.getData().size());
        EventBus.getDefault().post(new StartBrotherEvent(ThirdMobilePayFragment.newInstance(message.getData().get(0),userMoney,payId), SupportFragment.SINGLETASK));

    }

    @Override
    public void postDepositThirdAliPayResult(DepositThirdQQPayResult message) {
        GameLog.log("第三方支付宝支付大小："+message.getData().size());
        EventBus.getDefault().post(new StartBrotherEvent(ThirdMobilePayFragment.newInstance(message.getData().get(0),userMoney,payId), SupportFragment.SINGLETASK));

    }

    @Override
    public void postDepositThirdQQPayResult(DepositThirdQQPayResult message) {

        GameLog.log("第三方QQ支付大小："+message.getData().size());

        EventBus.getDefault().post(new StartBrotherEvent(ThirdMobilePayFragment.newInstance(message.getData().get(0),userMoney,payId), SupportFragment.SINGLETASK));
    }

    @Override
    public void setPresenter(DepositeContract.Presenter presenter) {

        this.presenter = presenter;
    }

    @Override
    public void onVisible() {
        super.onVisible();
        presenter.postDepositList("");
    }


    public class DepositListAdapter extends com.sands.corp.common.adapters.AutoSizeAdapter<DepositListResult.DataBean> {
        private Context context;

        public DepositListAdapter(Context context, int layoutId, List datas) {
            super(context, layoutId, datas);
            this.context = context;
        }

        @Override
        protected void convert(ViewHolder holder, final DepositListResult.DataBean dataBean, final int position) {
            holder.setText(R.id.tvDepositItem, dataBean.getTitle());
            switch (dataBean.getId()){
                case 0:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_union);
                    break;
                case 1:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_union);
                    break;
                case 2:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_atm);
                    break;
                case 3:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_wechat);
                    break;
                case 4:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_ali);
                    break;
                case 5:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_qq);
                    break;
                case 6:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_ali_code);
                    break;
                case 7:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.deposit_wechat_code);
                    break;
                case 8:
                    holder.setBackgroundRes(R.id.ivDepositItem,R.mipmap.u_pay);
                    break;

            }

            holder.setOnClickListener(R.id.llDepositItem, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    //EventBus.getDefault().post(new DepositEvent(dataBean.getId()));
                    String alias = ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS);
                    if(Check.isEmpty(alias)){
                        EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(userMoney,""), SupportFragment.SINGLETASK));
                        return;
                    }
                    onListenerDeposit(dataBean.getId(),dataBean.getBankid(),dataBean.getApi());
                }
            });

        }
    }

    private void onListenerDeposit(int id,String bankid,String api){
        payId = id;
        GameLog.log("当前支付的ID是： "+id);
        switch (id){
            case 0://快速充值
                //直接跳转到支付页面
                EventBus.getDefault().post(new StartBrotherEvent(OnlinePlayFragment.newInstance(api,"","","",""), SupportFragment.SINGLETASK));
                break;
            case 1://银行卡线上
                presenter.postDepositThirdBankCard("");
                break;
            case 2://公司入款
                presenter.postDepositBankCordList("");
                break;
            case 3://微信第三方
                presenter.postDepositThirdWXPay("");
                break;
            case 4://支付宝第三方
                presenter.postDepositThirdAliPay("");
                break;
            case 5://QQ第三方
                presenter.postDepositThirdQQPay("");
                break;
            case 6://支付宝扫码
                presenter.postDepositAliPayQCCode("",bankid);
                break;
            case 7://微信扫码
                presenter.postDepositWechatQCCode("",bankid);
                break;
            case 8://云闪付
                presenter.postDepositThirdUQCCode("",bankid);
                break;
        }
    }

}

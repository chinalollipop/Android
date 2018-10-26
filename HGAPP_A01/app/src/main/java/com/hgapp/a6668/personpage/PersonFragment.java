package com.hgapp.a6668.personpage;

import android.content.Context;
import android.content.pm.PackageInfo;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import com.hgapp.a6668.Injections;
import com.hgapp.a6668.R;
import com.hgapp.a6668.base.HGBaseFragment;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.common.event.LogoutEvent;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.GameShipHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.widgets.GridRvItemDecoration;
import com.hgapp.a6668.common.widgets.NTitleBar;
import com.hgapp.a6668.data.CPResult;
import com.hgapp.a6668.data.LoginResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.data.PersonInformResult;
import com.hgapp.a6668.data.QipaiResult;
import com.hgapp.a6668.homepage.UserMoneyEvent;
import com.hgapp.a6668.homepage.handicap.ShowMainEvent;
import com.hgapp.a6668.personpage.accountcenter.AccountCenterFragment;
import com.hgapp.a6668.personpage.balanceplatform.BalancePlatformFragment;
import com.hgapp.a6668.personpage.balancetransfer.BalanceTransferFragment;
import com.hgapp.a6668.personpage.betrecord.BetRecordFragment;
import com.hgapp.a6668.personpage.bindingcard.BindingCardFragment;
import com.hgapp.a6668.personpage.depositrecord.DepositRecordFragment;
import com.hgapp.a6668.personpage.realname.RealNameFragment;
import com.hgapp.a6668.withdrawPage.WithdrawFragment;
import com.hgapp.common.util.Check;
import com.hgapp.common.util.GameLog;
import com.hgapp.common.util.PackageUtil;
import com.hgapp.common.util.Timber;
import com.hgapp.common.util.Utils;
import com.zhy.adapter.recyclerview.base.ViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;
import me.yokeyword.sample.demo_wechat.event.StartBrotherEvent;

public class PersonFragment extends HGBaseFragment implements PersonContract.View {

    @BindView(R.id.tvPersonBack)
    NTitleBar tvPersonBack;
    @BindView(R.id.rvMyList)
    RecyclerView rvMyList;
    @BindView(R.id.tvPersonUsername)
    TextView tvPersonUsername;
    @BindView(R.id.personRefresh)
    TextView personRefresh;
    @BindView(R.id.tvPersonHg)
    TextView tvPersonHg;
    @BindView(R.id.personLogout)
    TextView personLogout;
    @BindView(R.id.personVersion)
    TextView personVersion;
    private String personMoney;
    private PersonBalanceResult personBalance;
    private PersonContract.Presenter presenter;
    private static List<String> myList = new ArrayList<String>();
    static {
        myList.add("充值");
        myList.add("额度转换");
        myList.add("银行卡");
        myList.add("提现");
        myList.add("平台余额");
        myList.add("站内信");
        myList.add("账户中心");
        //myList.add("转账记录");
        myList.add("投注记录");
        //myList.add("交易记录");
        myList.add("流水记录");
        //myList.add("登出");

    }

    public static PersonFragment newInstance() {
        PersonFragment fragment = new PersonFragment();
        Bundle args = new Bundle();
        fragment.setArguments(args);
        Injections.inject(null, fragment);
        return fragment;
    }

    @Override
    public int setLayoutId() {
        return R.layout.fragment_person;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),3, OrientationHelper.VERTICAL,false);
        rvMyList.setLayoutManager(gridLayoutManager);
        rvMyList.addItemDecoration(new GridRvItemDecoration(getContext()));
        rvMyList.setAdapter(new RvMylistAdapter(getContext(),R.layout.item_person,myList));
        PackageInfo packageInfo =  PackageUtil.getAppPackageInfo(Utils.getContext());
        if(null == packageInfo)
        {
            Timber.e("检查更新失败，获取不到app版本号");
            throw new RuntimeException("检查更新失败，获取不到app版本号");
        }
        String localver = packageInfo.versionName;
        GameLog.log("当前APP的版本号是："+localver);
        personVersion.setText("V:"+localver);
    }

    class RvMylistAdapter extends com.hgapp.a6668.common.adapters.AutoSizeRVAdapter<String>{
        private Context context;
        public RvMylistAdapter(Context context, int layoutId,List<String> datas){
            super(context, layoutId, datas);
            this.context =  context;
        }
        @Override
        protected void convert(ViewHolder holder, String string,final int position) {

            holder.setOnClickListener(R.id.llItemMySelf, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    GameLog.log("用户的金额："+personMoney);
                    switch (position){
                        case 0:
                            //EventBus.getDefault().post(new StartBrotherEvent(MainFragment.newInstance("person_to_deposit",""), SupportFragment.SINGLETASK));
                            EventBus.getDefault().post(new ShowMainEvent(1));
                            break;
                        case 1:
                            EventBus.getDefault().post(new StartBrotherEvent(BalanceTransferFragment.newInstance(personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 2:
                            EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            break;
                        case 3:

                            String alias = ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS);
                            if(Check.isEmpty(alias)){
                                EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                                return;
                            }
                            String userStatus = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT)+HGConstant.USERNAME_BIND_CARD);
                            //ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT+loginResult.getUserName()+, loginResult.getBindCard_Flag());
                            GameLog.log("用户是否已经绑定过银行卡："+userStatus);
                            if("0".equals(userStatus)){
                                showMessage("请先绑定银行卡！");
                                EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            }else{
                                EventBus.getDefault().post(new StartBrotherEvent(WithdrawFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            }
                            break;
                        case 4:
                            EventBus.getDefault().post(new StartBrotherEvent(BalancePlatformFragment.newInstance(personBalance), SupportFragment.SINGLETASK));
                            break;
                        case 5:

                            break;
                        case 6:
                            EventBus.getDefault().post(new StartBrotherEvent(AccountCenterFragment.newInstance(personMoney)));
                            break;
                        case 7:
                            //投注记录
                            EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("today",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 8://交易记录
                            EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("T",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 9://废弃了 暂时无用
                            EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            //EventBus.getDefault().post(new StartBrotherEvent(FlowingRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 10://废弃了 暂时无用
                            presenter.logOut();
                        /*case 9:
                            //交易记录
                            EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 10:
                            EventBus.getDefault().post(new StartBrotherEvent(FlowingRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 11:
                            presenter.logOut();*/
                            break;

                    }
                }
            });
            holder.setText(R.id.tvItemMyName,string);
            switch (position){
                case 0:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_deposit);
                    break;
                case 1:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_transfer);
                    break;
                case 2:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_bank_card);
                    break;
                case 3:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_withdraw);
                    break;
                case 4:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_balance);
                    break;
                case 5:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_message);
                    break;
                case 6:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_psersion);
                    break;
                case 7:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_deal_record);
                    //holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_transfer_record);
                    break;
                case 8:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_running_record);
                    //holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_bet_record);
                    break;
                case 9:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_deal_record);
                    break;
                case 10:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_running_record);
                    break;
                case 11:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_logout);
                    break;

            }

        }


    }




    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postPersonInformResult(PersonInformResult personInformResult) {

        //tvPersonUsername.setText(personInformResult.getUsername());
        personMoney = GameShipHelper.formatMoney(personInformResult.getBalance_hg());
        GameLog.log("成功获取用户个人信心");
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        this.personBalance = personBalance;
        personMoney = GameShipHelper.formatMoney(personBalance.getBalance_hg());
        tvPersonHg.setText(personMoney);
        EventBus.getDefault().post(new UserMoneyEvent(personMoney));
        tvPersonBack.setMoreText(personMoney);
        GameLog.log("成功获取用户余额信息");
    }

    @Override
    public void postQipaiResult(QipaiResult qipaiResult) {

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

        GameLog.log("我的获取的用户余额："+loginResult.getMoney());
        if(!Check.isEmpty(loginResult.getMoney())){
            personMoney = GameShipHelper.formatMoney(loginResult.getMoney());
            tvPersonBack.setMoreText(personMoney);
            tvPersonUsername.setText(loginResult.getUserName());
            tvPersonHg.setText(personMoney);
        }
    }

    @Override
    public void postPersonLogoutResult(String message) {
        showMessage(message);
        //EventBus.getDefault().post(new StartBrotherEvent(MainFragment.newInstance("person_to_home",""), SupportFragment.SINGLETASK));
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT), "0");
        //ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT, "");
        ACache.get(getContext()).put(HGConstant.APP_CP_COOKIE,"");
        ACache.get(getContext()).put(HGConstant.USERNAME_ALIAS, "");
        ACache.get(getContext()).put(HGConstant.USERNAME_LOGOUT, "true");
        EventBus.getDefault().post(new LogoutEvent(message));
    }

    @Override
    public void postCPResult(CPResult cpResult) {

    }

    @Override
    public void setPresenter(PersonContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    public void onVisible() {
        super.onVisible();

        presenter.getPersonBalance("", "");
        presenter.getPersonInform("");
        /*String userStatus = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT));
        GameLog.log("用户的登录状态 [ 1登录成功 ] [ 0 未登录 ] ："+userStatus);
        if("1".equals(userStatus)){
            presenter.getPersonBalance("", "");
            presenter.getPersonInform("");
        }else{
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
        }*/
    }
    @OnClick(R.id.personLogout)
    public void onLogout(){
        presenter.logOut();
    }

    @OnClick(R.id.personRefresh)
    public void onPersonRefresh(){
        presenter.getPersonBalance("","");
    }
}

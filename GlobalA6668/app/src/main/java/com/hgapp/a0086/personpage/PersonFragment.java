package com.hgapp.a0086.personpage;

import android.content.Context;
import android.content.pm.PackageInfo;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import com.hgapp.a0086.HGApplication;
import com.hgapp.a0086.Injections;
import com.hgapp.a0086.R;
import com.hgapp.a0086.base.HGBaseFragment;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.common.event.LogoutEvent;
import com.hgapp.a0086.common.http.Client;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.GameShipHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.widgets.RoundCornerImageView;
import com.hgapp.a0086.data.CPResult;
import com.hgapp.a0086.data.LoginResult;
import com.hgapp.a0086.data.NoticeResult;
import com.hgapp.a0086.data.PersonBalanceResult;
import com.hgapp.a0086.data.PersonInformResult;
import com.hgapp.a0086.data.QipaiResult;
import com.hgapp.a0086.homepage.HomePageIcon;
import com.hgapp.a0086.homepage.UserMoneyEvent;
import com.hgapp.a0086.homepage.handicap.ShowMainEvent;
import com.hgapp.a0086.homepage.noticelist.NoticeListFragment;
import com.hgapp.a0086.homepage.online.ContractFragment;
import com.hgapp.a0086.homepage.online.OnlineFragment;
import com.hgapp.a0086.personpage.accountcenter.AccountCenterFragment;
import com.hgapp.a0086.personpage.balanceplatform.BalancePlatformFragment;
import com.hgapp.a0086.personpage.balancetransfer.BalanceTransferFragment;
import com.hgapp.a0086.personpage.betrecord.BetRecordFragment;
import com.hgapp.a0086.personpage.bindingcard.BindingCardFragment;
import com.hgapp.a0086.personpage.depositrecord.DepositRecordFragment;
import com.hgapp.a0086.personpage.realname.RealNameFragment;
import com.hgapp.a0086.withdrawPage.WithdrawFragment;
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
    TextView tvPersonBack;
    @BindView(R.id.rvMyList)
    RecyclerView rvMyList;
    @BindView(R.id.tvPersonUsername)
    TextView tvPersonUsername;
    @BindView(R.id.personAdItem)
    RoundCornerImageView personAd;
    @BindView(R.id.personAgent)
    RoundCornerImageView personAgent;
    @BindView(R.id.personRefresh)
    TextView personRefresh;
    @BindView(R.id.tvPersonHg)
    TextView tvPersonHg;
    @BindView(R.id.personLogout)
    TextView personLogout;
    @BindView(R.id.personJoinDays)
    TextView personJoinDays;
    @BindView(R.id.personVersion)
    TextView personVersion;
    private String personMoney;
    private PersonBalanceResult personBalance;
    private NoticeResult noticeResultList;
    private PersonContract.Presenter presenter;
    private  List<HomePageIcon> myList = new ArrayList<HomePageIcon>();
    private void initData() {
        myList.clear();
        myList.add(new HomePageIcon(getString(R.string.deposite_money),R.mipmap.icon_my_deposit,0));
        myList.add(new HomePageIcon(getString(R.string.me_exchange),R.mipmap.icon_my_transfer,1));
        myList.add(new HomePageIcon(getString(R.string.me_bankcode),R.mipmap.icon_my_bank_card,2));
        myList.add(new HomePageIcon(getString(R.string.deposit_record_with),R.mipmap.icon_my_withdraw,3));
        myList.add(new HomePageIcon(getString(R.string.me_message),R.mipmap.icon_my_message,4));
        myList.add(new HomePageIcon(getString(R.string.me_accountcenter),R.mipmap.icon_my_psersion,5));
        myList.add(new HomePageIcon(getString(R.string.me_bettingrecord),R.mipmap.icon_my_deal_record,6));
        myList.add(new HomePageIcon(getString(R.string.me_flowrecord),R.mipmap.icon_my_running_record,7));
        myList.add(new HomePageIcon(getString(R.string.me_newaccount),R.mipmap.icon_my_new,8));
        myList.add(new HomePageIcon(getString(R.string.plat_contracts),R.mipmap.icon_my_contract,9));
        myList.add(new HomePageIcon(getString(R.string.plat_agents),R.mipmap.icon_my_agent,10));
        myList.add(new HomePageIcon(getString(R.string.plast_hg_remind),R.mipmap.icon_my_gonggao,11));
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
        initData();
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),4, OrientationHelper.VERTICAL,false);
        rvMyList.setLayoutManager(gridLayoutManager);
        //rvMyList.addItemDecoration(new GridRvItemDecoration(getContext()));
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

    private void showRealAccountMessage(){
        showMessage(getString(R.string.comm_pls_register_real_acccount));
    }

    class RvMylistAdapter extends com.hgapp.a0086.common.adapters.AutoSizeRVAdapter<HomePageIcon>{
        private Context context;
        public RvMylistAdapter(Context context, int layoutId,List<HomePageIcon> datas){
            super(context, layoutId, datas);
            this.context =  context;
        }
        @Override
        protected void convert(ViewHolder holder,final HomePageIcon data,final int position) {
            holder.setText(R.id.tvItemMyName,data.getIconName());
            holder.setImageResource(R.id.ivItemMyImage,data.getIconId());
            holder.setOnClickListener(R.id.llItemMySelf, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    GameLog.log("用户的金额："+personMoney);
                    switch (data.getId()){
                        case 0:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showRealAccountMessage();
                                return;
                            }
                            //EventBus.getDefault().post(new StartBrotherEvent(MainFragment.newInstance("person_to_deposit",""), SupportFragment.SINGLETASK));
                            EventBus.getDefault().post(new ShowMainEvent(1));
                            break;
                        case 1:
                           /* if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage("非常抱歉，请您注册真实会员！");
                                return;
                            }*/
                            EventBus.getDefault().post(new StartBrotherEvent(BalanceTransferFragment.newInstance(personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 2:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showRealAccountMessage();
                                return;
                            }
                            EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            break;
                        case 3:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showRealAccountMessage();
                                return;
                            }
                            String alias = ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS);
                            if(Check.isEmpty(alias)){
                                EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                                return;
                            }
                            String userStatus = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT)+HGConstant.USERNAME_BIND_CARD);
                            //ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT+loginResult.getUserName()+, loginResult.getBindCard_Flag());
                            GameLog.log("用户是否已经绑定过银行卡："+userStatus);
                            if("0".equals(userStatus)){
                                showMessage(getString(R.string.bcard_user_first));
                                EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            }else{
                                EventBus.getDefault().post(new StartBrotherEvent(WithdrawFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            }
                            break;
                        case 4:
                            /*if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage("非常抱歉，请您注册真实会员！");
                                return;
                            }*/
                            showMessage(getString(R.string.me_stay_tuned));
                            //EventBus.getDefault().post(new StartBrotherEvent(BalancePlatformFragment.newInstance(personBalance), SupportFragment.SINGLETASK));
                            break;
                        case 5:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showRealAccountMessage();
                                return;
                            }
                            EventBus.getDefault().post(new StartBrotherEvent(AccountCenterFragment.newInstance(personMoney)));
                            break;
                        case 6://投注记录
                            EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("today",personMoney), SupportFragment.SINGLETASK));

                            break;
                        case 7://交易记录
                            EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("T",personMoney), SupportFragment.SINGLETASK));

                            break;
                        case 8://新手教学
                            EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+ ACache.get(getContext()).getAsString("login_must_tpl_name")+"help.php?tip=app")));
                            break;
                        case 9://联系我们
                            EventBus.getDefault().post(new StartBrotherEvent(ContractFragment.newInstance(personMoney,
                                    ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_QQ),
                                    ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_WECHAT))));
                            break;
                        case 10://代理加盟
                            EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+ ACache.get(getContext()).getAsString("login_must_tpl_name")+"agents_reg.php?tip=app")));
                            break;
                        //presenter.logOut();
                        case 11://皇冠公告
                            //presenter.logOut();
                            if(Check.isNull(noticeResultList)) {
                                presenter.postNoticeList("");
                            }else{
                                EventBus.getDefault().post(new StartBrotherEvent(NoticeListFragment.newInstance(noticeResultList,"","")));
                            }
                            //showMessage("敬请期待！");
                            break;
                    }
                }
            });

        }


    }


    @Override
    public void postNoticeListResult(NoticeResult noticeResult) {

        noticeResultList =noticeResult;
        EventBus.getDefault().post(new StartBrotherEvent(NoticeListFragment.newInstance(noticeResultList,"","")));
    }


    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    @Override
    public void postPersonInformResult(PersonInformResult personInformResult) {

        //tvPersonUsername.setText(personInformResult.getUsername());
        personMoney = GameShipHelper.formatMoney(personInformResult.getBalance_hg());
        personJoinDays.setText(getString(R.string.app_name) +" "+personInformResult.getJoinDays()+getString(R.string.games_sign_comm_day));
        GameLog.log("成功获取用户个人信心");
    }

    @Override
    public void postPersonBalanceResult(PersonBalanceResult personBalance) {
        this.personBalance = personBalance;
        personMoney = GameShipHelper.formatMoney(personBalance.getBalance_hg());
        tvPersonHg.setText(personMoney);
        EventBus.getDefault().post(new UserMoneyEvent(personMoney));
        tvPersonBack.setText(personMoney);
        GameLog.log("成功获取用户余额信息");
    }

    @Override
    public void postQipaiResult(QipaiResult qipaiResult) {

    }

    @Override
    public void postHgQipaiResult(QipaiResult qipaiResult) {

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
            tvPersonBack.setText(personMoney);
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                tvPersonUsername.setText(getString(R.string.me_test_player));
            }else{
                tvPersonUsername.setText(loginResult.getUserName());
            }
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
        if(!Check.isNull(presenter)) {
            presenter.getPersonBalance("", "");
            presenter.getPersonInform("");
        }
        /*String userStatus = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_STATUS+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT));
        GameLog.log("用户的登录状态 [ 1登录成功 ] [ 0 未登录 ] ："+userStatus);
        if("1".equals(userStatus)){
            presenter.getPersonBalance("", "");
            presenter.getPersonInform("");
        }else{
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance(), SupportFragment.SINGLETASK));
        }*/
    }
    @OnClick({R.id.personAgent,R.id.personAdItem,R.id.personRefresh,R.id.personLogout,R.id.personDeposit,R.id.personDwith,R.id.personDepositC,R.id.personAD})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.personAgent:
                EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+ ACache.get(getContext()).getAsString("login_must_tpl_name")+"agents_reg.php?tip=app")));
                break;
            case R.id.personRefresh:
                presenter.getPersonBalance("","Refresh");
                break;
            case R.id.personLogout:
                if(Check.isNull(presenter)){
                    presenter = Injections.inject(null, this);
                }
                presenter.logOut();
                break;
            case R.id.personDeposit://存款
                if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showRealAccountMessage();
                    return;
                }
                //EventBus.getDefault().post(new StartBrotherEvent(MainFragment.newInstance("person_to_deposit",""), SupportFragment.SINGLETASK));
                EventBus.getDefault().post(new ShowMainEvent(1));
                break;
            case R.id.personDwith://取款
                if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                    showRealAccountMessage();
                    return;
                }
                String alias = ACache.get(getContext()).getAsString(HGConstant.USERNAME_ALIAS);
                if(Check.isEmpty(alias)){
                    EventBus.getDefault().post(new StartBrotherEvent(RealNameFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                    return;
                }
                String userStatus = ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT+ACache.get(getContext()).getAsString(HGConstant.USERNAME_LOGIN_ACCOUNT)+HGConstant.USERNAME_BIND_CARD);
                //ACache.get(getContext()).put(HGConstant.USERNAME_LOGIN_ACCOUNT+loginResult.getUserName()+, loginResult.getBindCard_Flag());
                GameLog.log("用户是否已经绑定过银行卡："+userStatus);
                if("0".equals(userStatus)){
                    showMessage(getString(R.string.bcard_user_first));
                    EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                }else{
                    EventBus.getDefault().post(new StartBrotherEvent(WithdrawFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                }
                break;
            case R.id.personDepositC://转账
                //EventBus.getDefault().post(new StartBrotherEvent(BalanceTransferFragment.newInstance(personMoney), SupportFragment.SINGLETASK));
                EventBus.getDefault().post(new StartBrotherEvent(BalancePlatformFragment.newInstance(personBalance), SupportFragment.SINGLETASK));
                break;
            case R.id.personAdItem:
            case R.id.personAD://活动
                EventBus.getDefault().post(new ShowMainEvent(0));
                break;
        }
    }
}

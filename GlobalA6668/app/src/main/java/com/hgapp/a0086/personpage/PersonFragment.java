package com.hgapp.a0086.personpage;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
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
import com.hgapp.a0086.common.util.Store;
import com.hgapp.a0086.common.widgets.GridRvItemDecoration;
import com.hgapp.a0086.common.widgets.NTitleBar;
import com.hgapp.a0086.data.CPResult;
import com.hgapp.a0086.data.LoginResult;
import com.hgapp.a0086.data.PersonBalanceResult;
import com.hgapp.a0086.data.PersonInformResult;
import com.hgapp.a0086.data.QipaiResult;
import com.hgapp.a0086.homepage.UserMoneyEvent;
import com.hgapp.a0086.homepage.handicap.ShowMainEvent;
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

   private void initListData() {
        /*myList.add("真人升级");
        myList.add("体育升级");
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
        myList.add("语言设置");*/
        /*<string name="me_zhenren">真人升级</string>
        <string name="me_tiyu">体育升级</string>
        <string name="me_deposit">充值</string>
        <string name="me_exchange">额度转换</string>
        <string name="me_bankcode">银行卡</string>
        <string name="me_withdraw">提现</string>
        <string name="me_platbalance">平台余额</string>
        <string name="me_message">站内信</string>
        <string name="me_accountcenter">账户中心</string>
        <string name="me_bettingrecord">投注记录</string>
        <string name="me_flowrecord">流水记录</string>*/
       myList.clear();
        myList.add(getString(R.string.me_zhenren));
        myList.add(getString(R.string.me_tiyu));
        myList.add(getString(R.string.me_deposit));
        myList.add(getString(R.string.me_exchange));
        myList.add(getString(R.string.me_bankcode));
        myList.add(getString(R.string.me_withdraw));
        myList.add(getString(R.string.me_platbalance));
        myList.add(getString(R.string.me_message));
        myList.add(getString(R.string.me_accountcenter));
        myList.add(getString(R.string.me_bettingrecord));
        myList.add(getString(R.string.me_flowrecord));
       myList.add(getString(R.string.select_language));
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

        initListData();

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

    class RvMylistAdapter extends com.hgapp.a0086.common.adapters.AutoSizeRVAdapter<String>{
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
                            EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+ACache.get(getContext()).getAsString("login_must_tpl_name")+"middle_lives_upgraded.php?tip=app&game_Type=live")));

                            break;
                        case 1:
                            EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+ACache.get(getContext()).getAsString("login_must_tpl_name")+"middle_lives_upgraded.php?tip=app&game_Type=sport")));

                            break;
                        case 2:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage(getString(R.string.comm_pls_register_real_acccount));
                                return;
                            }
                            //EventBus.getDefault().post(new StartBrotherEvent(MainFragment.newInstance("person_to_deposit",""), SupportFragment.SINGLETASK));
                            EventBus.getDefault().post(new ShowMainEvent(1));
                            break;
                        case 3:
                            EventBus.getDefault().post(new StartBrotherEvent(BalanceTransferFragment.newInstance(personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 4:

                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage(getString(R.string.comm_pls_register_real_acccount));
                                return;
                            }
                            EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            break;
                        case 5:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage(getString(R.string.comm_pls_register_real_acccount));
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
                        case 6:
                            EventBus.getDefault().post(new StartBrotherEvent(BalancePlatformFragment.newInstance(personBalance), SupportFragment.SINGLETASK));
                            break;
                        case 7:
                            showMessage(getString(R.string.me_stay_tuned));
                            break;
                        case 8:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage(getString(R.string.comm_pls_register_real_acccount));
                                return;
                            }
                            EventBus.getDefault().post(new StartBrotherEvent(AccountCenterFragment.newInstance(personMoney)));
                            break;
                        case 9:
                            //投注记录
                            EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("today",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 10:
                            //交易记录
                            EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("T",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 11://语言设置
                            //presenter.logOut();
                            setLanguage();
                            break;
                        case 12:
                            EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            //EventBus.getDefault().post(new StartBrotherEvent(FlowingRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            break;


                    }
                }
            });
            holder.setText(R.id.tvItemMyName,string);
            switch (position){
                case 0:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_live);
                    break;
                case 1:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_sport);
                    break;
                case 2:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_deposit);
                    break;
                case 3:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_transfer);
                    break;
                case 4:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_bank_card);
                    break;
                case 5:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_withdraw);
                    break;
                case 6:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_balance);
                    break;
                case 7:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_message);
                    break;
                case 8:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_psersion);
                    break;
                case 9:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_deal_record);
                    //holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_transfer_record);
                    break;
                case 10:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_running_record);
                    //holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_bet_record);
                    break;
                case 11:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_deal_record);
                    break;
                case 12:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_running_record);
                    break;
                case 13:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_logout);
                    break;

            }

        }


    }


    private void setLanguage(){
        final String[] cities = {getString(R.string.lan_chinese), getString(R.string.lan_en), getString(R.string.lan_vi),getString(R.string.lan_ja), getString(R.string.lan_de)};
        final String[] locals = {"zh_cn", "en-us", "vi-vn","ja", "de"};
        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
        builder.setIcon(R.mipmap.ico_launcher);
        builder.setTitle(R.string.select_language);
        builder.setItems(cities, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                Store.setLanguageLocal(getActivity(), locals[which]);
                EventBus.getDefault().post("EVENT_REFRESH_LANGUAGE");
            }
        });
        builder.show();
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
            tvPersonBack.setMoreText(personMoney);
            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                tvPersonUsername.setText(getString(R.string.me_test_player));
            }else {
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

        if(!Check.isNull(presenter)){
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
    @OnClick(R.id.personLogout)
    public void onLogout(){
        if(Check.isNull(presenter)){
            presenter = Injections.inject(null, this);
        }
        presenter.logOut();
    }

    @OnClick(R.id.personRefresh)
    public void onPersonRefresh(){
        presenter.getPersonBalance("","");
    }
}

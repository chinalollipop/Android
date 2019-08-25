package com.sunapp.bloc.personpage;

import android.content.Context;
import android.content.pm.PackageInfo;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.Injections;
import com.sunapp.bloc.R;
import com.sunapp.bloc.base.HGBaseFragment;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.common.event.LogoutEvent;
import com.sunapp.bloc.common.http.Client;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.GameShipHelper;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.widgets.GridRvItemDecoration;
import com.sunapp.bloc.common.widgets.NTitleBar;
import com.sunapp.bloc.data.CPResult;
import com.sunapp.bloc.data.LoginResult;
import com.sunapp.bloc.data.NoticeResult;
import com.sunapp.bloc.data.PersonBalanceResult;
import com.sunapp.bloc.data.PersonInformResult;
import com.sunapp.bloc.data.QipaiResult;
import com.sunapp.bloc.homepage.UserMoneyEvent;
import com.sunapp.bloc.homepage.handicap.ShowMainEvent;
import com.sunapp.bloc.homepage.noticelist.NoticeListFragment;
import com.sunapp.bloc.homepage.online.ContractFragment;
import com.sunapp.bloc.homepage.online.OnlineFragment;
import com.sunapp.bloc.personpage.accountcenter.AccountCenterFragment;
import com.sunapp.bloc.personpage.balanceplatform.BalancePlatformFragment;
import com.sunapp.bloc.personpage.balancetransfer.BalanceTransferFragment;
import com.sunapp.bloc.personpage.betrecord.BetRecordFragment;
import com.sunapp.bloc.personpage.bindingcard.BindingCardFragment;
import com.sunapp.bloc.personpage.depositrecord.DepositRecordFragment;
import com.sunapp.bloc.personpage.realname.RealNameFragment;
import com.sunapp.bloc.withdrawPage.WithdrawFragment;
import com.sunapp.common.util.Check;
import com.sunapp.common.util.GameLog;
import com.sunapp.common.util.PackageUtil;
import com.sunapp.common.util.Timber;
import com.sunapp.common.util.Utils;
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
    ImageView personRefresh;
    @BindView(R.id.tvPersonHg)
    TextView tvPersonHg;
    /*@BindView(R.id.personLogout)
    TextView personLogout;*/
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
        myList.add("消息公告");
        myList.add("站内信");
        myList.add("账户中心");
        myList.add("投注记录");
        myList.add("流水记录");
        myList.add("新手教学");
        myList.add("关于太阳城");
        myList.add("代理加盟");
        myList.add("联系我们");
        myList.add("安全退出");

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

    class RvMylistAdapter extends com.sunapp.bloc.common.adapters.AutoSizeRVAdapter<String>{
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
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage("非常抱歉，请您注册真实会员！");
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
                                showMessage("非常抱歉，请您注册真实会员！");
                                return;
                            }
                            EventBus.getDefault().post(new StartBrotherEvent(BindingCardFragment.newInstance(personMoney,""), SupportFragment.SINGLETASK));
                            break;
                        case 3:
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage("非常抱歉，请您注册真实会员！");
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
                                showMessage("请先绑定银行卡！");
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
                            EventBus.getDefault().post(new StartBrotherEvent(BalancePlatformFragment.newInstance(personBalance), SupportFragment.SINGLETASK));
                            break;
                        case 5://消息公告
                            presenter.postNoticeList("");
                            break;
                        case 6://站内信
                            showMessage("敬请期待！");
                            break;
                        case 7://账户中心
                            if("true".equals(ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.USERNAME_LOGIN_DEMO))){
                                showMessage("非常抱歉，请您注册真实会员！");
                                return;
                            }
                            EventBus.getDefault().post(new StartBrotherEvent(AccountCenterFragment.newInstance(personMoney)));
                            break;
                        case 8:
                            //投注记录
                            EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("today",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 9://流水记录
                            EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("T",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 10://新手教学
                            EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+"template/help.php?tip=app")));
                            //EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            //EventBus.getDefault().post(new StartBrotherEvent(FlowingRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 11://关于太阳城
                            EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+"template/aboutus.php?tip=app")));

                            //EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+"/template/help.php?tip=app")));
                            break;
                        case 12://代理加盟
                            EventBus.getDefault().post(new StartBrotherEvent(OnlineFragment.newInstance(personMoney, Client.baseUrl()+"agents_reg.php?tip=app")));
                            //交易记录
                            //EventBus.getDefault().post(new StartBrotherEvent(DepositRecordFragment.newInstance("S",personMoney), SupportFragment.SINGLETASK));
                            break;
                        case 13://联系我们
                            EventBus.getDefault().post(new StartBrotherEvent(ContractFragment.newInstance(personMoney,
                                    ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_QQ),
                                    ACache.get(getContext()).getAsString(HGConstant.USERNAME_SERVICE_URL_WECHAT))));
                            break;
                        case 14:
                            presenter.logOut();
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
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_withdraw_deposit);
                    break;
                case 4:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_withdraw);
                    break;
                case 5:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_gg);
                    break;
                case 6:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_message);
                    break;
                case 7:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_psersion);
                    break;
                case 8:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_deal_record);
                    //holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_transfer_record);
                    break;
                case 9:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_running_record);
                    //holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_bet_record);
                    break;
                case 10:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_new);
                    break;
                case 11:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_about);
                    break;
                case 12:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_accent);
                    break;
                case 13:
                    holder.setImageResource(R.id.ivItemMyImage,R.mipmap.icon_my_contact);
                    break;
                case 14:
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
    public void postHgQipaiResult(QipaiResult qipaiResult) {

    }

    @Override
    public void postNoticeListResult(NoticeResult noticeResult) {
        EventBus.getDefault().post(new StartBrotherEvent(NoticeListFragment.newInstance(noticeResult,"","")));
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
                tvPersonUsername.setText("试玩玩家");
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
    /*@OnClick(R.id.personLogout)
    public void onLogout(){
        if(Check.isNull(presenter)){
            presenter = Injections.inject(null, this);
        }
        presenter.logOut();
    }*/

    @OnClick(R.id.personRefresh)
    public void onPersonRefresh(){
        presenter.getPersonBalance("","");
    }
}

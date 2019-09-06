package com.cfcp.a01.ui.me;

import android.content.pm.PackageInfo;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.OrientationHelper;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.Injections;
import com.cfcp.a01.R;
import com.cfcp.a01.common.base.BaseFragment;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.event.StartBrotherEvent;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Check;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.common.utils.GameShipHelper;
import com.cfcp.a01.common.utils.NetworkUtils;
import com.cfcp.a01.common.utils.PackageUtil;
import com.cfcp.a01.common.utils.Utils;
import com.cfcp.a01.common.widget.GridRvItemDecoration;
import com.cfcp.a01.data.BalanceResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.ui.home.deposit.DepositFragment;
import com.cfcp.a01.ui.home.login.fastlogin.LoginFragment;
import com.cfcp.a01.ui.home.withdraw.WithDrawFragment;
import com.cfcp.a01.ui.main.MainEvent;
import com.cfcp.a01.ui.me.bankcard.CardFragment;
import com.cfcp.a01.ui.me.emailbox.EmailBoxFragment;
import com.cfcp.a01.ui.me.info.InfoFragment;
import com.cfcp.a01.ui.me.link.RegisterLinkFragment;
import com.cfcp.a01.ui.me.pwd.PwdFragment;
import com.cfcp.a01.ui.me.record.BetRecordFragment;
import com.cfcp.a01.ui.me.record.overbet.TraceListFragment;
import com.cfcp.a01.ui.me.register.RegisterMeFragment;
import com.cfcp.a01.ui.me.report.PersonFragment;
import com.cfcp.a01.ui.me.report.TeamFragment;
import com.cfcp.a01.ui.me.report.myreport.MyReportFragment;
import com.cfcp.a01.ui.me.userlist.UserListFragment;
import com.chad.library.adapter.base.BaseQuickAdapter;
import com.chad.library.adapter.base.BaseViewHolder;

import org.greenrobot.eventbus.EventBus;
import org.greenrobot.eventbus.Subscribe;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import butterknife.BindView;
import butterknife.OnClick;
import me.yokeyword.fragmentation.SupportFragment;

//用户中心
public class MeFragment extends BaseFragment implements MeContract.View{

    @BindView(R.id.meUser)
    TextView meUser;
    @BindView(R.id.meRegister)
    TextView meRegister;
    @BindView(R.id.meLogout)
    TextView meLogout;
    @BindView(R.id.meVersion)
    TextView meVersion;
    @BindView(R.id.meDeposit)
    LinearLayout meDeposit;
    @BindView(R.id.meWithDraw)
    LinearLayout meWithDraw;
    @BindView(R.id.meBottom)
    LinearLayout meBottom;
    @BindView(R.id.meRecyView)
    RecyclerView meRecyView;
    MeContract.Presenter presenter;
    private static List<MeIconEvent> meCenterList = new ArrayList<MeIconEvent>();
    private static List<MeIconEvent> meCenterListAgent = new ArrayList<MeIconEvent>();

    @Override
    public void postLogoutResult(LogoutResult logoutResult) {
        //退出登录的逻辑  发送消息
        //EventBus.getDefault().post(new LogoutResult("您已登出!"));
        showMessage("用户已退出登录！");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN,"");
        EventBus.getDefault().post(new MainEvent(0));
    }

    @Override
    public void getBalanceResult(BalanceResult balanceResult) {
        GameLog.log("get user balance is "+balanceResult.getAvailable());
        meUser.setText(""+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT)+" 余额："+
                GameShipHelper.formatMoney(balanceResult.getAvailable())+" 元");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_BALANCE,balanceResult.getAvailable());
    }

    @Override
    public void setPresenter(MeContract.Presenter presenter) {
        this.presenter = presenter;
    }

    @Override
    protected List<IPresenter> presenters() {
        return Arrays.asList((IPresenter) presenter);
    }

    public enum MemberType {
        ME_GAME_RECORDS,        //游戏记录
        ME_ZHUIHAO_RECORDS,     //追号查询
        ME_PERSONAL_TABLE,      //个人报表
        ME_TEAM_TABLE,          //团队报表
        ME_ACCOUNT_CHANGE,      //账变报表
        ME_DEPOSIT_RECORDS,     //充值记录
        ME_DISCOUNTS_ACTIVITY,  //优惠活动
        ME_USE_INFOR,           //用户资料
        ME_BANK_CARD,           //银行卡
        ME_PERSONAL_OVERVIEW,   //个人总览
        ME_PWD_SET,             //密码设定
        ME_PWD_CHANGE,          //密码修改
        ME_LOTTERY_INFOR,       //彩种信息
        ME_LOTTERY_LIMIT,       //彩种限额
        ME_LOTTERY_END,         //开奖结果
        ME_RUN_CHART,           //走势图
        ME_TEAM_OVERVIEW,       //团队总览
        ME_USE_LIST,            //用户列表
        ME_SEO_LINK,            //推广链接
        ME_INSTATION_INFOR,     //站内短信
        ME_WEBSITE_NOTICE       //网站公告
    }
    static {
        meCenterList.add(new MeIconEvent("游戏记录","每分钟一期",R.mipmap.me_game_records,MemberType.ME_GAME_RECORDS,1));
        meCenterList.add(new MeIconEvent("追号查询","每分钟一期",R.mipmap.me_zhuihao_records,MemberType.ME_ZHUIHAO_RECORDS,2));
        meCenterList.add(new MeIconEvent("个人报表","每分钟一期",R.mipmap.me_personal_table,MemberType.ME_PERSONAL_TABLE,3));
        //meCenterList.add(new MeIconEvent("团队报表","每分钟一期",R.mipmap.me_team_table,MemberType.ME_TEAM_TABLE,4));
        meCenterList.add(new MeIconEvent("账单报表","每分钟一期",R.mipmap.me_account_change,MemberType.ME_ACCOUNT_CHANGE,5));
        meCenterList.add(new MeIconEvent("充值记录","每分钟一期",R.mipmap.me_deposit_records,MemberType.ME_DEPOSIT_RECORDS,6));
        meCenterList.add(new MeIconEvent("优惠活动","每分钟一期",R.mipmap.me_discounts_activity,MemberType.ME_DISCOUNTS_ACTIVITY,7));
        meCenterList.add(new MeIconEvent("用户资料","每分钟一期",R.mipmap.me_use_infor,MemberType.ME_USE_INFOR,8));
        meCenterList.add(new MeIconEvent("银行卡","每分钟一期",R.mipmap.me_bank_card,MemberType.ME_BANK_CARD,9));
        //meCenterList.add(new MeIconEvent("个人总览","每分钟一期",R.mipmap.me_personal_overview,MemberType.ME_PERSONAL_OVERVIEW,10));
        meCenterList.add(new MeIconEvent("修改登录密码","每分钟一期",R.mipmap.me_pwd_set,MemberType.ME_PWD_SET,11));
        meCenterList.add(new MeIconEvent("设置资金密码","每分钟一期",R.mipmap.me_pwd_change,MemberType.ME_PWD_CHANGE,12));
        meCenterList.add(new MeIconEvent("彩种信息","每分钟一期",R.mipmap.me_lottery_infor,MemberType.ME_LOTTERY_INFOR,13));
        //meCenterList.add(new MeIconEvent("彩种限额","每分钟一期",R.mipmap.me_lottery_limit,MemberType.ME_LOTTERY_INFOR,14));
        meCenterList.add(new MeIconEvent("开奖结果","每分钟一期",R.mipmap.me_lottery_end,MemberType.ME_LOTTERY_END,15));
        meCenterList.add(new MeIconEvent("走势图","每分钟一期",R.mipmap.me_run_chart,MemberType.ME_RUN_CHART,16));
        //meCenterList.add(new MeIconEvent("团队总览","每分钟一期",R.mipmap.me_team_overview,MemberType.ME_TEAM_OVERVIEW,17));
        //meCenterList.add(new MeIconEvent("用户列表","每分钟一期",R.mipmap.me_use_list,MemberType.ME_USE_LIST,18));
        //meCenterList.add(new MeIconEvent("推广链接","每分钟一期",R.mipmap.me_seo_link,MemberType.ME_SEO_LINK,19));
        meCenterList.add(new MeIconEvent("站内短信","每分钟一期",R.mipmap.me_instation_infor,MemberType.ME_INSTATION_INFOR,20));
        meCenterList.add(new MeIconEvent("网站公告","每分钟一期",R.mipmap.me_website_notice,MemberType.ME_WEBSITE_NOTICE,21));


        meCenterListAgent.add(new MeIconEvent("游戏记录","每分钟一期",R.mipmap.me_game_records,MemberType.ME_GAME_RECORDS,1));
        meCenterListAgent.add(new MeIconEvent("追号查询","每分钟一期",R.mipmap.me_zhuihao_records,MemberType.ME_ZHUIHAO_RECORDS,2));
        meCenterListAgent.add(new MeIconEvent("个人报表","每分钟一期",R.mipmap.me_personal_table,MemberType.ME_PERSONAL_TABLE,3));
        meCenterListAgent.add(new MeIconEvent("团队报表","每分钟一期",R.mipmap.me_team_table,MemberType.ME_TEAM_TABLE,4));
        meCenterListAgent.add(new MeIconEvent("账单报表","每分钟一期",R.mipmap.me_account_change,MemberType.ME_ACCOUNT_CHANGE,5));
        meCenterListAgent.add(new MeIconEvent("充值记录","每分钟一期",R.mipmap.me_deposit_records,MemberType.ME_DEPOSIT_RECORDS,6));
        meCenterListAgent.add(new MeIconEvent("优惠活动","每分钟一期",R.mipmap.me_discounts_activity,MemberType.ME_DISCOUNTS_ACTIVITY,7));
        meCenterListAgent.add(new MeIconEvent("用户资料","每分钟一期",R.mipmap.me_use_infor,MemberType.ME_USE_INFOR,8));
        meCenterListAgent.add(new MeIconEvent("银行卡","每分钟一期",R.mipmap.me_bank_card,MemberType.ME_BANK_CARD,9));
        //meCenterList.add(new MeIconEvent("个人总览","每分钟一期",R.mipmap.me_personal_overview,MemberType.ME_PERSONAL_OVERVIEW,10));
        meCenterListAgent.add(new MeIconEvent("修改登录密码","每分钟一期",R.mipmap.me_pwd_set,MemberType.ME_PWD_SET,11));
        meCenterListAgent.add(new MeIconEvent("设置资金密码","每分钟一期",R.mipmap.me_pwd_change,MemberType.ME_PWD_CHANGE,12));
        meCenterListAgent.add(new MeIconEvent("彩种信息","每分钟一期",R.mipmap.me_lottery_infor,MemberType.ME_LOTTERY_INFOR,13));
        //meCenterListAgent.add(new MeIconEvent("彩种限额","每分钟一期",R.mipmap.me_lottery_limit,MemberType.ME_LOTTERY_INFOR,14));
        meCenterListAgent.add(new MeIconEvent("开奖结果","每分钟一期",R.mipmap.me_lottery_end,MemberType.ME_LOTTERY_END,15));
        meCenterListAgent.add(new MeIconEvent("走势图","每分钟一期",R.mipmap.me_run_chart,MemberType.ME_RUN_CHART,16));
        //meCenterList.add(new MeIconEvent("团队总览","每分钟一期",R.mipmap.me_team_overview,MemberType.ME_TEAM_OVERVIEW,17));
        meCenterListAgent.add(new MeIconEvent("用户列表","每分钟一期",R.mipmap.me_use_list,MemberType.ME_USE_LIST,18));
        meCenterListAgent.add(new MeIconEvent("推广链接","每分钟一期",R.mipmap.me_seo_link,MemberType.ME_SEO_LINK,19));
        meCenterListAgent.add(new MeIconEvent("站内短信","每分钟一期",R.mipmap.me_instation_infor,MemberType.ME_INSTATION_INFOR,20));
        meCenterListAgent.add(new MeIconEvent("网站公告","每分钟一期",R.mipmap.me_website_notice,MemberType.ME_WEBSITE_NOTICE,21));
    }

    public static MeFragment newInstance() {
        MeFragment MeFragment = new MeFragment();
        Injections.inject(MeFragment, null);
        return MeFragment;
    }


    @Override
    public int setLayoutId() {
        return R.layout.fragment_me;
    }

    @Override
    public void setEvents(@Nullable Bundle savedInstanceState) {
        EventBus.getDefault().register(this);
        PackageInfo packageInfo =  PackageUtil.getAppPackageInfo(Utils.getContext());
        meVersion.setText("V:"+packageInfo.versionName);
        GridLayoutManager gridLayoutManager = new GridLayoutManager(getContext(),3, OrientationHelper.VERTICAL,false);
        meRecyView.setLayoutManager(gridLayoutManager);
        meRecyView.setHasFixedSize(true);
        meRecyView.setNestedScrollingEnabled(false);
        meRecyView.addItemDecoration(new GridRvItemDecoration(getContext()));
        if("0".equals(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_IS_AGENT))){
            meRegister.setVisibility(View.GONE);
            meRecyView.setAdapter(new MeAdapter(R.layout.item_me,meCenterList));
        }else{
            meRecyView.setAdapter(new MeAdapter(R.layout.item_me,meCenterListAgent));
        }


    }

    class MeAdapter extends BaseQuickAdapter<MeIconEvent, BaseViewHolder> {

        public MeAdapter(int layoutId, List datas) {
            super(layoutId, datas);
        }

        @Override
        protected void convert(BaseViewHolder holder,final MeIconEvent data) {
            /*TextView textView = holder.getView(R.id.itemHomeIconName);
            if(position==8){
                textView.setTextColor(getResources().getColor(R.color.event_red));
            }else{
                textView.setTextColor(getResources().getColor(R.color.login_left));
            }*/
            holder.setText(R.id.itemMeIconName,data.getIconName());
            holder.setBackgroundRes(R.id.itemMeIconDrawable,data.getIconDrawable());
            holder.setOnClickListener(R.id.itemMeShow, new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    if(!NetworkUtils.isConnected()){
                        showMessage("请检查您的网络！");
                        return;
                    }
                    switch (data.getIconId()){
                        case ME_GAME_RECORDS:
                            EventBus.getDefault().post(new StartBrotherEvent(BetRecordFragment.newInstance("","")));
                            break;
                        case ME_ZHUIHAO_RECORDS:
                            EventBus.getDefault().post(new StartBrotherEvent(TraceListFragment.newInstance("","")));
                            break;
                        case ME_PERSONAL_TABLE:
                            //EventBus.getDefault().post(new StartBrotherEvent(PersonFragment.newInstance("","")));
                            EventBus.getDefault().post(new StartBrotherEvent(TeamFragment.newInstance("","person")));
                            break;
                        case ME_TEAM_TABLE:
                            EventBus.getDefault().post(new StartBrotherEvent(TeamFragment.newInstance("","team")));
                            break;
                        case ME_ACCOUNT_CHANGE:
                            EventBus.getDefault().post(new StartBrotherEvent(MyReportFragment.newInstance("0","")));
                            break;
                        case ME_DEPOSIT_RECORDS:
                            EventBus.getDefault().post(new StartBrotherEvent(MyReportFragment.newInstance("1","")));
                            break;
                        case ME_DISCOUNTS_ACTIVITY:
                            EventBus.getDefault().post(new MainEvent(2));
                            break;
                        case ME_USE_INFOR:
                            EventBus.getDefault().post(new StartBrotherEvent(InfoFragment.newInstance("","")));
                            break;
                        case ME_BANK_CARD:
                            EventBus.getDefault().post(new StartBrotherEvent(CardFragment.newInstance("","")));
                            break;
                        case ME_PERSONAL_OVERVIEW:

                            break;
                        case ME_PWD_SET:
                            EventBus.getDefault().post(new StartBrotherEvent(PwdFragment.newInstance("1","")));
                            break;
                        case ME_PWD_CHANGE:
                            EventBus.getDefault().post(new StartBrotherEvent(PwdFragment.newInstance("2","")));
                            break;
                        case ME_LOTTERY_INFOR:
                            EventBus.getDefault().post(new StartBrotherEvent(CaiInfoFragment.newInstance("2","")));
                            break;
                        case ME_LOTTERY_LIMIT:

                            break;
                        case ME_LOTTERY_END:
                        case ME_RUN_CHART:
                            EventBus.getDefault().post(new MainEvent(3));
                            break;
                        case ME_TEAM_OVERVIEW:

                            break;
                        case ME_USE_LIST:
                            EventBus.getDefault().post(new StartBrotherEvent(UserListFragment.newInstance("2","")));
                            break;
                        case ME_SEO_LINK:
                            EventBus.getDefault().post(new StartBrotherEvent(RegisterLinkFragment.newInstance("2","")));
                            break;
                        case ME_INSTATION_INFOR:
                            EventBus.getDefault().post(new StartBrotherEvent(EmailBoxFragment.newInstance("","")));
                            break;
                        case ME_WEBSITE_NOTICE:
                            EventBus.getDefault().post(new StartBrotherEvent(EventListFragment.newInstance("","")));
                            break;
                        default:
                                break;
                    }
                }
            });
        }
    }


    @Override
    public void onDestroyView() {
        super.onDestroyView();
        EventBus.getDefault().unregister(this);
        GameLog.log("MeFragment 终止了监听器");
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
    }

    @Override
    public void onSupportVisible() {
        super.onSupportVisible();
        //先判断是否登录  如果没有登录 需要登录然后在显示这个界面
        String token = ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN);
        GameLog.log(" onSupportVisible  个人的token是 "+token );
        if(Check.isEmpty(token)){
            EventBus.getDefault().post(new StartBrotherEvent(LoginFragment.newInstance()));
            //EventBus.getDefault().post(new MainEvent(0));
        }else{
            if(Check.isNull(presenter)){
                presenter =  Injections.inject(this, null);
            }
            presenter.getBalance();
            meUser.setText(""+ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT)+" 余额："+
                    GameShipHelper.formatMoney(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_BALANCE))+" 元");
        }
    }

    @OnClick({R.id.meUser, R.id.meRegister, R.id.meLogout, R.id.meDeposit, R.id.meWithDraw})
    public void onViewClicked(View view) {
        switch (view.getId()) {
            case R.id.meUser:
                break;
            case R.id.meRegister:
                EventBus.getDefault().post(new StartBrotherEvent(RegisterMeFragment.newInstance("",""), SupportFragment.SINGLETASK));
                break;
            case R.id.meLogout:
                presenter.postLogout("");
                break;
            case R.id.meDeposit:
                /*if("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                //检查是否登录 如果未登录  请调整到登录页先登录
                EventBus.getDefault().post(new StartBrotherEvent(DepositFragment.newInstance(), SupportFragment.SINGLETASK));
                break;
            case R.id.meWithDraw:
                /*if("true".equals(ACache.get(Utils.getContext()).getAsString(CFConstant.USERNAME_LOGIN_DEMO))){
                    showMessage("非常抱歉，请您注册真实会员！");
                    return;
                }*/
                /*if(Check.isEmpty(ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_NAME))){
                    EventBus.getDefault().post(new StartBrotherEvent(CardFragment.newInstance("","")));
                    return;
                }*/
                EventBus.getDefault().post(new StartBrotherEvent(WithDrawFragment.newInstance("",""), SupportFragment.SINGLETASK));
                break;
        }
    }

    @Subscribe
    public void onEventMain(LogoutResult logoutResult) {
        GameLog.log("=======个人中心界面=========用户退出了================");
        //meUser.setText("");
        ACache.get(getContext()).put(CFConstant.USERNAME_LOGIN_TOKEN,"");
        EventBus.getDefault().post(new MainEvent(0));
    }

    @Subscribe
    public void onEventMain(LoginResult loginResult) {
        GameLog.log("========个人中心界面========用户登录成功================");
        meUser.setText(""+loginResult.getUsername()+" 余额："+ GameShipHelper.formatMoney(loginResult.getAbalance())+" 元");
        //EventBus.getDefault().post(new MainEvent(0));
        EventBus.getDefault().unregister(this);
        EventBus.getDefault().post(loginResult);
    }

}

package com.hgapp.a6668;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.hgapp.a6668.common.http.Client;
import com.hgapp.a6668.common.http.cphttp.CPClient;
import com.hgapp.a6668.depositpage.DepositPresenter;
import com.hgapp.a6668.depositpage.DepositeContract;
import com.hgapp.a6668.depositpage.IDepositApi;
import com.hgapp.a6668.depositpage.aliqcpay.AliQCPayContract;
import com.hgapp.a6668.depositpage.aliqcpay.AliQCPayPresenter;
import com.hgapp.a6668.depositpage.aliqcpay.IAliQCPayApi;
import com.hgapp.a6668.depositpage.companypay.CompanyPayContract;
import com.hgapp.a6668.depositpage.companypay.CompanyPayPresenter;
import com.hgapp.a6668.depositpage.companypay.ICompanyPayApi;
import com.hgapp.a6668.homepage.HomePageContract;
import com.hgapp.a6668.homepage.HomePagePresenter;
import com.hgapp.a6668.homepage.IHomePageApi;
import com.hgapp.a6668.homepage.aglist.AGListContract;
import com.hgapp.a6668.homepage.aglist.AGListPresenter;
import com.hgapp.a6668.homepage.aglist.IAGListApi;
import com.hgapp.a6668.homepage.aglist.agchange.AGPlatformContract;
import com.hgapp.a6668.homepage.aglist.agchange.AGPlatformPresenter;
import com.hgapp.a6668.homepage.aglist.agchange.IAgPlatformApi;
import com.hgapp.a6668.homepage.cplist.CPListContract;
import com.hgapp.a6668.homepage.cplist.CPListPresenter;
import com.hgapp.a6668.homepage.cplist.ICPListApi;
import com.hgapp.a6668.homepage.cplist.bet.CpBetApiContract;
import com.hgapp.a6668.homepage.cplist.bet.CpBetApiPresenter;
import com.hgapp.a6668.homepage.cplist.bet.ICpBetApi;
import com.hgapp.a6668.homepage.cplist.hall.CPHallListContract;
import com.hgapp.a6668.homepage.cplist.hall.CPHallListPresenter;
import com.hgapp.a6668.homepage.cplist.hall.ICPHallListApi;
import com.hgapp.a6668.homepage.cplist.order.CPOrderContract;
import com.hgapp.a6668.homepage.cplist.order.CPOrderPresenter;
import com.hgapp.a6668.homepage.cplist.order.ICPOrderApi;
import com.hgapp.a6668.homepage.events.EventsContract;
import com.hgapp.a6668.homepage.events.EventsPresenter;
import com.hgapp.a6668.homepage.events.IEventsApi;
import com.hgapp.a6668.homepage.handicap.betapi.IPrepareBetApi;
import com.hgapp.a6668.homepage.handicap.betapi.PrepareBetApiContract;
import com.hgapp.a6668.homepage.handicap.betapi.PrepareBetApiPresenter;
import com.hgapp.a6668.homepage.handicap.betapi.zhbetapi.IPrepareZHBetApi;
import com.hgapp.a6668.homepage.handicap.betapi.zhbetapi.PrepareZHBetApiContract;
import com.hgapp.a6668.homepage.handicap.betapi.zhbetapi.PrepareZHBetApiPresenter;
import com.hgapp.a6668.homepage.handicap.leaguedetail.ILeagueDetailSearchListApi;
import com.hgapp.a6668.homepage.handicap.leaguedetail.LeagueDetailSearchListContract;
import com.hgapp.a6668.homepage.handicap.leaguedetail.LeagueDetailSearchListPresenter;
import com.hgapp.a6668.homepage.handicap.leaguedetail.zhbet.IPrepareBetZHApi;
import com.hgapp.a6668.homepage.handicap.leaguedetail.zhbet.PrepareBetZHApiContract;
import com.hgapp.a6668.homepage.handicap.leaguedetail.zhbet.PrepareBetZHApiPresenter;
import com.hgapp.a6668.homepage.handicap.leaguelist.ILeagueSearchListApi;
import com.hgapp.a6668.homepage.handicap.leaguelist.LeagueSearchListContract;
import com.hgapp.a6668.homepage.handicap.leaguelist.LeagueSearchListPresenter;
import com.hgapp.a6668.homepage.handicap.leaguelist.championlist.ChampionDetailListContract;
import com.hgapp.a6668.homepage.handicap.leaguelist.championlist.ChampionDetailListPresenter;
import com.hgapp.a6668.homepage.handicap.leaguelist.championlist.IChampionDetailListApi;
import com.hgapp.a6668.homepage.handicap.saiguo.ISaiGuoApi;
import com.hgapp.a6668.homepage.handicap.saiguo.SaiGuoContract;
import com.hgapp.a6668.homepage.handicap.saiguo.SaiGuoPresenter;
import com.hgapp.a6668.homepage.sportslist.ISportsListApi;
import com.hgapp.a6668.homepage.sportslist.SportsListContract;
import com.hgapp.a6668.homepage.sportslist.SportsListPresenter;
import com.hgapp.a6668.homepage.sportslist.bet.BetContract;
import com.hgapp.a6668.homepage.sportslist.bet.BetPresenter;
import com.hgapp.a6668.homepage.sportslist.bet.IBetApi;
import com.hgapp.a6668.login.fastlogin.ILoginApi;
import com.hgapp.a6668.login.fastlogin.LoginContract;
import com.hgapp.a6668.login.fastlogin.LoginPresenter;
import com.hgapp.a6668.login.fastregister.IRegisterApi;
import com.hgapp.a6668.login.fastregister.RegisterContract;
import com.hgapp.a6668.login.fastregister.RegisterPresenter;
import com.hgapp.a6668.login.forgetpwd.ForgetPwdContract;
import com.hgapp.a6668.login.forgetpwd.ForgetPwdPresenter;
import com.hgapp.a6668.login.forgetpwd.IForgetPwdApi;
import com.hgapp.a6668.personpage.IPersonApi;
import com.hgapp.a6668.personpage.PersonContract;
import com.hgapp.a6668.personpage.PersonPresenter;
import com.hgapp.a6668.personpage.balanceplatform.BalancePlatformContract;
import com.hgapp.a6668.personpage.balanceplatform.BalancePlatformPresenter;
import com.hgapp.a6668.personpage.balanceplatform.IBalancePlatformApi;
import com.hgapp.a6668.personpage.balancetransfer.BalanceTransferContract;
import com.hgapp.a6668.personpage.balancetransfer.BalanceTransferPresenter;
import com.hgapp.a6668.personpage.balancetransfer.IBalanceTransferApi;
import com.hgapp.a6668.personpage.betrecord.BetRecordContract;
import com.hgapp.a6668.personpage.betrecord.BetRecordPresenter;
import com.hgapp.a6668.personpage.betrecord.IBetRecordApi;
import com.hgapp.a6668.personpage.bindingcard.BindingCardContract;
import com.hgapp.a6668.personpage.bindingcard.BindingCardPresenter;
import com.hgapp.a6668.personpage.bindingcard.IBindingCardApi;
import com.hgapp.a6668.personpage.depositrecord.DepositRecordContract;
import com.hgapp.a6668.personpage.depositrecord.DepositRecordPresenter;
import com.hgapp.a6668.personpage.depositrecord.IDepositRecordApi;
import com.hgapp.a6668.personpage.flowingrecord.FlowingRecordContract;
import com.hgapp.a6668.personpage.flowingrecord.FlowingRecordPresenter;
import com.hgapp.a6668.personpage.flowingrecord.IFlowingRecordApi;
import com.hgapp.a6668.personpage.managepwd.IManagePwdApi;
import com.hgapp.a6668.personpage.managepwd.ManagePwdContract;
import com.hgapp.a6668.personpage.managepwd.ManagePwdPresenter;
import com.hgapp.a6668.personpage.realname.IRealNameApi;
import com.hgapp.a6668.personpage.realname.RealNameContract;
import com.hgapp.a6668.personpage.realname.RealNamePresenter;
import com.hgapp.a6668.upgrade.CheckUpdateContract;
import com.hgapp.a6668.upgrade.CheckUpdatePresenter;
import com.hgapp.a6668.upgrade.ICheckVerUpdateApi;
import com.hgapp.a6668.withdrawPage.IWithdrawApi;
import com.hgapp.a6668.withdrawPage.WithDrawPresenter;
import com.hgapp.a6668.withdrawPage.WithdrawContract;

public class CPInjections {
    private CPInjections(){}

    //彩票的接口
    //----------------------------------------------------------------------------------------------------------------------------------
    public static CPHallListContract.Presenter inject(@NonNull CPHallListContract.View view, @Nullable ICPHallListApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICPHallListApi.class);
        }
        return new CPHallListPresenter(api,view);
    }

    public static CPListContract.Presenter inject(@NonNull CPListContract.View view, @Nullable ICPListApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICPListApi.class);
        }
        return new CPListPresenter(api,view);
    }

    public static CPOrderContract.Presenter inject(@Nullable ICPOrderApi api, @NonNull CPOrderContract.View view)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICPOrderApi.class);
        }

        return new CPOrderPresenter(api,view);
    }

    public static CpBetApiContract.Presenter inject(@Nullable ICpBetApi api, @NonNull CpBetApiContract.View view)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICpBetApi.class);
        }

        return new CpBetApiPresenter(api,view);
    }

}

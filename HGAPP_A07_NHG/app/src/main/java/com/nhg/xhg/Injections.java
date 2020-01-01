package com.nhg.xhg;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.nhg.xhg.common.http.Client;
import com.nhg.xhg.common.http.cphttp.CPClient;
import com.nhg.xhg.depositpage.DepositPresenter;
import com.nhg.xhg.depositpage.DepositeContract;
import com.nhg.xhg.depositpage.IDepositApi;
import com.nhg.xhg.depositpage.aliqcpay.AliQCPayContract;
import com.nhg.xhg.depositpage.aliqcpay.AliQCPayPresenter;
import com.nhg.xhg.depositpage.aliqcpay.IAliQCPayApi;
import com.nhg.xhg.depositpage.companypay.CompanyPayContract;
import com.nhg.xhg.depositpage.companypay.CompanyPayPresenter;
import com.nhg.xhg.depositpage.companypay.ICompanyPayApi;
import com.nhg.xhg.homepage.HomePageContract;
import com.nhg.xhg.homepage.HomePagePresenter;
import com.nhg.xhg.homepage.IHomePageApi;
import com.nhg.xhg.homepage.aglist.AGListContract;
import com.nhg.xhg.homepage.aglist.AGListPresenter;
import com.nhg.xhg.homepage.aglist.IAGListApi;
import com.nhg.xhg.homepage.aglist.agchange.AGPlatformContract;
import com.nhg.xhg.homepage.aglist.agchange.AGPlatformPresenter;
import com.nhg.xhg.homepage.aglist.agchange.IAgPlatformApi;
import com.nhg.xhg.homepage.cplist.CPListContract;
import com.nhg.xhg.homepage.cplist.CPListPresenter;
import com.nhg.xhg.homepage.cplist.ICPListApi;
import com.nhg.xhg.homepage.cplist.hall.CPHallListContract;
import com.nhg.xhg.homepage.cplist.hall.CPHallListPresenter;
import com.nhg.xhg.homepage.cplist.hall.ICPHallListApi;
import com.nhg.xhg.homepage.cplist.order.CPOrderContract;
import com.nhg.xhg.homepage.cplist.order.CPOrderPresenter;
import com.nhg.xhg.homepage.cplist.order.ICPOrderApi;
import com.nhg.xhg.homepage.events.EventsContract;
import com.nhg.xhg.homepage.events.EventsPresenter;
import com.nhg.xhg.homepage.events.IEventsApi;
import com.nhg.xhg.homepage.handicap.betapi.IPrepareBetApi;
import com.nhg.xhg.homepage.handicap.betapi.PrepareBetApiContract;
import com.nhg.xhg.homepage.handicap.betapi.PrepareBetApiPresenter;
import com.nhg.xhg.homepage.handicap.betapi.zhbetapi.IPrepareZHBetApi;
import com.nhg.xhg.homepage.handicap.betapi.zhbetapi.PrepareZHBetApiContract;
import com.nhg.xhg.homepage.handicap.betapi.zhbetapi.PrepareZHBetApiPresenter;
import com.nhg.xhg.homepage.handicap.leaguedetail.ILeagueDetailSearchListApi;
import com.nhg.xhg.homepage.handicap.leaguedetail.LeagueDetailSearchListContract;
import com.nhg.xhg.homepage.handicap.leaguedetail.LeagueDetailSearchListPresenter;
import com.nhg.xhg.homepage.handicap.leaguedetail.zhbet.IPrepareBetZHApi;
import com.nhg.xhg.homepage.handicap.leaguedetail.zhbet.PrepareBetZHApiContract;
import com.nhg.xhg.homepage.handicap.leaguedetail.zhbet.PrepareBetZHApiPresenter;
import com.nhg.xhg.homepage.handicap.leaguelist.ILeagueSearchListApi;
import com.nhg.xhg.homepage.handicap.leaguelist.LeagueSearchListContract;
import com.nhg.xhg.homepage.handicap.leaguelist.LeagueSearchListPresenter;
import com.nhg.xhg.homepage.handicap.leaguelist.championlist.ChampionDetailListContract;
import com.nhg.xhg.homepage.handicap.leaguelist.championlist.ChampionDetailListPresenter;
import com.nhg.xhg.homepage.handicap.leaguelist.championlist.IChampionDetailListApi;
import com.nhg.xhg.homepage.handicap.saiguo.ISaiGuoApi;
import com.nhg.xhg.homepage.handicap.saiguo.SaiGuoContract;
import com.nhg.xhg.homepage.handicap.saiguo.SaiGuoPresenter;
import com.nhg.xhg.homepage.signtoday.ISignTodayApi;
import com.nhg.xhg.homepage.signtoday.SignTodayContract;
import com.nhg.xhg.homepage.signtoday.SignTodayPresenter;
import com.nhg.xhg.homepage.sportslist.ISportsListApi;
import com.nhg.xhg.homepage.sportslist.SportsListContract;
import com.nhg.xhg.homepage.sportslist.SportsListPresenter;
import com.nhg.xhg.homepage.sportslist.bet.BetContract;
import com.nhg.xhg.homepage.sportslist.bet.BetPresenter;
import com.nhg.xhg.homepage.sportslist.bet.IBetApi;
import com.nhg.xhg.login.fastlogin.ILoginApi;
import com.nhg.xhg.login.fastlogin.LoginContract;
import com.nhg.xhg.login.fastlogin.LoginPresenter;
import com.nhg.xhg.login.fastregister.IRegisterApi;
import com.nhg.xhg.login.fastregister.RegisterContract;
import com.nhg.xhg.login.fastregister.RegisterPresenter;
import com.nhg.xhg.login.forgetpwd.ForgetPwdContract;
import com.nhg.xhg.login.forgetpwd.ForgetPwdPresenter;
import com.nhg.xhg.login.forgetpwd.IForgetPwdApi;
import com.nhg.xhg.login.resetpwd.IResetPwdApi;
import com.nhg.xhg.login.resetpwd.ResetPwdContract;
import com.nhg.xhg.login.resetpwd.ResetPwdPresenter;
import com.nhg.xhg.personpage.IPersonApi;
import com.nhg.xhg.personpage.PersonContract;
import com.nhg.xhg.personpage.PersonPresenter;
import com.nhg.xhg.personpage.balanceplatform.BalancePlatformContract;
import com.nhg.xhg.personpage.balanceplatform.BalancePlatformPresenter;
import com.nhg.xhg.personpage.balanceplatform.IBalancePlatformApi;
import com.nhg.xhg.personpage.balancetransfer.BalanceTransferContract;
import com.nhg.xhg.personpage.balancetransfer.BalanceTransferPresenter;
import com.nhg.xhg.personpage.balancetransfer.IBalanceTransferApi;
import com.nhg.xhg.personpage.betrecord.BetRecordContract;
import com.nhg.xhg.personpage.betrecord.BetRecordPresenter;
import com.nhg.xhg.personpage.betrecord.IBetRecordApi;
import com.nhg.xhg.personpage.bindingcard.BindingCardContract;
import com.nhg.xhg.personpage.bindingcard.BindingCardPresenter;
import com.nhg.xhg.personpage.bindingcard.IBindingCardApi;
import com.nhg.xhg.personpage.depositrecord.DepositRecordContract;
import com.nhg.xhg.personpage.depositrecord.DepositRecordPresenter;
import com.nhg.xhg.personpage.depositrecord.IDepositRecordApi;
import com.nhg.xhg.personpage.flowingrecord.FlowingRecordContract;
import com.nhg.xhg.personpage.flowingrecord.FlowingRecordPresenter;
import com.nhg.xhg.personpage.flowingrecord.IFlowingRecordApi;
import com.nhg.xhg.personpage.managepwd.IManagePwdApi;
import com.nhg.xhg.personpage.managepwd.ManagePwdContract;
import com.nhg.xhg.personpage.managepwd.ManagePwdPresenter;
import com.nhg.xhg.personpage.realname.IRealNameApi;
import com.nhg.xhg.personpage.realname.RealNameContract;
import com.nhg.xhg.personpage.realname.RealNamePresenter;
import com.nhg.xhg.upgrade.CheckUpdateContract;
import com.nhg.xhg.upgrade.CheckUpdatePresenter;
import com.nhg.xhg.upgrade.ICheckVerUpdateApi;
import com.nhg.xhg.withdrawPage.IWithdrawApi;
import com.nhg.xhg.withdrawPage.WithDrawPresenter;
import com.nhg.xhg.withdrawPage.WithdrawContract;

public class Injections {
    private Injections(){}

    /**
     * 向快速登陆Presenter注入登陆视图和登陆接口
     * @param view
     * @return {@linkplain LoginContract.Presenter}
     */
    public static LoginContract.Presenter inject(@NonNull LoginContract.View view, @Nullable ILoginApi loginApi)
    {
        if(null == loginApi)
        {
            loginApi = Client.getRetrofit().create(ILoginApi.class);
        }

        LoginContract.Presenter presenter = new LoginPresenter(loginApi,view);
        return presenter;
    }

    public static ResetPwdContract.Presenter inject(@NonNull ResetPwdContract.View view, @Nullable IResetPwdApi loginApi)
    {
        if(null == loginApi)
        {
            loginApi = Client.getRetrofit().create(IResetPwdApi.class);
        }

        ResetPwdContract.Presenter presenter = new ResetPwdPresenter(loginApi,view);
        return presenter;
    }

    public static RegisterContract.Presenter inject(@NonNull RegisterContract.View view, @Nullable IRegisterApi registerApi)
    {
        if(null == registerApi)
        {
            registerApi = Client.getRetrofit().create(IRegisterApi.class);
        }

        return new RegisterPresenter(registerApi,view);
    }

    public static RealNameContract.Presenter inject(@NonNull RealNameContract.View view, @Nullable IRealNameApi iRealNameApi)
    {
        if(null == iRealNameApi)
        {
            iRealNameApi = Client.getRetrofit().create(IRealNameApi.class);
        }

        return new RealNamePresenter(iRealNameApi,view);
    }

    public static ForgetPwdContract.Presenter inject(@NonNull ForgetPwdContract.View view, @Nullable IForgetPwdApi api)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IForgetPwdApi.class);
        }

        return new ForgetPwdPresenter(api,view);
    }

    public static HomePageContract.Presenter inject(@Nullable IHomePageApi api, @NonNull HomePageContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IHomePageApi.class);
        }

        return new HomePagePresenter(api,view);
    }

    public static LeagueSearchListContract.Presenter inject(@Nullable ILeagueSearchListApi api, @NonNull LeagueSearchListContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(ILeagueSearchListApi.class);
        }

        return new LeagueSearchListPresenter(api,view);
    }

    public static LeagueDetailSearchListContract.Presenter inject(@Nullable ILeagueDetailSearchListApi api, @NonNull LeagueDetailSearchListContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(ILeagueDetailSearchListApi.class);
        }

        return new LeagueDetailSearchListPresenter(api,view);
    }

    public static ChampionDetailListContract.Presenter inject(@Nullable IChampionDetailListApi api, @NonNull ChampionDetailListContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IChampionDetailListApi.class);
        }

        return new ChampionDetailListPresenter(api,view);
    }

    public static AGListContract.Presenter inject(@Nullable IAGListApi api, @NonNull AGListContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IAGListApi.class);
        }

        return new AGListPresenter(api,view);
    }

    public static SportsListContract.Presenter inject(@Nullable ISportsListApi api, @NonNull SportsListContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(ISportsListApi.class);
        }

        return new SportsListPresenter(api,view);
    }


    public static BetContract.Presenter inject(@Nullable IBetApi api, @NonNull BetContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IBetApi.class);
        }

        return new BetPresenter(api,view);
    }

    public static AGPlatformContract.Presenter inject(@Nullable IAgPlatformApi api, @NonNull AGPlatformContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IAgPlatformApi.class);
        }

        return new AGPlatformPresenter(api,view);
    }

    public static PrepareBetApiContract.Presenter inject(@Nullable IPrepareBetApi api, @NonNull PrepareBetApiContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IPrepareBetApi.class);
        }

        return new PrepareBetApiPresenter(api,view);
    }

    public static PrepareBetZHApiContract.Presenter inject(@Nullable IPrepareBetZHApi api, @NonNull PrepareBetZHApiContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IPrepareBetZHApi.class);
        }

        return new PrepareBetZHApiPresenter(api,view);
    }

    public static PrepareZHBetApiContract.Presenter inject(@Nullable IPrepareZHBetApi api, @NonNull PrepareZHBetApiContract.View view)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(IPrepareZHBetApi.class);
        }

        return new PrepareZHBetApiPresenter(api,view);
    }

    public static PersonContract.Presenter inject(@Nullable IPersonApi api ,@NonNull PersonContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IPersonApi.class);
        }
        return new PersonPresenter(api,view);
    }


    public static ManagePwdContract.Presenter inject(@Nullable IManagePwdApi api , @NonNull ManagePwdContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IManagePwdApi.class);
        }
        return new ManagePwdPresenter(api,view);
    }

    public static DepositRecordContract.Presenter inject(@Nullable IDepositRecordApi api , @NonNull DepositRecordContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IDepositRecordApi.class);
        }
        return new DepositRecordPresenter(api,view);
    }

    public static DepositeContract.Presenter inject(@Nullable IDepositApi api , @NonNull DepositeContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IDepositApi.class);
        }
        return new DepositPresenter(api,view);
    }

    public static CompanyPayContract.Presenter inject(@Nullable ICompanyPayApi api , @NonNull CompanyPayContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(ICompanyPayApi.class);
        }
        return new CompanyPayPresenter(api,view);
    }

    public static AliQCPayContract.Presenter inject(@Nullable IAliQCPayApi api , @NonNull AliQCPayContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IAliQCPayApi.class);
        }
        return new AliQCPayPresenter(api,view);
    }

    public static EventsContract.Presenter inject(@Nullable IEventsApi api , @NonNull EventsContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IEventsApi.class);
        }
        return new EventsPresenter(api,view);
    }

    public static SignTodayContract.Presenter inject(@Nullable ISignTodayApi api , @NonNull SignTodayContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(ISignTodayApi.class);
        }
        return new SignTodayPresenter(api,view);
    }

    public static BindingCardContract.Presenter inject(@Nullable IBindingCardApi api , @NonNull BindingCardContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IBindingCardApi.class);
        }
        return new BindingCardPresenter(api,view);
    }

    public static WithdrawContract.Presenter inject(@Nullable IWithdrawApi api , @NonNull WithdrawContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IWithdrawApi.class);
        }
        return new WithDrawPresenter(api,view);
    }

    public static BetRecordContract.Presenter inject(@Nullable IBetRecordApi api , @NonNull BetRecordContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IBetRecordApi.class);
        }
        return new BetRecordPresenter(api,view);
    }

    public static SaiGuoContract.Presenter inject(@Nullable ISaiGuoApi api , @NonNull SaiGuoContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(ISaiGuoApi.class);
        }
        return new SaiGuoPresenter(api,view);
    }

    public static FlowingRecordContract.Presenter inject(@Nullable IFlowingRecordApi api , @NonNull FlowingRecordContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IFlowingRecordApi.class);
        }
        return new FlowingRecordPresenter(api,view);
    }

    public static BalanceTransferContract.Presenter inject(@Nullable IBalanceTransferApi api , @NonNull BalanceTransferContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IBalanceTransferApi.class);
        }
        return new BalanceTransferPresenter(api,view);
    }

    public static BalancePlatformContract.Presenter inject(@Nullable IBalancePlatformApi api , @NonNull BalancePlatformContract.View view){
        if(null == api){
            api = Client.getRetrofit().create(IBalancePlatformApi.class);
        }
        return new BalancePlatformPresenter(api,view);
    }


    /**
     * 向检查更新控制器注入视图和网络接口
     * @param view
     * @param api
     * @return
     */
    public static CheckUpdateContract.Presenter inject(@NonNull CheckUpdateContract.View view, @Nullable ICheckVerUpdateApi api)
    {
        if(null == api)
        {
            api = Client.getRetrofit().create(ICheckVerUpdateApi.class);
        }
        return new CheckUpdatePresenter(view,api);
    }

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

}

package com.venen.tian;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.venen.tian.common.http.Client;
import com.venen.tian.depositpage.DepositPresenter;
import com.venen.tian.depositpage.DepositeContract;
import com.venen.tian.depositpage.IDepositApi;
import com.venen.tian.depositpage.aliqcpay.AliQCPayContract;
import com.venen.tian.depositpage.aliqcpay.AliQCPayPresenter;
import com.venen.tian.depositpage.aliqcpay.IAliQCPayApi;
import com.venen.tian.depositpage.companypay.CompanyPayContract;
import com.venen.tian.depositpage.companypay.CompanyPayPresenter;
import com.venen.tian.depositpage.companypay.ICompanyPayApi;
import com.venen.tian.homepage.HomePageContract;
import com.venen.tian.homepage.HomePagePresenter;
import com.venen.tian.homepage.IHomePageApi;
import com.venen.tian.homepage.aglist.AGListContract;
import com.venen.tian.homepage.aglist.AGListPresenter;
import com.venen.tian.homepage.aglist.IAGListApi;
import com.venen.tian.homepage.aglist.agchange.AGPlatformContract;
import com.venen.tian.homepage.aglist.agchange.AGPlatformPresenter;
import com.venen.tian.homepage.aglist.agchange.IAgPlatformApi;
import com.venen.tian.homepage.events.EventsContract;
import com.venen.tian.homepage.events.EventsPresenter;
import com.venen.tian.homepage.events.IEventsApi;
import com.venen.tian.homepage.handicap.betapi.IPrepareBetApi;
import com.venen.tian.homepage.handicap.betapi.PrepareBetApiContract;
import com.venen.tian.homepage.handicap.betapi.PrepareBetApiPresenter;
import com.venen.tian.homepage.handicap.betapi.zhbetapi.IPrepareZHBetApi;
import com.venen.tian.homepage.handicap.betapi.zhbetapi.PrepareZHBetApiContract;
import com.venen.tian.homepage.handicap.betapi.zhbetapi.PrepareZHBetApiPresenter;
import com.venen.tian.homepage.handicap.leaguedetail.ILeagueDetailSearchListApi;
import com.venen.tian.homepage.handicap.leaguedetail.LeagueDetailSearchListContract;
import com.venen.tian.homepage.handicap.leaguedetail.LeagueDetailSearchListPresenter;
import com.venen.tian.homepage.handicap.leaguedetail.zhbet.IPrepareBetZHApi;
import com.venen.tian.homepage.handicap.leaguedetail.zhbet.PrepareBetZHApiContract;
import com.venen.tian.homepage.handicap.leaguedetail.zhbet.PrepareBetZHApiPresenter;
import com.venen.tian.homepage.handicap.leaguelist.ILeagueSearchListApi;
import com.venen.tian.homepage.handicap.leaguelist.LeagueSearchListContract;
import com.venen.tian.homepage.handicap.leaguelist.LeagueSearchListPresenter;
import com.venen.tian.homepage.handicap.leaguelist.championlist.ChampionDetailListContract;
import com.venen.tian.homepage.handicap.leaguelist.championlist.ChampionDetailListPresenter;
import com.venen.tian.homepage.handicap.leaguelist.championlist.IChampionDetailListApi;
import com.venen.tian.homepage.handicap.saiguo.ISaiGuoApi;
import com.venen.tian.homepage.handicap.saiguo.SaiGuoContract;
import com.venen.tian.homepage.handicap.saiguo.SaiGuoPresenter;
import com.venen.tian.homepage.signtoday.ISignTodayApi;
import com.venen.tian.homepage.signtoday.SignTodayContract;
import com.venen.tian.homepage.signtoday.SignTodayPresenter;
import com.venen.tian.homepage.sportslist.ISportsListApi;
import com.venen.tian.homepage.sportslist.SportsListContract;
import com.venen.tian.homepage.sportslist.SportsListPresenter;
import com.venen.tian.homepage.sportslist.bet.BetContract;
import com.venen.tian.homepage.sportslist.bet.BetPresenter;
import com.venen.tian.homepage.sportslist.bet.IBetApi;
import com.venen.tian.login.fastlogin.ILoginApi;
import com.venen.tian.login.fastlogin.LoginContract;
import com.venen.tian.login.fastlogin.LoginPresenter;
import com.venen.tian.login.fastregister.IRegisterApi;
import com.venen.tian.login.fastregister.RegisterContract;
import com.venen.tian.login.fastregister.RegisterPresenter;
import com.venen.tian.login.forgetpwd.ForgetPwdContract;
import com.venen.tian.login.forgetpwd.ForgetPwdPresenter;
import com.venen.tian.login.forgetpwd.IForgetPwdApi;
import com.venen.tian.login.resetpwd.IResetPwdApi;
import com.venen.tian.login.resetpwd.ResetPwdContract;
import com.venen.tian.login.resetpwd.ResetPwdPresenter;
import com.venen.tian.personpage.IPersonApi;
import com.venen.tian.personpage.PersonContract;
import com.venen.tian.personpage.PersonPresenter;
import com.venen.tian.personpage.balanceplatform.BalancePlatformContract;
import com.venen.tian.personpage.balanceplatform.BalancePlatformPresenter;
import com.venen.tian.personpage.balanceplatform.IBalancePlatformApi;
import com.venen.tian.personpage.balancetransfer.BalanceTransferContract;
import com.venen.tian.personpage.balancetransfer.BalanceTransferPresenter;
import com.venen.tian.personpage.balancetransfer.IBalanceTransferApi;
import com.venen.tian.personpage.betrecord.BetRecordContract;
import com.venen.tian.personpage.betrecord.BetRecordPresenter;
import com.venen.tian.personpage.betrecord.IBetRecordApi;
import com.venen.tian.personpage.bindingcard.BindingCardContract;
import com.venen.tian.personpage.bindingcard.BindingCardPresenter;
import com.venen.tian.personpage.bindingcard.IBindingCardApi;
import com.venen.tian.personpage.depositrecord.DepositRecordContract;
import com.venen.tian.personpage.depositrecord.DepositRecordPresenter;
import com.venen.tian.personpage.depositrecord.IDepositRecordApi;
import com.venen.tian.personpage.flowingrecord.FlowingRecordContract;
import com.venen.tian.personpage.flowingrecord.FlowingRecordPresenter;
import com.venen.tian.personpage.flowingrecord.IFlowingRecordApi;
import com.venen.tian.personpage.managepwd.IManagePwdApi;
import com.venen.tian.personpage.managepwd.ManagePwdContract;
import com.venen.tian.personpage.managepwd.ManagePwdPresenter;
import com.venen.tian.personpage.realname.IRealNameApi;
import com.venen.tian.personpage.realname.RealNameContract;
import com.venen.tian.personpage.realname.RealNamePresenter;
import com.venen.tian.upgrade.CheckUpdateContract;
import com.venen.tian.upgrade.CheckUpdatePresenter;
import com.venen.tian.upgrade.ICheckVerUpdateApi;
import com.venen.tian.withdrawPage.IWithdrawApi;
import com.venen.tian.withdrawPage.WithDrawPresenter;
import com.venen.tian.withdrawPage.WithdrawContract;

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

}

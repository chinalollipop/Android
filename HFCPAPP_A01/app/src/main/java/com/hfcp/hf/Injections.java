package com.hfcp.hf;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.hfcp.hf.common.http.Client;
import com.hfcp.hf.ui.event.EventContract;
import com.hfcp.hf.ui.event.EventPresenter;
import com.hfcp.hf.ui.event.IEventApi;
import com.hfcp.hf.ui.home.HomeContract;
import com.hfcp.hf.ui.home.HomePresenter;
import com.hfcp.hf.ui.home.IHomeApi;
import com.hfcp.hf.ui.home.bet.BetFragmentContract;
import com.hfcp.hf.ui.home.bet.BetFragmentPresenter;
import com.hfcp.hf.ui.home.bet.IBetFragmentApi;
import com.hfcp.hf.ui.home.deposit.DepositContract;
import com.hfcp.hf.ui.home.deposit.DepositSubmitContract;
import com.hfcp.hf.ui.home.deposit.DepositSubmitPresenter;
import com.hfcp.hf.ui.home.deposit.DepositPresenter;
import com.hfcp.hf.ui.home.deposit.IDepositApi;
import com.hfcp.hf.ui.home.deposit.IDepositSubmitApi;
import com.hfcp.hf.ui.home.dragon.DragonContract;
import com.hfcp.hf.ui.home.dragon.DragonPresenter;
import com.hfcp.hf.ui.home.dragon.IDragonApi;
import com.hfcp.hf.ui.home.login.fastlogin.ILoginApi;
import com.hfcp.hf.ui.home.login.fastlogin.LoginContract;
import com.hfcp.hf.ui.home.login.fastlogin.LoginPresenter;
import com.hfcp.hf.ui.home.login.fastregister.IRegisterApi;
import com.hfcp.hf.ui.home.login.fastregister.RegisterContract;
import com.hfcp.hf.ui.home.login.fastregister.RegisterPresenter;
import com.hfcp.hf.ui.home.withdraw.IWithDrawApi;
import com.hfcp.hf.ui.home.withdraw.WithDrawContract;
import com.hfcp.hf.ui.home.withdraw.WithDrawPresenter;
import com.hfcp.hf.ui.home.withdraw.submit.IWithDrawSubmitApi;
import com.hfcp.hf.ui.home.withdraw.submit.WithDrawSubmitContract;
import com.hfcp.hf.ui.home.withdraw.submit.WithDrawSubmitPresenter;
import com.hfcp.hf.ui.lottery.ILotteryResultApi;
import com.hfcp.hf.ui.lottery.LotteryResultContract;
import com.hfcp.hf.ui.lottery.LotteryResultPresenter;
import com.hfcp.hf.ui.main.upgrade.CheckUpdateContract;
import com.hfcp.hf.ui.main.upgrade.CheckUpdatePresenter;
import com.hfcp.hf.ui.main.upgrade.ICheckVerUpdateApi;
import com.hfcp.hf.ui.me.IMeApi;
import com.hfcp.hf.ui.me.MeContract;
import com.hfcp.hf.ui.me.MePresenter;
import com.hfcp.hf.ui.me.bankcard.AddCardContract;
import com.hfcp.hf.ui.me.bankcard.AddCardPresenter;
import com.hfcp.hf.ui.me.bankcard.AddCardSubmitContract;
import com.hfcp.hf.ui.me.bankcard.AddCardSubmitPresenter;
import com.hfcp.hf.ui.me.bankcard.CardContract;
import com.hfcp.hf.ui.me.bankcard.CardPresenter;
import com.hfcp.hf.ui.me.bankcard.IAddCardApi;
import com.hfcp.hf.ui.me.bankcard.IAddCardSubmitApi;
import com.hfcp.hf.ui.me.bankcard.ICardApi;
import com.hfcp.hf.ui.me.bankcard.IModifyApi;
import com.hfcp.hf.ui.me.bankcard.IModifyCardApi;
import com.hfcp.hf.ui.me.bankcard.ModifyCardContract;
import com.hfcp.hf.ui.me.bankcard.ModifyCardPresenter;
import com.hfcp.hf.ui.me.bankcard.ModifyContract;
import com.hfcp.hf.ui.me.bankcard.ModifyPresenter;
import com.hfcp.hf.ui.me.emailbox.EmailBoxContract;
import com.hfcp.hf.ui.me.emailbox.EmailBoxPresenter;
import com.hfcp.hf.ui.me.emailbox.IEmailBoxApi;
import com.hfcp.hf.ui.me.game.GameContract;
import com.hfcp.hf.ui.me.game.GamePresenter;
import com.hfcp.hf.ui.me.game.IGameApi;
import com.hfcp.hf.ui.me.info.IInfoApi;
import com.hfcp.hf.ui.me.info.InfoContract;
import com.hfcp.hf.ui.me.info.InfoPresenter;
import com.hfcp.hf.ui.me.link.IRegisterLinkApi;
import com.hfcp.hf.ui.me.link.RegisterLinkContract;
import com.hfcp.hf.ui.me.link.RegisterLinkPresenter;
import com.hfcp.hf.ui.me.pwd.IPwdApi;
import com.hfcp.hf.ui.me.pwd.PwdContract;
import com.hfcp.hf.ui.me.pwd.PwdPresenter;
import com.hfcp.hf.ui.me.record.betdetail.BetDetailContract;
import com.hfcp.hf.ui.me.record.betdetail.BetDetailPresenter;
import com.hfcp.hf.ui.me.record.betdetail.IBetDetailApi;
import com.hfcp.hf.ui.me.record.overbet.TraceListContract;
import com.hfcp.hf.ui.me.record.overbet.TraceListPresenter;
import com.hfcp.hf.ui.me.record.BetRecordContract;
import com.hfcp.hf.ui.me.record.BetRecordPresenter;
import com.hfcp.hf.ui.me.record.overbet.ITraceListApi;
import com.hfcp.hf.ui.me.record.IBetRecordApi;
import com.hfcp.hf.ui.me.record.tracedetail.ITraceDetailApi;
import com.hfcp.hf.ui.me.record.tracedetail.TraceDetailContract;
import com.hfcp.hf.ui.me.record.tracedetail.TraceDetailPresenter;
import com.hfcp.hf.ui.me.register.IRegisterMeApi;
import com.hfcp.hf.ui.me.register.RegisterMeContract;
import com.hfcp.hf.ui.me.register.RegisterMePresenter;
import com.hfcp.hf.ui.me.report.IPersonApi;
import com.hfcp.hf.ui.me.report.ITeamApi;
import com.hfcp.hf.ui.me.report.PersonContract;
import com.hfcp.hf.ui.me.report.PersonPresenter;
import com.hfcp.hf.ui.me.report.TeamContract;
import com.hfcp.hf.ui.me.report.TeamPresenter;
import com.hfcp.hf.ui.me.report.myreport.IMyReportApi;
import com.hfcp.hf.ui.me.report.myreport.MyReportContract;
import com.hfcp.hf.ui.me.report.myreport.MyReportPresenter;
import com.hfcp.hf.ui.me.userlist.IUserListApi;
import com.hfcp.hf.ui.me.userlist.UserListContract;
import com.hfcp.hf.ui.me.userlist.UserListPresenter;
import com.hfcp.hf.ui.me.userlist.setprize.ISetPrizeApi;
import com.hfcp.hf.ui.me.userlist.setprize.SetPrizeContract;
import com.hfcp.hf.ui.me.userlist.setprize.SetPrizePresenter;


public class Injections {
    private Injections() {
    }

    /**
     * 向快速登陆Presenter注入登陆视图和登陆接口
     *
     * @param view
     * @return {@linkplain LoginContract.Presenter}
     */
    public static LoginContract.Presenter inject(@NonNull LoginContract.View view, @Nullable ILoginApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ILoginApi.class);
        }
        return new LoginPresenter(iApi, view);
    }

    /**
     * 向快速注册Presenter注入登陆视图和登陆接口
     *
     * @param view
     * @return {@linkplain RegisterContract.Presenter}
     */
    public static RegisterContract.Presenter inject(@NonNull RegisterContract.View view, @Nullable IRegisterApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IRegisterApi.class);
        }
        return new RegisterPresenter(iApi, view);
    }

    //首页检查更新的逻辑处理
    public static CheckUpdateContract.Presenter inject(@NonNull CheckUpdateContract.View view, @Nullable ICheckVerUpdateApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ICheckVerUpdateApi.class);
        }
        return new CheckUpdatePresenter(view,iApi);
    }


    //首页的逻辑处理
    public static HomeContract.Presenter inject(@NonNull HomeContract.View view, @Nullable IHomeApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IHomeApi.class);
        }
        return new HomePresenter(iApi, view);
    }

    //长龙的逻辑处理
    public static DragonContract.Presenter inject(@NonNull DragonContract.View view, @Nullable IDragonApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IDragonApi.class);
        }
        return new DragonPresenter(iApi, view);
    }

    //优惠活动的逻辑处理
    public static EventContract.Presenter inject(@NonNull EventContract.View view, @Nullable IEventApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IEventApi.class);
        }
        return new EventPresenter(iApi, view);
    }

    //开奖结果的逻辑处理
    public static LotteryResultContract.Presenter inject(@NonNull LotteryResultContract.View view, @Nullable ILotteryResultApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ILotteryResultApi.class);
        }
        return new LotteryResultPresenter(iApi, view);
    }

    //用户中心的逻辑处理
    public static MeContract.Presenter inject(@NonNull MeContract.View view, @Nullable IMeApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IMeApi.class);
        }
        return new MePresenter(iApi, view);
    }

    //存款的逻辑处理
    public static DepositContract.Presenter inject(@NonNull DepositContract.View view, @Nullable IDepositApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IDepositApi.class);
        }
        return new DepositPresenter(iApi, view);
    }

    //存款第二部的逻辑处理
    public static DepositSubmitContract.Presenter inject(@NonNull DepositSubmitContract.View view, @Nullable IDepositSubmitApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IDepositSubmitApi.class);
        }
        return new DepositSubmitPresenter(iApi, view);
    }

    //投注的逻辑处理
    public static BetRecordContract.Presenter inject(@NonNull BetRecordContract.View view, @Nullable IBetRecordApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IBetRecordApi.class);
        }
        return new BetRecordPresenter(iApi, view);
    }

    //投注详情的逻辑处理
    public static BetDetailContract.Presenter inject(@NonNull BetDetailContract.View view, @Nullable IBetDetailApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IBetDetailApi.class);
        }
        return new BetDetailPresenter(iApi, view);
    }

    //追号投注的逻辑处理
    public static TraceListContract.Presenter inject(@NonNull TraceListContract.View view, @Nullable ITraceListApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ITraceListApi.class);
        }
        return new TraceListPresenter(iApi, view);
    }

    //追号投注详情的逻辑处理
    public static TraceDetailContract.Presenter inject(@NonNull TraceDetailContract.View view, @Nullable ITraceDetailApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ITraceDetailApi.class);
        }
        return new TraceDetailPresenter(iApi, view);
    }

    //取款的逻辑处理
    public static WithDrawContract.Presenter inject(@NonNull WithDrawContract.View view, @Nullable IWithDrawApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IWithDrawApi.class);
        }
        return new WithDrawPresenter(iApi, view);
    }

    //取款提交的逻辑处理
    public static WithDrawSubmitContract.Presenter inject(@NonNull WithDrawSubmitContract.View view, @Nullable IWithDrawSubmitApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IWithDrawSubmitApi.class);
        }
        return new WithDrawSubmitPresenter(iApi, view);
    }

    //用户列表的逻辑处理
    public static UserListContract.Presenter inject(@NonNull UserListContract.View view, @Nullable IUserListApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IUserListApi.class);
        }
        return new UserListPresenter(iApi, view);
    }

    //个人报表的逻辑处理
    public static PersonContract.Presenter inject(@NonNull PersonContract.View view, @Nullable IPersonApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IPersonApi.class);
        }
        return new PersonPresenter(iApi, view);
    }

    //团队报表的逻辑处理
    public static TeamContract.Presenter inject(@NonNull TeamContract.View view, @Nullable ITeamApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ITeamApi.class);
        }
        return new TeamPresenter(iApi, view);
    }

    //账变报表的逻辑处理
    public static MyReportContract.Presenter inject(@NonNull MyReportContract.View view, @Nullable IMyReportApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IMyReportApi.class);
        }
        return new MyReportPresenter(iApi, view);
    }

    //站内信的逻辑处理
    public static EmailBoxContract.Presenter inject(@NonNull EmailBoxContract.View view, @Nullable IEmailBoxApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IEmailBoxApi.class);
        }
        return new EmailBoxPresenter(iApi, view);
    }

    //修改密码的逻辑处理
    public static PwdContract.Presenter inject(@NonNull PwdContract.View view, @Nullable IPwdApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IPwdApi.class);
        }
        return new PwdPresenter(iApi, view);
    }

    //设置真实姓名的逻辑处理
    public static InfoContract.Presenter inject(@NonNull InfoContract.View view, @Nullable IInfoApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IInfoApi.class);
        }
        return new InfoPresenter(iApi, view);
    }

    //设置游戏余额的逻辑处理
    public static GameContract.Presenter inject(@NonNull GameContract.View view, @Nullable IGameApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IGameApi.class);
        }
        return new GamePresenter(iApi, view);
    }

    //设置下级的返点的逻辑处理
    public static SetPrizeContract.Presenter inject(@NonNull SetPrizeContract.View view, @Nullable ISetPrizeApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ISetPrizeApi.class);
        }
        return new SetPrizePresenter(iApi, view);
    }

    //注册管理的逻辑处理
    public static RegisterMeContract.Presenter inject(@NonNull RegisterMeContract.View view, @Nullable IRegisterMeApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IRegisterMeApi.class);
        }
        return new RegisterMePresenter(iApi, view);
    }

    //注册链接的逻辑处理
    public static RegisterLinkContract.Presenter inject(@NonNull RegisterLinkContract.View view, @Nullable IRegisterLinkApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IRegisterLinkApi.class);
        }
        return new RegisterLinkPresenter(iApi, view);
    }

    //银行卡的逻辑处理
    public static CardContract.Presenter inject(@NonNull CardContract.View view, @Nullable ICardApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(ICardApi.class);
        }
        return new CardPresenter(iApi, view);
    }

    //添加银行卡的逻辑处理
    public static AddCardContract.Presenter inject(@NonNull AddCardContract.View view, @Nullable IAddCardApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IAddCardApi.class);
        }
        return new AddCardPresenter(iApi, view);
    }

    //添加银行卡的逻辑处理
    public static AddCardSubmitContract.Presenter inject(@NonNull AddCardSubmitContract.View view, @Nullable IAddCardSubmitApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IAddCardSubmitApi.class);
        }
        return new AddCardSubmitPresenter(iApi, view);
    }

    //修改银行卡的逻辑处理
    public static ModifyContract.Presenter inject(@NonNull ModifyContract.View view, @Nullable IModifyApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IModifyApi.class);
        }
        return new ModifyPresenter(iApi, view);
    }

    //修改银行卡的逻辑处理
    public static ModifyCardContract.Presenter inject(@NonNull ModifyCardContract.View view, @Nullable IModifyCardApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IModifyCardApi.class);
        }
        return new ModifyCardPresenter(iApi, view);
    }


    //--------------------------------------------为了防止相互冲突 ，请colin 您编写在下划线的后面添加数据即可----------------------------------------------------

    public static void inject(@NonNull BetFragmentContract.View view, @Nullable IBetFragmentApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IBetFragmentApi.class);
        }
        new BetFragmentPresenter(iApi, view);
    }

}

package com.qpweb.a01;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.qpweb.a01.http.Client;
import com.qpweb.a01.ui.home.HomeContract;
import com.qpweb.a01.ui.home.HomePresenter;
import com.qpweb.a01.ui.home.IHomeApi;
import com.qpweb.a01.ui.home.agency.AgencyContract;
import com.qpweb.a01.ui.home.agency.AgencyPresenter;
import com.qpweb.a01.ui.home.agency.IAgencyApi;
import com.qpweb.a01.ui.home.bank.BindCardContract;
import com.qpweb.a01.ui.home.bank.BindCardPresenter;
import com.qpweb.a01.ui.home.bank.IBindCardApi;
import com.qpweb.a01.ui.home.bind.BindContract;
import com.qpweb.a01.ui.home.bind.BindPresenter;
import com.qpweb.a01.ui.home.bind.IBindApi;
import com.qpweb.a01.ui.home.deposit.DepositApi;
import com.qpweb.a01.ui.home.deposit.DepositContract;
import com.qpweb.a01.ui.home.deposit.DepositPresenter;
import com.qpweb.a01.ui.home.fastlogout.ILogoutApi;
import com.qpweb.a01.ui.home.fastlogout.LogoutContract;
import com.qpweb.a01.ui.home.fastlogout.LogoutPresenter;
import com.qpweb.a01.ui.home.fenhong.DividendContract;
import com.qpweb.a01.ui.home.fenhong.DividendPresenter;
import com.qpweb.a01.ui.home.fenhong.IDividendApi;
import com.qpweb.a01.ui.home.hongbao.HBaoContract;
import com.qpweb.a01.ui.home.hongbao.HBaoPresenter;
import com.qpweb.a01.ui.home.hongbao.IHBaoApi;
import com.qpweb.a01.ui.home.icon.IconContract;
import com.qpweb.a01.ui.home.icon.IconPresenter;
import com.qpweb.a01.ui.home.icon.IIconApi;
import com.qpweb.a01.ui.home.set.ISetPwdApi;
import com.qpweb.a01.ui.home.set.SetPwdContract;
import com.qpweb.a01.ui.home.set.SetPwdPresenter;
import com.qpweb.a01.ui.home.withdraw.IWithDrawApi;
import com.qpweb.a01.ui.home.withdraw.WithDrawContract;
import com.qpweb.a01.ui.home.withdraw.WithDrawPresenter;
import com.qpweb.a01.ui.loginhome.fastlogin.ILoginApi;
import com.qpweb.a01.ui.loginhome.fastlogin.LoginContract;
import com.qpweb.a01.ui.loginhome.fastlogin.LoginPresenter;
import com.qpweb.a01.ui.loginhome.fastregister.IRegisterApi;
import com.qpweb.a01.ui.loginhome.fastregister.RegisterContract;
import com.qpweb.a01.ui.loginhome.fastregister.RegisterPresenter;
import com.qpweb.a01.ui.loginhome.sign.ISignTodayApi;
import com.qpweb.a01.ui.loginhome.sign.SignTodayContract;
import com.qpweb.a01.ui.loginhome.sign.SignTodayPresenter;


public class Injections {
    private Injections() {
    }

    /**
     * 向快速登陆Presenter注入登陆视图和登陆接口
     *
     * @param view
     * @return {@linkplain LoginContract.Presenter}
     */
    public static LoginContract.Presenter inject(@NonNull LoginContract.View view, @Nullable ILoginApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(ILoginApi.class);
        }
        return new LoginPresenter(loginApi, view);
    }

    public static DepositContract.Presenter inject(@NonNull DepositContract.View view, @Nullable DepositApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(DepositApi.class);
        }
        return new DepositPresenter(loginApi, view);
    }

    public static WithDrawContract.Presenter inject(@NonNull WithDrawContract.View view, @Nullable IWithDrawApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IWithDrawApi.class);
        }
        return new WithDrawPresenter(loginApi, view);
    }

    public static BindContract.Presenter inject(@NonNull BindContract.View view, @Nullable IBindApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IBindApi.class);
        }
        return new BindPresenter(loginApi, view);
    }
    public static BindCardContract.Presenter inject(@NonNull BindCardContract.View view, @Nullable IBindCardApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IBindCardApi.class);
        }
        return new BindCardPresenter(loginApi, view);
    }

    public static SetPwdContract.Presenter inject(@NonNull SetPwdContract.View view, @Nullable ISetPwdApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(ISetPwdApi.class);
        }
        return new SetPwdPresenter(loginApi, view);
    }

    public static AgencyContract.Presenter inject(@NonNull AgencyContract.View view, @Nullable IAgencyApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IAgencyApi.class);
        }
        return new AgencyPresenter(loginApi, view);
    }

    public static HBaoContract.Presenter inject(@NonNull HBaoContract.View view, @Nullable IHBaoApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IHBaoApi.class);
        }
        return new HBaoPresenter(loginApi, view);
    }

    public static DividendContract.Presenter inject(@NonNull DividendContract.View view, @Nullable IDividendApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IDividendApi.class);
        }
        return new DividendPresenter(loginApi, view);
    }

    public static IconContract.Presenter inject(@NonNull IconContract.View view, @Nullable IIconApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IIconApi.class);
        }
        return new IconPresenter(loginApi, view);
    }

    public static RegisterContract.Presenter inject(@NonNull RegisterContract.View view, @Nullable IRegisterApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IRegisterApi.class);
        }
        return new RegisterPresenter(loginApi, view);
    }

    public static LogoutContract.Presenter inject(@NonNull LogoutContract.View view, @Nullable ILogoutApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(ILogoutApi.class);
        }
        return new LogoutPresenter(loginApi, view);
    }

    public static HomeContract.Presenter inject(@NonNull HomeContract.View view, @Nullable IHomeApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(IHomeApi.class);
        }
        return new HomePresenter(loginApi, view);
    }

    public static SignTodayContract.Presenter inject(@NonNull SignTodayContract.View view, @Nullable ISignTodayApi loginApi) {
        if (null == loginApi) {
            loginApi = Client.getRetrofit().create(ISignTodayApi.class);
        }
        return new SignTodayPresenter(loginApi, view);
    }


}

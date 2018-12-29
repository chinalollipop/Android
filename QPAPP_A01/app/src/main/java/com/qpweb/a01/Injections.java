package com.qpweb.a01;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.qpweb.a01.http.Client;
import com.qpweb.a01.ui.home.HomeContract;
import com.qpweb.a01.ui.home.HomePresenter;
import com.qpweb.a01.ui.home.IHomeApi;
import com.qpweb.a01.ui.home.fastlogout.ILogoutApi;
import com.qpweb.a01.ui.home.fastlogout.LogoutContract;
import com.qpweb.a01.ui.home.fastlogout.LogoutPresenter;
import com.qpweb.a01.ui.loginhome.fastlogin.ILoginApi;
import com.qpweb.a01.ui.loginhome.fastlogin.LoginContract;
import com.qpweb.a01.ui.loginhome.fastlogin.LoginPresenter;


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


}

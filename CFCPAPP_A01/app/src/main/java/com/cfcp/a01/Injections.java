package com.cfcp.a01;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.cfcp.a01.common.http.Client;
import com.cfcp.a01.ui.home.HomeContract;
import com.cfcp.a01.ui.home.HomePresenter;
import com.cfcp.a01.ui.home.IHomeApi;
import com.cfcp.a01.ui.home.login.fastlogin.ILoginApi;
import com.cfcp.a01.ui.home.login.fastlogin.LoginContract;
import com.cfcp.a01.ui.home.login.fastlogin.LoginPresenter;
import com.cfcp.a01.ui.me.IMeApi;
import com.cfcp.a01.ui.me.MeContract;
import com.cfcp.a01.ui.me.MePresenter;


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


    public static HomeContract.Presenter inject(@NonNull HomeContract.View view, @Nullable IHomeApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IHomeApi.class);
        }
        return new HomePresenter(iApi, view);
    }

    public static MeContract.Presenter inject(@NonNull MeContract.View view, @Nullable IMeApi iApi) {
        if (null == iApi) {
            iApi = Client.getRetrofit().create(IMeApi.class);
        }
        return new MePresenter(iApi, view);
    }


}

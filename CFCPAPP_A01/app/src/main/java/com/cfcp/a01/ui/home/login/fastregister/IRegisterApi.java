package com.cfcp.a01.ui.home.login.fastregister;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.LoginResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IRegisterApi {


    //会员注册
    @GET("service")
    Observable<AppTextMessageResponse<LoginResult>> registerMember(
            @QueryMap Map<String, String> params
    );

}

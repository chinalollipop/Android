package com.cfcp.a01.ui.me;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.BalanceResult;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface IMeApi {

    //会员登录
    @POST("service")
    @FormUrlEncoded
    Observable<AppTextMessageResponse<LoginResult>> postLogin(
            @Field("appRefer") String appRefer,
            @Field("packet") String packet,
            @Field("action") String action,
            @Field("username") String username,
            @Field("password") String password,
            @Field("terminal_id") String terminal_id
    );

    //会员注销
    @GET("service")
    Observable<AppTextMessageResponse<LogoutResult>> getLogout(
            @QueryMap Map<String, String> params
    );

    //会员余额
    @GET("service")
    Observable<AppTextMessageResponse<BalanceResult>> getBalance(
            @QueryMap Map<String, String> params
    );

}

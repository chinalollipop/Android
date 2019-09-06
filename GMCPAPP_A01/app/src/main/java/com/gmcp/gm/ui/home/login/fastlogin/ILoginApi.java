package com.gmcp.gm.ui.home.login.fastlogin;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.LoginResult;

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

public interface ILoginApi {

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

    //会员登录
    @GET("service")
    Observable<AppTextMessageResponse<LoginResult>> getLogin(
            @QueryMap Map<String, String> params
    );
}

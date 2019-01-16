package com.cfcp.a01.ui.login.fastregister;


import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IRegisterApi {


    //会员注册
    @POST("api/register.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> registerMember(
            @Field("appRefer") String appRefer, @Field("action") String action, @Field("reference") String reference,
            @Field("username") String username, @Field("password") String password, @Field("password2") String password2,
            @Field("verifycode") String verifycode, @Field("code") String code);

}

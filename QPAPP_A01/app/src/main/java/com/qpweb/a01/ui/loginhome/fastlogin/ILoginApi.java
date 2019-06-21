package com.qpweb.a01.ui.loginhome.fastlogin;


import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface ILoginApi {

    //会员账号登录
    @POST("api/login.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> postLogin(
            @Field("appRefer") String appRefer,
            @Field("username") String username, @Field("passwd") String passwd);

    //游客登录
    @POST("api/guest_register.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> postRegister(
            @Field("appRefer") String appRefer,
            @Field("action") String action);

    //获取验证码
    @POST("api/sms/submail/register_login_xsend.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> postPhone(
            @Field("appRefer") String appRefer,
            @Field("mem_phone") String mem_phone, @Field("code") String code);

    //验证码登录
    @POST("api/sms/registerLoginIn.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> postLoginPhone(
            @Field("appRefer") String appRefer,
            @Field("mem_phone") String mem_phone, @Field("mem_yzm") String mem_yzm,
            @Field("reference") String reference, @Field("code") String code);

}

package com.sunapp.bloc.login.fastlogin;

import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.common.http.request.AppTextMessageResponseList;
import com.sunapp.bloc.data.LoginResult;
import com.sunapp.bloc.data.SportsPlayMethodRBResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface ILoginApi {

    @POST("mem_reg_add.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> mem_reg_add(@Field("accountname") String accountname, @Field("pwd") String pwd);

    //会员登录
    @POST("login_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> login(@Field("appRefer") String appRefer, @Field("username") String username,@Field("passwd") String passwd);

    //试玩登录
    @POST("login_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> loginDemo(@Field("appRefer") String appRefer,@Field("demoplay") String demoplay, @Field("username") String username,@Field("passwd") String passwd);

    //会员注册
    @POST("mem_reg_add.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> addMember (
            @Field("appRefer") String appRefer, @Field("introducer") String introducer,@Field("keys") String keys,
            @Field("username") String username, @Field("password") String password,@Field("password2") String password2,
            @Field("alias") String alias, @Field("paypassword") String paypassword,@Field("phone") String phone,
            @Field("wechat") String wechat, @Field("birthday") String birthday,@Field("know_site") String know_site);


    @GET("login.php?appRefer=13&username=lincoin06&passwd=123qwe")
    public Observable<AppTextMessageResponse<LoginResult>> loginGet();

    @GET("var_api.php?appRefer=13&type=FU&more=s")
    public Observable<AppTextMessageResponse<SportsPlayMethodRBResult>> getFullPayGameList();

    //输入手机号码校验
    @POST("guest_login_save_phone_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LoginResult>> loginPhone(@Field("appRefer") String appRefer, @Field("phone") String phone);




}

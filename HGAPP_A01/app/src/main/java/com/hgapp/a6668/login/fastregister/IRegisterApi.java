package com.hgapp.a6668.login.fastregister;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.data.LoginResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IRegisterApi {


    //会员注册
    @POST("mem_reg_add.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> registerMember(
            @Field("appRefer") String appRefer, @Field("introducer") String introducer, @Field("keys") String keys,
            @Field("username") String username, @Field("password") String password, @Field("password2") String password2,
            @Field("alias") String alias, @Field("paypassword") String paypassword, @Field("phone") String phone,
            @Field("wechat") String wechat, @Field("birthday") String birthday, @Field("know_site") String know_site);



}

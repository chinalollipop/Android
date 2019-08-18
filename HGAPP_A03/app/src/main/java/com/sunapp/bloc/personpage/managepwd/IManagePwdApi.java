package com.sunapp.bloc.personpage.managepwd;

import com.sunapp.bloc.common.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IManagePwdApi {
    //修改登录密码
    @POST("account/changepwd_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postChangeLoginPwd(@Field("appRefer") String appRefer, @Field("action") String action, @Field("flag_action") String flag_action, @Field("oldpassword") String oldpassword, @Field("password") String password, @Field("REpassword") String REpassword);

    //修改取款密码
    @POST("account/changepwd_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postChangeWithDrawalPwd(@Field("appRefer") String appRefer,@Field("action") String action,@Field("flag_action") String flag_action,@Field("pay_oldpassword") String pay_oldpassword,@Field("pay_password") String pay_password,@Field("pay_REpassword") String pay_REpassword);

}

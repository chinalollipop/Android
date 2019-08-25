package com.hg3366.a3366.login.forgetpwd;

import com.hg3366.a3366.common.http.request.AppTextMessageResponseList;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IForgetPwdApi {


    //找回密码
    @POST("forget_pwd.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<String>> postForgetPwd(@Field("appRefer") String appRefer, @Field("action_type") String action_type, @Field("username") String username, @Field("realname") String realname, @Field("withdraw_password") String withdraw_password, @Field("birthday") String birthday, @Field("new_password") String new_password, @Field("password_confirmation") String password_confirmation);


}

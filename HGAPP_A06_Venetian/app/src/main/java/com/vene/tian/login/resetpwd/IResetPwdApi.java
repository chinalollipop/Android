package com.vene.tian.login.resetpwd;

import com.vene.tian.common.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IResetPwdApi {

    //修改登录密码
    @POST("account/changepwd_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postChangeLoginPwd(@Field("appRefer") String appRefer, @Field("action") String action, @Field("flag_action") String flag_action, @Field("oldpassword") String oldpassword, @Field("password") String password, @Field("REpassword") String REpassword);


}

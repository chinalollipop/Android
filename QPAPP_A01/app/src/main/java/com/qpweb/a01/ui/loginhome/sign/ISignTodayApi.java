package com.qpweb.a01.ui.loginhome.sign;


import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface ISignTodayApi {

    //会员注册
    @POST("api/login.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> postLogin(
            @Field("appRefer") String appRefer,
            @Field("username") String username, @Field("passwd") String passwd);

}

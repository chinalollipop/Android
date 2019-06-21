package com.qpweb.a01.ui.home.bind;


import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IBindApi {

    //绑定手机号 第一步 获取验证码
    @POST("api/sms/submail/message_xsend.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<LoginResult>> postSendCode(
            @Field("appRefer") String appRefer,
            @Field("mem_phone") String mem_phone);

    //绑定手机号 第二步 获取验证码
    @POST("api/sms/bandRegister.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RedPacketResult>> postCodeSubmit(
            @Field("appRefer") String appRefer,@Field("nickname") String nickname,
            @Field("mem_phone") String mem_phone, @Field("mem_yzm") String mem_yzm);

}

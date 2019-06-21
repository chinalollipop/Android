package com.qpweb.a01.ui.home.set;


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

public interface ISetPwdApi {

    //修改登录密码
    @POST("api/account/changepwd.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RedPacketResult>> postChangLoginPwd(
            @Field("appRefer") String appRefer,@Field("type") String type,
            @Field("pwdCur") String pwdCur,@Field("pwdNew") String pwdNew
            ,@Field("pwdNew1") String pwdNew1);

    //修改资金密码
    @POST("api/account/changepwd.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RedPacketResult>> postChangeWithDrawPwd(
            @Field("appRefer") String appRefer,@Field("type") String type,
            @Field("nameReal") String nameReal,@Field("pwdSafe") String pwdSafe
            ,@Field("pwdSafe1") String pwdSafe1);

}

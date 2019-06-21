package com.qpweb.a01.ui.home.icon;


import com.qpweb.a01.data.ChangIconResult;
import com.qpweb.a01.data.NickNameResult;
import com.qpweb.a01.data.PSignatureResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IIconApi {

    //头像
    @POST("api/userinfo_edit.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<ChangIconResult>> postChangeIcon(
            @Field("appRefer") String appRefer,
            @Field("action_type") String action_type, @Field("avatarid") String avatarid);

    //昵称
    @POST("api/userinfo_edit.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<NickNameResult>> postChangeNickName(
            @Field("appRefer") String appRefer,
            @Field("action_type") String action_type, @Field("nickname") String nickname);
    @POST("api/userinfo_edit.php")

    //签名
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<PSignatureResult>> postChangeSignWords(
            @Field("appRefer") String appRefer,
            @Field("action_type") String action_type, @Field("personalizedsignature") String personalizedsignature);

}

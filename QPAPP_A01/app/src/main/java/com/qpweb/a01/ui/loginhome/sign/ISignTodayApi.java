package com.qpweb.a01.ui.loginhome.sign;


import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.SignTodayResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface ISignTodayApi {

    //签到
    @POST("api/signin.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<SignTodayResult>> postSignTodays(
            @Field("appRefer") String appRefer,
            @Field("action") String action);

    //签到领取
    @POST("api/signin.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RedPacketResult>> postRed(
            @Field("appRefer") String appRefer);

}

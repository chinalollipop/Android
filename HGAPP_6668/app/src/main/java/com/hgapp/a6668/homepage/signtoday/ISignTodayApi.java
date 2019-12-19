package com.hgapp.a6668.homepage.signtoday;

import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.data.DownAppGiftResult;
import com.hgapp.a6668.data.LuckGiftResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.data.ReceiveSignTidayResults;
import com.hgapp.a6668.data.SignTodayResults;
import com.hgapp.a6668.data.ValidResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ISignTodayApi {

    // 签到
    @POST("api/attendanceApi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<SignTodayResults>> postSignTodayCheck(@Field("appRefer") String appRefer, @Field("actype") String actype);


    // 领取
    @POST("api/attendanceApi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ReceiveSignTidayResults>> postSignTodayReceive(@Field("appRefer") String appRefer, @Field("actype") String actype);


}

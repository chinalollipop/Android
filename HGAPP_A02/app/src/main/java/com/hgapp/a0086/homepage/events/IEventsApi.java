package com.hgapp.a0086.homepage.events;

import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.common.http.request.AppTextMessageResponseList;
import com.hgapp.a0086.data.PersonBalanceResult;
import com.hgapp.a0086.data.DownAppGiftResult;
import com.hgapp.a0086.data.LuckGiftResult;
import com.hgapp.a0086.data.ValidResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IEventsApi {
    //老会员下载礼金
    @POST("download_app_gift_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<DownAppGiftResult>> postDownAppGift(@Field("appRefer") String appRefer);

    //抽取幸运红包
    @POST("lucky_red_envelope_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LuckGiftResult>> postLuckGift(@Field("appRefer") String appRefer, @Field("action") String action);
    //昨日有效金额
    @POST("lucky_red_envelope_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ValidResult>> postValidGift(@Field("appRefer") String appRefer, @Field("action") String action);

    //新年用户签到
    @POST("newyearapi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ValidResult>> postNewUserSign(@Field("appRefer") String appRefer, @Field("mobile") String mobile, @Field("action") String action);

    //新年获取剩余红包次数
    @POST("newyearapi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ValidResult>> postNewUserSignValidNum(@Field("appRefer") String appRefer, @Field("mobile") String mobile, @Field("action") String action);

    //新年获取红包
    @POST("newyearapi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LuckGiftResult>> postNewUserRed(@Field("appRefer") String appRefer, @Field("action") String action);


    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);



}

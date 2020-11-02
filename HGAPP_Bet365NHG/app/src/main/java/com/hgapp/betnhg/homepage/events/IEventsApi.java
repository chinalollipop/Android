package com.hgapp.betnhg.homepage.events;

import com.hgapp.betnhg.common.http.request.AppTextMessageResponseList;
import com.hgapp.betnhg.data.PersonBalanceResult;
import com.hgapp.betnhg.data.DownAppGiftResult;
import com.hgapp.betnhg.data.LuckGiftResult;
import com.hgapp.betnhg.data.ValidResult;

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

    //新年红包
    @POST("newyear_6668_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LuckGiftResult>> postNewYearRed(@Field("appRefer") String appRefer, @Field("action") String action);


    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);


}

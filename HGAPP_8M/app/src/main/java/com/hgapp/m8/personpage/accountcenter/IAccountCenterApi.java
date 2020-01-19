package com.hgapp.m8.personpage.accountcenter;

import com.hgapp.m8.common.http.request.AppTextMessageResponse;
import com.hgapp.m8.data.BetRecordResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IAccountCenterApi {

    //投注记录，交易状况
    @POST("todaywagers_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetRecordResult>> postBetToday(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("page") String page);

    //投注记录，账户历史
    @POST("historywagers_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetRecordResult>> postBetHistory(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("page") String page);


}

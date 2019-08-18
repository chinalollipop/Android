package com.sunapp.bloc.personpage.flowingrecord;

import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.data.FlowingRecordResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IFlowingRecordApi {

    //投注记录，交易状况
    @POST("todaywagers_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<FlowingRecordResult>> postBetToday(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("page") String page);

    //投注记录，账户历史
    @POST("historywagers_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<FlowingRecordResult>> postBetHistory(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("page") String page);


}

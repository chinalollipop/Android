package com.hgapp.m8.personpage.depositrecord;

import com.hgapp.m8.common.http.request.AppTextMessageResponse;
import com.hgapp.m8.data.RecordResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IDepositRecordApi {

    //存款记录 thistype=S
    @POST("account/record_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RecordResult>> postDepositRecord(@Field("appRefer") String appRefer, @Field("thistype") String thistype, @Field("page") String page,@Field("type_status") String type_status, @Field("date_start") String date_start, @Field("date_end") String date_end);

}

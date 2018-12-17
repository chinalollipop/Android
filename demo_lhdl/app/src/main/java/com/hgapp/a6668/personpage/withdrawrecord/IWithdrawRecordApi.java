package com.hgapp.a6668.personpage.withdrawrecord;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.data.PersonBalanceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IWithdrawRecordApi {
    //取款记录 thistype=T
    @POST("account/record_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<PersonBalanceResult>> postWithDrawalRecord(@Field("appRefer") String appRefer, @Field("thistype") String thistype, @Field("page") String page);

}

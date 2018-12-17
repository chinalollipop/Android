package com.hgapp.a6668.homepage.aglist.agchange;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.data.PersonBalanceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IAgPlatformApi {

    //体育额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

}

package com.hgapp.a0086.personpage.balanceplatform;

import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.data.KYBalanceResult;
import com.hgapp.a0086.data.PersonBalanceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IBalancePlatformApi {

    //体育额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //彩票额度转换  action=fundLimitTrans from=hg&to=cp from=cp&to=hg
    @POST("ajaxTran.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postBanalceTransferCP(@Field("appRefer") String appRefer, @Field("action") String action, @Field("from") String from, @Field("to") String to, @Field("fund") String fund);


    //开元额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<KYBalanceResult>> postBanalceTransferKY(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<PersonBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取开元余额
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<KYBalanceResult>> postPersonBalanceKY(@Field("appRefer") String appRefer, @Field("action") String action);

}

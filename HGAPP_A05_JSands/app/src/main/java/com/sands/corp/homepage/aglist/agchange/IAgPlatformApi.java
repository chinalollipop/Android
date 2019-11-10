package com.sands.corp.homepage.aglist.agchange;

import com.sands.corp.common.http.request.AppTextMessageResponseList;
import com.sands.corp.data.PersonBalanceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IAgPlatformApi {

    //体育额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //MG额度转换  f=hg&t=mg f=mg&t=hg
    @POST("mg/mg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postMGBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //cq9额度转换  f=hg&t=ag f=ag&t=hg
    @POST("cq9/cq9_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postCQBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //mw额度转换  f=hg&t=mg f=mg&t=hg
    @POST("mw/mw_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postMWBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取MG余额
    @POST("mg/mg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postMGPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取余额
    @POST("cq9/cq9_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postCQPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取MG余额
    @POST("mw/mw_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postMWPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取FG余额
    @POST("fg/fg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postFGPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //FG额度转换  f=hg&t=fg f=fg&t=hg
    @POST("fg/fg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postFGBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


}

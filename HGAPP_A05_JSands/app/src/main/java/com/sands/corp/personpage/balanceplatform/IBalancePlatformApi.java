package com.sands.corp.personpage.balanceplatform;

import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.common.http.request.AppTextMessageResponseList;
import com.sands.corp.data.KYBalanceResult;
import com.sands.corp.data.PersonBalanceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IBalancePlatformApi {

    //体育额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //彩票额度转换  action=fundLimitTrans from=hg&to=cp from=cp&to=hg
    @POST("gmcp/cp_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postBanalceTransferCP(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


    //开元额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferKY(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


    @POST("lyqp/ly_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferLY(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


    //皇冠棋牌额度转换  f=hg&t=ag f=ag&t=hg
    @POST("hgqp/hg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferHG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("vgqp/vg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferVG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("mg/mg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferMG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


    @POST("avia/avia_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferAG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("og/og_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferOG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);



    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取彩票余额
    @POST("gmcp/cp_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceCP(@Field("appRefer") String appRefer, @Field("action") String action);


    //获取开元余额
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceKY(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取皇冠棋牌余额
    @POST("hgqp/hg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceHG(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("vgqp/vg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceVG(@Field("appRefer") String appRefer, @Field("action") String action);


    //获取开元余额
    @POST("lyqp/ly_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceLY(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("mg/mg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceMG(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("avia/avia_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceAG(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("og/og_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceOG(@Field("appRefer") String appRefer, @Field("action") String action);

}

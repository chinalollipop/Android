package com.hgapp.a6668.personpage.balancetransfer;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.data.KYBalanceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IBalanceTransferApi {

    //体育额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<Object>> postBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //彩票额度转换  action=fundLimitTrans from=hg&to=cp from=cp&to=hg
    @POST("ajaxTran.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postBanalceTransferCP(@Field("appRefer") String appRefer, @Field("action") String action, @Field("from") String from, @Field("to") String to, @Field("fund") String fund);

    //开元额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferKY(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


    //皇冠额度转换  f=hg&t=ag f=ag&t=hg
    @POST("klqp/kl_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferHG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("vgqp/vg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferVG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("lyqp/ly_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferLY(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("mg/mg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferMG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("avia/avia_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferAG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("og/og_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferOG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("cq9/cq9_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferCQ(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("mw/mw_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferMW(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("fg/fg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferFG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("bbin/bbin_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferBBIN(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("thunfire/fire_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferFire(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

}

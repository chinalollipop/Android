package com.hgapp.a0086.personpage.balancetransfer;

import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.common.http.request.AppTextMessageResponseList;
import com.hgapp.a0086.data.KYBalanceResult;

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
    @POST("hgqp/hg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferHG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("vgqp/vg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferVG(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("lyqp/ly_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferLY(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

}

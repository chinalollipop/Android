package com.hgapp.bet365.personpage.balancetransfer;

import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.common.http.request.AppTextMessageResponseList;
import com.hgapp.bet365.data.KYBalanceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IBalanceTransferApi {
    //体育中心获取余额
    @POST("sportcenter/sport_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceTY(@Field("appRefer") String appRefer, @Field("action") String action);

    //体育中心额度转换  action=fundLimitTrans from=hg&to=cp from=cp&to=hg
    @POST("sportcenter/sport_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferTY(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);


    //体育额度转换  f=hg&t=ag f=ag&t=hg
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransfer(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    //彩票额度转换  action=fundLimitTrans from=hg&to=cp from=cp&to=hg
    @POST("ajaxTran.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<KYBalanceResult>> postBanalceTransferCP(@Field("appRefer") String appRefer, @Field("action") String action, @Field("f") String f, @Field("t") String t, @Field("fund") String fund);

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


    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取彩票余额
    @POST("ajaxTran.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceCP(@Field("appRefer") String appRefer, @Field("action") String action);


    //获取开元余额
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceKY(@Field("appRefer") String appRefer, @Field("action") String action);

    //获取皇冠棋牌余额
    @POST("klqp/kl_api.php")
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

    @POST("cq9/cq9_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceCQ(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("mw/mw_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceMW(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("fg/fg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceFG(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("bbin/bbin_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceBBIN(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("bbin/bbin_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferBBIN(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

    @POST("thunfire/fire_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postPersonBalanceFire(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("thunfire/fire_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<KYBalanceResult>> postBanalceTransferFire(@Field("appRefer") String appRefer, @Field("f") String f, @Field("t") String t, @Field("b") String b);

}

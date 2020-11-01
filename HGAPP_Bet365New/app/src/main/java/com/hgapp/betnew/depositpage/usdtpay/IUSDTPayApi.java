package com.hgapp.betnew.depositpage.usdtpay;

import com.hgapp.betnew.common.http.request.AppTextMessageResponse;
import com.hgapp.betnew.data.USDTRateResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IUSDTPayApi {
    //USDT-支付宝二维码 微信二维码 提交
    @POST("account/bank_type_SAOMA_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postDepositUSDTPaySubimt(@Field("appRefer") String appRefer, @Field("payid") String payid, @Field("v_amount") String v_amount, @Field("cn_date") String cn_date, @Field("memo") String memo, @Field("bank_user") String bank_user);


    @POST("api/usdtRateApi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<USDTRateResult>> postUsdtRateApiSubimt(@Field("appRefer") String appRefer, @Field("v_amount") String v_amount);

}

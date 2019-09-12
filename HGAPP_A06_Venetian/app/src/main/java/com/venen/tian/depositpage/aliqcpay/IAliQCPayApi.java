package com.venen.tian.depositpage.aliqcpay;

import com.venen.tian.common.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IAliQCPayApi {
    //公司入款-支付宝二维码 微信二维码 提交
    @POST("account/bank_type_SAOMA_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postDepositAliPayQCPaySubimt(@Field("appRefer") String appRefer, @Field("payid") String payid, @Field("v_amount") String v_amount, @Field("cn_date") String cn_date, @Field("memo") String memo, @Field("bank_user") String bank_user);

}

package com.hgapp.bet365.depositpage.companypay;

import com.hgapp.bet365.common.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ICompanyPayApi {
    //公司入款-银行卡支付提交
    @POST("account/deposit_two_bank_company_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postDepositCompanyPaySubimt(@Field("appRefer") String appRefer, @Field("payid") String payid, @Field("v_Name") String v_Name, @Field("InType") String InType, @Field("v_amount") String v_amount, @Field("cn_date") String cn_date, @Field("memo") String memo, @Field("IntoBank") String IntoBank);

}

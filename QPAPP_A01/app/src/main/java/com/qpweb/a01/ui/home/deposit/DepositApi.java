package com.qpweb.a01.ui.home.deposit;


import com.qpweb.a01.data.DepositAliPayQCCodeResult;
import com.qpweb.a01.data.DepositBankCordListResult;
import com.qpweb.a01.data.DepositListResult;
import com.qpweb.a01.data.DepositThirdBankCardResult;
import com.qpweb.a01.data.DepositThirdQQPayResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface DepositApi {

    //充值列表
    @POST("api/account/deposit_one_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<DepositListResult>> postLogin(
            @Field("appRefer") String appRefer,
            @Field("username") String username, @Field("passwd") String passwd);


    //公司入款-银行卡列表  对应公司入款
    @POST("api/account/deposit_two_bank_company.php")
    @FormUrlEncoded
    public Observable<DepositBankCordListResult> postDepositBankCordList(@Field("appRefer") String appRefer);


    //公司入款-第三方银行卡  对应银行卡线上
    @POST("api/account/deposit_two_third_bank.php")
    @FormUrlEncoded
    public Observable<DepositThirdBankCardResult> postDepositThirdBankCard(@Field("appRefer") String appRefer);

    //公司入款-第三方微信
    @POST("api/account/deposit_two_third_wx.php")
    @FormUrlEncoded
    public Observable<DepositThirdQQPayResult> postDepositThirdWXPay(@Field("appRefer") String appRefer);

    //公司入款-第三方支付宝
    @POST("api/account/deposit_two_third_zfb.php")
    @FormUrlEncoded
    public Observable<DepositThirdQQPayResult> postDepositThirdAliPay(@Field("appRefer") String appRefer);

    //公司入款-第三方QQ
    @POST("api/account/deposit_two_third_qq.php")
    @FormUrlEncoded
    public Observable<DepositThirdQQPayResult> postDepositThirdQQPay(@Field("appRefer") String appRefer);

    //公司入款-支付宝二维码
    @POST("api/account/bank_type_ALISAOMA_api.php")
    @FormUrlEncoded
    public Observable<DepositAliPayQCCodeResult> postDepositAliPayQCCode(@Field("appRefer") String appRefer,@Field("bankid") String bankid);

    //公司入款-微信二维码
    @POST("api/account/bank_type_WESAOMA_api.php")
    @FormUrlEncoded
    public Observable<DepositAliPayQCCodeResult> postDepositWechatQCCode(@Field("appRefer") String appRefer, @Field("bankid") String bankid);


    //公司入款-银行卡支付提交
    @POST("api/account/deposit_two_bank_company_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postDepositCompanyPaySubimt(@Field("appRefer") String appRefer, @Field("payid") String payid, @Field("v_Name") String v_Name, @Field("InType") String InType, @Field("v_amount") String v_amount, @Field("cn_date") String cn_date, @Field("memo") String memo, @Field("IntoBank") String IntoBank);


    //公司入款-支付宝二维码 微信二维码 提交
    @POST("api/account/bank_type_SAOMA_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postDepositAliPayQCPaySubimt(@Field("appRefer") String appRefer, @Field("payid") String payid, @Field("v_amount") String v_amount, @Field("cn_date") String cn_date, @Field("memo") String memo, @Field("bank_user") String bank_user);


}

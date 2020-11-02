package com.hgapp.betnhg.depositpage.thirdmobilepay;

import com.hgapp.betnhg.common.http.request.AppTextMessageResponse;
import com.hgapp.betnhg.data.SportsPlayMethodRBResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IDepositThirdMobileSubmitApi {
    //公司入款-银行卡支付提交 / 微信支付提交  / QQ支付提交
    @POST("account/deposit_two_bank_company_save.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<SportsPlayMethodRBResult>> postDepositThirdSubmit(@Field("order_amount") String order_amount, @Field("pid") String pid, @Field("onlineIntoBank") String onlineIntoBank, @Field("uid") String uid, @Field("userid") String userid, @Field("payid") String payid, @Field("min_money") String min_money);

}

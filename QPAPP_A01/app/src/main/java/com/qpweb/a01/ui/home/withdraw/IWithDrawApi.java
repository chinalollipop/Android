package com.qpweb.a01.ui.home.withdraw;


import com.qpweb.a01.data.BankListResult;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.data.MemValidBetResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IWithDrawApi {

    //提款第一步 先请求打码量是否满足
    @POST("api/getMemValidBet.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<MemValidBetResult>> postMemValidBet(
            @Field("appRefer") String appRefer
    );

    //提款第二步
    @POST("api/account/withdraw.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BindCardResult>> postWithDraw(
            @Field("appRefer") String appRefer, @Field("Bank_Address") String Bank_Address,
            @Field("Bank_Account") String Bank_Account, @Field("Bank_Name") String Bank_Name,
            @Field("Money") String Money, @Field("Withdrawal_Passwd") String Withdrawal_Passwd,
            @Field("Alias") String Alias);

}

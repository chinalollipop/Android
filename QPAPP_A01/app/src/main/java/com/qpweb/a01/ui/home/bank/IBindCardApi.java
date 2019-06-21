package com.qpweb.a01.ui.home.bank;


import com.qpweb.a01.data.BankListResult;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IBindCardApi {

    //获取银行卡列表 如果有银行卡直接展示出来
    @POST("api/account/bank_list.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BankListResult>> postBankList(
            @Field("appRefer") String appRefer
    );

    //绑定银行卡
    @POST("api/account/bank_add_card.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BindCardResult>> postBindBank(
            @Field("appRefer") String appRefer, @Field("real_name") String real_name,
            @Field("bank_Account") String bank_Account, @Field("bank_Address") String bank_Address,
            @Field("bank_Id") String bank_Id);

}

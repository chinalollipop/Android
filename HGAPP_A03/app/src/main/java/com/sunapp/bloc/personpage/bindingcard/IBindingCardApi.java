package com.sunapp.bloc.personpage.bindingcard;

import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.data.GetBankCardListResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IBindingCardApi {


    //获取银行卡列表
    @POST("account/bankcard.php")
    @FormUrlEncoded
    public Observable<GetBankCardListResult> postGetBankCardList(@Field("appRefer") String appRefer, @Field("action_type") String action_type);

    //绑定银行卡
    @POST("account/bankcard.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postBindingBankCard(@Field("appRefer") String appRefer, @Field("action_type") String action_type, @Field("bank_name") String bank_name, @Field("bank_account") String bank_account, @Field("bank_address") String bank_address, @Field("pay_password") String pay_password, @Field("pay_password2") String pay_password2);

}

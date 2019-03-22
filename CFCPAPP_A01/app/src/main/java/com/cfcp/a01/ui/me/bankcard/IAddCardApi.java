package com.cfcp.a01.ui.me.bankcard;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.BankCardAddResult;
import com.cfcp.a01.data.BankListResult;
import com.cfcp.a01.data.LoginResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IAddCardApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BankListResult>> getBankList(
            @QueryMap Map<String, String> params
    );

    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BankCardAddResult>> getAddCard(
            @QueryMap Map<String, String> params
    );
}

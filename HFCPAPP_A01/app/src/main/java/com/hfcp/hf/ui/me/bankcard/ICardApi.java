package com.hfcp.hf.ui.me.bankcard;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.BankCardListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface ICardApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BankCardListResult>> getBankCardList(
            @QueryMap Map<String, String> params
    );
}

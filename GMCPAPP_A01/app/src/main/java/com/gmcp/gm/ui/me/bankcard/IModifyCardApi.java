package com.gmcp.gm.ui.me.bankcard;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.BankCardAddResult;
import com.gmcp.gm.data.BankListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IModifyCardApi {

    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BankListResult>> getBankList(
            @QueryMap Map<String, String> params
    );


    //修改银行卡(encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BankCardAddResult>> getModifyCard(
            @QueryMap Map<String, String> params
    );


}

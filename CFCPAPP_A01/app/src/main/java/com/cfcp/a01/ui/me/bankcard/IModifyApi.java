package com.cfcp.a01.ui.me.bankcard;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.BankCardListResult;
import com.cfcp.a01.data.TeamReportResult;

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

public interface IModifyApi {

    //修改银行卡(encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<TeamReportResult>> getCardModify(
            @QueryMap Map<String, String> params
    );

    //验证银行卡(encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<TeamReportResult>> getCardVerify(
            @QueryMap Map<String, String> params
    );

    //删除银行卡(encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<TeamReportResult>> getCardDelete(
            @QueryMap Map<String, String> params
    );

}

package com.cfcp.a01.ui.home.deposit;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.DepositMethodResult;
import com.cfcp.a01.data.DepositTypeResult;
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

public interface IDepositApi {

    //存款方式
    @GET("service")
    Observable<AppTextMessageResponse<DepositMethodResult>> getDepositMethod(
            @QueryMap Map<String, String> params
    );

    //存款方式确认
    @GET("service")
    Observable<AppTextMessageResponse<DepositTypeResult>> getDepositVerify(
            @QueryMap Map<String, String> params
    );
}

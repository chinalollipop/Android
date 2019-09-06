package com.gmcp.gm.ui.home.deposit;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.DepositMethodResult;
import com.gmcp.gm.data.DepositTypeResult;

import java.util.Map;

import retrofit2.http.GET;
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

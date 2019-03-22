package com.cfcp.a01.ui.home.deposit;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.DepositMethodResult;
import com.cfcp.a01.data.LoginResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IDepositSubmitApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<LoginResult>> getDepositSubmit(
            @QueryMap Map<String, String> params
    );
}

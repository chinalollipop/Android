package com.cfcp.a01.ui.home.withdraw.submit;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.WithDrawNextResult;
import com.cfcp.a01.data.WithDrawResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IWithDrawSubmitApi {


    @GET("service")
    Observable<AppTextMessageResponse<WithDrawNextResult>> getWithDrawSubmit(
            @QueryMap Map<String, String> params
    );
}

package com.cfcp.a01.ui.home.withdraw;


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

public interface IWithDrawApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<WithDrawResult>> getWithDraw(
            @QueryMap Map<String, String> params
    );
    @GET("service")
    Observable<AppTextMessageResponse<WithDrawNextResult>> getWithDrawNext(
            @QueryMap Map<String, String> params
    );
}

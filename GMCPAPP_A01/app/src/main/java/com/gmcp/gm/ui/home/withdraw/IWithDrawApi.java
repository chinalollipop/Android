package com.gmcp.gm.ui.home.withdraw;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.WithDrawNextResult;
import com.gmcp.gm.data.WithDrawResult;

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
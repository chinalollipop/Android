package com.gmcp.gm.ui.home.withdraw.submit;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.WithDrawNextResult;

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

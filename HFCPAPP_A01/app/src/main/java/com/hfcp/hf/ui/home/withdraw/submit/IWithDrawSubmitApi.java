package com.hfcp.hf.ui.home.withdraw.submit;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.WithDrawNextResult;

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

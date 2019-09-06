package com.hfcp.hf.ui.me.record.betdetail;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.BetDetailResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IBetDetailApi {


    //注单列表 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BetDetailResult>> getProjectDetail(
            @QueryMap Map<String, String> params
    );
}

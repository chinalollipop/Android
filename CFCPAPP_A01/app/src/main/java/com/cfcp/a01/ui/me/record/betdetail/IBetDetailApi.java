package com.cfcp.a01.ui.me.record.betdetail;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.BetDetailResult;

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

package com.cfcp.a01.ui.me.record.tracedetail;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.TraceDetailResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface ITraceDetailApi {


    //追号列表详情提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<TraceDetailResult>> getTraceDetail(
            @QueryMap Map<String, String> params
    );
}

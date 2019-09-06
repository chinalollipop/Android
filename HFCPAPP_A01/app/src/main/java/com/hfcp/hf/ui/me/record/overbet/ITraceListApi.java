package com.hfcp.hf.ui.me.record.overbet;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.TraceListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface ITraceListApi {


    //追号列表提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<TraceListResult>> getTraceList(
            @QueryMap Map<String, String> params
    );
}

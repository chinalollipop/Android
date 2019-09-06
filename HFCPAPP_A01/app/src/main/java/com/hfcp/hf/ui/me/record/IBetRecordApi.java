package com.hfcp.hf.ui.me.record;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.BetRecordResult;
import com.hfcp.hf.data.BetRecordsResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IBetRecordApi {


    //注单列表 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BetRecordResult>> getProjectList(
            @QueryMap Map<String, String> params
    );

    @GET("service")
    Observable<AppTextMessageResponse<BetRecordsResult>> getCpBetRecords(
            @QueryMap Map<String, String> params);

}

package com.gmcp.gm.ui.me.record;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.BetRecordResult;
import com.gmcp.gm.data.BetRecordsResult;

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

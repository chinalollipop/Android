package com.cfcp.a01.ui.home.cplist.bet.betrecords;

import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.data.BetRecordsResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import retrofit2.http.Url;
import rx.Observable;

public interface ICpBetRecordsApi {

    @GET("service")
    Observable<AppTextMessageResponse<BetRecordsResult>> getCpBetRecords(@QueryMap Map<String, String> params);

    @POST
    Observable<BetRecordsResult> postCpBetRecords(@Url String path);

}

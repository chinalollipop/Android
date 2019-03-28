package com.cfcp.a01.ui.home.cplist.bet.betrecords.chonglong;

import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.CPBetNowResult;
import com.cfcp.a01.data.CPChangLongResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import retrofit2.http.Url;
import rx.Observable;

public interface ICpChangLongApi {

    @GET("service")
    Observable<AppTextMessageResponse<CPChangLongResult>> getCpBetRecords(@QueryMap Map<String, String> params);

    @POST
    Observable<CPBetNowResult> postCpBetRecords(@Url String path);

}

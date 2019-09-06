package com.hfcp.hf.ui.home.cplist.bet.betrecords.chonglong;

import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.CPBetNowResult;
import com.hfcp.hf.data.CPChangLongResult;

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

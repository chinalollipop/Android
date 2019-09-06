package com.hfcp.hf.ui.home.cplist.bet.betrecords.betnow;

import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.CPBetNowResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import retrofit2.http.Url;
import rx.Observable;

public interface ICpBetNowApi {

    @GET("service")
    Observable<AppTextMessageResponse<CPBetNowResult>> getCpBetRecords(@QueryMap Map<String, String> params);

    @POST
    Observable<CPBetNowResult> postCpBetRecords(@Url String path);

}

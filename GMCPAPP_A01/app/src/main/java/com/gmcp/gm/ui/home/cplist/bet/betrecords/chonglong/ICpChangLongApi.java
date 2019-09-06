package com.gmcp.gm.ui.home.cplist.bet.betrecords.chonglong;

import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.CPBetNowResult;
import com.gmcp.gm.data.CPChangLongResult;

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

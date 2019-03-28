package com.cfcp.a01.ui.home.cplist.lottery;

import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.CPLotteryListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.Headers;
import retrofit2.http.QueryMap;
import retrofit2.http.Url;
import rx.Observable;

public interface ICPLotteryListApi {

    @GET("service")
    Observable<AppTextMessageResponse<CPLotteryListResult>> get(@QueryMap Map<String, String> params);

}

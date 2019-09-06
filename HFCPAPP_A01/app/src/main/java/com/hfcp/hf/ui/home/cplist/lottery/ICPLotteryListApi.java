package com.hfcp.hf.ui.home.cplist.lottery;

import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.CPLotteryListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

public interface ICPLotteryListApi {

    @GET("service")
    Observable<AppTextMessageResponse<CPLotteryListResult>> get(@QueryMap Map<String, String> params);

}

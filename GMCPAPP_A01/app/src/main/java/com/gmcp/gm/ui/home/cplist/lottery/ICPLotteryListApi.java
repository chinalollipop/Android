package com.gmcp.gm.ui.home.cplist.lottery;

import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.CPLotteryListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

public interface ICPLotteryListApi {

    @GET("service")
    Observable<AppTextMessageResponse<CPLotteryListResult>> get(@QueryMap Map<String, String> params);

}

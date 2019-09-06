package com.hfcp.hf.ui.lottery;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.common.http.request.AppTextMessageResponseList;
import com.hfcp.hf.data.CPLotteryListResult;
import com.hfcp.hf.data.LotteryListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface ILotteryResultApi {

    //开奖结果
    @GET("service")
    Observable<AppTextMessageResponseList<LotteryListResult>> getLotteryListResult(
            @QueryMap Map<String, String> params
    );

    //信用盘的开奖结果
    @GET("service")
    Observable<AppTextMessageResponse<CPLotteryListResult>> get(@QueryMap Map<String, String> params);

}

package com.cfcp.a01.ui.home.bet;

import com.cfcp.a01.data.BetDataResult;
import com.cfcp.a01.data.BetGameSettingsForRefreshResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

public interface IBetFragmentApi {

    //投注页获取指定游戏数据（玩法、奖期等信息）
    @GET("service")
    Observable<BetGameSettingsForRefreshResult> getGameSettingsForRefresh(
            @QueryMap Map<String, String> params
    );

    //投注接口
    @POST("service")
    Observable<BetDataResult> getBet(
            @QueryMap Map<String, String> params
    );

}
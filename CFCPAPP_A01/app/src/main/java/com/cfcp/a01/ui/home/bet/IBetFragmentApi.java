package com.cfcp.a01.ui.home.bet;

import com.cfcp.a01.data.AllGamesResult;
import com.cfcp.a01.data.BetDataResult;
import com.cfcp.a01.data.BetGameSettingsForRefreshResult;
import com.cfcp.a01.data.GamesTipsResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

public interface IBetFragmentApi {

    //投注页获取指定游戏数据（玩法、奖期等信息）
    @GET("service")
    Observable<BetGameSettingsForRefreshResult> getGameSettingsForRefresh(@QueryMap Map<String, String> params);

    //投注接口
    @POST("service")
    Observable<BetDataResult> getBet(@QueryMap Map<String, String> params);

    //所有游戏列表
    @GET("service")
    Observable<AllGamesResult> getAllGames(@QueryMap Map<String, String> params);

    //开奖提示
    @GET("service")
    Observable<GamesTipsResult> getGamesTips(@QueryMap Map<String, String> params);
}

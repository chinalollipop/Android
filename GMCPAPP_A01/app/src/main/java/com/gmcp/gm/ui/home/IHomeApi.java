package com.gmcp.gm.ui.home;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.http.request.AppTextMessageResponseList;
import com.gmcp.gm.data.AllGamesResult;
import com.gmcp.gm.data.BannerResult;
import com.gmcp.gm.data.GameQueueMoneyResult;
import com.gmcp.gm.data.LogoutResult;
import com.gmcp.gm.data.NoticeResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IHomeApi {

    //banner图片
    @GET("service")
    Observable<BannerResult> getBanner(
            @QueryMap Map<String, String> params);

    //滚动公告notice(1:滚动公告；2：公告列表；3：站内信)
    @GET("service")
    Observable<NoticeResult> getNotice(@QueryMap Map<String, String> params);

    //所有游戏列表
    @GET("service")
    Observable<AllGamesResult> getAllGames(@QueryMap Map<String, String> params);

    //AG电子游戏列表
    @GET("service")
    Observable<AllGamesResult> getAGGames(@QueryMap Map<String, String> params);

    //AG真人游戏列表
    @GET("service")
    Observable<AllGamesResult> getAGVideoGames(@QueryMap Map<String, String> params);

    //AG捕鱼游戏
    @GET("service")
    Observable<AllGamesResult> getAGFishGames(@QueryMap Map<String, String> params);



    @GET("service")
    Observable<AppTextMessageResponse<GameQueueMoneyResult>> getPlayOutWithMoney(
            @QueryMap Map<String, String> params
    );


    //退出
    @POST("api/logout.php")
    @FormUrlEncoded
    Observable<AppTextMessageResponseList<LogoutResult>> postLogout(@Field("appRefer") String appRefer);


    //双面盘联合登录接口
    @POST("service")//?action=CreditLogin&packet=Credit&token={id}
    @FormUrlEncoded
    Observable<AllGamesResult> getJointLogin(@Field("action") String action,@Field("packet") String packet,@Field("token") String token,
                                             @Field("username") String username,@Field("credit_token") String credit_token,@Field("terminal_id") String terminal_id);

}

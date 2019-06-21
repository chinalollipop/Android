package com.qpweb.a01.ui.home.fenhong;


import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.TouziResult;
import com.qpweb.a01.data.TouziYestodayResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IDividendApi {

    //昨日投资榜
    @POST("api/touzi_fenhong.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<TouziYestodayResult>> postTouziYestodayList(
            @Field("appRefer") String appRefer, @Field("action") String action);

    //投资签到
    @POST("api/touzi_fenhong.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RedPacketResult>> postTouziSign(
            @Field("appRefer") String appRefer, @Field("action") String action);

    //个人投资
    @POST("api/touzi_fenhong.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RedPacketResult>> postTouzi(
            @Field("appRefer") String appRefer, @Field("action") String action, @Field("Money") String Money);


    //个人投资记录
    @POST("api/touzi_fenhong.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<TouziResult>> postTouziRecord(
            @Field("appRefer") String appRefer, @Field("action") String action);

}

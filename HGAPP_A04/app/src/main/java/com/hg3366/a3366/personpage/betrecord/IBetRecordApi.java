package com.hg3366.a3366.personpage.betrecord;

import com.hg3366.a3366.common.http.request.AppTextMessageResponse;
import com.hg3366.a3366.data.BetRecordResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IBetRecordApi {

    /**
     * /wagers_api.php 投注记录
     * gtype  赛事类型，FT 足球、BK 篮球
     * Checked  是否结算 ，N 未结注单 Y 已结注单
     * Cancel  是否取消 , Y  取消交易单 N 未取消交易单
     * date_start 2018-09-18 00:00:01
     * date_end  2018-09-18 23:59:59
     * page 从第0页开始
     */
    @POST("wagers_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetRecordResult>> postBetRecordList(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("Checked") String Checked, @Field("Cancel") String Cancel, @Field("date_start") String date_start, @Field("date_end") String date_end, @Field("page") String page);

    /**
     * /historyAgGameApi.php 电子投注记录
     * gtype  赛事类型，aglive AG电子、aggame AG视讯、agby AG捕鱼
     * Checked  是否结算 ，N 未结注单 Y 已结注单
     * Cancel  是否取消 , Y  取消交易单 N 未取消交易单
     * date_start 2018-09-18 00:00:01
     * date_end  2018-09-18 23:59:59
     * page 从第0页开始
     */
    @POST("api/historyAgGameApi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetRecordResult>> postBetAGDZRecordList(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("Checked") String Checked, @Field("Cancel") String Cancel, @Field("date_start") String date_start, @Field("date_end") String date_end, @Field("page") String page);


    /**
     * /historyAgGameApi.php 电子投注记录
     * gtype  赛事类型，kyqp 开元棋牌、lyqp 乐游棋牌、vgqp VG棋牌、hgqp 皇冠棋牌、mgdz MG电子、avia 泛亚电竞、oglive  OG视讯 、cq9dz CQ9电、mwdz  MW电子
     * Checked  是否结算 ，N 未结注单 Y 已结注单
     * Cancel  是否取消 , Y  取消交易单 N 未取消交易单
     * date_start 2018-09-18 00:00:01
     * date_end  2018-09-18 23:59:59
     * page 从第0页开始
     */
    @POST("api/betHistoryApi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetRecordResult>> postBetODZRecordList(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("Checked") String Checked, @Field("Cancel") String Cancel, @Field("date_start") String date_start, @Field("date_end") String date_end, @Field("page") String page);

}

package com.qpweb.a01.ui.home.agency;


import com.qpweb.a01.data.DetailWeekListResult;
import com.qpweb.a01.data.MyAgencyResults;
import com.qpweb.a01.data.DetailListResult;
import com.qpweb.a01.data.ProListResults;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IAgencyApi {

    //我的推广
    @POST("api/report/promotion_list.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<MyAgencyResults>> postMyProList(
            @Field("appRefer") String appRefer, @Field("action") String action);

    //推广详情
    @POST("api/report/promotion_detail.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<DetailListResult>> postProDetail(
            @Field("appRefer") String appRefer, @Field("action") String action);
    //推广周榜
    @POST("api/report/promotion_week_rank.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<DetailWeekListResult>> postWeeksDetail(
            @Field("appRefer") String appRefer, @Field("action") String action);
    //领取奖金
    @POST("api/account/promotion_get.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<RedPacketResult>> postGetMyPromotion(
            @Field("appRefer") String appRefer, @Field("action") String action);
    //领取奖金
    @POST("api/report/promotion_get_check_records.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ProListResults>> postGetMyPromotionRecord(
            @Field("appRefer") String appRefer, @Field("action") String action);

}

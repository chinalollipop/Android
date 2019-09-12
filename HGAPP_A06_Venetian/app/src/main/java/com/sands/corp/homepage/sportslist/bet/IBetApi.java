package com.sands.corp.homepage.sportslist.bet;

import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.data.BetResult;
import com.sands.corp.data.SportsPlayMethodResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IBetApi {

    //玩法列表
    @POST("match_api.php")
    @FormUrlEncoded
    public Observable<SportsPlayMethodResult> postSprotsPlayMethod(@Field("appRefer") String appRefer, @Field("type") String type, @Field("more") String more, @Field("gid") String gid);

    //滚球玩法列表
    @POST("match_api.php")
    @FormUrlEncoded
    public Observable<String> postSprotsPlayRBMethod(@Field("appRefer") String appRefer, @Field("type") String type, @Field("more") String more, @Field("gid") String gid);

    //投注接口
    @POST("order/order_finish_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetResult>> postBet(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype,@Field("randomNum") String randomNum);



}

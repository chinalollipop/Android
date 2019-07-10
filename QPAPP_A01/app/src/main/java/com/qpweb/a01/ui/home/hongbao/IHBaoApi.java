package com.qpweb.a01.ui.home.hongbao;


import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.ValidResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IHBaoApi {

    //获取昨日有效金额、可领次数
    @POST("api/activity/lucky_red_envelope_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<ValidResult>> postValid(
            @Field("appRefer") String appRefer, @Field("action") String action);

    //领取红包
    @POST("api/activity/lucky_red_envelope_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<RedPacketResult>> postLuckEnvelope(
            @Field("appRefer") String appRefer, @Field("action") String action);
    //获取红包记录
    @POST("api/activity/lucky_red_envelope_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ValidResult>> postLuckEnvelopeRecord(
            @Field("appRefer") String appRefer, @Field("action") String action);

}

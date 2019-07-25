package com.cfcp.a01.ui.home.dragon;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.BetDragonResult;
import com.cfcp.a01.data.BetRecordsResult;
import com.cfcp.a01.data.CPBetResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IDragonApi {

    @POST("service")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<CPBetResult>> postCpBets(@Field("action") String action, @Field("packet") String packet,
                                                                      @Field("betdata") String betdata, @Field("token") String token);

    //修改资金密码(encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BetDragonResult>> getDragonBetList(
            @QueryMap Map<String, String> params);

    //修改密码(encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<BetRecordsResult>> getDragonBetRecordList(
            @QueryMap Map<String, String> params
    );

}
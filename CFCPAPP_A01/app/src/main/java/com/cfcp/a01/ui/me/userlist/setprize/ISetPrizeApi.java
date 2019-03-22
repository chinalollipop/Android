package com.cfcp.a01.ui.me.userlist.setprize;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LowerInfoDataResult;

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

public interface ISetPrizeApi {

    //存款方式提交 (encoded = true)
    @POST("service")
    @FormUrlEncoded
    Observable<AppTextMessageResponse<LoginResult>> getRealName(
            @Field("terminal_id") String terminal_id,
            @Field("packet") String packet,
            @Field("action") String action,
            @Field("name") String name,
            @Field("token") String token
    );

    //下级个人信息
    @GET("service")
    Observable<AppTextMessageResponse<LowerInfoDataResult>> getLowerLevelReport(
            @QueryMap Map<String, String> params
    );

}

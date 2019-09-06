package com.hfcp.hf.ui.me.info;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.LoginResult;
import com.hfcp.hf.data.LowerInfoDataResult;

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

public interface IInfoApi {


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

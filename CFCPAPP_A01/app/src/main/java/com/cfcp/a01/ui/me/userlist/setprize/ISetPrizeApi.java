package com.cfcp.a01.ui.me.userlist.setprize;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LowerInfoDataResult;
import com.cfcp.a01.data.LowerSetDataResult;

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
    @GET("service")
    Observable<AppTextMessageResponse<LoginResult>> getRealName(
            @QueryMap Map<String, String> params
    );

    //下级个人信息
    @GET("service")
    Observable<AppTextMessageResponse<LowerSetDataResult>> getLowerLevelReport(
            @QueryMap Map<String, String> params
    );

}

package com.hfcp.hf.ui.me.userlist.setprize;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.LoginResult;
import com.hfcp.hf.data.LowerSetDataResult;

import java.util.Map;

import retrofit2.http.GET;
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

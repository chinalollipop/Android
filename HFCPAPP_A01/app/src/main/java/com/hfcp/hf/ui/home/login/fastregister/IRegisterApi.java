package com.hfcp.hf.ui.home.login.fastregister;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.LoginResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IRegisterApi {


    //会员注册
    @GET("service")
    Observable<AppTextMessageResponse<LoginResult>> registerMember(
            @QueryMap Map<String, String> params
    );

}

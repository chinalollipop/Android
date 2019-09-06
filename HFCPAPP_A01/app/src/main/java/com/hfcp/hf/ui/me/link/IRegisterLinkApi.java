package com.hfcp.hf.ui.me.link;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.RegisterLinkListResult;
import com.hfcp.hf.data.RegisterMeResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IRegisterLinkApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<RegisterMeResult>> getFundGroup(
            @QueryMap Map<String, String> params
    );
    @GET("service")
    Observable<AppTextMessageResponse<RegisterLinkListResult>> getFundList(
            @QueryMap Map<String, String> params
    );
}

package com.gmcp.gm.ui.home.deposit;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.DepositH5Result;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IDepositSubmitApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<DepositH5Result>> getDepositSubmit(
            @QueryMap Map<String, String> params
    );
}
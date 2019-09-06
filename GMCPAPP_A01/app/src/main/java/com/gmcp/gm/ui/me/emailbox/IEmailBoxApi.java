package com.gmcp.gm.ui.me.emailbox;


import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.EmailBoxListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IEmailBoxApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<EmailBoxListResult>> getPersonReport(
            @QueryMap Map<String, String> params
    );
}

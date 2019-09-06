package com.hfcp.hf.ui.me.report;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.PersonReportResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IPersonApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<PersonReportResult>> getPersonReport(
            @QueryMap Map<String, String> params
    );
}

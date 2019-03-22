package com.cfcp.a01.ui.me.report;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.TeamReportResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface ITeamApi {

    //团队区间报表 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponse<TeamReportResult>> getTeamReport(
            @QueryMap Map<String, String> params
    );
}

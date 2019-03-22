package com.cfcp.a01.ui.main.upgrade;

import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.data.CheckUpgradeResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @GET("service")
    public Observable<AppTextMessageResponseList<CheckUpgradeResult>> checkupdate(@QueryMap Map<String, String> params);
}

package com.gmcp.gm.ui.main.upgrade;

import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.data.CheckUpgradeResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @GET("service")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate(@QueryMap Map<String, String> params);
}

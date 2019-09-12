package com.sands.corp.upgrade;

import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

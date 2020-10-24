package com.hgapp.a0086.upgrade;

import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

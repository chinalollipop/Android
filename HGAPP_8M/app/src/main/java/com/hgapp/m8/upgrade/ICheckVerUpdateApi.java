package com.hgapp.m8.upgrade;

import com.hgapp.m8.common.http.request.AppTextMessageResponse;
import com.hgapp.m8.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

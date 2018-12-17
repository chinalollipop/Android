package com.hgapp.a6668.upgrade;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

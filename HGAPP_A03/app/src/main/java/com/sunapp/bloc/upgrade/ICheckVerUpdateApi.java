package com.sunapp.bloc.upgrade;

import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

package com.venen.tian.upgrade;

import com.venen.tian.common.http.request.AppTextMessageResponse;
import com.venen.tian.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

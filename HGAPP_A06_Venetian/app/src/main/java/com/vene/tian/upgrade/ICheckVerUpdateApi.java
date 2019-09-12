package com.vene.tian.upgrade;

import com.vene.tian.common.http.request.AppTextMessageResponse;
import com.vene.tian.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

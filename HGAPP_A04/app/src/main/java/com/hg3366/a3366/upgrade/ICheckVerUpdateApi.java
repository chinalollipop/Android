package com.hg3366.a3366.upgrade;

import com.hg3366.a3366.common.http.request.AppTextMessageResponse;
import com.hg3366.a3366.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

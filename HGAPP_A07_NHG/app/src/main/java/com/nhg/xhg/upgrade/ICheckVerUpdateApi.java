package com.nhg.xhg.upgrade;

import com.nhg.xhg.common.http.request.AppTextMessageResponse;
import com.nhg.xhg.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

package com.hgapp.bet365.upgrade;

import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

package com.hgapp.betnew.upgrade;

import com.hgapp.betnew.common.http.request.AppTextMessageResponse;
import com.hgapp.betnew.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

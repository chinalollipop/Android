package com.hgapp.betnhg.upgrade;

import com.hgapp.betnhg.common.http.request.AppTextMessageResponse;
import com.hgapp.betnhg.data.CheckUpgradeResult;

import retrofit2.http.POST;
import rx.Observable;

public interface ICheckVerUpdateApi {
    @POST("mrelease.php?appRefer=14")
    public Observable<AppTextMessageResponse<CheckUpgradeResult>> checkupdate();
}

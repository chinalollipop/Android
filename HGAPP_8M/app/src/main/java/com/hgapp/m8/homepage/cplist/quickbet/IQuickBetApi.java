package com.hgapp.m8.homepage.cplist.quickbet;

import com.hgapp.m8.common.http.request.AppTextMessageResponse;

import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface IQuickBetApi {

    @POST("logout")
    public Observable<AppTextMessageResponse> logout();
}

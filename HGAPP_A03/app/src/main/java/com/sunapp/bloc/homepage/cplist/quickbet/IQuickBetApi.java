package com.sunapp.bloc.homepage.cplist.quickbet;

import com.sunapp.bloc.common.http.request.AppTextMessageResponse;

import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface IQuickBetApi {

    @POST("logout")
    public Observable<AppTextMessageResponse> logout();
}

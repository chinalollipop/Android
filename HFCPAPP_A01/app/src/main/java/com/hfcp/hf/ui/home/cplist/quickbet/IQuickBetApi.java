package com.hfcp.hf.ui.home.cplist.quickbet;


import com.hfcp.hf.common.http.request.AppTextMessageResponse;

import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface IQuickBetApi {

    @POST("logout")
    public Observable<AppTextMessageResponse> logout();
}

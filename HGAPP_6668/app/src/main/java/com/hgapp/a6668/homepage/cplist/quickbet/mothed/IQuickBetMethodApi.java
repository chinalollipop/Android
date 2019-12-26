package com.hgapp.a6668.homepage.cplist.quickbet.mothed;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.data.CPQuickBetMothedResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface IQuickBetMethodApi {
    @FormUrlEncoded
    @POST("game/ajax_post")
    public Observable<CPQuickBetMothedResult> postQuickBetMothed(@Field("code") String code,@Field("gamecode") String gamecode,@Field("code_number") String code_number,@Field("sort") String sort,@Field("x-session-token") String token);
}
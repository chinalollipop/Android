package com.hgapp.m8.homepage.handicap.saiguo;

import com.hgapp.m8.data.SaiGuoResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ISaiGuoApi {

    @POST("result.php")
    @FormUrlEncoded
    public Observable<SaiGuoResult> postSaiGuoList(@Field("appRefer") String appRefer, @Field("game_type") String game_type, @Field("list_data") String list_data);

}

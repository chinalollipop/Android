package com.hgapp.betnew.personpage.onlineservice;

import com.hgapp.betnew.common.http.request.AppTextMessageResponse;
import com.hgapp.betnew.data.OnlineServiceResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IOnLineServiceApi {

    //在线客服
    @POST("kefu_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<OnlineServiceResult>> postDepositRecord(@Field("appRefer") String appRefer);

}

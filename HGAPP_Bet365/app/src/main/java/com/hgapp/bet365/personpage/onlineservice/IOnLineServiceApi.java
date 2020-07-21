package com.hgapp.bet365.personpage.onlineservice;

import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.data.OnlineServiceResult;

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

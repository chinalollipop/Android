package com.qpweb.a01.ui.home.fastlogout;


import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.LogoutResult;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2019/1/8.
 */

public interface ILogoutApi {

    //退出
    @POST("api/logout.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LogoutResult>> postLogout(@Field("appRefer") String appRefer);

}

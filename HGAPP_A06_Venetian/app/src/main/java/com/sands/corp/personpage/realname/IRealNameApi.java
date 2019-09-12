package com.sands.corp.personpage.realname;


import com.sands.corp.common.http.request.AppTextMessageResponseList;
import com.sands.corp.data.LoginResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

/**
 * Created by Daniel on 2018/7/3.
 */

public interface IRealNameApi {


    //先设置真实姓名
    @POST("account/update_realname.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LoginResult>> postUpdataRealName(
            @Field("appRefer") String appRefer, @Field("realname") String realname, @Field("phone") String phone,
            @Field("wechat") String wechat, @Field("birthday") String birthday);
}

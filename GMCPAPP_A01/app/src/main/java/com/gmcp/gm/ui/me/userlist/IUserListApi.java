package com.gmcp.gm.ui.me.userlist;


import com.gmcp.gm.common.http.request.AppTextMessageResponseList;
import com.gmcp.gm.data.UserListResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IUserListApi {


    //存款方式提交 (encoded = true)
    @GET("service")
    Observable<AppTextMessageResponseList<UserListResult>> getUserList(
            @QueryMap Map<String, String> params
    );
}

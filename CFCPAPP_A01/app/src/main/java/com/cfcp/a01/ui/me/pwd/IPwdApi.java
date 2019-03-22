package com.cfcp.a01.ui.me.pwd;


import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.data.TeamReportResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/20.
 */

public interface IPwdApi {

    //修改资金密码首次(encoded = true)
    @POST("service")
    @FormUrlEncoded
    Observable<AppTextMessageResponse<TeamReportResult>> getChangeFundPwdFirst(
            @Field("terminal_id") String terminal_id,
            @Field("packet") String packet,
            @Field("action") String action,
            @Field("fund_password") String fund_password,
            @Field("confirm_fund_password") String confirm_fund_password,
            @Field("token") String token
    );
    //修改资金密码(encoded = true)
    @POST("service")
    @FormUrlEncoded
    Observable<AppTextMessageResponse<TeamReportResult>> getChangeFundPwd(
            @Field("terminal_id") String terminal_id,
            @Field("packet") String packet,
            @Field("action") String action,
            @Field("current_password") String current_password,
            @Field("new_password") String new_password,
            @Field("username") String username,
            @Field("token") String token
    );

    //修改密码(encoded = true)
    @POST("service")
    @FormUrlEncoded
    Observable<AppTextMessageResponse<TeamReportResult>> getChangeLoginPwd(
            @Field("terminal_id") String terminal_id,
            @Field("packet") String packet,
            @Field("action") String action,
            @Field("current_password") String current_password,
            @Field("new_password") String new_password,
            @Field("username") String username,
            @Field("token") String token
    );

}

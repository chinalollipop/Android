package com.cfcp.a01.ui.home.cplist.order;

import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.data.COLastResultHK;
import com.cfcp.a01.data.CPBJSCResult;
import com.cfcp.a01.data.CPHKResult;
import com.cfcp.a01.data.CPJSFTResult;
import com.cfcp.a01.data.CPJSK2Result;
import com.cfcp.a01.data.CPJSKSResult;
import com.cfcp.a01.data.CPJSSCResult;
import com.cfcp.a01.data.CPKL8Result;
import com.cfcp.a01.data.CPKLSFResult;
import com.cfcp.a01.data.CPLastResult;
import com.cfcp.a01.data.CPLeftInfoResult;
import com.cfcp.a01.data.CPNextIssueResult;
import com.cfcp.a01.data.CPQuickBetResult;
import com.cfcp.a01.data.CPXYNCResult;
import com.cfcp.a01.data.CQ1FCResult;
import com.cfcp.a01.data.CQ2FCResult;
import com.cfcp.a01.data.CQ3FCResult;
import com.cfcp.a01.data.CQ5FCResult;
import com.cfcp.a01.data.CQSSCResult;
import com.cfcp.a01.data.Cp11X5Result;
import com.cfcp.a01.data.PCDDResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
import retrofit2.http.Url;
import rx.Observable;

public interface ICPOrderApi {

    @GET("service")
    public Observable<AppTextMessageResponse<CPLeftInfoResult>> postCPLeftInfo(@QueryMap Map<String, String> params);


    @GET("service")
    public Observable<AppTextMessageResponse<CPNextIssueResult>> postNextIssue(@QueryMap Map<String, String> params);


    @GET("service")
    public Observable<AppTextMessageResponse<CPLastResult>> postLastResult(@QueryMap Map<String, String> params);


    /*//创建AG账号
    @Headers({"Domain-Name: CpUrl"})
    @GET
    Observable<CPHallResult> get(@Url String path);*/

    @POST("game/quick_bet_android")
    @FormUrlEncoded
    Observable<CPQuickBetResult> postQuickBet(@Field("game_code") String game_code, @Field("type") String type, @Field("sort") String sort, @Field("x-session-token") String x_session_token);



    @GET("service")
    Observable<AppTextMessageResponse<CPBJSCResult>> postRateInfoBjsc(@QueryMap Map<String, String> params);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPJSSCResult> postRateInfoJssc(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @GET("service")
    Observable<AppTextMessageResponse<CPJSFTResult>> postRateInfoJsft(@QueryMap Map<String, String> params);

    @GET("service")
    Observable<AppTextMessageResponse<CQSSCResult>> postRateInfo(@QueryMap Map<String, String> params);

    @GET("service")
    Observable<AppTextMessageResponse<CQSSCResult>> postRateInfo1FC(@QueryMap Map<String, String> params);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQ2FCResult> postRateInfo2FC(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQ3FCResult> postRateInfo3FC(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQ5FCResult> postRateInfo5FC(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);


    @POST("gamessc/lastresult")
    @FormUrlEncoded
    public Observable<COLastResultHK> postLastResultHK(@Field("game_code") String game_code, @Field("x-session-token") String x_session_token);


    @POST("gamexq/getNextIssue")
    @FormUrlEncoded
    public Observable<CPNextIssueResult> postNextIssueHK(@Field("game_code") String game_code, @Field("x-session-token") String x_session_token);

    @GET("service")
    Observable<AppTextMessageResponse<PCDDResult>> postRateInfoPCDD(@QueryMap Map<String, String> params);

    @GET("service")
    Observable<AppTextMessageResponse<CPJSKSResult>> postRateInfoJsk3(@QueryMap Map<String, String> params);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPJSK2Result> postRateInfoJsk32(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @GET("service")
    Observable<AppTextMessageResponse<CPXYNCResult>> postRateInfoXYnc(@QueryMap Map<String, String> params);

    @GET("service")
    Observable<AppTextMessageResponse<CPKLSFResult>> postRateInfoKlsf(@QueryMap Map<String, String> params);

    @GET("service")
    Observable<AppTextMessageResponse<CPKL8Result>> postRateInfoKl8(@QueryMap Map<String, String> params);

    @GET("service")
    Observable<AppTextMessageResponse<Cp11X5Result>> postRateInfo11X5(@QueryMap Map<String, String> params);

    @GET("service")
    Observable<AppTextMessageResponse<CPHKResult>> postRateInfoHK(@QueryMap Map<String, String> params);

}

package com.hfcp.hf.ui.home.cplist.order;

import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.data.COLastResultHK;
import com.hfcp.hf.data.CPBJSCResult;
import com.hfcp.hf.data.CPHKResult;
import com.hfcp.hf.data.CPJSFTResult;
import com.hfcp.hf.data.CPJSK2Result;
import com.hfcp.hf.data.CPJSKSResult;
import com.hfcp.hf.data.CPJSSCResult;
import com.hfcp.hf.data.CPKL8Result;
import com.hfcp.hf.data.CPKLSFResult;
import com.hfcp.hf.data.CPLastResult;
import com.hfcp.hf.data.CPLeftInfoResult;
import com.hfcp.hf.data.CPNextIssueResult;
import com.hfcp.hf.data.CPQuickBetResult;
import com.hfcp.hf.data.CPXYNCResult;
import com.hfcp.hf.data.CQ2FCResult;
import com.hfcp.hf.data.CQ3FCResult;
import com.hfcp.hf.data.CQ5FCResult;
import com.hfcp.hf.data.CQSSCResult;
import com.hfcp.hf.data.Cp11X5Result;
import com.hfcp.hf.data.GamesTipsResult;
import com.hfcp.hf.data.PCDDResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.QueryMap;
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

    //开奖提示
    @GET("service")
    Observable<GamesTipsResult> getGamesTips(@QueryMap Map<String, String> params);

}

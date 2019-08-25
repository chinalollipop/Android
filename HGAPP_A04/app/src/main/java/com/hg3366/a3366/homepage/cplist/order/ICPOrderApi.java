package com.hg3366.a3366.homepage.cplist.order;

import com.hg3366.a3366.data.COLastResultHK;
import com.hg3366.a3366.data.CPBJSCResult;
import com.hg3366.a3366.data.CPHKResult;
import com.hg3366.a3366.data.CPHallResult;
import com.hg3366.a3366.data.CPJSFTResult;
import com.hg3366.a3366.data.CPJSK2Result;
import com.hg3366.a3366.data.CPJSKSResult;
import com.hg3366.a3366.data.CPJSSCResult;
import com.hg3366.a3366.data.CPLastResult;
import com.hg3366.a3366.data.CPLeftInfoResult;
import com.hg3366.a3366.data.CPNextIssueResult;
import com.hg3366.a3366.data.CPQuickBetResult;
import com.hg3366.a3366.data.CPXYNCResult;
import com.hg3366.a3366.data.CQ1FCResult;
import com.hg3366.a3366.data.CQ2FCResult;
import com.hg3366.a3366.data.CQ3FCResult;
import com.hg3366.a3366.data.CQ5FCResult;
import com.hg3366.a3366.data.CQSSCResult;
import com.hg3366.a3366.data.PCDDResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.Url;
import rx.Observable;

public interface ICPOrderApi {

    @POST("main/leftinfo")
    @FormUrlEncoded
    public Observable<CPLeftInfoResult> postCPLeftInfo(@Field("optype") String optype, @Field("x-session-token") String x_session_token);

    //创建AG账号
    @Headers({"Domain-Name: CpUrl"})
    @GET
    Observable<CPHallResult> get(@Url String path);

    @POST("game/quick_bet_android")
    @FormUrlEncoded
    Observable<CPQuickBetResult> postQuickBet(@Field("game_code") String game_code, @Field("type") String type, @Field("sort") String sort, @Field("x-session-token") String x_session_token);


    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPBJSCResult> postRateInfoBjsc(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPJSSCResult> postRateInfoJssc(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPJSFTResult> postRateInfoJsft(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);


    @Headers({"Domain-Name: CpUrl"})
    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQSSCResult> postRateInfo(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQ1FCResult> postRateInfo1FC(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQ2FCResult> postRateInfo2FC(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQ3FCResult> postRateInfo3FC(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CQ5FCResult> postRateInfo5FC(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);


    @Headers({"Domain-Name: CpUrl"})
    @POST("gamessc/lastresult")
    @FormUrlEncoded
    public Observable<CPLastResult> postLastResult(@Field("game_code") String game_code, @Field("x-session-token") String x_session_token);

    @POST("gamessc/lastresult")
    @FormUrlEncoded
    public Observable<COLastResultHK> postLastResultHK(@Field("game_code") String game_code, @Field("x-session-token") String x_session_token);

    @Headers({"Domain-Name: CpUrl"})
    @POST("gamessc/getNextIssue")
    @FormUrlEncoded
    public Observable<CPNextIssueResult> postNextIssue(@Field("game_code") String game_code, @Field("x-session-token") String x_session_token);


    @POST("gamexq/getNextIssue")
    @FormUrlEncoded
    public Observable<CPNextIssueResult> postNextIssueHK(@Field("game_code") String game_code, @Field("x-session-token") String x_session_token);


    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<PCDDResult> postRateInfoPCDD(@Field("game_code") String game_code, @Field("x-session-token") String x_session_token);


    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPJSKSResult> postRateInfoJsk3(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPJSK2Result> postRateInfoJsk32(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);


    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPXYNCResult> postRateInfoXYnc(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamessc/getrateinfo")
    @FormUrlEncoded
    Observable<CPXYNCResult> postRateInfoKlsf(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

    @POST("gamexq/getrateinfo")
    @FormUrlEncoded
    Observable<CPHKResult> postRateInfoHK(@Field("game_code") String game_code, @Field("type") String type, @Field("x-session-token") String x_session_token);

}

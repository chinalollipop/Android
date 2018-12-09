package com.hgapp.a6668.homepage.cplist.bet;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.data.BetResult;
import com.hgapp.a6668.data.CPBetResult;
import com.hgapp.a6668.data.GameAllPlayBKResult;
import com.hgapp.a6668.data.GameAllPlayFTResult;
import com.hgapp.a6668.data.GameAllPlayRBKResult;
import com.hgapp.a6668.data.GameAllPlayRFTResult;
import com.hgapp.a6668.data.PrepareBetResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ICpBetApi {

    /**
     *
     * @param game_code  当前的彩种id
     * @param round     当前期号
     * @param totalNums     注数
     * @param totalMoney    金额
     * @param number        betBean[0][ip_3217]: 1  betBean[0][ip_3218]: 1  betBean[0][ip_3219]: 1
     * @param x_session_token
     * @return
     */
    @POST("bill/bet")
    @FormUrlEncoded
    public Observable<CPBetResult> postCpBets(@Field("game_code") String game_code, @Field("round") String round, @Field("totalNums") String totalNums,
                                                   @Field("totalMoney") String totalMoney,@Field("number") String number,@Field("x-session-token") String x_session_token);


    /**
     * 六合彩下单
     * @param game_code
     * @param round
     * @param totalNums
     * @param totalMoney
     * @param number
     * @param x_session_token
     * @return
     */
    @POST("billxq/bet")
    @FormUrlEncoded
    public Observable<CPBetResult> postCpBetsHK(@Field("game_code") String game_code, @Field("round") String round, @Field("totalNums") String totalNums,
                                              @Field("totalMoney") String totalMoney,@Field("number") String number,@Field("x-session-token") String x_session_token);


    /**
     * 连码下单
     * @param game_code
     * @param round
     * @param totalNums
     * @param totalMoney
     * @param number
     * @param betmoney
     * @param typecode
     * @param x_session_token
     * @return
     */
    @POST("bill/lianmaten_ok")
    @FormUrlEncoded
    public Observable<CPBetResult> postCpBetsLM(@Field("game_code") String game_code, @Field("round") String round, @Field("totalNums") String totalNums,
                                                @Field("totalMoney") String totalMoney,@Field("number") String number,
                                                @Field("betmoney") String betmoney,@Field("typecode") String typecode,@Field("x-session-token") String x_session_token);






}

package com.hgapp.a0086.homepage.cplist.bet;

import com.hgapp.a0086.data.CPBetResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FieldMap;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ICpBetApi {


    /**
     *
     * @param game_code             当前的彩种id
     * @param round                 当前期号
     * @param totalNums             注数
     * @param totalMoney            金额
     * @param number
     * @param fields                betBean[0][ip_3217]: 1  betBean[0][ip_3218]: 1  betBean[0][ip_3219]: 1
     * @param x_session_token       token
     * @return
     */
    @POST("bill/bet")
    @FormUrlEncoded
    public Observable<CPBetResult> postCpBets(@Field("game_code") String game_code, @Field("round") String round, @Field("totalNums") String totalNums,
                                              @Field("totalMoney") String totalMoney,@Field("number") String number,@FieldMap Map<String, String> fields,@Field("x-session-token") String x_session_token);


    /**
     * 六合彩下单
     * @param game_code
     * @param round
     * @param totalNums
     * @param totalMoney
     * @param number
     * @param x_session_token
     * @return  @QueryMap Map options,@FieldMap Map<String, String> fields,
     */
    @POST("billxq/bet")
    @FormUrlEncoded
    public Observable<CPBetResult> postCpBetsHK(@Field("game_code") String game_code, @Field("round") String round, @Field("totalNums") String totalNums,
                                                @Field("totalMoney") String totalMoney, @Field("number") String number,@Field("betmoney") String betmoney,
                                                @Field("typecode") String typecode,@Field("rtype") String rtype, @Field("x-session-token") String x_session_token);

    //键值对
    @POST("billxq/bet")
    @FormUrlEncoded
    public Observable<CPBetResult> postCpBetsHKMap(@Field("game_code") String game_code, @Field("round") String round, @Field("totalNums") String totalNums,
                                              @Field("totalMoney") String totalMoney,@Field("number") String number,@FieldMap Map<String, String> fields,@Field("x-session-token") String x_session_token);


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

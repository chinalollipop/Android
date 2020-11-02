package com.hgapp.betnhg.homepage.handicap.betapi.zhbetapi;

import com.hgapp.betnhg.common.http.request.AppTextMessageResponseList;
import com.hgapp.betnhg.data.BetZHResult;
import com.hgapp.betnhg.data.GameAllZHBetsBKResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IPrepareZHBetApi {

    /**
     * /order/order_prepare_pr_api.php?game=PMH,PMH&game_id=2605878,2605892  篮球综合过关选择玩法和赔率，准备投注接口
     * @param  game
     * @param  game_id
     */
    @POST("order/order_prepare_pr_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<GameAllZHBetsBKResult>> postGameAllZHBetsBK(@Field("appRefer") String appRefer, @Field("game") String game, @Field("game_id") String game_id);

    /**
     * /order/order_prepare_p3_api.php?game=PRH,PRH,PRH,POUC&game_id=3363442,3363572,3363582,3363562  足球综合过关选择玩法和赔率，准备投注接口
     * @param  game
     * @param  game_id
     */
    @POST("order/order_prepare_p3_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<GameAllZHBetsBKResult>> postGameAllZHBetsFT(@Field("appRefer") String appRefer, @Field("game") String game, @Field("game_id") String game_id);


    /**BK_order_p_finish_api.php 篮球综合过关下注接口
     * active   2 篮球今日赛事, 22 篮球早餐
     * teamcount
     * gold  金额
     * wagerDatas
     * randomNum 随机数
     */
    @POST("order/BK_order_p_finish_api.php")
    @FormUrlEncoded
    public Observable<BetZHResult> postZHBetBK(@Field("appRefer") String appRefer, @Field("active") String active, @Field("teamcount") String teamcount, @Field("gold") String gold, @Field("wagerDatas") String wagerDatas, @Field("randomNum") String randomNum);

    /**FT_order_p_finish_api.php 足球综合过关下注接口
     * active   1 篮球今日赛事, 11 篮球早餐
     * teamcount
     * gold  金额
     * wagerDatas
     * randomNum 随机数
     */
    @POST("order/FT_order_p_finish_api.php")
    @FormUrlEncoded
    public Observable<BetZHResult> postZHBetFT(@Field("appRefer") String appRefer,  @Field("active") String active,  @Field("teamcount") String teamcount,  @Field("gold") String gold, @Field("wagerDatas") String wagerDatas,  @Field("randomNum") String randomNum);

}

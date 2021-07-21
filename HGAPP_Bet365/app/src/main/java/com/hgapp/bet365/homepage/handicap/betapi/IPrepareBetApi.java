package com.hgapp.bet365.homepage.handicap.betapi;

import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.common.http.request.AppTextMessageResponseList;
import com.hgapp.bet365.data.BetResult;
import com.hgapp.bet365.data.GameAllPlayRBKResult;
import com.hgapp.bet365.data.GameAllPlayBKResult;
import com.hgapp.bet365.data.GameAllPlayFTResult;
import com.hgapp.bet365.data.GameAllPlayRFTResult;
import com.hgapp.bet365.data.PersonInformResult;
import com.hgapp.bet365.data.PrepareBetResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IPrepareBetApi {


    //所有篮球玩法接口 废弃  无用了
    @POST("get_game_allbets.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<GameAllPlayRBKResult>> postGameAllBets(@Field("appRefer") String appRefer, @Field("gid") String gid, @Field("gtype") String gtype, @Field("showtype") String showtype);


    //今日 早盘篮球玩法接口
    @POST("get_game_allbets.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<GameAllPlayBKResult>> postGameAllBetsBK(@Field("appRefer") String appRefer, @Field("gid") String gid, @Field("gtype") String gtype, @Field("showtype") String showtype);

    //滚球篮球玩法接口
    @POST("get_game_allbets.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<GameAllPlayRBKResult>> postGameAllBetsRBK(@Field("appRefer") String appRefer, @Field("gid") String gid, @Field("gtype") String gtype, @Field("showtype") String showtype);


    //今日 早盘足球玩法接口
    @POST("get_game_allbets.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<GameAllPlayFTResult>> postGameAllBetsFT(@Field("appRefer") String appRefer, @Field("gid") String gid, @Field("gtype") String gtype, @Field("showtype") String showtype);

    //滚球足球玩法接口
    @POST("get_game_allbets.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<GameAllPlayRFTResult>> postGameAllBetsRFT(@Field("appRefer") String appRefer, @Field("gid") String gid, @Field("gtype") String gtype, @Field("showtype") String showtype,@Field("isMaster") String isMaster);


    /**
     * 选择玩法和赔率，准备投注接口
     * order/order_prepare_api.php
     *
     * @param  order_method FT_rm 滚球独赢，FT_re 滚球让球，FT_rou 滚球大小，FT_rt 滚球单双，FT_hrm 滚球半场独赢，FT_hre 滚球半场让球，FT_hrou 滚球半场大小，FT_m 独赢，FT_r 让球，FT_ou 大小，FT_t 单双，FT_hm 半场独赢，FT_hr 半场让球，FT_hou 半场大小，BK_re 滚球让球，BK_rou 滚球大小，BK_m 独赢，BK_r 让球，BK_ou 大小，BK_t 单双，BK_ouhc 球队得分大小
     * @param  gid
     * @param  type  H 主队 C 客队  N 和
     * @param  wtype  M 独赢，R 让球，大小 OU，单双 EO，半场独赢 HM，半场让球 HR，半场大小 HOU
     * @param  rtype  ODD 单 EVEN 双
     * @param  odd_f_type  H
     * @param  error_flag
     * @param  order_type
     */
    //准备投注接口
    @POST("order/order_prepare_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PrepareBetResult>> postPrepareBet(
            @Field("appRefer") String appRefer, @Field("order_method") String order_method, @Field("gid") String gid,
            @Field("type") String type, @Field("wtype") String wtype, @Field("rtype") String rtype,
            @Field("odd_f_type") String odd_f_type, @Field("error_flag") String error_flag, @Field("order_type") String order_type,
            @Field("isMaster") String isMaster);

    //投注接口 此接口已废弃
    @POST("order/order_finish_api.php")
    @FormUrlEncoded
    public Observable<BetResult> postBet(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("autoOdd") String autoOdd, @Field("randomNum") String randomNum);

    //足球今日赛事和早盘下注接口 投注接口
    @POST("order/FT_order_finish_api.php")
    @FormUrlEncoded
    public Observable<BetResult> postBetFT(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("autoOdd") String autoOdd, @Field("randomNum") String randomNum);

    //足球滚球全场投注 投注接口
    @POST("order/FT_order_re_finish_api.php")
    @FormUrlEncoded
    public Observable<BetResult> postBetFTre(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("autoOdd") String autoOdd, @Field("randomNum") String randomNum);

    //足球滚球半场投注 投注接口
    @POST("order/FT_order_hre_finish_api.php")
    @FormUrlEncoded
    public Observable<BetResult> postBetFThre(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("autoOdd") String autoOdd, @Field("randomNum") String randomNum);


    //篮球今日赛事与早盘投注 投注接口
    @POST("order/BK_order_finish_api.php")
    @FormUrlEncoded
    public Observable<BetResult> postBetBK(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("autoOdd") String autoOdd, @Field("randomNum") String randomNum);


    //篮球滚球投注 投注接口
    @POST("order/BK_order_re_finish_api.php")
    @FormUrlEncoded
    public Observable<BetResult> postBetBKre(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("autoOdd") String autoOdd, @Field("randomNum") String randomNum);


    //冠军投注（篮球与足球公用） 投注接口
    @POST("order/FT_order_nfs_finish_api.php ")
    @FormUrlEncoded
    public Observable<BetResult> postBetChampionFT(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("autoOdd") String autoOdd, @Field("randomNum") String randomNum);


    @POST("account_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<PersonInformResult>> postPersonInform(@Field("appRefer") String appRefer);

}

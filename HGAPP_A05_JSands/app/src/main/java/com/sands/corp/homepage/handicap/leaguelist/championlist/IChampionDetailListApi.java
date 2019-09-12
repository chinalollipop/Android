package com.sands.corp.homepage.handicap.leaguelist.championlist;

import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.common.http.request.AppTextMessageResponseList;
import com.sands.corp.data.BetResult;
import com.sands.corp.data.ChampionDetailListResult;
import com.sands.corp.data.PrepareBetResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IChampionDetailListApi {

    //冠军联赛数据接口
    @POST("loadgame_R_api.php")
    @FormUrlEncoded
    public Observable<ChampionDetailListResult> postLeagueSearchChampionList(@Field("appRefer") String appRefer, @Field("showtype")String showtype, @Field("FStype")String FStype, @Field("mtype")String mtype, @Field("M_League")String M_League);

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
            @Field("odd_f_type") String odd_f_type, @Field("error_flag") String error_flag, @Field("order_type") String order_type);



    //投注接口
    @POST("order/order_finish_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetResult>> postBet(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("randomNum") String randomNum);


}

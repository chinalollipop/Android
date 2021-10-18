package com.hgapp.a6668.homepage.handicap.leaguedetail;

import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.data.BetResult;
import com.hgapp.a6668.data.ComPassSearchListResult;
import com.hgapp.a6668.data.LeagueDetailListDataResults;
import com.hgapp.a6668.data.LeagueDetailSearchListResult;
import com.hgapp.a6668.data.PrepareBetResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ILeagueDetailSearchListApi {

    /**
     * /var_by_league_api.php  联赛下面的盘口列表（让球、大小）
     *
     * @param  type   FT 足球，FU 足球早盘，BK 篮球，BU 篮球早盘
     * @param  more   s 今日赛事， r 滚球
     * @param  gid  3321118,3321062
     */
    //体育联赛下的具体球队数据接口
    @POST("var_by_league_api.php")
    @FormUrlEncoded
    public Observable<LeagueDetailSearchListResult> postLeagueDetailSearchList(@Field("appRefer") String appRefer, @Field("type") String type, @Field("more") String more, @Field("gid") String gid);



    /**
     * /var_lid_p3_api.php  体育联赛数据接口_综合过关
     * @param  gtype   FT 足球，BK 篮球
     * @param  sorttype   league 联盟排序  time 时间排序
     * @param  mdate  日期
     * @param  showtype
     * @param  M_League  欧洲冠军杯（显示此联赛全部冠军盘口，以及赔率）
     */
    @POST("var_lid_p3_api.php")
    @FormUrlEncoded
    public Observable<ComPassSearchListResult> postComPassSearchList(@Field("appRefer") String appRefer, @Field("gtype")String gtype, @Field("sorttype")String sorttype, @Field("mdate")String mdate, @Field("showtype")String showtype, @Field("M_League")String M_League);


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



    //投注接口
    @POST("order/order_finish_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<BetResult>> postBet(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype, @Field("randomNum") String randomNum);



    //滚球足球玩法接口
    @POST("get_game_allbets.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LeagueDetailListDataResults.DataBean>> postGameAllBets(@Field("appRefer") String appRefer, @Field("gid") String gid, @Field("gtype") String gtype, @Field("showtype") String showtype, @Field("isMaster") String isMaster);


    //滚球足球玩法接口
    @POST("get_game_allbets.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<LeagueDetailListDataResults.DataBean>> postGameAllBetsZH(@Field("appRefer") String appRefer, @Field("gid") String gid, @Field("gtype") String gtype, @Field("showtype") String showtype, @Field("isMaster") String isMaster, @Field("isP3") String isP3);


}

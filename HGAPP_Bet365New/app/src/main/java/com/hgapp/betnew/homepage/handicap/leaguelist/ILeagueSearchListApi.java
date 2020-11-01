package com.hgapp.betnew.homepage.handicap.leaguelist;

import com.hgapp.betnew.common.http.request.AppTextMessageResponseList;
import com.hgapp.betnew.data.LeagueDetailSearchListResult;
import com.hgapp.betnew.data.LeagueSearchListResult;
import com.hgapp.betnew.data.LeagueSearchTimeResult;
import com.hgapp.betnew.data.MaintainResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ILeagueSearchListApi {

    //让球/大小 体育联赛数据接口  String appRefer, String gtype,String showtype, String sorttype,String date
    @POST("var_lid_api.php")
    @FormUrlEncoded
    public Observable<LeagueSearchListResult> postLeagueSearchList(@Field("appRefer") String appRefer, @Field("gtype")String gtype, @Field("showtype")String showtype, @Field("sorttype")String sorttype,@Field("mdate")String mdate);

    //综合过关 体育联赛数据接口  String appRefer, String gtype,String showtype, String sorttype,String date
    @POST("var_lid_p3_api.php")
    @FormUrlEncoded
    public Observable<LeagueSearchListResult> postLeaguePassSearchList(@Field("appRefer") String appRefer, @Field("gtype")String gtype, @Field("showtype")String showtype, @Field("sorttype")String sorttype,@Field("mdate")String mdate);

    //冠军联赛数据接口
    @POST("loadgame_R_api.php")
    @FormUrlEncoded
    public Observable<LeagueSearchListResult> postLeagueSearchChampionList(@Field("appRefer") String appRefer, @Field("showtype")String showtype, @Field("FStype")String FStype, @Field("mtype")String mtype);


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
    public Observable<LeagueDetailSearchListResult> postLeagueDetailSearchList(@Field("appRefer") String appRefer, @Field("type")String type, @Field("more")String more, @Field("gid")String gid);

    //体育联赛时间数据接口
    @POST("date_of_next_15_days_api.php")
    @FormUrlEncoded
    public Observable<LeagueSearchTimeResult> postLeagueSearchTime(@Field("appRefer") String appRefer);


    //维护日志信息
    @POST("maintenance_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<MaintainResult>> postMaintain(@Field("appRefer") String appRefer);



}

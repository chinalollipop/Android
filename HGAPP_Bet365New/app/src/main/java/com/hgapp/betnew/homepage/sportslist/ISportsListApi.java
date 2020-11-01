package com.hgapp.betnew.homepage.sportslist;

import com.hgapp.betnew.common.http.request.AppTextMessageResponse;
import com.hgapp.betnew.data.SportsListResult;
import com.hgapp.betnew.data.SportsPlayMethodRBResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface ISportsListApi {


    //让球/大小 体育联赛数据接口  String appRefer, String gtype,String showtype, String sorttype,String date
    @POST("var_lid_api.php")
    @FormUrlEncoded
    public Observable<SportsListResult> postLeagueSearchList(@Field("appRefer") String appRefer, @Field("gtype")String gtype, @Field("showtype")String showtype, @Field("sorttype")String sorttype, @Field("mdate")String mdate);

    //盘口列表
    @POST("var_api.php")
    @FormUrlEncoded
    public Observable<SportsListResult> postSprotsList(@Field("appRefer") String appRefer, @Field("type") String type, @Field("more") String more);

    //盘口列表
    @POST("var_api.php")
    @FormUrlEncoded
    public Observable<SportsListResult> postFullPayGameList(@Field("appRefer") String appRefer, @Field("type") String type, @Field("more") String more);

    //玩法列表
    @POST("match_api.php")
    @FormUrlEncoded
    public Observable<SportsPlayMethodRBResult> postSprotsPlayMethod(@Field("appRefer") String appRefer, @Field("type") String type, @Field("more") String more, @Field("gid") String gid);

    //投注接口
    @POST("order/order_finish_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<SportsPlayMethodRBResult>> postBet(@Field("appRefer") String appRefer, @Field("cate") String cate, @Field("gid") String gid, @Field("type") String type, @Field("active") String active, @Field("line_type") String line_type
            , @Field("odd_f_type") String odd_f_type, @Field("gold") String gold, @Field("ioradio_r_h") String ioradio_r_h, @Field("rtype") String rtype, @Field("wtype") String wtype);



}

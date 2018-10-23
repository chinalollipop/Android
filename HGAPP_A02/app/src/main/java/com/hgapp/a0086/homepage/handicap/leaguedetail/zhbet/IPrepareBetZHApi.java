package com.hgapp.a0086.homepage.handicap.leaguedetail.zhbet;

import com.hgapp.a0086.common.http.request.AppTextMessageResponseList;
import com.hgapp.a0086.data.GameAllPlayZHResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IPrepareBetZHApi {
    /**
     * /var_lid_p3_api.php  体育联赛数据接口_综合过关
     * @param  gtype   FT 足球，BK 篮球
     * @param  sorttype   league 联盟排序  time 时间排序
     * @param  mdate  日期 2018-09-15
     * @param  showtype
     * @param  M_League  欧洲冠军杯（显示此联赛全部冠军盘口，以及赔率）
     * @param  gid
     */
    @POST("var_lid_p3_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<GameAllPlayZHResult>> postBetZH(@Field("appRefer") String appRefer, @Field("gtype") String gtype, @Field("sorttype") String sorttype, @Field("mdate") String mdate,
                                                                                 @Field("showtype") String showtype, @Field("M_League") String M_League, @Field("gid") String gid);
}

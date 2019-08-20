package com.sunapp.bloc.homepage;

import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.common.http.request.AppTextMessageResponseList;
import com.sunapp.bloc.data.AGCheckAcountResult;
import com.sunapp.bloc.data.AGGameLoginResult;
import com.sunapp.bloc.data.BannerResult;
import com.sunapp.bloc.data.CPResult;
import com.sunapp.bloc.data.CheckAgLiveResult;
import com.sunapp.bloc.data.MaintainResult;
import com.sunapp.bloc.data.NoticeResult;
import com.sunapp.bloc.data.OnlineServiceResult;
import com.sunapp.bloc.data.QipaiResult;
import com.sunapp.bloc.data.ValidResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IHomePageApi {

    //在线客服
    @POST("kefu_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<OnlineServiceResult>> postOnlineService(@Field("appRefer") String appRefer);

    @POST("banner.php")
    @FormUrlEncoded
    public Observable<BannerResult> postBanner(@Field("appRefer") String appRefer);

    @POST("notice.php")
    @FormUrlEncoded
    public Observable<NoticeResult> postNotice(@Field("appRefer") String appRefer,@Field("carousel") String carousel);

    //AG视讯检查账号是否注册
    @POST("live.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<CheckAgLiveResult>> postAGLiveCheckRegister(@Field("appRefer") String appRefer);


    //创建AG账号
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AGCheckAcountResult> postAGGameRegisterAccount(@Field("appRefer") String appRefer, @Field("action") String action);

    //棋牌游戏
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //棋牌游戏
    @POST("lyqp/ly_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postLYQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //进入泛亚电竞游戏
    @POST("avia/avia_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postAviaQiPai(@Field("appRefer") String appRefer, @Field("action") String action);


    //皇冠棋牌游戏
    @POST("hgqp/hg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postHGQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //VG棋牌游戏
    @POST("vgqp/vg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postVGQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //彩票联合登录接口
    @POST("index_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<CPResult>> postCP(@Field("appRefer") String appRefer);

    //昨日有效金额
    @POST("lucky_red_envelope_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ValidResult>> postValidGift(@Field("appRefer") String appRefer, @Field("action") String action);

    //维护日志信息
    @POST("maintenance_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<MaintainResult>> postMaintain(@Field("appRefer") String appRefer);

    //捕鱼游戏
    @POST("zrsx_login.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<AGGameLoginResult>> postBYGame(@Field("appRefer") String appRefer, @Field("gameid") String gameid);



}
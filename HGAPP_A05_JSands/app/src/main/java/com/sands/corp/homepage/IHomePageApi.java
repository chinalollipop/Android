package com.sands.corp.homepage;

import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.common.http.request.AppTextMessageResponseList;
import com.sands.corp.data.AGCheckAcountResult;
import com.sands.corp.data.AGGameLoginResult;
import com.sands.corp.data.BannerResult;
import com.sands.corp.data.CPResult;
import com.sands.corp.data.CheckAgLiveResult;
import com.sands.corp.data.MaintainResult;
import com.sands.corp.data.NoticeResult;
import com.sands.corp.data.OnlineServiceResult;
import com.sands.corp.data.QipaiResult;
import com.sands.corp.data.ValidResult;

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


    //OG视讯
    @POST("og/og_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<AGGameLoginResult>> postOGGame(@Field("appRefer") String appRefer, @Field("action") String action);


    //皇冠棋牌游戏
    @POST("hgqp/hg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postHGQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //VG棋牌游戏
    @POST("vgqp/vg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postVGQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //彩票联合登录接口
    @POST("api/thirdLotteryApi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<CPResult>> postCP(@Field("appRefer") String appRefer,@Field("actype") String actype);

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

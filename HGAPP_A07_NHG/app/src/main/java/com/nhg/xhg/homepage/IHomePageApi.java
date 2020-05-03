package com.nhg.xhg.homepage;

import com.nhg.xhg.common.http.request.AppTextMessageResponse;
import com.nhg.xhg.common.http.request.AppTextMessageResponseList;
import com.nhg.xhg.data.AGCheckAcountResult;
import com.nhg.xhg.data.AGGameLoginResult;
import com.nhg.xhg.data.BannerResult;
import com.nhg.xhg.data.CPResult;
import com.nhg.xhg.data.CheckAgLiveResult;
import com.nhg.xhg.data.MaintainResult;
import com.nhg.xhg.data.NoticeResult;
import com.nhg.xhg.data.OnlineServiceResult;
import com.nhg.xhg.data.QipaiResult;
import com.nhg.xhg.data.Sportcenter;
import com.nhg.xhg.data.ValidResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IHomePageApi {

    //在线客服
    @POST("kefu_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<OnlineServiceResult>> postOnlineService(@Field("appRefer") String appRefer);

    @POST("api/indexBannerApi.php")
    @FormUrlEncoded
    public Observable<BannerResult> postBanner(@Field("appRefer") String appRefer, @Field("action") String action);

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

    //体育中心
    @POST("sportcenter/sport_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Sportcenter>> postSportcenter(@Field("appRefer") String appRefer, @Field("action") String action);

    //棋牌游戏
    @POST("lyqp/ly_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postLYQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //进入泛亚电竞游戏
    @POST("avia/avia_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postAviaQiPai(@Field("appRefer") String appRefer, @Field("action") String action);


    //雷火电竞
    @POST("thunfire/fire_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postThunFireGame(@Field("appRefer") String appRefer, @Field("action") String action);

    //OG视讯
    @POST("og/og_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<AGGameLoginResult>> postOGGame(@Field("appRefer") String appRefer, @Field("action") String action);

    //BBIN视讯
    @POST("bbin/bbin_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<AGGameLoginResult>> postBBINGame(@Field("appRefer") String appRefer, @Field("action") String action);

    //皇冠棋牌游戏
    @POST("klqp/kl_api.php")
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

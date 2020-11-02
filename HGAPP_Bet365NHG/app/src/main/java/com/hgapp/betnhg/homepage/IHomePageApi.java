package com.hgapp.betnhg.homepage;

import com.hgapp.betnhg.common.http.request.AppTextMessageResponse;
import com.hgapp.betnhg.common.http.request.AppTextMessageResponseList;
import com.hgapp.betnhg.data.AGGameLoginResult;
import com.hgapp.betnhg.data.BannerResult;
import com.hgapp.betnhg.data.CPResult;
import com.hgapp.betnhg.data.GameNumResult;
import com.hgapp.betnhg.data.MaintainResult;
import com.hgapp.betnhg.data.NoticeResult;
import com.hgapp.betnhg.data.QipaiResult;
import com.hgapp.betnhg.data.ValidResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;
import rx.Observable;

public interface IHomePageApi {

    @POST("api/indexGameNumApi.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<GameNumResult>> postGameNum(@Field("appRefer") String appRefer);


    @POST("api/indexBannerApi.php")
    @FormUrlEncoded
    public Observable<BannerResult> postBanner(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("notice.php")
    @FormUrlEncoded
    public Observable<NoticeResult> postNotice(@Field("appRefer") String appRefer,@Field("carousel") String carousel);

    //AG捕鱼
    @POST("zrsx_login.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<AGGameLoginResult>> postLoginGame(@Field("appRefer") String appRefer, @Field("gameid") String gameid);

    //OG视讯
    @POST("og/og_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<AGGameLoginResult>> postOGGame(@Field("appRefer") String appRefer, @Field("action") String action);

    //BBIN视讯
    @POST("bbin/bbin_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<AGGameLoginResult>> postBBINGame(@Field("appRefer") String appRefer, @Field("action") String action);

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

    //雷火电竞
    @POST("thunfire/fire_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postThunFireGame(@Field("appRefer") String appRefer, @Field("action") String action);



    //皇冠棋牌游戏
    @POST("klqp/kl_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postHGQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //VG棋牌游戏
    @POST("vgqp/vg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postVGQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //彩票联合登录接口
    @POST("index_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<CPResult>> postCP(@Field("appRefer") String appRefer,@Field("actype") String actype);

    //昨日有效金额
    @POST("lucky_red_envelope_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<ValidResult>> postValidGift(@Field("appRefer") String appRefer, @Field("action") String action);

    //维护日志信息
    @POST("maintenance_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<MaintainResult>> postMaintain(@Field("appRefer") String appRefer);



}

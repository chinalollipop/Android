package com.hgapp.betnew.personpage;

import com.hgapp.betnew.common.http.request.AppTextMessageResponse;
import com.hgapp.betnew.common.http.request.AppTextMessageResponseList;
import com.hgapp.betnew.data.CPResult;
import com.hgapp.betnew.data.NoticeResult;
import com.hgapp.betnew.data.PersonBalanceResult;
import com.hgapp.betnew.data.PersonInformResult;
import com.hgapp.betnew.data.QipaiResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.Url;
import rx.Observable;

public interface IPersonApi {
    //我的账户
    @POST("account_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<PersonInformResult>> postPersonInform(@Field("appRefer") String appRefer);

    //获取余额
    @POST("ag_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<PersonBalanceResult>> postPersonBalance(@Field("appRefer") String appRefer, @Field("action") String action);

    @POST("notice.php")
    @FormUrlEncoded
    public Observable<NoticeResult> postNotice(@Field("appRefer") String appRefer, @Field("carousel") String carousel);



    //棋牌游戏
    @POST("ky/ky_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postQiPai(@Field("appRefer") String appRefer, @Field("action") String action);

    //皇冠棋牌游戏
    @POST("hgqp/hg_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<QipaiResult>> postHgQiPai(@Field("appRefer") String appRefer, @Field("action") String action);


    //会员注销 安全退出
    @POST("logout.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postLogOut(@Field("appRefer") String appRefer);


    //彩票联合登录接口
    @POST("index_api.php")
    @FormUrlEncoded
    public Observable<AppTextMessageResponseList<CPResult>> postCP(@Field("appRefer") String appRefer,@Field("actype") String actype);

    //彩票联合推出
    @Headers({"Domain-Name: CpUrl"})
    @GET()
    public Observable<AppTextMessageResponse<Object>> getLogOutCP(@Url String path);

}

package com.hgapp.a6668.demo;

import com.hgapp.a6668.data.CPInitResult;
import com.hgapp.a6668.data.CPNoteResult;

import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.Url;
import rx.Observable;

public interface ICPDemoApi {

    //进入彩票联合登录接口
    /*@Headers({"Domain-Name: CpUrl"})
    @GET("{path}")
    public Observable<AppTextMessageResponse<Object>> postCPLogin(@Path(value = "path",encoded = true) String path);
*/
    @Headers({"Domain-Name: CpUrl"})
    @GET
    public Observable<Object> postCPLogin(@Url String path);
   /* @Headers({"Domain-Name: CpUrl"})//https://api.hces888.com/
    @POST("api/game/races")
    @FormUrlEncoded
    public Observable<AppTextMessageResponse<Object>> postCPLogin(@Field("gameID") String gameID,@Field("raceID") String raceID,@Field("raceType") String raceType,
                                                                  @Field("round") String round,@Field("raceStatus") String raceStatus,@Field("token") String token,@Field("lang") String lang);
*/

    @Headers({"Domain-Name: CpUrl"})
    @POST
    @FormUrlEncoded
    public Observable<CPInitResult> postCPInit(@Url String path, @Field("x-session-token") String x_session_token);

    @GET
    public Observable<CPNoteResult> postCPNote(@Url String url);


}

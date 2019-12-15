package com.hgapp.a6668.homepage.cplist.bet.betrecords;

import com.hgapp.a6668.data.BetRecordsResult;
import com.hgapp.a6668.data.CPBetResult;
import com.hgapp.a6668.data.CPHallResult;

import java.util.Map;

import retrofit2.http.Field;
import retrofit2.http.FieldMap;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Url;
import rx.Observable;

public interface ICpBetRecordsApi {

    @GET
    Observable<BetRecordsResult> getCpBetRecords(@Url String path);

    @POST
    Observable<BetRecordsResult> postCpBetRecords(@Url String path);

}

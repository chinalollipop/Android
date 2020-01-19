package com.hgapp.m8.homepage.cplist.bet.betrecords;

import com.hgapp.m8.data.BetRecordsResult;

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
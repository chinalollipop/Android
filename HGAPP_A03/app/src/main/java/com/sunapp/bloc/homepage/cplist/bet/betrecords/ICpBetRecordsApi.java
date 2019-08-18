package com.sunapp.bloc.homepage.cplist.bet.betrecords;

import com.sunapp.bloc.data.BetRecordsResult;

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

package com.nhg.xhg.homepage.cplist.bet.betrecords.betlistrecords;

import com.nhg.xhg.data.BetRecordsListItemResult;
import com.nhg.xhg.data.BetRecordsResult;

import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Url;
import rx.Observable;

public interface ICpBetListRecordsApi {

    @GET
    Observable<BetRecordsListItemResult> getCpBetRecords(@Url String path);

    @POST
    Observable<BetRecordsResult> postCpBetRecords(@Url String path);

}

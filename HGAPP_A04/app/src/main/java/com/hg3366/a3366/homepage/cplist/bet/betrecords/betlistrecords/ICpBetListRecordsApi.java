package com.hg3366.a3366.homepage.cplist.bet.betrecords.betlistrecords;

import com.hg3366.a3366.data.BetRecordsListItemResult;
import com.hg3366.a3366.data.BetRecordsResult;

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

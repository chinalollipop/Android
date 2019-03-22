package com.cfcp.a01.ui.home.cplist.bet.betrecords.betlistrecords;


import com.cfcp.a01.data.BetRecordsListItemResult;
import com.cfcp.a01.data.BetRecordsResult;

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

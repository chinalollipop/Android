package com.hgapp.a6668.homepage.cplist.bet.betrecords.betnow;

import com.hgapp.a6668.data.BetRecordsListItemResult;
import com.hgapp.a6668.data.BetRecordsResult;
import com.hgapp.a6668.data.CPBetNowResult;

import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Url;
import rx.Observable;

public interface ICpBetNowApi {

    @GET
    Observable<CPBetNowResult> getCpBetRecords(@Url String path);

    @POST
    Observable<CPBetNowResult> postCpBetRecords(@Url String path);

}

package com.cfcp.a01.ui.home.cplist.bet.betrecords.betnow;

import com.cfcp.a01.data.CPBetNowResult;

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

package com.hgapp.betnhg.homepage.cplist.lottery;

import com.hgapp.betnhg.data.CPLotteryListResult;

import retrofit2.http.GET;
import retrofit2.http.Headers;
import retrofit2.http.Url;
import rx.Observable;

public interface ICPLotteryListApi {



    @Headers({"Domain-Name: CpUrl"})
    @GET
    Observable<CPLotteryListResult> get(@Url String path);

}

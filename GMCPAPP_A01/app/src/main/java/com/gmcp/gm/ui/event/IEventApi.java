package com.gmcp.gm.ui.event;


import com.gmcp.gm.data.CouponResult;

import java.util.Map;

import retrofit2.http.GET;
import retrofit2.http.QueryMap;
import rx.Observable;

/**
 * Created by Daniel on 2019/2/22.
 */

public interface IEventApi {

    //优惠活动列表
    @GET("service")
    Observable<CouponResult> getCoupon(
            @QueryMap Map<String, String> params
    );
}

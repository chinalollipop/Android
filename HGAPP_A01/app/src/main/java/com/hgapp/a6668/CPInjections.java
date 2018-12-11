package com.hgapp.a6668;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.hgapp.a6668.common.http.cphttp.CPClient;
import com.hgapp.a6668.homepage.cplist.CPListContract;
import com.hgapp.a6668.homepage.cplist.CPListPresenter;
import com.hgapp.a6668.homepage.cplist.ICPListApi;
import com.hgapp.a6668.homepage.cplist.bet.CpBetApiContract;
import com.hgapp.a6668.homepage.cplist.bet.CpBetApiPresenter;
import com.hgapp.a6668.homepage.cplist.bet.ICpBetApi;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.CpBetRecordsContract;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.CpBetRecordsPresenter;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.ICpBetRecordsApi;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.betlistrecords.CpBetListRecordsContract;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.betlistrecords.CpBetListRecordsPresenter;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.betlistrecords.ICpBetListRecordsApi;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.betnow.CpBetNowContract;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.betnow.CpBetNowPresenter;
import com.hgapp.a6668.homepage.cplist.bet.betrecords.betnow.ICpBetNowApi;
import com.hgapp.a6668.homepage.cplist.hall.CPHallListContract;
import com.hgapp.a6668.homepage.cplist.hall.CPHallListPresenter;
import com.hgapp.a6668.homepage.cplist.hall.ICPHallListApi;
import com.hgapp.a6668.homepage.cplist.order.CPOrderContract;
import com.hgapp.a6668.homepage.cplist.order.CPOrderPresenter;
import com.hgapp.a6668.homepage.cplist.order.ICPOrderApi;

public class CPInjections {
    private CPInjections(){}

    //彩票的接口
    //----------------------------------------------------------------------------------------------------------------------------------
    public static CPHallListContract.Presenter inject(@NonNull CPHallListContract.View view, @Nullable ICPHallListApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICPHallListApi.class);
        }
        return new CPHallListPresenter(api,view);
    }

    public static CPListContract.Presenter inject(@NonNull CPListContract.View view, @Nullable ICPListApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICPListApi.class);
        }
        return new CPListPresenter(api,view);
    }

    public static CPOrderContract.Presenter inject(@Nullable ICPOrderApi api, @NonNull CPOrderContract.View view)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICPOrderApi.class);
        }

        return new CPOrderPresenter(api,view);
    }

    public static CpBetApiContract.Presenter inject(@Nullable ICpBetApi api, @NonNull CpBetApiContract.View view)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICpBetApi.class);
        }

        return new CpBetApiPresenter(api,view);
    }

    public static CpBetRecordsContract.Presenter inject(@NonNull CpBetRecordsContract.View view, @Nullable ICpBetRecordsApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICpBetRecordsApi.class);
        }
        return new CpBetRecordsPresenter(api,view);
    }

    public static CpBetListRecordsContract.Presenter inject(@NonNull CpBetListRecordsContract.View view, @Nullable ICpBetListRecordsApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICpBetListRecordsApi.class);
        }
        return new CpBetListRecordsPresenter(api,view);
    }

    public static CpBetNowContract.Presenter inject(@NonNull CpBetNowContract.View view, @Nullable ICpBetNowApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICpBetNowApi.class);
        }
        return new CpBetNowPresenter(api,view);
    }

}

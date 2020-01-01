package com.nhg.xhg;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.nhg.xhg.common.http.cphttp.CPClient;
import com.nhg.xhg.homepage.cplist.CPListContract;
import com.nhg.xhg.homepage.cplist.CPListPresenter;
import com.nhg.xhg.homepage.cplist.ICPListApi;
import com.nhg.xhg.homepage.cplist.bet.CpBetApiContract;
import com.nhg.xhg.homepage.cplist.bet.CpBetApiPresenter;
import com.nhg.xhg.homepage.cplist.bet.ICpBetApi;
import com.nhg.xhg.homepage.cplist.bet.betrecords.CpBetRecordsContract;
import com.nhg.xhg.homepage.cplist.bet.betrecords.CpBetRecordsPresenter;
import com.nhg.xhg.homepage.cplist.bet.betrecords.ICpBetRecordsApi;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betlistrecords.CpBetListRecordsContract;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betlistrecords.CpBetListRecordsPresenter;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betlistrecords.ICpBetListRecordsApi;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betnow.CpBetNowContract;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betnow.CpBetNowPresenter;
import com.nhg.xhg.homepage.cplist.bet.betrecords.betnow.ICpBetNowApi;
import com.nhg.xhg.homepage.cplist.hall.CPHallListContract;
import com.nhg.xhg.homepage.cplist.hall.CPHallListPresenter;
import com.nhg.xhg.homepage.cplist.hall.ICPHallListApi;
import com.nhg.xhg.homepage.cplist.quickbet.IQuickBetApi;
import com.nhg.xhg.homepage.cplist.quickbet.QuickBetContract;
import com.nhg.xhg.homepage.cplist.quickbet.QuickBetPresenter;
import com.nhg.xhg.homepage.cplist.lottery.CPLotteryListContract;
import com.nhg.xhg.homepage.cplist.lottery.CPLotteryListPresenter;
import com.nhg.xhg.homepage.cplist.lottery.ICPLotteryListApi;
import com.nhg.xhg.homepage.cplist.order.CPOrderContract;
import com.nhg.xhg.homepage.cplist.order.CPOrderPresenter;
import com.nhg.xhg.homepage.cplist.order.ICPOrderApi;
import com.nhg.xhg.homepage.cplist.quickbet.mothed.IQuickBetMethodApi;
import com.nhg.xhg.homepage.cplist.quickbet.mothed.QuickBetMethodContract;
import com.nhg.xhg.homepage.cplist.quickbet.mothed.QuickBetMethodPresenter;

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


    public static CPLotteryListContract.Presenter inject(@NonNull CPLotteryListContract.View view, @Nullable ICPLotteryListApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(ICPLotteryListApi.class);
        }
        return new CPLotteryListPresenter(api,view);
    }

    public static QuickBetContract.Presenter inject(@NonNull QuickBetContract.View view, @Nullable IQuickBetApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(IQuickBetApi.class);
        }
        return new QuickBetPresenter(api,view);
    }

    public static QuickBetMethodContract.Presenter inject(@NonNull QuickBetMethodContract.View view, @Nullable IQuickBetMethodApi api)
    {
        if(null == api)
        {
            api = CPClient.getRetrofit().create(IQuickBetMethodApi.class);
        }
        return new QuickBetMethodPresenter(api,view);
    }
}

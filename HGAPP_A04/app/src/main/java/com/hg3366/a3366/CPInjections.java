package com.hg3366.a3366;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.hg3366.a3366.common.http.cphttp.CPClient;
import com.hg3366.a3366.homepage.cplist.CPListContract;
import com.hg3366.a3366.homepage.cplist.CPListPresenter;
import com.hg3366.a3366.homepage.cplist.ICPListApi;
import com.hg3366.a3366.homepage.cplist.bet.CpBetApiContract;
import com.hg3366.a3366.homepage.cplist.bet.CpBetApiPresenter;
import com.hg3366.a3366.homepage.cplist.bet.ICpBetApi;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.CpBetRecordsContract;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.CpBetRecordsPresenter;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.ICpBetRecordsApi;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.betlistrecords.CpBetListRecordsContract;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.betlistrecords.CpBetListRecordsPresenter;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.betlistrecords.ICpBetListRecordsApi;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.betnow.CpBetNowContract;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.betnow.CpBetNowPresenter;
import com.hg3366.a3366.homepage.cplist.bet.betrecords.betnow.ICpBetNowApi;
import com.hg3366.a3366.homepage.cplist.hall.CPHallListContract;
import com.hg3366.a3366.homepage.cplist.hall.CPHallListPresenter;
import com.hg3366.a3366.homepage.cplist.hall.ICPHallListApi;
import com.hg3366.a3366.homepage.cplist.quickbet.IQuickBetApi;
import com.hg3366.a3366.homepage.cplist.quickbet.QuickBetContract;
import com.hg3366.a3366.homepage.cplist.quickbet.QuickBetPresenter;
import com.hg3366.a3366.homepage.cplist.lottery.CPLotteryListContract;
import com.hg3366.a3366.homepage.cplist.lottery.CPLotteryListPresenter;
import com.hg3366.a3366.homepage.cplist.lottery.ICPLotteryListApi;
import com.hg3366.a3366.homepage.cplist.order.CPOrderContract;
import com.hg3366.a3366.homepage.cplist.order.CPOrderPresenter;
import com.hg3366.a3366.homepage.cplist.order.ICPOrderApi;
import com.hg3366.a3366.homepage.cplist.quickbet.mothed.IQuickBetMethodApi;
import com.hg3366.a3366.homepage.cplist.quickbet.mothed.QuickBetMethodContract;
import com.hg3366.a3366.homepage.cplist.quickbet.mothed.QuickBetMethodPresenter;

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

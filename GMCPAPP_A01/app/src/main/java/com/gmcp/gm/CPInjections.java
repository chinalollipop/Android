package com.gmcp.gm;

import android.support.annotation.NonNull;
import android.support.annotation.Nullable;

import com.gmcp.gm.common.http.Client;
import com.gmcp.gm.ui.home.cplist.bet.CpBetApiContract;
import com.gmcp.gm.ui.home.cplist.bet.CpBetApiPresenter;
import com.gmcp.gm.ui.home.cplist.bet.ICpBetApi;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.CpBetRecordsContract;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.CpBetRecordsPresenter;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.ICpBetRecordsApi;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.betlistrecords.CpBetListRecordsContract;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.betlistrecords.CpBetListRecordsPresenter;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.betlistrecords.ICpBetListRecordsApi;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.betnow.CpBetNowContract;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.betnow.CpBetNowPresenter;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.betnow.ICpBetNowApi;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.chonglong.CpChangLongContract;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.chonglong.CpChangLongPresenter;
import com.gmcp.gm.ui.home.cplist.bet.betrecords.chonglong.ICpChangLongApi;
import com.gmcp.gm.ui.home.cplist.lottery.CPLotteryListContract;
import com.gmcp.gm.ui.home.cplist.lottery.CPLotteryListPresenter;
import com.gmcp.gm.ui.home.cplist.lottery.ICPLotteryListApi;
import com.gmcp.gm.ui.home.cplist.order.CPOrderContract;
import com.gmcp.gm.ui.home.cplist.order.CPOrderPresenter;
import com.gmcp.gm.ui.home.cplist.order.ICPOrderApi;
import com.gmcp.gm.ui.home.cplist.quickbet.IQuickBetApi;
import com.gmcp.gm.ui.home.cplist.quickbet.QuickBetContract;
import com.gmcp.gm.ui.home.cplist.quickbet.QuickBetPresenter;
import com.gmcp.gm.ui.home.cplist.quickbet.mothed.IQuickBetMethodApi;
import com.gmcp.gm.ui.home.cplist.quickbet.mothed.QuickBetMethodContract;
import com.gmcp.gm.ui.home.cplist.quickbet.mothed.QuickBetMethodPresenter;

public class CPInjections {
    private CPInjections() {
    }

    //彩票的接口
    //----------------------------------------------------------------------------------------------------------------------------------

    public static CPOrderContract.Presenter inject(@Nullable ICPOrderApi api, @NonNull CPOrderContract.View view) {
        if (null == api) {
            api = Client.getRetrofit().create(ICPOrderApi.class);
        }

        return new CPOrderPresenter(api, view);
    }

    public static CpBetApiContract.Presenter inject(@Nullable ICpBetApi api, @NonNull CpBetApiContract.View view) {
        if (null == api) {
            api = Client.getRetrofit().create(ICpBetApi.class);
        }

        return new CpBetApiPresenter(api, view);
    }

    public static CpBetRecordsContract.Presenter inject(@NonNull CpBetRecordsContract.View view, @Nullable ICpBetRecordsApi api) {
        if (null == api) {
            api = Client.getRetrofit().create(ICpBetRecordsApi.class);
        }
        return new CpBetRecordsPresenter(api, view);
    }

    public static CpBetListRecordsContract.Presenter inject(@NonNull CpBetListRecordsContract.View view, @Nullable ICpBetListRecordsApi api) {
        if (null == api) {
            api = Client.getRetrofit().create(ICpBetListRecordsApi.class);
        }
        return new CpBetListRecordsPresenter(api, view);
    }

    public static CpBetNowContract.Presenter inject(@NonNull CpBetNowContract.View view, @Nullable ICpBetNowApi api) {
        if (null == api) {
            api = Client.getRetrofit().create(ICpBetNowApi.class);
        }
        return new CpBetNowPresenter(api, view);
    }


    public static CPLotteryListContract.Presenter inject(@NonNull CPLotteryListContract.View view, @Nullable ICPLotteryListApi api) {
        if (null == api) {
            api = Client.getRetrofit().create(ICPLotteryListApi.class);
        }
        return new CPLotteryListPresenter(api, view);
    }

    public static CpChangLongContract.Presenter inject(@NonNull CpChangLongContract.View view, @Nullable ICpChangLongApi api) {
        if (null == api) {
            api = Client.getRetrofit().create(ICpChangLongApi.class);
        }
        return new CpChangLongPresenter(api, view);
    }

    public static QuickBetContract.Presenter inject(@NonNull QuickBetContract.View view, @Nullable IQuickBetApi api) {
        if (null == api) {
            api = Client.getRetrofit().create(IQuickBetApi.class);
        }
        return new QuickBetPresenter(api, view);
    }

    public static QuickBetMethodContract.Presenter inject(@NonNull QuickBetMethodContract.View view, @Nullable IQuickBetMethodApi api) {
        if (null == api) {
            api = Client.getRetrofit().create(IQuickBetMethodApi.class);
        }
        return new QuickBetMethodPresenter(api, view);
    }
}

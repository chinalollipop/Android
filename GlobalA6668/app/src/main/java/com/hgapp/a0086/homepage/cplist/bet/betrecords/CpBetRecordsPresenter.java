package com.hgapp.a0086.homepage.cplist.bet.betrecords;

import com.hgapp.a0086.HGApplication;
import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.DateHelper;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.BetRecordsResult;
import com.hgapp.a0086.data.CPBetResult;


public class CpBetRecordsPresenter implements CpBetRecordsContract.Presenter {
    private ICpBetRecordsApi api;
    private CpBetRecordsContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CpBetRecordsPresenter(ICpBetRecordsApi api, CpBetRecordsContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void getCpBetRecords() {
        String endDate = DateHelper.getYesterday();
        String startDate =  DateHelper.getLastWeek();
        String x_session_token = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        String requestUrl = "main/betcount_less_12hours_android?endDate="+endDate+"&startDate="+startDate+"&x-session-token="+x_session_token;
        subscriptionHelper.add(RxHelper.addSugar(api.getCpBetRecords(requestUrl))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<BetRecordsResult>() {
                    @Override
                    public void success(BetRecordsResult response) {
                        view.getBetRecordsResult(response);
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void start() {

    }

    @Override
    public void destroy() {

    }


}
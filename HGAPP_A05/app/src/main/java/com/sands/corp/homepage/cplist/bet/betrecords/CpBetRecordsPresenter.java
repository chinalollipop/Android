package com.sands.corp.homepage.cplist.bet.betrecords;

import com.sands.corp.HGApplication;
import com.sands.corp.common.http.ResponseSubscriber;
import com.sands.corp.common.util.ACache;
import com.sands.corp.common.util.DateHelper;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.util.RxHelper;
import com.sands.corp.common.util.SubscriptionHelper;
import com.sands.corp.data.BetRecordsResult;


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
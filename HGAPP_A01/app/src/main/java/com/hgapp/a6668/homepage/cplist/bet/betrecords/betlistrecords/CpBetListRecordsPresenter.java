package com.hgapp.a6668.homepage.cplist.bet.betrecords.betlistrecords;

import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.DateHelper;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.BetRecordsListItemResult;
import com.hgapp.a6668.data.BetRecordsResult;


public class CpBetListRecordsPresenter implements CpBetListRecordsContract.Presenter {
    private ICpBetListRecordsApi api;
    private CpBetListRecordsContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CpBetListRecordsPresenter(ICpBetListRecordsApi api, CpBetListRecordsContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void getCpBetRecords(String dataTime) {
        String x_session_token = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        String requestUrl = "main/betcount_list_less_12hours/"+dataTime+"?x-session-token="+x_session_token;
        subscriptionHelper.add(RxHelper.addSugar(api.getCpBetRecords(requestUrl))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<BetRecordsListItemResult>() {
                    @Override
                    public void success(BetRecordsListItemResult response) {
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

package com.hgapp.a0086.homepage.cplist.bet.betrecords.betnow;

import com.hgapp.a0086.HGApplication;
import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.util.ACache;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.CPBetNowResult;


public class CpBetNowPresenter implements CpBetNowContract.Presenter {
    private ICpBetNowApi api;
    private CpBetNowContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CpBetNowPresenter(ICpBetNowApi api, CpBetNowContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void getCpBetRecords(String dataTime) {
        String x_session_token = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        String requestUrl = "main/getNotcountAndroid?x-session-token="+x_session_token;
        subscriptionHelper.add(RxHelper.addSugar(api.getCpBetRecords(requestUrl))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPBetNowResult>() {
                    @Override
                    public void success(CPBetNowResult response) {
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

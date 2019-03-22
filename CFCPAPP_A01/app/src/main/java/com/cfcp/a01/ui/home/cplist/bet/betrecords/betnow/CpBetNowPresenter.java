package com.cfcp.a01.ui.home.cplist.bet.betrecords.betnow;


import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Utils;
import com.cfcp.a01.data.CPBetNowResult;

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
        String x_session_token = ACache.get(Utils.getContext()).getAsString(CFConstant.APP_CP_X_SESSION_TOKEN);
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

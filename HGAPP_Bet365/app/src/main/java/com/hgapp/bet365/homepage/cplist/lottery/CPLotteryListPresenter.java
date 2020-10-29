package com.hgapp.bet365.homepage.cplist.lottery;

import com.hgapp.bet365.HGApplication;
import com.hgapp.bet365.common.http.ResponseSubscriber;
import com.hgapp.bet365.common.util.ACache;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.util.RxHelper;
import com.hgapp.bet365.common.util.SubscriptionHelper;
import com.hgapp.bet365.data.CPLotteryListResult;


public class CPLotteryListPresenter implements CPLotteryListContract.Presenter {


    private ICPLotteryListApi api;
    private CPLotteryListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CPLotteryListPresenter(ICPLotteryListApi api, CPLotteryListContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }


    @Override
    public void start() {

    }

    @Override
    public void destroy() {

    }



    @Override
    public void postCPLotteryList(String dataId) {
        //postLogin("");
        String date= System.currentTimeMillis()+"";
        String getUtl3 = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        subscriptionHelper.add(RxHelper.addSugar(api.get("main/result_android/"+dataId+"?x-session-token="+getUtl3))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPLotteryListResult>() {
                    @Override
                    public void success(CPLotteryListResult response) {
                            view.postCPLotteryListResult(response);
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
}

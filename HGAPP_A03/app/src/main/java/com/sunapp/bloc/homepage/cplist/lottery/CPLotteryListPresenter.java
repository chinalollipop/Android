package com.sunapp.bloc.homepage.cplist.lottery;

import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.common.http.ResponseSubscriber;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.util.RxHelper;
import com.sunapp.bloc.common.util.SubscriptionHelper;
import com.sunapp.bloc.data.CPLotteryListResult;


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

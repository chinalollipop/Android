package com.sunapp.bloc.homepage.cplist.hall;

import com.sunapp.bloc.HGApplication;
import com.sunapp.bloc.common.http.ResponseSubscriber;
import com.sunapp.bloc.common.util.ACache;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.util.RxHelper;
import com.sunapp.bloc.common.util.SubscriptionHelper;
import com.sunapp.bloc.data.CPHallResult;
import com.sunapp.bloc.data.CPLeftInfoResult;
import com.sunapp.common.util.GameLog;


public class CPHallListPresenter implements CPHallListContract.Presenter {


    private ICPHallListApi api;
    private CPHallListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CPHallListPresenter(ICPHallListApi api, CPHallListContract.View  view){
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
    public void postCPLeftInfo(String appRefer) {
        String token = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        subscriptionHelper.add(RxHelper.addSugar(api.postLeftInfo("1",token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPLeftInfoResult>() {
                    @Override
                    public void success(CPLeftInfoResult response) {
                        GameLog.log(""+response.toString());
                        view.postCPLeftInfoResult(response);
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
        /*subscriptionHelper.add(RxHelper.addSugar(api.postCPHallList("1",getUtl3))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CheckAgLiveResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CheckAgLiveResult> response) {
                        if(response.isSuccess())
                        {
                            //view.postPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }
                    }
                }));*/
    }

    @Override
    public void postCPHallList(String appRefer) {
        //postLogin("");
        String date= System.currentTimeMillis()+"";
        GameLog.log("当前的时间戳 "+date.substring(0,10));
        String getUtl3 = ACache.get(HGApplication.instance().getApplicationContext()).getAsString(HGConstant.APP_CP_X_SESSION_TOKEN);
        GameLog.log("cp 的之前的token "+getUtl3);
        //GameLog.log("========token "+getUtl2);
        subscriptionHelper.add(RxHelper.addSugar(api.get("gamessc/getAllNextIssue?_"+date+"&x-session-token="+getUtl3))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPHallResult>() {
                    @Override
                    public void success(CPHallResult response) {
                            view.postCPHallListResult(response);
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

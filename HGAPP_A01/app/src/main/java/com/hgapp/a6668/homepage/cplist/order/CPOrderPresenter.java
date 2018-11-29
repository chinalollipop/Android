package com.hgapp.a6668.homepage.cplist.order;

import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.CPHallResult;
import com.hgapp.a6668.data.CPLastResult;
import com.hgapp.a6668.data.CPLeftInfoResult;
import com.hgapp.a6668.data.CPNextIssueResult;
import com.hgapp.a6668.data.CQSSCResult;
import com.hgapp.common.util.GameLog;


public class CPOrderPresenter implements CPOrderContract.Presenter {
    private ICPOrderApi api;
    private CPOrderContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CPOrderPresenter(ICPOrderApi api, CPOrderContract.View  view){
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
    public void postCPLeftInfo(String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCPLeftInfo("1",x_session_token))//loginGet() login(appRefer,username,pwd)
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
    }

    @Override
    public void postRateInfo(String game_code, String type, String x_session_token) {

        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CQSSCResult>() {
                    @Override
                    public void success(CQSSCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfoResult(response);
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

    /*@Override
    public void postRateInfo6(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CQSSCResult>() {
                    @Override
                    public void success(CQSSCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfo6Result(response);
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
    public void postRateInfo1(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CQSSCResult>() {
                    @Override
                    public void success(CQSSCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfo1Result(response);
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
    }*/

    @Override
    public void postLastResult(String game_code, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLastResult(game_code,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPLastResult>() {
                    @Override
                    public void success(CPLastResult response) {
                        GameLog.log(""+response.toString());
                        view.postLastResultResult(response);
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
    public void postNextIssue(String game_code, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postNextIssue(game_code,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPNextIssueResult>() {
                    @Override
                    public void success(CPNextIssueResult response) {
                        GameLog.log(""+response.toString());
                        view.postNextIssueResult(response);
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

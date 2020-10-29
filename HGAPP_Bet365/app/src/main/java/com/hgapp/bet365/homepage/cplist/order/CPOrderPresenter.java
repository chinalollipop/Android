package com.hgapp.bet365.homepage.cplist.order;

import com.hgapp.bet365.common.http.ResponseSubscriber;
import com.hgapp.bet365.common.util.RxHelper;
import com.hgapp.bet365.common.util.SubscriptionHelper;
import com.hgapp.bet365.data.COLastResultHK;
import com.hgapp.bet365.data.CPBJSCResult;
import com.hgapp.bet365.data.CPHKResult;
import com.hgapp.bet365.data.CPJSFTResult;
import com.hgapp.bet365.data.CPJSK2Result;
import com.hgapp.bet365.data.CPJSKSResult;
import com.hgapp.bet365.data.CPJSSCResult;
import com.hgapp.bet365.data.CPLastResult;
import com.hgapp.bet365.data.CPLeftInfoResult;
import com.hgapp.bet365.data.CPNextIssueResult;
import com.hgapp.bet365.data.CPQuickBetResult;
import com.hgapp.bet365.data.CPXYNCResult;
import com.hgapp.bet365.data.CQ1FCResult;
import com.hgapp.bet365.data.CQ2FCResult;
import com.hgapp.bet365.data.CQ3FCResult;
import com.hgapp.bet365.data.CQ5FCResult;
import com.hgapp.bet365.data.CQSSCResult;
import com.hgapp.bet365.data.PCDDResult;
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
    public void postQuickBet(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postQuickBet(game_code,type,"0",x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPQuickBetResult>() {
                    @Override
                    public void success(CPQuickBetResult response) {
                        GameLog.log(""+response.toString());
                        view.postQuickBetResult(response);
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
    public void postRateInfoBjsc(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoBjsc(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPBJSCResult>() {
                    @Override
                    public void success(CPBJSCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfoBjscResult(response);
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
    public void postRateInfoJssc(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoJssc(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPJSSCResult>() {
                    @Override
                    public void success(CPJSSCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfoJsscResult(response);
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
    public void postRateInfoJsft(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoJsft(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPJSFTResult>() {
                    @Override
                    public void success(CPJSFTResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfoJsftResult(response);
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

    @Override
    public void postRateInfo1FC(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo1FC(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CQ1FCResult>() {
                    @Override
                    public void success(CQ1FCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfo1FCResult(response);
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
    public void postRateInfo2FC(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo2FC(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CQ2FCResult>() {
                    @Override
                    public void success(CQ2FCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfo2FCResult(response);
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
    public void postRateInfo3FC(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo3FC(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CQ3FCResult>() {
                    @Override
                    public void success(CQ3FCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfo3FCResult(response);
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
    public void postRateInfo5FC(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo5FC(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CQ5FCResult>() {
                    @Override
                    public void success(CQ5FCResult response) {
                        GameLog.log(""+response.toString());
                        view.postRateInfo5FCResult(response);
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
    public void postRateInfoJsk3(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoJsk3(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPJSKSResult>() {
                    @Override
                    public void success(CPJSKSResult response) {
                        view.postRateInfoJsk3Result(response);
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
    public void postRateInfoJsk32(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoJsk32(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPJSK2Result>() {
                    @Override
                    public void success(CPJSK2Result response) {
                        view.postRateInfoJsk32Result(response);
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
    public void postRateInfoXync(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoXYnc(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPXYNCResult>() {
                    @Override
                    public void success(CPXYNCResult response) {
                        view.postRateInfoXyncResult(response);
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
    public void postRateInfoKlsf(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoKlsf(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPXYNCResult>() {
                    @Override
                    public void success(CPXYNCResult response) {
                        view.postRateInfoKlsfResult(response);
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
    public void postRateInfoHK(String game_code, String type, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoHK(game_code,type,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<CPHKResult>() {
                    @Override
                    public void success(CPHKResult response) {
                        view.postRateInfoHKResult(response);
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
    public void postRateInfoPCDD(String game_code, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoPCDD(game_code,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<PCDDResult>() {
                    @Override
                    public void success(PCDDResult response) {
                        view.postRateInfoPCDDResult(response);
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
    public void postLastResultHK(String game_code, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLastResultHK(game_code,x_session_token))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<COLastResultHK>() {
                    @Override
                    public void success(COLastResultHK response) {
                        GameLog.log(""+response.toString());
                        view.postLastResultHKResult(response);
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

    @Override
    public void postNextIssueHK(String game_code, String x_session_token) {
        subscriptionHelper.add(RxHelper.addSugar(api.postNextIssueHK(game_code,x_session_token))//loginGet() login(appRefer,username,pwd)
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

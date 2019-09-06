package com.cfcp.a01.ui.home.cplist.order;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.GameLog;
import com.cfcp.a01.data.COLastResultHK;
import com.cfcp.a01.data.CPBJSCResult;
import com.cfcp.a01.data.CPHKResult;
import com.cfcp.a01.data.CPJSFTResult;
import com.cfcp.a01.data.CPJSK2Result;
import com.cfcp.a01.data.CPJSKSResult;
import com.cfcp.a01.data.CPJSSCResult;
import com.cfcp.a01.data.CPKL8Result;
import com.cfcp.a01.data.CPKLSFResult;
import com.cfcp.a01.data.CPLastResult;
import com.cfcp.a01.data.CPLeftInfoResult;
import com.cfcp.a01.data.CPNextIssueResult;
import com.cfcp.a01.data.CPQuickBetResult;
import com.cfcp.a01.data.CPXYNCResult;
import com.cfcp.a01.data.CQ1FCResult;
import com.cfcp.a01.data.CQ2FCResult;
import com.cfcp.a01.data.CQ3FCResult;
import com.cfcp.a01.data.CQ5FCResult;
import com.cfcp.a01.data.CQSSCResult;
import com.cfcp.a01.data.Cp11X5Result;
import com.cfcp.a01.data.GamesTipsResult;
import com.cfcp.a01.data.PCDDResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;


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
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","LeftInfo");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postCPLeftInfo(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPLeftInfoResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPLeftInfoResult> response) {
                        if(response.isSuccess()) {
                            view.postCPLeftInfoResult(response.getData());
                        }else{
                            view.showMessage(response.getDescribe());
                        }
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
    public void postNextIssue(String lottery_id, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","NextIssue");
        params.put("lottery_id",lottery_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postNextIssue(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPNextIssueResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPNextIssueResult> response) {
                        if(response.isSuccess()){
                            view.postNextIssueResult(response.getData());
                        }else{
                            view.showMessage(response.getDescribe());
                        }
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
    public void postLastResult(String lottery_id, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","CurIssue");
        params.put("lottery_id",lottery_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postLastResult(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPLastResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPLastResult> response) {
                        if(response.isSuccess()){
                            view.postLastResultResult(response.getData());
                        }else{
                            view.showMessage(response.getDescribe());
                        }
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
                           
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postRateInfoBjsc(String lottery_id, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",lottery_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoBjsc(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPBJSCResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPBJSCResult> response) {
                        //GameLog.log(""+response.toString());
                        view.postRateInfoBjscResult(response.getData());
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
                           
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postRateInfoJsft(String lottery_id, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",lottery_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoJsft(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPJSFTResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPJSFTResult> response) {
                        view.postRateInfoJsftResult(response.getData());
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
    public void postRateInfo(String game_code, String type, String x_session_token) {

        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CQSSCResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CQSSCResult> response) {
                        view.postRateInfoResult(response.getData());
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
    public void postRateInfo1FC(String game_code, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo1FC(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CQSSCResult> >() {
                    @Override
                    public void success(AppTextMessageResponse<CQSSCResult>  response) {
                        view.postRateInfoResult(response.getData());
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
                           
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postRateInfoJsk3(String game_code, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoJsk3(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPJSKSResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPJSKSResult> response) {
                        view.postRateInfoJsk3Result(response.getData());
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
                           
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postRateInfoXync(String game_code, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoXYnc(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPXYNCResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPXYNCResult> response) {
                        view.postRateInfoXyncResult(response.getData());
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
    public void postRateInfoKlsf(String game_code, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoKlsf(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPKLSFResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPKLSFResult> response) {
                        view.postRateInfoKlsfResult(response.getData());
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
    public void postRateInfoKl8(String game_code, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoKl8(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPKL8Result>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPKL8Result> response) {
                        view.postRateInfoKl8Result(response.getData());
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
    public void postRateInfo11X5(String game_code, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfo11X5(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Cp11X5Result>>() {
                    @Override
                    public void success(AppTextMessageResponse<Cp11X5Result> response) {
                        view.postRateInfo11X5Result(response.getData());
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
    public void postRateInfoHK(String game_code, String type, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoHK(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPHKResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPHKResult> response) {
                        view.postRateInfoHKResult(response.getData());
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
    public void postRateInfoPCDD(String game_code, String x_session_token) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Credit");
        params.put("action","GameRate");
        params.put("lottery_id",game_code);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.postRateInfoPCDD(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<PCDDResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<PCDDResult> response) {
                        view.postRateInfoPCDDResult(response.getData());
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
    public void getGamesTips() {
        Map<String, String> params = new HashMap<>();
        params.put("packet", "Notice");
        params.put("action", "GetNoticePrize");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getGamesTips(params))
                .subscribe(new ResponseSubscriber<GamesTipsResult>() {
                    @Override
                    public void success(GamesTipsResult response) {
                        if (response.getErrno() == 0) {
                            view.setGamesTipsResult(response);
                        } else {
                            view.showMessage(response.getError());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
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
                           
                            view.showMessage(msg);
                        }
                    }
                }));
    }*/

}

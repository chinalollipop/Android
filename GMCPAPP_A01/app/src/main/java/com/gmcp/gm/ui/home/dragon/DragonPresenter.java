package com.gmcp.gm.ui.home.dragon;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.common.utils.GameLog;
import com.gmcp.gm.data.BetDragonResult;
import com.gmcp.gm.data.BetRecordsResult;
import com.gmcp.gm.data.CPBetResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2019/4/20.
 */
public class DragonPresenter implements DragonContract.Presenter {

    private IDragonApi api;
    private DragonContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DragonPresenter(IDragonApi api, DragonContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postCpBets(String game_code, String round, String totalNums, String totalMoney, String number, Map<String, String> fields, String x_session_token) {
        GameLog.log("投注的信息是 "+x_session_token);
        subscriptionHelper.add(RxHelper.addSugar(api.postCpBets("CreditBet","Credit",x_session_token, ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN)))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPBetResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPBetResult> response) {
                        if(response.isSuccess()){
                            view.postCpBetResult(response.getData());
                        }else{
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        /*if(null != view)
                        {
                            view.setError(0,0);
                            view.showMessage(msg);
                        }*/
                    }
                }));
    }


    @Override
    public void getDragonBetList(String current_password, String new_password) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Credit");
        params.put("action", "LongDragonData");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getDragonBetList(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetDragonResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetDragonResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getDragonBetListResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
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

    @Override
    public void getDragonBetRecordList(String current_password, String new_password) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Credit");
        params.put("action", "ReportSelf");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getDragonBetRecordList(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordsResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordsResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getDragonBetRecordListResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
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

    @Override
    public void start() {

    }

    @Override
    public void destroy() {

        subscriptionHelper.unsubscribe();
        view = null;
        api = null;
    }


}


package com.cfcp.a01.ui.home.cplist.bet.betrecords.betlistrecords;


import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.Utils;
import com.cfcp.a01.data.BetRecordsListItemResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;

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
    public void getCpBetRecords(String lottery_id,String from) {
        String requestUrl ="";
        String x_session_token = ACache.get(Utils.getContext()).getAsString(CFConstant.APP_CP_X_SESSION_TOKEN);
        if(from.equals("today")){
            //requestUrl = "main/todaybill?"+lottery_id+"&x-session-token="+x_session_token;//page=1&rows=20
            requestUrl = "TodayBill";
        }else if(from.equals("before")){
            //requestUrl = "main/betcount_list_less_12hours/"+lottery_id+"?x-session-token="+x_session_token;//2018-12-06/1/20
        }else{
            //main/getNotcountDetail?gameId=51&rows=100&x-session-token=f03744fb19f25a4d15f8238d76ad3c11
            //requestUrl = "main/getNotcountDetail?gameId="+lottery_id+"&rows=100&x-session-token="+x_session_token;//2018-12-06/1/20
            requestUrl = "NotCountDetail";
        }
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Credit");
        params.put("action", requestUrl);
        params.put("lottery_id", lottery_id);
        params.put("page", "1");
        params.put("rows", "200");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getCpBetRecords(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordsListItemResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordsListItemResult> response) {
                        view.getBetRecordsResult(response.getData());
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

package com.cfcp.a01.ui.home.cplist.bet.betrecords;


import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.common.utils.DateHelper;
import com.cfcp.a01.common.utils.Utils;
import com.cfcp.a01.data.BetRecordsResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;

public class CpBetRecordsPresenter implements CpBetRecordsContract.Presenter {
    private ICpBetRecordsApi api;
    private CpBetRecordsContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CpBetRecordsPresenter(ICpBetRecordsApi api, CpBetRecordsContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void getCpBetRecords(String lottery_id,String page,String date_start,String date_end) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Credit");
        params.put("action", "ReportSelf");
        params.put("date_start", date_start);
        params.put("date_end", date_end);
        params.put("lottery_id", lottery_id);
        params.put("page", page);
        params.put("rows", "200");
        params.put("status", "-1");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getCpBetRecords(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordsResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordsResult> response) {
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

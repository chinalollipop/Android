package com.hfcp.hf.ui.home.cplist.bet.betrecords.chonglong;


import com.hfcp.hf.CFConstant;
import com.hfcp.hf.common.http.ResponseSubscriber;
import com.hfcp.hf.common.http.RxHelper;
import com.hfcp.hf.common.http.SubscriptionHelper;
import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.data.CPChangLongResult;

import java.util.HashMap;
import java.util.Map;

import static com.hfcp.hf.common.utils.Utils.getContext;

public class CpChangLongPresenter implements CpChangLongContract.Presenter {
    private ICpChangLongApi api;
    private CpChangLongContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CpChangLongPresenter(ICpChangLongApi api, CpChangLongContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void getCpBetRecords(String lottery_id) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Credit");
        params.put("action", "LongDragon");
        params.put("lottery_id", lottery_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getCpBetRecords(params))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPChangLongResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPChangLongResult> response) {
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

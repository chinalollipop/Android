package com.gmcp.gm.ui.home.cplist.bet.betrecords.chonglong;


import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.CPChangLongResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;

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

package com.hfcp.hf.ui.home.cplist.lottery;

import com.hfcp.hf.CFConstant;
import com.hfcp.hf.common.http.ResponseSubscriber;
import com.hfcp.hf.common.http.RxHelper;
import com.hfcp.hf.common.http.SubscriptionHelper;
import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.data.CPLotteryListResult;

import java.util.HashMap;
import java.util.Map;

import static com.hfcp.hf.common.utils.Utils.getContext;

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
    public void postCPLotteryList(String dataStr,String dataId) {
        Map<String, String> params = new HashMap<>();
        params.put("terminal_id", CFConstant.PRODUCT_PLATFORM);
        params.put("packet", "Credit");
        params.put("action", "HistoryData");
        params.put("data", dataStr);
        params.put("lottery_id", dataId);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.get(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<CPLotteryListResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<CPLotteryListResult> response) {
                            view.postCPLotteryListResult(response.getData());
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
}

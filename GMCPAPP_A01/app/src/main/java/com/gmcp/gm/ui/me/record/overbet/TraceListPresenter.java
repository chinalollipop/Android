package com.gmcp.gm.ui.me.record.overbet;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.TraceListResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class TraceListPresenter implements TraceListContract.Presenter {

    private ITraceListApi api;
    private TraceListContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public TraceListPresenter(ITraceListApi api, TraceListContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getTraceList(String lottery_id,String page,String pagesize,String begin_date,String end_date) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Game");
        params.put("action","GetTraceList");
        params.put("lottery_id",lottery_id);
        params.put("page",page);
        params.put("begin_date",begin_date);
        params.put("end_date",end_date);
        params.put("pagesize",pagesize);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getTraceList(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TraceListResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TraceListResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getTraceListResult(response.getData());
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


package com.gmcp.gm.ui.me.report.myreport;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.MyReportResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class MyReportPresenter implements MyReportContract.Presenter {

    private IMyReportApi api;
    private MyReportContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public MyReportPresenter(IMyReportApi api, MyReportContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getPersonReport(String user_id,String type_id,String begin_date, String end_date,String page,String pagesize) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Fund");
        params.put("action","GetTransactionList");
        params.put("begin_time",begin_date);
        params.put("end_time",end_date);
        params.put("page",page);
        params.put("pagesize",pagesize);
        params.put("user_id",user_id);
        params.put("type_id",type_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getPersonReport(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<MyReportResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<MyReportResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getPersonReportResult(response.getData());
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


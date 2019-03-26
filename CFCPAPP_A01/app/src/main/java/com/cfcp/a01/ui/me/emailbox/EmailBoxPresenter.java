package com.cfcp.a01.ui.me.emailbox;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.http.request.AppTextMessageResponseList;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.data.EmailBoxListResult;
import com.cfcp.a01.data.PersonReportResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class EmailBoxPresenter implements EmailBoxContract.Presenter {

    private IEmailBoxApi api;
    private EmailBoxContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public EmailBoxPresenter(IEmailBoxApi api, EmailBoxContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getPersonReport(String begin_date, String end_date) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","GetMsgList");
        params.put("emailbox","inbox");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getPersonReport(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<EmailBoxListResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<EmailBoxListResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getPersonReportResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                        //view.postLoginResult(response.getData());
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

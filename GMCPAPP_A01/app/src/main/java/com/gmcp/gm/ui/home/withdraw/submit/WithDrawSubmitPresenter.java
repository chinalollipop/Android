package com.gmcp.gm.ui.home.withdraw.submit;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.WithDrawNextResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class WithDrawSubmitPresenter implements WithDrawSubmitContract.Presenter {

    private IWithDrawSubmitApi api;
    private WithDrawSubmitContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public WithDrawSubmitPresenter(IWithDrawSubmitApi api, WithDrawSubmitContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void getWithDrawSubmit(String id,String amount,String fundPwd) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Fund");
        params.put("action","Withdraw");
        params.put("id",id);
        params.put("step","2");
        params.put("amount",amount);
        params.put("fund_password",fundPwd);
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getWithDrawSubmit(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<WithDrawNextResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<WithDrawNextResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getWithDrawSubmitResult(response.getData());
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


package com.gmcp.gm.ui.home.deposit;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.DepositMethodResult;
import com.gmcp.gm.data.DepositTypeResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class DepositPresenter implements DepositContract.Presenter {

    private IDepositApi api;
    private DepositContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DepositPresenter(IDepositApi api, DepositContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void getDepositMethod(String appRefer) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Fund");
        params.put("action","Payment");
        params.put("step","1");
        params.put("platform","gm");
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getDepositMethod(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<DepositMethodResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<DepositMethodResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getDepositMethodResult(response.getData());
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
    public void getDepositVerify(String amount,String deposit_mode,String payment_platform_id) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Fund");
        params.put("action","Payment");
        params.put("amount",amount);
        params.put("step","2");
        params.put("deposit_mode",deposit_mode);
        params.put("payment_platform_id",payment_platform_id);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getDepositVerify(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<DepositTypeResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<DepositTypeResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getDepositVerifyResult(response.getData());
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


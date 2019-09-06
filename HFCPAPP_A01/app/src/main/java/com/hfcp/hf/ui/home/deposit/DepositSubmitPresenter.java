package com.hfcp.hf.ui.home.deposit;

import com.hfcp.hf.CFConstant;
import com.hfcp.hf.common.http.ResponseSubscriber;
import com.hfcp.hf.common.http.RxHelper;
import com.hfcp.hf.common.http.SubscriptionHelper;
import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.common.utils.Check;
import com.hfcp.hf.data.DepositH5Result;

import java.util.HashMap;
import java.util.Map;

import static com.hfcp.hf.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class DepositSubmitPresenter implements DepositSubmitContract.Presenter {

    private IDepositSubmitApi api;
    private DepositSubmitContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DepositSubmitPresenter(IDepositSubmitApi api, DepositSubmitContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getDepositSubmit(String deposit_mode,String payment_platform_id,String payer_name,String amount) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Fund");
        params.put("action","Payment");
        params.put("step","3");
        params.put("deposit_mode",deposit_mode);
        params.put("payment_platform_id",payment_platform_id);
        if(!Check.isEmpty(payer_name)){//String payer_name,
            params.put("payer_name", payer_name);
            /*try {
                params.put("payer_name", URLEncoder.encode(payer_name, "utf-8"));
//                params.put("payer_name",new String(payer_name.getBytes("GB2312"),"utf-8"));
            } catch (UnsupportedEncodingException e) {
                e.printStackTrace();
            }*/
        }
        params.put("amount",amount);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getDepositSubmit(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<DepositH5Result>>() {
                    @Override
                    public void success(AppTextMessageResponse<DepositH5Result> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getDepositSubmitResult(response.getData());
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


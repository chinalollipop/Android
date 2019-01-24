package com.cfcp.a01.ui.me;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LogoutResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/12/20.
 */
public class MePresenter implements MeContract.Presenter {

    private IMeApi api;
    private MeContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public MePresenter(IMeApi api, MeContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postLogout(String appRefer) {
        Map<String,String> params = new HashMap<>();
        params.put("appRefer",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","Logout");
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getLogin(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LogoutResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LogoutResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.postLogoutResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                        //view.postLogoutResult(response.getData());
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


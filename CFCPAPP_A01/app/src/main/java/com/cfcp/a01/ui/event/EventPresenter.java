package com.cfcp.a01.ui.event;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.data.CouponResult;

import java.util.HashMap;
import java.util.Map;


/**
 * Created by Daniel on 2019/2/22.
 */
public class EventPresenter implements EventContract.Presenter {

    private IEventApi api;
    private EventContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public EventPresenter(IEventApi api, EventContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void getCoupon(String appRefer, String username, String password) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Coupon");
        params.put("action","GetCoupon");
        subscriptionHelper.add(RxHelper.addSugar(api.getCoupon(params))//CFConstant.PRODUCT_PLATFORM, "User", "Login", username, password, "1"
                .subscribe(new ResponseSubscriber<CouponResult>() {
                    @Override
                    public void success(CouponResult response) {
                        if (response.getErrno()==0) {//目前返回的errno为0需要改成200 代表正确的
                            view.getCouponResult(response);
                        } else {
                            view.showMessage(response.getError());
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
    }


}


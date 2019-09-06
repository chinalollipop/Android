package com.gmcp.gm.ui.home.withdraw;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.WithDrawNextResult;
import com.gmcp.gm.data.WithDrawResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class WithDrawPresenter implements WithDrawContract.Presenter {

    private IWithDrawApi api;
    private WithDrawContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public WithDrawPresenter(IWithDrawApi api, WithDrawContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void getWithDraw() {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Fund");
        params.put("action","Withdraw");
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getWithDraw(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<WithDrawResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<WithDrawResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getWithDrawResult(response.getData());
                        }else if(response.getErrno().equals("7024")) {
                            view.getAddCard();
                            view.showMessage(response.getDescribe());
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
    public void getWithDrawNext(String id,String amount) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","Fund");
        params.put("action","Withdraw");
        params.put("id",id);
        params.put("step","1");
        params.put("amount",amount);
        params.put("token",ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getWithDrawNext(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<WithDrawNextResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<WithDrawNextResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getWithDrawNextResult(response.getData());
                        }else if(response.getErrno().equals("7034")){
                            view.showMessage("您的打码量不足，您还不能出款");
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


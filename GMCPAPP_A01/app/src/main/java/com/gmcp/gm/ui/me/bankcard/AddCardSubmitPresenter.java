package com.gmcp.gm.ui.me.bankcard;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.BankCardAddResult;

import java.util.HashMap;
import java.util.Map;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class AddCardSubmitPresenter implements AddCardSubmitContract.Presenter {

    private IAddCardSubmitApi api;
    private AddCardSubmitContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public AddCardSubmitPresenter(IAddCardSubmitApi api, AddCardSubmitContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getAddCardSubmit(String type,String id,String bank, String bank_id, String branch, String account_name, String account, String account_confirmation) {
        Map<String,String> params = new HashMap<>();
        params.put("packet","User");
        params.put("action","GetBankCardList");
        if(type.equals("1")){
            params.put("id",id);
            params.put("way","modify-card");
        }else{
            params.put("way","bind-card");
        }
        params.put("step","3");
        params.put("bank",bank);
        params.put("bank_id",bank_id);
        params.put("branch",branch);
        params.put("account_name",account_name);
        params.put("account",account_confirmation);
        params.put("account_confirmation",account_confirmation);
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getAddCardSubmit(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BankCardAddResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BankCardAddResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getAddCardSubmitResult(response.getData());
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
    public void getModifyCardClearSession(String id) {
        Map<String,String> params = new HashMap<>();
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("id",id);
        params.put("way","modify-card");
        params.put("step","4");
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getAddCardSubmit(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BankCardAddResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BankCardAddResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getModifyCardClearSession();
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


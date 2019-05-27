package com.cfcp.a01.ui.me.bankcard;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.data.BankCardAddResult;
import com.cfcp.a01.data.BankListResult;
import com.cfcp.a01.data.LoginResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2018/4/20.
 */
public class AddCardPresenter implements AddCardContract.Presenter {

    private IAddCardApi api;
    private AddCardContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public AddCardPresenter(IAddCardApi api, AddCardContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getBankList() {
        Map<String,String> params = new HashMap<>();
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("way","bind-card");
        params.put("step","1");
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getBankList(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BankListResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BankListResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getBankListResult(response.getData());
                        }else if("7001".equals(response.getErrno())) {
                            view.getFundPwdResult(response.getDescribe());
                        }else {
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
    public void getAddCard(String bank, String bank_id, String branch, String account_name, String account, String account_confirmation) {
        Map<String,String> params = new HashMap<>();
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("way","bind-card");
        params.put("step","2");
        params.put("bank",bank);
        params.put("bank_id",bank_id);
        params.put("branch",branch);
        params.put("account_name",account_name);
        params.put("account",account_confirmation);
        params.put("account_confirmation",account_confirmation);
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getAddCard(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BankCardAddResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BankCardAddResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getAddCardResult(response.getData());
                        }else if("7001".equals(response.getErrno())) {
                            view.getFundPwdResult(response.getDescribe());
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


package com.cfcp.a01.ui.me.bankcard;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.data.BankCardAddResult;
import com.cfcp.a01.data.BankCardListResult;
import com.cfcp.a01.data.BankListResult;
import com.cfcp.a01.data.TeamReportResult;

import java.util.HashMap;
import java.util.Map;

import static com.cfcp.a01.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2019/4/20.
 */
public class ModifyCardPresenter implements ModifyCardContract.Presenter {

    private IModifyCardApi api;
    private ModifyCardContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public ModifyCardPresenter(IModifyCardApi api, ModifyCardContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getBankList(String id) {
        Map<String,String> params = new HashMap<>();
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("way","modify-card");
        params.put("step","1");
        params.put("id",id);
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getBankList(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BankListResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BankListResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getBankListResult(response.getData());
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
    public void getModifyCard(String id,String bank, String bank_id, String branch, String account_name, String account, String account_confirmation) {
        Map<String,String> params = new HashMap<>();
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("way","modify-card");
        params.put("step","2");
        params.put("id",id);
        params.put("bank",bank);
        params.put("bank_id",bank_id);
        params.put("branch",branch);
        params.put("account_name",account_name);
        params.put("account",account_confirmation);
        params.put("account_confirmation",account_confirmation);
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getModifyCard(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BankCardAddResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BankCardAddResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getModifyCardResult(response.getData());
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


package com.hfcp.hf.ui.me.bankcard;

import com.hfcp.hf.CFConstant;
import com.hfcp.hf.common.http.ResponseSubscriber;
import com.hfcp.hf.common.http.RxHelper;
import com.hfcp.hf.common.http.SubscriptionHelper;
import com.hfcp.hf.common.http.request.AppTextMessageResponse;
import com.hfcp.hf.common.utils.ACache;
import com.hfcp.hf.data.TeamReportResult;

import java.util.HashMap;
import java.util.Map;

import static com.hfcp.hf.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2019/4/20.
 */
public class ModifyPresenter implements ModifyContract.Presenter {

    private IModifyApi api;
    private ModifyContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public ModifyPresenter(IModifyApi api, ModifyContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void getCardModify(String id, String account_name,String account, String fund_password) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("way","modify-card");
        params.put("step","0");
        params.put("id",id);
        params.put("account_name",account_name);
        params.put("account",account);
        params.put("fund_password",fund_password);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getCardModify(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TeamReportResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TeamReportResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getCardModifyResult(response.getData());
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
    public void getCardVerify(String id, String account_name, String account, String fund_password) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("way","bind-card");
        params.put("step","0");
        params.put("id",id);
        params.put("account_name",account_name);
        params.put("account",account);
        params.put("fund_password",fund_password);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getCardVerify(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TeamReportResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TeamReportResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getCardVerifyResult(response.getData());
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
    public void getCardDelete(String id, String account_name,String account, String fund_password) {
        Map<String,String> params = new HashMap<>();
        params.put("terminal_id",CFConstant.PRODUCT_PLATFORM);
        params.put("packet","User");
        params.put("action","GetBankCardList");
        params.put("way","destroy");
        params.put("step","1");
        params.put("id",id);
        params.put("account_name",account_name);
        params.put("account",account);
        params.put("fund_password",fund_password);
        params.put("token", ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN));
        subscriptionHelper.add(RxHelper.addSugar(api.getCardDelete(params))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TeamReportResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TeamReportResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getCardDeleteResult(response.getData());
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


package com.cfcp.a01.ui.home.dragon;

import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.common.utils.ACache;
import com.cfcp.a01.data.TeamReportResult;

import static com.cfcp.a01.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2019/4/20.
 */
public class DragonPresenter implements DragonContract.Presenter {

    private IDragonApi api;
    private DragonContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DragonPresenter(IDragonApi api, DragonContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void getChangeFundPwdFirst(String fund_password, String confirm_fund_password) {
        subscriptionHelper.add(RxHelper.addSugar(api.getChangeFundPwdFirst(CFConstant.PRODUCT_PLATFORM,"User",
                "SetFundPwd",fund_password,confirm_fund_password,
                ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN)))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TeamReportResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TeamReportResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getChangeFundPwdResult(response.getData());
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
    public void getChangeFundPwd(String current_password, String new_password) {
        subscriptionHelper.add(RxHelper.addSugar(api.getChangeFundPwd(CFConstant.PRODUCT_PLATFORM,"User",
                "ChangeFundPwd",current_password,new_password,
                ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT),
                ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN)))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TeamReportResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TeamReportResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getChangeFundPwdResult(response.getData());
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
    public void getChangeLoginPwd(String current_password, String new_password) {
        subscriptionHelper.add(RxHelper.addSugar(api.getChangeLoginPwd(CFConstant.PRODUCT_PLATFORM,"User",
                "ChangeLoginPwd",current_password,new_password,
                ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_ACCOUNT),
                ACache.get(getContext()).getAsString(CFConstant.USERNAME_LOGIN_TOKEN)))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TeamReportResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TeamReportResult> response) {
                        if (response.isSuccess()) {//目前返回的errno为0需要改成200 代表正确的
                            view.getChangeLoginPwdResult(response.getData());
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


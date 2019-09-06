package com.gmcp.gm.ui.me.pwd;

import com.gmcp.gm.CFConstant;
import com.gmcp.gm.common.http.ResponseSubscriber;
import com.gmcp.gm.common.http.RxHelper;
import com.gmcp.gm.common.http.SubscriptionHelper;
import com.gmcp.gm.common.http.request.AppTextMessageResponse;
import com.gmcp.gm.common.utils.ACache;
import com.gmcp.gm.data.TeamReportResult;

import static com.gmcp.gm.common.utils.Utils.getContext;


/**
 * Created by Daniel on 2019/4/20.
 */
public class PwdPresenter implements PwdContract.Presenter {

    private IPwdApi api;
    private PwdContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public PwdPresenter(IPwdApi api, PwdContract.View view) {
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


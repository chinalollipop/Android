package com.hgapp.bet365.login.resetpwd;

import com.hgapp.bet365.common.http.ResponseSubscriber;
import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.util.RxHelper;
import com.hgapp.bet365.common.util.SubscriptionHelper;

/**
 * Created by Daniel on 2017/4/20.
 */
public class ResetPwdPresenter implements ResetPwdContract.Presenter {

    private IResetPwdApi api;
    private ResetPwdContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public ResetPwdPresenter(IResetPwdApi api, ResetPwdContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void getChangeLoginPwd(String appRefer, String action, String flag_action, String oldpassword, String password, String REpassword) {
        subscriptionHelper.add(RxHelper.addSugar(api.postChangeLoginPwd(HGConstant.PRODUCT_PLATFORM,"1","1",oldpassword,password,REpassword))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        if(response.isSuccess()){
                            view.onChangeLoginPwdResut(response.getDescribe());
                        }else{
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
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


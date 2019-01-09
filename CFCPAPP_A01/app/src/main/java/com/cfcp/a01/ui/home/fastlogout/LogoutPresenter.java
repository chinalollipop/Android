package com.cfcp.a01.ui.home.fastlogout;

import com.cfcp.a01.data.LogoutResult;
import com.cfcp.a01.http.ResponseSubscriber;
import com.cfcp.a01.http.RxHelper;
import com.cfcp.a01.http.SubscriptionHelper;
import com.cfcp.a01.http.request.AppTextMessageResponseList;
import com.cfcp.a01.utils.QPConstant;
import com.cfcp.a01.utils.Timber;


/**
 * Created by Daniel on 2019/1/8.
 */
public class LogoutPresenter implements LogoutContract.Presenter {

    private ILogoutApi api;
    private LogoutContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public LogoutPresenter(ILogoutApi api, LogoutContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postLogout(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLogout(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<LogoutResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<LogoutResult> response) {
                        if(response.isSuccess())
                        {
                            view.postLogoutResult(response.getDescribe());
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
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


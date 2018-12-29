package com.qpweb.a01.ui.loginhome.fastlogin;

import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.Timber;


/**
 * Created by Daniel on 2017/4/20.
 */
public class LoginPresenter implements LoginContract.Presenter {

    private ILoginApi api;
    private LoginContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public LoginPresenter(ILoginApi api, LoginContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postLogin(String appRefer, String username, String password) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLogin(QPConstant.PRODUCT_PLATFORM,username,password))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postLoginResult(response.getData());
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


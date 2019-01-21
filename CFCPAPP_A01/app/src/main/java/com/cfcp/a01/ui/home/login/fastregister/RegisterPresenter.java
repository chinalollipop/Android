package com.cfcp.a01.ui.home.login.fastregister;

import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.common.http.ResponseSubscriber;
import com.cfcp.a01.common.http.RxHelper;
import com.cfcp.a01.common.http.SubscriptionHelper;
import com.cfcp.a01.common.http.request.AppTextMessageResponse;
import com.cfcp.a01.CFConstant;
import com.cfcp.a01.common.utils.Timber;


/**
 * Created by Daniel on 2017/4/20.
 */
public class RegisterPresenter implements RegisterContract.Presenter {

    private IRegisterApi api;
    private RegisterContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public RegisterPresenter(IRegisterApi api, RegisterContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }


    @Override
    public void postRegisterMember(String appRefer, String action, String reference, String username, String password, String password2, String verifycode, String code) {
        subscriptionHelper.add(RxHelper.addSugar(api.registerMember(CFConstant.PRODUCT_PLATFORM,action,reference,username,password,password2,
                verifycode,code))//loginGet() login(appRefer,username,pwd) appRefer=13&type=FU&more=s
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            view.postRegisterMemberResult(response.getData());
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


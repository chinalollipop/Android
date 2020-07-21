package com.hgapp.bet365.login.fastregister;

import com.hgapp.bet365.common.http.ResponseSubscriber;
import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.util.RxHelper;
import com.hgapp.bet365.common.util.SubscriptionHelper;
import com.hgapp.bet365.data.LoginResult;
import com.hgapp.common.util.Timber;

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
    public void postRegisterMember(String appRefer,String introducer,String keys,String username,String password, String password2,String alias,
                               String paypassword,String phone,String wechat,String birthday,String know_site) {
        subscriptionHelper.add(RxHelper.addSugar(api.registerMember(HGConstant.PRODUCT_PLATFORM,introducer,keys,username,password,password2,
                alias,paypassword,phone,wechat,birthday,know_site))//loginGet() login(appRefer,username,pwd) appRefer=13&type=FU&more=s
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


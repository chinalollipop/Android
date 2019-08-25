package com.hg3366.a3366.login.forgetpwd;

import com.hg3366.a3366.common.http.ResponseSubscriber;
import com.hg3366.a3366.common.http.request.AppTextMessageResponseList;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.util.RxHelper;
import com.hg3366.a3366.common.util.SubscriptionHelper;
import com.hg3366.common.util.GameLog;

/**
 * Created by Daniel on 2017/4/20.
 */
public class ForgetPwdPresenter implements ForgetPwdContract.Presenter {

    private IForgetPwdApi api;
    private ForgetPwdContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public ForgetPwdPresenter(IForgetPwdApi api, ForgetPwdContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postForgetPwd(String appRefer, String action_type, String username, String realname,String withdraw_password, String birthday, String new_password,String password_confirmation) {

        subscriptionHelper.add(RxHelper.addSugar(api.postForgetPwd(HGConstant.PRODUCT_PLATFORM,action_type,username,realname,withdraw_password,birthday,new_password,password_confirmation))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<String>>() {
                    @Override
                    public void success(AppTextMessageResponseList<String> response) {
                        GameLog.log("返回的日志："+response.getDescribe());
                        view.showMessage(response.getDescribe());
                        if(response.getDescribe().equals("密码更改成功!")){
                            view.postForgetPwdResult(null);
                        }
                       /* if(response.isSuccess())
                        {
                            LoginResult loginResult = (LoginResult)response.getData();
                            Timber.d("快速登陆成功:%s",loginResult);
                            //view.success(response);
                            //EventBus.getDefault().post(loginResult);
                            if(null != view )
                            {
                                view.postForgetPwdResult(loginResult);
                            }
                        }
                        else
                        {

                            Timber.d("快速登陆失败:%s",response);
                        }*/
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


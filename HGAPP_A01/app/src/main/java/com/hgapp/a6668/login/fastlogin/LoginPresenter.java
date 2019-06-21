package com.hgapp.a6668.login.fastlogin;

import com.hgapp.a6668.HGApplication;
import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.util.ACache;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.LoginResult;
import com.hgapp.a6668.data.SportsPlayMethodRBResult;
import com.hgapp.common.util.Timber;

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
    public void postLogin(final String appRefer,final String username, String passwd) {

        subscriptionHelper.add(RxHelper.addSugar(api.login(appRefer,username,passwd))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.USERNAME_LOGIN_DEMO, "false");
                            LoginResult loginResult = (LoginResult)response.getData();
                            Timber.d("快速登陆成功:%s",loginResult);
                            //view.success(response);
                            //EventBus.getDefault().post(loginResult);
                            if(null != view )
                            {
                                view.postLoginResult(loginResult);
                            }
                        }else if(response.getStatus().equals("401.100"))
                        {
                            Timber.d("快速登陆失败:%s",response);
                            view.postLoginResultError(response.getDescribe());
                        }
                        else
                        {
                            view.showMessage(response.getDescribe());
                            Timber.d("快速登陆失败:%s",response);
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
    public void postLoginDemo(String appRefer, String username, String passwd) {
        subscriptionHelper.add(RxHelper.addSugar(api.loginDemo(appRefer,"Yes",username,passwd))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            ACache.get(HGApplication.instance().getApplicationContext()).put(HGConstant.USERNAME_LOGIN_DEMO, "true");
                            LoginResult loginResult = (LoginResult)response.getData();
                            Timber.d("快速登陆成功:%s",loginResult);
                            //view.success(response);
                            //EventBus.getDefault().post(loginResult);
                            if(null != view )
                            {
                                view.postLoginResult(loginResult);
                            }
                        }
                        else
                        {
                            view.showMessage(response.getDescribe());
                            Timber.d("快速登陆失败:%s",response);
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
    public void logOut() {


    }

    @Override
    public void loginGet() {

        subscriptionHelper.add(RxHelper.addSugar(api.loginGet())//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            LoginResult loginResult = (LoginResult)response.getData();
                            Timber.d("快速登陆成功:%s",loginResult);
                            //view.success(response);
                            //EventBus.getDefault().post(loginResult);
                            if(null != view )
                            {
                                view.successGet(loginResult);
                            }
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                        }
                    }
                }));
    }

    @Override
    public void getFullPayGameList() {
        subscriptionHelper.add(RxHelper.addSugar(api.getFullPayGameList())//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<SportsPlayMethodRBResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<SportsPlayMethodRBResult> response) {
                        if(response.isSuccess())
                        {
                            //LoginResult loginResult = (LoginResult)response.getData();
                            /*Timber.d("快速登陆成功:%s",loginResult);
                            //view.success(response);
                            //EventBus.getDefault().post(loginResult);
                            if(null != view )
                            {
                                view.successGet(loginResult);
                            }*/
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                        }
                    }
                }));
    }

    @Override
    public void postFullPayGameList() {
        /*subscriptionHelper.add(RxHelper.addSugar(api.postFullPayGameList(HGConstant.PRODUCT_PLATFORM,"FU","s"))//loginGet() login(appRefer,username,pwd) appRefer=13&type=FU&more=s
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<FullPayGameResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<FullPayGameResult> response) {
                        if(response.isSuccess())
                        {
                            //LoginResult loginResult = (LoginResult)response.getData();
                            *//*Timber.d("快速登陆成功:%s",loginResult);
                            //view.success(response);
                            //EventBus.getDefault().post(loginResult);
                            if(null != view )
                            {
                                view.successGet(loginResult);
                            }*//*
                        }
                        else
                        {
                            Timber.d("快速登陆失败:%s",response);
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.setError(0,0);
                        }
                    }
                }));*/
    }

    @Override
    public void addMember() {
        subscriptionHelper.add(RxHelper.addSugar(api.addMember(HGConstant.PRODUCT_PLATFORM,"ceshi000","add","kkapitest13","123qwe","123qwe",
                "张三","123456","13699888888","123123","1988-06-24","1"))//loginGet() login(appRefer,username,pwd) appRefer=13&type=FU&more=s
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if(response.isSuccess())
                        {
                            //LoginResult loginResult = (LoginResult)response.getData();
                            /*Timber.d("快速登陆成功:%s",loginResult);
                            //view.success(response);
                            //EventBus.getDefault().post(loginResult);
                            if(null != view )
                            {
                                view.successGet(loginResult);
                            }*/
                            view.showMessage(response.getDescribe());
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


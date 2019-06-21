package com.qpweb.a01.ui.home.icon;

import com.qpweb.a01.data.ChangIconResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.NickNameResult;
import com.qpweb.a01.data.PSignatureResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.Timber;


/**
 * Created by Daniel on 2017/4/20.
 */
public class IconPresenter implements IconContract.Presenter {

    private IIconApi api;
    private IconContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public IconPresenter(IIconApi api, IconContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postChangeNickName(String appRefer, String action_type, String nickname) {
        subscriptionHelper.add(RxHelper.addSugar(api.postChangeNickName(QPConstant.PRODUCT_PLATFORM,action_type,nickname))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<NickNameResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<NickNameResult> response) {
                        if(response.isSuccess())
                        {
                            view.postChangeNickNameResult(response.getData());
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
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postChangeSignWords(String appRefer, String action_type, String personalizedsignature) {
        subscriptionHelper.add(RxHelper.addSugar(api.postChangeSignWords(QPConstant.PRODUCT_PLATFORM,action_type,personalizedsignature))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<PSignatureResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<PSignatureResult> response) {
                        if(response.isSuccess())
                        {
                            view.postChangeSignWordsResult(response.getData());
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
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postChangeIcon(String appRefer, String avatarid_save, String avatarid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postChangeIcon(QPConstant.PRODUCT_PLATFORM,avatarid_save,avatarid))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<ChangIconResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<ChangIconResult> response) {
                        if(response.isSuccess())
                        {
                            view.postChangeIconResult(response.getData());
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


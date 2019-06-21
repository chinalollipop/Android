package com.qpweb.a01.ui.loginhome.sign;

import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.SignTodayResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.Timber;


/**
 * Created by Daniel on 2017/4/20.
 */
public class SignTodayPresenter implements SignTodayContract.Presenter {

    private ISignTodayApi api;
    private SignTodayContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public SignTodayPresenter(ISignTodayApi api, SignTodayContract.View view)
    {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postSignTodays(String appRefer, String username, String password) {
        subscriptionHelper.add(RxHelper.addSugar(api.postSignTodays(QPConstant.PRODUCT_PLATFORM,"sign_days"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<SignTodayResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<SignTodayResult> response) {
                        if(response.isSuccess())
                        {
                            view.postSignTodaysResult(response.getData());
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
    public void postRed(String appRefer, String username, String password) {
        subscriptionHelper.add(RxHelper.addSugar(api.postRed(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RedPacketResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RedPacketResult> response) {
                        if(response.isSuccess())
                        {
                            view.postRedResult(response.getData());
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


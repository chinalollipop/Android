package com.qpweb.a01.ui.home.bind;

import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.Timber;


/**
 * Created by Daniel on 2017/4/20.
 */
public class BindPresenter implements BindContract.Presenter {

    private IBindApi api;
    private BindContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BindPresenter(IBindApi api, BindContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postSendCode(String appRefer, String mem_phone) {
        subscriptionHelper.add(RxHelper.addSugar(api.postSendCode(QPConstant.PRODUCT_PLATFORM, mem_phone))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<LoginResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<LoginResult> response) {
                        if (response.isSuccess()) {
                            view.postSendCodeResult();
                            view.showMessage(response.getDescribe());
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
    public void postCodeSubmit(String appRefer, String nickName, String mem_phone, String mem_yzm) {
        subscriptionHelper.add(RxHelper.addSugar(api.postCodeSubmit(QPConstant.PRODUCT_PLATFORM, nickName, mem_phone, mem_yzm))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RedPacketResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RedPacketResult> response) {
                        if (response.isSuccess()) {
                            view.postCodeSubmitResult(response.getData());
                            view.showMessage(response.getDescribe());
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


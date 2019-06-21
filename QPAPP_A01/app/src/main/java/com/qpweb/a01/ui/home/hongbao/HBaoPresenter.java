package com.qpweb.a01.ui.home.hongbao;

import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.utils.QPConstant;


/**
 * Created by Daniel on 2017/4/20.
 */
public class HBaoPresenter implements HBaoContract.Presenter {

    private IHBaoApi api;
    private HBaoContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public HBaoPresenter(IHBaoApi api, HBaoContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postChangLoginPwd(String appRefer, String type,String pwdCur,String pwdNew,String pwdNew1) {
        subscriptionHelper.add(RxHelper.addSugar(api.postChangLoginPwd(QPConstant.PRODUCT_PLATFORM, type,pwdCur,pwdNew,pwdNew1))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RedPacketResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RedPacketResult> response) {
                        if (response.isSuccess()) {
                            view.postChangLoginPwdResult(response.getData());
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
    public void postChangeWithDrawPwd(String appRefer, String type, String nameReal, String pwdSafe,String pwdSafe1) {
        subscriptionHelper.add(RxHelper.addSugar(api.postChangeWithDrawPwd(QPConstant.PRODUCT_PLATFORM, type, nameReal, pwdSafe,pwdSafe1))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RedPacketResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RedPacketResult> response) {
                        view.showMessage(response.getDescribe());
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


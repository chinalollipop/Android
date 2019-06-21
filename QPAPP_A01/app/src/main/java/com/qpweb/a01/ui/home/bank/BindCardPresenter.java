package com.qpweb.a01.ui.home.bank;

import com.qpweb.a01.data.BankListResult;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.utils.QPConstant;


/**
 * Created by Daniel on 2017/4/20.
 */
public class BindCardPresenter implements BindCardContract.Presenter {

    private IBindCardApi api;
    private BindCardContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BindCardPresenter(IBindCardApi api, BindCardContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postBankList(String appRefer, String mem_phone) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBankList(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BankListResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BankListResult> response) {
                        if (response.isSuccess()) {
                            view.postBankListResult(response.getData());
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
    public void postBindBank(String appRefer, String real_name, String bank_Account, String bank_Address,String bank_Id) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBindBank(QPConstant.PRODUCT_PLATFORM, real_name, bank_Account, bank_Address,bank_Id))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BindCardResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BindCardResult> response) {
                        if (response.isSuccess()) {
                            view.postBindBankResult(response.getData());
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


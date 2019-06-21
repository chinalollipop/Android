package com.qpweb.a01.ui.home.withdraw;

import com.qpweb.a01.data.BankListResult;
import com.qpweb.a01.data.BindCardResult;
import com.qpweb.a01.data.MemValidBetResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.utils.QPConstant;


/**
 * Created by Daniel on 2017/4/20.
 */
public class WithDrawPresenter implements WithDrawContract.Presenter {

    private IWithDrawApi api;
    private WithDrawContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public WithDrawPresenter(IWithDrawApi api, WithDrawContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postMemValidBet(String appRefer, String mem_phone) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMemValidBet(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<MemValidBetResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<MemValidBetResult> response) {
                        if (response.isSuccess()) {
                            view.postMemValidBetResult(response.getData());
                        } else {
                            //view.postMemValidBetErrorResult();
                            view.postMemValidBetResult(response.getData());
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
    public void postWithDraw(String appRefer, String Bank_Address, String Bank_Account, String Bank_Name, String Money, String Withdrawal_Passwd, String Alias){
        subscriptionHelper.add(RxHelper.addSugar(api.postWithDraw(QPConstant.PRODUCT_PLATFORM, Bank_Address, Bank_Account, Bank_Name,Money,Withdrawal_Passwd,Alias))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BindCardResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BindCardResult> response) {
                        view.showMessage(response.getDescribe());
                        if (response.isSuccess()) {
                            view.postWithDrawResult(response.getData());
                            //view.showMessage(response.getDescribe());
                        } /*else {
                            view.showMessage(response.getDescribe());
                        }*/
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


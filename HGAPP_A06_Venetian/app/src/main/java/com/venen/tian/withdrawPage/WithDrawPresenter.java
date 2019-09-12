package com.venen.tian.withdrawPage;

import com.venen.tian.common.http.ResponseSubscriber;
import com.venen.tian.common.http.request.AppTextMessageResponse;
import com.venen.tian.common.util.HGConstant;
import com.venen.tian.common.util.RxHelper;
import com.venen.tian.common.util.SubscriptionHelper;
import com.venen.tian.data.WithdrawResult;


public class WithDrawPresenter implements WithdrawContract.Presenter {


    private IWithdrawApi api;
    private WithdrawContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public WithDrawPresenter(IWithdrawApi api, WithdrawContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postWithdrawBankCard(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postWithdrawBankCard(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<WithdrawResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<WithdrawResult> response) {
                        if(response.isSuccess()){
                            view.postWithdrawResult(response.getData());
                        }else{
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
    public void postWithdrawSubmit(String appRefer, String Bank_Address, String Bank_Account, String Bank_Name, String Money, String Withdrawal_Passwd, String Alias, String Key) {
        subscriptionHelper.add(RxHelper.addSugar(api.postWithdrawSubmit(HGConstant.PRODUCT_PLATFORM,Bank_Address,Bank_Account,Bank_Name,Money,Withdrawal_Passwd,Alias,Key))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        if(response.isSuccess()){
                            view.postWithdrawResult(response.getDescribe());
                        }else{
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

    }
}

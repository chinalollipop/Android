package com.hgapp.a0086.personpage.accountcenter;

import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.BetRecordResult;


public class AccountCenterPresenter implements AccountCenterContract.Presenter {





    private IAccountCenterApi api;
    private AccountCenterContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public AccountCenterPresenter(IAccountCenterApi api, AccountCenterContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postBetToday(String appRefer, String gtype, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetToday(HGConstant.PRODUCT_PLATFORM,gtype,page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordResult> response) {
                        if(response.isSuccess()){
                            view.postBetRecordResult(response.getData());
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
    public void postBetHistory(String appRefer, String gtype, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetHistory(HGConstant.PRODUCT_PLATFORM,gtype,page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordResult> response) {
                        if(response.isSuccess()){
                            view.postBetRecordResult(response.getData());
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

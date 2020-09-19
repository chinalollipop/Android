package com.sands.corp.depositpage.usdtpay;

import com.sands.corp.common.http.ResponseSubscriber;
import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.util.RxHelper;
import com.sands.corp.common.util.SubscriptionHelper;
import com.sands.corp.data.USDTRateResult;

public class USDTPayPresenter implements USDTPayContract.Presenter {

    private IUSDTPayApi api;
    private USDTPayContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public USDTPayPresenter(IUSDTPayApi api, USDTPayContract.View view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }
    @Override
    public void postDepositUSDTPaySubimt(String appRefer, String payid,final String v_amount, String cn_date, String memo,String bank_user) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositUSDTPaySubimt(HGConstant.PRODUCT_PLATFORM,payid,v_amount,cn_date,memo,bank_user))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        if(response.isSuccess()){
                            //view.postDepositUSDTPaySubimtResult(response.getDescribe());
                            postUsdtRateApiSubimt(v_amount);
                        }
                        view.showMessage(response.getDescribe());

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
    public void postUsdtRateApiSubimt(String v_amount) {
        subscriptionHelper.add(RxHelper.addSugar(api.postUsdtRateApiSubimt(HGConstant.PRODUCT_PLATFORM,v_amount))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<USDTRateResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<USDTRateResult> response) {
                        if(response.isSuccess()){
                            view.postUsdtRateApiSubimtResult(response.getData());
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

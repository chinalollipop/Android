package com.sands.corp.depositpage.aliqcpay;

import com.sands.corp.common.http.ResponseSubscriber;
import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.util.RxHelper;
import com.sands.corp.common.util.SubscriptionHelper;

public class AliQCPayPresenter implements AliQCPayContract.Presenter {

    private IAliQCPayApi api;
    private AliQCPayContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public AliQCPayPresenter(IAliQCPayApi api, AliQCPayContract.View view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }
    @Override
    public void postDepositAliPayQCPaySubimt(String appRefer, String payid, String v_amount, String cn_date, String memo,String bank_user) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositAliPayQCPaySubimt(HGConstant.PRODUCT_PLATFORM,payid,v_amount,cn_date,memo,bank_user))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        if(response.isSuccess()){
                            view.postDepositAliPayQCPaySubimtResult(response.getDescribe());
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

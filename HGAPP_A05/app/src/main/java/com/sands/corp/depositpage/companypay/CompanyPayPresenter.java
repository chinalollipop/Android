package com.sands.corp.depositpage.companypay;

import com.sands.corp.common.http.ResponseSubscriber;
import com.sands.corp.common.http.request.AppTextMessageResponse;
import com.sands.corp.common.util.HGConstant;
import com.sands.corp.common.util.RxHelper;
import com.sands.corp.common.util.SubscriptionHelper;

public class CompanyPayPresenter implements CompanyPayContract.Presenter {

    private ICompanyPayApi api;
    private CompanyPayContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public CompanyPayPresenter(ICompanyPayApi api,CompanyPayContract.View view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }
    @Override
    public void postDepositCompanyPaySubimt(String appRefer, String payid, String v_Name, String InType, String v_amount, String cn_date, String memo,String IntoBank) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositCompanyPaySubimt(HGConstant.PRODUCT_PLATFORM,payid,v_Name,InType,v_amount,cn_date,memo,IntoBank))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
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
    public void start() {

    }

    @Override
    public void destroy() {

    }
}
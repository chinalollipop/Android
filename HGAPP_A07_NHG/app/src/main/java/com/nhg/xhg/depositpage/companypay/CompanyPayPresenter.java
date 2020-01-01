package com.nhg.xhg.depositpage.companypay;

import com.nhg.xhg.common.http.ResponseSubscriber;
import com.nhg.xhg.common.http.request.AppTextMessageResponse;
import com.nhg.xhg.common.util.HGConstant;
import com.nhg.xhg.common.util.RxHelper;
import com.nhg.xhg.common.util.SubscriptionHelper;

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
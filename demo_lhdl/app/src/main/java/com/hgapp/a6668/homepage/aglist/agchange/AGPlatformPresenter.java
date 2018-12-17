package com.hgapp.a6668.homepage.aglist.agchange;

import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.PersonBalanceResult;


public class AGPlatformPresenter implements AGPlatformContract.Presenter {
    private IAgPlatformApi api;
    private AGPlatformContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public AGPlatformPresenter(IAgPlatformApi api, AGPlatformContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postPersonBalance(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalance(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<PersonBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<PersonBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postPersonBalanceResult(response.getData().get(0));
                        }
                        else
                        {
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
    public void postBanalceTransfer(String appRefer, String f, String t,String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransfer(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<Object>>() {
                    @Override
                    public void success(AppTextMessageResponseList<Object> response) {

                        if(response.isSuccess()){
                            view.postBanalceTransferSuccess();
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
    public void start() {

    }

    @Override
    public void destroy() {

    }
}

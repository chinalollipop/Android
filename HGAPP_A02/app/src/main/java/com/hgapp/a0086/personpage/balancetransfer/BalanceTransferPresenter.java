package com.hgapp.a0086.personpage.balancetransfer;

import com.hgapp.a0086.common.http.ResponseSubscriber;
import com.hgapp.a0086.common.http.request.AppTextMessageResponse;
import com.hgapp.a0086.common.util.HGConstant;
import com.hgapp.a0086.common.util.RxHelper;
import com.hgapp.a0086.common.util.SubscriptionHelper;
import com.hgapp.a0086.data.KYBalanceResult;


public class BalanceTransferPresenter implements BalanceTransferContract.Presenter {


    private IBalanceTransferApi api;
    private BalanceTransferContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BalanceTransferPresenter(IBalanceTransferApi api, BalanceTransferContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postBanalceTransferCP(String appRefer,  String action, String from,String to, String fund) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferCP(HGConstant.PRODUCT_PLATFORM,action,from,to,fund))
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
    public void postBanalceTransfer(String appRefer, String f, String t,String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransfer(HGConstant.PRODUCT_PLATFORM,f,t,b))
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
    public void postBanalceTransferKY(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferKY(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<KYBalanceResult> response) {
                        //view.postPersonBalanceKYResult(response.getData());
                        /*if(response.isSuccess()){
                            view.showMessage(response.getDescribe());
                        }else{
                            view.showMessage(response.getDescribe());
                        }*/
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

package com.sunapp.bloc.personpage.balancetransfer;

import com.sunapp.bloc.common.http.ResponseSubscriber;
import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.common.http.request.AppTextMessageResponseList;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.util.RxHelper;
import com.sunapp.bloc.common.util.SubscriptionHelper;
import com.sunapp.bloc.data.KYBalanceResult;


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
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<Object>>() {
                    @Override
                    public void success(AppTextMessageResponseList<Object> response) {

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
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
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
    public void postBanalceTransferHG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferHG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
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
    public void postBanalceTransferVG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferVG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
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
    public void postBanalceTransferLY(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferLY(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
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
    public void postBanalceTransferMG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferMG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
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
    public void postBanalceTransferAG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferAG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
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

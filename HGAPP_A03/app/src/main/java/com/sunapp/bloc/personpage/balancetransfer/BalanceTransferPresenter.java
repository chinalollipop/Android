package com.sunapp.bloc.personpage.balancetransfer;

import com.sunapp.bloc.common.http.ResponseSubscriber;
import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.common.http.request.AppTextMessageResponseList;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.util.RxHelper;
import com.sunapp.bloc.common.util.SubscriptionHelper;
import com.sunapp.bloc.data.KYBalanceResult;
import com.sunapp.bloc.data.PersonBalanceResult;


public  class BalanceTransferPresenter implements BalanceTransferContract.Presenter {
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
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferCP(HGConstant.PRODUCT_PLATFORM,from,to,fund))
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
    public void postBanalceTransferOG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferOG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceOGResult(response.getData().get(0));
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
    public void postPersonBalanceCP(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceCP(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postPersonBalanceCPResult(response.getData().get(0));
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
    public void postPersonBalanceKY(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceKY(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceKYResult(response.getData().get(0));
                        }
                        //view.showMessage(response.getDescribe());
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
    public void postPersonBalanceHG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceHG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceHGResult(response.getData().get(0));
                        }
                        //view.showMessage(response.getDescribe());
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
    public void postPersonBalanceVG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceVG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceVGResult(response.getData().get(0));
                        }
                        //view.showMessage(response.getDescribe());
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
    public void postPersonBalanceLY(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceLY(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceLYResult(response.getData().get(0));
                        }
                        //view.showMessage(response.getDescribe());
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
    public void postPersonBalanceMG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceMG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceMGResult(response.getData().get(0));
                        }
                        //view.showMessage(response.getDescribe());
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
    public void postPersonBalanceAG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceAG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceAGResult(response.getData().get(0));
                        }
                        //view.showMessage(response.getDescribe());
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
    public void postPersonBalanceOG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceOG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceOGResult(response.getData().get(0));
                        }
                        //view.showMessage(response.getDescribe());
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

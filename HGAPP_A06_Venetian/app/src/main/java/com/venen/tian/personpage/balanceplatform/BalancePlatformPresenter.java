package com.venen.tian.personpage.balanceplatform;

import com.venen.tian.common.http.ResponseSubscriber;
import com.venen.tian.common.http.request.AppTextMessageResponseList;
import com.venen.tian.common.util.HGConstant;
import com.venen.tian.common.util.RxHelper;
import com.venen.tian.common.util.SubscriptionHelper;
import com.venen.tian.data.KYBalanceResult;


public class BalancePlatformPresenter implements BalancePlatformContract.Presenter {


    private IBalancePlatformApi api;
    private BalancePlatformContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BalancePlatformPresenter(IBalancePlatformApi api, BalancePlatformContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postPersonBalanceTY(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceTY(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            view.postPersonBalanceTYResult(response.getData().get(0));
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
    public void postBanalceTransferTY(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferTY(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceTYResult(response.getData().get(0));
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
    public void postBanalceTransferCP(String appRefer,  String action, String from,String to, String fund) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferCP(HGConstant.PRODUCT_PLATFORM,from,to,fund))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceCPResult(response.getData().get(0));
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
    public void postBanalceTransfer(String appRefer, String f, String t,String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransfer(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
                        if(response.isSuccess())
                        {
                            //view.postPersonBalanceResult(response.getData().get(0));
                            postPersonBalance("","");
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
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {
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
    public void postPersonBalanceKY(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceKY(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceKYResult(response.getData().get(0));
                        }else
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
    public void postBanalceTransferKY(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferKY(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceKYResult(response.getData().get(0));
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
    public void postPersonBalanceHG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceHG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceHGResult(response.getData().get(0));
                        }else
                        {
                            view.showMessage(response.getDescribe());
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
    public void postBanalceTransferHG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferHG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceHGResult(response.getData().get(0));
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
    public void postPersonBalanceVG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceVG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceVGResult(response.getData().get(0));
                        }else
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
    public void postBanalceTransferVG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferVG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceVGResult(response.getData().get(0));
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
    public void postPersonBalanceLY(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceLY(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceLYResult(response.getData().get(0));
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
    public void postBanalceTransferLY(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferLY(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceLYResult(response.getData().get(0));
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
    public void postPersonBalanceMG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceMG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceMGResult(response.getData().get(0));
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
    public void postBanalceTransferMG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferMG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceMGResult(response.getData().get(0));
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
    public void postPersonBalanceAG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceAG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceAGResult(response.getData().get(0));
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
    public void postBanalceTransferAG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferAG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceAGResult(response.getData().get(0));
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
    public void postPersonBalanceOG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceOG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceOGResult(response.getData().get(0));
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
    public void postPersonBalanceCQ(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceCQ(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceCQResult(response.getData().get(0));
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
    public void postBanalceTransferCQ(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferCQ(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceCQResult(response.getData().get(0));
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
    public void postPersonBalanceMW(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceMW(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceMWResult(response.getData().get(0));
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
    public void postBanalceTransferMW(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferMW(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceMWResult(response.getData().get(0));
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
    public void postPersonBalanceFG(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceFG(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceFGResult(response.getData().get(0));
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
    public void postBanalceTransferFG(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferFG(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceFGResult(response.getData().get(0));
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
    public void postPersonBalanceBBIN(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postPersonBalanceBBIN(HGConstant.PRODUCT_PLATFORM,"b"))//loginGet() login(appRefer,username,pwd)
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess())
                        {
                            view.postPersonBalanceBBINResult(response.getData().get(0));
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
    public void postBanalceTransferBBIN(String appRefer, String f, String t, String b) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBanalceTransferBBIN(HGConstant.PRODUCT_PLATFORM,f,t,b))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<KYBalanceResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<KYBalanceResult> response) {

                        if(response.isSuccess()){
                            view.postPersonBalanceBBINResult(response.getData().get(0));
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

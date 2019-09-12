package com.vene.tian.personpage.onlineservice;

import com.vene.tian.common.http.ResponseSubscriber;
import com.vene.tian.common.http.request.AppTextMessageResponse;
import com.vene.tian.common.util.HGConstant;
import com.vene.tian.common.util.RxHelper;
import com.vene.tian.common.util.SubscriptionHelper;
import com.vene.tian.data.OnlineServiceResult;


public class OnlineServicePresenter implements OnLineServiceContract.Presenter {

    private IOnLineServiceApi api;
    private OnLineServiceContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public OnlineServicePresenter(IOnLineServiceApi api, OnLineServiceContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void getOnlineService(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositRecord(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<OnlineServiceResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<OnlineServiceResult> response) {
                        if(response.isSuccess()){
                            view.postOnlineServiceResult(response.getData());
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

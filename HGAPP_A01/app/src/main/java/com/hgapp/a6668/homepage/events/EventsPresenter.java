package com.hgapp.a6668.homepage.events;

import com.hgapp.a6668.common.http.ResponseSubscriber;
import com.hgapp.a6668.common.http.request.AppTextMessageResponse;
import com.hgapp.a6668.common.http.request.AppTextMessageResponseList;
import com.hgapp.a6668.common.util.HGConstant;
import com.hgapp.a6668.common.util.RxHelper;
import com.hgapp.a6668.common.util.SubscriptionHelper;
import com.hgapp.a6668.data.DownAppGiftResult;

public class EventsPresenter implements EventsContract.Presenter {

    private IEventsApi api;
    private EventsContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public EventsPresenter(IEventsApi api, EventsContract.View view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }
    @Override
    public void postDownAppGift(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDownAppGift(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<String>>() {
                    @Override
                    public void success(AppTextMessageResponseList<String> response) {
                        view.postDownAppGiftResult("38");
                        /*if(response.isSuccess()){
                            view.postDownAppGiftResult(response.getData().get(0));
                        }else{
                            view.showMessage(response.getDescribe());
                        }*/
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

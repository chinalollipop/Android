package com.hgapp.m8.personpage.flowingrecord;

import com.hgapp.m8.common.http.ResponseSubscriber;
import com.hgapp.m8.common.http.request.AppTextMessageResponse;
import com.hgapp.m8.common.util.HGConstant;
import com.hgapp.m8.common.util.RxHelper;
import com.hgapp.m8.common.util.SubscriptionHelper;
import com.hgapp.m8.data.FlowingRecordResult;


public class FlowingRecordPresenter implements FlowingRecordContract.Presenter {





    private IFlowingRecordApi api;
    private FlowingRecordContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public FlowingRecordPresenter(IFlowingRecordApi api, FlowingRecordContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postFlowingToday(String appRefer, String gtype, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetToday(HGConstant.PRODUCT_PLATFORM,gtype,page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<FlowingRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<FlowingRecordResult> response) {
                        if(response.isSuccess()){
                            view.postFlowingRecordResult(response.getData());
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
    public void postFlowingHistory(String appRefer, String gtype, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetHistory(HGConstant.PRODUCT_PLATFORM,gtype,page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<FlowingRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<FlowingRecordResult> response) {
                        if(response.isSuccess()){
                            view.postFlowingRecordResult(response.getData());
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

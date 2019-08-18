package com.sunapp.bloc.personpage.flowingrecord;

import com.sunapp.bloc.common.http.ResponseSubscriber;
import com.sunapp.bloc.common.http.request.AppTextMessageResponse;
import com.sunapp.bloc.common.util.HGConstant;
import com.sunapp.bloc.common.util.RxHelper;
import com.sunapp.bloc.common.util.SubscriptionHelper;
import com.sunapp.bloc.data.FlowingRecordResult;


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

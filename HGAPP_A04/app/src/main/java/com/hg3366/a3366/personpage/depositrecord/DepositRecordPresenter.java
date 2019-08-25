package com.hg3366.a3366.personpage.depositrecord;

import com.hg3366.a3366.common.http.ResponseSubscriber;
import com.hg3366.a3366.common.http.request.AppTextMessageResponse;
import com.hg3366.a3366.common.util.HGConstant;
import com.hg3366.a3366.common.util.RxHelper;
import com.hg3366.a3366.common.util.SubscriptionHelper;
import com.hg3366.a3366.data.RecordResult;
import com.hg3366.common.util.Check;


public class DepositRecordPresenter implements DepositRecordContract.Presenter {
    private IDepositRecordApi api;
    private DepositRecordContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DepositRecordPresenter(IDepositRecordApi api, DepositRecordContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void getDepositRecord(String appRefer,String thistype,String page,String type_status,String date_start,String date_end) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositRecord(HGConstant.PRODUCT_PLATFORM,thistype,page,type_status,date_start,date_end))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RecordResult> response) {
                        if(response.isSuccess()){
                            if(!Check.isNull(response.getData())){//&&response.getData().getRows().size()>0
                                view.postDepositRecordResult(response.getData());
                            }
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

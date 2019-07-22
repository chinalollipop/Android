package com.qpweb.a01.ui.home.agency;

import com.qpweb.a01.data.DetailWeekListResult;
import com.qpweb.a01.data.MyAgencyResults;
import com.qpweb.a01.data.DetailListResult;
import com.qpweb.a01.data.ProListResults;
import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;
import com.qpweb.a01.utils.QPConstant;


/**
 * Created by Daniel on 2017/4/20.
 */
public class AgencyPresenter implements AgencyContract.Presenter {

    private IAgencyApi api;
    private AgencyContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public AgencyPresenter(IAgencyApi api, AgencyContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postMyProList(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postMyProList(QPConstant.PRODUCT_PLATFORM,"info"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<MyAgencyResults>>() {
                    @Override
                    public void success(AppTextMessageResponseList<MyAgencyResults> response) {
                        if (response.isSuccess()) {
                            if(response.getData().size()>0){
                                view.postMyProListResult(response.getData().get(0));
                            }
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postProDetail(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postProDetail(QPConstant.PRODUCT_PLATFORM,"info"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<DetailListResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<DetailListResult> response) {
                        if (response.isSuccess()) {
                            view.postDetailListResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postWeeksDetail(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postWeeksDetail(QPConstant.PRODUCT_PLATFORM,"info"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<DetailWeekListResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<DetailWeekListResult> response) {
                        if (response.isSuccess()) {
                            view.postWeeksDetailResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postGetMyPromotion(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGetMyPromotion(QPConstant.PRODUCT_PLATFORM,""))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<RedPacketResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<RedPacketResult> response) {
                        view.showMessage(response.getDescribe());
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postGetMyPromotionRecord(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postGetMyPromotionRecord(QPConstant.PRODUCT_PLATFORM,""))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<ProListResults>>() {
                    @Override
                    public void success(AppTextMessageResponseList<ProListResults> response) {
                        if (response.isSuccess()) {
                            view.postGetMyPromotionRecordResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
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

        subscriptionHelper.unsubscribe();
        view = null;
        api = null;
    }


}


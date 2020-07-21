package com.hgapp.bet365.personpage.betrecord;

import com.hgapp.bet365.common.http.ResponseSubscriber;
import com.hgapp.bet365.common.http.request.AppTextMessageResponse;
import com.hgapp.bet365.common.util.HGConstant;
import com.hgapp.bet365.common.util.RxHelper;
import com.hgapp.bet365.common.util.SubscriptionHelper;
import com.hgapp.bet365.data.BetRecordResult;

public class BetRecordPresenter implements BetRecordContract.Presenter {
    private IBetRecordApi api;
    private BetRecordContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public BetRecordPresenter(IBetRecordApi api, BetRecordContract.View view) {
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postBetRecordList(String appRefer, String gtype, String Checked, String Cancel, String date_start, String date_end, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetRecordList(HGConstant.PRODUCT_PLATFORM, gtype, Checked, Cancel, date_start, date_end, page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordResult> response) {
                        if (response.isSuccess()) {
                            view.postBetRecordResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postBetCPRecordList(String appRefer, String gtype, String Checked, String Cancel, String date_start, String date_end, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetCPRecordList(HGConstant.PRODUCT_PLATFORM, gtype, Checked, Cancel, date_start, date_end, page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordResult> response) {
                        if (response.isSuccess()) {
                            view.postBetRecordResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postBetAGDZRecordList(String appRefer, String gtype, String Checked, String Cancel, String date_start, String date_end, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetAGDZRecordList(HGConstant.PRODUCT_PLATFORM, gtype, Checked, Cancel, date_start, date_end, page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordResult> response) {
                        if (response.isSuccess()) {
                            view.postBetRecordResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
                            view.showMessage(msg);
                        }
                    }
                }));
    }

    @Override
    public void postBetODZRecordList(String appRefer, String gtype, String Checked, String Cancel, String date_start, String date_end, String page) {
        subscriptionHelper.add(RxHelper.addSugar(api.postBetODZRecordList(HGConstant.PRODUCT_PLATFORM, gtype, Checked, Cancel, date_start, date_end, page))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<BetRecordResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<BetRecordResult> response) {
                        if (response.isSuccess()) {
                            view.postBetRecordResult(response.getData());
                        } else {
                            view.showMessage(response.getDescribe());
                        }
                    }

                    @Override
                    public void fail(String msg) {
                        if (null != view) {
                            view.setError(0, 0);
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

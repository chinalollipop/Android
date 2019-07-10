package com.qpweb.a01.ui.home.hongbao;

import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.ValidResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;
import com.qpweb.a01.utils.QPConstant;


/**
 * Created by Daniel on 2017/4/20.
 */
public class HBaoPresenter implements HBaoContract.Presenter {

    private IHBaoApi api;
    private HBaoContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public HBaoPresenter(IHBaoApi api, HBaoContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postValid(String appRefer, String action) {
        subscriptionHelper.add(RxHelper.addSugar(api.postValid(QPConstant.PRODUCT_PLATFORM, "get_valid"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<ValidResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<ValidResult> response) {
                        if (response.isSuccess()) {
                            view.postValidResult(response.getData());
                            view.showMessage(response.getDescribe());
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
    public void postLuckEnvelope(String appRefer, String type) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLuckEnvelope(QPConstant.PRODUCT_PLATFORM, "extract_lucky_red_envelope"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<RedPacketResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<RedPacketResult> response) {
                        if (response.isSuccess()) {
                            view.postLuckEnvelopeResult(response.getData().get(0));
                            view.showMessage(response.getDescribe());
                        } else {
                            view.postLuckEnvelopeErrorResult(response.getDescribe());
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
    public void postLuckEnvelopeRecord(String appRefer, String type) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLuckEnvelopeRecord(QPConstant.PRODUCT_PLATFORM, "get_record"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<ValidResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<ValidResult> response) {
                        if (response.isSuccess()) {
                            view.postLuckEnvelopeRecordResult(response.getData());
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


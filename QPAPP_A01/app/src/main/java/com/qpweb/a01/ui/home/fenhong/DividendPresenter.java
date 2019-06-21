package com.qpweb.a01.ui.home.fenhong;

import com.qpweb.a01.data.RedPacketResult;
import com.qpweb.a01.data.TouziResult;
import com.qpweb.a01.data.TouziYestodayResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;
import com.qpweb.a01.utils.Check;
import com.qpweb.a01.utils.GameLog;
import com.qpweb.a01.utils.QPConstant;


/**
 * Created by Daniel on 2017/4/20.
 */
public class DividendPresenter implements DividendContract.Presenter {

    private IDividendApi api;
    private DividendContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DividendPresenter(IDividendApi api, DividendContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postTouziYestodayList(String appRefer, String type) {
        subscriptionHelper.add(RxHelper.addSugar(api.postTouziYestodayList(QPConstant.PRODUCT_PLATFORM, "get_touzi_yestoday_list"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<TouziYestodayResult>>() {
                    @Override
                    public void success(AppTextMessageResponse<TouziYestodayResult> response) {
                        if (response.isSuccess()) {
                            view.postTouziYestodayListResult(response.getData());
                        }else{
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
    public void postTouziSign(String appRefer, String type) {
        subscriptionHelper.add(RxHelper.addSugar(api.postTouziSign(QPConstant.PRODUCT_PLATFORM, "signin"))
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
    public void postTouzi(String appRefer, String money) {
        subscriptionHelper.add(RxHelper.addSugar(api.postTouzi(QPConstant.PRODUCT_PLATFORM, "touzi",money))
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
    public void postTouziRecord(String appRefer, String type) {
        subscriptionHelper.add(RxHelper.addSugar(api.postTouziRecord(QPConstant.PRODUCT_PLATFORM, "get_touzi_records"))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<TouziResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<TouziResult> response) {
                        if (response.isSuccess()) {
                            GameLog.log("投资记录 "+response.getData());
                            if(Check.isNull(response.getData())){
                                view.showMessage(response.getDescribe());
                            }else{
                                view.postTouziRecordResult(response.getData());
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
    public void start() {

    }

    @Override
    public void destroy() {

        subscriptionHelper.unsubscribe();
        view = null;
        api = null;
    }


}


package com.qpweb.a01.ui.home.deposit;

import com.qpweb.a01.data.DepositAliPayQCCodeResult;
import com.qpweb.a01.data.DepositBankCordListResult;
import com.qpweb.a01.data.DepositListResult;
import com.qpweb.a01.data.DepositThirdBankCardResult;
import com.qpweb.a01.data.DepositThirdQQPayResult;
import com.qpweb.a01.data.LoginResult;
import com.qpweb.a01.http.ResponseSubscriber;
import com.qpweb.a01.http.RxHelper;
import com.qpweb.a01.http.SubscriptionHelper;
import com.qpweb.a01.http.request.AppTextMessageResponse;
import com.qpweb.a01.http.request.AppTextMessageResponseList;
import com.qpweb.a01.utils.QPConstant;
import com.qpweb.a01.utils.Timber;


/**
 * Created by Daniel on 2017/4/20.
 */
public class DepositPresenter implements DepositContract.Presenter {

    private DepositApi api;
    private DepositContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DepositPresenter(DepositApi api, DepositContract.View view) {
        this.view = view;
        this.view.setPresenter(this);
        this.api = api;
    }

    @Override
    public void postLogin(String appRefer, String username, String password) {
        subscriptionHelper.add(RxHelper.addSugar(api.postLogin(QPConstant.PRODUCT_PLATFORM, username, password))
                .subscribe(new ResponseSubscriber<AppTextMessageResponseList<DepositListResult>>() {
                    @Override
                    public void success(AppTextMessageResponseList<DepositListResult> response) {
                        if (response.isSuccess()) {
                            view.postLoginResult(response.getData());
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
    public void postDepositBankCordList(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositBankCordList(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositBankCordListResult>() {
                    @Override
                    public void success(DepositBankCordListResult response) {
                        if ("200".equals(response.getStatus())) {
                            view.postDepositBankCordListResult(response);
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
    public void postDepositThirdBankCard(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdBankCard(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdBankCardResult>() {
                    @Override
                    public void success(DepositThirdBankCardResult response) {
                        if ("200".equals(response.getStatus())) {
                            view.postDepositThirdBankCardResult(response);
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
    public void postDepositThirdWXPay(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdWXPay(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdQQPayResult>() {
                    @Override
                    public void success(DepositThirdQQPayResult response) {
                        if ("200".equals(response.getStatus())) {
                            view.postDepositThirdWXPayResult(response);
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
    public void postDepositThirdAliPay(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdAliPay(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdQQPayResult>() {
                    @Override
                    public void success(DepositThirdQQPayResult response) {
                        if ("200".equals(response.getStatus())) {
                            view.postDepositThirdAliPayResult(response);
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
    public void postDepositThirdQQPay(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdQQPay(QPConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdQQPayResult>() {
                    @Override
                    public void success(DepositThirdQQPayResult response) {
                        if ("200".equals(response.getStatus())) {
                            view.postDepositThirdQQPayResult(response);
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
    public void postDepositAliPayQCCode(String appRefer, String bankid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositAliPayQCCode(QPConstant.PRODUCT_PLATFORM, bankid))
                .subscribe(new ResponseSubscriber<DepositAliPayQCCodeResult>() {
                    @Override
                    public void success(DepositAliPayQCCodeResult response) {
                        if ("200".equals(response.getStatus())) {
                            view.postDepositAliPayQCCodeResult(response);
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
    public void postDepositWechatQCCode(String appRefer, String bankid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositWechatQCCode(QPConstant.PRODUCT_PLATFORM, bankid))
                .subscribe(new ResponseSubscriber<DepositAliPayQCCodeResult>() {
                    @Override
                    public void success(DepositAliPayQCCodeResult response) {
                        if ("200".equals(response.getStatus())) {
                            view.postDepositAliPayQCCodeResult(response);
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
    public void postDepositCompanyPaySubimt(String appRefer, String payid, String v_Name, String InType, String v_amount, String cn_date, String memo,String IntoBank) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositCompanyPaySubimt(QPConstant.PRODUCT_PLATFORM,payid,v_Name,InType,v_amount,cn_date,memo,IntoBank))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        view.showMessage(response.getDescribe());
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
                            view.showMessage(msg);
                        }
                    }
                }));
    }


    @Override
    public void postDepositAliPayQCPaySubimt(String appRefer, String payid, String v_amount, String cn_date, String memo,String bank_user) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositAliPayQCPaySubimt(QPConstant.PRODUCT_PLATFORM,payid,v_amount,cn_date,memo,bank_user))
                .subscribe(new ResponseSubscriber<AppTextMessageResponse<Object>>() {
                    @Override
                    public void success(AppTextMessageResponse<Object> response) {
                        view.showMessage(response.getDescribe());
                    }

                    @Override
                    public void fail(String msg) {
                        if(null != view)
                        {
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


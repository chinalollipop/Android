package com.venen.tian.depositpage;

import com.venen.tian.common.http.ResponseSubscriber;
import com.venen.tian.common.util.HGConstant;
import com.venen.tian.common.util.RxHelper;
import com.venen.tian.common.util.SubscriptionHelper;
import com.venen.tian.data.DepositAliPayQCCodeResult;
import com.venen.tian.data.DepositBankCordListResult;
import com.venen.tian.data.DepositListResult;
import com.venen.tian.data.DepositThirdBankCardResult;
import com.venen.tian.data.DepositThirdQQPayResult;


public class DepositPresenter implements DepositeContract.Presenter {


    private IDepositApi api;
    private DepositeContract.View view;
    private SubscriptionHelper subscriptionHelper = new SubscriptionHelper();

    public DepositPresenter(IDepositApi api, DepositeContract.View  view){
        this.view = view;
        this.api = api;
        this.view.setPresenter(this);
    }

    @Override
    public void postDepositList(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositList(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositListResult>() {
                    @Override
                    public void success(DepositListResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositListResult(response);
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
    public void postDepositBankCordList(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositBankCordList(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositBankCordListResult>() {
                    @Override
                    public void success(DepositBankCordListResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositBankCordListResult(response);
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
    public void postDepositThirdBankCard(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdBankCard(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdBankCardResult>() {
                    @Override
                    public void success(DepositThirdBankCardResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositThirdBankCardResult(response);
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
    public void postDepositThirdWXPay(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdWXPay(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdQQPayResult>() {
                    @Override
                    public void success(DepositThirdQQPayResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositThirdWXPayResult(response);
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
    public void postDepositThirdAliPay(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdAliPay(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdQQPayResult>() {
                    @Override
                    public void success(DepositThirdQQPayResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositThirdAliPayResult(response);
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
    public void postDepositThirdQQPay(String appRefer) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositThirdQQPay(HGConstant.PRODUCT_PLATFORM))
                .subscribe(new ResponseSubscriber<DepositThirdQQPayResult>() {
                    @Override
                    public void success(DepositThirdQQPayResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositThirdQQPayResult(response);
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
    public void postDepositAliPayQCCode(String appRefer, String bankid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositAliPayQCCode(HGConstant.PRODUCT_PLATFORM,bankid))
                .subscribe(new ResponseSubscriber<DepositAliPayQCCodeResult>() {
                    @Override
                    public void success(DepositAliPayQCCodeResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositAliPayQCCodeResult(response);
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
    public void postDepositWechatQCCode(String appRefer, String bankid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositWechatQCCode(HGConstant.PRODUCT_PLATFORM,bankid))
                .subscribe(new ResponseSubscriber<DepositAliPayQCCodeResult>() {
                    @Override
                    public void success(DepositAliPayQCCodeResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositAliPayQCCodeResult(response);
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
    public void postDepositThirdUQCCode(String appRefer, String bankid) {
        subscriptionHelper.add(RxHelper.addSugar(api.postDepositUQCCode(HGConstant.PRODUCT_PLATFORM,bankid))
                .subscribe(new ResponseSubscriber<DepositAliPayQCCodeResult>() {
                    @Override
                    public void success(DepositAliPayQCCodeResult response) {
                        if("200".equals(response.getStatus())){
                            view.postDepositAliPayQCCodeResult(response);
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

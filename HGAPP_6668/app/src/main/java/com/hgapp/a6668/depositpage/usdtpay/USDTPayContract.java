package com.hgapp.a6668.depositpage.usdtpay;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.USDTRateResult;

public interface USDTPayContract {

    public interface Presenter extends IPresenter
    {
        public void postDepositUSDTPaySubimt(String appRefer, String payid, String v_amount, String cn_date, String memo, String bank_user);
        public void postUsdtRateApiSubimt(String v_amount);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {
        public void postDepositUSDTPaySubimtResult(String message);
        public void postUsdtRateApiSubimtResult(USDTRateResult usdtRateResult);
    }

}

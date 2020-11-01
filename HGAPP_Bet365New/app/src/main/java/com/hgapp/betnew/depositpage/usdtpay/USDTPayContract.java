package com.hgapp.betnew.depositpage.usdtpay;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.USDTRateResult;

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

package com.hgapp.bet365.depositpage.usdtpay;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.USDTRateResult;

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

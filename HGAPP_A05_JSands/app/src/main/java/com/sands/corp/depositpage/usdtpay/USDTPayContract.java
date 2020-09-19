package com.sands.corp.depositpage.usdtpay;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.USDTRateResult;

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

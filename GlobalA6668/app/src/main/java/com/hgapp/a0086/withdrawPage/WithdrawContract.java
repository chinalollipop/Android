package com.hgapp.a0086.withdrawPage;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.USDTRateResult;
import com.hgapp.a0086.data.WithdrawResult;

public interface WithdrawContract {
    public interface Presenter extends IPresenter
    {
        public void postWithdrawBankCard(String appRefer);
        public void postWithdrawSubmit(String appRefer,String Bank_Address,String Bank_Account,String Bank_Name,String Money,String Withdrawal_Passwd,String Alias,String Key,String usdt_address);
        public void postUsdtRateApiSubimt(String action);
    }
    public interface View extends IView<WithdrawContract.Presenter>,IMessageView,IProgressView
    {
        public void postWithdrawResult(WithdrawResult withdrawResult);
        public void postWithdrawResult(Object object);
        public void postUsdtRateApiSubimtResult(USDTRateResult usdtRateResult);
    }
}

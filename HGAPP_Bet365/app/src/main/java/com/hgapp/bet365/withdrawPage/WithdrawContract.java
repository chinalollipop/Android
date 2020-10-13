package com.hgapp.bet365.withdrawPage;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.USDTRateResult;
import com.hgapp.bet365.data.WithdrawResult;

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

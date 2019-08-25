package com.hg3366.a3366.withdrawPage;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.WithdrawResult;

public interface WithdrawContract {
    public interface Presenter extends IPresenter
    {
        public void postWithdrawBankCard(String appRefer);
        public void postWithdrawSubmit(String appRefer,String Bank_Address,String Bank_Account,String Bank_Name,String Money,String Withdrawal_Passwd,String Alias,String Key);

    }
    public interface View extends IView<WithdrawContract.Presenter>,IMessageView,IProgressView
    {
        public void postWithdrawResult(WithdrawResult withdrawResult);
        public void postWithdrawResult(Object object);
    }
}

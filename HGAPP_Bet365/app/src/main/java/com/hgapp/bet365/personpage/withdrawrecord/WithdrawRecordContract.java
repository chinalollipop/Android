package com.hgapp.bet365.personpage.withdrawrecord;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;

public interface WithdrawRecordContract {
    public interface Presenter extends IPresenter
    {
        public void getDepositRecord(String appRefer, String thistype, String page);

    }
    public interface View extends IView<WithdrawRecordContract.Presenter>,IMessageView,IProgressView
    {
        public void postDepositRecordResult(String message);
    }
}

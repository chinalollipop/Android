package com.hgapp.a0086.personpage.withdrawrecord;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;

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

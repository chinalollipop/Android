package com.hgapp.m8.personpage.withdrawrecord;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;

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

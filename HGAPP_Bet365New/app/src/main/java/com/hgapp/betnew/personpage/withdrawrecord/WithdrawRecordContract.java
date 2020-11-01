package com.hgapp.betnew.personpage.withdrawrecord;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;

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

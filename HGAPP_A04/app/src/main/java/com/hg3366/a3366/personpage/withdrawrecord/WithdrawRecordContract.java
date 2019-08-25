package com.hg3366.a3366.personpage.withdrawrecord;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;

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

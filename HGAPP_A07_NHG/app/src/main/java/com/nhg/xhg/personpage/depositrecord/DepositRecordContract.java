package com.nhg.xhg.personpage.depositrecord;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.RecordResult;

public interface DepositRecordContract {
    public interface Presenter extends IPresenter
    {
        public void getDepositRecord(String appRefer,String thistype,String page,String type_status,String date_start,String date_end);

    }
    public interface View extends IView<DepositRecordContract.Presenter>,IMessageView,IProgressView
    {
        public void postDepositRecordResult(RecordResult message);
    }
}

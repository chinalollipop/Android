package com.hgapp.a0086.personpage.depositrecord;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.RecordResult;

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

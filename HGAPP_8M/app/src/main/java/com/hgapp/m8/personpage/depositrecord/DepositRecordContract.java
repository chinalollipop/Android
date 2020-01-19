package com.hgapp.m8.personpage.depositrecord;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.RecordResult;

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

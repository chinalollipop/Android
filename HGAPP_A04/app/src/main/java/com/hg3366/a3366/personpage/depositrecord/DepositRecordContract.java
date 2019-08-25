package com.hg3366.a3366.personpage.depositrecord;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.RecordResult;

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

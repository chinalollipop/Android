package com.sands.corp.personpage.flowingrecord;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.FlowingRecordResult;

public interface FlowingRecordContract {
    public interface Presenter extends IPresenter
    {
        public void postFlowingToday(String appRefer, String gtype, String page);

        public void postFlowingHistory(String appRefer, String gtype, String page);
    }
    public interface View extends IView<FlowingRecordContract.Presenter>,IMessageView,IProgressView
    {
        public void postFlowingRecordResult(FlowingRecordResult message);
    }
}

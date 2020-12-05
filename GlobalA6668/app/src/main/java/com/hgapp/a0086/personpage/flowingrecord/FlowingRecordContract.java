package com.hgapp.a0086.personpage.flowingrecord;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.FlowingRecordResult;

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

package com.hgapp.bet365.personpage.flowingrecord;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.FlowingRecordResult;

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

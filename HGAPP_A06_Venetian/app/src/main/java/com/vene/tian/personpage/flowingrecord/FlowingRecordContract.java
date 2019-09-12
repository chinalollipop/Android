package com.vene.tian.personpage.flowingrecord;

import com.vene.tian.base.IMessageView;
import com.vene.tian.base.IPresenter;
import com.vene.tian.base.IProgressView;
import com.vene.tian.base.IView;
import com.vene.tian.data.FlowingRecordResult;

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

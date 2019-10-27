package com.hg3366.a3366.personpage.betrecord;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.BetRecordResult;

public interface BetRecordContract {
    public interface Presenter extends IPresenter
    {
        public void postBetRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
        public void postBetAGDZRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
        public void postBetODZRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
    }
    public interface View extends IView<BetRecordContract.Presenter>,IMessageView,IProgressView
    {
        public void postBetRecordResult(BetRecordResult message);
    }
}

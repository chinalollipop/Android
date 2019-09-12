package com.venen.tian.personpage.betrecord;

import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.BetRecordResult;

public interface BetRecordContract {
    public interface Presenter extends IPresenter
    {
        public void postBetRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
    }
    public interface View extends IView<BetRecordContract.Presenter>,IMessageView,IProgressView
    {
        public void postBetRecordResult(BetRecordResult message);
    }
}

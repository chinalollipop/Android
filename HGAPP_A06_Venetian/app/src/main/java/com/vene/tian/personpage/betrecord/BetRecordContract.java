package com.vene.tian.personpage.betrecord;

import com.vene.tian.base.IMessageView;
import com.vene.tian.base.IPresenter;
import com.vene.tian.base.IProgressView;
import com.vene.tian.base.IView;
import com.vene.tian.data.BetRecordResult;

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

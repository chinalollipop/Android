package com.hgapp.a6668.personpage.betrecord;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.BetRecordResult;

public interface BetRecordContract {
    public interface Presenter extends IPresenter
    {
        public void postBetRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
        public void postBetCPRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
        public void postBetAGDZRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
        public void postBetODZRecordList(String appRefer, String gtype , String Checked, String Cancel, String date_start , String date_end, String page);
    }
    public interface View extends IView<BetRecordContract.Presenter>,IMessageView,IProgressView
    {
        public void postBetRecordResult(BetRecordResult message);
    }
}

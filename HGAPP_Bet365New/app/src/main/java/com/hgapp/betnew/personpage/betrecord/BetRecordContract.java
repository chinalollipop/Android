package com.hgapp.betnew.personpage.betrecord;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.BetRecordResult;

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

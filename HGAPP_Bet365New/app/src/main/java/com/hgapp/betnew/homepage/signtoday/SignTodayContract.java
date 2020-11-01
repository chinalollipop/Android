package com.hgapp.betnew.homepage.signtoday;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.ReceiveSignTidayResults;
import com.hgapp.betnew.data.SignTodayResults;

public interface SignTodayContract {

    public interface Presenter extends IPresenter
    {
        public void postSignTodayCheck(String appRefer, String action);
        public void postSignTodaySign(String appRefer, String action);
        public void postSignTodayReceive(String appRefer, String action);
    }
    public interface View extends IView<Presenter>,IMessageView
    {
        public void postSignTodayCheckResult(SignTodayResults signTodayResults);
        public void postSignTodayReceiveResult(ReceiveSignTidayResults receiveSignTidayResults);
    }

}

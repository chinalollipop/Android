package com.hgapp.betnhg.homepage.signtoday;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.ReceiveSignTidayResults;
import com.hgapp.betnhg.data.SignTodayResults;

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

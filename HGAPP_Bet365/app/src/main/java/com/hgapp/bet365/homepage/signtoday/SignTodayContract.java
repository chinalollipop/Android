package com.hgapp.bet365.homepage.signtoday;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.ReceiveSignTidayResults;
import com.hgapp.bet365.data.SignTodayResults;

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

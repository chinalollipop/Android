package com.hgapp.m8.homepage.signtoday;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.ReceiveSignTidayResults;
import com.hgapp.m8.data.SignTodayResults;

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

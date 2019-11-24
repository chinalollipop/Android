package com.hgapp.a6668.homepage.signtoday;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.DownAppGiftResult;
import com.hgapp.a6668.data.LuckGiftResult;
import com.hgapp.a6668.data.PersonBalanceResult;
import com.hgapp.a6668.data.SignTodayResults;
import com.hgapp.a6668.data.ValidResult;

public interface SignTodayContract {

    public interface Presenter extends IPresenter
    {
        public void postSignTodayCheck(String appRefer, String action);
        public void postSignTodayReceive(String appRefer, String action);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {
        public void postSignTodayCheckResult(SignTodayResults signTodayResults);
        public void postSignTodayReceiveResult(SignTodayResults signTodayResults);
    }

}

package com.hg3366.a3366.homepage.signtoday;


import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.ReceiveSignTidayResults;
import com.hg3366.a3366.data.SignTodayResults;

public interface SignTodayContract {

    public interface Presenter extends IPresenter
    {
        public void postSignTodayCheck(String appRefer, String action);
        public void postSignTodaySign(String appRefer, String action);
        public void postSignTodayReceive(String appRefer, String action);
    }
    public interface View extends IView<Presenter>, IMessageView
    {
        public void postSignTodayCheckResult(SignTodayResults signTodayResults);
        public void postSignTodayReceiveResult(ReceiveSignTidayResults receiveSignTidayResults);
    }

}

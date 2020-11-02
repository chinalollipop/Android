package com.hgapp.betnhg.personpage.onlineservice;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.OnlineServiceResult;

public interface OnLineServiceContract {
    public interface Presenter extends IPresenter
    {
        public void getOnlineService(String appRefer);

    }
    public interface View extends IView<OnLineServiceContract.Presenter>,IMessageView,IProgressView
    {
        public void postOnlineServiceResult(OnlineServiceResult message);
    }
}

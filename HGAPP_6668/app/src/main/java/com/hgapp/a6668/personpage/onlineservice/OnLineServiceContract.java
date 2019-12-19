package com.hgapp.a6668.personpage.onlineservice;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.OnlineServiceResult;

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

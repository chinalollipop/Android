package com.hgapp.m8.personpage.onlineservice;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.OnlineServiceResult;

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

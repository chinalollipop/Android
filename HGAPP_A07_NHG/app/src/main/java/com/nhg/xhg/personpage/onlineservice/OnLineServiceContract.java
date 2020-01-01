package com.nhg.xhg.personpage.onlineservice;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.OnlineServiceResult;

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

package com.sands.corp.personpage.onlineservice;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.OnlineServiceResult;

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

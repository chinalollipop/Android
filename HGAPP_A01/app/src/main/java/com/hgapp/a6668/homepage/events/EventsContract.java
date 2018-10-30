package com.hgapp.a6668.homepage.events;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.DownAppGiftResult;

public interface EventsContract {

    public interface Presenter extends IPresenter
    {
        public void postDownAppGift(String appRefer);
    }
    public interface View extends IView<Presenter>,IMessageView,IProgressView
    {
        public void postDownAppGiftResult(String data);
    }

}

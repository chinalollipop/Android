package com.hgapp.a0086.homepage.cplist;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.AGGameLoginResult;
import com.hgapp.a0086.data.AGLiveResult;
import com.hgapp.a0086.data.CPNoteResult;
import com.hgapp.a0086.data.CheckAgLiveResult;
import com.hgapp.a0086.data.PersonBalanceResult;

public interface CPListContract {

    public interface Presenter extends IPresenter
    {
        public void postCPLogin(String path);
        public void postCPNote(String token);
    }
    public interface View extends IView<CPListContract.Presenter>,IMessageView,IProgressView
    {
        public void postCPNoteResult(CPNoteResult cpNoteResult);

    }

}

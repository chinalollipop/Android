package com.hgapp.bet365.homepage.cplist;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.CPNoteResult;

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

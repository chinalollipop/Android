package com.hgapp.betnew.homepage.cplist;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.CPNoteResult;

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

package com.hgapp.betnhg.homepage.cplist;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.CPNoteResult;

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

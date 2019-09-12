package com.sands.corp.homepage.cplist;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.CPNoteResult;

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

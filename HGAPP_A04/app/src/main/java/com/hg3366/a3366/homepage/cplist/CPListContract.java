package com.hg3366.a3366.homepage.cplist;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.CPNoteResult;

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

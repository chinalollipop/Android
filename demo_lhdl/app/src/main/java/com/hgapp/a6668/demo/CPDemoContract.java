package com.hgapp.a6668.demo;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.CPNoteResult;

public interface CPDemoContract {

    public interface Presenter extends IPresenter
    {
        public void postCPLogin(String path);
        public void postCPInit();
    }
    public interface View extends IView<CPDemoContract.Presenter>,IMessageView,IProgressView
    {
        public void postCPNoteResult(CPNoteResult cpNoteResult);

    }

}

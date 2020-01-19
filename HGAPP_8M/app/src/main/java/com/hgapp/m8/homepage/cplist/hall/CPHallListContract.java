package com.hgapp.m8.homepage.cplist.hall;

import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.CPHallResult;
import com.hgapp.m8.data.CPLeftInfoResult;

public interface CPHallListContract {

    public interface Presenter extends IPresenter
    {
        public void postCPLeftInfo(String appRefer);
        public void postCPHallList(String appRefer);
    }
    public interface View extends IView<CPHallListContract.Presenter>,IMessageView,IProgressView
    {
        public void postCPHallListResult(CPHallResult cpHallResult);
        public void postCPLeftInfoResult(CPLeftInfoResult cpLeftInfoResult);
    }

}

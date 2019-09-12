package com.sands.corp.homepage.cplist.hall;

import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.CPHallResult;
import com.sands.corp.data.CPLeftInfoResult;

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

package com.hgapp.a6668.homepage.cplist.hall;

import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.AGGameLoginResult;
import com.hgapp.a6668.data.AGLiveResult;
import com.hgapp.a6668.data.CPHallResult;
import com.hgapp.a6668.data.CPLeftInfoResult;
import com.hgapp.a6668.data.CheckAgLiveResult;
import com.hgapp.a6668.data.PersonBalanceResult;

import java.util.List;

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

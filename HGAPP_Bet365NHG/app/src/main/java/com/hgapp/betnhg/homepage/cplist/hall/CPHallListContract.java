package com.hgapp.betnhg.homepage.cplist.hall;

import com.hgapp.betnhg.base.IMessageView;
import com.hgapp.betnhg.base.IPresenter;
import com.hgapp.betnhg.base.IProgressView;
import com.hgapp.betnhg.base.IView;
import com.hgapp.betnhg.data.CPHallResult;
import com.hgapp.betnhg.data.CPLeftInfoResult;

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

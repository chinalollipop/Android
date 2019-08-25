package com.hg3366.a3366.homepage.cplist.hall;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.CPHallResult;
import com.hg3366.a3366.data.CPLeftInfoResult;

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

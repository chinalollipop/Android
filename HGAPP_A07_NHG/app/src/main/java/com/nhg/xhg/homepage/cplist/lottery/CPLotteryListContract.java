package com.nhg.xhg.homepage.cplist.lottery;

import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.CPLotteryListResult;

public interface CPLotteryListContract {

    public interface Presenter extends IPresenter
    {
        public void postCPLotteryList(String data);
    }
    public interface View extends IView<CPLotteryListContract.Presenter>,IMessageView,IProgressView
    {
        public void postCPLotteryListResult(CPLotteryListResult cpLotteryListResult);
    }

}

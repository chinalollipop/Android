package com.hgapp.a0086.homepage.cplist.lottery;

import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.CPLotteryListResult;

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

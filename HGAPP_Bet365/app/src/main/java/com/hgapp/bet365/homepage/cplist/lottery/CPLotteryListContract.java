package com.hgapp.bet365.homepage.cplist.lottery;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.CPLotteryListResult;

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

package com.hgapp.betnew.homepage.cplist.lottery;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.CPLotteryListResult;

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

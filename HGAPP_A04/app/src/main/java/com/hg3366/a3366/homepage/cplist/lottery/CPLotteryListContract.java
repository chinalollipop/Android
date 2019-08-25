package com.hg3366.a3366.homepage.cplist.lottery;

import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.CPLotteryListResult;

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

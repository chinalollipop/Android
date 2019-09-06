package com.cfcp.a01.ui.home.cplist.lottery;


import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.CPLotteryListResult;

public interface CPLotteryListContract {

    public interface Presenter extends IPresenter
    {
        public void postCPLotteryList(String dataStr,String data);
    }
    public interface View extends IView<Presenter>, IMessageView
    {
        public void postCPLotteryListResult(CPLotteryListResult cpLotteryListResult);
    }

}

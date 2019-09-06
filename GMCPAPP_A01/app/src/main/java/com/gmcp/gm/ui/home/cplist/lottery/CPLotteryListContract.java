package com.gmcp.gm.ui.home.cplist.lottery;


import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CPLotteryListResult;

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

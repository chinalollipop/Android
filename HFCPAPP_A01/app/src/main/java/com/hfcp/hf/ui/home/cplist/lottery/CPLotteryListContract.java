package com.hfcp.hf.ui.home.cplist.lottery;


import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CPLotteryListResult;

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

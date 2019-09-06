package com.hfcp.hf.ui.main.upgrade;


import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CheckUpgradeResult;

/**
 * Created by Daniel on 2019/7/29.
 */

public interface CheckUpdateContract {

    public interface Presenter extends IPresenter
    {
        public void checkupdate();
    }

    public interface View extends IView<Presenter>, IMessageView
    {
        public void wantShowMessage(CheckUpgradeResult checkUpgradeResult);
    }
}

package com.gmcp.gm.ui.main.upgrade;


import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CheckUpgradeResult;

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

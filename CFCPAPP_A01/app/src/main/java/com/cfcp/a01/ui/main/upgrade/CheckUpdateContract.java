package com.cfcp.a01.ui.main.upgrade;


import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.CheckUpgradeResult;

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

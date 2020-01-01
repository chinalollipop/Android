package com.nhg.xhg.upgrade;

import com.nhg.xhg.base.DataAware;
import com.nhg.xhg.base.IMessageView;
import com.nhg.xhg.base.IPresenter;
import com.nhg.xhg.base.IProgressView;
import com.nhg.xhg.base.IView;
import com.nhg.xhg.data.CheckUpgradeResult;

/**
 * Created by Daniel on 2018/7/29.
 */

public interface CheckUpdateContract {

    public interface Presenter extends IPresenter
    {
        int ACTION=444;
        public void checkupdate();
    }

    public interface View extends IView<Presenter>,DataAware<CheckUpgradeResult>,IProgressView,IMessageView
    {
        public boolean wantShowMessage();
    }
}

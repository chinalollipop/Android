package com.venen.tian.upgrade;

import com.venen.tian.base.DataAware;
import com.venen.tian.base.IMessageView;
import com.venen.tian.base.IPresenter;
import com.venen.tian.base.IProgressView;
import com.venen.tian.base.IView;
import com.venen.tian.data.CheckUpgradeResult;

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

package com.hgapp.a0086.upgrade;

import com.hgapp.a0086.base.DataAware;
import com.hgapp.a0086.base.IMessageView;
import com.hgapp.a0086.base.IPresenter;
import com.hgapp.a0086.base.IProgressView;
import com.hgapp.a0086.base.IView;
import com.hgapp.a0086.data.CheckUpgradeResult;

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

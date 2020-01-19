package com.hgapp.m8.upgrade;

import com.hgapp.m8.base.DataAware;
import com.hgapp.m8.base.IMessageView;
import com.hgapp.m8.base.IPresenter;
import com.hgapp.m8.base.IProgressView;
import com.hgapp.m8.base.IView;
import com.hgapp.m8.data.CheckUpgradeResult;

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

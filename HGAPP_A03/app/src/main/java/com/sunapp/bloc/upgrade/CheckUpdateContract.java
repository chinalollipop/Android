package com.sunapp.bloc.upgrade;

import com.sunapp.bloc.base.DataAware;
import com.sunapp.bloc.base.IMessageView;
import com.sunapp.bloc.base.IPresenter;
import com.sunapp.bloc.base.IProgressView;
import com.sunapp.bloc.base.IView;
import com.sunapp.bloc.data.CheckUpgradeResult;

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

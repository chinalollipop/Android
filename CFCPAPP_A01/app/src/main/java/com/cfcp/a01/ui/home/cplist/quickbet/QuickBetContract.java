package com.cfcp.a01.ui.home.cplist.quickbet;


import com.cfcp.a01.common.base.IPresenter;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface QuickBetContract {

    public interface Presenter extends IPresenter
    {
        public void logout();
    }

    public interface View
    {
        public void setPresenter(Presenter presenter);
    }
}

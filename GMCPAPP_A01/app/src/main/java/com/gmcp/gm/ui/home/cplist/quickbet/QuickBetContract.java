package com.gmcp.gm.ui.home.cplist.quickbet;


import com.gmcp.gm.common.base.IPresenter;

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

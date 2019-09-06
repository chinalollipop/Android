package com.hfcp.hf.ui.home.cplist.quickbet;


import com.hfcp.hf.common.base.IPresenter;

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

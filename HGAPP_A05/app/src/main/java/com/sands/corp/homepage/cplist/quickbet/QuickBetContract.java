package com.sands.corp.homepage.cplist.quickbet;


import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface QuickBetContract {

    public interface Presenter extends IPresenter
    {
        public void logout();
    }

    public interface View extends IProgressView
    {
        public void setPresenter(Presenter presenter);
    }
}

package com.hg3366.a3366.homepage.cplist.quickbet;


import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;

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

package com.hgapp.betnew.homepage.cplist.quickbet;


import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;

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

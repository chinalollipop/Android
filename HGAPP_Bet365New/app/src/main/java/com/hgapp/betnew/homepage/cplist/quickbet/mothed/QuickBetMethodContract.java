package com.hgapp.betnew.homepage.cplist.quickbet.mothed;


import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.CPQuickBetMothedResult;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface QuickBetMethodContract {

    public interface Presenter extends IPresenter
    {
        public void postQuickBetMothed(String code, String gamecode, String code_number, String sort, String token);
    }

    public interface View extends IView<QuickBetMethodContract.Presenter>,IProgressView,IMessageView
    {
        public void postQuickBetMothedResult(CPQuickBetMothedResult cpQuickBetMothedResult);

    }
}

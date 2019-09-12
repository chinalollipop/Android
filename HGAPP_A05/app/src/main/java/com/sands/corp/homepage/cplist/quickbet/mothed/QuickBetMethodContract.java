package com.sands.corp.homepage.cplist.quickbet.mothed;


import com.sands.corp.base.IMessageView;
import com.sands.corp.base.IPresenter;
import com.sands.corp.base.IProgressView;
import com.sands.corp.base.IView;
import com.sands.corp.data.CPQuickBetMothedResult;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface QuickBetMethodContract {

    public interface Presenter extends IPresenter
    {
        public void postQuickBetMothed(String code,String gamecode,String code_number,String sort,String token);
    }

    public interface View extends IView<QuickBetMethodContract.Presenter>,IProgressView,IMessageView
    {
        public void postQuickBetMothedResult(CPQuickBetMothedResult cpQuickBetMothedResult);

    }
}

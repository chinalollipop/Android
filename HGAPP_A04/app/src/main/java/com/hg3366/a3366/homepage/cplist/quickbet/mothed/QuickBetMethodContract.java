package com.hg3366.a3366.homepage.cplist.quickbet.mothed;


import com.hg3366.a3366.base.IMessageView;
import com.hg3366.a3366.base.IPresenter;
import com.hg3366.a3366.base.IProgressView;
import com.hg3366.a3366.base.IView;
import com.hg3366.a3366.data.CPQuickBetMothedResult;

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

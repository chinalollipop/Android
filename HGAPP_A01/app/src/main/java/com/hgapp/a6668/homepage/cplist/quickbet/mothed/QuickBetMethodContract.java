package com.hgapp.a6668.homepage.cplist.quickbet.mothed;


import com.hgapp.a6668.base.IMessageView;
import com.hgapp.a6668.base.IPresenter;
import com.hgapp.a6668.base.IProgressView;
import com.hgapp.a6668.base.IView;
import com.hgapp.a6668.data.CPQuickBetMothedResult;
import com.hgapp.a6668.homepage.cplist.order.CPOrderContract;

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

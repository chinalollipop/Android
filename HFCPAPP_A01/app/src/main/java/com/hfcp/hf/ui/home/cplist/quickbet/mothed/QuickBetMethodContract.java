package com.hfcp.hf.ui.home.cplist.quickbet.mothed;


import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.CPQuickBetMothedResult;

/**
 * Created by Daniel on 2017/5/31.
 */

public interface QuickBetMethodContract {

    public interface Presenter extends IPresenter
    {
        public void postQuickBetMothed(String code, String gamecode, String code_number, String sort, String token);
    }

    public interface View extends IView<Presenter>,IMessageView
    {
        public void postQuickBetMothedResult(CPQuickBetMothedResult cpQuickBetMothedResult);

    }
}

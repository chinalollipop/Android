package com.gmcp.gm.ui.home.cplist.quickbet.mothed;


import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.CPQuickBetMothedResult;

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

package com.hfcp.hf.ui.me.info;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.LoginResult;
import com.hfcp.hf.data.LowerInfoDataResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface InfoContract {

    interface Presenter extends IPresenter {

        void getLowerLevelReport(String user_id);
        void getRealName(String mobile,String name,String email,String qq);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getRealNameResult(LoginResult loginResult);
        void getLowerLevelReportResult(LowerInfoDataResult lowerInfoDataResult);
    }
}

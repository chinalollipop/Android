package com.gmcp.gm.ui.me.userlist.setprize;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.LoginResult;
import com.gmcp.gm.data.LowerSetDataResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface SetPrizeContract {

    interface Presenter extends IPresenter {

        void getLowerLevelReport(String user_id);
        void getRealName(String user_id, String name, String email, String qq);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getRealNameResult(LoginResult loginResult);
        void getLowerLevelReportResult(LowerSetDataResult lowerSetDataResult);
    }
}

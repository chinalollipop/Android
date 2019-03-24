package com.cfcp.a01.ui.me.userlist.setprize;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.LowerInfoDataResult;
import com.cfcp.a01.data.LowerSetDataResult;

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

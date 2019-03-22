package com.cfcp.a01.ui.me.pwd;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.TeamReportResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface PwdContract {

    interface Presenter extends IPresenter {

        void getChangeFundPwdFirst(String fund_password, String confirm_fund_password);
        void getChangeFundPwd(String current_password, String new_password);
        void getChangeLoginPwd(String current_password, String new_password);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getChangeFundPwdResult(TeamReportResult teamReportResult);
        void getChangeLoginPwdResult(TeamReportResult teamReportResult);
    }
}

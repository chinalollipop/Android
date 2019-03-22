package com.cfcp.a01.ui.me.bankcard;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.TeamReportResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface ModifyContract {

    interface Presenter extends IPresenter {

        void getCardModify(String id, String account_name,String account, String fund_password);
        void getCardVerify(String id, String account_name,String account, String fund_password);
        void getCardDelete(String id, String account_name,String account, String fund_password);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getCardModifyResult(TeamReportResult teamReportResult);
        void getCardVerifyResult(TeamReportResult teamReportResult);
        void getCardDeleteResult(TeamReportResult teamReportResult);
    }
}

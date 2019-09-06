package com.gmcp.gm.ui.me.bankcard;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.TeamReportResult;

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

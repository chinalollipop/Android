package com.cfcp.a01.ui.me.report;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.LoginResult;
import com.cfcp.a01.data.PersonReportResult;
import com.cfcp.a01.data.TeamReportResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface TeamContract {

    interface Presenter extends IPresenter {
        void getPersonReport(String begin_date, String end_date);
        void getTeamReport(String user_id,String begin_date, String end_date);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getTeamReportResult(TeamReportResult teamReportResult);
    }
}

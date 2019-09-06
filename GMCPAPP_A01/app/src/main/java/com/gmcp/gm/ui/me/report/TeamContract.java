package com.gmcp.gm.ui.me.report;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.TeamReportResult;

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

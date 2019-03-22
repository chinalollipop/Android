package com.cfcp.a01.ui.me.report.myreport;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.MyReportResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface MyReportContract {

    interface Presenter extends IPresenter {

        void getPersonReport(String user_id,String type_id,String begin_date, String end_date,String page,String pagesize);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getPersonReportResult(MyReportResult myReportResult);
    }
}

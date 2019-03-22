package com.cfcp.a01.ui.me.emailbox;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.EmailBoxListResult;
import com.cfcp.a01.data.PersonReportResult;

import java.util.List;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface EmailBoxContract {

    interface Presenter extends IPresenter {

        void getPersonReport(String begin_date, String end_date);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getPersonReportResult(List<EmailBoxListResult> emailBoxListResult);
    }
}

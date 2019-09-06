package com.hfcp.hf.ui.me.emailbox;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.EmailBoxListResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface EmailBoxContract {

    interface Presenter extends IPresenter {

        void getPersonReport(String begin_date, String end_date);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getPersonReportResult(EmailBoxListResult emailBoxListResult);
    }
}

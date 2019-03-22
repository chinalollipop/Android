package com.cfcp.a01.ui.me.record;

import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BetRecordResult;
import com.cfcp.a01.data.LoginResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface BetContract {

    interface Presenter extends IPresenter {

        void getProjectList(String page,String pagesize,String begin_date,String end_date);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getProjectListResult(BetRecordResult betRecordResult);
    }
}

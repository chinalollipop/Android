package com.gmcp.gm.ui.me.record;

import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BetRecordResult;
import com.gmcp.gm.data.BetRecordsResult;

/**
 * Created by Daniel on 2018/12/20.
 */

public interface BetRecordContract {

    interface Presenter extends IPresenter {

        void getProjectList(String lottery_id,String page,String pagesize,String begin_date,String end_date);
        void getCpBetRecords(String lottery_id,String page,String date_start,String date_end);
    }

    interface View extends IView<Presenter>, IMessageView {

        void getProjectListResult(BetRecordResult betRecordResult);
        void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}

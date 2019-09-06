package com.hfcp.hf.ui.me.record;

import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.BetRecordResult;
import com.hfcp.hf.data.BetRecordsResult;

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

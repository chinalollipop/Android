package com.gmcp.gm.ui.home.cplist.bet.betrecords;


import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BetRecordsResult;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String lottery_id,String page,String date_start,String date_end);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}

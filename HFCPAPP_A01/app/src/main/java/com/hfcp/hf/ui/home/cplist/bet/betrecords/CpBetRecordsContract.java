package com.hfcp.hf.ui.home.cplist.bet.betrecords;


import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.BetRecordsResult;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String lottery_id,String page,String date_start,String date_end);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}

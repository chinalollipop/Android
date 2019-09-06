package com.hfcp.hf.ui.home.cplist.bet.betrecords.betlistrecords;


import com.hfcp.hf.common.base.IMessageView;
import com.hfcp.hf.common.base.IPresenter;
import com.hfcp.hf.common.base.IView;
import com.hfcp.hf.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime, String from);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}

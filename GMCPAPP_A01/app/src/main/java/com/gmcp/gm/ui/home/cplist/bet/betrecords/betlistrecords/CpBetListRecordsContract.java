package com.gmcp.gm.ui.home.cplist.bet.betrecords.betlistrecords;


import com.gmcp.gm.common.base.IMessageView;
import com.gmcp.gm.common.base.IPresenter;
import com.gmcp.gm.common.base.IView;
import com.gmcp.gm.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime, String from);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}

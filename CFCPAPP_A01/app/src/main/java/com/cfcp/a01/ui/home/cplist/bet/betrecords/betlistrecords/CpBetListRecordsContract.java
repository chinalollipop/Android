package com.cfcp.a01.ui.home.cplist.bet.betrecords.betlistrecords;


import com.cfcp.a01.common.base.IMessageView;
import com.cfcp.a01.common.base.IPresenter;
import com.cfcp.a01.common.base.IView;
import com.cfcp.a01.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter {
        public void getCpBetRecords(String dataTime, String from);
    }

    public interface View extends IView<Presenter>, IMessageView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}

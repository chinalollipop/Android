package com.hgapp.bet365.homepage.cplist.bet.betrecords.betlistrecords;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime, String from);
    }

    public interface View extends IView<CpBetListRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}

package com.hgapp.betnew.homepage.cplist.bet.betrecords.betlistrecords;

import com.hgapp.betnew.base.IMessageView;
import com.hgapp.betnew.base.IPresenter;
import com.hgapp.betnew.base.IProgressView;
import com.hgapp.betnew.base.IView;
import com.hgapp.betnew.data.BetRecordsListItemResult;

public interface CpBetListRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords(String dataTime, String from);
    }

    public interface View extends IView<CpBetListRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsListItemResult betRecordsResult);
    }
}

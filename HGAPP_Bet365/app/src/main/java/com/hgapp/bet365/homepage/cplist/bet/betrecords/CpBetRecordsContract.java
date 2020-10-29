package com.hgapp.bet365.homepage.cplist.bet.betrecords;

import com.hgapp.bet365.base.IMessageView;
import com.hgapp.bet365.base.IPresenter;
import com.hgapp.bet365.base.IProgressView;
import com.hgapp.bet365.base.IView;
import com.hgapp.bet365.data.BetRecordsResult;

public interface CpBetRecordsContract {
    public interface Presenter extends IPresenter{
        public void getCpBetRecords();
    }

    public interface View extends IView<CpBetRecordsContract.Presenter>,IMessageView,IProgressView {
        public void getBetRecordsResult(BetRecordsResult betRecordsResult);
    }
}
